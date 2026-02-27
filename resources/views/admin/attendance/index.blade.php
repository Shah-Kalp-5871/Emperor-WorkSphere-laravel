@extends('layouts.admin.master')

@section('title', 'WorkOS — Attendance')
@section('page_title', 'Attendance')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <div>
        <h1 style="font-size:20px;font-weight:700;margin:0">Employee Attendance</h1>
        <p style="font-size:13px;color:var(--text-3);margin:4px 0 0">Daily punch-in / punch-out records</p>
    </div>
    <div style="font-size:13px;color:var(--text-3)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        Last refreshed: <span id="last-refreshed">—</span>
    </div>
</div>

{{-- FILTERS --}}
<div class="panel" style="margin-bottom:20px;padding:16px 20px">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
        <input type="date" id="filter-date" class="form-control" style="width:180px"
               value="{{ date('Y-m-d') }}" title="Filter by date">

        <select id="filter-status" class="form-control" style="width:160px">
            <option value="">All Statuses</option>
            <option value="PUNCHED_IN">Punched In</option>
            <option value="PUNCHED_OUT">Punched Out</option>
        </select>

        <input type="text" id="filter-search" class="form-control" placeholder="Search employee name..."
               style="width:220px">

        <button onclick="loadAttendance()" class="btn btn-primary" style="margin-left:auto">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            Search
        </button>
        <button onclick="clearFilters()" class="btn btn-outline">Clear</button>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon green"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"/></svg></div></div>
        <div class="stat-value" id="count-in">—</div>
        <div class="stat-label">Punched In</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon blue"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M19.8 12H9"/></svg></div></div>
        <div class="stat-value" id="count-out">—</div>
        <div class="stat-label">Completed (Punched Out)</div>
    </div>
    <div class="stat-card">
        <div class="stat-header"><div class="stat-icon amber"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
        <div class="stat-value" id="count-total">—</div>
        <div class="stat-label">Total Records</div>
    </div>
</div>

{{-- TABLE --}}
<div class="panel" style="padding:0;overflow:hidden">
    <div style="overflow-x:auto">
        <table id="attendance-table" style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="background:var(--bg-2);border-bottom:1px solid var(--border)">
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">#</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Employee</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Date</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Punch In</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Punch Out</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Working Hours</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Status</th>
                    <th style="padding:12px 16px;text-align:left;font-weight:600;color:var(--text-2)">Location</th>
                </tr>
            </thead>
            <tbody id="attendance-body">
                <tr><td colspan="8" style="padding:40px;text-align:center"><div class="spinner-sm" style="margin:0 auto"></div></td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

<style>
    .spinner-sm { width: 24px; height: 24px; border: 2px solid var(--border); border-top: 2px solid var(--accent); border-radius: 50%; animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .form-control { background: var(--bg-1); border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); outline: none; }
    .form-control:focus { border-color: var(--accent); }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; border:none; transition:all .2s; }
    .btn-primary { background:var(--accent); color:white; }
    .btn-primary:hover { opacity:.9; }
    .btn-outline { background:transparent; color:var(--text-2); border:1px solid var(--border); }
    .btn-outline:hover { background:var(--bg-2); }
    #attendance-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    #attendance-table tbody tr:hover { background: var(--bg-2); }
    #attendance-table td { padding: 12px 16px; vertical-align: middle; color: var(--text-1); }
    .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
    .badge-in  { background:var(--green-lt); color:var(--green); }
    .badge-out { background:var(--blue-lt);  color:var(--accent); }
    .emp-code  { font-size:11px; color:var(--text-3); }
    .map-link  { color:var(--accent); font-size:11px; text-decoration:none; }
    .map-link:hover { text-decoration:underline; }
</style>

@push('scripts')
<script>
    async function loadAttendance() {
        const date   = document.getElementById('filter-date').value;
        const status = document.getElementById('filter-status').value;
        const search = document.getElementById('filter-search').value;

        const body = document.getElementById('attendance-body');
        body.innerHTML = '<tr><td colspan="8" style="padding:40px;text-align:center"><div class="spinner-sm" style="margin:0 auto"></div></td></tr>';

        try {
            const params = new URLSearchParams();
            if (date)   params.append('date', date);
            if (status) params.append('status', status);
            if (search) params.append('search', search);

            const res  = await axios.get('/api/admin/attendance?' + params.toString());
            const data = res.data.data;

            // Update summary
            document.getElementById('count-total').textContent = data.length;
            document.getElementById('count-in').textContent  = data.filter(r => r.status === 'PUNCHED_IN').length;
            document.getElementById('count-out').textContent = data.filter(r => r.status === 'PUNCHED_OUT').length;
            document.getElementById('last-refreshed').textContent = new Date().toLocaleTimeString();

            if (!data.length) {
                body.innerHTML = '<tr><td colspan="8" style="padding:40px;text-align:center;color:var(--text-3)">No attendance records found.</td></tr>';
                return;
            }

            body.innerHTML = data.map((r, i) => `
                <tr>
                    <td style="color:var(--text-3)">${i + 1}</td>
                    <td>
                        <div style="font-weight:500">${r.employee_name}</div>
                        <div class="emp-code">${r.employee_code}</div>
                    </td>
                    <td>${r.date}</td>
                    <td>${r.punch_in_time || '—'}</td>
                    <td>${r.punch_out_time || '—'}</td>
                    <td style="font-weight:500">${r.working_hours}</td>
                    <td>
                        <span class="badge ${r.status === 'PUNCHED_IN' ? 'badge-in' : 'badge-out'}">
                            ${r.status === 'PUNCHED_IN' ? 'In Office' : 'Completed'}
                        </span>
                    </td>
                    <td>
                        <a class="map-link" href="https://maps.google.com/?q=${r.punch_in_latitude},${r.punch_in_longitude}" target="_blank">
                            📍 Punch In Location
                        </a>
                        ${r.punch_out_latitude ? `<br><a class="map-link" href="https://maps.google.com/?q=${r.punch_out_latitude},${r.punch_out_longitude}" target="_blank">📍 Punch Out</a>` : ''}
                    </td>
                </tr>
            `).join('');

        } catch (err) {
            console.error(err);
            body.innerHTML = '<tr><td colspan="8" style="padding:40px;text-align:center;color:var(--danger)">Failed to load attendance records.</td></tr>';
        }
    }

    function clearFilters() {
        document.getElementById('filter-date').value   = '{{ date("Y-m-d") }}';
        document.getElementById('filter-status').value = '';
        document.getElementById('filter-search').value = '';
        loadAttendance();
    }

    // Auto-refresh every 60s
    document.addEventListener('DOMContentLoaded', () => {
        loadAttendance();
        setInterval(loadAttendance, 60000);
    });
</script>
@endpush
