<?php 
$pageTitle = 'Projects';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    <div class="page active" id="page-projects">
      <div class="section-header">
        <div>
          <div class="section-title">Projects</div>
          <div class="section-sub">5 active · 2 archived</div>
        </div>
        <div class="section-actions">
          <button class="btn btn-primary" onclick="openModal('create-proj-modal')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Project
          </button>
        </div>
      </div>
      <div class="filter-bar">
        <input class="filter-input" placeholder="Search projects…">
        <select class="filter-select"><option>All Projects</option><option>My Projects</option></select>
      </div>
      <div class="card">
        <div class="table-wrap">
        <table id="tbl-projects" data-tabulator>
            <thead><tr>
              <th>Project</th><th>Creator</th><th>Members</th><th>Tasks</th><th>Progress</th><th>Created</th><th>Actions</th>
            </tr></thead>
            <tbody>
              <tr>
                <td class="td-main">Website Redesign</td>
                <td>Priya S.</td>
                <td>
                  <div class="avatar-group">
                    <div class="av">P</div><div class="av av2">R</div><div class="av av3">A</div><div class="av av4">+1</div>
                  </div>
                </td>
                <td>6 tasks · 4 done</td>
                <td><div style="display:flex;align-items:center;gap:8px"><div class="progress-bar-wrap" style="width:70px"><div class="progress-bar" style="width:72%"></div></div><span style="font-size:12px;color:var(--text3)">72%</span></div></td>
                <td>Jan 20</td>
                <td style="display:flex;gap:6px;padding-top:13px">
                  <button class="btn btn-ghost btn-sm">View</button>
                  <button class="btn btn-danger btn-sm">Archive</button>
                </td>
              </tr>
              <tr>
                <td class="td-main">Mobile App v2</td>
                <td>Admin</td>
                <td>
                  <div class="avatar-group">
                    <div class="av av2">R</div><div class="av av3">A</div><div class="av">S</div>
                  </div>
                </td>
                <td>11 tasks · 5 done</td>
                <td><div style="display:flex;align-items:center;gap:8px"><div class="progress-bar-wrap" style="width:70px"><div class="progress-bar" style="width:45%;background:var(--accent2)"></div></div><span style="font-size:12px;color:var(--text3)">45%</span></div></td>
                <td>Feb 1</td>
                <td style="display:flex;gap:6px;padding-top:13px">
                  <button class="btn btn-ghost btn-sm">View</button>
                  <button class="btn btn-danger btn-sm">Archive</button>
                </td>
              </tr>
              <tr>
                <td class="td-main">API Integration</td>
                <td>Ankit M.</td>
                <td>
                  <div class="avatar-group">
                    <div class="av av3">A</div><div class="av av4">S</div>
                  </div>
                </td>
                <td>4 tasks · 3 done</td>
                <td><div style="display:flex;align-items:center;gap:8px"><div class="progress-bar-wrap" style="width:70px"><div class="progress-bar" style="width:90%;background:var(--accent3)"></div></div><span style="font-size:12px;color:var(--text3)">90%</span></div></td>
                <td>Feb 10</td>
                <td style="display:flex;gap:6px;padding-top:13px">
                  <button class="btn btn-ghost btn-sm">View</button>
                  <button class="btn btn-danger btn-sm">Archive</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
