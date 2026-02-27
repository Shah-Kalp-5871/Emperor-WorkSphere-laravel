@extends('layouts.employee.master')

@section('title', 'WorkSphere — Edit Profile')
@section('page_title', 'Edit Profile')

@section('content')
<div class="page-profile-edit" style="animation: fadeUp .4s ease both;">
    
    <div style="margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
        <a href="{{ url('/employee/profile/my-profile') }}" class="greeting-btn" style="background:var(--surface-2);color:var(--text-2);border:1px solid var(--border)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Profile
        </a>
    </div>

    <div class="two-col" style="grid-template-columns: 1.2fr 0.8fr; align-items: flex-start;">
        <!-- EDIT FORM -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Personal Information
                </div>
            </div>
            <div style="padding: 24px;">
                <form id="editProfileForm">
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="edit-name" class="form-control" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" id="edit-email" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Phone Number</label>
                        <input type="text" id="edit-phone" class="form-control" placeholder="Enter your phone number">
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>About Me</label>
                        <textarea id="edit-about-me" class="form-control" rows="4" placeholder="Brief description about yourself..."></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>My Skills (comma separated)</label>
                        <input type="text" id="edit-skills" class="form-control" placeholder="e.g. Laravel, React, PHP">
                        <small style="color: var(--text-3); font-size: 12px; margin-top: 4px; display: block;">Separate skills with commas.</small>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label>Address</label>
                        <textarea id="edit-address" class="form-control" rows="3" placeholder="Enter your current address"></textarea>
                    </div>

                    <div style="margin-top: 30px; display: flex; justify-content: flex-end;">
                        <button type="submit" class="greeting-btn" id="save-profile-btn" style="min-width: 150px; justify-content: center;">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECURITY SETTINGS -->
        <div class="panel" id="security">
            <div class="panel-header">
                <div class="panel-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Security Settings
                </div>
            </div>
            <div style="padding: 24px;">
                <form id="changePasswordForm">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" id="current_password" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label>New Password</label>
                        <input type="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label>Confirm New Password</label>
                        <input type="password" id="password_confirmation" class="form-control" required>
                    </div>
                    <div style="margin-top: 25px;">
                        <button type="submit" class="greeting-btn" id="save-password-btn" style="width: 100%; justify-content: center;">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group label { display: block; font-size: 13.5px; font-weight: 500; color: var(--text-2); margin-bottom: 8px; }
    .form-control { 
        width: 100%; 
        padding: 12px; 
        border-radius: 8px; 
        border: 1px solid var(--border); 
        background: var(--surface-2); 
        color: var(--text-1); 
        font-size: 14px; 
        transition: all .2s ease;
    }
    .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.1); }
</style>

@push('scripts')
<script>
    let currentUser = null;

    async function loadData() {
        try {
            const res = await axios.get('/api/me');
            currentUser = res.data;
            
            // Populate Form
            document.getElementById('edit-name').value = currentUser.name;
            document.getElementById('edit-email').value = currentUser.email;
            document.getElementById('edit-phone').value = currentUser.employee?.phone || '';
            document.getElementById('edit-about-me').value = currentUser.employee?.about_me || '';
            document.getElementById('edit-skills').value = currentUser.employee?.skills || '';
            document.getElementById('edit-address').value = currentUser.employee?.address || '';
        } catch (err) {
            console.error('Load Error:', err);
            alert('Failed to load profile data.');
        }
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
                about_me: document.getElementById('edit-about-me').value,
                skills: document.getElementById('edit-skills').value,
                address: document.getElementById('edit-address').value,
            });
            alert('Profile updated successfully!');
            window.location.href = '/employee/profile/my-profile';
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
            document.getElementById('changePasswordForm').reset();
        } catch (err) {
            alert('Password update failed: ' + (err.response?.data?.message || 'Check your fields'));
        } finally {
            btn.disabled = false;
            btn.textContent = 'Update Password';
        }
    });

    document.addEventListener('DOMContentLoaded', loadData);
</script>
@endpush
@endsection
