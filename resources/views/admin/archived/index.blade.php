@extends('layouts.admin.master')

@section('title', 'WorkSphere — Archived')

@section('content')
<div class="page active" id="page-archived">
    <div class="section-header">
        <div>
            <div class="section-title">Archived Repository</div>
            <div class="section-sub">Consolidated soft-deleted resources with restoration capabilities</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="padding: 0 16px;">
            <div style="display: flex; gap: 24px;">
                <div class="tab-item active" data-tab="projects" onclick="switchThemeTab('projects')">Archived Projects</div>
                <div class="tab-item" data-tab="tasks" onclick="switchThemeTab('tasks')">Archived Tasks</div>
                <div class="tab-item" data-tab="employees" onclick="switchThemeTab('employees')">Archived Employees</div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="data-table" id="archive-table" style="width: 100%;">
                <thead id="archive-thead">
                    <!-- Dynamic Headers -->
                </thead>
                <tbody id="archive-tbody">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>
            
            <div id="archive-loading" style="padding: 40px; text-align: center; display: none;">
                <div class="skeleton" style="height: 20px; width: 100%; margin-bottom: 10px;"></div>
                <div class="skeleton" style="height: 20px; width: 100%; margin-bottom: 10px;"></div>
                <div class="skeleton" style="height: 20px; width: 100%;"></div>
            </div>

            <div id="archive-empty" style="padding: 40px; text-align: center; display: none; color: var(--text-3);">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 12px; opacity: 0.5;">
                    <polyline points="21 8 21 21 3 21 3 8"></polyline>
                    <rect x="1" y="3" width="22" height="5"></rect>
                    <line x1="10" y1="12" x2="14" y2="12"></line>
                </svg>
                <div>No archived items found in this category</div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-item { padding: 16px 0; font-size: 13px; font-weight: 500; color: var(--text-3); cursor: pointer; position: relative; transition: color 0.2s; }
    .tab-item:hover { color: var(--accent); }
    .tab-item.active { color: var(--accent); }
    .tab-item.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--accent); border-radius: 2px 2px 0 0; }
    .data-table thead th { padding: 12px 16px; font-size: 11px; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.05em; text-align: left; border-bottom: 1px solid var(--border); }
    .data-table tbody td { padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid var(--border); color: var(--text-1); }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .btn-restore { background: var(--accent-lt); color: var(--accent); border: none; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-restore:hover { background: var(--accent); color: #fff; }
    .skeleton { background: var(--surface-2); border-radius: 4px; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }
</style>

<script>
    let activeArchiveTab = 'projects';

    async function switchThemeTab(tab) {
        activeArchiveTab = tab;
        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
        document.querySelector(`.tab-item[data-tab="${tab}"]`).classList.add('active');
        fetchArchived();
    }

    async function fetchArchived() {
        const tbody = document.getElementById('archive-tbody');
        const thead = document.getElementById('archive-thead');
        const loader = document.getElementById('archive-loading');
        const empty = document.getElementById('archive-empty');
        const table = document.getElementById('archive-table');

        table.style.display = 'none';
        empty.style.display = 'none';
        loader.style.display = 'block';

        try {
            const res = await axios.get(`/api/admin/${activeArchiveTab}/archived`);
            const data = (activeArchiveTab === 'employees') ? res.data.data.data : res.data.data.data; 
            // Handle different pagination wrappers if necessary, but usually it's in data.data.data

            loader.style.display = 'none';

            if (!data || data.length === 0) {
                empty.style.display = 'block';
                return;
            }

            table.style.display = 'table';
            renderArchiveHeaders(thead);
            renderArchiveRows(tbody, data);

        } catch (err) {
            console.error('Fetch archived error:', err);
            loader.style.display = 'none';
            empty.style.display = 'block';
        }
    }

    function renderArchiveHeaders(thead) {
        let headers = '';
        if (activeArchiveTab === 'projects') {
            headers = `<tr><th>Project Name</th><th>Status</th><th>Archived Date</th><th>Action</th></tr>`;
        } else if (activeArchiveTab === 'tasks') {
            headers = `<tr><th>Task Title</th><th>Due Date</th><th>Priority</th><th>Action</th></tr>`;
        } else if (activeArchiveTab === 'employees') {
            headers = `<tr><th>Employee</th><th>Code</th><th>Designation</th><th>Action</th></tr>`;
        }
        thead.innerHTML = headers;
    }

    function renderArchiveRows(tbody, items) {
        tbody.innerHTML = items.map(item => {
            if (activeArchiveTab === 'projects') {
                return `<tr>
                    <td style="font-weight:500">${item.name}</td>
                    <td><span class="privacy-pill privacy-public">${item.status}</span></td>
                    <td>${item.updated_at ? new Date(item.updated_at).toLocaleDateString() : 'N/A'}</td>
                    <td><button class="btn-restore" onclick="restoreArchive(${item.id})">Restore</button></td>
                </tr>`;
            } else if (activeArchiveTab === 'tasks') {
                 return `<tr>
                    <td style="font-weight:500">${item.title}</td>
                    <td>${item.due_date ? new Date(item.due_date).toLocaleDateString() : 'N/A'}</td>
                    <td>${item.priority}</td>
                    <td><button class="btn-restore" onclick="restoreArchive(${item.id})">Restore</button></td>
                </tr>`;
            } else if (activeArchiveTab === 'employees') {
                 return `<tr>
                    <td style="font-weight:500">${item.user?.name || 'N/A'}</td>
                    <td>${item.employee_code}</td>
                    <td>${item.designation?.name || 'N/A'}</td>
                    <td><button class="btn-restore" onclick="restoreArchive(${item.id})">Restore</button></td>
                </tr>`;
            }
        }).join('');
    }

    async function restoreArchive(id) {
        if (!confirm('Are you sure you want to restore this item?')) return;
        
        try {
            await axios.post(`/api/admin/${activeArchiveTab}/${id}/restore`);
            fetchArchived();
        } catch (err) {
            alert('Restore failed: ' + (err.response?.data?.message || 'Error occurred'));
        }
    }

    document.addEventListener('DOMContentLoaded', fetchArchived);
</script>
@endsection
