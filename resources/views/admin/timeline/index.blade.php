@extends('layouts.admin.master')

@section('title', 'WorkSphere — Activity Timeline')

@section('content')
<div class="page active" id="page-timeline">
    <div class="section-header">
    <div>
        <div class="section-title">Activity Timeline</div>
        <div class="section-sub">System-wide activity log · Read-only</div>
    </div>
    </div>
    <div class="card">
    <div class="card-body">
        <div class="timeline">
        <div class="tl-item">
            <div class="tl-icon">✅</div>
            <div class="tl-content">
            <div class="tl-action"><span class="tl-user">Priya Sharma</span> marked task <em>"Deploy staging server"</em> as <span class="badge badge-green" style="padding:1px 7px">Done</span></div>
            <div class="tl-time">Today, 10:43 AM · Website Redesign</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">👤</div>
            <div class="tl-content">
            <div class="tl-action"><span class="tl-user">Admin</span> added <span class="tl-user">Ravi Kumar</span> to project <em>Mobile App v2</em></div>
            <div class="tl-time">Today, 10:09 AM</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">📋</div>
            <div class="tl-content">
            <div class="tl-action">Task <em>"Integrate push notifications"</em> assigned to <span class="tl-user">Ankit Mehta</span> <span class="badge badge-orange" style="padding:1px 7px">Medium</span></div>
            <div class="tl-time">Today, 9:25 AM · Mobile App v2</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">📁</div>
            <div class="tl-content">
            <div class="tl-action">Project <em>"Data Analytics Dashboard"</em> created by <span class="tl-user">Sara Joshi</span></div>
            <div class="tl-time">Yesterday, 4:58 PM</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">📋</div>
            <div class="tl-content">
            <div class="tl-action">Task <em>"Fix payment gateway"</em> created in <em>API Integration</em> <span class="badge badge-red" style="padding:1px 7px">High</span></div>
            <div class="tl-time">Yesterday, 2:15 PM</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">✅</div>
            <div class="tl-content">
            <div class="tl-action"><span class="tl-user">Sara Joshi</span> marked task <em>"Design new landing page"</em> as Done</div>
            <div class="tl-time">Feb 23, 5:44 PM · Website Redesign</div>
            </div>
        </div>
        <div class="tl-item">
            <div class="tl-icon">👤</div>
            <div class="tl-content">
            <div class="tl-action"><span class="tl-user">Admin</span> created new employee account <em>@sara_j</em></div>
            <div class="tl-time">Feb 5, 9:00 AM</div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
