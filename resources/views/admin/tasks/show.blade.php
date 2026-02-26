@extends('layouts.admin.master')

@section('title', 'WorkSphere — Task Detail')

@section('content')
<div class="page active" id="page-task-detail">
    <div style="margin-bottom:20px;">
        <a href="/admin/tasks" class="btn btn-ghost btn-sm">← Back to Tasks</a>
    </div>

    <div id="detail-loading" style="text-align:center;padding:60px;">
        <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:36px;height:36px;animation:spin 1s linear infinite;margin:0 auto 14px;"></div>
        <div style="color:var(--text-2);font-size:14px;">Loading task...</div>
    </div>

    <div id="detail-error" style="text-align:center;padding:60px;display:none;color:#ff4d4d;">
        <div style="font-size:28px;margin-bottom:10px;">⚠️</div>
        <div id="detail-error-msg" style="font-size:14px;">Failed to load task.</div>
    </div>

    <div id="detail-content" style="display:none;">
        <div class="section-header">
            <div>
                <div class="section-title" id="task-title">—</div>
                <div class="section-sub">
                    <span class="status-pill" id="task-status-badge">—</span>
                    &nbsp;·&nbsp;
                    <span style="color:var(--text-3);font-size:13px;">Priority: <strong id="task-priority-badge">—</strong></span>
                </div>
            </div>
            <div class="section-actions">
                <button class="btn btn-ghost btn-sm" onclick="window.location.href='/admin/tasks'">Edit in List</button>
                <button class="btn btn-danger btn-sm" id="archive-task-btn">Archive</button>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header"><div class="card-title">Task Details</div></div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Description</div>
                            <div class="detail-value" id="task-desc-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Project</div>
                            <div class="detail-value" id="task-project-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Created By</div>
                            <div class="detail-value" id="task-creator-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Due Date</div>
                            <div class="detail-value" id="task-due-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Date Added</div>
                            <div class="detail-value" id="task-created-val">—</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title">Assignees</div></div>
                <div class="card-body" id="task-assignees-list">
                    <div style="color:var(--text-3);font-size:13px;">No assignees.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .status-pill { display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.03em; }
    .t-pending   { background:#f59e0b20;color:#f59e0b; }
    .t-in_progress { background:#3b82f620;color:#3b82f6; }
    .t-completed { background:#22c55e20;color:#22c55e; }
</style>

@push('scripts')
<script>
    const taskId = {{ $taskId }};
    function statusPillClass(s) {
        const m = {pending:'t-pending', in_progress:'t-in_progress', completed:'t-completed', on_hold:'t-on_hold'};
        return m[s] || 't-pending';
    }

    async function loadTask() {
        const loading = document.getElementById('detail-loading');
        const error = document.getElementById('detail-error');
        const content = document.getElementById('detail-content');
        try {
            const res = await axios.get(`/api/admin/tasks/${taskId}`);
            const t = res.data.data;
            document.getElementById('task-title').textContent = t.title;
            document.getElementById('task-status-badge').textContent = t.status.replace('_', ' ');
            document.getElementById('task-status-badge').className = 'status-pill ' + statusPillClass(t.status);
            document.getElementById('task-priority-badge').textContent = t.priority;
            document.getElementById('task-desc-val').textContent = t.description || 'No description.';
            document.getElementById('task-project-val').textContent = t.project_name || 'N/A';
            document.getElementById('task-creator-val').textContent = t.creator_name || 'Admin';
            document.getElementById('task-due-val').textContent = t.due_date || '—';
            document.getElementById('task-created-val').textContent = new Date(t.created_at).toLocaleDateString();

            const assigneesList = document.getElementById('task-assignees-list');
            if (t.assignees && t.assignees.length > 0) {
                assigneesList.innerHTML = t.assignees.map(a => `
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:13px;flex-shrink:0;">
                            ${(a.name[0]||'?').toUpperCase()}
                        </div>
                        <div style="font-size:13px;font-weight:500;">${a.name}</div>
                    </div>
                `).join('');
            }

            document.getElementById('archive-task-btn').onclick = () => archiveTask(t.id, t.title);
            loading.style.display = 'none';
            content.style.display = 'block';
        } catch (err) {
            loading.style.display = 'none';
            document.getElementById('detail-error-msg').textContent = err.response?.data?.message || 'Failed to load task.';
            error.style.display = 'block';
        }
    }

    async function archiveTask(id, title) {
        if (!confirm(`Archive task "${title}"?`)) return;
        try {
            await axios.delete(`/api/admin/tasks/${id}`);
            window.location.href = '/admin/tasks';
        } catch (err) { alert(err.response?.data?.message || 'Failed to archive.'); }
    }

    document.addEventListener('DOMContentLoaded', loadTask);
</script>
@endpush
@endsection
