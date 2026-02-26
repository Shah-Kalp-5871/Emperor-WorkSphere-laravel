@extends('layouts.admin.master')

@section('title', 'Support Tickets — Admin Panel')

@section('content')
<div style="animation: fadeUp 0.4s ease-out;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">Support Tickets</h1>
            <p style="color: var(--text-3); font-size: 14px; margin-top: 4px;">Monitor and manage employee support queries.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row" id="ticket-stats-container" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;">
        @foreach(range(1, 4) as $i)
        <div class="stat-card skeleton-card" style="height: 100px;"></div>
        @endforeach
    </div>

    <!-- Tickets Table -->
    <div class="panel">
        <div class="panel-header" style="padding: 17px 22px 14px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
            <div class="panel-title" style="font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Employee Tickets
                <span class="count" style="background: var(--bg); border: 1px solid var(--border); font-size: 11px; font-weight: 500; color: var(--text-3); padding: 1px 7px; border-radius: 20px;">{{ count($tickets ?? []) + 3 }}</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <select class="filter-select" id="statusFilter" style="padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); font-size: 12px;" onchange="currentPage=1; fetchTickets();">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>
        
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;">
            <table class="ticket-table" id="ticketsTable">
                <thead>
                    <tr>
                        <th data-width="120">ID</th>
                        <th>Subject</th>
                        <th data-width="130">Category</th>
                        <th data-width="150">Employee</th>
                        <th data-width="110">Status</th>
                        <th data-width="140">Date Raised</th>
                        <th data-width="100" style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Dynamic rows -->
                </tbody>
            </table>

            <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
                <div class="spinner" style="border: 4px solid rgba(255,255,255,0.1); border-top: 4px solid var(--accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 10px;"></div>
                <div style="color: var(--text-2); font-size: 14px;">Loading tickets...</div>
            </div>

            <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
                <div style="font-size: 24px; margin-bottom: 10px;">🎫</div>
                <div style="color: var(--text-2); font-size: 14px;">No support tickets found.</div>
            </div>

            <div id="table-error" style="text-align:center; padding: 40px; display: none; color: #ff4d4d;">
                <div style="font-size: 20px; margin-bottom: 10px;">⚠️</div>
                <div id="error-message" style="font-size: 14px;">Failed to load tickets.</div>
            </div>
        </div>
    </div>
    
    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; margin-top: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);">
        <div id="pagination-info" style="color: var(--text-2); font-size: 13px;">Showing 0 of 0 tickets</div>
        <div id="pagination-controls" style="display: flex; gap: 8px;">
            <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
            <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
        </div>
    </div>
</div>

<!-- Ticket Details Modal (Mirroring Employee Side) -->
<div id="ticketModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: var(--radius); width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: var(--shadow-md);">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700;">Ticket Details</h3>
            <button class="icon-btn" onclick="closeTicketModal()" style="border: none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div id="ticketDetailsContent" style="padding: 24px;">
            <!-- Content loaded dynamically -->
        </div>
    </div>
</div>

@push('styles')
<style>
    .status-tag {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .status-open { background: #fef3c7; color: #d97706; }
    .status-in_progress { background: #dbeafe; color: #2563eb; }
    .status-resolved { background: #d1fae5; color: #059669; }
    .status-closed { background: #f3f4f6; color: #6b7280; }
    
    .ticket-row:hover { background: var(--surface-2); }
    .skeleton-card { background: var(--bg-1); border: 1px solid var(--border); border-radius: 12px; animation: skeleton-shimmer 2s infinite; }
    @keyframes skeleton-shimmer { 0% { opacity: 0.5; } 50% { opacity: 1; } 100% { opacity: 0.5; } }
</style>
@endpush

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchStats() {
        try {
            const res = await axios.get('/api/admin/support-tickets/stats');
            const s = res.data.data;
            document.getElementById('ticket-stats-container').innerHTML = `
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon blue"><span>#</span></div><span class="stat-badge info">Total</span></div>
                    <div class="stat-value">${s.total}</div><div class="stat-label">All Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon amber"><span>!</span></div><span class="stat-badge warn">Open</span></div>
                    <div class="stat-value">${s.open}</div><div class="stat-label">Awaiting Response</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon blue"><span>⏳</span></div><span class="stat-badge info">Active</span></div>
                    <div class="stat-value">${s.in_progress}</div><div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon green"><span>✓</span></div><span class="stat-badge up">Resolved</span></div>
                    <div class="stat-value">${s.resolved + s.closed}</div><div class="stat-label">Resolved/Closed</div>
                </div>
            `;
        } catch (err) { console.error('Stats Error:', err); }
    }

    async function fetchTickets(page = 1) {
        const tbody = document.getElementById('ticketsTableBody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const errorDiv = document.getElementById('table-error');
        const info = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const status = document.getElementById('statusFilter').value;

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        errorDiv.style.display = 'none';
        
        try {
            const response = await axios.get(`/api/admin/support-tickets`, { params: { page, status } });
            const { data, meta } = response.data;
            const tickets = data;

            loading.style.display = 'none';

            if (!tickets || tickets.length === 0) {
                empty.style.display = 'block';
                return;
            }

            tickets.forEach(ticket => {
                const tr = document.createElement('tr');
                tr.className = 'ticket-row';
                const createdDate = ticket.created_at ? new Date(ticket.created_at).toLocaleDateString() : 'N/A';
                
                tr.innerHTML = `
                    <td style="font-weight: 600; color: var(--accent);">#${ticket.ticket_number}</td>
                    <td>
                        <div style="font-weight: 500;">${ticket.subject}</div>
                        <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">${ticket.description.substring(0, 50)}...</div>
                    </td>
                    <td><span class="ticket-category-pill" style="display:inline-block;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:500;background:var(--bg-1);border:1px solid var(--border)">${ticket.category.toUpperCase()}</span></td>
                    <td>
                        <div style="font-weight: 500;">${ticket.employee_name}</div>
                        <div style="font-size: 11px; color: var(--text-3);">${ticket.employee_designation}</div>
                    </td>
                    <td><span class="status-tag status-${ticket.status}">${ticket.status.replace('_', ' ').toUpperCase()}</span></td>
                    <td>${createdDate}</td>
                    <td style="text-align: right;">
                        <button class="icon-btn" onclick="viewTicket('${ticket.id}')"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            if (meta) {
                currentPage = meta.current_page;
                info.innerText = `Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} tickets`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === meta.last_page;
            }


        } catch (error) {
            console.error('Fetch error:', error);
            loading.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('error-message').innerText = error.response?.data?.message || 'Failed to load tickets.';
        }
    }

function filterTickets(status) {
    // Basic filter logic or re-fetch with status param
    fetchTickets(1); // Simplified for now
}

async function viewTicket(ticketId) {
    const modal = document.getElementById('ticketModal');
    const content = document.getElementById('ticketDetailsContent');
    content.innerHTML = '<div style="text-align:center; padding: 20px;">Loading details...</div>';
    modal.style.display = 'flex';

    try {
        const response = await axios.get(`/api/admin/support-tickets/${ticketId}`);
        const ticket = response.data.data;
        
        const createdDate = new Date(ticket.created_at).toLocaleString();
        const statusTag = `status-${ticket.status}`;

        let repliesHtml = '';
        if (ticket.replies && ticket.replies.length > 0) {
            repliesHtml = '<h5 style="font-size: 14px; font-weight: 700; margin: 20px 0 12px;">Conversation</h5>';
            ticket.replies.forEach(reply => {
                const align = reply.is_admin ? 'right' : 'left';
                const bg = reply.is_admin ? 'var(--blue-lt)' : 'var(--bg-1)';
                const border = reply.is_admin ? '1px solid #bfdbfe' : '1px solid var(--border)';
                repliesHtml += `
                    <div style="margin-bottom: 12px; text-align: ${align}">
                        <div style="display:inline-block; background: ${bg}; border: ${border}; padding: 10px 14px; border-radius: 12px; max-width: 85%; text-align: left;">
                            <div style="font-size: 11px; font-weight: 700; margin-bottom: 4px; color: var(--text-3)">${reply.sender_name}</div>
                            <div style="font-size: 13px;">${reply.message}</div>
                        </div>
                    </div>
                `;
            });
        }

        content.innerHTML = `
            <div style="margin-bottom: 20px;">
                <div style="display: flex; gap: 12px; margin-bottom: 16px; align-items:center">
                    <span class="status-tag ${statusTag}">${ticket.status.toUpperCase()}</span>
                    <span style="color: var(--text-3); font-size: 12px;">Raised on ${createdDate}</span>
                </div>
                <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">${ticket.subject}</h4>
                <div style="background: var(--bg-1); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                    <p style="color: var(--text-2); line-height: 1.6; font-size:14px;">${ticket.description}</p>
                </div>
                
                <div id="modal-replies-container" style="max-height: 300px; overflow-y: auto; padding: 10px 0;">
                    ${repliesHtml}
                </div>

                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <h5 style="font-size: 13px; font-weight: 700; margin-bottom: 8px;">Update Ticket Status</h5>
                    <select id="update-status-select" class="form-control" style="width: 100%; margin-bottom: 16px; padding: 10px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-1); color: var(--text-1);">
                        <option value="open" ${ticket.status === 'open' ? 'selected' : ''}>Open</option>
                        <option value="in_progress" ${ticket.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                        <option value="resolved" ${ticket.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                        <option value="closed" ${ticket.status === 'closed' ? 'selected' : ''}>Closed</option>
                    </select>

                    <h5 style="font-size: 13px; font-weight: 700; margin-bottom: 8px;">Post Internal/External Reply</h5>
                    <textarea id="reply-message" class="form-control" rows="3" placeholder="Type your response..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; outline: none; background: var(--bg-1); color: var(--text-1); font-size:14px;"></textarea>
                    
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 12px;">
                        <input type="checkbox" id="is-internal-note" style="cursor:pointer">
                        <label for="is-internal-note" style="font-size: 12px; color: var(--text-3); cursor:pointer">Internal Private Note</label>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button class="btn btn-primary" onclick="handleUpdateTicket('${ticket.id}')" id="update-ticket-btn" style="flex:1">Update Ticket</button>
                        <button class="btn btn-ghost" onclick="closeTicketModal()">Close</button>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        content.innerHTML = '<div style="color:red; padding:20px;">Failed to load ticket details.</div>';
    }
}

async function handleUpdateTicket(ticketId) {
    const status = document.getElementById('update-status-select').value;
    const message = document.getElementById('reply-message').value;
    const isInternal = document.getElementById('is-internal-note').checked;
    const btn = document.getElementById('update-ticket-btn');

    btn.disabled = true;
    btn.textContent = 'Updating...';

    try {
        // 1. Update Status
        await axios.put(`/api/admin/support-tickets/${ticketId}`, { status });
        
        // 2. Post Reply if exists
        if (message.trim()) {
            await axios.post(`/api/admin/support-tickets/${ticketId}/reply`, { 
                message, 
                is_internal: isInternal 
            });
        }

        closeTicketModal();
        fetchStats();
        fetchTickets(currentPage);
    } catch (error) {
        alert('Failed to update ticket: ' + (error.response?.data?.message || 'Error occurred'));
    } finally {
        btn.disabled = false;
        btn.textContent = 'Update Ticket';
    }
}

function closeTicketModal() {
    document.getElementById('ticketModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('ticketModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

document.getElementById('prev-page').addEventListener('click', () => {
    if (currentPage > 1) fetchTickets(currentPage - 1);
});

document.getElementById('next-page').addEventListener('click', () => {
    fetchTickets(currentPage + 1);
});

document.addEventListener('DOMContentLoaded', () => {
    fetchStats();
    fetchTickets();
});
</script>
@endpush
@endsection
