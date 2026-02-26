@extends('layouts.admin.master')

@section('title', 'WorkSphere — Employees')

@section('content')
<div class="page active" id="page-employees">
    <div class="section-header">
    <div>
        <div class="section-title">Employees</div>
        <div class="section-sub">12 active employees</div>
    </div>
    <div class="section-actions">
        <button class="btn btn-primary" onclick="window.location.href='/admin/employees/create'">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Employee
        </button>
    </div>
    </div>
    <div class="filter-bar">
    <input class="filter-input" placeholder="Search by name or username…">
    <select class="filter-select"><option>All Profiles</option><option>Public</option><option>Private</option></select>
    <select class="filter-select"><option>All Status</option><option>Active</option><option>Inactive</option></select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-employees">
        <thead><tr>
            <th>Employee</th><th>Username</th><th>Email</th><th>Mobile</th><th>Profile</th><th>Joined</th><th>Actions</th>
        </tr></thead>
        <tbody id="employee-tbody">
            <!-- Dynamic rows will be inserted here -->
        </tbody>
    </table>
    
    <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
        <div class="spinner" style="border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
        <div style="color: var(--text-2); font-size: 14px;">Loading employees...</div>
    </div>

    <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
        <div style="font-size: 24px; margin-bottom: 10px;">👥</div>
        <div style="color: var(--text-2); font-size: 14px;">No employees found.</div>
    </div>

    <div id="table-error" style="text-align:center; padding: 40px; display: none; color: #ff4d4d;">
        <div style="font-size: 20px; margin-bottom: 10px;">⚠️</div>
        <div id="error-message" style="font-size: 14px;">Failed to load employees.</div>
    </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-top: 1px solid var(--border);">
        <div id="pagination-info" style="color: var(--text-2); font-size: 13px;">Showing 0 of 0 employees</div>
        <div id="pagination-controls" style="display: flex; gap: 8px;">
            <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
            <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
        </div>
    </div>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchEmployees(page = 1) {
        const tbody = document.getElementById('employee-tbody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        errorDiv.style.display = 'none';
        
        try {
            const response = await axios.get(`/api/admin/employees?page=${page}`);
            const { data, current_page, last_page, total, from, to } = response.data;

            loading.style.display = 'none';

            if (data.length === 0) {
                empty.style.display = 'block';
                return;
            }

            data.forEach(employee => {
                const tr = document.createElement('tr');
                const fullName = employee.user ? employee.user.name : 'N/A';
                const initial = fullName !== 'N/A' ? fullName[0].toUpperCase() : '?';
                const email = employee.user ? employee.user.email : 'N/A';
                const joinedDate = employee.created_at ? new Date(employee.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                const privacyClass = employee.profile_visibility === 'private' ? 'privacy-private' : 'privacy-public';
                const privacyIcon = employee.profile_visibility === 'private' ? '🔒' : '🌍';
                const privacyText = employee.profile_visibility === 'private' ? 'Private' : 'Public';

                tr.innerHTML = `
                    <td><div style="display:flex;align-items:center;gap:10px"><div class="av" style="margin:0;width:32px;height:32px">${initial}</div><span class="td-main">${fullName}</span></div></td>
                    <td>${employee.employee_code || 'N/A'}</td>
                    <td>${email}</td>
                    <td>${employee.phone || 'N/A'}</td>
                    <td><span class="privacy-pill ${privacyClass}">${privacyIcon} ${privacyText}</span></td>
                    <td>${joinedDate}</td>
                    <td><button class="btn btn-ghost btn-sm" onclick="alert('Viewing employee ID: ${employee.id}')">View</button></td>
                `;
                tbody.appendChild(tr);
            });

            // Update Pagination
            currentPage = current_page;
            info.innerText = `Showing ${from || 0} to ${to || 0} of ${total} employees`;
            prevBtn.disabled = current_page === 1;
            nextBtn.disabled = current_page === last_page;

        } catch (error) {
            console.error('Fetch error:', error);
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = error.response?.data?.message || 'Failed to load employees.';
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchEmployees(currentPage - 1);
    });

    document.getElementById('next-page').addEventListener('click', () => {
        fetchEmployees(currentPage + 1);
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        fetchEmployees();
    });
</script>
@endpush

@endsection
