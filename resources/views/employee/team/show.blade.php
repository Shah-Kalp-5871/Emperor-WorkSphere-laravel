@extends('layouts.employee.master')

@section('title', 'WorkSphere — Team Member Profile')
@section('page_title', 'Team Member Profile')

@section('content')
<div class="page-profile" style="animation: fadeUp .4s ease both;">
    
    <div style="margin-bottom: 24px;">
        <a href="{{ url('/employee/team') }}" class="greeting-btn" style="background:var(--surface-2);color:var(--text-2);border:1px solid var(--border)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Team
        </a>
    </div>

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
                    <button class="greeting-btn" onclick="alert('Message feature coming soon!')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                        Message
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
                    About
                </div>
            </div>
            <div style="padding: 22px;">
                <p style="font-size: 14px; line-height: 1.6; color: var(--text-2);" id="about-me-text">
                    Loading...
                </p>
            </div>
        </div>

        <!-- SKILLS -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    Skills
                </div>
            </div>
            <div style="padding: 22px;">
                <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="skills-container">
                    <span style="color:var(--text-3);font-size:14px;">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PROFESSIONAL INFORMATION -->
    <div class="panel" style="grid-column: span 2;">
        <div class="panel-header">
            <div class="panel-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Professional Information
            </div>
        </div>
        <div style="padding: 22px;">
            <div class="detail-grid" id="personal-info-grid">
                <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value skeleton" style="width:120px;height:20px"></div></div>
                <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value skeleton" style="width:180px;height:20px"></div></div>
                <div class="detail-item"><div class="detail-label">Department</div><div class="detail-value skeleton" style="width:150px;height:20px"></div></div>
            </div>
        </div>
    </div>
</div>

<style>
    .skeleton { background: var(--surface-2); border-radius: 4px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
</style>

@push('scripts')
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const memberId = urlParams.get('id');

    async function fetchTeamMember() {
        if (!memberId) {
            document.getElementById('profile-header-info').innerHTML = '<p style="color:var(--red);">No Member ID specified.</p>';
            return;
        }

        try {
            const res = await axios.get(`/api/employee/team/${memberId}`);
            const member = res.data.data;
            const user = member.user || { name: 'Unknown', email: 'N/A' };
            
            // Header
            const avatar = user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            const role = member.designation?.name || 'Employee';
            const status = member.is_active ? 'Online' : 'Offline';
            const statusClass = member.is_active ? 'privacy-public' : 'privacy-private';
            
            document.getElementById('profile-header-info').innerHTML = `
                <div class="profile-avatar-lg">${avatar}</div>
                <div>
                    <div class="profile-name">${user.name}</div>
                    <div class="profile-role-text" style="display:flex; align-items:center; gap:8px;">
                        <span>${role}</span>
                        <span class="privacy-pill ${statusClass}" style="border:1px solid ${member.is_active ? 'var(--accent-lt)' : 'var(--border)'}">${status}</span>
                    </div>
                </div>
            `;

            // Professional Info
            document.getElementById('personal-info-grid').innerHTML = `
                <div class="detail-item"><div class="detail-label">Full Name</div><div class="detail-value">${user.name}</div></div>
                <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value"><a href="mailto:${user.email}" style="color:var(--accent);text-decoration:none;">${user.email}</a></div></div>
                <div class="detail-item"><div class="detail-label">Department</div><div class="detail-value">${member.department?.name || 'N/A'}</div></div>
            `;

            // About Me & Skills
            document.getElementById('about-me-text').textContent = member.about_me || 'No description provided yet.';
            
            const skillsContainer = document.getElementById('skills-container');
            const skillsArr = (member.skills || '').split(',').map(s => s.trim()).filter(s => s);
            if (skillsArr.length > 0) {
                skillsContainer.innerHTML = skillsArr.map(skill => 
                    `<span class="privacy-pill privacy-public" style="border: 1px solid var(--accent-lt);">${skill}</span>`
                ).join('');
            } else {
                skillsContainer.innerHTML = '<span style="color:var(--text-3);font-size:14px;">No skills listed.</span>';
            }

        } catch (err) { 
            console.error('Fetch Member error:', err);
            document.getElementById('profile-header-info').innerHTML = '<p style="color:var(--red);">Failed to load member profile.</p>';
        }
    }

    document.addEventListener('DOMContentLoaded', fetchTeamMember);
</script>
@endpush
@endsection
