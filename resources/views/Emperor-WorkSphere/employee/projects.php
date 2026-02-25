<?php
$pageTitle = 'My Projects';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>My <em>Projects</em> 📁</h2>
        <p>Overview of all projects you are currently assigned to.</p>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Active Projects <span class="count">3</span></div>
      </div>
      
      <style>
        .table-container { padding: 0; overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .data-table th { 
          text-align: left; 
          padding: 12px 22px; 
          font-size: 11px; 
          text-transform: uppercase; 
          letter-spacing: 0.05em; 
          color: var(--text-3); 
          border-bottom: 1px solid var(--border);
          font-weight: 600;
        }
        .data-table td { 
          padding: 16px 22px; 
          font-size: 13.5px; 
          border-bottom: 1px solid var(--border);
          color: var(--text-2);
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover { background: var(--surface-2); }
        .member-avatars { display: flex; align-items: center; }
        .member-avatar { 
          width: 24px; 
          height: 24px; 
          border-radius: 50%; 
          border: 2px solid var(--surface); 
          background: var(--accent-lt); 
          color: var(--accent); 
          font-size: 10px; 
          font-weight: 600; 
          display: flex; 
          align-items: center; 
          justify-content: center; 
          margin-left: -8px;
        }
        .member-avatar:first-child { margin-left: 0; }
        .progress-wrap { width: 100px; }
        .progress-text { font-size: 11px; color: var(--text-3); margin-bottom: 4px; display: block; text-align: right; }
        .action-btn { 
          padding: 6px 12px; 
          border-radius: 6px; 
          border: 1px solid var(--border); 
          background: #fff; 
          font-size: 12px; 
          color: var(--text-2); 
          cursor: pointer; 
          transition: all 0.2s;
        }
        .action-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }
      </style>

      <div class="table-container">
        <table class="data-table" id="tbl-emp-projects" data-tabulator>
          <thead>
            <tr>
              <th>Project</th>
              <th>Creator</th>
              <th>Members</th>
              <th>Tasks</th>
              <th>Progress</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><div style="font-weight: 600; color: var(--text-1);">Website Redesign</div><div style="font-size: 11px; color: var(--text-3);">Internal Development</div></td>
              <td>Priya S.</td>
              <td>
                <div class="member-avatars">
                  <div class="member-avatar">P</div>
                  <div class="member-avatar" style="background:var(--blue-lt); color:var(--blue);">R</div>
                  <div class="member-avatar" style="background:var(--amber-lt); color:var(--amber);">A</div>
                  <div class="member-avatar">+1</div>
                </div>
              </td>
              <td>6 tasks · 4 done</td>
              <td>
                <div class="progress-wrap">
                  <span class="progress-text">72%</span>
                  <div class="proj-bar-bg"><div class="proj-bar-fill" style="width: 72%; background: var(--accent);"></div></div>
                </div>
              </td>
              <td>Jan 20, 2026</td>
              <td><button class="action-btn" onclick="window.location.href='project_details.php'">View Details</button></td>
            </tr>
            <tr>
              <td><div style="font-weight: 600; color: var(--text-1);">Mobile App v2</div><div style="font-size: 11px; color: var(--text-3);">Client Project</div></td>
              <td>Admin</td>
              <td>
                <div class="member-avatars">
                  <div class="member-avatar" style="background:var(--blue-lt); color:var(--blue);">R</div>
                  <div class="member-avatar" style="background:var(--amber-lt); color:var(--amber);">A</div>
                  <div class="member-avatar">K</div>
                </div>
              </td>
              <td>11 tasks · 5 done</td>
              <td>
                <div class="progress-wrap">
                  <span class="progress-text">45%</span>
                  <div class="proj-bar-bg"><div class="proj-bar-fill" style="width: 45%; background: var(--blue);"></div></div>
                </div>
              </td>
              <td>Feb 01, 2026</td>
              <td><button class="action-btn" onclick="window.location.href='project_details.php'">View Details</button></td>
            </tr>
            <tr>
              <td><div style="font-weight: 600; color: var(--text-1);">API Integration</div><div style="font-size: 11px; color: var(--text-3);">Infrastructure Enhancement</div></td>
              <td>Ankit M.</td>
              <td>
                <div class="member-avatars">
                  <div class="member-avatar" style="background:var(--amber-lt); color:var(--amber);">A</div>
                  <div class="member-avatar">K</div>
                </div>
              </td>
              <td>4 tasks · 3 done</td>
              <td>
                <div class="progress-wrap">
                  <span class="progress-text">90%</span>
                  <div class="proj-bar-bg"><div class="proj-bar-fill" style="width: 90%; background: var(--amber);"></div></div>
                </div>
              </td>
              <td>Feb 10, 2026</td>
              <td><button class="action-btn" onclick="window.location.href='project_details.php'">View Details</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
