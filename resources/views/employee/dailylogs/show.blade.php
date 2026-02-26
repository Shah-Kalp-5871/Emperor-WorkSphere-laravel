@extends('layouts.employee.master')

@section('title', 'WorkSphere — Daily Log Details')
@section('page_title', 'Daily Log Details')

@section('content')
    <div class="panel" style="max-width: 800px; margin: 0 auto;">
        <div class="panel-header" style="justify-content: space-between;">
            <div class="panel-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Log for <span id="log-date-display">...</span>
            </div>
            <span id="log-status-pill" class="status-pill">Loading...</span>
        </div>
        
        <div class="panel-body" style="padding: 30px;" id="log-details">
            <div class="skeleton-list">
                <div class="skeleton" style="width: 100%; height: 60px; margin-bottom: 20px;"></div>
                <div class="skeleton" style="width: 100%; height: 60px; margin-bottom: 20px;"></div>
                <div class="skeleton" style="width: 100%; height: 40px;"></div>
            </div>
        </div>
        
        <div class="panel-footer" style="padding: 20px 30px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
            <button class="action-btn" onclick="window.location.href='{{ url('/employee/dailylogs') }}'">Back to History</button>
            <button id="edit-log-btn" class="greeting-btn" style="display:none;">Edit Log</button>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .log-section { margin-bottom: 30px; }
    .log-section-label { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-3); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
    .log-content { font-size: 15px; color: var(--text-1); line-height: 1.6; background: var(--bg-1); padding: 20px; border-radius: 8px; border: 1px solid var(--border); }
    .mood-display { font-size: 20px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
    .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
    .status-pill.pending { background: var(--amber-lt); color: var(--amber); }
    .status-pill.approved { background: var(--accent-lt); color: var(--accent); }
    .status-pill.rejected { background: var(--red-lt); color: var(--red); }
</style>
@endpush

@push('scripts')
<script>
async function fetchLogDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const logId = urlParams.get('id');
    const container = document.getElementById('log-details');
    const statusPill = document.getElementById('log-status-pill');
    const dateDisplay = document.getElementById('log-date-display');
    const editBtn = document.getElementById('edit-log-btn');

    if (!logId) {
        container.innerHTML = '<p style="color:var(--red);">No Log ID provided.</p>';
        return;
    }

    try {
        const res = await axios.get(`/api/employee/daily-logs/${logId}`);
        const log = res.data.data;

        dateDisplay.textContent = new Date(log.log_date).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        statusPill.textContent = log.status;
        statusPill.className = `status-pill ${log.status}`;

        if (log.status === 'pending') {
            editBtn.style.display = 'block';
            editBtn.onclick = () => window.location.href = `/employee/dailylogs/edit?id=${log.id}`;
        }

        const moodMap = {
            'happy': '😊 Happy',
            'good': '🙂 Good',
            'neutral': '😐 Neutral',
            'bad': '😔 Bad',
            'tired': '😫 Tired'
        };

        container.innerHTML = `
            <div class="log-section">
                <div class="log-section-label">🌅 Morning Session</div>
                <div class="log-content">${log.morning_summary}</div>
            </div>
            <div class="log-section">
                <div class="log-section-label">🌆 Afternoon Session</div>
                <div class="log-content">${log.afternoon_summary}</div>
            </div>
            <div class="log-section">
                <div class="log-section-label">Mood of the day</div>
                <div class="mood-display">${moodMap[log.mood] || '—'}</div>
            </div>
            ${log.admin_remark ? `
            <div class="log-section" style="margin-top: 40px; border-top: 1px dashed var(--border); padding-top: 20px;">
                <div class="log-section-label" style="color:var(--accent)">💬 Admin Remark</div>
                <div class="log-content" style="background:var(--accent-lt); border-color:var(--accent)">${log.admin_remark}</div>
            </div>
            ` : ''}
        `;

    } catch (err) {
        console.error('Fetch log error:', err);
        container.innerHTML = '<p style="color:var(--red);">Failed to load daily log details.</p>';
    }
}

document.addEventListener('DOMContentLoaded', fetchLogDetails);
</script>
@endpush
@endsection
