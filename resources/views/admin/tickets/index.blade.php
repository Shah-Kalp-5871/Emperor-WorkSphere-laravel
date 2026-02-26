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
    <div class="stats-row" style="margin-bottom: 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px;">
        <div class="stat-card">
            <div class="stat-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div class="stat-icon blue" style="width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; background: var(--blue-lt); color: var(--blue);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <span class="stat-badge info" style="font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 20px; background: var(--blue-lt); color: var(--blue);">Total</span>
            </div>
            <div class="stat-value" style="font-size: 28px; font-weight: 600; letter-spacing: -.5px;">{{ $totalTickets ?? 3 }}</div>
            <div class="stat-label" style="font-size: 12px; color: var(--text-3); margin-top: 3px;">All Tickets</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div class="stat-icon amber" style="width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; background: var(--amber-lt); color: var(--amber);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <span class="stat-badge warn" style="font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 20px; background: var(--amber-lt); color: var(--amber);">Active</span>
            </div>
            <div class="stat-value" style="font-size: 28px; font-weight: 600; letter-spacing: -.5px;">{{ $pendingTickets ?? 2 }}</div>
            <div class="stat-label" style="font-size: 12px; color: var(--text-3); margin-top: 3px;">Awaiting Response</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div class="stat-icon green" style="width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; background: var(--accent-lt); color: var(--accent);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17L4 12"/>
                    </svg>
                </div>
                <span class="stat-badge up" style="font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 20px; background: var(--accent-lt); color: var(--accent);">Resolved</span>
            </div>
            <div class="stat-value" style="font-size: 28px; font-weight: 600; letter-spacing: -.5px;">{{ $completedTickets ?? 1 }}</div>
            <div class="stat-label" style="font-size: 12px; color: var(--text-3); margin-top: 3px;">Completed</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                <div class="stat-icon" style="width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; background: var(--surface-2); color: var(--text-2);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <span class="stat-badge" style="font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 20px; background: var(--surface-2); color: var(--text-3);">Avg</span>
            </div>
            <div class="stat-value" style="font-size: 28px; font-weight: 600; letter-spacing: -.5px;">1.8h</div>
            <div class="stat-label" style="font-size: 12px; color: var(--text-3); margin-top: 3px;">Response Time</div>
        </div>
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
                <select class="filter-select" style="padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); font-size: 12px;" onchange="filterTickets(this.value)">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
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
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .status-pending { background: var(--amber-lt); color: var(--amber); }
    .status-completed { background: var(--accent-lt); color: var(--accent); }
    
    .ticket-row:hover { background: var(--surface-2); }
</style>
@endpush

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchTickets(page = 1) {
        const tbody = document.getElementById('ticketsTableBody');
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
            const response = await axios.get(`/api/admin/support-tickets?page=${page}`);
            const { data, meta } = response.data;
            const tickets = data;
            const paginationMeta = meta || response.data;

            loading.style.display = 'none';

            if (!tickets || tickets.length === 0) {
                empty.style.display = 'block';
                return;
            }

            tickets.forEach(ticket => {
                const tr = document.createElement('tr');
                tr.className = 'ticket-row';
                const createdDate = ticket.created_at ? new Date(ticket.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A';
                
                const statusTag = ticket.status === 'open' ? 'status-pending' : 'status-completed';

                let categoryBackground = ticket.category === 'technical' ? '#ef444420' : (ticket.category === 'hr' ? '#3b82f620' : '#f59e0b20');
                let categoryColor = ticket.category === 'technical' ? '#ef4444' : (ticket.category === 'hr' ? '#3b82f6' : '#f59e0b');

                tr.innerHTML = `
                    <td style="font-weight: 600; color: var(--accent);">#${ticket.ticket_number}</td>
                    <td>
                        <div style="font-weight: 500;">${ticket.subject}</div>
                        <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">${ticket.description.substring(0, 50)}...</div>
                    </td>
                    <td>
                        <span class="ticket-category-pill" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; background: ${categoryBackground}; color: ${categoryColor};">
                            ${ticket.category.toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 500;">${ticket.employee_name}</div>
                        <div style="font-size: 11px; color: var(--text-3);">${ticket.employee_designation}</div>
                    </td>
                    <td>
                        <span class="status-tag ${statusTag}">${ticket.status.toUpperCase()}</span>
                    </td>
                    <td>${createdDate}</td>
                    <td style="text-align: right;">
                        <button class="icon-btn" onclick="viewTicket('${ticket.id}')" title="Respond">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            if (paginationMeta) {
                currentPage = paginationMeta.current_page || 1;
                info.innerText = `Showing ${paginationMeta.from || 0} to ${paginationMeta.to || 0} of ${paginationMeta.total || 0} tickets`;
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === (paginationMeta.last_page || 1);
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
        const statusTag = ticket.status === 'open' ? 'status-pending' : 'status-completed';

        let repliesHtml = '';
        if (ticket.replies && ticket.replies.length > 0) {
            repliesHtml = '<h5 style="font-size: 14px; font-weight: 700; margin: 20px 0 12px;">Conversation</h5>';
            ticket.replies.forEach(reply => {
                const align = reply.is_admin ? 'right' : 'left';
                const bg = reply.is_admin ? 'var(--blue-lt)' : 'var(--surface-2)';
                repliesHtml += `
                    <div style="margin-bottom: 12px; text-align: ${align}">
                        <div style="display:inline-block; background: ${bg}; padding: 10px 14px; border-radius: 8px; max-width: 80%; text-align: left;">
                            <div style="font-size: 11px; font-weight: 600; margin-bottom: 4px;">${reply.sender_name}</div>
                            <div style="font-size: 13px;">${reply.message}</div>
                        </div>
                    </div>
                `;
            });
        }

        content.innerHTML = `
            <div style="margin-bottom: 20px;">
                <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                    <span class="status-tag ${statusTag}">${ticket.status.toUpperCase()}</span>
                    <span style="color: var(--text-3); font-size: 13px;">Raised on ${createdDate}</span>
                </div>
                <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">${ticket.subject}</h4>
                <div style="background: var(--surface-2); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                    <p style="color: var(--text-2); line-height: 1.6;">${ticket.description}</p>
                </div>
                
                ${repliesHtml}

                <h5 style="font-size: 14px; font-weight: 700; margin-bottom: 12px;">Send Response</h5>
                <div style="margin-top: 20px;">
                    <textarea id="reply-message" class="form-control" rows="3" placeholder="Type your response..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); outline: none; background: var(--bg-1); color: var(--text-1);"></textarea>
                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                        <button class="btn btn-primary" onclick="handleSendReply('${ticket.id}')" id="send-reply-btn">Send Response</button>
                        ${ticket.status === 'open' ? `<button class="btn btn-primary" onclick="handleUpdateStatus('${ticket.id}', 'resolved')">Mark as Resolved</button>` : ''}
                        <button class="btn btn-ghost" onclick="closeTicketModal()">Close</button>
                    </div>
                </div>
            </div>
        `;
    } catch (error) {
        content.innerHTML = '<div style="color:red; padding:20px;">Failed to load ticket details.</div>';
    }
}

async function handleSendReply(ticketId) {
    const message = document.getElementById('reply-message').value;
    if (!message) return;

    const btn = document.getElementById('send-reply-btn');
    btn.disabled = true;
    btn.textContent = 'Sending...';

    try {
        await axios.post(`/api/admin/support-tickets/${ticketId}/reply`, { message });
        viewTicket(ticketId); // Refresh details
    } catch (error) {
        alert('Failed to send reply.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Send Response';
    }
}

async function handleUpdateStatus(ticketId, status) {
    try {
        await axios.put(`/api/admin/support-tickets/${ticketId}`, { status });
        viewTicket(ticketId);
        fetchTickets(currentPage);
    } catch (error) {
        alert('Failed to update status.');
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
    fetchTickets();
});
</script>
@endpush
@endsection
