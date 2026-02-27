@extends('layouts.employee.master')

@section('title', 'WorkSphere — Daily Work Log')
@section('page_title', 'Daily Work Log')

@section('content')
    <!-- SUBMIT TODAY'S LOG -->
    <div class="panel" style="margin-bottom: 24px;">
      <div class="panel-header">
        <div class="panel-title">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Today's Submission
        </div>
        <span id="log-status" style="font-size:11px;background:var(--amber-lt);color:var(--amber);padding:3px 9px;border-radius:20px;font-weight:500;border:1px solid #FDE68A">Draft</span>
      </div>
      <div class="log-body" style="padding: 24px;">
        <div class="log-date" style="font-size: 14px; font-weight: 500; color: var(--text-1);">📅 {{ date('l, d F Y') }}</div>
        <p style="font-size: 12px; color: var(--text-3); margin-bottom: 20px;">Please document your progress for today's tasks.</p>
        
        <form id="daily-log-form">
            <div class="log-grid">
              <div class="log-sec">
                <div class="log-sec-label">🌅 Morning Session</div>
                <textarea id="morning_summary" class="lf" placeholder="What did you work on this morning?" required></textarea>
              </div>
              <div class="log-sec">
                <div class="log-sec-label">🌆 Afternoon Session</div>
                <textarea id="afternoon_summary" class="lf" placeholder="What did you finish this afternoon?" required></textarea>
              </div>
            </div>

            
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid var(--border); padding-top: 20px;">
              <button type="button" class="action-btn" style="padding: 10px 24px;" onclick="saveDraft()">Save Draft</button>
              <button type="submit" class="greeting-btn" id="log-btn">Submit Final Log</button>
            </div>
        </form>
        
        <div id="log-success-msg" style="display:none;align-items:center;gap:6px;font-size:12px;color:var(--accent);margin-top:12px;justify-content: flex-end;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Log submitted successfully!
        </div>
      </div>
    </div>

    <!-- LOG HISTORY -->
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Log History</div>
        <div style="display: flex; gap: 8px;">
           <input type="month" id="history-month" class="lf" style="padding: 5px 10px; font-size: 12px; min-height: unset; width: auto;" value="{{ date('Y-m') }}">
        </div>
      </div>
      <div style="overflow-x: auto;">
        <table class="history-table" id="tbl-emp-dailylogs">
          <thead>
            <tr>
              <th style="width:140px">Date</th>
              <th>Morning Summary</th>
              <th>Afternoon Summary</th>
              <th style="width:120px">Status</th>
              <th style="width:100px">Action</th>
            </tr>
          </thead>
          <tbody id="log-history-body">
            <!-- Skeletons -->
            @for($i=0; $i<3; $i++)
            <tr>
                <td><div class="skeleton" style="width:80px;height:12px;margin-bottom:6px"></div><div class="skeleton" style="width:50px;height:10px"></div></td>
                <td><div class="skeleton" style="width:90%;height:12px"></div></td>
                <td><div class="skeleton" style="width:90%;height:12px"></div></td>
                <td><div class="skeleton" style="width:60px;height:18px;border-radius:10px"></div></td>
                <td><div class="skeleton" style="width:50px;height:24px"></div></td>
            </tr>
            @endfor
          </tbody>
        </table>
      </div>
    </div>
@endsection

@push('styles')
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

    .spinner { animation: spin 0.8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@push('scripts')
<script>
async function fetchLogs() {
    const tbody = document.getElementById('log-history-body');
    const month = document.getElementById('history-month').value;

    try {
        const res = await axios.get('/api/employee/daily-logs');
        const logs = res.data.data.data || res.data.data;

        if (logs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-3);">No logs found yet.</td></tr>';
            return;
        }

        tbody.innerHTML = logs.map(l => {
            const date = new Date(l.log_date);
            const dateDisplay = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
            const statusClass = l.status === 'approved' ? 'done' : 'pending';
            const logId = l.id;

            return `
                <tr>
                  <td>
                    <div style="font-weight: 600;">${dateDisplay}</div>
                    <div style="font-size: 11px; color: var(--text-3);">${dayName}</div>
                  </td>
                  <td>${l.morning_summary.substring(0, 60)}${l.morning_summary.length > 60 ? '...' : ''}</td>
                  <td>${l.afternoon_summary.substring(0, 60)}${l.afternoon_summary.length > 60 ? '...' : ''}</td>
                  <td><span class="status-pill ${statusClass}">${l.status.charAt(0).toUpperCase() + l.status.slice(1)}</span></td>
                  <td><button class="action-btn" style="padding: 6px 12px; font-size: 12px;" onclick="window.location.href='/employee/dailylogs/show?id=${logId}'">View</button></td>
                </tr>
            `;
        }).join('');
    } catch (err) {
        console.error('Fetch logs error:', err);
    }
}

document.getElementById('daily-log-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('log-btn');
    const successMsg = document.getElementById('log-success-msg');
    
    const data = {
        log_date: new Date().toISOString().split('T')[0],
        morning_summary: document.getElementById('morning_summary').value,
        afternoon_summary: document.getElementById('afternoon_summary').value,
    };

    btn.innerHTML = '<svg class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Submitting...';
    btn.disabled = true;

    try {
        await axios.post('/api/employee/daily-logs', data);
        successMsg.style.display = 'flex';
        btn.innerHTML = 'Submitted';
        btn.style.background = 'var(--text-3)';
        document.getElementById('daily-log-form').reset();
        fetchLogs(); // Reload history
        
        setTimeout(() => { successMsg.style.display = 'none'; }, 3000);
    } catch (err) {
        alert('Failed to submit log: ' + (err.response?.data?.message || 'Unknown error'));
        btn.innerHTML = 'Submit Final Log';
        btn.disabled = false;
    }
});

document.getElementById('history-month').addEventListener('change', fetchLogs);
document.addEventListener('DOMContentLoaded', fetchLogs);

function saveDraft() {
    alert('Draft saved to local storage (Simulation)');
}
</script>
@endpush
