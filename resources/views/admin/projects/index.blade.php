@extends('layouts.admin.master')

@section('title', 'WorkSphere — Projects')

@section('content')
<div class="page active" id="page-projects">
    <div class="section-header">
    <div>
        <div class="section-title">Projects</div>
        <div class="section-sub" id="projects-summary">Loading...</div>
    </div>
    <div class="section-actions">
        <a href="{{ url('/admin/projects/archived') }}" class="btn btn-ghost btn-sm" style="margin-right:8px;">
            🗃 Archived
        </a>
        <a href="{{ url('/admin/projects/create') }}" class="btn btn-primary">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Project
        </a>
    </div>
    </div>



    <div class="filter-bar">
        <input class="filter-input" id="search-input" placeholder="Search projects…" oninput="debounceSearch()">
        <select class="filter-select" id="status-filter" onchange="fetchProjects(1)">
            <option value="">All Status</option>
            <option value="planning">Planning</option>
            <option value="active">Active</option>
            <option value="on_hold">On Hold</option>
            <option value="completed">Completed</option>
        </select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-projects">
        <thead><tr>
            <th>Project</th><th>Status</th><th>Priority</th><th>Creator</th><th>Members</th><th>Tasks</th><th>Progress</th><th>Created</th><th>Actions</th>
        </tr></thead>
        <tbody id="projects-tbody">
        </tbody>
    </table>

    <div id="table-loading" style="text-align:center;padding:40px;display:none;">
        <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:30px;height:30px;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
        <div style="color:var(--text-2);font-size:14px;">Loading projects...</div>
    </div>

    <div id="table-empty" style="text-align:center;padding:40px;display:none;">
        <div style="font-size:24px;margin-bottom:10px;">📁</div>
        <div style="color:var(--text-2);font-size:14px;">No projects found.</div>
    </div>

    <div id="table-error" style="text-align:center;padding:40px;display:none;color:#ff4d4d;">
        <div style="font-size:20px;margin-bottom:10px;">⚠️</div>
        <div id="error-message" style="font-size:14px;">Failed to load projects.</div>
    </div>
    </div>

    <div class="pagination-row" style="display:flex;justify-content:space-between;align-items:center;padding:16px;border-top:1px solid var(--border);">
        <div id="pagination-info" style="color:var(--text-2);font-size:13px;">Showing 0 of 0 projects</div>
        <div id="pagination-controls" style="display:flex;gap:8px;">
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
    .s-active    { background:var(--accent-lt,#22c55e20);color:var(--accent,#22c55e); }
    .s-on_hold   { background:#f59e0b20;color:#f59e0b; }
    .s-completed { background:#6366f120;color:#6366f1; }
    .p-high      { background:#ef444420;color:#ef4444; }
    .p-medium    { background:#f59e0b20;color:#f59e0b; }
    .p-low       { background:#6366f120;color:#6366f1; }
</style>

@push('scripts')
<script>
    let currentPage = 1;
    let searchTimeout = null;

    // ── Helpers ──────────────────────────────────────────────────────────
    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchProjects(1), 400);
    }

    function statusClass(s) {
        const map = { planning:'s-planning', active:'s-active', on_hold:'s-on_hold', completed:'s-completed' };
        return map[s] || 's-planning';
    }

    function priorityClass(p) {
        const map = { high:'p-high', medium:'p-medium', low:'p-low' };
        return map[p] || 'p-medium';
    }

    // ── Load employees for modals ─────────────────────────────────────────
    async function loadEmployeesForModal(selectId) {
        const select = document.getElementById(selectId);
        if (select.options.length > 0) return;
        try {
            const res = await axios.get('/api/admin/employees?per_page=200');
            const employees = res.data.data?.data ?? res.data.data ?? [];
            employees.forEach(emp => {
                const opt = document.createElement('option');
                opt.value = emp.id;
                opt.textContent = `${emp.user?.name ?? 'Unknown'} (${emp.employee_code})`;
                select.appendChild(opt);
            });
        } catch (e) {
            console.error('Failed to load employees:', e);
        }
    }



    // ── ARCHIVE ────────────────────────────────────────────────────────────
    async function archiveProject(id, name) {
        if (!confirm(`Archive project "${name}"? It can be restored later.`)) return;
        try {
            const res = await axios.delete(`/api/admin/projects/${id}`);
            if (res.data.success) fetchProjects(currentPage);
        } catch (err) {
            alert(err.response?.data?.message || 'Failed to archive project.');
        }
    }

    // ── FETCH LIST ─────────────────────────────────────────────────────────
    async function fetchProjects(page = 1) {
        const tbody    = document.getElementById('projects-tbody');
        const loading  = document.getElementById('table-loading');
        const empty    = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info     = document.getElementById('pagination-info');
        const prevBtn  = document.getElementById('prev-page');
        const nextBtn  = document.getElementById('next-page');

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display   = 'none';
        errorDiv.style.display = 'none';

        const status = document.getElementById('status-filter').value;
        const search = document.getElementById('search-input').value;

        try {
            const params = new URLSearchParams({ page });
            if (status) params.append('status', status);
            if (search) params.append('search', search);

            const res   = await axios.get(`/api/admin/projects?${params}`);
            const body  = res.data;

            // Standardized response: { success, message, data: { data: [...], meta: {...} } }
            const projects   = body.data?.data ?? [];
            const meta       = body.data?.meta ?? {};

            loading.style.display = 'none';

            // Update summary badge
            document.getElementById('projects-summary').textContent =
                `${meta.total ?? projects.length} total`;

            if (projects.length === 0) { empty.style.display = 'block'; return; }

            projects.forEach(p => {
                const progress    = p.tasks_count > 0 ? Math.round((p.completed_tasks_count / p.tasks_count) * 100) : 0;
                const progressColor = progress > 70 ? 'var(--accent)' : progress > 30 ? '#f59e0b' : '#ef4444';
                const createdDate = p.created_at ? new Date(p.created_at).toLocaleDateString('en-US', { month:'short', day:'numeric' }) : '—';

                let avatarsHtml = '';
                (p.members ?? []).slice(0, 3).forEach((m, i) => {
                    avatarsHtml += `<div class="av${i>0?' av'+(i+1):''}">${(m.name[0]||'?').toUpperCase()}</div>`;
                });
                if ((p.members ?? []).length > 3) {
                    avatarsHtml += `<div class="av av4">+${p.members.length - 3}</div>`;
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="td-main">
                        <a href="/admin/projects/${p.id}" style="color:inherit;text-decoration:none;font-weight:600;">${p.name}</a>
                        ${p.description ? `<div style="font-size:11px;color:var(--text-3);margin-top:2px;">${p.description.substring(0,50)}${p.description.length>50?'…':''}</div>` : ''}
                    </td>
                    <td><span class="status-pill ${statusClass(p.status)}">${(p.status||'').replace('_',' ')}</span></td>
                    <td><span class="status-pill ${priorityClass(p.priority)}">${p.priority||'—'}</span></td>
                    <td>${p.creator_name}</td>
                    <td><div class="avatar-group">${avatarsHtml || '—'}</div></td>
                    <td>${p.tasks_count} tasks · ${p.completed_tasks_count} done</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="progress-bar-wrap" style="width:70px">
                                <div class="progress-bar" style="width:${progress}%;background:${progressColor}"></div>
                            </div>
                            <span style="font-size:12px;color:var(--text-3)">${progress}%</span>
                        </div>
                    </td>
                    <td>${createdDate}</td>
                    <td style="display:flex;gap:6px;padding-top:13px">
                        <button class="btn btn-ghost btn-sm" onclick="window.location.href='/admin/projects/${p.id}'">View</button>
                        <button class="btn btn-ghost btn-sm" onclick="window.location.href='/admin/projects/${p.id}/edit'">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="archiveProject(${p.id}, '${p.name.replace(/'/g,"\\'")}')">Archive</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Pagination
            currentPage          = meta.current_page ?? 1;
            info.innerText       = `Showing ${meta.from ?? 1} to ${meta.to ?? projects.length} of ${meta.total ?? projects.length} projects`;
            prevBtn.disabled     = currentPage === 1;
            nextBtn.disabled     = currentPage === (meta.last_page ?? 1);

        } catch (err) {
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText =
                err.response?.data?.message || 'Failed to load projects.';
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchProjects(currentPage - 1);
    });
    document.getElementById('next-page').addEventListener('click', () => {
        fetchProjects(currentPage + 1);
    });

    document.addEventListener('DOMContentLoaded', () => fetchProjects());
</script>
@endpush
@endsection
