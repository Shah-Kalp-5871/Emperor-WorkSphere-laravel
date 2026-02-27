@extends('layouts.employee.master')

@section('title', 'WorkSphere — Edit Daily Log')
@section('page_title', 'Edit Daily Log')

@section('content')
    <div class="panel" style="max-width: 800px; margin: 0 auto;">
        <div class="panel-header">
            <div class="panel-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Log for <span id="log-date-display">...</span>
            </div>
        </div>
        
        <div class="panel-body" style="padding: 30px;">
            <form id="edit-daily-log-form">
                <div class="log-grid" style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                  <div class="log-sec">
                    <div class="log-sec-label">🌅 Morning Session</div>
                    <textarea id="morning_summary" class="lf" style="min-height: 120px;" placeholder="What did you work on this morning?" required></textarea>
                  </div>
                  <div class="log-sec">
                    <div class="log-sec-label">🌆 Afternoon Session</div>
                    <textarea id="afternoon_summary" class="lf" style="min-height: 120px;" placeholder="What did you finish this afternoon?" required></textarea>
                  </div>
                </div>

                
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; border-top: 1px solid var(--border); padding-top: 20px;">
                  <button type="button" class="action-btn" onclick="window.history.back()">Cancel</button>
                  <button type="submit" class="greeting-btn" id="update-btn">Update Log</button>
                </div>
            </form>
            
            <div id="update-success-msg" style="display:none;align-items:center;gap:6px;font-size:12px;color:var(--accent);margin-top:12px;justify-content: flex-end;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
              Log updated successfully!
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .log-sec-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-3); margin-bottom: 8px; }
    .mood-opt { cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 6px 12px; border-radius: 20px; border: 1px solid var(--border); background: var(--surface); font-size: 13px; transition: all 0.2s; }
    .mood-opt:has(input:checked) { border-color: var(--accent); background: var(--accent-lt); color: var(--accent); }
    .mood-opt input { display: none; }
</style>
@endpush

@push('scripts')
<script>
const urlParams = new URLSearchParams(window.location.search);
const logId = urlParams.get('id');

async function fetchLog() {
    if (!logId) return;

    try {
        const res = await axios.get(`/api/employee/daily-logs/${logId}`);
        const log = res.data.data;

        document.getElementById('log-date-display').textContent = new Date(log.log_date).toLocaleDateString();
        document.getElementById('morning_summary').value = log.morning_summary;
        document.getElementById('afternoon_summary').value = log.afternoon_summary;

    } catch (err) {
        console.error('Fetch log error:', err);
    }
}

document.getElementById('edit-daily-log-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('update-btn');
    const successMsg = document.getElementById('update-success-msg');
    const data = {
        morning_summary: document.getElementById('morning_summary').value,
        afternoon_summary: document.getElementById('afternoon_summary').value,
    };

    btn.innerHTML = '<svg class="spinner" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Updating...';
    btn.disabled = true;

    try {
        await axios.put(`/api/employee/daily-logs/${logId}`, data);
        successMsg.style.display = 'flex';
        btn.innerHTML = 'Updated';
        
        setTimeout(() => { 
            window.location.href = `/employee/dailylogs/show?id=${logId}`;
        }, 1500);
    } catch (err) {
        alert('Failed to update log: ' + (err.response?.data?.message || 'Unknown error'));
        btn.innerHTML = 'Update Log';
        btn.disabled = false;
    }
});

document.addEventListener('DOMContentLoaded', fetchLog);
</script>
@endpush
@endsection
