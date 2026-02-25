<?php
$pageTitle = 'My Tasks';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>My <em>Tasks</em> ✅</h2>
        <p>You have 4 pending tasks and 1 overdue task requiring attention.</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <div class="stat-badge warn" style="padding: 8px 14px; border: 1px solid var(--amber);">4 Pending</div>
        <div class="stat-badge down" style="padding: 8px 14px; border: 1px solid var(--red);">1 Overdue</div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">All Assigned Tasks <span class="count">5</span></div>
        <div style="display: flex; gap: 8px;">
          <select class="lf" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;">
            <option>All Projects</option>
            <option>Website Redesign</option>
            <option>Backend API v2</option>
          </select>
          <select class="lf" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;">
            <option>All Priorities</option>
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
          </select>
        </div>
      </div>

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

      <div style="overflow-x: auto;">
        <table class="task-table" id="tbl-emp-tasks" data-tabulator>
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
          <tbody>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTask(this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: 500;">Design homepage wireframe for client review</div></td>
              <td><span style="color: var(--text-3);">Website Redesign</span></td>
              <td><span class="priority high">High</span></td>
              <td><span class="task-due late">⚠ Feb 22</span></td>
              <td class="action-btns">
                <button class="btn-icon" title="Edit Task"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTask(this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: 500;">Write API documentation for auth module</div></td>
              <td><span style="color: var(--text-3);">Backend API v2</span></td>
              <td><span class="priority medium">Medium</span></td>
              <td><span class="task-due" style="color: var(--text-2);">Feb 27</span></td>
              <td class="action-btns">
                <button class="btn-icon" title="Edit Task"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTask(this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: 500;">Fix responsive layout bugs on mobile</div></td>
              <td><span style="color: var(--text-3);">Website Redesign</span></td>
              <td><span class="priority medium">Medium</span></td>
              <td><span class="task-due" style="color: var(--text-2);">Mar 01</span></td>
              <td class="action-btns">
                <button class="btn-icon" title="Edit Task"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
            <tr class="task-row completed">
              <td class="checkbox-cell">
                <div class="task-check done" onclick="toggleTask(this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: 400;">Set up staging environment</div></td>
              <td><span style="color: var(--text-3);">DevOps</span></td>
              <td><span class="priority low">Low</span></td>
              <td><span class="task-due" style="color: var(--accent);">✓ Done</span></td>
              <td class="action-btns">
                <button class="btn-icon" title="Edit Task"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
            <tr class="task-row">
              <td class="checkbox-cell">
                <div class="task-check" onclick="toggleTask(this)"></div>
              </td>
              <td><div class="task-text" style="font-weight: 500;">Prepare quarterly progress report</div></td>
              <td><span style="color: var(--text-3);">Internal</span></td>
              <td><span class="priority low">Low</span></td>
              <td><span class="task-due" style="color: var(--text-2);">Mar 05</span></td>
              <td class="action-btns">
                <button class="btn-icon" title="Edit Task"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<script>
function toggleTask(el) {
  el.classList.toggle('done');
  const row = el.closest('.task-row');
  row.classList.toggle('completed');
  
  // Update status text if needed
  const statusCell = row.querySelector('.task-due');
  if (el.classList.contains('done')) {
    if (!statusCell.dataset.oldText) statusCell.dataset.oldText = statusCell.innerText;
    statusCell.innerHTML = '<span style="color: var(--accent);">✓ Done</span>';
  } else {
    statusCell.innerText = statusCell.dataset.oldText || 'Pending';
  }
}
</script>

<?php include 'includes/footer.php'; ?>
