@extends('layouts.employee.master')

@section('title', 'WorkSphere — Task Details')
@section('page_title', 'Task Details')

@section('content')
    <div class="panel" style="max-width: 800px; margin: 0 auto; animation: fadeUp 0.4s ease-out;">
        <div class="panel-header" style="justify-content: space-between;">
            <div class="panel-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Task #<span id="task-id-text">...</span>
            </div>
            <div id="task-status-badge" class="status-pill">Loading...</div>
        </div>
        
        <div class="panel-body" style="padding: 30px;" id="task-details-content">
            <div class="skeleton-list">
                <div class="skeleton" style="width: 100%; height: 40px; margin-bottom: 20px;"></div>
                <div class="skeleton" style="width: 100%; height: 100px; margin-bottom: 20px;"></div>
                <div class="skeleton" style="width: 100%; height: 60px;"></div>
            </div>
        </div>
        
        <div class="panel-footer" style="padding: 20px 30px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <button class="action-btn" onclick="window.location.href='{{ url('/employee/tasks') }}'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                Back to Tasks
            </button>
            <div style="display: flex; gap: 10px;">
                <select id="status-update-select" class="lf" style="width: 150px; padding: 6px 10px; font-size: 13px;">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
                <button id="update-status-btn" class="greeting-btn" onclick="updateTaskStatus()">Update Status</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .task-section { margin-bottom: 25px; }
    .task-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-3); margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .task-value { font-size: 15px; color: var(--text-1); line-height: 1.6; }
    .task-box { background: var(--bg-1); padding: 20px; border-radius: 8px; border: 1px solid var(--border); }
    .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
    .status-pill.pending { background: var(--amber-lt); color: var(--amber); }
    .status-pill.in_progress { background: var(--blue-lt); color: var(--blue); }
    .status-pill.completed { background: var(--accent-lt); color: var(--accent); }
    
    .priority-tag { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
    .priority-high { background: var(--red-lt); color: var(--red); }
    .priority-medium { background: var(--amber-lt); color: var(--amber); }
    .priority-low { background: var(--blue-lt); color: var(--blue); }
</style>
@endpush

@push('scripts')
<script>
let currentTaskId = null;

async function fetchTaskDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    currentTaskId = urlParams.get('id');
    const content = document.getElementById('task-details-content');
    const statusPill = document.getElementById('task-status-badge');
    const idText = document.getElementById('task-id-text');
    const statusSelect = document.getElementById('status-update-select');

    if (!currentTaskId) {
        content.innerHTML = '<p style="color:var(--red); text-align:center; padding:40px;">No Task ID provided.</p>';
        return;
    }

    try {
        const res = await axios.get(`/api/employee/tasks/${currentTaskId}`);
        const task = res.data.data;

        idText.textContent = task.id;
        statusPill.textContent = task.status.replace('_', ' ');
        statusPill.className = `status-pill ${task.status}`;
        statusSelect.value = task.status;

        const dueDate = new Date(task.due_date);
        const isOverdue = dueDate < new Date() && task.status !== 'completed';

        content.innerHTML = `
            <div class="task-section">
                <h2 style="font-size: 22px; margin-bottom: 15px;">${task.title}</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="task-label" style="margin-bottom:0">Priority:</span>
                        <span class="priority-tag priority-${task.priority}">${task.priority}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span class="task-label" style="margin-bottom:0">Due Date:</span>
                        <span style="font-size: 13px; font-weight: 500; color: ${isOverdue ? 'var(--red)' : 'var(--text-2)'}">${dueDate.toLocaleDateString()} ${isOverdue ? '(Overdue)' : ''}</span>
                    </div>
                </div>
            </div>

            <div class="task-section">
                <div class="task-label">Project</div>
                <div class="task-value" style="font-weight: 600; color: var(--accent); cursor: pointer;" onclick="window.location.href='/employee/projects/details?id=${task.project_id}'">
                    ${task.project ? task.project.name : 'Unassigned'}
                </div>
            </div>

            <div class="task-section">
                <div class="task-label">Description</div>
                <div class="task-box">${task.description || '<span style="color:var(--text-3); font-style:italic;">No description provided.</span>'}</div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="task-section">
                    <div class="task-label">Created By</div>
                    <div class="task-value">${task.creator ? task.creator.name : 'System'}</div>
                </div>
                <div class="task-section">
                    <div class="task-label">Assigned At</div>
                    <div class="task-value">${new Date(task.created_at).toLocaleDateString()}</div>
                </div>
            </div>
        `;

    } catch (err) {
        console.error('Fetch task error:', err);
        content.innerHTML = '<p style="color:var(--red); text-align:center; padding:40px;">Failed to load task details.</p>';
    }
}

async function updateTaskStatus() {
    const btn = document.getElementById('update-status-btn');
    const status = document.getElementById('status-update-select').value;
    
    btn.innerHTML = '<svg class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Updating...';
    btn.disabled = true;

    try {
        await axios.patch(`/api/employee/tasks/${currentTaskId}/status`, { status });
        await fetchTaskDetails(); // Refresh view
        alert('Task status updated successfully!');
    } catch (err) {
        alert('Failed to update status: ' + (err.response?.data?.message || 'Unknown error'));
    } finally {
        btn.innerHTML = 'Update Status';
        btn.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', fetchTaskDetails);
</script>
@endpush
@endsection
