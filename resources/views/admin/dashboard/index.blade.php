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

    <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Employees</div>
        <div class="stat-value">12</div>
        <div class="stat-sub">↑ 2 added this month</div>
        <div class="stat-icon">👥</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Active Projects</div>
        <div class="stat-value">5</div>
        <div class="stat-sub">18 tasks in progress</div>
        <div class="stat-icon">📁</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-label">Logs Submitted</div>
        <div class="stat-value">9<span style="font-size:18px;color:var(--text3)">/12</span></div>
        <div class="stat-sub">3 pending today</div>
        <div class="stat-icon">📝</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Overdue Tasks</div>
        <div class="stat-value">3</div>
        <div class="stat-sub">Requires attention</div>
        <div class="stat-icon">⚠️</div>
    </div>
    </div>

    <div class="grid-2">
    <!-- Active Projects -->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Active Projects</div>
        <span class="card-action" onclick="window.location.href='{{ url('/admin/projects') }}'">View all →</span>
        </div>
        <div class="card-body">
        <div class="project-item">
            <div class="project-dot"></div>
            <div class="project-info">
            <div class="project-name">Website Redesign</div>
            <div class="project-meta">4 members · 6 tasks</div>
            </div>
            <div class="project-progress">
            <div class="progress-text">72%</div>
            <div class="progress-bar-wrap"><div class="progress-bar" style="width:72%"></div></div>
            </div>
        </div>
        <div class="project-item">
            <div class="project-dot green"></div>
            <div class="project-info">
            <div class="project-name">Mobile App v2</div>
            <div class="project-meta">3 members · 11 tasks</div>
            </div>
            <div class="project-progress">
            <div class="progress-text">45%</div>
            <div class="progress-bar-wrap"><div class="progress-bar" style="width:45%; background:var(--accent2)"></div></div>
            </div>
        </div>
        <div class="project-item">
            <div class="project-dot orange"></div>
            <div class="project-info">
            <div class="project-name">API Integration</div>
            <div class="project-meta">2 members · 4 tasks</div>
            </div>
            <div class="project-progress">
            <div class="progress-text">90%</div>
            <div class="progress-bar-wrap"><div class="progress-bar" style="width:90%; background:var(--accent3)"></div></div>
            </div>
        </div>
        <div class="project-item">
            <div class="project-dot"></div>
            <div class="project-info">
            <div class="project-name">Data Analytics Dashboard</div>
            <div class="project-meta">5 members · 8 tasks</div>
            </div>
            <div class="project-progress">
            <div class="progress-text">28%</div>
            <div class="progress-bar-wrap"><div class="progress-bar" style="width:28%"></div></div>
            </div>
        </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
        <div class="card-title">Activity Feed</div>
        <span class="card-action" onclick="window.location.href='{{ url('/admin/timeline') }}'">Full timeline →</span>
        </div>
        <div class="card-body">
        <div class="activity-item">
            <div class="activity-dot-col"><div class="act-dot"></div><div class="act-line"></div></div>
            <div><div class="act-text"><strong>Priya S.</strong> marked task <em>"Deploy staging server"</em> as Done</div><div class="act-time">2 min ago</div></div>
        </div>
        <div class="activity-item">
            <div class="activity-dot-col"><div class="act-dot" style="background:var(--accent2)"></div><div class="act-line"></div></div>
            <div><div class="act-text"><strong>Ravi K.</strong> was added to <em>Mobile App v2</em></div><div class="act-time">34 min ago</div></div>
        </div>
        <div class="activity-item">
            <div class="activity-dot-col"><div class="act-dot" style="background:var(--accent3)"></div><div class="act-line"></div></div>
            <div><div class="act-text">Task <em>"Fix login bug"</em> assigned to <strong>Ankit M.</strong></div><div class="act-time">1 hr ago</div></div>
        </div>
        <div class="activity-item">
            <div class="activity-dot-col"><div class="act-dot" style="background:var(--danger)"></div><div class="act-line" style="display:none"></div></div>
            <div><div class="act-text">Project <em>"Analytics Dashboard"</em> created by <strong>Sara J.</strong></div><div class="act-time">2 hr ago</div></div>
        </div>
        </div>
    </div>
    </div>

    <!-- Overdue Tasks Quick View -->
    <div class="card" style="margin-bottom:0">
    <div class="card-header">
        <div class="card-title" style="color:var(--danger)">⚠️ Overdue Tasks</div>
        <span class="card-action" onclick="window.location.href='{{ url('/admin/tasks') }}'">Manage tasks →</span>
    </div>
    <div class="card-body">
        <div class="table-wrap">
        <table>
            <thead><tr>
            <th>Task</th><th>Project</th><th>Assigned To</th><th>Due Date</th><th>Priority</th>
            </tr></thead>
            <tbody>
            <tr>
                <td class="td-main">Fix payment gateway</td>
                <td>API Integration</td>
                <td>Ankit M.</td>
                <td><span class="overdue">Feb 20 · 4 days late</span></td>
                <td><span class="badge badge-red">High</span></td>
            </tr>
            <tr>
                <td class="td-main">Update user documentation</td>
                <td>Website Redesign</td>
                <td>Priya S.</td>
                <td><span class="overdue">Feb 22 · 2 days late</span></td>
                <td><span class="badge badge-orange">Medium</span></td>
            </tr>
            <tr>
                <td class="td-main">Write unit tests</td>
                <td>Mobile App v2</td>
                <td>Ravi K.</td>
                <td><span class="overdue">Feb 23 · 1 day late</span></td>
                <td><span class="badge badge-blue">Low</span></td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</div>
@endsection
