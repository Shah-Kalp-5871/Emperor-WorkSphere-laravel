@extends('layouts.admin.master')

@section('title', 'WorkSphere — Employee Profile')
@section('page_title', 'Employee Profile')

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
            <button class="greeting-btn" onclick="window.location.href='/admin/employees'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to List
            </button>
        </div>
        </div>
    </div>
    </div>

    <!-- DETAILS -->
    <div class="two-col" style="grid-template-columns: 1.2fr 0.8fr;">
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

        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Employment Details
                </div>
            </div>
            <div style="padding: 22px;">
                <div class="detail-grid" style="grid-template-columns: 1fr;">
                    <div class="detail-item"><div class="detail-label">Department</div><div class="detail-value" id="department-display">-</div></div>
                    <div class="detail-item"><div class="detail-label">Designation</div><div class="detail-value" id="designation-display">-</div></div>
                    <div class="detail-item"><div class="detail-label">Joined Date</div><div class="detail-value" id="joined-display">-</div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-header { display: flex; align-items: center; gap: 24px; }
    .profile-avatar-lg { width: 80px; height: 80px; border-radius: 50%; background: var(--accent-lt); display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; color: var(--accent); flex-shrink: 0; border: 1px solid var(--border); }
    .profile-name { font-family: 'Instrument Serif', serif; font-size: 26px; font-weight: 400; }
    .profile-role-text { font-size: 14px; color: var(--text-3); margin-top: 4px; display: flex; align-items: center; gap: 10px; }
    .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
    .detail-item { padding: 16px 18px; background: var(--surface-2); border-radius: var(--radius-sm); border: 1px solid var(--border); }
    .detail-label { font-size: 11px; color: var(--text-3); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
    .detail-value { font-size: 14.5px; color: var(--text-1); font-weight: 500; }
    .skeleton { background: var(--surface-2); border-radius: 4px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
</style>

@push('scripts')
<script>
    const employeeId = {{ $employeeId }};

    async function fetchEmployee() {
        try {
            const res = await axios.get(`/api/admin/employees/${employeeId}`);
            const employee = res.data;
            renderEmployee(employee);
        } catch (err) { console.error('Fetch employee error:', err); }
    }

    function renderEmployee(data) {
        const head = document.getElementById('profile-header-info');
        const personal = document.getElementById('personal-info-grid');
        
        const avatar = data.user.name.split(' ').map(n => n[0]).join('').toUpperCase();
        
        head.innerHTML = `
            <div class="profile-avatar-lg">${avatar}</div>
            <div>
                <div class="profile-name">${data.user.name}</div>
                <div class="profile-role-text">
                    <span>${data.designation?.name || 'Employee'}</span>
                    <span class="privacy-pill privacy-public">${data.employee_code}</span>
                </div>
            </div>
        `;

        personal.innerHTML = `
            <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value">${data.user.name}</div></div>
            <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value">${data.user.email}</div></div>
            <div class="detail-item"><div class="detail-label">Mobile Number</div><div class="detail-value">${data.phone || 'N/A'}</div></div>
            <div class="detail-item" style="grid-column: span 2"><div class="detail-label">Address</div><div class="detail-value">${data.address || 'N/A'}</div></div>
        `;

        document.getElementById('department-display').textContent = data.department?.name || 'N/A';
        document.getElementById('designation-display').textContent = data.designation?.name || 'N/A';
        document.getElementById('joined-display').textContent = data.date_of_joining ? new Date(data.date_of_joining).toLocaleDateString() : 'N/A';
    }

    document.addEventListener('DOMContentLoaded', fetchEmployee);
</script>
@endpush
@endsection
