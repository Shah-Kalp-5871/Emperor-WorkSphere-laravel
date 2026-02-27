@extends('layouts.employee.master')

@section('title', 'WorkSphere — My Projects')
@section('page_title', 'My Projects')

@section('content')
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>My <em>Projects</em> 📁</h2>
        <p>Overview of all projects you are currently assigned to.</p>
      </div>
    </div>

    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">Active Projects <span class="count" id="project-count">0</span></div>
        <button class="action-btn" style="background: var(--accent); color: #fff; border: none; font-weight: 600;" onclick="openCreateModal()">+ Create Project</button>
      </div>

      <!-- CREATE PROJECT MODAL -->
      <div id="create-project-modal" class="modal-backdrop" style="display:none;">
          <div class="modal-content" style="max-width: 500px;">
              <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; padding:15px 20px; border-bottom:1px solid var(--border);">
                  <h3 style="margin:0; font-size:16px;">Create New Project</h3>
                  <button type="button" class="close-btn" style="background:none; border:none; font-size:24px; cursor:pointer; color:var(--text-3);" onclick="closeCreateModal()">&times;</button>
              </div>
              <form id="create-project-form">
                  <div style="padding: 20px;">
                      <div class="field-group" style="margin-bottom:15px">
                          <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">Project Name</label>
                          <input type="text" id="proj_name" class="lf" placeholder="Enter project name" required>
                      </div>
                      <div class="field-group" style="margin-bottom:15px">
                          <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">Description</label>
                          <textarea id="proj_desc" class="lf" style="height:80px" placeholder="Enter project description"></textarea>
                      </div>
                      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:15px">
                          <div class="field-group">
                              <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">Priority</label>
                              <select id="proj_priority" class="lf">
                                  <option value="low">Low</option>
                                  <option value="medium" selected>Medium</option>
                                  <option value="high">High</option>
                              </select>
                          </div>
                      </div>
                      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:15px">
                          <div class="field-group">
                              <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">Start Date</label>
                              <input type="date" id="proj_start" class="lf">
                          </div>
                          <div class="field-group">
                              <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">End Date</label>
                              <input type="date" id="proj_end" class="lf">
                          </div>
                      </div>

                      <div class="field-group">
                          <label style="display:block; font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-3); margin-bottom:6px;">Assign Teammates</label>
                          <div id="teammate-list" style="max-height: 120px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px; padding: 10px; background: var(--bg);">
                              <div style="font-size: 12px; color: var(--text-3); text-align: center; padding: 10px;">Loading teammates...</div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer" style="padding:15px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:10px;">
                      <button type="button" class="action-btn" onclick="closeCreateModal()">Cancel</button>
                      <button type="submit" class="greeting-btn" id="submit-project-btn">Create Project</button>
                  </div>
              </form>
          </div>
      </div>
      
      <div class="table-container">
        <table class="data-table" id="tbl-emp-projects">
          <thead>
            <tr>
              <th>Project</th>
              <th>Creator</th>
              <th>Members</th>
              <th>Tasks</th>
              <th>Progress</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="project-list-body">
            <!-- Loading Skeletons -->
            @for($i=0; $i<3; $i++)
            <tr class="skeleton-row">
                <td><div class="skeleton" style="width:140px;height:15px;margin-bottom:8px"></div><div class="skeleton" style="width:100px;height:10px"></div></td>
                <td><div class="skeleton" style="width:80px;height:12px"></div></td>
                <td><div style="display:flex;gap:4px"><div class="skeleton" style="width:24px;height:24px;border-radius:50%"></div><div class="skeleton" style="width:24px;height:24px;border-radius:50%"></div></div></td>
                <td><div class="skeleton" style="width:90px;height:12px"></div></td>
                <td><div class="skeleton" style="width:100px;height:15px"></div></td>
                <td><div class="skeleton" style="width:80px;height:12px"></div></td>
                <td><div class="skeleton" style="width:80px;height:28px;border-radius:6px"></div></td>
            </tr>
            @endfor
          </tbody>
        </table>

        <div id="projects-empty" style="display:none; padding:40px; text-align:center; color:var(--text-3);">
            <div style="font-size:32px;margin-bottom:12px">📂</div>
            <p>You are not assigned to any active projects yet.</p>
        </div>
      </div>
    </div>
@push('scripts')
<script>
async function fetchProjects() {
    const tbody = document.getElementById('project-list-body');
    const emptyDiv = document.getElementById('projects-empty');
    const countSpan = document.getElementById('project-count');

    try {
        const res = await axios.get('/api/employee/projects');
        const projects = res.data.data.data || res.data.data; // Handle both paginated and direct array

        if (projects.length === 0) {
            tbody.innerHTML = '';
            emptyDiv.style.display = 'block';
            countSpan.textContent = '0';
            return;
        }

        emptyDiv.style.display = 'none';
        countSpan.textContent = projects.length;
        
        tbody.innerHTML = projects.map(p => {
            const creator = p.creator_name || 'Admin';
            const createdDate = new Date(p.created_at).toLocaleDateString();
            const progress = p.progress || 0; // Assuming backend calculates or default to 0
            
            // Member Names
            const members = p.members || [];
            const memberNames = members.map(m => m.name || 'Unknown').join(', ');
            const memberHtml = `<div style="font-size: 13px; color: var(--text-2);" title="${memberNames}">
                ${memberNames.length > 40 ? memberNames.substring(0, 40) + '...' : memberNames}
            </div>`;

            return `
                <tr>
                  <td>
                    <div style="font-weight: 600; color: var(--text-1);">${p.name}</div>
                    <div style="font-size: 11px; color: var(--text-3);">${p.description ? p.description.substring(0, 40) + '...' : 'No description'}</div>
                  </td>
                  <td>${creator}</td>
                  <td><div class="member-avatars">${memberHtml}</div></td>
                  <td>${p.tasks_count || 0} tasks</td>
                  <td>
                    <div class="progress-wrap">
                      <span class="progress-text">${progress}%</span>
                      <div class="proj-bar-bg"><div class="proj-bar-fill" style="width: ${progress}%; background: var(--accent);"></div></div>
                    </div>
                  </td>
                  <td>${createdDate}</td>
                  <td><button class="action-btn" onclick="window.location.href='/employee/projects/details?id=${p.id}'">View Details</button></td>
                </tr>
            `;
        }).join('');

    } catch (err) {
        console.error('Fetch projects error:', err);
    }
}

document.addEventListener('DOMContentLoaded', fetchProjects);

function openCreateModal() {
    document.getElementById('create-project-modal').style.display = 'flex';
    fetchTeammates();
}

async function fetchTeammates() {
    const list = document.getElementById('teammate-list');
    try {
        const res = await axios.get('/api/employee/team');
        const team = res.data.data.data; // Paginated response
        
        if (!team || team.length === 0) {
            list.innerHTML = '<div style="font-size:12px;color:var(--text-3);text-align:center;padding:10px">No teammates found.</div>';
            return;
        }

        list.innerHTML = team.map(member => `
            <label style="display:flex; align-items:center; gap:8px; padding:6px 0; cursor:pointer; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.03);">
                <input type="checkbox" name="employee_ids[]" value="${member.id}" style="width:16px;height:16px;accent-color:var(--accent)">
                <span>${member.user.name} <small style="color:var(--text-3)">(${member.designation?.name || 'Employee'})</small></span>
            </label>
        `).join('');
    } catch (err) {
        list.innerHTML = '<div style="font-size:12px;color:var(--red);text-align:center;padding:10px">Failed to load teammates.</div>';
    }
}

function closeCreateModal() {
    document.getElementById('create-project-modal').style.display = 'none';
    document.getElementById('create-project-form').reset();
    document.getElementById('teammate-list').innerHTML = '<div style="font-size:12px;color:var(--text-3);text-align:center;padding:10px">Loading teammates...</div>';
}

document.getElementById('create-project-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submit-project-btn');
    
    const selectedMembers = Array.from(document.querySelectorAll('input[name="employee_ids[]"]:checked')).map(cb => cb.value);

    const data = {
        name: document.getElementById('proj_name').value,
        description: document.getElementById('proj_desc').value,
        priority: document.getElementById('proj_priority').value,
        start_date: document.getElementById('proj_start').value || null,
        end_date: document.getElementById('proj_end').value || null,
        employee_ids: selectedMembers
    };

    btn.innerHTML = 'Creating...';
    btn.disabled = true;

    try {
        await axios.post('/api/employee/projects', data);
        alert('Project created successfully!');
        closeCreateModal();
        fetchProjects();
    } catch (err) {
        alert(err.response?.data?.message || 'Failed to create project.');
    } finally {
        btn.innerHTML = 'Create Project';
        btn.disabled = false;
    }
});
</script>
@endpush
@endsection

@push('styles')
<style>
    .table-container { padding: 0; overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 800px; }
    .data-table th { 
        text-align: left; 
        padding: 12px 22px; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        color: var(--text-3); 
        border-bottom: 1px solid var(--border);
        font-weight: 600;
    }
    .data-table td { 
        padding: 16px 22px; 
        font-size: 13.5px; 
        border-bottom: 1px solid var(--border);
        color: var(--text-2);
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover { background: var(--surface-2); }
    .member-avatars { display: flex; align-items: center; }
    .member-avatar { 
        width: 24px; 
        height: 24px; 
        border-radius: 50%; 
        border: 2px solid var(--surface); 
        background: var(--accent-lt); 
        color: var(--accent); 
        font-size: 10px; 
        font-weight: 600; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-left: -8px;
    }
    .member-avatar:first-child { margin-left: 0; }
    .progress-wrap { width: 100px; }
    .progress-text { font-size: 11px; color: var(--text-3); margin-bottom: 4px; display: block; text-align: right; }
    .action-btn { 
        padding: 6px 12px; 
        border-radius: 6px; 
        border: 1px solid var(--border); 
        background: #fff; 
        font-size: 12px; 
        color: var(--text-2); 
        cursor: pointer; 
        transition: all 0.2s;
    }
    .action-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-lt); }

    .modal-backdrop { 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0,0,0,0.4); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        z-index: 1000; 
        animation: fadeIn 0.2s ease;
    }
    .modal-content { 
        background: #fff; 
        border-radius: 12px; 
        width: 90%; 
        box-shadow: var(--shadow-md); 
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endpush
