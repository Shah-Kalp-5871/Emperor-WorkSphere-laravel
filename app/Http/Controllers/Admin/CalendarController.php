<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CalendarEvent;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    /**
     * Get events for FullCalendar
     */
    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        
        // Determine if current user is admin
        $isAdmin = auth('admin')->check();

        $events = CalendarEvent::query()
            ->when($start, fn($q) => $q->where(function($query) use ($start) {
                $query->where('end_date', '>=', $start)
                      ->orWhere('start_date', '>=', $start);
            }))
            ->when($end, fn($q) => $q->where('start_date', '<=', $end))
            ->when(!$isAdmin, fn($q) => $q->where('visible_to', 'all'))
            ->get()
            ->map(function ($event) {
                // Map event_type to colors if not set
                $color = $event->color;
                if (!$color) {
                    $color = match ($event->event_type) {
                        'holiday' => '#f43f5e', // red-500
                        'office_off' => '#f43f5e', 
                        'meeting' => '#3b82f6', // blue-500
                        'deadline' => '#ef4444', 
                        'announcement' => '#10b981', // emerald-500
                        default => '#6366f1' // indigo-500
                    };
                }

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d'),
                    'end' => $event->end_date 
                        ? $event->end_date->addDay()->format('Y-m-d') 
                        : $event->start_date->addDay()->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'extendedProps' => [
                        'description' => $event->description,
                        'event_type' => $event->event_type,
                        'visible_to' => $event->visible_to,
                    ]
                ];
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'event_type' => 'required|in:holiday,office_off,meeting,deadline,announcement',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'is_all_day' => 'boolean',
            'color' => 'nullable|string|max:7',
            'visible_to' => 'in:all,admin_only',
        ]);

        $validated['created_by'] = auth()->id() ?? 1; // Default to 1 if not auth (for testing)

        $event = CalendarEvent::create($validated);

        return response()->json($event, 201);
    }

    public function show($id)
    {
        return response()->json(CalendarEvent::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $event = CalendarEvent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'event_type' => 'sometimes|required|in:holiday,office_off,meeting,deadline,announcement',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'is_all_day' => 'boolean',
            'color' => 'nullable|string|max:7',
            'visible_to' => 'in:all,admin_only',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = CalendarEvent::findOrFail($id);
        $event->delete();

        return response()->json(null, 204);
    }
}
