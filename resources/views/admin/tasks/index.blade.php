@extends('layouts.admin.master')

@section('title', 'WorkSphere — Tasks')

@section('content')
<div class="page active" id="page-tasks">
    <div class="section-header">
    <div>
        <div class="section-title">All Tasks</div>
        <div class="section-sub">29 total · 3 overdue</div>
    </div>
    <div class="section-actions">
        <button class="btn btn-primary" onclick="openCreateTaskModal()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Task
        </button>
    </div>
    </div>
    <div class="filter-bar">
    <input class="filter-input" placeholder="Search tasks…">
    <select class="filter-select"><option>All Projects</option><option>Website Redesign</option><option>Mobile App v2</option></select>
    <select class="filter-select"><option>All Status</option><option>Pending</option><option>Done</option></select>
    <select class="filter-select"><option>All Priority</option><option>High</option><option>Medium</option><option>Low</option></select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-tasks">
        <thead><tr>
            <th>Task</th><th>Project</th><th>Assigned To</th><th>Priority</th><th>Status</th><th>Due Date</th><th>Actions</th>
        </tr></thead>
        <tbody id="tasks-tbody">
            <!-- Dynamic rows will be inserted here -->
        </tbody>
    </table>

    <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
        <div class="spinner" style="border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
        <div style="color: var(--text-2); font-size: 14px;">Loading tasks...</div>
    </div>

    <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
        <div style="font-size: 24px; margin-bottom: 10px;">📝</div>
        <div style="color: var(--text-2); font-size: 14px;">No tasks found.</div>
    </div>

    <div id="table-error" style="text-align:center; padding: 40px; display: none; color: #ff4d4d;">
        <div style="font-size: 20px; margin-bottom: 10px;">⚠️</div>
        <div id="error-message" style="font-size: 14px;">Failed to load tasks.</div>
    </div>
    </div>

    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-top: 1px solid var(--border);">
        <div id="pagination-info" style="color: var(--text-2); font-size: 13px;">Showing 0 of 0 tasks</div>
        <div id="pagination-controls" style="display: flex; gap: 8px;">
            <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
            <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
        </div>
    </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal-overlay" id="create-task-modal">
        <div class="modal">
            <div class="modal-close" onclick="closeModal('create-task-modal')">✕</div>
            <div class="modal-title">New Task</div>
            <div class="modal-sub">Assign a task to a project and employee.</div>
            
            <form id="create-task-form" onsubmit="handleCreateTask(event)">
                <div class="form-group">
                    <label class="form-label">Task Title</label>
                    <input class="form-input" id="task-title" required placeholder="e.g. Fix login bug">
                </div>
                <div class="form-group">
                    <label class="form-label">Project</label>
                    <select class="form-select" id="task-project-id" required style="width:100%; border:1px solid var(--border); border-radius:8px; background:var(--bg-1); color:var(--text-1); padding:8px;">
                        <option value="">Select Project</option>
                        <!-- Loaded via JS -->
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Assign To</label>
                    <select class="form-select" id="task-assignees" multiple style="height:100px; width:100%; border:1px solid var(--border); border-radius:8px; background:var(--bg-1); color:var(--text-1); padding:8px;">
                        <!-- Loaded via JS -->
                    </select>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="task-priority" style="width:100%; border:1px solid var(--border); border-radius:8px; background:var(--bg-1); color:var(--text-1); padding:8px;">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input class="form-input" type="date" id="task-due-date" style="width:100%; border:1px solid var(--border); border-radius:8px; background:var(--bg-1); color:var(--text-1); padding:8px;">
                    </div>
                </div>
                
                <div id="modal-error" style="color: #ef4444; font-size: 13px; margin-bottom: 16px; display: none;"></div>

                <div class="modal-footer" style="padding:0; margin-top:24px;">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('create-task-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="create-task-btn">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
    let currentPage = 1;

    function openCreateTaskModal() {
        openModal('create-task-modal');
        loadDataForModal();
    }

    async function loadDataForModal() {
        const projSelect = document.getElementById('task-project-id');
        const empSelect = document.getElementById('task-assignees');
        
        if (projSelect.options.length > 1 && empSelect.options.length > 0) return;

        try {
            const [projsRes, empsRes] = await Promise.all([
                axios.get('/api/admin/projects?per_page=100'),
                axios.get('/api/admin/employees?per_page=100')
            ]);

            const projects = projsRes.data.data;
            const employees = empsRes.data.data;

            if (projSelect.options.length <= 1) {
                projects.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.name;
                    projSelect.appendChild(opt);
                });
            }

            if (empSelect.options.length === 0) {
                employees.forEach(e => {
                    const opt = document.createElement('option');
                    opt.value = e.id;
                    opt.textContent = `${e.user.name} (${e.employee_code})`;
                    empSelect.appendChild(opt);
                });
            }
        } catch (error) {
            console.error('Failed to load modal data:', error);
        }
    }

    async function handleCreateTask(e) {
        e.preventDefault();
        const btn = document.getElementById('create-task-btn');
        const errorDiv = document.getElementById('modal-error');

        const data = {
            title: document.getElementById('task-title').value,
            project_id: document.getElementById('task-project-id').value,
            priority: document.getElementById('task-priority').value,
            due_date: document.getElementById('task-due-date').value,
            status: 'pending'
        };
        const assignees = Array.from(document.getElementById('task-assignees').selectedOptions).map(o => o.value);

        btn.disabled = true;
        btn.textContent = 'Creating...';
        errorDiv.style.display = 'none';

        try {
            const response = await axios.post('/api/admin/tasks', data);
            const taskId = response.data.data.id;

            if (assignees.length > 0) {
                await axios.post(`/api/admin/tasks/${taskId}/assign-employees`, {
                    employee_ids: assignees
                });
            }

            closeModal('create-task-modal');
            fetchTasks(1);
            
            // Clear form
            document.getElementById('task-title').value = '';
            document.getElementById('task-project-id').value = '';
            document.getElementById('task-assignees').value = '';
            document.getElementById('task-due-date').value = '';

        } catch (error) {
            console.error('Task creation failed:', error);
            errorDiv.innerText = error.response?.data?.message || 'Failed to create task.';
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Create Task';
        }
    }

    async function fetchTasks(page = 1) {
        const tbody = document.getElementById('tasks-tbody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        errorDiv.style.display = 'none';
        
        try {
            const response = await axios.get(`/api/admin/tasks?page=${page}`);
            const { data, meta } = response.data;
            const tasks = data;
            const paginationMeta = meta || response.data;

            loading.style.display = 'none';

            if (!tasks || tasks.length === 0) {
                empty.style.display = 'block';
                return;
            }

            tasks.forEach(task => {
                const tr = document.createElement('tr');
                const dueDate = task.due_date ? new Date(task.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'N/A';
                
                const priorityClass = task.priority === 'high' ? 'badge-red' : (task.priority === 'medium' ? 'badge-orange' : 'badge-blue');
                const statusClass = task.status === 'completed' || task.status === 'done' ? 'badge-green' : 'badge-muted';
                const statusText = task.status.charAt(0).toUpperCase() + task.status.slice(1);

                let assigneesHtml = 'N/A';
                if (task.assignees && task.assignees.length > 0) {
                    assigneesHtml = task.assignees.map(a => `<span title="${a.name}">${a.name.split(' ')[0]}</span>`).join(', ');
                }

                tr.innerHTML = `
                    <td class="td-main">${task.title}</td>
                    <td>${task.project_name || 'N/A'}</td>
                    <td>${assigneesHtml}</td>
                    <td><span class="badge ${priorityClass}">${task.priority.toUpperCase()}</span></td>
                    <td><span class="badge ${statusClass}">${statusText}</span></td>
                    <td>${dueDate}</td>
                    <td>
                        <button class="btn btn-ghost btn-sm" onclick="alert('Viewing task ID: ${task.id}')">View</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            if (paginationMeta) {
                currentPage = paginationMeta.current_page || 1;
                info.innerText = `Showing ${paginationMeta.from || 0} to ${paginationMeta.to || 0} of ${paginationMeta.total || 0} tasks`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === (paginationMeta.last_page || 1);
            }

        } catch (error) {
            console.error('Fetch error:', error);
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = error.response?.data?.message || 'Failed to load tasks.';
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchTasks(currentPage - 1);
    });

    document.getElementById('next-page').addEventListener('click', () => {
        fetchTasks(currentPage + 1);
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchTasks();
    });
</script>
@endpush
@endsection
