<!-- TOPBAR -->
<div class="topbar">
  <div>
    <div class="topbar-title" id="topbar-title">@yield('page_title', 'Dashboard')</div>
  </div>
  <div class="topbar-spacer"></div>
  <div class="topbar-search">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    Search anything…
  </div>
  <div class="topbar-btn" onclick="openModal('notif-modal')">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
    <span class="notif-dot"></span>
  </div>
  <div class="topbar-btn" onclick="window.location.href='{{ url('/admin/profile/my-profile') }}'">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
  </div>
  <div class="topbar-btn" onclick="switchPanel()">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.85"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
  </div>
</div>
