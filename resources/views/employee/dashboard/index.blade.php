@extends('layouts.employee.master')

@section('title', 'WorkSphere — Employee Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <!-- GREETING -->
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2 id="greeting-msg">Good afternoon, <em>...</em> 👋</h2>
        <p id="greeting-sub">Loading your schedule...</p>
      </div>
      <button class="greeting-btn" onclick="window.location.href='{{ url('/employee/dailylogs') }}'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Submit Today's Log
      </button>
    </div>

    <!-- STATS -->
    <div class="stats-row" id="stats-container">
      @foreach(range(1, 4) as $i)
      <div class="stat-card skeleton-card">
        <div class="skeleton-text" style="width: 40%; height: 12px; margin-bottom: 8px;"></div>
        <div class="skeleton-text" style="width: 30%; height: 24px;"></div>
      </div>
      @endforeach
    </div>

    <!-- TWO COL -->
    <div class="two-col">
      <!-- TASKS -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">My Tasks <span class="count" id="task-count">0</span></div>
          <a class="panel-link" href="{{ url('/employee/tasks') }}">View all →</a>
        </div>
        <div id="tasks-list">
            <div style="padding: 20px; text-align: center;"><div class="spinner-sm"></div></div>
        </div>
      </div>

      <!-- PROJECTS -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">My Projects <span class="count" id="project-count">0</span></div>
          <a class="panel-link" href="{{ url('/employee/projects') }}">View all →</a>
        </div>
        <div id="projects-list">
            <div style="padding: 20px; text-align: center;"><div class="spinner-sm"></div></div>
        </div>

        <!-- MINI CALENDAR placeholder remains static or we can wire it later if needed -->
      </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-row">
      <!-- DAILY LOG STATUS -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Today's Work Log
          </div>
          <span id="log-status-badge" style="font-size:11px;background:var(--amber-lt);color:var(--amber);padding:3px 9px;border-radius:20px;font-weight:500;border:1px solid #FDE68A">Checking...</span>
        </div>
        <div class="log-body">
          <div class="log-date">📅 {{ date('l, d F Y') }}</div>
          <p id="log-info" style="font-size: 13px; color: var(--text-2); margin: 10px 0;">Loading status...</p>
          <button class="log-save" onclick="window.location.href='{{ url('/employee/dailylogs') }}'">Go to Logs</button>
        </div>
      </div>

      <!-- ACTIVITY TIMELINE -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Recent Activity
          </div>
        </div>
        <div id="activity-timeline">
            <div style="padding: 20px; text-align: center;"><div class="spinner-sm"></div></div>
        </div>
      </div>
    </div>
@endsection

<style>
    .skeleton-card { background: var(--bg-1); border-color: var(--border); padding: 20px; border-radius: 12px; }
    .skeleton-text { background: linear-gradient(90deg, var(--border) 25%, var(--bg-2) 50%, var(--border) 75%); background-size: 200% 100%; animation: skeleton-shimmer 2s infinite; border-radius: 4px; }
    @keyframes skeleton-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .spinner-sm { width: 24px; height: 24px; border: 2px solid var(--border); border-top: 2px solid var(--accent); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    .status-pill { display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;text-transform:uppercase; }
    .priority.high { color: var(--danger); background: var(--red-lt); }
    .priority.medium { color: var(--amber); background: var(--amber-lt); }
    .priority.low { color: var(--accent); background: var(--blue-lt); }
    .task-due.late { color: var(--danger); }
</style>

@push('scripts')
<script>
    async function loadDashboard() {
        try {
            const res = await axios.get('/api/employee/dashboard');
            const data = res.data.data;

            // 1. Greet
            document.getElementById('greeting-msg').innerHTML = `Good morning, <em>${data.greeting.name.split(' ')[0]}</em> 👋`;
            document.getElementById('greeting-sub').textContent = `You have ${data.greeting.pending_tasks} pending tasks and ${data.greeting.log_submitted_today ? 'your daily log is submitted' : 'one daily log to complete'} today.`;

            // 2. Stats
            renderStats(data.stats);

            // 3. Tasks
            renderTasks(data.tasks);
            document.getElementById('task-count').textContent = data.stats.open_tasks;

            // 4. Projects
            renderProjects(data.projects);
            document.getElementById('project-count').textContent = data.stats.projects_assigned;

            // 5. Activity
            renderActivity(data.activities);

            // 6. Log Status
            const badge = document.getElementById('log-status-badge');
            const info = document.getElementById('log-info');
            if(data.greeting.log_submitted_today) {
                badge.textContent = 'Submitted';
                badge.style.background = 'var(--green-lt)';
                badge.style.color = 'var(--green)';
                badge.style.borderColor = '#A7F3D0';
                info.textContent = 'Great job! Your log for today has been recorded.';
            } else {
                badge.textContent = 'Not submitted';
                info.textContent = 'You haven\'t submitted your work activities for today yet.';
            }

        } catch (err) {
            console.error('Dashboard Load Error:', err);
        }
    }

    function renderStats(stats) {
        const container = document.getElementById('stats-container');
        container.innerHTML = `
            <div class="stat-card">
                <div class="stat-header">
                <div class="stat-icon green"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                <span class="stat-badge up">Active</span>
                </div>
                <div class="stat-value">${stats.projects_assigned}</div>
                <div class="stat-label">Projects Assigned</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                <div class="stat-icon amber"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
                <span class="stat-badge warn">Pending</span>
                </div>
                <div class="stat-value">${stats.open_tasks}</div>
                <div class="stat-label">Open Tasks</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                <div class="stat-icon blue"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <span class="stat-badge info">Month</span>
                </div>
                <div class="stat-value">${stats.logs_this_month}</div>
                <div class="stat-label">Logs Submitted</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                <div class="stat-icon red"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
                <span class="stat-badge down">Overdue</span>
                </div>
                <div class="stat-value">${stats.overdue_tasks}</div>
                <div class="stat-label">Overdue Tasks</div>
            </div>
        `;
    }

    function renderTasks(tasks) {
        const container = document.getElementById('tasks-list');
        if(!tasks.length) {
            container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-3)">No pending tasks.</div>';
            return;
        }

        container.innerHTML = tasks.map(t => `
            <div class="task-item">
                <div class="task-check ${t.status === 'completed' ? 'done' : ''}"></div>
                <div class="task-body">
                    <div class="task-name ${t.status === 'completed' ? 'striked' : ''}">${t.title}</div>
                    <div class="task-meta">
                        <span class="task-project">${t.project ? t.project.name : 'No Project'}</span>
                        <span class="priority ${t.priority}">${t.priority}</span>
                        <span class="task-due ${new Date(t.due_date) < new Date() && t.status !== 'completed' ? 'late' : ''}" style="margin-left:auto">
                            ${t.status === 'completed' ? '✓ Done' : t.due_date}
                        </span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderProjects(projects) {
        const container = document.getElementById('projects-list');
        if(!projects.length) {
            container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-3)">No projects assigned.</div>';
            return;
        }

        container.innerHTML = projects.map((p, idx) => {
            const colors = ['#2D6A4F', '#2563EB', '#D97706'];
            const color = colors[idx % 3];
            return `
                <div class="project-item">
                    <div class="proj-dot" style="background:${color}"></div>
                    <div class="proj-info">
                        <div class="proj-name">${p.name}</div>
                        <div class="proj-stat">${p.tasks_count} tasks · ${p.members_count} members</div>
                    </div>
                    <div class="proj-bar">
                        <div style="font-size:11px;color:var(--text-3);margin-bottom:3px;text-align:right">${p.progress}%</div>
                        <div class="proj-bar-bg"><div class="proj-bar-fill" style="width:${p.progress}%;background:${color}"></div></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderActivity(activities) {
        const container = document.getElementById('activity-timeline');
        if(!activities.length) {
            container.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-3)">No recent activity.</div>';
            return;
        }

        container.innerHTML = activities.map(act => `
            <div class="act-item">
                <div class="act-col">
                    <div class="act-dot" style="background:var(--accent)"></div>
                    <div class="act-line"></div>
                </div>
                <div>
                    <div class="act-text">${act.description}</div>
                    <div class="act-time">${act.created_at}</div>
                </div>
            </div>
        `).join('');
    }

    document.addEventListener('DOMContentLoaded', loadDashboard);
</script>
@endpush