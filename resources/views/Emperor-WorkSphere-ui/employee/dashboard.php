<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">

    <!-- GREETING -->
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>Good afternoon, <em>Kalp</em> 👋</h2>
        <p>You have 4 pending tasks and 1 daily log to complete today.</p>
      </div>
      <button class="greeting-btn" onclick="window.location.href='dailylogs.php'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Submit Today's Log
      </button>
    </div>

    <!-- STATS -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon green"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
          <span class="stat-badge up">Active</span>
        </div>
        <div class="stat-value">3</div>
        <div class="stat-label">Projects Assigned</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon amber"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg></div>
          <span class="stat-badge warn">Pending</span>
        </div>
        <div class="stat-value">4</div>
        <div class="stat-label">Open Tasks</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon blue"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
          <span class="stat-badge info">Feb</span>
        </div>
        <div class="stat-value">18</div>
        <div class="stat-label">Logs Submitted</div>
      </div>
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon red"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
          <span class="stat-badge down">Overdue</span>
        </div>
        <div class="stat-value">1</div>
        <div class="stat-label">Overdue Tasks</div>
      </div>
    </div>

    <!-- TWO COL -->
    <div class="two-col">
      <!-- TASKS -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">My Tasks <span class="count">4</span></div>
          <a class="panel-link" href="tasks.php">View all →</a>
        </div>
        <div class="task-item">
          <div class="task-check" onclick="toggleCheck(this)"></div>
          <div class="task-body">
            <div class="task-name">Design homepage wireframe for client review</div>
            <div class="task-meta">
              <span class="task-project">Website Redesign</span>
              <span class="priority high">High</span>
              <span class="task-due late" style="margin-left:auto">⚠ Feb 22 – Overdue</span>
            </div>
          </div>
        </div>
        <div class="task-item">
          <div class="task-check" onclick="toggleCheck(this)"></div>
          <div class="task-body">
            <div class="task-name">Write API documentation for auth module</div>
            <div class="task-meta">
              <span class="task-project">Backend API</span>
              <span class="priority medium">Medium</span>
              <span class="task-due" style="margin-left:auto">Feb 27</span>
            </div>
          </div>
        </div>
        <div class="task-item">
          <div class="task-check" onclick="toggleCheck(this)"></div>
          <div class="task-body">
            <div class="task-name">Fix responsive layout bugs on mobile</div>
            <div class="task-meta">
              <span class="task-project">Website Redesign</span>
              <span class="priority medium">Medium</span>
              <span class="task-due" style="margin-left:auto">Mar 1</span>
            </div>
          </div>
        </div>
        <div class="task-item">
          <div class="task-check done" onclick="toggleCheck(this)"></div>
          <div class="task-body">
            <div class="task-name striked">Set up staging environment</div>
            <div class="task-meta">
              <span class="task-project">DevOps</span>
              <span class="priority low">Low</span>
              <span class="task-due" style="margin-left:auto;color:var(--accent)">✓ Done</span>
            </div>
          </div>
        </div>
        <div class="task-item">
          <div class="task-check" onclick="toggleCheck(this)"></div>
          <div class="task-body">
            <div class="task-name">Prepare quarterly progress report</div>
            <div class="task-meta">
              <span class="task-project">Internal</span>
              <span class="priority low">Low</span>
              <span class="task-due" style="margin-left:auto">Mar 5</span>
            </div>
          </div>
        </div>
      </div>

      <!-- PROJECTS + MINI CALENDAR -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">My Projects <span class="count">3</span></div>
          <a class="panel-link" href="projects.php">View all →</a>
        </div>
        <div class="project-item">
          <div class="proj-dot" style="background:#2D6A4F"></div>
          <div class="proj-info"><div class="proj-name">Website Redesign</div><div class="proj-stat">6 tasks · 3 members</div></div>
          <div class="proj-bar">
            <div style="font-size:11px;color:var(--text-3);margin-bottom:3px;text-align:right">68%</div>
            <div class="proj-bar-bg"><div class="proj-bar-fill" style="width:68%;background:#2D6A4F"></div></div>
          </div>
        </div>
        <div class="project-item">
          <div class="proj-dot" style="background:#2563EB"></div>
          <div class="proj-info"><div class="proj-name">Backend API v2</div><div class="proj-stat">12 tasks · 5 members</div></div>
          <div class="proj-bar">
            <div style="font-size:11px;color:var(--text-3);margin-bottom:3px;text-align:right">40%</div>
            <div class="proj-bar-bg"><div class="proj-bar-fill" style="width:40%;background:#2563EB"></div></div>
          </div>
        </div>
        <div class="project-item">
          <div class="proj-dot" style="background:#D97706"></div>
          <div class="proj-info"><div class="proj-name">DevOps Migration</div><div class="proj-stat">4 tasks · 2 members</div></div>
          <div class="proj-bar">
            <div style="font-size:11px;color:var(--text-3);margin-bottom:3px;text-align:right">90%</div>
            <div class="proj-bar-bg"><div class="proj-bar-fill" style="width:90%;background:#D97706"></div></div>
          </div>
        </div>

        <!-- MINI CALENDAR -->
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <div style="font-size:13px;font-weight:600">February 2026</div>
            <div style="display:flex;gap:4px">
              <button style="background:none;border:1px solid var(--border);width:26px;height:26px;border-radius:5px;cursor:pointer;font-size:12px;color:var(--text-2)">‹</button>
              <button style="background:none;border:1px solid var(--border);width:26px;height:26px;border-radius:5px;cursor:pointer;font-size:12px;color:var(--text-2)">›</button>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:repeat(7,1fr);text-align:center;gap:2px">
            <?php
            foreach(['S','M','T','W','T','F','S'] as $d)
              echo "<div style='font-size:10px;font-weight:600;color:var(--text-3);padding:3px 0'>$d</div>";
            $today=24; $startDay=0; $totalDays=28;
            $offDays=[1,7,8,14,15,21,22,28];
            $logDays=[17,18,19,20,23];
            for($i=0;$i<$startDay;$i++) echo "<div></div>";
            for($d=1;$d<=$totalDays;$d++){
              $s='aspect-ratio:1;display:flex;align-items:center;justify-content:center;font-size:11px;border-radius:5px;cursor:pointer;position:relative;';
              if($d==$today) $s.='background:var(--accent);color:#fff;font-weight:600;';
              elseif(in_array($d,$offDays)) $s.='background:var(--red-lt);color:var(--red);';
              else $s.='color:var(--text-2);';
              $dot=in_array($d,$logDays)?"<span style='position:absolute;bottom:2px;left:50%;transform:translateX(-50%);width:3px;height:3px;border-radius:50%;background:".($d==$today?'#fff':'var(--accent)')."'></span>":'';
              echo "<div style='$s'>$d$dot</div>";
            }
            ?>
          </div>
          <div style="display:flex;gap:12px;margin-top:12px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text-3)"><span style="width:8px;height:8px;border-radius:2px;background:var(--accent);display:inline-block"></span>Today</div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text-3)"><span style="width:8px;height:8px;border-radius:2px;background:var(--red-lt);border:1px solid var(--red);display:inline-block"></span>Office Off</div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text-3)"><span style="width:6px;height:6px;border-radius:50%;background:var(--accent);display:inline-block"></span>Log done</div>
          </div>
        </div>
      </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-row">

      <!-- DAILY LOG FORM -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Today's Work Log
          </div>
          <span id="log-status" style="font-size:11px;background:var(--amber-lt);color:var(--amber);padding:3px 9px;border-radius:20px;font-weight:500;border:1px solid #FDE68A">Not submitted</span>
        </div>
        <div class="log-body">
          <div class="log-date">📅 Tuesday, 24 February 2026 — editable until midnight</div>
          <div class="log-sec">
            <div class="log-sec-label">🌅 Before Lunch</div>
            <textarea class="lf" placeholder="What did you work on before lunch?"></textarea>
            <input class="lf" type="url" placeholder="🔗 Work link (optional)" style="margin-top:6px;min-height:unset;padding:8px 12px"/>
          </div>
          <div class="log-sec">
            <div class="log-sec-label">🌆 After Lunch</div>
            <textarea class="lf" placeholder="What did you work on after lunch?"></textarea>
            <input class="lf" type="url" placeholder="🔗 Work link (optional)" style="margin-top:6px;min-height:unset;padding:8px 12px"/>
          </div>
          <button class="log-save" id="log-btn" onclick="window.location.href='dailylogs.php'">Manage All Logs</button>
          <div id="log-saved" style="display:none;align-items:center;gap:6px;font-size:12px;color:var(--accent);margin-top:8px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Log saved successfully
          </div>
        </div>
      </div>

      <!-- ACTIVITY TIMELINE -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Recent Activity
          </div>
          <a class="panel-link" href="#">All →</a>
        </div>
        <?php
        $activities = [
          ['#2D6A4F','You were added to <strong> Website Redesign </strong> project','Today, 10:32 AM'],
          ['#2563EB','<strong> Design homepage wireframe </strong> task assigned to you','Today, 10:15 AM'],
          ['#D97706','Task <strong> Set up staging environment </strong> marked as Done','Yesterday, 5:47 PM'],
          ['#9B9890','Daily log submitted for <strong> Feb 23 </strong>','Yesterday, 5:30 PM'],
          ['#2D6A4F','You joined <strong> Backend API v2 </strong> project','Feb 22, 11:00 AM'],
          ['#C0392B','Reminder: daily log missing for <strong> Feb 21 </strong>','Feb 21, 8:00 PM'],
          ['#9B9890','<strong> Write API documentation </strong> task created','Feb 20, 2:15 PM'],
        ];
        foreach($activities as $a) {
          echo "
          <div class='act-item'>
            <div class='act-col'>
              <div class='act-dot' style='background:{$a[0]}'></div>
              <div class='act-line'></div>
            </div>
            <div>
              <div class='act-text'>{$a[1]}</div>
              <div class='act-time'>{$a[2]}</div>
            </div>
          </div>";
        }
        ?>
      </div>
    </div>

  </div>
</main>

<?php include 'includes/footer.php'; ?>