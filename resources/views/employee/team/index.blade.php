@extends('layouts.employee.master')

@section('title', 'WorkSphere — Team Directory')
@section('page_title', 'Team Directory')

@section('content')
    <div class="greeting-banner">
      <div class="greeting-text">
        <h2>Our <em>Team</em> 🤝</h2>
        <p>Connect with your colleagues and see who's currently available.</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <div class="stat-badge up" id="online-count" style="padding: 8px 14px;">0 Online</div>
      </div>
    </div>

    <div class="team-grid" id="team-grid">
      <!-- Loading Skeletons -->
      @for($i=0; $i<6; $i++)
      <div class="team-card skeleton-card">
        <div class="skeleton" style="width:80px;height:80px;border-radius:50%;margin-bottom:16px"></div>
        <div class="skeleton" style="width:140px;height:20px;margin-bottom:8px"></div>
        <div class="skeleton" style="width:100px;height:14px;margin-bottom:12px"></div>
        <div class="skeleton" style="width:180px;height:14px;margin-bottom:20px"></div>
        <div style="display:flex;gap:8px;width:100%"><div class="skeleton" style="flex:1;height:34px;border-radius:6px"></div><div class="skeleton" style="flex:1;height:34px;border-radius:6px"></div></div>
      </div>
      @endfor
    </div>

    <div id="team-empty" style="display:none; padding:40px; text-align:center; color:var(--text-3);">
        <p>No team members found.</p>
    </div>
@push('scripts')
<script>
async function fetchTeam() {
    const grid = document.getElementById('team-grid');
    const empty = document.getElementById('team-empty');
    const onlineBadge = document.getElementById('online-count');

    try {
        const res = await axios.get('/api/employee/team');
        const members = res.data.data.data || res.data.data;

        if (members.length === 0) {
            grid.innerHTML = '';
            empty.style.display = 'block';
            onlineBadge.textContent = '0 Online';
            return;
        }

        empty.style.display = 'none';
        onlineBadge.textContent = `${members.filter(m => m.is_active).length} Online`; // Simulation: all active are online

        grid.innerHTML = members.map(m => {
            const user = m.user || { name: 'Unknown', email: 'N/A' };
            const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            const role = m.designation?.name || 'Employee';
            const isOnline = m.is_active; // Simulation

            return `
                <div class="team-card">
                    <div class="status-indicator">
                        <div class="status-dot ${isOnline ? 'online' : 'offline'}"></div>
                        <span style="color: ${isOnline ? '#22c55e' : 'var(--text-3)'}">${isOnline ? 'Online' : 'Offline'}</span>
                    </div>
                    <div class="team-avatar-lg">${initials}</div>
                    <h3 class="member-name">${user.name}</h3>
                    <p class="member-role">${role}</p>
                    <p class="member-email">${user.email}</p>
                    <div class="card-actions">
                        <button class="card-btn" onclick="window.location.href='/employee/profile/my-profile?id=${m.id}'">View Profile</button>
                        <button class="card-btn" onclick="alert('Message feature coming soon!')">Message</button>
                    </div>
                </div>
            `;
        }).join('');

    } catch (err) {
        console.error('Fetch team error:', err);
    }
}

document.addEventListener('DOMContentLoaded', fetchTeam);
</script>
@endpush
@endsection

@push('styles')
<style>
    .team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 24px;
    }
    .team-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--shadow);
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    }
    .team-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--accent);
    }
    .team-avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--accent-lt);
    color: var(--accent);
    font-size: 28px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    border: 3px solid var(--surface);
    box-shadow: 0 0 0 1px var(--border);
    }
    .status-indicator {
    position: absolute;
    top: 24px;
    right: 24px;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    }
    .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    }
    .status-dot.online { background: #22c55e; box-shadow: 0 0 8px #22c55e; }
    .status-dot.offline { background: var(--text-3); }
    
    .member-name { font-size: 17px; font-weight: 600; color: var(--text-1); margin-bottom: 4px; }
    .member-role { font-size: 13px; color: var(--accent); font-weight: 500; margin-bottom: 12px; }
    .member-email { font-size: 13px; color: var(--text-3); margin-bottom: 20px; }
    
    .card-actions {
    width: 100%;
    display: flex;
    gap: 8px;
    border-top: 1px solid var(--border);
    padding-top: 16px;
    }
    .card-btn {
    flex: 1;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: #fff;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-2);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    }
    .card-btn:hover { background: var(--bg); border-color: var(--accent); color: var(--accent); }
</style>
@endpush
