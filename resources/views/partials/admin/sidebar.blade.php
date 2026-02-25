<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">W</div>
    <span class="logo-text">WorkOS</span>
    <span class="logo-badge">Admin</span>
  </div>
  <nav class="sidebar-nav">
    <span class="nav-section-label">Overview</span>
    <a class="nav-item {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ url('/admin/dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a class="nav-item {{ request()->is('admin/employees*') ? 'active' : '' }}" href="{{ url('/admin/employees') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path d="M16 3.13a4 4 0 010 7.75"/><path d="M21 21v-2a4 4 0 00-3-3.85"/></svg>
      Employees
      <span class="nav-badge">12</span>
    </a>
    <a class="nav-item {{ request()->is('admin/projects*') ? 'active' : '' }}" href="{{ url('/admin/projects') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
      Projects
      <span class="nav-badge warn">5</span>
    </a>
    <a class="nav-item {{ request()->is('admin/tasks*') ? 'active' : '' }}" href="{{ url('/admin/tasks') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      Tasks
      <span class="nav-badge danger">3</span>
    </a>
    <a class="nav-item {{ request()->is('admin/dailylogs*') ? 'active' : '' }}" href="{{ url('/admin/dailylogs') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Daily Logs
    </a>
    <span class="nav-section-label">Management</span>
    <a class="nav-item {{ request()->is('admin/calendar*') ? 'active' : '' }}" href="{{ url('/admin/calendar') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Office Calendar
    </a>
    <a class="nav-item {{ request()->is('admin/timeline*') ? 'active' : '' }}" href="{{ url('/admin/timeline') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Activity Timeline
    </a>
    <a class="nav-item {{ request()->is('admin/archived*') ? 'active' : '' }}" href="{{ url('/admin/archived') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x1="1" y1="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
      Archived
    </a>
  </nav>
  <div class="sidebar-user" onclick="window.location.href='{{ url('/admin/profile/my-profile') }}'" style="cursor: pointer;">
    <div class="user-avatar">A</div>
    <div class="user-info">
      <div class="user-name">Admin</div>
      <div class="user-role">Super Admin</div>
    </div>
  </div>
</aside>
