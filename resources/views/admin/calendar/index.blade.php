@extends('layouts.admin.master')

@section('title', 'WorkSphere — Office Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    /* Global Reset & Modern Variables for Admin (Dark Theme compatible) */
    :root {
        --cal-bg: var(--surface);
        --cal-border: var(--border);
        --cal-accent: var(--accent);
        --cal-text: var(--text);
        --cal-muted: var(--text3);
    }

    .fc { font-family: 'DM Sans', sans-serif; border: none !important; }
    .fc .fc-toolbar-title { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--cal-text); }
    
    /* Buttons */
    .fc .fc-button-primary { 
        background-color: var(--surface2) !important; 
        border: 1px solid var(--cal-border) !important; 
        color: var(--cal-text) !important; 
        font-weight: 600 !important;
        text-transform: capitalize !important;
        padding: 8px 16px !important;
        transition: all 0.2s ease !important;
    }
    .fc .fc-button-primary:hover { border-color: var(--cal-accent) !important; color: var(--cal-accent) !important; background-color: var(--border) !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: var(--cal-accent) !important; 
        border-color: var(--cal-accent) !important; 
        color: #fff !important; 
    }

    /* Grid & Cells */
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid var(--cal-border) !important; }
    .fc .fc-col-header-cell { background: var(--surface2); padding: 12px 0 !important; }
    .fc .fc-col-header-cell-cushion { 
        font-size: 11px; 
        font-weight: 700; 
        color: var(--cal-muted); 
        text-transform: uppercase; 
        letter-spacing: 0.1em;
        text-decoration: none !important;
    }
    .fc .fc-daygrid-day-number { font-size: 14px; color: var(--cal-text); padding: 12px !important; text-decoration: none !important; }
    .fc-day-today { background: rgba(79, 142, 247, 0.1) !important; }
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: var(--cal-accent); font-weight: 700; }
    
    /* Events */
    .fc-event { 
        border: none !important; 
        padding: 4px 8px !important; 
        border-radius: 6px !important; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        font-size: 11px !important;
        font-weight: 600 !important;
        margin: 2px 4px !important;
        cursor: pointer !important;
    }
    
    /* Layout */
    .calendar-container { display: grid; grid-template-columns: 1fr 340px; gap: 24px; }
    @media (max-width: 1100px) { .calendar-container { grid-template-columns: 1fr; } }
    
    .summary-panel { 
        background: var(--surface); 
        border: 1px solid var(--cal-border); 
        border-radius: 16px; 
        padding: 24px;
        position: sticky;
        top: 24px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    
    .event-card { 
        display: flex; 
        gap: 15px; 
        padding: 16px; 
        border-radius: 12px; 
        background: var(--surface2); 
        border: 1px solid var(--border);
        margin-bottom: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-align: left;
    }
    .event-card:hover { transform: translateX(5px); border-color: var(--cal-accent); background: var(--border); }
    .event-date-box { 
        width: 48px; 
        height: 48px; 
        background: var(--surface); 
        border: 1px solid var(--border); 
        border-radius: 10px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0;
    }
    .event-day { font-size: 18px; font-weight: 700; color: var(--cal-text); line-height: 1; }
    .event-month { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--cal-muted); margin-top: 2px; }
    .event-info { flex: 1; min-width: 0; }
    .event-info h4 { font-size: 14px; font-weight: 600; color: var(--cal-text); margin-bottom: 2px; }
    .event-info p { font-size: 12px; color: var(--cal-muted); display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

    /* Modal Overlay Overrides */
    .modal { border: 1px solid var(--border2) !important; box-shadow: 0 30px 60px rgba(0,0,0,0.5) !important; }
</style>
@endpush

@section('content')
<div class="calendar-container">
    <div class="panel" style="background: var(--surface); border-radius: 20px; padding: 24px; border: 1px solid var(--cal-border);">
        <div id="calendar"></div>
    </div>
    
    <div class="summary-panel shadow-sm">
        <div class="summary-title" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="font-family:'Syne', sans-serif; font-weight:700; font-size:18px;">Office Agenda</div>
            <span id="event-total-count" style="font-size:12px; color:var(--cal-muted); font-weight:600;">0 events</span>
        </div>
        
        <div id="upcoming-events-list">
            <div style="text-align:center; padding:40px; color:var(--cal-muted); font-size:14px;">
                <div id="loading-text">Loading events...</div>
            </div>
        </div>
        
        <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--cal-border)">
            <button class="topbar-btn" style="width:100%; height:44px; border-radius:12px; gap:8px; font-size:13px; font-weight:600; color:var(--accent); border-color:var(--accent);" onclick="openAddModal()">
                Add New Event
            </button>
        </div>
    </div>
</div>

<!-- Admin Event Modal -->
<div class="modal-overlay" id="event-modal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('event-modal')">×</button>
        <div class="modal-title" id="modal-title">New Event</div>
        <div class="modal-sub">Schedule an office event or milestone</div>
        
        <form id="event-form" style="margin-top: 20px;">
            <input type="hidden" id="event-id">
            
            <div class="form-group">
                <label class="form-label">Event Title</label>
                <input type="text" id="event-title" class="form-input" placeholder="e.g. Project Sprint Start" required>
            </div>
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select id="event-type" class="form-select" required>
                        <option value="holiday">Holiday</option>
                        <option value="office_off">Office OFF</option>
                        <option value="meeting">Meeting</option>
                        <option value="announcement">Announcement</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Visibility</label>
                    <select id="event-visible-to" class="form-select" required>
                        <option value="all">Public (Employees)</option>
                        <option value="admin_only">Private (Admins)</option>
                    </select>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" id="event-start-date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" id="event-end-date" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Note</label>
                <textarea id="event-description" class="form-input" style="height:100px; resize:none;" placeholder="Brief details about this event..."></textarea>
            </div>

            <div class="modal-footer" style="padding:0; border:none; margin-top:24px; display:flex; gap:12px;">
                <button type="button" id="delete-event-btn" class="topbar-btn" style="width: auto; height:44px; padding:0 20px; border-radius:12px; color:var(--danger); border-color:var(--danger); display:none" onclick="deleteEvent()">Delete</button>
                <button type="submit" class="topbar-btn" style="flex:1; height:44px; border-radius:12px; background:var(--accent); color:#fff; border:none; justify-content:center;" id="save-event-btn">Save Event</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    let calendar;
    const form = document.getElementById('event-form');
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: window.APP_URL + '/api/admin/calendar/events',
            editable: true,
            selectable: true,
            dayMaxEvents: true,
            height: 'auto',
            
            select: function(info) {
                openAddModal(info.startStr, info.endStr);
            },
            
            eventClick: function(info) {
                openEditModal(info.event);
            },
            
            eventDrop: function(info) {
                updateEventDate(info.event);
            },
            
            loading: function(isLoading) {
                if (!isLoading) {
                    setTimeout(updateSummaryList, 300);
                }
            }
        });
        calendar.render();
        form.onsubmit = handleFormSubmit;
    });

    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        form.reset();
        document.getElementById('event-id').value = '';
        document.getElementById('delete-event-btn').style.display = 'none';
    }

    function openAddModal(start = '', end = '') {
        document.getElementById('modal-title').innerText = 'New Event';
        document.getElementById('event-id').value = '';
        document.getElementById('event-start-date').value = start || new Date().toISOString().split('T')[0];
        document.getElementById('event-end-date').value = end ? new Date(new Date(end).getTime() - 86400000).toISOString().split('T')[0] : document.getElementById('event-start-date').value;
        document.getElementById('delete-event-btn').style.display = 'none';
        document.getElementById('save-event-btn').innerText = 'Create Event';
        openModal('event-modal');
    }

    function openEditModal(event) {
        document.getElementById('modal-title').innerText = 'Update Event';
        document.getElementById('event-id').value = event.id;
        document.getElementById('event-title').value = event.title;
        document.getElementById('event-type').value = event.extendedProps.event_type;
        document.getElementById('event-visible-to').value = event.extendedProps.visible_to || 'all';
        document.getElementById('event-start-date').value = event.startStr.split('T')[0];
        document.getElementById('event-end-date').value = event.end ? new Date(new Date(event.end).getTime() - 86400000).toISOString().split('T')[0] : event.startStr.split('T')[0];
        document.getElementById('event-description').value = event.extendedProps.description || '';
        
        document.getElementById('delete-event-btn').style.display = 'block';
        document.getElementById('save-event-btn').innerText = 'Save Changes';
        openModal('event-modal');
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('event-id').value;
        const data = {
            title: document.getElementById('event-title').value,
            event_type: document.getElementById('event-type').value,
            visible_to: document.getElementById('event-visible-to').value,
            start_date: document.getElementById('event-start-date').value,
            end_date: document.getElementById('event-end-date').value || document.getElementById('event-start-date').value,
            description: document.getElementById('event-description').value,
            is_all_day: true
        };

        try {
            if (id) {
                await axios.put(`${window.APP_URL}/api/admin/calendar/events/${id}`, data);
            } else {
                await axios.post(`${window.APP_URL}/api/admin/calendar/events`, data);
            }
            calendar.refetchEvents();
            closeModal('event-modal');
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to save event.'
            });
        }
    }

    async function updateEventDate(event) {
        const data = {
            start_date: event.startStr.split('T')[0],
            end_date: event.endStr ? new Date(new Date(event.endStr).getTime() - 86400000).toISOString().split('T')[0] : event.startStr.split('T')[0]
        };
        try {
            await axios.put(`${window.APP_URL}/api/admin/calendar/events/${event.id}`, data);
            updateSummaryList();
        } catch (error) {
            event.revert();
        }
    }

    async function deleteEvent() {
        const id = document.getElementById('event-id').value;
        if (!id) return;
        
        const result = await Swal.fire({
            title: 'Delete Event?',
            text: 'Are you sure you want to permanently delete this event?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete it',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`${window.APP_URL}/api/admin/calendar/events/${id}`);
                calendar.refetchEvents();
                closeModal('event-modal');
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Event has been deleted.',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: 'Deletion failed.'
                });
            }
        }
    }
    async function updateSummaryList() {
        const list = document.getElementById('upcoming-events-list');
        const countEl = document.getElementById('event-total-count');
        
        try {
            const res = await axios.get(window.APP_URL + '/api/admin/calendar/events', {
                params: {
                    start: new Date().toISOString().split('T')[0],
                    end: '2027-12-31'
                }
            });
            const upcoming = res.data;
            
            list.innerHTML = '';
            countEl.innerText = `${upcoming.length} upcoming`;

            if (upcoming.length === 0) {
                list.innerHTML = '<div style="text-align:center; padding:40px; color:var(--cal-muted); font-size:13px;">No office events scheduled</div>';
                return;
            }

            upcoming.forEach(event => {
                const startDate = new Date(event.start);
                const card = document.createElement('div');
                card.className = 'event-card';
                card.onclick = () => {
                    // Try to find the event in calendar to open correctly
                    const calEvent = calendar.getEventById(event.id);
                    if (calEvent) openEditModal(calEvent);
                    else {
                        // Fallback: manually fetch and open if not in current view
                        axios.get(`${window.APP_URL}/api/admin/calendar/events/${event.id}`)
                             .then(r => openEditModal({
                                 id: r.data.id,
                                 title: r.data.title,
                                 startStr: r.data.start_date,
                                 end: r.data.end_date ? new Date(new Date(r.data.end_date).getTime() + 86400000) : null,
                                 extendedProps: r.data
                             }));
                    }
                };
                card.innerHTML = `
                    <div class="event-date-box">
                        <div class="event-day">${startDate.getDate()}</div>
                        <div class="event-month">${startDate.toLocaleDateString('en-US', { month: 'short' })}</div>
                    </div>
                    <div class="event-info">
                        <h4>${event.title}</h4>
                        <p>${event.extendedProps.description || 'View details'}</p>
                    </div>
                `;
                list.appendChild(card);
            });
        } catch (error) {
            console.error('Agenda update failed', error);
        }
    }
</script>
@endpush
