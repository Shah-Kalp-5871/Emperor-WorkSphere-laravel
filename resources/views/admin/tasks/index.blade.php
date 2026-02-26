@extends('layouts.admin.master')

@section('title', 'WorkSphere — Tasks')

@section('content')
<div class="page active" id="page-tasks">
    <div class="section-header">
        <div>
            <div class="section-title">Tasks</div>
            <div class="section-sub" id="tasks-summary">Loading...</div>
        </div>
        <div class="section-actions">
            <a href="{{ url('/admin/tasks/archived') }}" class="btn btn-ghost btn-sm" style="margin-right:8px;">
                🗃 Archived
            </a>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Task
            </button>
        </div>
    </div>

    {{-- Create Task Modal --}}
    <div class="modal-overlay" id="create-task-modal">
        <div class="modal">
            <div class="modal-close" onclick="closeModal('create-task-modal')">✕</div>
            <div class="modal-title">New Task</div>
            <div class="modal-sub">Assign a new task to your team.</div>
            <form id="create-task-form" onsubmit="handleCreateTask(event)">
                <div class="form-group">
                    <label class="form-label">Project *</label>
                    <select class="form-select" id="task-project-id" required style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;"></select>
                </div>
                <div class="form-group">
                    <label class="form-label">Task Title *</label>
                    <input class="form-input" id="task-title" required placeholder="e.g. Design Landing Page">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input" id="task-desc" rows="2" placeholder="Task details…" style="resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="task-status" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="task-priority" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" class="form-input" id="task-due-date">
                </div>
                <div class="form-group">
                    <label class="form-label">Assign To</label>
                    <select class="form-select" id="task-assignees-select" multiple style="height:100px;width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;"></select>
                    <small style="color:var(--text-3);font-size:11px;margin-top:4px;display:block;">Hold Ctrl/Cmd to select multiple</small>
                </div>
                <div id="modal-error" style="color:#ef4444;font-size:13px;margin-bottom:16px;display:none;"></div>
                <div class="modal-footer" style="padding:0;margin-top:24px;">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('create-task-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="create-task-btn">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Task Modal --}}
    <div class="modal-overlay" id="edit-task-modal">
        <div class="modal">
            <div class="modal-close" onclick="closeModal('edit-task-modal')">✕</div>
            <div class="modal-title">Edit Task</div>
            <form id="edit-task-form" onsubmit="handleEditTask(event)">
                <input type="hidden" id="edit-task-id">
                <div class="form-group">
                    <label class="form-label">Task Title *</label>
                    <input class="form-input" id="edit-task-title" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input" id="edit-task-desc" rows="2" style="resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="edit-task-status" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="edit-task-priority" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Due Date</label>
                    <input type="date" class="form-input" id="edit-task-due-date">
                </div>
                <div id="edit-modal-error" style="color:#ef4444;font-size:13px;margin-bottom:16px;display:none;"></div>
                <div class="modal-footer" style="padding:0;margin-top:24px;">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('edit-task-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="edit-task-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="filter-bar" style="display:flex;gap:12px;">
        <input class="filter-input" id="search-input" placeholder="Search tasks…" oninput="debounceSearch()" style="flex:1;">
        <select class="filter-select" id="project-filter" onchange="fetchTasks(1)" style="width:200px;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
            <option value="">All Projects</option>
        </select>
        <select class="filter-select" id="status-filter" onchange="fetchTasks(1)" style="width:150px;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="on_hold">On Hold</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table id="tbl-tasks">
                <thead><tr>
                    <th>Task</th><th>Project</th><th>Status</th><th>Priority</th><th>Assignees</th><th>Due Date</th><th>Actions</th>
                </tr></thead>
                <tbody id="tasks-tbody"></tbody>
            </table>

            <div id="table-loading" style="text-align:center;padding:40px;display:none;">
                <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:30px;height:30px;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
                <div style="color:var(--text-2);font-size:14px;">Loading tasks...</div>
            </div>

            <div id="table-empty" style="text-align:center;padding:40px;display:none;">
                <div style="font-size:24px;margin-bottom:10px;">📋</div>
                <div style="color:var(--text-2);font-size:14px;">No tasks found.</div>
            </div>

            <div id="table-error" style="text-align:center;padding:40px;display:none;color:#ff4d4d;">
                <div style="font-size:20px;margin-bottom:10px;">⚠️</div>
                <div id="error-message" style="font-size:14px;">Failed to load tasks.</div>
            </div>
        </div>

        <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-top:1px solid var(--border);">
            <div id="pagination-info" style="color:var(--text-2);font-size:13px;">Showing 0 of 0 tasks</div>
            <div id="pagination-controls" style="display:flex;gap:8px;">
                <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
                <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .status-pill { display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.03em; }
    .t-pending   { background:#f59e0b20;color:#f59e0b; }
    .t-in_progress { background:#3b82f620;color:#3b82f6; }
    .t-on_hold   { background:#6b728020;color:#6b7280; }
    .t-completed { background:#22c55e20;color:#22c55e; }
    .p-urgent    { background:#ef444420;color:#ef4444; }
    .p-high      { background:#f59e0b20;color:#f59e0b; }
    .p-medium    { background:#3b82f620;color:#3b82f6; }
    .p-low       { background:#6b728020;color:#6b7280; }
</style>

@push('scripts')
<script>
    let currentPage = 1;
    let searchTimeout = null;

    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchTasks(1), 400);
    }

    function statusClass(s) {
        const m = {pending:'t-pending', in_progress:'t-in_progress', on_hold:'t-on_hold', completed:'t-completed'};
        return m[s] || 't-pending';
    }

    function priorityClass(p) {
        const m = {urgent:'p-urgent', high:'p-high', medium:'p-medium', low:'p-low'};
        return m[p] || 'p-medium';
    }

    async function loadSelectData() {
        try {
            const [projRes, empRes] = await Promise.all([
                axios.get('/api/admin/projects?per_page=100'),
                axios.get('/api/admin/employees?per_page=100')
            ]);

            const projects = projRes.data.data?.data ?? [];
            const employees = empRes.data.data?.data ?? [];

            const projFilter = document.getElementById('project-filter');
            const projSelect = document.getElementById('task-project-id');
            const empSelect = document.getElementById('task-assignees-select');

            projects.forEach(p => {
                const opt = new Option(p.name, p.id);
                projFilter.appendChild(opt.cloneNode(true));
                projSelect.appendChild(opt);
            });

            employees.forEach(e => {
                const opt = new Option(`${e.user?.name ?? 'Unknown'} (${e.employee_code})`, e.id);
                empSelect.appendChild(opt);
            });
        } catch (e) {
            console.error('Failed to load select data:', e);
        }
    }

    function openCreateModal() {
        openModal('create-task-modal');
    }

    async function handleCreateTask(e) {
        e.preventDefault();
        const btn = document.getElementById('create-task-btn');
        const errorDiv = document.getElementById('modal-error');
        errorDiv.style.display = 'none';
        btn.disabled = true;

        const assignee_ids = Array.from(document.getElementById('task-assignees-select').selectedOptions).map(o => parseInt(o.value));

        try {
            await axios.post('/api/admin/tasks', {
                project_id:  document.getElementById('task-project-id').value,
                title:       document.getElementById('task-title').value,
                description: document.getElementById('task-desc').value,
                status:      document.getElementById('task-status').value,
                priority:    document.getElementById('task-priority').value,
                due_date:    document.getElementById('task-due-date').value,
                assignee_ids
            });
            closeModal('create-task-modal');
            document.getElementById('create-task-form').reset();
            fetchTasks(1);
        } catch (err) {
            errorDiv.innerText = err.response?.data?.message || 'Failed to create task.';
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
        }
    }

    async function openEditModal(id) {
        openModal('edit-task-modal');
        document.getElementById('edit-task-id').value = id;
        try {
            const res = await axios.get(`/api/admin/tasks/${id}`);
            const t = res.data.data;
            document.getElementById('edit-task-title').value = t.title;
            document.getElementById('edit-task-desc').value = t.description || '';
            document.getElementById('edit-task-status').value = t.status;
            document.getElementById('edit-task-priority').value = t.priority;
            document.getElementById('edit-task-due-date').value = t.due_date || '';
        } catch (e) {
            alert('Failed to load task data.');
            closeModal('edit-task-modal');
        }
    }

    async function handleEditTask(e) {
        e.preventDefault();
        const id = document.getElementById('edit-task-id').value;
        const btn = document.getElementById('edit-task-btn');
        const errorDiv = document.getElementById('edit-modal-error');
        errorDiv.style.display = 'none';
        btn.disabled = true;

        try {
            await axios.put(`/api/admin/tasks/${id}`, {
                title:       document.getElementById('edit-task-title').value,
                description: document.getElementById('edit-task-desc').value,
                status:      document.getElementById('edit-task-status').value,
                priority:    document.getElementById('edit-task-priority').value,
                due_date:    document.getElementById('edit-task-due-date').value
            });
            closeModal('edit-task-modal');
            fetchTasks(currentPage);
        } catch (err) {
            errorDiv.innerText = err.response?.data?.message || 'Failed to update task.';
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
        }
    }

    async function updateStatus(id, newStatus) {
        try {
            await axios.patch(`/api/admin/tasks/${id}/status`, { status: newStatus });
            fetchTasks(currentPage);
        } catch (err) {
            alert(err.response?.data?.message || 'Failed to update status.');
        }
    }

    async function archiveTask(id, title) {
        if (!confirm(`Archive task "${title}"?`)) return;
        try {
            await axios.delete(`/api/admin/tasks/${id}`);
            fetchTasks(currentPage);
        } catch (err) {
            alert(err.response?.data?.message || 'Failed to archive task.');
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

        const status = document.getElementById('status-filter').value;
        const projectId = document.getElementById('project-filter').value;
        const search = document.getElementById('search-input').value;

        try {
            const params = new URLSearchParams({ page });
            if (status) params.append('status', status);
            if (projectId) params.append('project_id', projectId);
            if (search) params.append('search', search);

            const res = await axios.get(`/api/admin/tasks?${params}`);
            const data = res.data.data;
            const tasks = data.data ?? [];
            const meta = data.meta ?? {};

            loading.style.display = 'none';
            document.getElementById('tasks-summary').textContent = `${meta.total ?? tasks.length} total tasks`;

            if (tasks.length === 0) { empty.style.display = 'block'; return; }

            tasks.forEach(t => {
                let assigneesHtml = '';
                (t.assignees ?? []).slice(0, 3).forEach((a, i) => {
                    assigneesHtml += `<div class="av${i>0?' av'+(i+1):''}">${(a.name[0]||'?').toUpperCase()}</div>`;
                });
                if ((t.assignees ?? []).length > 3) assigneesHtml += `<div class="av av4">+${t.assignees.length - 3}</div>`;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="td-main">
                        <a href="/admin/tasks/${t.id}" style="color:inherit;text-decoration:none;font-weight:600;">${t.title}</a>
                        ${t.description ? `<div style="font-size:11px;color:var(--text-3);margin-top:2px;">${t.description.substring(0,40)}...</div>` : ''}
                    </td>
                    <td>${t.project_name || 'N/A'}</td>
                    <td><span class="status-pill ${statusClass(t.status)}">${t.status.replace('_',' ')}</span></td>
                    <td><span class="status-pill ${priorityClass(t.priority)}">${t.priority}</span></td>
                    <td><div class="avatar-group">${assigneesHtml || '—'}</div></td>
                    <td>${t.due_date || '—'}</td>
                    <td style="display:flex;gap:4px;padding-top:12px">
                        <select onchange="updateStatus(${t.id}, this.value)" class="btn btn-ghost btn-sm" style="padding:0 4px;font-size:10px;height:24px;">
                            <option value="" disabled selected>Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                        <button class="btn btn-ghost btn-sm" onclick="openEditModal(${t.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="archiveTask(${t.id}, '${t.title.replace(/'/g,"\\'")}')">Archive</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            currentPage = meta.current_page ?? 1;
            info.innerText = `Showing ${meta.from ?? 1} to ${meta.to ?? tasks.length} of ${meta.total ?? tasks.length} tasks`;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === (meta.last_page ?? 1);
        } catch (err) {
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = err.response?.data?.message || 'Failed to load tasks.';
        }
    }

    document.getElementById('prev-page').onclick = () => { if (currentPage > 1) fetchTasks(currentPage - 1); };
    document.getElementById('next-page').onclick = () => fetchTasks(currentPage + 1);

    document.addEventListener('DOMContentLoaded', () => {
        loadSelectData();
        fetchTasks();
    });
</script>
@endpush
@endsection
