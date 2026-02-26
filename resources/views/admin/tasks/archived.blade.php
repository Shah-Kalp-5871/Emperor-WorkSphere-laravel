@extends('layouts.admin.master')

@section('title', 'WorkSphere — Archived Tasks')

@section('content')
<div class="page active" id="page-archived-tasks">
    <div class="section-header">
        <div>
            <div class="section-title">Archived Tasks</div>
            <div class="section-sub" id="archived-summary">Soft-deleted tasks · Restorable</div>
        </div>
        <div class="section-actions">
            <a href="{{ url('/admin/tasks') }}" class="btn btn-ghost btn-sm">← Back to Active Tasks</a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table id="tbl-archived-tasks">
                <thead><tr>
                    <th>Task</th><th>Project</th><th>Status</th><th>Priority</th><th>Archived On</th><th>Actions</th>
                </tr></thead>
                <tbody id="archived-tbody"></tbody>
            </table>

            <div id="table-loading" style="text-align:center;padding:40px;display:none;">
                <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:30px;height:30px;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
                <div style="color:var(--text-2);font-size:14px;">Loading archived tasks...</div>
            </div>

            <div id="table-empty" style="text-align:center;padding:40px;display:none;">
                <div style="font-size:28px;margin-bottom:10px;">🗃</div>
                <div style="color:var(--text-2);font-size:14px;">No archived tasks found.</div>
            </div>

            <div id="table-error" style="text-align:center;padding:40px;display:none;color:#ff4d4d;">
                <div style="font-size:20px;margin-bottom:10px;">⚠️</div>
                <div id="error-message" style="font-size:14px;">Failed to load archived tasks.</div>
            </div>
        </div>

        <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-top:1px solid var(--border);">
            <div id="pagination-info" style="color:var(--text-2);font-size:13px;">Showing 0 of 0 archived tasks</div>
            <div style="display:flex;gap:8px;">
                <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
                <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
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
    let currentPage = 1;
    function statusClass(s) {
        const m = {pending:'t-pending', in_progress:'t-in_progress', completed:'t-completed', on_hold:'t-on_hold'};
        return m[s] || 't-pending';
    }

    async function fetchArchivedTasks(page = 1) {
        const tbody = document.getElementById('archived-tbody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info = document.getElementById('pagination-info');
        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        errorDiv.style.display = 'none';

        try {
            const res = await axios.get(`/api/admin/tasks/archived?page=${page}`);
            const data = res.data.data;
            const tasks = data.data ?? [];
            const meta = data.meta ?? {};
            loading.style.display = 'none';

            if (tasks.length === 0) { empty.style.display = 'block'; return; }

            tasks.forEach(t => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="td-main" style="opacity:.7;font-weight:600;">${t.title}</td>
                    <td>${t.project_name || 'N/A'}</td>
                    <td><span class="status-pill ${statusClass(t.status)}">${t.status}</span></td>
                    <td style="text-transform:capitalize;">${t.priority}</td>
                    <td>${new Date(t.deleted_at).toLocaleDateString()}</td>
                    <td><button class="btn btn-ghost btn-sm" onclick="restoreTask(${t.id}, '${t.title.replace(/'/g,"\\'")}')">♻ Restore</button></td>
                `;
                tbody.appendChild(tr);
            });

            currentPage = meta.current_page ?? 1;
            info.innerText = `Showing ${meta.from ?? 1} to ${meta.to ?? tasks.length} of ${meta.total ?? tasks.length} archived tasks`;
            document.getElementById('prev-page').disabled = currentPage === 1;
            document.getElementById('next-page').disabled = currentPage === (meta.last_page ?? 1);
        } catch (err) {
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = err.response?.data?.message || 'Failed to load archived tasks.';
        }
    }

    async function restoreTask(id, title) {
        if (!confirm(`Restore task "${title}"?`)) return;
        try {
            await axios.post(`/api/admin/tasks/${id}/restore`);
            fetchArchivedTasks(currentPage);
        } catch (err) { alert(err.response?.data?.message || 'Failed to restore.'); }
    }

    document.getElementById('prev-page').onclick = () => { if (currentPage > 1) fetchArchivedTasks(currentPage - 1); };
    document.getElementById('next-page').onclick = () => fetchArchivedTasks(currentPage + 1);
    document.addEventListener('DOMContentLoaded', () => fetchArchivedTasks());
</script>
@endpush
@endsection
