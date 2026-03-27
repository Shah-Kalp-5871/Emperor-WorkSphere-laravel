@extends('layouts.admin.master')

@section('title', 'Support Tickets — Admin Panel')

@section('content')
<div style="animation: fadeUp 0.4s ease-out;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">Support Tickets</h1>
            <p style="color: var(--text-3); font-size: 14px; margin-top: 4px;">Monitor and manage employee support queries.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px;">
        <div class="stat-card"><div class="stat-header"><div class="stat-icon blue"><span>#</span></div><span class="stat-badge info">Total</span></div><div class="stat-value" id="stat-total">—</div><div class="stat-label">All Tickets</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon amber"><span>!</span></div><span class="stat-badge warn">Open</span></div><div class="stat-value" id="stat-open">—</div><div class="stat-label">Awaiting Action</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon blue"><span>↻</span></div><span class="stat-badge info">Progress</span></div><div class="stat-value" id="stat-progress">—</div><div class="stat-label">In Progress</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon green"><span>✓</span></div><span class="stat-badge up">Done</span></div><div class="stat-value" id="stat-resolved">—</div><div class="stat-label">Resolved / Closed</div></div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                All Employee Tickets
                <span id="tickets-count" class="count">0</span>
            </div>
            <select id="statusFilter" onchange="loadTickets()" style="padding:6px 10px; border:1px solid var(--border); border-radius:var(--radius-sm); background:var(--surface); font-size:12px;">
                <option value="">All Statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div id="loading-state" style="text-align:center; padding:40px;">
            <div style="width:28px;height:28px;border:3px solid var(--border);border-top:3px solid var(--accent);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto;"></div>
            <div style="color:var(--text-3);font-size:13px;margin-top:12px;">Loading tickets...</div>
        </div>

        <div id="empty-state" style="text-align:center; padding:50px; display:none;">
            <div style="font-size:32px;margin-bottom:12px;">🎫</div>
            <div style="font-weight:600;margin-bottom:6px;">No tickets yet</div>
            <div style="color:var(--text-3);font-size:13px;">Employees haven't raised any tickets yet.</div>
        </div>

        <div id="error-state" style="text-align:center; padding:40px; display:none;">
            <div style="font-size:24px;margin-bottom:10px;">⚠️</div>
            <div style="color:var(--red);font-size:13px;" id="error-msg">Failed to load tickets.</div>
            <button onclick="loadTickets()" style="margin-top:12px;padding:8px 16px;border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:12px;">Retry</button>
        </div>

        <div id="table-wrapper" style="display:none; overflow:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Ticket ID</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Subject</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Employee</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Category</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Status</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;">Date Raised</th>
                        <th style="text-align:right;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Action</th>
                    </tr>
                </thead>
                <tbody id="tickets-tbody"></tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;margin-top:10px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);">
        <div id="pagination-info" style="color:var(--text-3);font-size:13px;">Showing 0 tickets</div>
        <div style="display:flex;gap:8px;">
            <button id="prev-btn" onclick="changePage(-1)" disabled style="padding:6px 14px;border:1px solid var(--border);border-radius:6px;background:var(--surface);cursor:pointer;font-size:12px;">Previous</button>
            <button id="next-btn" onclick="changePage(1)"  disabled style="padding:6px 14px;border:1px solid var(--border);border-radius:6px;background:var(--surface);cursor:pointer;font-size:12px;">Next</button>
        </div>
    </div>
</div>

{{-- Detail Modal --}}
<div id="ticket-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:var(--surface);border-radius:var(--radius);width:90%;max-width:640px;max-height:88vh;overflow-y:auto;box-shadow:var(--shadow-md);">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:var(--surface);z-index:1;">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;">Ticket Details</h3>
            <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;padding:4px;color:var(--text-3);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="modal-body" style="padding:24px;">Loading...</div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .s-pill { display:inline-block; font-size:10px; font-weight:700; padding:2px 8px; border-radius:4px; text-transform:uppercase; }
    .s-open { background:#fef3c7; color:#d97706; }
    .s-in_progress { background:#dbeafe; color:#2563eb; }
    .s-resolved { background:#d1fae5; color:#059669; }
    .s-closed { background:#f3f4f6; color:#6b7280; }
    .t-row { border-bottom:1px solid var(--border); transition:background .12s; }
    .t-row:last-child { border-bottom:none; }
    .t-row:hover { background:var(--surface-2); }
    .t-row td { padding:14px 20px; font-size:13px; vertical-align:middle; }
</style>

@push('scripts')
<script>
let currentPage = 1;
let lastPage = 1;

async function loadStats() {
    try {
        const r = await axios.get(window.APP_URL + '/api/admin/support-tickets/stats');
        const d = r.data.data;
        document.getElementById('stat-total').textContent    = d.total    ?? 0;
        document.getElementById('stat-open').textContent     = d.open     ?? 0;
        document.getElementById('stat-progress').textContent = d.in_progress ?? 0;
        document.getElementById('stat-resolved').textContent = (d.resolved ?? 0) + (d.closed ?? 0);
    } catch(e) { console.error('Admin stats error', e); }
}

async function loadTickets(page) {
    page = page || currentPage;
    const status = document.getElementById('statusFilter').value;

    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('table-wrapper').style.display = 'none';
    document.getElementById('empty-state').style.display   = 'none';
    document.getElementById('error-state').style.display   = 'none';

    try {
        const r = await axios.get(window.APP_URL + '/api/admin/support-tickets', { params: { page, status } });
        const result  = r.data;
        // Laravel resource collection wraps in { data: [...], links: {}, meta: {} }
        const tickets = result.data || [];
        const meta    = result.meta || null;

        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('tickets-count').textContent = meta ? meta.total : tickets.length;

        if (tickets.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
            return;
        }

        const tbody = document.getElementById('tickets-tbody');
        tbody.innerHTML = '';

        tickets.forEach(t => {
            const date    = t.created_at ? new Date(t.created_at).toLocaleDateString('en-IN') : '—';
            const num     = t.ticket_number || '---';
            const subject = t.subject    || 'No Subject';
            const desc    = t.description || '';
            const cat     = (t.category  || 'other').toUpperCase();
            const empName = t.employee_name || 'Unknown';
            const empRole = t.employee_designation || '';
            const status  = t.status || 'open';

            const row = document.createElement('tr');
            row.className = 't-row';
            row.innerHTML = `
                <td style="font-weight:600;color:var(--accent);white-space:nowrap;">#${num}</td>
                <td>
                    <div style="font-weight:500;">${subject}</div>
                    <div style="font-size:11px;color:var(--text-3);margin-top:2px;">${desc.slice(0,55)}${desc.length > 55 ? '…' : ''}</div>
                </td>
                <td>
                    <div style="font-weight:500;">${empName}</div>
                    <div style="font-size:11px;color:var(--text-3);">${empRole}</div>
                </td>
                <td><span style="font-size:11px;padding:2px 7px;border-radius:4px;background:var(--bg-2);border:1px solid var(--border);font-weight:500;">${cat}</span></td>
                <td><span class="s-pill s-${status}">${status.replace('_',' ').toUpperCase()}</span></td>
                <td style="color:var(--text-3);white-space:nowrap;">${date}</td>
                <td style="text-align:right;">
                    <button onclick="openTicket(${t.id})" style="background:var(--accent);color:#fff;border:none;padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;">Manage</button>
                </td>`;
            tbody.appendChild(row);
        });

        document.getElementById('table-wrapper').style.display = 'block';

        if (meta) {
            currentPage = meta.current_page;
            lastPage    = meta.last_page;
            document.getElementById('pagination-info').textContent =
                `Showing ${meta.from || 0}–${meta.to || 0} of ${meta.total || 0} tickets`;
            document.getElementById('prev-btn').disabled = currentPage <= 1;
            document.getElementById('next-btn').disabled = currentPage >= lastPage;
        }

    } catch(e) {
        console.error('Admin tickets error', e);
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('error-state').style.display   = 'block';
        document.getElementById('error-msg').textContent = 'Error: ' + (e.response?.data?.message || e.message);
    }
}

function changePage(dir) {
    const next = currentPage + dir;
    if (next < 1 || next > lastPage) return;
    currentPage = next;
    loadTickets(currentPage);
}

async function openTicket(id) {
    const modal = document.getElementById('ticket-modal');
    const body  = document.getElementById('modal-body');
    modal.style.display = 'flex';
    body.innerHTML = '<div style="text-align:center;padding:30px;"><div style="width:24px;height:24px;border:2px solid var(--border);border-top:2px solid var(--accent);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto;"></div></div>';

    try {
        const r  = await axios.get(`${window.APP_URL}/api/admin/support-tickets/${id}`);
        const t  = r.data.data;

        const replies = t.replies || [];
        let convoHtml = '';
        if (replies.length > 0) {
            convoHtml = '<div style="font-size:12px;font-weight:600;color:var(--text-3);margin:16px 0 10px;text-transform:uppercase;letter-spacing:.05em;">Conversation</div>';
            replies.forEach(r => {
                const align = r.is_admin ? 'flex-end' : 'flex-start';
                const bg    = r.is_admin ? 'var(--blue-lt)' : 'var(--bg-1)';
                convoHtml += `
                    <div style="margin-bottom:10px;display:flex;justify-content:${align};">
                        <div style="max-width:82%;background:${bg};border:1px solid var(--border);border-radius:10px;padding:10px 14px;">
                            <div style="font-size:10px;font-weight:700;color:var(--text-3);margin-bottom:3px;">${r.sender_name || 'Unknown'}</div>
                            <div style="font-size:13px;">${r.message}</div>
                        </div>
                    </div>`;
            });
        }

        const date = t.created_at ? new Date(t.created_at).toLocaleString('en-IN') : '—';
        const status = t.status || 'open';

        body.innerHTML = `
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
                <span class="s-pill s-${status}">${status.replace('_',' ').toUpperCase()}</span>
                <span style="font-size:11px;color:var(--text-3);">Raised on ${date} &nbsp;•&nbsp; #${t.ticket_number}</span>
                <span style="font-size:11px;color:var(--text-3);">by ${t.employee_name || 'Unknown'}</span>
            </div>
            <h4 style="font-size:17px;font-weight:700;margin-bottom:12px;">${t.subject || '—'}</h4>
            <div style="background:var(--bg-1);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:4px;">
                <p style="color:var(--text-2);font-size:13px;line-height:1.6;margin:0;">${t.description || ''}</p>
            </div>

            ${convoHtml}

            <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <span style="font-size:13px;font-weight:700;">Update Status</span>
                    <select id="status-select" onchange="onStatusChange()" style="padding:6px 10px;border:1px solid var(--border);border-radius:6px;background:var(--bg-1);font-size:12px;">
                        <option value="open"        ${status==='open'        ? 'selected':''}>Open</option>
                        <option value="in_progress" ${status==='in_progress' ? 'selected':''}>In Progress</option>
                        <option value="resolved"    ${status==='resolved'    ? 'selected':''}>Resolved</option>
                        <option value="closed"      ${status==='closed'      ? 'selected':''}>Closed</option>
                    </select>
                </div>

                <label id="reply-label" style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Reply / Response</label>
                <textarea id="reply-input" rows="3" placeholder="Type your response here..." style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:13px;font-family:inherit;background:var(--bg-1);resize:vertical;outline:none;"></textarea>

                <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                    <input type="checkbox" id="is-internal">
                    <label for="is-internal" style="font-size:12px;color:var(--text-3);cursor:pointer;">Internal Private Note</label>
                </div>

                <div style="display:flex;gap:10px;margin-top:16px;">
                    <button id="update-btn" onclick="updateTicket(${t.id})" class="btn btn-primary" style="flex:1;">Update Ticket</button>
                    ${status === 'open' ? `<button onclick="quickStatus(${t.id}, 'in_progress')" style="padding:10px 16px;border:1px solid #bfdbfe;border-radius:6px;background:var(--blue-lt);color:#2563eb;cursor:pointer;font-size:12px;font-weight:500;">Mark In Progress</button>` : ''}
                    <button onclick="closeModal()" style="padding:10px 16px;border:1px solid var(--border);border-radius:6px;background:var(--surface-2);cursor:pointer;font-size:12px;">Close</button>
                </div>
            </div>`;

    } catch(e) {
        body.innerHTML = `<div style="color:var(--red);padding:20px;text-align:center;">Failed to load ticket: ${e.response?.data?.message || e.message}</div>`;
    }
}

function onStatusChange() {
    const s = document.getElementById('status-select').value;
    const lbl = document.getElementById('reply-label');
    if (s === 'resolved') {
        lbl.textContent = '✅ Final Resolution Note';
        lbl.style.color = '#059669';
    } else {
        lbl.textContent = 'Reply / Response';
        lbl.style.color = '';
    }
}

async function updateTicket(id) {
    const status     = document.getElementById('status-select').value;
    const message    = (document.getElementById('reply-input').value || '').trim();
    const isInternal = document.getElementById('is-internal').checked;
    const btn        = document.getElementById('update-btn');

    btn.disabled = true; btn.textContent = 'Updating…';

    try {
        if (message) {
            await axios.post(`${window.APP_URL}/api/admin/support-tickets/${id}/reply`, { message, is_internal: isInternal });
        }
        await axios.put(`${window.APP_URL}/api/admin/support-tickets/${id}`, { status });
        closeModal();
        loadStats();
        loadTickets(currentPage);
    } catch(e) {
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: 'Failed to update ticket: ' + (e.response?.data?.message || e.message)
        });
        btn.disabled = false; btn.textContent = 'Update Ticket';
    }
}

async function quickStatus(id, status) {
    try {
        await axios.put(`${window.APP_URL}/api/admin/support-tickets/${id}`, { status });
        openTicket(id);
        loadStats();
        loadTickets(currentPage);
    } catch(e) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed: ' + (e.response?.data?.message || e.message)
        });
    }
}

function closeModal() {
    document.getElementById('ticket-modal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadTickets(1);
});
</script>
@endpush
@endsection
