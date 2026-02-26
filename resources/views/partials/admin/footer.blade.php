<!-- MODALS -->

<!-- Create Employee Modal -->
<div class="modal-overlay" id="create-emp-modal">
  <div class="modal">
    <div class="modal-close" onclick="closeModal('create-emp-modal')">✕</div>
    <div class="modal-title">Create Employee Account</div>
    <div class="modal-sub">Employee will complete their profile after first login</div>
    <div class="form-group">
      <label class="form-label">Username</label>
      <input class="form-input" placeholder="e.g. john_doe">
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" placeholder="Set initial password">
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('create-emp-modal')">Cancel</button>
      <button class="btn btn-primary">Create Account</button>
    </div>
  </div>
</div>

<!-- View Employee Profile Modal -->
<div class="modal-overlay" id="view-emp-modal">
  <div class="modal" style="width:520px">
    <div class="modal-close" onclick="closeModal('view-emp-modal')">✕</div>
    <div class="profile-header">
      <div class="profile-avatar">P</div>
      <div>
        <div class="profile-name">Priya Sharma</div>
        <div class="profile-role">Employee · @priya_s · <span style="color:var(--accent2)">🌍 Public Profile</span></div>
      </div>
    </div>
    <div class="detail-grid">
      <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value">priya@company.com</div></div>
      <div class="detail-item"><div class="detail-label">Mobile</div><div class="detail-value">+91 98XXX XXXXX</div></div>
      <div class="detail-item"><div class="detail-label">Joined</div><div class="detail-value">January 10, 2026</div></div>
      <div class="detail-item"><div class="detail-label">Projects</div><div class="detail-value">Website Redesign, Mobile App v2</div></div>
    </div>
    <div style="margin-top:16px;padding:12px 14px;background:rgba(79,142,247,0.06);border:1px solid rgba(79,142,247,0.12);border-radius:8px;font-size:12px;color:var(--text3)">
      ℹ️ Admin can view profile but cannot edit employee details.
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('view-emp-modal')">Close</button>
    </div>
  </div>
</div>





<!-- Mark Day Modal -->
<div class="modal-overlay" id="mark-day-modal">
  <div class="modal">
    <div class="modal-close" onclick="closeModal('mark-day-modal')">✕</div>
    <div class="modal-title">Mark Office Day</div>
    <div class="form-group">
      <label class="form-label">Date</label>
      <input class="form-input" type="date" value="2026-02-24">
    </div>
    <div class="form-group">
      <label class="form-label">Status</label>
      <select class="form-select"><option>Office ON</option><option>Office OFF</option></select>
    </div>
    <div class="form-group">
      <label class="form-label">Reason (optional)</label>
      <input class="form-input" placeholder="e.g. Public holiday, Team event…">
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('mark-day-modal')">Cancel</button>
      <button class="btn btn-primary">Save</button>
    </div>
  </div>
</div>

<!-- Notif Modal -->
<div class="modal-overlay" id="notif-modal">
  <div class="modal" style="width:380px">
    <div class="modal-close" onclick="closeModal('notif-modal')">✕</div>
    <div class="modal-title">Notifications</div>
    <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px">
      <div style="padding:12px;background:rgba(79,142,247,0.06);border:1px solid rgba(79,142,247,0.12);border-radius:8px">
        <div style="font-size:13px">✅ <strong>Priya S.</strong> marked <em>"Deploy staging server"</em> as Done</div>
        <div style="font-size:11px;color:var(--text3);margin-top:3px">2 min ago</div>
      </div>
      <div style="padding:12px;background:rgba(244,63,94,0.06);border:1px solid rgba(244,63,94,0.12);border-radius:8px">
        <div style="font-size:13px">⚠️ <strong>3 tasks</strong> are overdue and need attention</div>
        <div style="font-size:11px;color:var(--text3);margin-top:3px">Today</div>
      </div>
      <div style="padding:12px;background:rgba(110,231,183,0.06);border:1px solid rgba(110,231,183,0.12);border-radius:8px">
        <div style="font-size:13px">📝 <strong>3 employees</strong> have not submitted today's log</div>
        <div style="font-size:11px;color:var(--text3);margin-top:3px">8:00 PM reminder pending</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost btn-sm" onclick="closeModal('notif-modal')">Dismiss all</button>
    </div>
  </div>
</div>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});
function switchPanel() {
  window.location.href = '/employee/dashboard';
}
</script>
<script src="https://unpkg.com/tabulator-tables@6.3.0/dist/js/tabulator.min.js"></script>
@vite('resources/js/admin/tabulator-init.js')
