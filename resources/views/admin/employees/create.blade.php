@extends('layouts.admin.master')

@section('title', 'WorkSphere — Add Employee')

@section('content')
<div class="page active" id="page-employees-create">
    <div class="section-header">
        <div>
            <div class="section-title">Add New Employee</div>
            <div class="section-sub">Quickly create an account for a new team member.</div>
        </div>
        <div class="section-actions">
            <button class="btn btn-ghost" onclick="window.location.href='/admin/employees'">
                Back to List
            </button>
        </div>
    </div>

    <div class="card" style="max-width: 500px; margin: 40px auto; padding: 32px;">
        <!-- Form View -->
        <div id="setup-view">
            <form id="employee-form">
                <div class="form-group">
                    <label class="form-label">Work Email <span style="color:red">*</span></label>
                    <input type="email" class="input" id="email" name="email" required placeholder="name@worksphere.com">
                </div>
                
                <div class="form-group" style="position: relative;">
                    <label class="form-label">Password <span style="color:red">*</span></label>
                    <input type="password" class="input" id="password" name="password" required placeholder="Min 8 characters">
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 10px; top: 38px; background: none; border: none; cursor: pointer; font-size: 14px; opacity: 0.6;">👁️</button>
                </div>

                <div style="margin-top: 32px; display: flex; flex-direction: column; gap: 12px;">
                    <button type="submit" class="btn btn-primary" id="submit-btn" style="width: 100%; height: 44px;">Create Account</button>
                    <div id="form-error" style="color: #ef4444; font-size: 13px; text-align: center; display: none;"></div>
                </div>
            </form>
        </div>

        <!-- Success/Copy View -->
        <div id="success-view" style="display: none; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 20px;">✅</div>
            <h3 style="margin-bottom: 8px;">Employee Account Created!</h3>
            <p style="color: var(--text-2); font-size: 14px; margin-bottom: 24px;">Copy the credentials below to send via WhatsApp.</p>
            
            <div class="copy-box" id="whatsapp-text" style="background: var(--bg-1); border: 1px solid var(--border); border-radius: 12px; padding: 16px; text-align: left; margin-bottom: 24px; font-family: monospace; white-space: pre-wrap; font-size: 13px;"></div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <button class="btn btn-primary" onclick="copyToClipboard()" id="copy-btn">Copy for WhatsApp</button>
                <button class="btn btn-ghost" onclick="window.location.href='/admin/employees'">Back to List</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword() {
        const pass = document.getElementById('password');
        pass.type = pass.type === 'password' ? 'text' : 'password';
    }

    async function copyToClipboard() {
        const text = document.getElementById('whatsapp-text').innerText;
        const btn = document.getElementById('copy-btn');
        
        try {
            await navigator.clipboard.writeText(text);
            const originalText = btn.innerText;
            btn.innerText = 'Copied!';
            btn.style.background = '#22c55e';
            setTimeout(() => {
                btn.innerText = originalText;
                btn.style.background = '';
            }, 2000);
        } catch (err) {
            alert('Failed to copy');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('employee-form');
        const setupView = document.getElementById('setup-view');
        const successView = document.getElementById('success-view');
        const whatsappBox = document.getElementById('whatsapp-text');
        const errorDiv = document.getElementById('form-error');
        const submitBtn = document.getElementById('submit-btn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Setting up...';
            errorDiv.style.display = 'none';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await axios.post('/api/admin/employees', data);
                const employee = response.data.data;
                
                // Build WhatsApp Text
                const message = `*Welcome to WorkSphere!* 🚀\n\n` +
                                `Your account has been set up. Please use the following credentials to log in:\n\n` +
                                `*Login URL:* ${window.location.origin}/employee/login\n` +
                                `*Email:* ${data.email}\n` +
                                `*Password:* ${data.password}\n` +
                                `*Employee ID:* ${employee.employee_code}\n\n` +
                                `_Please log in and complete your profile details._`;

                whatsappBox.innerText = message;
                
                setupView.style.display = 'none';
                successView.style.display = 'block';

            } catch (error) {
                console.error('Creation failed:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Create Account';
                
                errorDiv.innerText = error.response?.data?.message || 'Failed to create employee.';
                errorDiv.style.display = 'block';
                
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorDiv.innerText = Object.values(errors)[0][0];
                }
            }
        });
    });
</script>
@endpush

<style>
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-2); margin-bottom: 8px; }
    .input { 
        width: 100%; 
        background: var(--bg-3); 
        border: 1px solid var(--border); 
        border-radius: 10px; 
        padding: 12px 14px; 
        color: var(--text-1); 
        font-size: 15px;
        transition: all 0.2s;
    }
    .input:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
</style>
@endsection
