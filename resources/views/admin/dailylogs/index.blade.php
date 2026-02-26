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
    <table id="tbl-dailylogs">
        <thead><tr>
            <th>Employee</th><th>Date</th><th>Before Lunch</th><th>After Lunch</th><th>Work Link</th><th>Status</th>
        </tr></thead>
        <tbody id="dailylogs-tbody">
            <!-- Dynamic rows -->
        </tbody>
    </table>

    <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
        <div class="spinner" style="border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
        <div style="color: var(--text-2); font-size: 14px;">Loading logs...</div>
    </div>

    <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
        <div style="font-size: 24px; margin-bottom: 10px;">📁</div>
        <div style="color: var(--text-2); font-size: 14px;">No daily logs found.</div>
    </div>

    <div id="table-error" style="text-align:center; padding: 40px; display: none; color: #ff4d4d;">
        <div style="font-size: 20px; margin-bottom: 10px;">⚠️</div>
        <div id="error-message" style="font-size: 14px;">Failed to load logs.</div>
    </div>
    </div>

    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-top: 1px solid var(--border);">
        <div id="pagination-info" style="color: var(--text-2); font-size: 13px;">Showing 0 of 0 logs</div>
        <div id="pagination-controls" style="display: flex; gap: 8px;">
            <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
            <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
        </div>
    </div>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchLogs(page = 1) {
        const tbody = document.getElementById('dailylogs-tbody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        errorDiv.style.display = 'none';
        
        try {
            const response = await axios.get(`/api/admin/daily-logs?page=${page}`);
            const { data, meta } = response.data;
            const logs = data;
            const paginationMeta = meta || response.data;

            loading.style.display = 'none';

            if (!logs || logs.length === 0) {
                empty.style.display = 'block';
                return;
            }

            logs.forEach(log => {
                const tr = document.createElement('tr');
                const logDate = log.log_date ? new Date(log.log_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                
                const statusClass = log.status === 'submitted' ? 'badge-green' : (log.status === 'reviewed' ? 'badge-blue' : 'badge-orange');
                const statusText = log.status.charAt(0).toUpperCase() + log.status.slice(1);

                tr.innerHTML = `
                    <td class="td-main">${log.employee_name}</td>
                    <td>${logDate}</td>
                    <td style="max-width:200px; font-size:12.5px">${log.morning_summary || '—'}</td>
                    <td style="max-width:200px; font-size:12.5px">${log.afternoon_summary || '—'}</td>
                    <td>—</td>
                    <td><span class="badge ${statusClass}">${statusText}</span></td>
                `;
                tbody.appendChild(tr);
            });

            if (paginationMeta) {
                currentPage = paginationMeta.current_page || 1;
                info.innerText = `Showing ${paginationMeta.from || 0} to ${paginationMeta.to || 0} of ${paginationMeta.total || 0} logs`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === (paginationMeta.last_page || 1);
            }

        } catch (error) {
            console.error('Fetch error:', error);
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = error.response?.data?.message || 'Failed to load logs.';
        }
    }

    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) fetchLogs(currentPage - 1);
    });

    document.getElementById('next-page').addEventListener('click', () => {
        fetchLogs(currentPage + 1);
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchLogs();
    });
</script>
@endpush
</div>
@endsection
