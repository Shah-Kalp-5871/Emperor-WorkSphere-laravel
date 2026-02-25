<!-- SIDEBAR -->
<?php
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-mark">
      <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <span class="brand-name">Work<span>Sphere</span></span>
  </div>
  <div class="sidebar-user" onclick="window.location.href='my-profile.php'" style="cursor: pointer;">
    <div class="avatar">AR</div>
    <div class="user-info">
      <div class="name">kalp Shah</div>
      <div class="role">Intern</div>
    </div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Main</div>
    <a class="nav-item <?php echo ($currentFile == 'dashboard.php' || $currentFile == '') ? 'active' : ''; ?>" href="dashboard.php">
      <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard
    </a>
    <a class="nav-item <?php echo ($currentFile == 'tasks.php') ? 'active' : ''; ?>" href="tasks.php">
      <svg class="icon" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>My Tasks<span class="nav-badge">4</span>
    </a>
    <a class="nav-item <?php echo ($currentFile == 'projects.php') ? 'active' : ''; ?>" href="projects.php">
      <svg class="icon" viewBox="0 0 24 24"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>Projects<span class="nav-badge warn">2</span>
    </a>
    <a class="nav-item <?php echo ($currentFile == 'dailylogs.php') ? 'active' : ''; ?>" href="dailylogs.php">
      <svg class="icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>Daily Log
    </a>
    <div class="nav-label" style="margin-top:10px">Workspace</div>
    <a class="nav-item <?php echo ($currentFile == 'team.php') ? 'active' : ''; ?>" href="team.php">
      <svg class="icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Team Members
    </a>
    <a class="nav-item <?php echo ($currentFile == 'calendar.php') ? 'active' : ''; ?>" href="calendar.php">
      <svg class="icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Office Calendar
    </a>
    <a class="nav-item <?php echo ($currentFile == 'my-profile.php') ? 'active' : ''; ?>" href="my-profile.php">
      <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>My Profile
    </a>
    <div class="nav-label" style="margin-top:10px">Account</div>
    <a class="nav-item" href="#">
      <svg class="icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>Settings
    </a>
    <a class="nav-item danger" href="#">
      <svg class="icon" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Logout
    </a>
  </nav>
  <div class="sidebar-footer">
    <div class="office-status">
      <div class="online-dot"></div>
      <span>Office is <strong>Open</strong> today</span>
    </div>
  </div>
</aside>
