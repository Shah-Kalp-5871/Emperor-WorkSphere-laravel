@extends('layouts.employee.master')

@section('title', 'WorkSphere — My Tasks')
@section('page_title', 'My Tasks')

@section('content')
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>My <em>Tasks</em> ✅</h2>
        <p id="task-greeting-sub">Loading your task summary...</p>
      </div>
      <div style="display: flex; gap: 10px;" id="task-stat-badges">
        <div class="skeleton" style="width:80px;height:32px;border-radius:20px"></div>
        <div class="skeleton" style="width:80px;height:32px;border-radius:20px"></div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">All Assigned Tasks <span class="count" id="task-count">0</span></div>
        <div style="display: flex; gap: 8px;">
          <select class="lf" id="filter-project" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;">
            <option value="">All Projects</option>
          </select>
          <select class="lf" id="filter-priority" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;">
            <option value="">All Priorities</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
          </select>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table class="task-table" id="tbl-emp-tasks">
          <thead>
            <tr>
              <th class="checkbox-cell"></th>
              <th>Task Details</th>
              <th>Project</th>
              <th>Priority</th>
              <th>Due Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="task-list-body">
            <!-- Loading Skeletons -->
            @for($i=0; $i<5; $i++)
            <tr class="skeleton-row">
                <td class="checkbox-cell"><div class="skeleton" style="width:20px;height:20px;border-radius:4px"></div></td>
                <td><div class="skeleton" style="width:70%;height:15px"></div></td>
                <td><div class="skeleton" style="width:80px;height:12px"></div></td>
                <td><div class="skeleton" style="width:50px;height:12px"></div></td>
                <td><div class="skeleton" style="width:60px;height:12px"></div></td>
                <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px"></div></td>
            </tr>
            @endfor
          </tbody>
        </table>

        <div id="tasks-empty" style="display:none; padding:40px; text-align:center; color:var(--text-3);">
            <div style="font-size:32px;margin-bottom:12px">🎉</div>
            <p>No pending tasks found. You're all caught up!</p>
        </div>
      </div>
    </div>
@endsection

@push('styles')
<style>
    .task-table { width: 100%; border-collapse: collapse; }
    .task-table th { text-align: left; padding: 12px 22px; font-size: 11px; text-transform: uppercase; color: var(--text-3); border-bottom: 1px solid var(--border); font-weight: 600; }
    .task-table td { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 13.5px; vertical-align: middle; transition: all 0.2s; }
    .task-table tr:hover td { background: var(--surface-2); }
    
    .checkbox-cell { width: 40px; padding-right: 0 !important; }
    .task-row.completed td { color: var(--text-3); }
    .task-row.completed .task-text { text-decoration: line-through; }
    
    .action-btns { display: flex; gap: 6px; }
    .btn-icon { 
        width: 28px; 
        height: 28px; 
        border-radius: 6px; 
        border: 1px solid var(--border); 
        background: #fff; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: var(--text-2); 
        cursor: pointer; 
        transition: all 0.2s;
    }
    .btn-icon:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
</style>
@endpush

@push('scripts')
<script>
let allTasks = [];

async function fetchTasks() {
    try {
        const res = await axios.get('/api/employee/tasks');
        allTasks = res.data.data.data; // Standard pagination structure
        renderTasks();
        renderProjectsFilter();
    } catch (err) {
        console.error('Fetch tasks error:', err);
    }
}

function renderTasks() {
    const tbody = document.getElementById('task-list-body');
    const projectFilter = document.getElementById('filter-project').value;
    const priorityFilter = document.getElementById('filter-priority').value;
    const emptyDiv = document.getElementById('tasks-empty');

    const filtered = allTasks.filter(t => {
        const pMatch = !projectFilter || t.project_id == projectFilter;
        const prMatch = !priorityFilter || t.priority.toLowerCase() == priorityFilter.toLowerCase();
        return pMatch && prMatch;
    });

    if (filtered.length === 0) {
        tbody.innerHTML = '';
        emptyDiv.style.display = 'block';
        return;
    }

    emptyDiv.style.display = 'none';
    tbody.innerHTML = filtered.map(t => {
        const isDone = t.status === 'completed';
        const priorityClass = t.priority.toLowerCase();
        const dueDate = t.due_date ? new Date(t.due_date) : null;
        const isLate = dueDate && dueDate < new Date() && !isDone;
        
        let dueDisplay = t.due_date ? new Date(t.due_date).toLocaleDateString() : 'No date';
        if (isDone) dueDisplay = '<span style="color: var(--accent);">✓ Done</span>';
        else if (isLate) dueDisplay = `<span class="task-due late">⚠ ${dueDisplay}</span>`;

        return `
            <tr class="task-row ${isDone ? 'completed' : ''}" data-id="${t.id}">
              <td class="checkbox-cell">
                <div class="task-check ${isDone ? 'done' : ''}" onclick="toggleTask(${t.id}, this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: ${isDone ? '400' : '500'};">${t.title}</div></td>
              <td><span style="color: var(--text-3);">${t.project?.name || 'Unassigned'}</span></td>
              <td><span class="priority ${priorityClass}">${t.priority}</span></td>
              <td><span class="task-due">${dueDisplay}</span></td>
              <td class="action-btns">
                <button class="btn-icon" onclick="window.location.href='/employee/tasks/show?id=${t.id}'"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
        `;
    }).join('');

    updateStats(allTasks);
}

function renderProjectsFilter() {
    const projects = [...new Set(allTasks.map(t => t.project).filter(p => p !== null))];
    const select = document.getElementById('filter-project');
    const current = select.value;
    
    select.innerHTML = '<option value="">All Projects</option>' + 
        projects.map(p => `<option value="${p.id}" ${current == p.id ? 'selected' : ''}>${p.name}</option>`).join('');
}

function updateStats(tasks) {
    const pending = tasks.filter(t => t.status !== 'completed').length;
    const overdue = tasks.filter(t => {
        const due = t.due_date ? new Date(t.due_date) : null;
        return due && due < new Date() && t.status !== 'completed';
    }).length;

    document.getElementById('task-count').textContent = tasks.length;
    document.getElementById('task-greeting-sub').textContent = `You have ${pending} pending tasks ${overdue > 0 ? `and ${overdue} overdue task${overdue > 1 ? 's' : ''}` : ''} requiring attention.`;
    
    document.getElementById('task-stat-badges').innerHTML = `
        <div class="stat-badge warn" style="padding: 8px 14px; border: 1px solid var(--amber);">${pending} Pending</div>
        ${overdue > 0 ? `<div class="stat-badge down" style="padding: 8px 14px; border: 1px solid var(--red);">${overdue} Overdue</div>` : ''}
    `;
}

async function toggleTask(id, el) {
    const isDone = !el.classList.contains('done');
    const newStatus = isDone ? 'completed' : 'in_progress';

    try {
        await axios.patch(`/api/employee/tasks/${id}/status`, { status: newStatus });
        // Optimistic UI or refetch
        fetchTasks();
    } catch (err) {
        alert('Failed to update status: ' + (err.response?.data?.message || 'Unknown error'));
    }
}

document.getElementById('filter-project').addEventListener('change', renderTasks);
document.getElementById('filter-priority').addEventListener('change', renderTasks);

document.addEventListener('DOMContentLoaded', fetchTasks);
</script>
@endpush
