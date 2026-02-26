@extends('layouts.employee.master')

@section('title', 'My Support Tickets — WorkSphere')

@section('content')
<div style="animation: fadeUp 0.4s ease-out;">
    <!-- Header with Raise Ticket Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-family: 'Instrument Serif', serif; font-size: 32px; font-weight: 400;">Support Tickets</h1>
            <p style="color: var(--text-3); font-size: 14px; margin-top: 4px;">Track and manage your queries, doubts, and issues.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="greeting-btn" onclick="window.location.href='{{ route('employee.tickets.create') }}'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Raise New Ticket
            </button>
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
        <div class="panel-header">
            <div class="panel-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                Recent Tickets
                <span class="count">{{ count($tickets ?? []) }}</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <select class="filter-select" id="statusFilter" style="padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); font-size: 12px;" onchange="currentPage=1; fetchTickets();">
                    <option value="">All Tickets</option>
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
                        <th data-width="120">Ticket ID</th>
                        <th>Subject</th>
                        <th data-width="130">Category</th>
                        <th data-width="110">Status</th>
                        <th data-width="140">Date Raised</th>
                        <th data-width="100" style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Dynamic Rows -->
                </tbody>
            </table>

            <div id="table-loading" style="text-align:center; padding: 40px; display: none;">
                <div class="spinner-sm"></div>
                <div style="color: var(--text-3); font-size: 13px; margin-top: 10px;">Loading your tickets...</div>
            </div>

            <div id="table-empty" style="text-align:center; padding: 40px; display: none;">
                <div style="font-size: 24px; margin-bottom: 10px;">🎫</div>
                <div style="color: var(--text-3); font-size: 13px;">You haven't raised any tickets yet.</div>
            </div>
        </div>
    </div>

    <div class="pagination-row" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; margin-top: 10px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);">
        <div id="pagination-info" style="color: var(--text-3); font-size: 13px;">Showing 0 tickets</div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-ghost btn-sm" id="prev-page" disabled>Previous</button>
            <button class="btn btn-ghost btn-sm" id="next-page" disabled>Next</button>
        </div>
    </div>
</div>

<style>
    .status-tag { font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; }
    .status-open { background: #fef3c7; color: #d97706; }
    .status-in_progress { background: #dbeafe; color: #2563eb; }
    .status-resolved { background: #d1fae5; color: #059669; }
    .status-closed { background: #f3f4f6; color: #6b7280; }
    .skeleton-card { background: var(--bg-1); border: 1px solid var(--border); border-radius: 12px; animation: skeleton-shimmer 2s infinite; }
    @keyframes skeleton-shimmer { 0% { opacity: 0.5; } 50% { opacity: 1; } 100% { opacity: 0.5; } }
    .spinner-sm { width: 24px; height: 24px; border: 2px solid var(--border); border-top: 2px solid var(--accent); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<!-- Ticket Details Modal -->
<div id="ticketModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--surface); border-radius: var(--radius); width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; box-shadow: var(--shadow-md);">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-family: 'Instrument Serif', serif; font-size: 20px;">Ticket Details</h3>
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

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchStats() {
        try {
            const res = await axios.get('/api/employee/tickets/stats');
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
                    <div class="stat-header"><div class="stat-icon green"><span>✓</span></div><span class="stat-badge up">Resolved</span></div>
                    <div class="stat-value">${s.resolved}</div><div class="stat-label">Resolved</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon" style="background:var(--bg-2);color:var(--text-3)"><span>Avg</span></div></div>
                    <div class="stat-value">2.4h</div><div class="stat-label">Response Time</div>
                </div>
            `;
        } catch (err) { console.error('Stats Error:', err); }
    }

    async function fetchTickets(page = 1) {
        const tbody = document.getElementById('ticketsTableBody');
        const loading = document.getElementById('table-loading');
        const empty = document.getElementById('table-empty');
        const info = document.getElementById('pagination-info');
        const prevBtn = document.getElementById('prev-page');
        const nextBtn = document.getElementById('next-page');
        const status = document.getElementById('statusFilter').value;

        tbody.innerHTML = '';
        loading.style.display = 'block';
        empty.style.display = 'none';
        
        try {
            const response = await axios.get(`/api/employee/tickets`, { params: { page, status } });
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
                        <div style="font-size: 11px; color: var(--text-3); margin-top: 2px;">${ticket.description.substring(0, 45)}...</div>
                    </td>
                    <td><span style="font-size:11px;font-weight:500;padding:2px 6px;border-radius:4px;background:var(--bg-2);border:1px solid var(--border)">${ticket.category.toUpperCase()}</span></td>
                    <td><span class="status-tag status-${ticket.status}">${ticket.status.toUpperCase()}</span></td>
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
        }
    }

    async function viewTicket(ticketId) {
        const modal = document.getElementById('ticketModal');
        const content = document.getElementById('ticketDetailsContent');
        content.innerHTML = '<div style="text-align:center; padding: 20px;"><div class="spinner-sm"></div></div>';
        modal.style.display = 'flex';

        try {
            const response = await axios.get(`/api/employee/tickets/${ticketId}`);
            const ticket = response.data.data;
            
            const createdDate = new Date(ticket.created_at).toLocaleString();
            let repliesHtml = '';
            
            if (ticket.replies && ticket.replies.length > 0) {
                repliesHtml = '<h5 style="font-size: 13px; font-weight: 700; margin: 20px 0 10px;">Conversation</h5>';
                ticket.replies.forEach(reply => {
                    const isOwn = !reply.is_admin;
                    const align = isOwn ? 'right' : 'left';
                    const bg = isOwn ? 'var(--blue-lt)' : 'var(--bg-1)';
                    const border = isOwn ? '1px solid #bfdbfe' : '1px solid var(--border)';
                    
                    repliesHtml += `
                        <div style="margin-bottom: 12px; text-align: ${align}">
                            <div style="display:inline-block; background: ${bg}; border: ${border}; padding: 10px 14px; border-radius: 12px; max-width: 85%; text-align: left;">
                                <div style="font-size: 10px; font-weight: 700; margin-bottom: 4px; color: var(--text-3)">${reply.sender_name}</div>
                                <div style="font-size: 13px;">${reply.message}</div>
                            </div>
                        </div>
                    `;
                });
            }

            content.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 8px; margin-bottom: 12px; align-items:center">
                        <span class="status-tag status-${ticket.status}">${ticket.status.toUpperCase()}</span>
                        <span style="color: var(--text-3); font-size: 11px;">Raised on ${createdDate}</span>
                    </div>
                    <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">${ticket.subject}</h4>
                    <div style="background: var(--bg-1); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                        <p style="color: var(--text-2); line-height: 1.5; font-size:13px;">${ticket.description}</p>
                    </div>
                    
                    <div id="modal-replies-container" style="max-height: 250px; overflow-y: auto;">
                        ${repliesHtml}
                    </div>

                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                        <textarea id="reply-message" class="form-control" rows="3" placeholder="Add a follow-up message..." style="width: 100%; border-radius: 8px; border: 1px solid var(--border); padding: 12px; font-size:13px; outline:none; background:var(--bg-1)"></textarea>
                        <div style="display: flex; gap: 10px; margin-top: 12px;">
                            <button class="greeting-btn" onclick="handleSendReply('${ticket.id}')" id="send-reply-btn" style="flex:1">Send Message</button>
                            <button class="greeting-btn" style="background: var(--bg-2); color: var(--text-2)" onclick="closeTicketModal()">Close</button>
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
        if (!message.trim()) return;

        const btn = document.getElementById('send-reply-btn');
        btn.disabled = true;
        btn.textContent = 'Sending...';

        try {
            await axios.post(`/api/employee/tickets/${ticketId}/reply`, { message });
            viewTicket(ticketId); // Refresh modal
        } catch (error) {
            alert('Failed to send reply.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Send Message';
        }
    }

    function closeTicketModal() {
        document.getElementById('ticketModal').style.display = 'none';
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