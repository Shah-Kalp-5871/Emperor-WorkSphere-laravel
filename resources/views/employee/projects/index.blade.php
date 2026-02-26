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
            const creator = p.creator?.name || 'Admin';
            const createdDate = new Date(p.created_at).toLocaleDateString();
            const progress = p.progress || 0; // Assuming backend calculates or default to 0
            
            // Member Avatars
            const members = p.employees || [];
            const memberHtml = members.slice(0, 3).map(m => `
                <div class="member-avatar" title="${m.user?.name}">${m.user?.name ? m.user.name[0].toUpperCase() : '?'}</div>
            `).join('') + (members.length > 3 ? `<div class="member-avatar">+${members.length - 3}</div>` : '');

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
</style>
@endpush
