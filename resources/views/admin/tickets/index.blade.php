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
            <table class="ticket-table" id="ticketsTable" data-tabulator data-tabulator-pagination="true" data-tabulator-page-size="10">
                <thead>
                    <tr>
                        <th data-width="120">ID</th>
                        <th>Subject</th>
                        <th data-width="130">Category</th>
                        <th data-width="150">Employee</th>
                        <th data-width="110">Status</th>
                        <th data-width="140">Date Raised</th>
                        <th data-width="140">Last Updated</th>
                        <th data-width="100" style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Fake Static Entries -->
                    <tr class="ticket-row" data-status="pending">
                        <td style="font-weight: 600; color: var(--accent);">#TK-7842</td>
                        <td>
                            <div style="font-weight: 500;">API Authentication Issue</div>
                            <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">Getting 401 Unauthorized on the login endpoint...</div>
                        </td>
                        <td>
                            <span class="ticket-category-pill" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; background: #ef444420; color: #ef4444;">
                                Technical
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500;">Kalp Shah</div>
                            <div style="font-size: 11px; color: var(--text-3);">Intern</div>
                        </td>
                        <td>
                            <span class="status-tag status-pending">Pending</span>
                        </td>
                        <td>Feb 25, 2026</td>
                        <td>2 hours ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('1')" title="Respond">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="ticket-row" data-status="completed">
                        <td style="font-weight: 600; color: var(--accent);">#TK-7839</td>
                        <td>
                            <div style="font-weight: 500;">Leave Request Clarification</div>
                            <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">Need to know the carry-forward policy for...</div>
                        </td>
                        <td>
                            <span class="ticket-category-pill" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; background: #3b82f620; color: #3b82f6;">
                                HR
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500;">John Doe</div>
                            <div style="font-size: 11px; color: var(--text-3);">Developer</div>
                        </td>
                        <td>
                            <span class="status-tag status-completed">Completed</span>
                        </td>
                        <td>Feb 24, 2026</td>
                        <td>1 day ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('2')" title="View Conversation">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="ticket-row" data-status="pending">
                        <td style="font-weight: 600; color: var(--accent);">#TK-7835</td>
                        <td>
                            <div style="font-weight: 500;">UI Breakdown on Mobile</div>
                            <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">The dashboard sidebar overlaps content on...</div>
                        </td>
                        <td>
                            <span class="ticket-category-pill" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; background: #f59e0b20; color: #f59e0b;">
                                Design
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500;">Jane Smith</div>
                            <div style="font-size: 11px; color: var(--text-3);">UI Designer</div>
                        </td>
                        <td>
                            <span class="status-tag status-pending">Pending</span>
                        </td>
                        <td>Feb 23, 2026</td>
                        <td>2 days ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('3')" title="Respond">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    @forelse($tickets ?? [] as $ticket)
                    <tr class="ticket-row" data-status="{{ $ticket->status }}">
                        <td style="font-weight: 600; color: var(--accent);">#{{ $ticket->ticket_id }}</td>
                        <td>
                            <div style="font-weight: 500;">{{ $ticket->subject }}</div>
                            <div style="font-size: 12px; color: var(--text-3); margin-top: 2px;">{{ Str::limit($ticket->description, 50) }}</div>
                        </td>
                        <td>
                            <span class="ticket-category-pill" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; background: {{ $ticket->category_color }}20; color: {{ $ticket->category_color }};">
                                {{ ucfirst($ticket->category) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $ticket->employee_name ?? 'Employee' }}</div>
                        </td>
                        <td>
                            <span class="status-tag status-{{ $ticket->status }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('{{ $ticket->id }}')" title="Respond">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <!-- No real tickets, but we have fake ones above -->
                    @endforelse
                </tbody>
            </table>
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
function filterTickets(status) {
    if (window.TabulatorInstances && window.TabulatorInstances.ticketsTable) {
        if (status === 'all') {
            window.TabulatorInstances.ticketsTable.clearFilter();
        } else {
            window.TabulatorInstances.ticketsTable.setFilter("col_4", "like", status);
        }
    }
}

function viewTicket(ticketId) {
    const modal = document.getElementById('ticketModal');
    const content = document.getElementById('ticketDetailsContent');
    
    content.innerHTML = `
        <div style="margin-bottom: 20px;">
            <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                <span class="status-tag status-pending">Pending</span>
                <span style="color: var(--text-3); font-size: 13px;">Created by Kalp Shah on Feb 25, 2026</span>
            </div>
            <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 12px;">API Authentication Issue</h4>
            <div style="background: var(--surface-2); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                <p style="color: var(--text-2); line-height: 1.6;">Getting 401 Unauthorized on the login endpoint. This is blocking the integration work.</p>
            </div>
            
            <h5 style="font-size: 14px; font-weight: 700; margin-bottom: 12px;">Response Area</h5>
            
            <div style="margin-top: 20px;">
                <textarea class="form-control" rows="3" placeholder="Type your response to the employee..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); outline: none;"></textarea>
                <div style="display: flex; gap: 10px; margin-top: 12px;">
                    <button class="greeting-btn" style="padding: 8px 16px; background: var(--accent); color: #fff; border: none; border-radius: 4px; cursor: pointer;">Send Response</button>
                    <button class="greeting-btn" style="background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border); padding: 8px 16px; border-radius: 4px; cursor: pointer;" onclick="closeTicketModal()">Cancel</button>
                </div>
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
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
</script>
@endpush
@endsection
