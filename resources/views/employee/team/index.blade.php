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
        <div class="stat-badge up" style="padding: 8px 14px;">12 Online</div>
      </div>
    </div>

    <div class="team-grid">
      <!-- Member 1 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot online"></div>
          <span style="color: #22c55e;">Online</span>
        </div>
        <div class="team-avatar-lg">PS</div>
        <h3 class="member-name">Priya Sharma</h3>
        <p class="member-role">Product Designer</p>
        <p class="member-email">priya.sharma@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>

      <!-- Member 2 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot online"></div>
          <span style="color: #22c55e;">Online</span>
        </div>
        <div class="team-avatar-lg" style="background:var(--blue-lt); color:var(--blue);">RK</div>
        <h3 class="member-name">Ravi Kumar</h3>
        <p class="member-role">Full Stack Developer</p>
        <p class="member-email">ravi.kumar@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>

      <!-- Member 3 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot offline"></div>
          <span style="color: var(--text-3);">Offline</span>
        </div>
        <div class="team-avatar-lg" style="background:var(--amber-lt); color:var(--amber);">AM</div>
        <h3 class="member-name">Ankit Mehta</h3>
        <p class="member-role">DevOps Engineer</p>
        <p class="member-email">ankit.mehta@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>

      <!-- Member 4 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot online"></div>
          <span style="color: #22c55e;">Online</span>
        </div>
        <div class="team-avatar-lg" style="background:var(--red-lt); color:var(--red);">SJ</div>
        <h3 class="member-name">Sara Joshi</h3>
        <p class="member-role">QA Specialist</p>
        <p class="member-email">sara.joshi@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>

      <!-- Member 5 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot offline"></div>
          <span style="color: var(--text-3);">Offline</span>
        </div>
        <div class="team-avatar-lg">MK</div>
        <h3 class="member-name">Manoj Khan</h3>
        <p class="member-role">Frontend Intern</p>
        <p class="member-email">manoj.khan@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>

      <!-- Member 6 -->
      <div class="team-card">
        <div class="status-indicator">
          <div class="status-dot online"></div>
          <span style="color: #22c55e;">Online</span>
        </div>
        <div class="team-avatar-lg" style="background:var(--blue-lt); color:var(--blue);">KS</div>
        <h3 class="member-name">Kalp Shah</h3>
        <p class="member-role">Full Stack Intern</p>
        <p class="member-email">kalp.shah@worksphere.com</p>
        <div class="card-actions">
          <button class="card-btn">View Profile</button>
          <button class="card-btn">Message</button>
        </div>
      </div>
    </div>
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
