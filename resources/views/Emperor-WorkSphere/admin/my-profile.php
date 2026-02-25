<?php 
$pageTitle = 'My Profile';
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
?>

<!-- MAIN -->
<main class="main">
  <?php include 'includes/topbar.php'; ?>

  <div class="content">
    <div class="page active" id="page-profile">
      
      <!-- PROFILE HEADER -->
      <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
          <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 20px;">
              <div class="profile-avatar">A</div>
              <div>
                <div class="profile-name">Admin User</div>
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                  <span class="profile-role">Super Admin</span>
                  <span class="privacy-pill privacy-public">Public</span>
                </div>
              </div>
            </div>
            <button class="btn btn-primary">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit Profile
            </button>
          </div>
        </div>
      </div>

      <div class="grid-2">
        <!-- PERSONAL INFORMATION -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">Personal Information</div>
          </div>
          <div class="card-body">
            <div class="detail-grid">
              <div class="detail-item">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">Admin User</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Email</div>
                <div class="detail-value">admin@emperor.com</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Mobile</div>
                <div class="detail-value">+91 98765 43210</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Department</div>
                <div class="detail-value">Administration</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Designation</div>
                <div class="detail-value">Super Admin</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Location</div>
                <div class="detail-value">Surat, Gujarat</div>
              </div>
            </div>
          </div>
        </div>

        <!-- ACCOUNT INFORMATION -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">Account Information</div>
          </div>
          <div class="card-body">
            <div class="detail-grid">
              <div class="detail-item">
                <div class="detail-label">Username</div>
                <div class="detail-value">admin_master</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Joined Date</div>
                <div class="detail-value">Jan 12, 2024</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Last Login</div>
                <div class="detail-value">Feb 24, 2026 · 10:45 AM</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Total Projects</div>
                <div class="detail-value">24</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Total Tasks</div>
                <div class="detail-value">142</div>
              </div>
              <div class="detail-item">
                <div class="detail-label">Tasks Completed</div>
                <div class="detail-value">128</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid-2">
        <!-- PRIVACY SETTINGS -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">Privacy Settings</div>
          </div>
          <div class="card-body">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
              <div>
                <div style="font-size: 14px; font-weight: 500;">Profile Visibility</div>
                <div style="font-size: 12px; color: var(--text3); margin-top: 4px;">Allow other employees to see your profile details and activity.</div>
              </div>
              <label class="switch">
                <input type="checkbox" checked>
                <span class="slider round"></span>
              </label>
            </div>
            <style>
              .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
              .switch input { opacity: 0; width: 0; height: 0; }
              .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--surface2); border: 1px solid var(--border); transition: .4s; }
              .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: var(--text3); transition: .4s; }
              input:checked + .slider { background-color: var(--accent); border-color: var(--accent); }
              input:checked + .slider:before { transform: translateX(20px); background-color: #fff; }
              .slider.round { border-radius: 24px; }
              .slider.round:before { border-radius: 50%; }
            </style>
          </div>
        </div>

        <!-- ACTIVITY SUMMARY -->
        <div class="card">
          <div class="card-header">
            <div class="card-title">Activity Summary</div>
          </div>
          <div class="card-body">
            <div class="activity-item">
              <div class="activity-dot-col"><div class="act-dot"></div><div class="act-line"></div></div>
              <div><div class="act-text">Recent Project: <strong>Website Redesign</strong></div><div class="act-time">2 days ago</div></div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-col"><div class="act-dot" style="background:var(--accent2)"></div><div class="act-line"></div></div>
              <div><div class="act-text">Recent Task: <strong>Update user documentation</strong></div><div class="act-time">1 day ago</div></div>
            </div>
            <div class="activity-item">
              <div class="activity-dot-col"><div class="act-dot" style="background:var(--accent3)"></div><div class="act-line" style="display:none"></div></div>
              <div><div class="act-text">Recent Log: <strong>Daily log for Feb 23</strong> submitted</div><div class="act-time">Yesterday</div></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
