@extends('layouts.admin.master')

@section('title', 'WorkSphere — Archived Projects')

@section('content')
<div class="page active" id="page-archived-projects">
    <div class="section-header">
        <div>
            <div class="section-title">Archived Projects</div>
            <div class="section-sub" id="archived-summary">Soft-deleted projects · Restorable</div>
        </div>
        <div class="section-actions">
            <a href="{{ url('/admin/projects') }}" class="btn btn-ghost btn-sm">
                ← Back to Active Projects
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table id="tbl-archived-projects">
                <thead><tr>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Creator</th>
                    <th>Archived On</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody id="archived-tbody"></tbody>
            </table>

            <div id="table-loading" style="text-align:center;padding:40px;display:none;">
                <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:30px;height:30px;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
                <div style="color:var(--text-2);font-size:14px;">Loading archived projects...</div>
            </div>

            <div id="table-empty" style="text-align:center;padding:40px;display:none;">
                <div style="font-size:28px;margin-bottom:10px;">🗃</div>
                <div style="color:var(--text-2);font-size:14px;">No archived projects found.</div>
                <a href="/admin/projects" style="font-size:13px;color:var(--accent);margin-top:8px;display:inline-block;">Go to active projects →</a>
            </div>

            <div id="table-error" style="text-align:center;padding:40px;display:none;color:#ff4d4d;">
                <div style="font-size:20px;margin-bottom:10px;">⚠️</div>
                <div id="error-message" style="font-size:14px;">Failed to load archived projects.</div>
            </div>
        </div>

        <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-top:1px solid var(--border);">
            <div id="pagination-info" style="color:var(--text-2);font-size:13px;">Showing 0 of 0 archived projects</div>
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
    .s-planning  { background:#3b82f620;color:#3b82f6; }
    .s-active    { background:#22c55e20;color:#22c55e; }
    .s-on_hold   { background:#f59e0b20;color:#f59e0b; }
    .s-completed { background:#6366f120;color:#6366f1; }
    .p-high      { background:#ef444420;color:#ef4444; }
    .p-medium    { background:#f59e0b20;color:#f59e0b; }
    .p-low       { background:#6366f120;color:#6366f1; }
</style>

@push('scripts')
<script>
    let currentPage = 1;

    function statusClass(s) {
        const m = {planning:'s-planning', active:'s-active', on_hold:'s-on_hold', completed:'s-completed'};
        return m[s] || 's-planning';
    }

    function priorityClass(p) {
        const m = {high:'p-high', medium:'p-medium', low:'p-low'};
        return m[p] || 'p-medium';
    }

    async function fetchArchivedProjects(page = 1) {
        const tbody    = document.getElementById('archived-tbody');
        const loading  = document.getElementById('table-loading');
        const empty    = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info     = document.getElementById('pagination-info');
        const prevBtn  = document.getElementById('prev-page');
        const nextBtn  = document.getElementById('next-page');

        tbody.innerHTML = '';
        loading.style.display  = 'block';
        empty.style.display    = 'none';
        errorDiv.style.display = 'none';

        try {
            const res  = await axios.get(`/api/admin/projects/archived?page=${page}`);
            const body = res.data;
            const projects = body.data?.data ?? [];
            const meta     = body.data?.meta ?? {};

            loading.style.display = 'none';

            document.getElementById('archived-summary').textContent =
                `${meta.total ?? projects.length} archived projects · Restorable`;

            if (projects.length === 0) { empty.style.display = 'block'; return; }

            projects.forEach(p => {
                const archivedDate = p.deleted_at
                    ? new Date(p.deleted_at).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'})
                    : '—';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="td-main" style="opacity:.7;">
                        <span style="font-weight:600;">${p.name}</span>
                        ${p.description ? `<div style="font-size:11px;color:var(--text-3);margin-top:2px;">${p.description.substring(0,60)}${p.description.length>60?'…':''}</div>` : ''}
                    </td>
                    <td><span class="status-pill ${statusClass(p.status)}">${(p.status||'').replace('_',' ')}</span></td>
                    <td><span class="status-pill ${priorityClass(p.priority)}">${p.priority || '—'}</span></td>
                    <td>${p.creator_name}</td>
                    <td style="color:var(--text-3);">${archivedDate}</td>
                    <td>
                        <button class="btn btn-ghost btn-sm" onclick="restoreProject(${p.id}, '${p.name.replace(/'/g, "\\'")}')">
                            ♻ Restore
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            currentPage      = meta.current_page ?? 1;
            info.innerText   = `Showing ${meta.from ?? 1} to ${meta.to ?? projects.length} of ${meta.total ?? projects.length} archived projects`;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === (meta.last_page ?? 1);

        } catch (err) {
            loading.style.display  = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText =
                err.response?.data?.message || 'Failed to load archived projects.';
        }
    }

    async function restoreProject(id, name) {
        if (!confirm(`Restore project "${name}"? It will become active again.`)) return;
        try {
            const res = await axios.post(`/api/admin/projects/${id}/restore`);
            if (res.data.success) fetchArchivedProjects(currentPage);
        } catch (err) {
            alert(err.response?.data?.message || 'Failed to restore project.');
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchArchivedProjects(currentPage - 1);
    });
    document.getElementById('next-page').addEventListener('click', () => {
        fetchArchivedProjects(currentPage + 1);
    });

    document.addEventListener('DOMContentLoaded', () => fetchArchivedProjects());
</script>
@endpush
@endsection
