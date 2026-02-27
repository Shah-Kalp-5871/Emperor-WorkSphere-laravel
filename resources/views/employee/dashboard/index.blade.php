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

    <!-- ATTENDANCE & STATS -->
    <div class="top-content-grid">
      <!-- ATTENDANCE CARD -->
      <div class="panel attendance-panel">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Office Attendance
          </div>
          <span id="attendance-status-badge" class="status-badge checking">Checking...</span>
        </div>
        <div class="attendance-body">
          <div id="attendance-main-action">
            <div class="spinner-sm"></div>
          </div>
          <div id="attendance-location-info" class="location-info">
             <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
             <span id="location-text">Detecting location...</span>
          </div>
        </div>
      </div>

      <!-- STATS -->
      <div class="stats-row-grid" id="stats-container">
        @foreach(range(1, 4) as $i)
        <div class="stat-card skeleton-card">
          <div class="skeleton-text" style="width: 40%; height: 12px; margin-bottom: 8px;"></div>
          <div class="skeleton-text" style="width: 30%; height: 24px;"></div>
        </div>
        @endforeach
      </div>
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
    .top-content-grid { display: grid; grid-template-columns: 350px 1fr; gap: 20px; margin-bottom: 25px; }
    .attendance-panel { height: 100%; display: flex; flex-direction: column; min-height: 220px; }
    .attendance-body { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 24px 20px; text-align: center; gap: 14px; }

    /* Status badge */
    .status-badge { font-size: 11px; padding: 3px 10px; border-radius: 20px; font-weight: 600; border: 1px solid transparent; }
    .status-badge.checking  { background: #f1f5f9; color: #94a3b8; }
    .status-badge.not-punched { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
    .status-badge.punched-in  { background: #dcfce7; color: #16a34a; border-color: #bbf7d0; }
    .status-badge.punched-out { background: #dbeafe; color: #2563eb; border-color: #bfdbfe; }

    /* Punch button — circular */
    .punch-btn-wrap { position: relative; display: flex; align-items: center; justify-content: center; }
    .punch-ring { position: absolute; width: 136px; height: 136px; border-radius: 50%; animation: pulse-ring 2s ease-out infinite; }
    .punch-ring.green { border: 3px solid #22c55e; }
    .punch-ring.blue  { border: 3px solid #3b82f6; }
    @keyframes pulse-ring { 0% { transform: scale(0.9); opacity: 0.8; } 100% { transform: scale(1.25); opacity: 0; } }

    .punch-btn {
        width: 120px; height: 120px; border-radius: 50%; border: none; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative; z-index: 1;
    }
    .punch-btn:disabled { opacity: 0.5; cursor: not-allowed; animation: none !important; }
    .punch-btn:disabled .punch-ring { display: none; }

    .punch-btn.in  { background: #16a34a; color: #fff; box-shadow: 0 8px 24px rgba(22,163,74,0.35); }
    .punch-btn.in:not(:disabled):hover  { transform: scale(1.06); box-shadow: 0 12px 30px rgba(22,163,74,0.5); }
    .punch-btn.out { background: #2563eb; color: #fff; box-shadow: 0 8px 24px rgba(37,99,235,0.35); }
    .punch-btn.out:not(:disabled):hover { transform: scale(1.06); box-shadow: 0 12px 30px rgba(37,99,235,0.5); }

    .punch-btn svg  { display: block; margin-bottom: 4px; }
    .punch-btn span { font-weight: 700; font-size: 13px; letter-spacing: 0.4px; }

    /* Location chip */
    .location-info { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 20px; }

    /* Stats grid (2x2 on right) */
    .stats-row-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    @media (max-width: 1024px) { .top-content-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px)  { .stats-row-grid  { grid-template-columns: 1fr; } }

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
    // ATTENDANCE LOGIC
    let currentCoords = null;

    async function checkAttendanceStatus() {
        try {
            const res = await axios.get('/api/employee/attendance/status');
            updateAttendanceUI(res.data.attendance_status);
        } catch (err) {
            console.error('Attendance Status Error:', err);
        }
    }

    function updateAttendanceUI(status) {
        const badge = document.getElementById('attendance-status-badge');
        const main  = document.getElementById('attendance-main-action');

        badge.className   = 'status-badge ' + status.toLowerCase().replace(/_/g, '-');
        badge.textContent = status.replace(/_/g, ' ');

        if (status === 'NOT_PUNCHED') {
            main.innerHTML = `
                <div class="punch-btn-wrap">
                    <div class="punch-ring green"></div>
                    <button class="punch-btn in" onclick="handlePunch('in')">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"/>
                        </svg>
                        <span>Punch In</span>
                    </button>
                </div>
                <div style="font-size:12px;color:#94a3b8">Tap to mark your arrival</div>
            `;
        } else if (status === 'PUNCHED_IN') {
            main.innerHTML = `
                <div class="punch-btn-wrap">
                    <div class="punch-ring blue"></div>
                    <button class="punch-btn out" onclick="handlePunch('out')">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M19.8 12H9"/>
                        </svg>
                        <span>Punch Out</span>
                    </button>
                </div>
                <div style="font-size:12px;color:#94a3b8">Tap when leaving office</div>
            `;
        } else {
            main.innerHTML = `
                <div style="display:flex;flex-direction:column;align-items:center;gap:10px">
                    <div style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div style="font-size:13px;font-weight:600;color:#16a34a">All done for today!</div>
                    <div style="font-size:12px;color:#94a3b8">Attendance recorded successfully</div>
                </div>
            `;
        }
    }

    async function handlePunch(type) {
        const btn = document.querySelector('.punch-btn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner-sm" style="border-top-color:white"></div>';

        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            resetPunchBtn(btn, originalContent);
            return;
        }

        navigator.geolocation.getCurrentPosition(async (pos) => {
            try {
                const { latitude, longitude } = pos.coords;
                const url = type === 'in' ? '/api/employee/attendance/punch-in' : '/api/employee/attendance/punch-out';
                
                const res = await axios.post(url, { latitude, longitude });
                
                // Show success notification (simplified for now)
                alert(res.data.message);
                checkAttendanceStatus();
            } catch (err) {
                alert(err.response?.data?.message || 'Action failed');
                resetPunchBtn(btn, originalContent);
            }
        }, (err) => {
            alert('Location access denied or unavailable.');
            resetPunchBtn(btn, originalContent);
        });
    }

    function resetPunchBtn(btn, content) {
        btn.disabled = false;
        btn.innerHTML = content;
    }

    function trackLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition((pos) => {
                document.getElementById('location-text').textContent = 
                    `Lat: ${pos.coords.latitude.toFixed(4)}, Lon: ${pos.coords.longitude.toFixed(4)}`;
            }, (err) => {
                document.getElementById('location-text').textContent = 'Location unavailable';
            });
        }
    }

    async function loadDashboard() {
        // Run checks in parallel
        checkAttendanceStatus();
        trackLocation();
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