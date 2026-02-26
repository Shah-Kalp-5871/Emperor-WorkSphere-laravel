@extends('layouts.admin.master')

@section('title', 'WorkSphere — Projects')

@section('content')
<div class="page active" id="page-projects">
    <div class="section-header">
    <div>
        <div class="section-title">Projects</div>
        <div class="section-sub">5 active · 2 archived</div>
    </div>
    <div class="section-actions">
        <button class="btn btn-primary" onclick="openCreateModal()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Project
        </button>
    </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal-overlay" id="create-proj-modal">
        <div class="modal">
            <div class="modal-close" onclick="closeModal('create-proj-modal')">✕</div>
            <div class="modal-title">New Project</div>
            <div class="modal-sub">Create a new workspace for your team.</div>
            
            <form id="create-project-form" onsubmit="handleCreateProject(event)">
                <div class="form-group">
                    <label class="form-label">Project Name</label>
                    <input class="form-input" id="proj-name" required placeholder="e.g. Customer Portal v3">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input class="form-input" id="proj-desc" placeholder="Brief description…">
                </div>
                <div class="form-group">
                    <label class="form-label">Add Members</label>
                    <select class="form-select" id="project-members-select" multiple style="height:120px; width:100%; border:1px solid var(--border); border-radius:8px; background:var(--bg-1); color:var(--text-1); padding:8px;">
                        <!-- Options will be loaded via JS -->
                    </select>
                    <small style="color:var(--text-3); font-size:11px; margin-top:4px; display:block;">Hold Ctrl/Cmd to select multiple</small>
                </div>
                
                <div id="modal-error" style="color: #ef4444; font-size: 13px; margin-bottom: 16px; display: none;"></div>

                <div class="modal-footer" style="padding:0; margin-top:24px;">
                    <button type="button" class="btn btn-ghost" onclick="closeModal('create-proj-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="create-proj-btn">Create Project</button>
                </div>
            </form>
        </div>
    </div>
    <div class="filter-bar">
    <input class="filter-input" placeholder="Search projects…">
    <select class="filter-select"><option>All Projects</option><option>My Projects</option></select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-projects">
        <thead><tr>
            <th>Project</th><th>Creator</th><th>Members</th><th>Tasks</th><th>Progress</th><th>Created</th><th>Actions</th>
        </tr></thead>
        <tbody id="projects-tbody">
            <!-- Dynamic rows will be inserted here -->
        </tbody>
    </table>

    <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
        <div class="spinner" style="border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
        <div style="color: var(--text-2); font-size: 14px;">Loading projects...</div>
    </div>

    <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
        <div style="font-size: 24px; margin-bottom: 10px;">📁</div>
        <div style="color: var(--text-2); font-size: 14px;">No projects found.</div>
    </div>

    <div id="table-error" style="text-align:center; padding: 40px; display: none; color: #ff4d4d;">
        <div style="font-size: 20px; margin-bottom: 10px;">⚠️</div>
        <div id="error-message" style="font-size: 14px;">Failed to load projects.</div>
    </div>
    </div>

    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-top: 1px solid var(--border);">
        <div id="pagination-info" style="color: var(--text-2); font-size: 13px;">Showing 0 of 0 projects</div>
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

    function openCreateModal() {
        openModal('create-proj-modal');
        loadEmployeesForModal();
    }

    async function loadEmployeesForModal() {
        const select = document.getElementById('project-members-select');
        if (select.options.length > 0) return; // Already loaded

        try {
            const response = await axios.get('/api/admin/employees?per_page=100');
            const employees = response.data.data;
            
            employees.forEach(emp => {
                const opt = document.createElement('option');
                opt.value = emp.id;
                opt.textContent = `${emp.user.name} (${emp.employee_code})`;
                select.appendChild(opt);
            });
        } catch (error) {
            console.error('Failed to load employees for modal:', error);
        }
    }

    async function handleCreateProject(e) {
        e.preventDefault();
        const btn = document.getElementById('create-proj-btn');
        const errorDiv = document.getElementById('modal-error');
        
        const name = document.getElementById('proj-name').value;
        const description = document.getElementById('proj-desc').value;
        const members = Array.from(document.getElementById('project-members-select').selectedOptions).map(option => option.value);

        btn.disabled = true;
        btn.textContent = 'Creating...';
        errorDiv.style.display = 'none';

        try {
            const response = await axios.post('/api/admin/projects', {
                name,
                description,
                status: 'planning', // default
                priority: 'medium'   // default
            });
            
            const projectId = response.data.data.id;

            // Assign employees if any
            if (members.length > 0) {
                await axios.post(`/api/admin/projects/${projectId}/assign-employees`, {
                    employee_ids: members
                });
            }

            closeModal('create-proj-modal');
            fetchProjects(1); // Refresh list
            
            // Clear form
            document.getElementById('proj-name').value = '';
            document.getElementById('proj-desc').value = '';
            document.getElementById('project-members-select').value = '';

        } catch (error) {
            console.error('Project creation failed:', error);
            errorDiv.innerText = error.response?.data?.message || 'Failed to create project.';
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Create Project';
        }
    }

    async function archiveProject(id) {
        if (!confirm('Are you sure you want to archive this project?')) return;

        try {
            await axios.delete(`/api/admin/projects/${id}`);
            fetchProjects(currentPage);
        } catch (error) {
            console.error('Archive failed:', error);
            alert(error.response?.data?.message || 'Failed to archive project.');
        }
    }

    async function fetchProjects(page = 1) {
        const tbody = document.getElementById('projects-tbody');
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
            const response = await axios.get(`/api/admin/projects?page=${page}`);
            const { data, meta } = response.data; // JsonResource collection returns data and links/meta
            
            // If the structure is wrapped in 'data' (common for Resources)
            const projects = data;
            const paginationMeta = meta || response.data;

            loading.style.display = 'none';

            if (!projects || projects.length === 0) {
                empty.style.display = 'block';
                return;
            }

            projects.forEach(project => {
                const tr = document.createElement('tr');
                const createdDate = project.created_at ? new Date(project.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'N/A';
                
                const progress = project.tasks_count > 0 ? Math.round((project.completed_tasks_count / project.tasks_count) * 100) : 0;
                const progressColor = progress > 70 ? 'var(--accent)' : (progress > 30 ? 'var(--accent2)' : 'var(--accent3)');

                let avatarsHtml = '';
                if (project.members && project.members.length > 0) {
                    project.members.slice(0, 3).forEach((member, i) => {
                        const initial = member.name[0].toUpperCase();
                        avatarsHtml += `<div class="av ${i > 0 ? 'av' + (i+1) : ''}">${initial}</div>`;
                    });
                    if (project.members.length > 3) {
                        avatarsHtml += `<div class="av av4">+${project.members.length - 3}</div>`;
                    }
                }

                tr.innerHTML = `
                    <td class="td-main">${project.name}</td>
                    <td>${project.creator_name}</td>
                    <td><div class="avatar-group">${avatarsHtml}</div></td>
                    <td>${project.tasks_count} tasks · ${project.completed_tasks_count} done</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="progress-bar-wrap" style="width:70px">
                                <div class="progress-bar" style="width:${progress}%; background:${progressColor}"></div>
                            </div>
                            <span style="font-size:12px;color:var(--text3)">${progress}%</span>
                        </div>
                    </td>
                    <td>${createdDate}</td>
                    <td style="display:flex;gap:6px;padding-top:13px">
                        <button class="btn btn-ghost btn-sm" onclick="alert('Viewing project ID: ${project.id}')">View</button>
                        <button class="btn btn-danger btn-sm" onclick="archiveProject(${project.id})">Archive</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            // Update Pagination
            if (paginationMeta) {
                currentPage = paginationMeta.current_page || 1;
                info.innerText = `Showing ${paginationMeta.from || 0} to ${paginationMeta.to || 0} of ${paginationMeta.total || 0} projects`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === (paginationMeta.last_page || 1);
            }

        } catch (error) {
            console.error('Fetch error:', error);
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = error.response?.data?.message || 'Failed to load projects.';
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchProjects(currentPage - 1);
    });

    document.getElementById('next-page').addEventListener('click', () => {
        fetchProjects(currentPage + 1);
    });

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        fetchProjects();
    });
</script>
@endpush
@endsection
