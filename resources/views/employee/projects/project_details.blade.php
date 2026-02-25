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
        <div>
          <h1 class="proj-title-h1">Website Redesign</h1>
          <p class="proj-subtitle">Internal development for company portal upgrade</p>
        </div>
      </div>
      <button class="greeting-btn" onclick="openModal('create-task-modal')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Create New Task
      </button>
    </div>

    <!-- STATS & INFO -->
    <div class="stats-row" style="margin-top: 20px;">
      <div class="stat-card">
        <div class="stat-label">Overall Progress</div>
        <div class="stat-value" style="font-size: 22px; margin: 8px 0;">72%</div>
        <div class="proj-bar-bg"><div class="proj-bar-fill" style="width: 72%; background: var(--accent);"></div></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Active Team</div>
        <div class="member-avatars" style="margin-top: 12px;">
          <div class="member-avatar">P</div>
          <div class="member-avatar" style="background:var(--blue-lt); color:var(--blue);">R</div>
          <div class="member-avatar" style="background:var(--amber-lt); color:var(--amber);">A</div>
          <div class="member-avatar">+1</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Tasks Status</div>
        <div class="stat-value" style="font-size: 18px; margin-top: 8px;">
          <span style="color: var(--accent);">4 Done</span> · <span style="color: var(--amber);">2 Pending</span>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Deadline</div>
        <div class="stat-value" style="font-size: 18px; margin-top: 8px; color: var(--red);">Mar 15, 2026</div>
      </div>
    </div>

    <!-- TASK LIST -->
    <div class="panel" style="margin-top: 20px;">
      <div class="panel-header">
        <div class="panel-title">Project Tasks <span class="count">6</span></div>
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
          <tbody>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTaskRow(this)"></div>
              </td>
              <td><span class="task-text-name">Design homepage wireframe for client review</span></td>
              <td>Priya Sharma</td>
              <td><span class="priority high">High</span></td>
              <td><span class="task-due late">⚠ Feb 22</span></td>
              <td><button class="action-btn-sm">Edit</button></td>
            </tr>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTaskRow(this)"></div>
              </td>
              <td><span class="task-text-name">Fix responsive layout bugs on mobile</span></td>
              <td>Ravi Kumar</td>
              <td><span class="priority medium">Medium</span></td>
              <td><span class="task-due text-3">Mar 01</span></td>
              <td><button class="action-btn-sm">Edit</button></td>
            </tr>
            <tr class="task-row completed">
              <td class="checkbox-cell">
                <div class="task-check done" onclick="toggleTaskRow(this)"></div>
              </td>
              <td><span class="task-text-name">Setup initial project repository</span></td>
              <td>Ankit Mehta</td>
              <td><span class="priority low">Low</span></td>
              <td><span class="task-due text-3">✓ Feb 18</span></td>
              <td><button class="action-btn-sm">Edit</button></td>
            </tr>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTaskRow(this)"></div>
              </td>
              <td><span class="task-text-name">Client feedback integration</span></td>
              <td>Sara Joshi</td>
              <td><span class="priority high">High</span></td>
              <td><span class="task-due text-3">Mar 05</span></td>
              <td><button class="action-btn-sm">Edit</button></td>
            </tr>
          </tbody>
        </table>
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

    .member-avatars { display: flex; align-items: center; }
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
        margin-left: -10px;
    }
    .member-avatar:first-child { margin-left: 0; }
</style>
@endpush

@push('scripts')
<script>
function toggleTaskRow(el) {
  el.classList.toggle('done');
  const row = el.closest('.task-row');
  row.classList.toggle('completed');
}
</script>
@endpush
