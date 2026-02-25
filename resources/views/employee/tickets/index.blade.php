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
    <div class="stats-row" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <span class="stat-badge info">Total</span>
            </div>
            <div class="stat-value">{{ $totalTickets ?? 0 }}</div>
            <div class="stat-label">All Tickets</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon amber">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <span class="stat-badge warn">Pending</span>
            </div>
            <div class="stat-value">{{ $pendingTickets ?? 0 }}</div>
            <div class="stat-label">Awaiting Response</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 6L9 17L4 12"/>
                    </svg>
                </div>
                <span class="stat-badge up">Resolved</span>
            </div>
            <div class="stat-value">{{ $completedTickets ?? 0 }}</div>
            <div class="stat-label">Completed</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--surface-2); color: var(--text-2);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <span class="stat-badge" style="background: var(--surface-2); color: var(--text-3);">Avg</span>
            </div>
            <div class="stat-value">2.4h</div>
            <div class="stat-label">Response Time</div>
        </div>
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
                <select class="filter-select" style="padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--surface); font-size: 12px;" onchange="filterTickets(this.value)">
                    <option value="all">All Tickets</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
        
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden;">
            <table class="ticket-table" id="ticketsTable" data-tabulator data-tabulator-pagination="true" data-tabulator-page-size="10">
                <thead>
                    <tr>
                        <th data-width="120">Ticket ID</th>
                        <th>Subject</th>
                        <th data-width="130">Category</th>
                        <th data-width="150">Related To</th>
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
                            <span class="ticket-category-pill" style="background: #ef444420; color: #ef4444;">
                                Technical
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 13px;">Auth Module</span>
                        </td>
                        <td>
                            <span class="status-tag status-pending">Pending</span>
                        </td>
                        <td>Feb 25, 2026</td>
                        <td>2 hours ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('1')" title="View Details">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
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
                            <span class="ticket-category-pill" style="background: #3b82f620; color: #3b82f6;">
                                HR
                            </span>
                        </td>
                        <td>
                            <span style="color: var(--text-3); font-size: 12px;">—</span>
                        </td>
                        <td>
                            <span class="status-tag status-completed">Completed</span>
                        </td>
                        <td>Feb 24, 2026</td>
                        <td>1 day ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('2')" title="View Details">
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
                            <span class="ticket-category-pill" style="background: #f59e0b20; color: #f59e0b;">
                                Design
                            </span>
                        </td>
                        <td>
                            <span style="font-size: 13px;">Dashboard UI</span>
                        </td>
                        <td>
                            <span class="status-tag status-pending">Pending</span>
                        </td>
                        <td>Feb 23, 2026</td>
                        <td>2 days ago</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('3')" title="View Details">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
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
                            <span class="ticket-category-pill" style="background: {{ $ticket->category_color }}20; color: {{ $ticket->category_color }};">
                                {{ ucfirst($ticket->category) }}
                            </span>
                        </td>
                        <td>
                            @if($ticket->related_item)
                                <span style="font-size: 13px;">{{ $ticket->related_item }}</span>
                            @else
                                <span style="color: var(--text-3); font-size: 12px;">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-tag status-{{ $ticket->status }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td>{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td style="text-align: right;">
                            <button class="icon-btn" onclick="viewTicket('{{ $ticket->id }}')" title="View Details">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
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
function filterTickets(status) {
    if (window.TabulatorInstances && window.TabulatorInstances.ticketsTable) {
        if (status === 'all') {
            window.TabulatorInstances.ticketsTable.clearFilter();
        } else {
            // We search for the status text within the column col_4 (Status)
            // The status is inside a .status-tag span
            window.TabulatorInstances.ticketsTable.setFilter("col_4", "like", status);
        }
    } else {
        // Fallback for non-tabulator (shouldn't happen)
        const rows = document.querySelectorAll('.ticket-row');
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }
}

function viewTicket(ticketId) {
    // In a real application, this would fetch ticket details via AJAX
    // For demo purposes, we'll show a static modal
    const modal = document.getElementById('ticketModal');
    const content = document.getElementById('ticketDetailsContent');
    
    content.innerHTML = `
        <div style="margin-bottom: 20px;">
            <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                <span class="status-tag status-pending">Pending</span>
                <span style="color: var(--text-3); font-size: 13px;">Created on Feb 25, 2026</span>
            </div>
            <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">Issue with API integration task</h4>
            <div style="background: var(--surface-2); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                <p style="color: var(--text-2); line-height: 1.6;">The endpoint /v1/auth is returning 404 error when trying to authenticate. This is blocking the integration work. Please check if the route exists or if there's a configuration issue.</p>
            </div>
            
            <h5 style="font-size: 14px; font-weight: 600; margin-bottom: 12px;">Conversation</h5>
            
            <div style="margin-bottom: 16px;">
                <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                    <div class="avatar" style="width: 32px; height: 32px; background: var(--accent-lt);">JD</div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-weight: 500;">John Doe</span>
                            <span style="font-size: 11px; color: var(--text-3);">Feb 25, 2026</span>
                        </div>
                        <p style="color: var(--text-2); font-size: 13px;">I'm facing this issue while working on the authentication module.</p>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <div class="avatar" style="width: 32px; height: 32px; background: var(--blue-lt); color: var(--blue);">SP</div>
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-weight: 500;">Support Team</span>
                            <span style="font-size: 11px; color: var(--text-3);">Feb 26, 2026</span>
                        </div>
                        <p style="color: var(--text-2); font-size: 13px;">We're looking into this issue. Can you share the exact error message?</p>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <textarea class="form-control" rows="3" placeholder="Add a reply..."></textarea>
                <div style="display: flex; gap: 10px; margin-top: 12px;">
                    <button class="greeting-btn" style="padding: 8px 16px;">Send Reply</button>
                    <button class="greeting-btn" style="background: var(--surface-2); color: var(--text-2); border: 1px solid var(--border);" onclick="closeTicketModal()">Close</button>
                </div>
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

function closeTicketModal() {
    document.getElementById('ticketModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('ticketModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>
@endpush
@endsection