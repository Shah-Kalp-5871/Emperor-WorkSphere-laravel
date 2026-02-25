@extends('layouts.admin.master')

@section('title', 'WorkSphere — Daily Logs')

@section('content')
<div class="page active" id="page-dailylogs">
    <div class="section-header">
    <div>
        <div class="section-title">Daily Work Logs</div>
        <div class="section-sub">Read-only · All employees</div>
    </div>
    </div>
    <div class="filter-bar">
    <input class="filter-input" placeholder="Search employee…">
    <input type="date" class="filter-input" value="{{ date('Y-m-d') }}" style="max-width:180px">
    <select class="filter-select"><option>All Status</option><option>Submitted</option><option>Missing</option></select>
    </div>
    <div class="card">
    <div class="table-wrap">
    <table id="tbl-dailylogs" data-tabulator>
        <thead><tr>
            <th>Employee</th><th>Date</th><th>Before Lunch</th><th>After Lunch</th><th>Work Link</th><th>Status</th>
        </tr></thead>
        <tbody>
            <tr>
            <td class="td-main">Priya Sharma</td>
            <td>Feb 24, 2026</td>
            <td style="max-width:200px;font-size:12.5px">Worked on homepage redesign wireframes and reviewed Figma mockups</td>
            <td style="max-width:200px;font-size:12.5px">Implemented header component in React, reviewed PR #42</td>
            <td><a href="#" style="color:var(--accent);font-size:12px">figma.com/…</a></td>
            <td><span class="badge badge-green">Submitted</span></td>
            </tr>
            <tr>
            <td class="td-main">Ravi Kumar</td>
            <td>Feb 24, 2026</td>
            <td style="max-width:200px;font-size:12.5px">Set up test environment for mobile app</td>
            <td style="max-width:200px;font-size:12.5px">Wrote 14 unit tests for auth module</td>
            <td><a href="#" style="color:var(--accent);font-size:12px">github.com/…</a></td>
            <td><span class="badge badge-green">Submitted</span></td>
            </tr>
            <tr>
            <td class="td-main">Ankit Mehta</td>
            <td>Feb 24, 2026</td>
            <td style="max-width:200px;font-size:12.5px;color:var(--text3)">—</td>
            <td style="max-width:200px;font-size:12.5px;color:var(--text3)">—</td>
            <td>—</td>
            <td><span class="badge badge-red">Missing</span></td>
            </tr>
            <tr>
            <td class="td-main">Sara Joshi</td>
            <td>Feb 24, 2026</td>
            <td style="max-width:200px;font-size:12.5px">Meeting with client, documented requirements</td>
            <td style="max-width:200px;font-size:12.5px">Started UI mockups for analytics dashboard</td>
            <td><a href="#" style="color:var(--accent);font-size:12px">notion.so/…</a></td>
            <td><span class="badge badge-green">Submitted</span></td>
            </tr>
        </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
