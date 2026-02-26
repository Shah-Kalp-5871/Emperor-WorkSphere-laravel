@extends('layouts.admin.master')

@section('title', 'WorkSphere — Admin Dashboard')

@section('content')
<div class="page active" id="page-dashboard">
    <div class="section-header">
        <div>
            <div class="section-title">Good morning, Admin 👋</div>
            <div class="section-sub">Here's what's happening today — {{ date('M d, Y') }}</div>
        </div>
        <div class="section-actions">
            <button class="btn btn-ghost btn-sm" onclick="window.location.href='{{ url('/admin/calendar') }}'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Office: ON Today
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid" id="dashboard-stats">
        {{-- Skeleton Loaders --}}
        @foreach(range(1, 4) as $i)
        <div class="stat-card skeleton-card">
            <div class="skeleton-text" style="width: 60%; height: 14px; margin-bottom: 8px;"></div>
            <div class="skeleton-text" style="width: 40%; height: 28px; margin-bottom: 8px;"></div>
            <div class="skeleton-text" style="width: 70%; height: 12px;"></div>
        </div>
        @endforeach
    </div>

    <div class="grid-2">
        <!-- Active Projects -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Active Projects</div>
                <span class="card-action" onclick="window.location.href='{{ url('/admin/projects') }}'">View all →</span>
            </div>
            <div class="card-body" id="active-projects-list">
                <div class="table-loading" style="padding: 20px; text-align: center;">
                    <div class="spinner-sm"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Activity Feed</div>
                <span class="card-action" onclick="window.location.href='{{ url('/admin/timeline') }}'">Full timeline →</span>
            </div>
            <div class="card-body" id="activity-feed">
                <div class="table-loading" style="padding: 20px; text-align: center;">
                    <div class="spinner-sm"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <div class="card" style="margin-bottom:0">
        <div class="card-header">
            <div class="card-title" style="color:var(--danger)">⚠️ Overdue Tasks</div>
            <span class="card-action" onclick="window.location.href='{{ url('/admin/tasks') }}'">Manage tasks →</span>
        </div>
        <div class="card-body">
            <div class="table-wrap">
                <table id="tbl-overdue-tasks">
                    <thead>
                        <tr>
                            <th>Task</th><th>Project</th><th>Assigned To</th><th>Due Date</th><th>Priority</th>
                        </tr>
                    </thead>
                    <tbody id="overdue-tasks-tbody">
                        <tr><td colspan="5" style="text-align:center; padding:20px;"><div class="spinner-sm"></div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .skeleton-card { background: var(--bg-1); border-color: var(--border); }
    .skeleton-text { background: linear-gradient(90deg, var(--border) 25%, var(--bg-2) 50%, var(--border) 75%); background-size: 200% 100%; animation: skeleton-shimmer 2s infinite; border-radius: 4px; }
    @keyframes skeleton-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .spinner-sm { width: 20px; height: 20px; border: 2px solid var(--border); border-top: 2px solid var(--accent); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .overdue-tag { color: var(--danger); font-size: 11px; font-weight: 500; }
</style>

@push('scripts')
<script>
    async function loadDashboardData() {
        try {
            const res = await axios.get('/api/admin/dashboard/stats');
            const data = res.data.data;

            renderStats(data.stats);
            renderProjects(data.active_projects);
            renderActivity(data.recent_activity);
            renderOverdueTasks(data.overdue_tasks);
        } catch (err) {
            console.error('Dashboard Error:', err);
            // Show error state
        }
    }

    function renderStats(stats) {
        const grid = document.getElementById('dashboard-stats');
        grid.innerHTML = `
            <div class="stat-card">
                <div class="stat-label">Total Employees</div>
                <div class="stat-value">${stats.employees.total}</div>
                <div class="stat-sub">↑ ${stats.employees.new_this_month} added this month</div>
                <div class="stat-icon">👥</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Active Projects</div>
                <div class="stat-value">${stats.projects.active_count}</div>
                <div class="stat-sub">${stats.projects.tasks_in_progress} tasks in progress</div>
                <div class="stat-icon">📁</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-label">Logs Submitted Today</div>
                <div class="stat-value">${stats.logs.today_count}<span style="font-size:18px;color:rgba(255,255,255,0.6)">/${stats.logs.total_employees}</span></div>
                <div class="stat-sub">${stats.logs.pending_count} pending response</div>
                <div class="stat-icon">📝</div>
            </div>
            <div class="stat-card red">
                <div class="stat-label">Overdue Tasks</div>
                <div class="stat-value">${stats.tasks.overdue_count}</div>
                <div class="stat-sub">Requires immediate attention</div>
                <div class="stat-icon">⚠️</div>
            </div>
        `;
    }

    function renderProjects(projects) {
        const container = document.getElementById('active-projects-list');
        if (!projects || projects.length === 0) {
            container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-3);">No active projects.</div>';
            return;
        }

        container.innerHTML = projects.map((p, idx) => {
            const colors = ['', 'green', 'orange', 'red'];
            const dotColor = colors[idx % 4];
            return `
                <div class="project-item">
                    <div class="project-dot ${dotColor}"></div>
                    <div class="project-info">
                        <div class="project-name">${p.name}</div>
                        <div class="project-meta">${p.members_count} members · ${p.tasks_count} tasks</div>
                    </div>
                    <div class="project-progress">
                        <div class="progress-text">${p.progress}%</div>
                        <div class="progress-bar-wrap"><div class="progress-bar" style="width:${p.progress}%"></div></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderActivity(activities) {
        const container = document.getElementById('activity-feed');
        if (!activities || activities.length === 0) {
            container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-3);">No recent activity.</div>';
            return;
        }

        container.innerHTML = activities.map((act, idx) => `
            <div class="activity-item">
                <div class="activity-dot-col">
                    <div class="act-dot" style="background: ${idx % 2 === 0 ? 'var(--accent)' : 'var(--accent2)'}"></div>
                    ${idx < activities.length - 1 ? '<div class="act-line"></div>' : ''}
                </div>
                <div>
                    <div class="act-text">${act.description}</div>
                    <div class="act-time">${act.created_at}</div>
                </div>
            </div>
        `).join('');
    }

    function renderOverdueTasks(tasks) {
        const tbody = document.getElementById('overdue-tasks-tbody');
        if (!tasks || tasks.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:var(--text-3);">No overdue tasks! 🥳</td></tr>';
            return;
        }

        tbody.innerHTML = tasks.map(t => {
            const assignees = t.assignees && t.assignees.length > 0 
                ? t.assignees.map(a => a.name).join(', ') 
                : 'Unassigned';
            
            return `
                <tr>
                    <td class="td-main">${t.title}</td>
                    <td>${t.project_name || 'N/A'}</td>
                    <td>${assignees}</td>
                    <td><span class="overdue-tag">${t.due_date}</span></td>
                    <td><span class="badge badge-${t.priority === 'high' || t.priority === 'urgent' ? 'red' : 'orange'}">${t.priority}</span></td>
                </tr>
            `;
        }).join('');
    }

    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>
@endpush
@endsection
