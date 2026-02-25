@extends('layouts.admin.master')

@section('title', 'WorkSphere — Tasks')

@section('content')
<div class="page active" id="page-tasks">
    <div class="section-header">
    <div>
        <div class="section-title">All Tasks</div>
        <div class="section-sub">29 total · 3 overdue</div>
    </div>
    <div class="section-actions">
        <button class="btn btn-primary" onclick="openModal('create-task-modal')">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Task
        </button>
    </div>
    </div>
    <div class="filter-bar">
    <input class="filter-input" placeholder="Search tasks…">
    <select class="filter-select"><option>All Projects</option><option>Website Redesign</option><option>Mobile App v2</option></select>
    <select class="filter-select"><option>All Status</option><option>Pending</option><option>Done</option></select>
    <select class="filter-select"><option>All Priority</option><option>High</option><option>Medium</option><option>Low</option></select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-tasks" data-tabulator>
        <thead><tr>
            <th>Task</th><th>Project</th><th>Assigned To</th><th>Priority</th><th>Status</th><th>Due Date</th>
        </tr></thead>
        <tbody>
            <tr>
            <td class="td-main">Fix payment gateway</td>
            <td>API Integration</td>
            <td>Ankit M.</td>
            <td><span class="badge badge-red">High</span></td>
            <td><span class="badge badge-muted">Pending</span></td>
            <td><span class="overdue">Feb 20 ⚠</span></td>
            </tr>
            <tr>
            <td class="td-main">Deploy staging server</td>
            <td>Mobile App v2</td>
            <td>Priya S.</td>
            <td><span class="badge badge-red">High</span></td>
            <td><span class="badge badge-green">Done</span></td>
            <td>Feb 22</td>
            </tr>
            <tr>
            <td class="td-main">Update user documentation</td>
            <td>Website Redesign</td>
            <td>Priya S.</td>
            <td><span class="badge badge-orange">Medium</span></td>
            <td><span class="badge badge-muted">Pending</span></td>
            <td><span class="overdue">Feb 22 ⚠</span></td>
            </tr>
            <tr>
            <td class="td-main">Design new landing page</td>
            <td>Website Redesign</td>
            <td>Sara J.</td>
            <td><span class="badge badge-orange">Medium</span></td>
            <td><span class="badge badge-green">Done</span></td>
            <td>Feb 21</td>
            </tr>
            <tr>
            <td class="td-main">Write unit tests</td>
            <td>Mobile App v2</td>
            <td>Ravi K.</td>
            <td><span class="badge badge-blue">Low</span></td>
            <td><span class="badge badge-muted">Pending</span></td>
            <td><span class="overdue">Feb 23 ⚠</span></td>
            </tr>
            <tr>
            <td class="td-main">Integrate push notifications</td>
            <td>Mobile App v2</td>
            <td>Ankit M.</td>
            <td><span class="badge badge-orange">Medium</span></td>
            <td><span class="badge badge-muted">Pending</span></td>
            <td>Feb 28</td>
            </tr>
        </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
