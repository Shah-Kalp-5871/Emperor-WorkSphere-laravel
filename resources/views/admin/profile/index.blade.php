@extends('layouts.admin.master')

@section('title', 'WorkSphere — My Profile')

@section('content')
<div class="page active" id="page-profile">
    
    <!-- PROFILE HEADER -->
    <div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px;" id="profile-header-info">
            <div class="profile-avatar skeleton" style="width:60px; height:60px; border-radius:50%"></div>
            <div>
                <div class="profile-name skeleton" style="width:150px; height:24px; margin-bottom:8px"></div>
                <div class="profile-role skeleton" style="width:100px; height:18px"></div>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-primary" onclick="showEditProfile()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Profile
            </button>
            <button class="btn btn-ghost" onclick="showChangePassword()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Change Password
            </button>
        </div>
        </div>
    </div>
    </div>

    <div class="grid-2">
    <!-- PERSONAL INFORMATION -->
    <div class="card">
        <div class="card-header"><div class="card-title">Personal Information</div></div>
        <div class="card-body">
        <div class="detail-grid" id="personal-info-grid">
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value skeleton" style="width:120px;height:18px"></div></div>
            <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value skeleton" style="width:180px;height:18px"></div></div>
            <div class="detail-item"><div class="detail-label">Phone</div><div class="detail-value skeleton" style="width:100px;height:18px"></div></div>
        </div>
        </div>
    </div>

    <!-- ACCOUNT SUMMARY -->
    <div class="card">
        <div class="card-header"><div class="card-title">Account Summary</div></div>
        <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><div class="detail-label">Role</div><div class="detail-value" id="user-role-display">-</div></div>
            <div class="detail-item"><div class="detail-label">Status</div><div class="detail-value"><span class="privacy-pill privacy-public">Active</span></div></div>
        </div>
        </div>
    </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal-overlay" style="display:none">
    <div class="modal-content">
        <div class="modal-header"><h3>Edit Personal Information</h3><button class="close-btn" onclick="closeModals()">&times;</button></div>
        <div class="modal-body">
            <form id="editProfileForm">
                <div class="form-group"><label>Full Name</label><input type="text" id="edit-name" class="form-control" required></div>
                <div class="form-group"><label>Email Address</label><input type="email" id="edit-email" class="form-control" required></div>
                <div class="form-group"><label>Phone Number</label><input type="text" id="edit-phone" class="form-control"></div>
                <div style="display:flex; gap:10px; margin-top:20px">
                    <button type="submit" class="btn btn-primary" id="save-profile-btn" style="flex:1">Save Changes</button>
                    <button type="button" class="btn btn-ghost" onclick="closeModals()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal-overlay" style="display:none">
    <div class="modal-content">
        <div class="modal-header"><h3>Security Settings</h3><button class="close-btn" onclick="closeModals()">&times;</button></div>
        <div class="modal-body">
            <form id="changePasswordForm">
                <div class="form-group"><label>Current Password</label><input type="password" id="current_password" class="form-control" required></div>
                <div class="form-group"><label>New Password</label><input type="password" id="password" class="form-control" required></div>
                <div class="form-group"><label>Confirm New Password</label><input type="password" id="password_confirmation" class="form-control" required></div>
                <div style="display:flex; gap:10px; margin-top:20px">
                    <button type="submit" class="btn btn-primary" id="save-password-btn" style="flex:1">Update Password</button>
                    <button type="button" class="btn btn-ghost" onclick="closeModals()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<style>
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1100; animation: fadeIn 0.2s; }
    .modal-content { background: var(--surface); width: 100%; max-width: 450px; border-radius: 12px; box-shadow: var(--shadow-lg); overflow: hidden; animation: slideUp 0.3s; }
    .modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 20px; }
    .close-btn { background: none; border: none; font-size: 24px; color: var(--text-3); cursor: pointer; }
    .detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .detail-label { font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; margin-bottom: 4px; }
    .detail-value { font-size: 14px; font-weight: 500; color: var(--text-1); }
    .skeleton { background: var(--surface-2); border-radius: 4px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
</style>

<script>
    let currentUser = null;

    async function fetchMe() {
        try {
            const res = await axios.get('/api/me');
            currentUser = res.data;
            renderProfile();
        } catch (err) { console.error('Fetch Me error:', err); }
    }

    function renderProfile() {
        if (!currentUser) return;

        // Header
        document.getElementById('profile-header-info').innerHTML = `
            <div class="profile-avatar">${currentUser.name[0]}</div>
            <div>
                <div class="profile-name">${currentUser.name}</div>
                <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                    <span class="profile-role">${currentUser.role || 'Admin'}</span>
                    <span class="privacy-pill privacy-public">Active</span>
                </div>
            </div>
        `;

        // Personal Info
        document.getElementById('personal-info-grid').innerHTML = `
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value">${currentUser.name}</div></div>
            <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value">${currentUser.email}</div></div>
            <div class="detail-item"><div class="detail-label">Phone</div><div class="detail-value">${currentUser.employee?.phone || 'N/A'}</div></div>
            ${currentUser.employee ? `<div class="detail-item"><div class="detail-label">Employee Code</div><div class="detail-value">${currentUser.employee.employee_code}</div></div>` : ''}
        `;

        document.getElementById('user-role-display').textContent = currentUser.role || 'Super Admin';
    }

    function showEditProfile() {
        if (!currentUser) return;
        document.getElementById('edit-name').value = currentUser.name;
        document.getElementById('edit-email').value = currentUser.email;
        document.getElementById('edit-phone').value = currentUser.employee?.phone || '';
        document.getElementById('editProfileModal').style.display = 'flex';
    }

    function showChangePassword() {
        document.getElementById('changePasswordForm').reset();
        document.getElementById('changePasswordModal').style.display = 'flex';
    }

    function closeModals() {
        document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none');
    }

    document.getElementById('editProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-profile-btn');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        try {
            await axios.put('/api/profile', {
                name: document.getElementById('edit-name').value,
                email: document.getElementById('edit-email').value,
                phone: document.getElementById('edit-phone').value,
            });
            await fetchMe();
            closeModals();
        } catch (err) {
            alert('Update failed: ' + (err.response?.data?.message || 'Error occurred'));
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Changes';
        }
    });

    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('save-password-btn');
        btn.disabled = true;
        btn.textContent = 'Updating...';

        try {
            await axios.put('/api/password', {
                current_password: document.getElementById('current_password').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            });
            alert('Password updated successfully.');
            closeModals();
        } catch (err) {
            alert('Password update failed: ' + (err.response?.data?.message || 'Check your fields'));
        } finally {
            btn.disabled = false;
            btn.textContent = 'Update Password';
        }
    });

    document.addEventListener('DOMContentLoaded', fetchMe);
</script>
@endpush