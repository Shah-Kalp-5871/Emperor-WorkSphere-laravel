@extends('layouts.employee.master')

@section('title', 'WorkSphere — Office Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    /* Global Reset & Modern Variables */
    :root {
        --cal-bg: #fff;
        --cal-border: #f0f0f0;
        --cal-accent: var(--accent, #2D6A4F);
        --cal-accent-lt: var(--accent-lt, #D8EFE4);
        --cal-text: var(--text-1, #1A1916);
        --cal-muted: var(--text-3, #9B9890);
    }

    .fc { font-family: 'DM Sans', sans-serif; height: 100%; border: none !important; }
    .fc .fc-toolbar { margin-bottom: 2rem !important; }
    .fc .fc-toolbar-title { font-family: 'Instrument Serif', serif; font-size: 1.8rem; font-weight: 400; color: var(--cal-text); }
    
    /* Buttons */
    .fc .fc-button-primary { 
        background-color: transparent !important; 
        border: 1px solid var(--cal-border) !important; 
        color: var(--cal-text) !important; 
        font-weight: 500 !important;
        text-transform: capitalize !important;
        padding: 8px 16px !important;
        transition: all 0.2s ease !important;
    }
    .fc .fc-button-primary:hover { border-color: var(--cal-accent) !important; color: var(--cal-accent) !important; background-color: #fafafa !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: var(--cal-accent) !important; 
        border-color: var(--cal-accent) !important; 
        color: #fff !important; 
    }

    /* Grid & Cells */
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid var(--cal-border) !important; }
    .fc .fc-col-header-cell { background: #fafafa; padding: 12px 0 !important; }
    .fc .fc-col-header-cell-cushion { 
        font-size: 11px; 
        font-weight: 700; 
        color: var(--cal-muted); 
        text-transform: uppercase; 
        letter-spacing: 0.1em;
        text-decoration: none !important;
    }
    .fc .fc-daygrid-day-number { font-size: 14px; color: var(--cal-text); padding: 12px !important; text-decoration: none !important; }
    .fc-day-today { background: rgba(45, 106, 79, 0.05) !important; }
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: var(--cal-accent); font-weight: 700; }
    
    /* Events */
    .fc-event { 
        border: none !important; 
        padding: 4px 8px !important; 
        border-radius: 6px !important; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        font-size: 11px !important;
        font-weight: 600 !important;
        margin: 2px 4px !important;
        cursor: pointer !important;
    }
    
    /* Layout */
    .calendar-container { display: grid; grid-template-columns: 1fr 340px; gap: 30px; }
    @media (max-width: 1100px) { .calendar-container { grid-template-columns: 1fr; } }
    
    .summary-panel { 
        background: #fff; 
        border: 1px solid var(--cal-border); 
        border-radius: 16px; 
        padding: 24px;
        position: sticky;
        top: 24px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    .summary-title { 
        font-family: 'Instrument Serif', serif; 
        font-size: 1.5rem; 
        margin-bottom: 20px; 
        color: var(--cal-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-count { font-size: 12px; font-family: 'DM Sans', sans-serif; color: var(--cal-muted); font-weight: 500; }
    
    .event-card { 
        display: flex; 
        gap: 15px; 
        padding: 16px; 
        border-radius: 12px; 
        background: #fafafa; 
        border: 1px solid #f0f0f0;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
        text-align: left;
    }
    .event-card:hover { transform: translateX(5px); border-color: var(--cal-accent); background: #fff; box-shadow: var(--shadow); }
    .event-date-box { 
        width: 48px; 
        height: 48px; 
        background: #fff; 
        border: 1px solid #eee; 
        border-radius: 10px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0;
    }
    .event-day { font-size: 18px; font-weight: 700; color: var(--cal-text); line-height: 1; }
    .event-month { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--cal-muted); margin-top: 2px; }
    .event-info { flex: 1; }
    .event-info h4 { font-size: 14px; font-weight: 600; color: var(--cal-text); margin-bottom: 2px; }
    .event-info p { font-size: 12px; color: var(--cal-muted); display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

    /* Modal Tweaks */
    .modal { border-radius: 20px !important; width: 420px !important; border: 1px solid var(--border) !important; }
    .modal-title { font-family: 'Instrument Serif', serif; font-size: 26px !important; margin-bottom: 8px !important; }
    .modal-sub { font-size: 14px !important; color: var(--cal-accent) !important; font-weight: 500 !important; }
</style>
@endpush

@section('content')
<div class="calendar-container">
    <div class="panel" style="background: #fff; border-radius: 20px; padding: 30px; border: 1px solid var(--cal-border); box-shadow: var(--shadow);">
        <div id="calendar"></div>
    </div>
    
    <div class="summary-panel shadow-sm">
        <div class="summary-title">
            Office Agenda
            <span class="summary-count" id="event-total-count">0 events</span>
        </div>
        <div id="upcoming-events-list">
            <div style="text-align:center; padding:40px; color:var(--cal-muted); font-size:14px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom:10px; opacity:0.3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <div id="loading-text">Fetching latest events...</div>
            </div>
        </div>
    </div>
</div>

<!-- Simple View Modal -->
<div class="modal-overlay" id="view-event-modal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('view-event-modal')">×</button>
        <div class="modal-title" id="modal-event-title"></div>
        <div class="modal-sub" id="modal-event-date"></div>
        
        <div style="margin: 24px 0;">
            <div id="modal-event-type-badge" style="margin-bottom: 16px;"></div>
            <div style="font-size: 12px; color: var(--cal-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Description</div>
            <div id="modal-event-description" style="font-size: 15px; color: var(--text-2); line-height: 1.6; background: #fafafa; padding: 15px; border-radius: 12px; border: 1px solid #f0f0f0;"></div>
        </div>
        
        <div class="modal-footer" style="padding: 0; border: none; margin-top: 10px;">
            <button type="button" class="greeting-btn" style="width: 100%; justify-content: center; padding: 14px; border-radius: 12px;" onclick="closeModal('view-event-modal')">Close Details</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    let calendar;
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            events: window.APP_URL + '/api/employee/calendar/events',
            editable: false,
            selectable: false,
            dayMaxEvents: true,
            height: 'auto',
            
            eventClick: function(info) {
                openViewModal(info.event);
            },
            
            loading: function(isLoading) {
                if (!isLoading) {
                    // Small delay to ensure events are in the calendar object
                    setTimeout(updateSummaryList, 300);
                }
            }
        });
        calendar.render();
    });

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function openViewModal(event) {
        document.getElementById('modal-event-title').innerText = event.title;
        // Format date: Friday, 20 March 2026
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('modal-event-date').innerText = new Date(event.start).toLocaleDateString('en-US', dateOptions);
        document.getElementById('modal-event-description').innerText = event.extendedProps.description || 'No additional details provided.';
        
        const typeLabel = event.extendedProps.event_type.replace('_', ' ').toUpperCase();
        const typeClass = event.extendedProps.event_type === 'holiday' || event.extendedProps.event_type === 'office_off' ? 'priority high' : 'priority low';
        document.getElementById('modal-event-type-badge').innerHTML = `<span class="${typeClass}">${typeLabel}</span>`;
        
        document.getElementById('view-event-modal').classList.add('active');
    }

    async function updateSummaryList() {
        const list = document.getElementById('upcoming-events-list');
        const countEl = document.getElementById('event-total-count');
        
        try {
            const res = await axios.get(window.APP_URL + '/api/employee/calendar/events', {
                params: {
                    start: new Date().toISOString().split('T')[0],
                    end: '2027-12-31'
                }
            });
            const upcoming = res.data;
            
            list.innerHTML = '';
            countEl.innerText = `${upcoming.length} upcoming`;

            if (upcoming.length === 0) {
                list.innerHTML = '<div style="text-align:center; padding:40px; color:var(--cal-muted); font-size:14px;">No office events scheduled</div>';
                return;
            }

            upcoming.forEach(event => {
                const startDate = new Date(event.start);
                const card = document.createElement('div');
                card.className = 'event-card';
                card.onclick = () => openViewModal(event);
                card.innerHTML = `
                    <div class="event-date-box">
                        <div class="event-day">${startDate.getDate()}</div>
                        <div class="event-month">${startDate.toLocaleDateString('en-US', { month: 'short' })}</div>
                    </div>
                    <div class="event-info">
                        <h4>${event.title}</h4>
                        <p>${event.extendedProps.description || 'Click to view details'}</p>
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

