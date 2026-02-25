<?php 
$pageTitle = 'Employees';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    <div class="page active" id="page-employees">
      <div class="section-header">
        <div>
          <div class="section-title">Employees</div>
          <div class="section-sub">12 active employees</div>
        </div>
        <div class="section-actions">
          <button class="btn btn-primary" onclick="openModal('create-emp-modal')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Employee
          </button>
        </div>
      </div>
      <div class="filter-bar">
        <input class="filter-input" placeholder="Search by name or username…">
        <select class="filter-select"><option>All Profiles</option><option>Public</option><option>Private</option></select>
        <select class="filter-select"><option>All Status</option><option>Active</option><option>Inactive</option></select>
      </div>
      <div class="card">
        <div class="table-wrap">
        <table id="tbl-employees" data-tabulator>
            <thead><tr>
              <th>Employee</th><th>Username</th><th>Email</th><th>Mobile</th><th>Profile</th><th>Joined</th><th>Actions</th>
            </tr></thead>
            <tbody>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="av" style="margin:0;width:32px;height:32px">P</div><span class="td-main">Priya Sharma</span></div></td>
                <td>priya_s</td>
                <td>priya@company.com</td>
                <td>+91 98XXX XXXXX</td>
                <td><span class="privacy-pill privacy-public">🌍 Public</span></td>
                <td>Jan 10, 2026</td>
                <td><button class="btn btn-ghost btn-sm" onclick="openModal('view-emp-modal')">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="av av2" style="margin:0;width:32px;height:32px">R</div><span class="td-main">Ravi Kumar</span></div></td>
                <td>ravi_k</td>
                <td>ravi@company.com</td>
                <td>+91 97XXX XXXXX</td>
                <td><span class="privacy-pill privacy-private">🔒 Private</span></td>
                <td>Jan 15, 2026</td>
                <td><button class="btn btn-ghost btn-sm" onclick="openModal('view-emp-modal')">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="av av3" style="margin:0;width:32px;height:32px">A</div><span class="td-main">Ankit Mehta</span></div></td>
                <td>ankit_m</td>
                <td>ankit@company.com</td>
                <td>+91 96XXX XXXXX</td>
                <td><span class="privacy-pill privacy-public">🌍 Public</span></td>
                <td>Feb 1, 2026</td>
                <td><button class="btn btn-ghost btn-sm" onclick="openModal('view-emp-modal')">View</button></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="av av4" style="margin:0;width:32px;height:32px">S</div><span class="td-main">Sara Joshi</span></div></td>
                <td>sara_j</td>
                <td>sara@company.com</td>
                <td>+91 95XXX XXXXX</td>
                <td><span class="privacy-pill privacy-public">🌍 Public</span></td>
                <td>Feb 5, 2026</td>
                <td><button class="btn btn-ghost btn-sm" onclick="openModal('view-emp-modal')">View</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
