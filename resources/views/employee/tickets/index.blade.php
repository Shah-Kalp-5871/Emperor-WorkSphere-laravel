@extends('layouts.employee.master')

@section('title', 'My Support Tickets — WorkSphere')

@section('content')
<div style="animation: fadeUp 0.4s ease-out;">

    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-family: 'Instrument Serif', serif; font-size: 32px; font-weight: 400;">Support Tickets</h1>
            <p style="color: var(--text-3); font-size: 14px; margin-top: 4px;">Track and manage your queries and issues.</p>
        </div>
        <button class="greeting-btn" onclick="window.location.href='{{ route('employee.tickets.create') }}'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Raise New Ticket
        </button>
    </div>

    {{-- Stats --}}
    <div id="stats-row" style="display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px;">
        <div class="stat-card"><div class="stat-header"><div class="stat-icon blue"><span>#</span></div><span class="stat-badge info">Total</span></div><div class="stat-value" id="stat-total">—</div><div class="stat-label">All Tickets</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon amber"><span>!</span></div><span class="stat-badge warn">Open</span></div><div class="stat-value" id="stat-open">—</div><div class="stat-label">Awaiting Response</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon blue"><span>↻</span></div><span class="stat-badge info">Progress</span></div><div class="stat-value" id="stat-progress">—</div><div class="stat-label">In Progress</div></div>
        <div class="stat-card"><div class="stat-header"><div class="stat-icon green"><span>✓</span></div><span class="stat-badge up">Done</span></div><div class="stat-value" id="stat-resolved">—</div><div class="stat-label">Resolved</div></div>
    </div>

    {{-- Table --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                My Tickets
                <span class="count" id="tickets-count">0</span>
            </div>
            <select id="statusFilter" onchange="loadTickets()" style="padding:6px 10px; border:1px solid var(--border); border-radius:var(--radius-sm); background:var(--surface); font-size:12px;">
                <option value="">All Tickets</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <div id="loading-state" style="text-align:center; padding:40px;">
            <div style="width:24px;height:24px;border:2px solid var(--border);border-top:2px solid var(--accent);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto;"></div>
            <div style="color:var(--text-3);font-size:13px;margin-top:10px;">Loading tickets...</div>
        </div>

        <div id="empty-state" style="text-align:center; padding:50px; display:none;">
            <div style="font-size:32px;margin-bottom:12px;">🎫</div>
            <div style="font-weight:600;margin-bottom:6px;">No tickets yet</div>
            <div style="color:var(--text-3);font-size:13px;margin-bottom:16px;">Raise a ticket to get help from the admin team.</div>
            <button class="greeting-btn" onclick="window.location.href='{{ route('employee.tickets.create') }}'">Raise a Ticket</button>
        </div>

        <div id="error-state" style="text-align:center; padding:40px; display:none;">
            <div style="color:var(--red);font-size:13px;" id="error-msg">Failed to load tickets.</div>
            <button class="greeting-btn" onclick="loadTickets()" style="margin-top:12px; font-size:12px;">Retry</button>
        </div>

        <div id="table-wrapper" style="display:none; overflow:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Ticket ID</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Subject</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Category</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Status</th>
                        <th style="text-align:left;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Date</th>
                        <th style="text-align:right;padding:12px 20px;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:.04em;">Action</th>
                    </tr>
                </thead>
                <tbody id="tickets-tbody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="ticket-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:var(--surface);border-radius:var(--radius);width:90%;max-width:600px;max-height:85vh;overflow-y:auto;box-shadow:var(--shadow-md);">
        <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="font-family:'Instrument Serif',serif;font-size:20px;">Ticket Details</h3>
            <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;padding:4px;color:var(--text-3);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="modal-body" style="padding:24px;">Loading...</div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .status-pill { display:inline-block; font-size:10px; font-weight:700; padding:2px 8px; border-radius:4px; text-transform:uppercase; }
    .s-open { background:#fef3c7; color:#d97706; }
    .s-in_progress { background:#dbeafe; color:#2563eb; }
    .s-resolved { background:#d1fae5; color:#059669; }
    .s-closed { background:#f3f4f6; color:#6b7280; }
    .ticket-row { border-bottom:1px solid var(--border); transition:background .12s; }
    .ticket-row:last-child { border-bottom:none; }
    .ticket-row:hover { background:var(--surface-2); }
    .ticket-row td { padding:14px 20px; font-size:13px; vertical-align:middle; }
</style>

@push('scripts')
<script>
let currentTicketId = null;

async function loadStats() {
    try {
        const r = await axios.get(window.APP_URL + '/api/employee/tickets/stats');
        const d = r.data.data;
        document.getElementById('stat-total').textContent    = d.total    ?? 0;
        document.getElementById('stat-open').textContent     = d.open     ?? 0;
        document.getElementById('stat-progress').textContent = d.in_progress ?? 0;
        document.getElementById('stat-resolved').textContent = d.resolved  ?? 0;
    } catch(e) {
        console.error('Stats error', e);
    }
}

async function loadTickets() {
    const status = document.getElementById('statusFilter').value;
    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('table-wrapper').style.display = 'none';
    document.getElementById('empty-state').style.display = 'none';
    document.getElementById('error-state').style.display = 'none';

    try {
        const r  = await axios.get(window.APP_URL + '/api/employee/tickets', { params: { status } });
        const tickets = r.data.data || [];

        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('tickets-count').textContent = tickets.length;

        if (tickets.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
            return;
        }

        const tbody = document.getElementById('tickets-tbody');
        tbody.innerHTML = '';

        tickets.forEach(t => {
            const date = t.created_at ? new Date(t.created_at).toLocaleDateString('en-IN') : '—';
            const row = document.createElement('tr');
            row.className = 'ticket-row';
            row.innerHTML = `
                <td style="font-weight:600;color:var(--accent);">#${t.ticket_number}</td>
                <td>
                    <div style="font-weight:500;">${t.subject}</div>
                    <div style="font-size:11px;color:var(--text-3);margin-top:2px;">${(t.description||'').slice(0,60)}${(t.description||'').length > 60 ? '…' : ''}</div>
                </td>
                <td><span style="font-size:11px;padding:2px 7px;border-radius:4px;background:var(--bg-2);border:1px solid var(--border);font-weight:500;">${(t.category||'other').toUpperCase()}</span></td>
                <td><span class="status-pill s-${t.status}">${t.status.replace('_',' ').toUpperCase()}</span></td>
                <td style="color:var(--text-3);">${date}</td>
                <td style="text-align:right;">
                    <button onclick="openTicket(${t.id})" style="background:var(--accent-lt);color:var(--accent);border:1px solid var(--accent-lt);padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;">View</button>
                </td>`;
            tbody.appendChild(row);
        });

        document.getElementById('table-wrapper').style.display = 'block';

    } catch(e) {
        console.error('Load tickets error', e);
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('error-state').style.display = 'block';
        document.getElementById('error-msg').textContent = 'Error: ' + (e.response?.data?.message || e.message);
    }
}

async function openTicket(id) {
    currentTicketId = id;
    const modal = document.getElementById('ticket-modal');
    const body  = document.getElementById('modal-body');
    modal.style.display = 'flex';
    body.innerHTML = '<div style="text-align:center;padding:30px;"><div style="width:24px;height:24px;border:2px solid var(--border);border-top:2px solid var(--accent);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto;"></div></div>';

    try {
        const r = await axios.get(`${window.APP_URL}/api/employee/tickets/${id}`);
        const t = r.data.data;

        // Build resolution banner for resolved tickets
        let resolutionHtml = '';
        if (t.status === 'resolved' || t.status === 'closed') {
            const adminReplies = (t.replies || []).filter(r => r.is_admin);
            const lastAdminReply = adminReplies[adminReplies.length - 1];
            if (lastAdminReply) {
                resolutionHtml = `
                    <div style="background:#ecfdf5;border:1px solid #10b981;border-radius:8px;padding:16px;margin-bottom:16px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                            <div style="background:#10b981;color:#fff;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;">✓</div>
                            <span style="font-weight:700;font-size:13px;color:#065f46;">Final Resolution</span>
                        </div>
                        <p style="color:#065f46;font-size:13px;line-height:1.5;margin:0;">${lastAdminReply.message}</p>
                    </div>`;
            }
        }

        // Build conversation
        let convoHtml = '';
        if (t.replies && t.replies.length > 0) {
            convoHtml = '<div style="font-size:12px;font-weight:600;color:var(--text-3);margin:16px 0 10px;text-transform:uppercase;letter-spacing:.05em;">Conversation</div>';
            t.replies.forEach(r => {
                const align = r.is_admin ? 'left' : 'right';
                const bg    = r.is_admin ? 'var(--bg-1)' : 'var(--blue-lt)';
                convoHtml += `
                    <div style="margin-bottom:10px;display:flex;justify-content:${align === 'right' ? 'flex-end' : 'flex-start'};">
                        <div style="max-width:80%;background:${bg};border:1px solid var(--border);border-radius:10px;padding:10px 14px;">
                            <div style="font-size:10px;font-weight:700;color:var(--text-3);margin-bottom:3px;">${r.sender_name}</div>
                            <div style="font-size:13px;">${r.message}</div>
                        </div>
                    </div>`;
            });
        }

        const date = t.created_at ? new Date(t.created_at).toLocaleString('en-IN') : '—';

        body.innerHTML = `
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:12px;">
                <span class="status-pill s-${t.status}">${(t.status||'').replace('_',' ').toUpperCase()}</span>
                <span style="font-size:11px;color:var(--text-3);">Raised on ${date}</span>
                <span style="font-size:11px;color:var(--text-3);">• #${t.ticket_number}</span>
            </div>
            <h4 style="font-size:18px;font-weight:700;margin-bottom:12px;">${t.subject}</h4>
            ${resolutionHtml}
            <div style="background:var(--bg-1);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:4px;">
                <p style="color:var(--text-2);font-size:13px;line-height:1.6;margin:0;">${t.description}</p>
            </div>
            ${convoHtml}
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <textarea id="reply-input" rows="3" placeholder="Add a follow-up message..." style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:13px;font-family:inherit;background:var(--bg-1);resize:vertical;outline:none;"></textarea>
                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button onclick="sendReply(${t.id})" class="greeting-btn" style="flex:1;">Send Message</button>
                    <button onclick="closeModal()" style="padding:10px 18px;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--surface-2);cursor:pointer;font-size:13px;">Close</button>
                </div>
            </div>`;

    } catch(e) {
        body.innerHTML = `<div style="padding:20px;text-align:center;color:var(--red);">Failed to load ticket details. ${e.response?.data?.message || e.message}</div>`;
    }
}

async function sendReply(ticketId) {
    const input = document.getElementById('reply-input');
    const msg = (input?.value || '').trim();
    if (!msg) return;

    try {
        await axios.post(`${window.APP_URL}/api/employee/tickets/${ticketId}/reply`, { message: msg });
        openTicket(ticketId); // Refresh
    } catch(e) {
        alert('Failed to send reply: ' + (e.response?.data?.message || e.message));
    }
}

function closeModal() {
    document.getElementById('ticket-modal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    loadStats();
    loadTickets();
});
</script>
@endpush
@endsection