@extends('layouts.employee.master')

@section('title', 'WorkSphere — Office Calendar')
@section('page_title', 'Office Calendar')

@section('content')
<div class="calendar-layout">
    
    <!-- CALENDAR MAIN -->
    <div class="panel calendar-panel">
    <div class="panel-header">
        <div class="panel-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        February 2026
        </div>
        <div class="calendar-nav-btns">
        <button class="nav-btn-sm">‹</button>
        <button class="nav-btn-sm active">Today</button>
        <button class="nav-btn-sm">›</button>
        </div>
    </div>
    
    <div class="calendar-grid">
        <div class="day-header">Sun</div>
        <div class="day-header">Mon</div>
        <div class="day-header">Tue</div>
        <div class="day-header">Wed</div>
        <div class="day-header">Thu</div>
        <div class="day-header">Fri</div>
        <div class="day-header">Sat</div>
        
        @php
        $startOffset = 0; // Feb 1 2026 is Sunday
        $daysInMonth = 28;
        $today = 24;
        @endphp

        {{-- Empty days at start --}}
        @for ($i = 0; $i < $startOffset; $i++)
            <div class="day-cell empty"></div>
        @endfor
        
        @for ($d = 1; $d <= $daysInMonth; $d++)
            <div class="day-cell {{ $d == $today ? 'today' : '' }}">
                <span class="day-num">{{ $d }}</span>
                
                {{-- Event markers --}}
                @if ($d == 10)
                    <div class="event-tag blue">Sprint Planning</div>
                @endif
                @if ($d == 14)
                    <div class="event-tag red">Holiday</div>
                @endif
                @if ($d == 24)
                    <div class="event-tag green">Client Demo</div>
                    <div class="event-tag amber">Daily Standup</div>
                @endif
                @if ($d == 27)
                    <div class="event-tag red">Project Deadline</div>
                @endif
            </div>
        @endfor
    </div>
    </div>
    
    <!-- CALENDAR SIDEBAR -->
    <div class="calendar-sidebar">
    <div class="panel">
        <div class="panel-header">
        <div class="panel-title">Events for Today</div>
        </div>
        <div class="event-list">
        <div class="event-item">
            <div class="event-time">09:30 AM</div>
            <div class="event-info">
            <div class="event-name">Daily Standup</div>
            <div class="event-desc">Internal sync with the dev team</div>
            </div>
        </div>
        <div class="event-item">
            <div class="event-time">02:00 PM</div>
            <div class="event-info">
            <div class="event-name">Client Demo</div>
            <div class="event-desc">Showcasing the new Website Redesign progress</div>
            </div>
        </div>
        </div>
    </div>
    
    <div class="panel" style="margin-top: 20px;">
        <div class="panel-header">
        <div class="panel-title">Upcoming Holidays</div>
        </div>
        <div class="holiday-item">
        <div class="holiday-date">Mar 10</div>
        <div class="holiday-name">Holi (Festival of Colors)</div>
        </div>
        <div class="holiday-item">
        <div class="holiday-date">Mar 28</div>
        <div class="holiday-name">Ram Navami</div>
        </div>
    </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .calendar-layout { display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start; }
    @media (max-width: 1100px) { .calendar-layout { grid-template-columns: 1fr; } }
    
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); background: var(--border); gap: 1px; border: 1px solid var(--border); }
    .day-header { background: var(--surface-2); padding: 12px; text-align: center; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-3); letter-spacing: 0.05em; }
    .day-cell { background: var(--surface); min-height: 120px; padding: 10px; position: relative; transition: background 0.2s; cursor: pointer; }
    .day-cell:hover { background: var(--surface-2); }
    .day-cell.today { background: var(--accent-lt); }
    .day-cell.today .day-num { background: var(--accent); color: #fff; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin-top: -4px; margin-left: -4px; }
    .day-num { font-size: 13px; font-weight: 500; color: var(--text-2); display: block; margin-bottom: 8px; }
    
    .event-tag { font-size: 10px; padding: 3px 6px; border-radius: 4px; margin-bottom: 4px; border-left: 3px solid transparent; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .event-tag.blue { background: var(--blue-lt); color: var(--blue); border-left-color: var(--blue); }
    .event-tag.red { background: var(--red-lt); color: var(--red); border-left-color: var(--red); }
    .event-tag.green { background: var(--accent-lt); color: var(--accent); border-left-color: var(--accent); }
    .event-tag.amber { background: var(--amber-lt); color: var(--amber); border-left-color: var(--amber); }

    .calendar-nav-btns { display: flex; gap: 4px; }
    .nav-btn-sm { padding: 5px 10px; border: 1px solid var(--border); background: #fff; border-radius: 6px; font-size: 12px; cursor: pointer; color: var(--text-2); }
    .nav-btn-sm.active { background: var(--accent); color: #fff; border-color: var(--accent); }
    
    .event-list { padding: 8px 0; }
    .event-item { display: flex; gap: 15px; padding: 12px 20px; border-bottom: 1px solid var(--border); transition: background 0.2s; }
    .event-item:last-child { border-bottom: none; }
    .event-item:hover { background: var(--surface-2); }
    .event-time { font-size: 11px; font-weight: 600; color: var(--accent); width: 60px; flex-shrink: 0; }
    .event-name { font-size: 13.5px; font-weight: 500; color: var(--text-1); margin-bottom: 2px; }
    .event-desc { font-size: 11.5px; color: var(--text-3); }
    
    .holiday-item { padding: 12px 20px; border-bottom: 1px solid var(--border); display: flex; gap: 12px; align-items: center; }
    .holiday-item:last-child { border-bottom: none; }
    .holiday-date { font-size: 12px; font-weight: 600; color: var(--red); background: var(--red-lt); padding: 4px 8px; border-radius: 6px; min-width: 55px; text-align: center; }
    .holiday-name { font-size: 13px; color: var(--text-2); font-weight: 500; }
</style>
@endpush
