@extends('layouts.employee.master')

@section('title', 'WorkSphere — Project Details')
@section('page_title', 'Project Details')

@section('content')
    <!-- PROJECT HEADER -->
    <div class="project-header-panel">
      <div class="proj-brand">
        <div class="proj-logo-box">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
        </div>
        <div id="proj-header-info">
          <div class="skeleton" style="width:200px;height:28px;margin-bottom:8px"></div>
          <div class="skeleton" style="width:300px;height:14px"></div>
        </div>
      </div>
      <button class="greeting-btn" onclick="window.location.href='/employee/projects'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to Projects
      </button>
    </div>

    <!-- STATS & INFO -->
    <div class="stats-row" style="margin-top: 20px;">
      <div class="stat-card">
        <div class="stat-label">Overall Progress</div>
        <div class="stat-value" id="proj-progress-val" style="font-size: 22px; margin: 8px 0;">0%</div>
        <div class="proj-bar-bg"><div id="proj-progress-bar" class="proj-bar-fill" style="width: 0%; background: var(--accent);"></div></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Active Team</div>
        <div class="member-avatars" id="proj-member-avatars" style="margin-top: 12px;">
           <div class="skeleton" style="width:28px;height:28px;border-radius:50%"></div>
           <div class="skeleton" style="width:28px;height:28px;border-radius:50%"></div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Tasks Status</div>
        <div class="stat-value" id="proj-task-stats" style="font-size: 18px; margin-top: 8px;">
          <span class="skeleton" style="width:100px;height:18px"></span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Deadline</div>
        <div class="stat-value" id="proj-deadline" style="font-size: 18px; margin-top: 8px;">
            <span class="skeleton" style="width:100px;height:18px"></span>
        </div>
      </div>
    </div>

    <!-- TASK LIST -->
    <div class="panel" style="margin-top: 20px;">
      <div class="panel-header">
        <div class="panel-title">Project Tasks <span class="count" id="proj-task-count">0</span></div>
        <div style="display:flex; gap:8px;">
          <button class="btn-primary" id="btn-open-add-member" onclick="openAddMemberModal()" style="display:none; padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: var(--surface-2); color: var(--text-1); border: 1px solid var(--border); cursor: pointer;">+ Add Member</button>
          <button class="btn-primary" onclick="openProjectTaskModal()" style="padding: 6px 12px; font-size: 13px; border-radius: 6px; border: none; background: var(--accent); color: #fff; cursor: pointer;">+ Add Task</button>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table class="task-table">
          <thead>
            <tr>
              <th class="checkbox-cell"></th>
              <th>Task</th>
              <th>Assigned To</th>
              <th>Priority</th>
              <th>Due Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="proj-task-list">
            <!-- Loading Skeletons -->
            @for($i=0; $i<4; $i++)
            <tr class="skeleton-row">
                <td class="checkbox-cell"><div class="skeleton" style="width:20px;height:20px;border-radius:4px"></div></td>
                <td><div class="skeleton" style="width:70%;height:15px"></div></td>
                <td><div class="skeleton" style="width:100px;height:12px"></div></td>
                <td><div class="skeleton" style="width:60px;height:12px"></div></td>
                <td><div class="skeleton" style="width:80px;height:12px"></div></td>
                <td><div class="skeleton" style="width:50px;height:24px"></div></td>
            </tr>
            @endfor
          </tbody>
        </table>
      </div>
    </div>

{{-- Create Project Task Modal --}}
<div class="modal-overlay" id="create-proj-task-modal">
    <div class="modal">
        <div class="modal-close" onclick="closeModal('create-proj-task-modal')">✕</div>
        <div class="modal-title">Add Task to Project</div>
        <form id="create-proj-task-form" onsubmit="handleCreateProjTask(event)">
            <div class="form-group">
                <label class="form-label">Task Title *</label>
                <input class="form-input" id="ptask-title" required placeholder="Task title...">
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-input" id="ptask-desc" rows="2" style="resize:vertical;"></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="ptask-status" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select class="form-select" id="ptask-priority" style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Assign To (Project Members)</label>
                <select class="form-select" id="ptask-assignees" multiple style="height:100px;width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;"></select>
                <small style="color:var(--text-3);font-size:11px;margin-top:4px;display:block;">Hold Ctrl/Cmd to select multiple</small>
            </div>
            <div class="form-group">
                <label class="form-label">Due Date</label>
                <input type="date" class="form-input" id="ptask-due-date">
            </div>
            <div id="ptask-error" style="color:#ef4444;font-size:13px;margin-bottom:16px;display:none;"></div>
            <div class="modal-footer" style="padding:0;margin-top:24px;">
                <button type="button" class="btn-ghost" onclick="closeModal('create-proj-task-modal')" style="padding: 8px 16px; border: none; background: transparent; cursor: pointer; color: var(--text-2);">Cancel</button>
                <button type="submit" class="btn-primary" id="btn-create-ptask" style="padding: 8px 16px; border-radius: 6px; border: none; background: var(--accent); color: #fff; cursor: pointer;">Create Task</button>
            </div>
        </form>
    </div>
</div>

{{-- Add Project Member Modal --}}
<div class="modal-overlay" id="add-member-modal">
    <div class="modal">
        <div class="modal-close" onclick="closeModal('add-member-modal')">✕</div>
        <div class="modal-title">Add Team Member</div>
        <form id="add-member-form" onsubmit="handleAddMember(event)">
            <div class="form-group">
                <label class="form-label">Select Employee *</label>
                <select class="form-select" id="add-member-employee-id" required style="width:100%;border:1px solid var(--border);border-radius:8px;background:var(--bg-1);color:var(--text-1);padding:8px;"></select>
                <small style="color:var(--text-3);font-size:11px;margin-top:4px;display:block;">Only active employees not already in the project are shown.</small>
            </div>
            <div id="add-member-error" style="color:#ef4444;font-size:13px;margin-bottom:16px;display:none;"></div>
            <div class="modal-footer" style="padding:0;margin-top:24px;">
                <button type="button" class="btn-ghost" onclick="closeModal('add-member-modal')" style="padding: 8px 16px; border: none; background: transparent; cursor: pointer; color: var(--text-2);">Cancel</button>
                <button type="submit" class="btn-primary" id="btn-confirm-add-member" style="padding: 8px 16px; border-radius: 6px; border: none; background: var(--accent); color: #fff; cursor: pointer;">Add Member</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .project-header-panel { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        background: var(--surface); 
        padding: 24px 28px; 
        border-radius: var(--radius); 
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
    }
    .proj-brand { display: flex; align-items: center; gap: 16px; }
    .proj-logo-box { 
        width: 48px; 
        height: 48px; 
        background: var(--accent-lt); 
        color: var(--accent); 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
    }
    .proj-title-h1 { font-family: 'Instrument Serif', serif; font-size: 26px; font-weight: 400; }
    .proj-subtitle { font-size: 13px; color: var(--text-3); }

    /* Task Table Styles */
    .task-table { width: 100%; border-collapse: collapse; }
    .task-table th { text-align: left; padding: 12px 22px; font-size: 11px; text-transform: uppercase; color: var(--text-3); border-bottom: 1px solid var(--border); }
    .task-table td { padding: 14px 22px; border-bottom: 1px solid var(--border); font-size: 13.5px; vertical-align: middle; }
    .task-table tr:hover { background: var(--surface-2); }
    
    .checkbox-cell { width: 40px; padding-right: 0 !important; }
    .task-row.completed .task-text-name { text-decoration: line-through; color: var(--text-3); }
    
    .action-btn-sm { 
        padding: 4px 8px; 
        border-radius: 4px; 
        border: 1px solid var(--border); 
        background: #fff; 
        font-size: 11px; 
        color: var(--text-2); 
        cursor: pointer; 
    }
    .action-btn-sm:hover { border-color: var(--accent); color: var(--accent); }

    .member-avatars { display: flex; flex-direction: column; align-items: flex-start; gap: 8px; }
    .member-avatar { 
        width: 28px; 
        height: 28px; 
        border-radius: 50%; 
        border: 2px solid var(--surface); 
        background: var(--accent-lt); 
        color: var(--accent); 
        font-size: 11px; 
        font-weight: 600; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0;
    }
</style>
@endpush

@push('scripts')
<script>
const projectId = new URLSearchParams(window.location.search).get('id');
let projectMembers = [];

async function fetchProjectDetails() {
    if (!projectId) {
        alert('No project ID found');
        window.location.href = '/employee/projects';
        return;
    }

    try {
        const res = await axios.get(`/api/employee/projects/${projectId}`);
        const project = res.data.data;
        
        // Check if user has permission to add members (Admin or already a member)
        // Since this is the employee panel, we'll assume they are a member if they can see this,
        // but we'll double check the backend response logic.
        document.getElementById('btn-open-add-member').style.display = 'block';
        
        renderProject(project);
    } catch (err) {
        console.error('Fetch project details error:', err);
        alert(err.response?.data?.message || 'Failed to load project details.');
    }
}

function renderProject(p) {
    // Header
    const header = document.getElementById('proj-header-info');
    header.innerHTML = `
        <h1 class="proj-title-h1">${p.name}</h1>
        <p class="proj-subtitle">${p.description || 'No description provided.'}</p>
    `;

    // Stats
    const progress = p.progress || 0;
    document.getElementById('proj-progress-val').textContent = `${progress}%`;
    document.getElementById('proj-progress-bar').style.width = `${progress}%`;

    // Members
    const members = p.members || [];
    console.log("Project Members Details:", members);
    projectMembers = members;
    document.getElementById('proj-member-avatars').innerHTML = members.map(m => `
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
            <div class="member-avatar" title="${m.name}">${m.name ? m.name[0].toUpperCase() : '?'}</div>
            <span style="font-size: 13px; color: var(--text-2);">${m.name || 'Unknown'}</span>
        </div>
    `).join('');

    // Tasks Status
    const tasks = p.tasks || [];
    const doneCount = tasks.filter(t => t.status === 'completed').length;
    const pendingCount = tasks.length - doneCount;
    document.getElementById('proj-task-stats').innerHTML = `
        <span style="color: var(--accent);">${doneCount} Done</span> · <span style="color: var(--amber);">${pendingCount} Pending</span>
    `;

    // Deadline
    const deadline = p.end_date ? new Date(p.end_date).toLocaleDateString() : 'No deadline';
    const isLate = p.end_date && new Date(p.end_date) < new Date() && progress < 100;
    document.getElementById('proj-deadline').innerHTML = `
        <span style="${isLate ? 'color: var(--red);' : ''}">${deadline}</span>
    `;

    // Task Table
    const tbody = document.getElementById('proj-task-list');
    document.getElementById('proj-task-count').textContent = tasks.length;
    document.getElementById('proj-creator').textContent = p.creator_name || 'Admin';
    
    if (tasks.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-3);">No tasks found for this project.</td></tr>';
        return;
    }

    tbody.innerHTML = tasks.map(t => {
        const isDone = t.status === 'completed';
        const assignedTo = t.assigned_employees_names || 'Unassigned'; // Assuming backend provides this or it's a join
        const dueDate = t.due_date ? new Date(t.due_date).toLocaleDateString() : 'N/A';
        const priorityClass = t.priority.toLowerCase();

        return `
            <tr class="task-row ${isDone ? 'completed' : ''}" data-id="${t.id}">
              <td class="checkbox-cell">
                <div class="task-check ${isDone ? 'done' : ''}" onclick="toggleTask(${t.id}, this)"></div>
              </td>
              <td><span class="task-text-name">${t.title}</span></td>
              <td>${assignedTo}</td>
              <td><span class="priority ${priorityClass}">${t.priority}</span></td>
              <td><span class="task-due">${isDone ? '✓ Done' : dueDate}</span></td>
              <td><button class="action-btn-sm" onclick="window.location.href='/employee/tasks/show?id=${t.id}'">View</button></td>
            </tr>
        `;
    }).join('');
}

async function toggleTask(id, el) {
    const isDone = !el.classList.contains('done');
    const newStatus = isDone ? 'completed' : 'in_progress';

    try {
        await axios.patch(`/api/employee/tasks/${id}/status`, { status: newStatus });
        fetchProjectDetails(); // Refresh to update progress/stats
    } catch (err) {
        alert('Failed to update status: ' + (err.response?.data?.message || 'Unknown error'));
    }
}

function openProjectTaskModal() {
    const ptaskAssignees = document.getElementById('ptask-assignees');
    ptaskAssignees.innerHTML = '';
    projectMembers.forEach(m => {
        ptaskAssignees.appendChild(new Option(m.name || 'Unknown', m.id));
    });
    document.getElementById('create-proj-task-modal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

async function handleCreateProjTask(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-create-ptask');
    const errorDiv = document.getElementById('ptask-error');
    errorDiv.style.display = 'none';
    btn.disabled = true;

    const assignee_ids = Array.from(document.getElementById('ptask-assignees').selectedOptions).map(o => parseInt(o.value));

    try {
        await axios.post('/api/employee/tasks', {
            project_id: projectId,
            title: document.getElementById('ptask-title').value,
            description: document.getElementById('ptask-desc').value,
            status: document.getElementById('ptask-status').value,
            priority: document.getElementById('ptask-priority').value,
            due_date: document.getElementById('ptask-due-date').value || null,
            assignee_ids: assignee_ids.length > 0 ? assignee_ids : null
        });
        closeModal('create-proj-task-modal');
        document.getElementById('create-proj-task-form').reset();
        fetchProjectDetails(); // refresh project details to show new task
    } catch (err) {
        errorDiv.innerText = err.response?.data?.message || 'Failed to create task.';
        errorDiv.style.display = 'block';
    } finally {
        btn.disabled = false;
    }
}

async function openAddMemberModal() {
    const select = document.getElementById('add-member-employee-id');
    select.innerHTML = '<option value="">Loading employees...</option>';
    document.getElementById('add-member-modal').classList.add('active');

    try {
        const res = await axios.get('/api/employee/team');
        const allEmployees = res.data.data.data || res.data.data; // Handle pagination if present
        
        // Filter out existing members
        const existingIds = projectMembers.map(m => m.id);
        const availableEmployees = allEmployees.filter(e => !existingIds.includes(e.id) && e.is_active);

        select.innerHTML = '<option value="">-- Select Employee --</option>';
        availableEmployees.forEach(e => {
            select.appendChild(new Option(`${e.user?.name || 'Unknown'} (${e.employee_code || ''})`, e.id));
        });

        if (availableEmployees.length === 0) {
            select.innerHTML = '<option value="">No available employees to add</option>';
        }
    } catch (err) {
        console.error('Fetch team error:', err);
        select.innerHTML = '<option value="">Failed to load employees</option>';
    }
}

async function handleAddMember(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-confirm-add-member');
    const errorDiv = document.getElementById('add-member-error');
    const employeeId = document.getElementById('add-member-employee-id').value;

    if (!employeeId) return;

    errorDiv.style.display = 'none';
    btn.disabled = true;

    try {
        await axios.post(`/api/employee/projects/${projectId}/members`, {
            employee_id: employeeId
        });
        closeModal('add-member-modal');
        fetchProjectDetails(); // refresh details
    } catch (err) {
        errorDiv.innerText = err.response?.data?.message || 'Failed to add member.';
        errorDiv.style.display = 'block';
    } finally {
        btn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', fetchProjectDetails);
</script>
@endpush
