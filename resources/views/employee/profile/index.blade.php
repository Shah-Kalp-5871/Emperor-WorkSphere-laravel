@extends('layouts.employee.master')

@section('title', 'WorkSphere — My Profile')
@section('page_title', 'My Profile')

@section('content')
<div class="page-profile" style="animation: fadeUp .4s ease both;">
    
    <!-- PROFILE HEADER -->
    <div class="panel" style="margin-bottom: 24px;">
    <div style="padding: 24px 28px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
        <div class="profile-header" style="margin-bottom: 0;">
            <div class="profile-avatar-lg">KS</div>
            <div>
            <div class="profile-name">Kalp Shah</div>
            <div class="profile-role-text">
                <span>Full Stack Developer Intern</span>
                <span class="privacy-pill privacy-public">Public Profile</span>
            </div>
            </div>
        </div>
        <button class="greeting-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit My Profile
        </button>
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
        <div class="detail-grid">
            <div class="detail-item">
            <div class="detail-label">Full Name</div>
            <div class="detail-value">Kalp Shah</div>
            </div>
            <div class="detail-item">
            <div class="detail-label">Email Address</div>
            <div class="detail-value">kalp.shah@emperor.com</div>
            </div>
            <div class="detail-item">
            <div class="detail-label">Mobile Number</div>
            <div class="detail-value">+91 99887 76655</div>
            </div>
            <div class="detail-item">
            <div class="detail-label">Department</div>
            <div class="detail-value">Development Team</div>
            </div>
            <div class="detail-item">
            <div class="detail-label">Current Designation</div>
            <div class="detail-value">Intern</div>
            </div>
            <div class="detail-item">
            <div class="detail-label">Primary Location</div>
            <div class="detail-value">Surat Office</div>
            </div>
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
            <div class="detail-label">Username</div>
            <div class="detail-value">@kalp_shah</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="detail-item">
                <div class="detail-label">Joined On</div>
                <div class="detail-value">Feb 01, 2026</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Last Login</div>
                <div class="detail-value">Today, 9:15 AM</div>
            </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
            <div class="detail-item" style="text-align: center;">
                <div class="detail-label">Projects</div>
                <div class="detail-value">3</div>
            </div>
            <div class="detail-item" style="text-align: center;">
                <div class="detail-label">Tasks</div>
                <div class="detail-value">24</div>
            </div>
            <div class="detail-item" style="text-align: center;">
                <div class="detail-label">Done</div>
                <div class="detail-value">20</div>
            </div>
            </div>
        </div>
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

    <!-- ACTIVITY SUMMARY -->
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Recent Milestones
        </div>
        </div>
        <div style="padding: 10px 0;">
        <div class="act-item">
            <div class="act-col">
            <div class="act-dot" style="background:var(--accent)"></div>
            <div class="act-line"></div>
            </div>
            <div style="flex: 1;">
            <div class="act-text">Completed <strong>API Integration</strong> task in Projects</div>
            <div class="act-time">3 hours ago</div>
            </div>
        </div>
        <div class="act-item">
            <div class="act-col">
            <div class="act-dot" style="background:var(--blue)"></div>
            <div class="act-line"></div>
            </div>
            <div style="flex: 1;">
            <div class="act-text">Successfully submitted <strong>Daily Log</strong> for today</div>
            <div class="act-time">Today, 2:30 PM</div>
            </div>
        </div>
        <div class="act-item" style="border-bottom: none;">
            <div class="act-col">
            <div class="act-dot" style="background:var(--amber)"></div>
            <div class="act-line" style="display:none;"></div>
            </div>
            <div style="flex: 1;">
            <div class="act-text">Joined the <strong>DevOps Migration</strong> project team</div>
            <div class="act-time">Yesterday</div>
            </div>
        </div>
        </div>
    </div>
    </div>

</div>

@push('styles')
<style>
    /* Inline styles to verify parsing */
    .profile-header { display: flex; align-items: center; gap: 24px; margin-bottom: 28px; }
    .profile-avatar-lg { width: 80px; height: 80px; border-radius: 50%; background: var(--accent-lt); display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 700; color: var(--accent); flex-shrink: 0; border: 1px solid var(--border); }
    .profile-name { font-family: 'Instrument Serif', serif; font-size: 26px; font-weight: 400; }
    .profile-role-text { font-size: 14px; color: var(--text-3); margin-top: 4px; display: flex; align-items: center; gap: 10px; }

    .detail-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
    .detail-item { padding: 16px 18px; background: var(--surface-2); border-radius: var(--radius-sm); border: 1px solid var(--border); }
    .detail-label { font-size: 11px; color: var(--text-3); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
    .detail-value { font-size: 14.5px; color: var(--text-1); font-weight: 500; }

    .privacy-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .privacy-public { background: var(--accent-lt); color: var(--accent); }
    .privacy-private { background: var(--red-lt); color: var(--red); }
</style>
@endpush
@endsection
