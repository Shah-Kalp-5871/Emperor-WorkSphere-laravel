@extends('layouts.admin.master')

@section('title', 'WorkSphere — Project Detail')

@section('content')
<div class="page active" id="page-project-detail">

    {{-- Back button --}}
    <div style="margin-bottom:20px;">
        <a href="/admin/projects" class="btn btn-ghost btn-sm">
            ← Back to Projects
        </a>
    </div>

    {{-- Loading skeleton --}}
    <div id="detail-loading" style="text-align:center;padding:60px;">
        <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:36px;height:36px;animation:spin 1s linear infinite;margin:0 auto 14px;"></div>
        <div style="color:var(--text-2);font-size:14px;">Loading project...</div>
    </div>

    {{-- Error state --}}
    <div id="detail-error" style="text-align:center;padding:60px;display:none;color:#ff4d4d;">
        <div style="font-size:28px;margin-bottom:10px;">⚠️</div>
        <div id="detail-error-msg" style="font-size:14px;">Failed to load project.</div>
    </div>

    {{-- Content --}}
    <div id="detail-content" style="display:none;">

        {{-- Header --}}
        <div class="section-header">
            <div>
                <div class="section-title" id="proj-title">—</div>
                <div class="section-sub">
                    <span class="status-pill" id="proj-status-badge">—</span>
                    &nbsp;·&nbsp;
                    <span style="color:var(--text-3);font-size:13px;">Priority: <strong id="proj-priority-badge">—</strong></span>
                </div>
            </div>
            <div class="section-actions">
                <button class="btn btn-ghost btn-sm" onclick="openEditFromDetail()">Edit</button>
                <button class="btn btn-danger btn-sm" id="archive-detail-btn">Archive</button>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="stats-grid" style="margin-bottom:24px;">
            <div class="stat-card">
                <div class="stat-label">Members</div>
                <div class="stat-value" id="proj-members-count">—</div>
                <div class="stat-sub">Assigned employees</div>
                <div class="stat-icon">👥</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Total Tasks</div>
                <div class="stat-value" id="proj-tasks-count">—</div>
                <div class="stat-sub">In this project</div>
                <div class="stat-icon">📋</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Completed</div>
                <div class="stat-value" id="proj-done-count">—</div>
                <div class="stat-sub">Tasks done</div>
                <div class="stat-icon">✅</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Progress</div>
                <div class="stat-value" id="proj-progress-pct">—</div>
                <div class="stat-sub">Overall completion</div>
                <div class="stat-icon">📊</div>
            </div>
        </div>

        <div class="grid-2">
            {{-- Project Description --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Project Details</div>
                </div>
                <div class="card-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Description</div>
                            <div class="detail-value" id="proj-desc-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Created by</div>
                            <div class="detail-value" id="proj-creator-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value" id="proj-start-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">End Date</div>
                            <div class="detail-value" id="proj-end-val">—</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Created</div>
                            <div class="detail-value" id="proj-created-val">—</div>
                        </div>
                    </div>
                    <div style="margin-top:20px;">
                        <div style="font-size:12px;color:var(--text-3);margin-bottom:6px;">Overall Progress</div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="progress-bar-wrap" style="flex:1;">
                                <div class="progress-bar" id="proj-progress-bar" style="width:0%;transition:width .4s ease;"></div>
                            </div>
                            <span style="font-size:13px;color:var(--text-2);" id="proj-progress-label">0%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Members --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Members</div>
                </div>
                <div class="card-body" id="proj-members-list">
                    <div style="color:var(--text-3);font-size:13px;">Loading members...</div>
                </div>
            </div>
        </div>

        {{-- Tasks List --}}
        <div class="card" style="margin-top:0;">
            <div class="card-header">
                <div class="card-title">Tasks</div>
                <a href="/admin/tasks" class="card-action">All tasks →</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr>
                        <th>Task</th><th>Status</th><th>Actions</th>
                    </tr></thead>
                    <tbody id="proj-tasks-list">
                        <tr><td colspan="3" style="text-align:center;color:var(--text-3);padding:20px;">Loading tasks...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /detail-content --}}
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .status-pill { display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.03em; }
    .s-planning  { background:#3b82f620;color:#3b82f6; }
    .s-active    { background:var(--accent-lt,#22c55e20);color:var(--accent,#22c55e); }
    .s-on_hold   { background:#f59e0b20;color:#f59e0b; }
    .s-completed { background:#6366f120;color:#6366f1; }
    .t-pending   { background:#f59e0b20;color:#f59e0b; }
    .t-in_progress { background:#3b82f620;color:#3b82f6; }
    .t-completed { background:#22c55e20;color:#22c55e; }
</style>

@push('scripts')
<script>
    const projectId = {{ $projectId }};

    function statusPillClass(s) {
        const map = {planning:'s-planning', active:'s-active', on_hold:'s-on_hold', completed:'s-completed'};
        return map[s] || 's-planning';
    }

    function taskStatusClass(s) {
        const map = {pending:'t-pending', in_progress:'t-in_progress', completed:'t-completed'};
        return map[s] || 't-pending';
    }

    async function loadProject() {
        const loading = document.getElementById('detail-loading');
        const error   = document.getElementById('detail-error');
        const content = document.getElementById('detail-content');

        try {
            const res = await axios.get(`/api/admin/projects/${projectId}`);
            const p   = res.data.data;

            document.getElementById('proj-title').textContent         = p.name;
            document.getElementById('proj-status-badge').textContent  = (p.status || '').replace('_', ' ');
            document.getElementById('proj-status-badge').className    = 'status-pill ' + statusPillClass(p.status);
            document.getElementById('proj-priority-badge').textContent = p.priority || '—';

            // Stats
            document.getElementById('proj-members-count').textContent = p.members_count ?? (p.members?.length ?? 0);
            document.getElementById('proj-tasks-count').textContent   = p.tasks_count ?? 0;
            document.getElementById('proj-done-count').textContent    = p.completed_tasks_count ?? 0;

            const pct = p.tasks_count > 0 ? Math.round((p.completed_tasks_count / p.tasks_count) * 100) : 0;
            document.getElementById('proj-progress-pct').textContent    = pct + '%';
            document.getElementById('proj-progress-bar').style.width    = pct + '%';
            document.getElementById('proj-progress-label').textContent  = pct + '%';

            // Details
            document.getElementById('proj-desc-val').textContent    = p.description || 'No description.';
            document.getElementById('proj-creator-val').textContent = p.creator_name || 'Admin';
            document.getElementById('proj-start-val').textContent   = p.start_date || '—';
            document.getElementById('proj-end-val').textContent     = p.end_date   || '—';
            document.getElementById('proj-created-val').textContent = p.created_at
                ? new Date(p.created_at).toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'})
                : '—';

            // Members
            const membersList = document.getElementById('proj-members-list');
            if (p.members && p.members.length > 0) {
                membersList.innerHTML = p.members.map(m => `
                    <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:13px;flex-shrink:0;">
                            ${(m.name[0]||'?').toUpperCase()}
                        </div>
                        <div style="font-size:13px;font-weight:500;">${m.name}</div>
                    </div>
                `).join('');
            } else {
                membersList.innerHTML = '<div style="color:var(--text-3);font-size:13px;">No members assigned.</div>';
            }

            // Tasks
            const tasksTbody = document.getElementById('proj-tasks-list');
            if (p.tasks && p.tasks.length > 0) {
                tasksTbody.innerHTML = p.tasks.map(t => `
                    <tr>
                        <td class="td-main">${t.title}</td>
                        <td><span class="status-pill ${taskStatusClass(t.status)}">${(t.status||'').replace('_',' ')}</span></td>
                        <td>
                            <a href="/admin/tasks" class="btn btn-ghost btn-sm">View in Tasks</a>
                        </td>
                    </tr>
                `).join('');
            } else {
                tasksTbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:var(--text-3);padding:20px;">No tasks yet.</td></tr>';
            }

            // Archive button
            document.getElementById('archive-detail-btn').onclick = () => archiveFromDetail(p.id, p.name);

            loading.style.display  = 'none';
            content.style.display  = 'block';

        } catch (err) {
            loading.style.display  = 'none';
            document.getElementById('detail-error-msg').textContent = err.response?.data?.message || 'Failed to load project.';
            error.style.display    = 'block';
        }
    }

    async function archiveFromDetail(id, name) {
        if (!confirm(`Archive project "${name}"?`)) return;
        try {
            await axios.delete(`/api/admin/projects/${id}`);
            window.location.href = '/admin/projects';
        } catch (err) {
            alert(err.response?.data?.message || 'Failed to archive.');
        }
    }

    function openEditFromDetail() {
        window.location.href = '/admin/projects';
    }

    document.addEventListener('DOMContentLoaded', loadProject);
</script>
@endpush
@endsection
