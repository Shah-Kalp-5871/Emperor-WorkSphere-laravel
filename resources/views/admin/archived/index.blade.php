@extends('layouts.admin.master')

@section('title', 'WorkSphere — Archived')

@section('content')
<div class="page active" id="page-archived">
    <div class="section-header">
    <div>
        <div class="section-title">Archived</div>
        <div class="section-sub">Soft-deleted projects & tasks · Restorable</div>
    </div>
    </div>
    <div class="card" style="margin-bottom:16px">
    <div class="card-header"><div class="card-title">Archived Projects</div></div>
    <div class="card-body">
        <table id="tbl-archived" data-tabulator style="width:100%;border-collapse:collapse">
        <thead><tr>
            <th style="padding:10px 16px;font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid var(--border)">Project</th>
            <th style="padding:10px 16px;font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid var(--border)">Archived On</th>
            <th style="padding:10px 16px;font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid var(--border)">Archived By</th>
            <th style="padding:10px 16px;font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;text-align:left;border-bottom:1px solid var(--border)">Action</th>
        </tr></thead>
        <tbody>
            <tr>
            <td style="padding:13px 16px;font-size:13.5px;font-weight:500;opacity:.6">Old CRM Module</td>
            <td style="padding:13px 16px;font-size:13px;color:var(--text3)">Jan 12, 2026</td>
            <td style="padding:13px 16px;font-size:13px;color:var(--text3)">Admin</td>
            <td style="padding:13px 16px"><button class="btn btn-ghost btn-sm">Restore</button></td>
            </tr>
            <tr>
            <td style="padding:13px 16px;font-size:13.5px;font-weight:500;opacity:.6">Legacy Reports Tool</td>
            <td style="padding:13px 16px;font-size:13px;color:var(--text3)">Dec 5, 2025</td>
            <td style="padding:13px 16px;font-size:13px;color:var(--text3)">Admin</td>
            <td style="padding:13px 16px"><button class="btn btn-ghost btn-sm">Restore</button></td>
            </tr>
        </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
