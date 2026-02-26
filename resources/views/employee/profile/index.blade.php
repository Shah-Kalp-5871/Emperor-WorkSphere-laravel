@extends('layouts.employee.master')

@section('title', 'WorkSphere — My Profile')
@section('page_title', 'My Profile')

@section('content')
<div class="page-profile" style="animation: fadeUp .4s ease both;">
    
    <!-- PROFILE HEADER -->
    <div class="panel" style="margin-bottom: 24px;">
    <div style="padding: 24px 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div class="profile-header" style="margin-bottom: 0;" id="profile-header-info">
            <div class="profile-avatar-lg skeleton" style="width:80px;height:80px"></div>
            <div>
                <div class="profile-name skeleton" style="width:180px;height:28px;margin-bottom:8px"></div>
                <div class="profile-role-text skeleton" style="width:120px;height:18px"></div>
            </div>
        </div>
        <div style="display:flex; gap:12px">
            <button class="greeting-btn" onclick="showEditProfile()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Profile
            </button>
            <button class="greeting-btn" style="background:var(--surface-2);color:var(--text-2);border:1px solid var(--border)" onclick="showChangePassword()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Security
            </button>
        </div>
        </div>
    </div>
    </div>

    <!-- ABOUT & SKILLS -->
    <div class="two-col" style="grid-template-columns: 1.2fr 0.8fr; margin-bottom: 24px;">
        <!-- ABOUT ME -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h5z"/></svg>
                    About Me
                </div>
            </div>
            <div style="padding: 22px;">
                <p style="font-size: 14px; line-height: 1.6; color: var(--text-2);">
                    Passionate Full Stack Developer Intern with a focus on building scalable web applications using Laravel and modern JavaScript frameworks. I enjoy solving complex problems and learning new technologies to improve user experiences.
                </p>
            </div>
        </div>

        <!-- SKILLS -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    My Skills
                </div>
            </div>
            <div style="padding: 22px;">
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">Laravel</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">Vue.js</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">PHP</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">MySQL</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">Tailwind CSS</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">Git</span>
                    <span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">REST APIs</span>
                </div>
            </div>
        </div>
    </div>

    <div class="two-col" style="grid-template-columns: 1.2fr 0.8fr;">
    <!-- PERSONAL INFORMATION -->
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Personal Information
        </div>
        </div>
        <div style="padding: 22px;">
        <div class="detail-grid" id="personal-info-grid">
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value skeleton" style="width:120px;height:20px"></div></div>
            <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value skeleton" style="width:180px;height:20px"></div></div>
            <div class="detail-item"><div class="detail-label">Mobile Number</div><div class="detail-value skeleton" style="width:100px;height:20px"></div></div>
        </div>
        </div>
    </div>

    <!-- ACCOUNT INFORMATION -->
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Account Information
        </div>
        </div>
        <div style="padding: 22px;">
        <div class="detail-grid" style="grid-template-columns: 1fr;">
            <div class="detail-item">
            <div class="detail-label">Department</div>
            <div class="detail-value" id="department-display">-</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="detail-item">
                <div class="detail-label">Joined On</div>
                <div class="detail-value" id="joined-display">-</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Designation</div>
                <div class="detail-value" id="designation-display">-</div>
            </div>
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
                <div class="form-group" style="margin-top:10px"><label>Address</label><textarea id="edit-address" class="form-control" rows="3"></textarea></div>
                <div style="display:flex; gap:10px; margin-top:20px">
                    <button type="submit" class="greeting-btn" id="save-profile-btn" style="flex:1; justify-content:center">Save Changes</button>
                    <button type="button" class="greeting-btn" style="background:var(--surface-2);color:var(--text-2);flex:1;justify-content:center" onclick="closeModals()">Cancel</button>
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
                    <button type="submit" class="greeting-btn" id="save-password-btn" style="flex:1; justify-content:center">Update Password</button>
                    <button type="button" class="greeting-btn" style="background:var(--surface-2);color:var(--text-2);flex:1;justify-content:center" onclick="closeModals()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <div class="two-col" style="grid-template-columns: 0.8fr 1.2fr; margin-top: 20px;">
    <!-- PRIVACY SETTINGS -->
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Privacy & Visibility
        </div>
        </div>
        <div style="padding: 22px;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding: 12px; background: var(--surface-2); border-radius: var(--radius-sm); border: 1px solid var(--border);">
            <div style="flex: 1;">
            <div style="font-size: 14px; font-weight: 500; color: var(--text-1);">Public Profile Visibility</div>
            <p style="font-size: 12px; color: var(--text-3); margin-top: 4px; line-height: 1.4;">When enabled, your profile and activity are visible to team members.</p>
            </div>
            <label class="switch">
            <input type="checkbox" checked>
            <span class="slider"></span>
            </label>
        </div>
        <div style="margin-top: 16px; font-size: 12px; color: var(--text-3); text-align: center;">
            Update your security settings frequently for better protection.
        </div>
        </div>
    </div>

    <!-- SOCIAL ACCOUNTS -->
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
            Social Accounts
        </div>
        </div>
        <div style="padding: 22px;">
        <div class="detail-grid" style="grid-template-columns: 1fr;">
            <div class="detail-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px;">
            <div style="display: flex; align-items: center;">
                <svg width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/><rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/>
                <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/>
                <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/>
                <defs>
                <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)">
                <stop stop-color="#B13589"/>
                <stop offset="0.79309" stop-color="#C62F94"/>
                <stop offset="1" stop-color="#8A3AC8"/>
                </radialGradient>
                <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)">
                <stop stop-color="#E0E8B7"/>
                <stop offset="0.444662" stop-color="#FB8A2E"/>
                <stop offset="0.71474" stop-color="#E2425C"/>
                <stop offset="1" stop-color="#E2425C" stop-opacity="0"/></radialGradient>
                <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)">
                <stop offset="0.156701" stop-color="#406ADC"/>
                <stop offset="0.467799" stop-color="#6A45BE"/>
                <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/></radialGradient></defs></svg>
            </div>
            <div style="flex: 1;">
                <div class="detail-label" style="margin-bottom: 2px;">Instagram</div>
                <div class="detail-value" style="font-size: 13.5px;">@kalp_shah</div>
            </div>
            </div>
            <div class="detail-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px;">
            <div style="display: flex; align-items: center;">
                <svg width="20" height="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="none"><path fill="#0A66C2" d="M12.225 12.225h-1.778V9.44c0-.664-.012-1.519-.925-1.519-.926 0-1.068.724-1.068 1.47v2.834H6.676V6.498h1.707v.783h.024c.348-.594.996-.95 1.684-.925 1.802 0 2.135 1.185 2.135 2.728l-.001 3.14zM4.67 5.715a1.037 1.037 0 01-1.032-1.031c0-.566.466-1.032 1.032-1.032.566 0 1.031.466 1.032 1.032 0 .566-.466 1.032-1.032 1.032zm.889 6.51h-1.78V6.498h1.78v5.727zM13.11 2H2.885A.88.88 0 002 2.866v10.268a.88.88 0 00.885.866h10.226a.882.882 0 00.889-.866V2.865a.88.88 0 00-.889-.864z"/></svg>
            </div>
            <div style="flex: 1;">
                <div class="detail-label" style="margin-bottom: 2px;">LinkedIn</div>
                <div class="detail-value" style="font-size: 13.5px;">kalp-shah-5871</div>
            </div>
            </div>
            <div class="detail-item" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px;">
            <div style="display: flex; align-items: center;">
                <svg width="20" height="20" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <g transform="translate(-84.000000, -7399.000000)" fill="#000000">
                        <path d="M94,7399 C99.523,7399 104,7403.59 104,7409.253 C104,7413.782 101.138,7417.624 97.167,7418.981 C96.66,7419.082 96.48,7418.762 96.48,7418.489 C96.48,7418.151 96.492,7417.047 96.492,7415.675 C96.492,7414.719 96.172,7414.095 95.813,7413.777 C98.04,7413.523 100.38,7412.656 100.38,7408.718 C100.38,7407.598 99.992,7406.684 99.35,7405.966 C99.454,7405.707 99.797,7404.664 99.252,7403.252 C99.252,7403.252 98.414,7402.977 96.505,7404.303 C95.706,7404.076 94.85,7403.962 94,7403.958 C93.15,7403.962 92.295,7404.076 91.497,7404.303 C89.586,7402.977 88.746,7403.252 88.746,7403.252 C88.203,7404.664 88.546,7405.707 88.649,7405.966 C88.01,7406.684 87.619,7407.598 87.619,7408.718 C87.619,7412.646 89.954,7413.526 92.175,7413.785 C91.889,7414.041 91.63,7414.493 91.54,7415.156 C90.97,7415.418 89.522,7415.871 88.63,7414.304 C88.63,7414.304 88.101,7413.319 87.097,7413.247 C87.097,7413.247 86.122,7413.234 87.029,7413.87 C87.029,7413.87 87.684,7414.185 88.139,7415.37 C88.139,7415.37 88.726,7417.2 91.508,7416.58 C91.513,7417.437 91.522,7418.245 91.522,7418.489 C91.522,7418.76 91.338,7419.077 90.839,7418.982 C86.865,7417.627 84,7413.783 84,7409.253 C84,7403.59 88.478,7399 94,7399"></path>
                    </g>
                </svg>
            </div>
            <div style="flex: 1;">
                <div class="detail-label" style="margin-bottom: 2px;">GitHub</div>
                <div class="detail-value" style="font-size: 13.5px;">kalpshah-dev</div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>

</div>

<style>
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1100; animation: fadeIn 0.2s; }
    .modal-content { background: var(--surface); width: 100%; max-width: 450px; border-radius: 12px; box-shadow: var(--shadow-lg); overflow: hidden; animation: slideUp 0.3s; }
    .modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 20px; }
    .close-btn { background: none; border: none; font-size: 24px; color: var(--text-3); cursor: pointer; }
    .skeleton { background: var(--surface-2); border-radius: 4px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; color: var(--text-2); margin-bottom: 6px; }
    .form-control { width: 100%; padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface-2); color: var(--text-1); font-size: 14px; transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: var(--accent); }
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
        const avatar = currentUser.name.split(' ').map(n => n[0]).join('').toUpperCase();
        document.getElementById('profile-header-info').innerHTML = `
            <div class="profile-avatar-lg">${avatar}</div>
            <div>
                <div class="profile-name">${currentUser.name}</div>
                <div class="profile-role-text">
                    <span>${currentUser.employee?.designation?.name || 'Employee'}</span>
                    <span class="privacy-pill privacy-public">Active</span>
                </div>
            </div>
        `;

        // Personal Info
        document.getElementById('personal-info-grid').innerHTML = `
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value">${currentUser.name}</div></div>
            <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value">${currentUser.email}</div></div>
            <div class="detail-item"><div class="detail-label">Mobile Number</div><div class="detail-value">${currentUser.employee?.phone || 'N/A'}</div></div>
            <div class="detail-item"><div class="detail-label">Employee Code</div><div class="detail-value">${currentUser.employee?.employee_code || 'N/A'}</div></div>
            <div class="detail-item" style="grid-column: span 2"><div class="detail-label">Address</div><div class="detail-value">${currentUser.employee?.address || 'N/A'}</div></div>
        `;

        document.getElementById('department-display').textContent = currentUser.employee?.department?.name || 'N/A';
        document.getElementById('designation-display').textContent = currentUser.employee?.designation?.name || 'N/A';
        document.getElementById('joined-display').textContent = currentUser.employee?.date_of_joining ? new Date(currentUser.employee.date_of_joining).toLocaleDateString() : 'N/A';
    }

    function showEditProfile() {
        if (!currentUser) return;
        document.getElementById('edit-name').value = currentUser.name;
        document.getElementById('edit-email').value = currentUser.email;
        document.getElementById('edit-phone').value = currentUser.employee?.phone || '';
        document.getElementById('edit-address').value = currentUser.employee?.address || '';
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
                address: document.getElementById('edit-address').value,
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
@endsection
