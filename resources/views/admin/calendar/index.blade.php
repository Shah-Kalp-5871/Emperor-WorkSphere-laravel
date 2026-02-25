@extends('layouts.admin.master')

@section('title', 'WorkSphere — Office Calendar')

@section('content')
<div class="page active" id="page-calendar">
    <div class="section-header">
    <div>
        <div class="section-title">Office Calendar</div>
        <div class="section-sub">Manage office ON / OFF days</div>
    </div>
    <div class="section-actions">
        <button class="btn btn-primary btn-sm" onclick="openModal('mark-day-modal')">Mark Day</button>
    </div>
    </div>
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:20px">
    <div class="calendar-wrap">
        <div class="cal-header">
        <div class="cal-title">February 2026</div>
        <div class="cal-nav">
            <div class="cal-nav-btn">‹</div>
            <div class="cal-nav-btn">›</div>
        </div>
        </div>
        <div class="cal-grid">
        <div class="cal-day-label">S</div><div class="cal-day-label">M</div><div class="cal-day-label">T</div><div class="cal-day-label">W</div><div class="cal-day-label">T</div><div class="cal-day-label">F</div><div class="cal-day-label">S</div>
        <div class="cal-day"></div>
        <div class="cal-day office-on">3</div><div class="cal-day office-on">4</div><div class="cal-day office-on">5</div><div class="cal-day office-on">6</div><div class="cal-day office-on">7</div>
        <div class="cal-day office-off">8</div><div class="cal-day office-off">9</div>
        <div class="cal-day office-on">10</div><div class="cal-day office-on">11</div><div class="cal-day office-on">12</div><div class="cal-day office-on">13</div><div class="cal-day office-on">14</div>
        <div class="cal-day office-off">15</div><div class="cal-day office-off">16</div>
        <div class="cal-day office-on">17</div><div class="cal-day office-on">18</div><div class="cal-day office-on">19</div><div class="cal-day office-off" title="Holiday">20</div><div class="cal-day office-on">21</div>
        <div class="cal-day office-off">22</div><div class="cal-day office-off">23</div>
        <div class="cal-day today office-on">24</div><div class="cal-day office-on">25</div><div class="cal-day office-on">26</div><div class="cal-day office-on">27</div><div class="cal-day office-on">28</div>
        </div>
        <div class="cal-legend">
        <div class="leg-item"><div class="leg-dot" style="background:var(--accent2)"></div>Office ON</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--danger)"></div>Office OFF</div>
        <div class="leg-item"><div class="leg-dot" style="background:var(--accent)"></div>Today</div>
        </div>
    </div>
    <!-- Upcoming events -->
    <div class="card">
        <div class="card-header"><div class="card-title">February Schedule</div></div>
        <div class="card-body">
        <div style="display:flex;flex-direction:column;gap:12px">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:rgba(110,231,183,0.06);border:1px solid rgba(110,231,183,0.15);border-radius:8px">
            <div><div style="font-size:13px;font-weight:500">Mon-Fri this week</div><div style="font-size:11px;color:var(--text3)">Feb 24–28</div></div>
            <span class="badge badge-green">Office ON</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:rgba(244,63,94,0.06);border:1px solid rgba(244,63,94,0.15);border-radius:8px">
            <div><div style="font-size:13px;font-weight:500">Weekend</div><div style="font-size:11px;color:var(--text3)">Feb 22–23</div></div>
            <span class="badge badge-red">Office OFF</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 12px;background:rgba(244,63,94,0.06);border:1px solid rgba(244,63,94,0.15);border-radius:8px">
            <div><div style="font-size:13px;font-weight:500">Public Holiday</div><div style="font-size:11px;color:var(--text3)">Feb 20 · Maha Shivratri</div></div>
            <span class="badge badge-red">Office OFF</span>
            </div>
        </div>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
            <div style="font-size:12px;color:var(--text3);margin-bottom:8px">8 PM Reminder Status</div>
            <div style="font-size:13px;color:var(--accent2)">✓ Active — Will send reminders on Office ON days</div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
