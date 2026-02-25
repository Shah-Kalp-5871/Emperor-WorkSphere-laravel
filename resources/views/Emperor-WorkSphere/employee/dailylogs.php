<?php
$pageTitle = 'Daily Work Log';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    
    <!-- SUBMIT TODAY'S LOG -->
    <div class="panel" style="margin-bottom: 24px;">
      <div class="panel-header">
        <div class="panel-title">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Today's Submission
        </div>
        <span id="log-status" style="font-size:11px;background:var(--amber-lt);color:var(--amber);padding:3px 9px;border-radius:20px;font-weight:500;border:1px solid #FDE68A">Draft</span>
      </div>
      <div class="log-body">
        <div class="log-date" style="font-size: 14px; font-weight: 500; color: var(--text-1);">📅 Tuesday, 24 February 2026</div>
        <p style="font-size: 12px; color: var(--text-3); margin-bottom: 20px;">Please document your progress for today's tasks.</p>
        
        <div class="log-grid">
          <div class="log-sec">
            <div class="log-sec-label">🌅 Morning Session (Before Lunch)</div>
            <textarea class="lf" placeholder="What did you work on this morning? e.g. Design meeting, coding auth module..."></textarea>
            <input class="lf" type="url" placeholder="🔗 Optional project/commit link" style="margin-top:8px;min-height:unset;padding:10px 12px"/>
          </div>
          <div class="log-sec">
            <div class="log-sec-label">🌆 Afternoon Session (After Lunch)</div>
            <textarea class="lf" placeholder="What did you finish this afternoon?"></textarea>
            <input class="lf" type="url" placeholder="🔗 Optional project/commit link" style="margin-top:8px;min-height:unset;padding:10px 12px"/>
          </div>
        </div>
        
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid var(--border); padding-top: 20px;">
          <button class="action-btn" style="padding: 10px 24px;">Save Draft</button>
          <button class="greeting-btn" id="log-btn" onclick="submitLog()">Submit Final Log</button>
        </div>
        
        <div id="log-saved" style="display:none;align-items:center;gap:6px;font-size:12px;color:var(--accent);margin-top:12px;justify-content: flex-end;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Log submitted successfully!
        </div>
      </div>
    </div>

    <style>
      .log-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
      @media (max-width: 900px) { .log-grid { grid-template-columns: 1fr; } }
      
      .history-table { width: 100%; border-collapse: collapse; }
      .history-table th { text-align: left; padding: 12px 22px; font-size: 11px; text-transform: uppercase; color: var(--text-3); border-bottom: 1px solid var(--border); }
      .history-table td { padding: 16px 22px; border-bottom: 1px solid var(--border); font-size: 13.5px; transition: background 0.2s; }
      .history-table tr:hover td { background: var(--surface-2); }
      
      .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
      .status-pill.done { background: var(--accent-lt); color: var(--accent); }
      .status-pill.pending { background: var(--amber-lt); color: var(--amber); }
    </style>

    <!-- LOG HISTORY -->
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Log History</div>
        <div style="display: flex; gap: 8px;">
           <input type="month" class="lf" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;" value="2026-02">
        </div>
      </div>
      <div style="overflow-x: auto;">
        <table class="history-table" id="tbl-emp-dailylogs" data-tabulator>
          <thead>
            <tr>
              <th data-width="140">Date</th>
              <th data-width="220">Morning Summary</th>
              <th data-width="220">Afternoon Summary</th>
              <th data-width="120">Status</th>
              <th data-width="100">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><div style="font-weight: 600;">Feb 23, 2026</div><div style="font-size: 11px; color: var(--text-3);">Monday</div></td>
              <td>Worked on the responsive layout bugs for the admin dashboard.</td>
              <td>Completed the task management refactor and updated sidebar styles.</td>
              <td><span class="status-pill done">Submitted</span></td>
              <td><button class="action-btn" style="padding: 6px 12px; font-size: 12px;">View</button></td>
            </tr>
            <tr>
              <td><div style="font-weight: 600;">Feb 22, 2026</div><div style="font-size: 11px; color: var(--text-3);">Sunday</div></td>
              <td colspan="2" style="text-align: center; color: var(--text-3); font-style: italic;">Office Closed (Weekend)</td>
              <td><span style="color: var(--text-3);">—</span></td>
              <td>—</td>
            </tr>
            <tr>
              <td><div style="font-weight: 600;">Feb 21, 2026</div><div style="font-size: 11px; color: var(--text-3);">Saturday</div></td>
              <td colspan="2" style="text-align: center; color: var(--red); font-weight: 500;">Missing Log Entry</td>
              <td><span class="status-pill pending" style="background: var(--red-lt); color: var(--red);">Overdue</span></td>
              <td><button class="greeting-btn" style="padding: 6px 12px; font-size: 11px;">Fill Now</button></td>
            </tr>
            <tr>
              <td><div style="font-weight: 600;">Feb 20, 2026</div><div style="font-size: 11px; color: var(--text-3);">Friday</div></td>
              <td>Initial setup for the Employee portal and basic header/footer.</td>
              <td>Assigned to 3 new projects and started internal documentation.</td>
              <td><span class="status-pill done">Submitted</span></td>
              <td><button class="action-btn" style="padding: 6px 12px; font-size: 12px;">View</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<script>
function submitLog() {
  const btn = document.getElementById('log-btn');
  const status = document.getElementById('log-status');
  const saved = document.getElementById('log-saved');
  
  btn.innerHTML = '<svg class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Submitting...';
  btn.style.opacity = '0.7';
  btn.style.pointerEvents = 'none';
  
  setTimeout(() => {
    btn.innerHTML = 'Submitted';
    btn.style.background = 'var(--text-3)';
    status.innerHTML = 'Submitted';
    status.style.background = 'var(--accent-lt)';
    status.style.color = 'var(--accent)';
    status.style.borderColor = 'var(--accent)';
    saved.style.display = 'flex';
  }, 1000);
}
</script>

<style>
.spinner { animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<?php include 'includes/footer.php'; ?>
