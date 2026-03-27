<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\CalendarEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->admin = Admin::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_can_fetch_events()
    {
        CalendarEvent::factory()->count(3)->create(['created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson('/api/admin/calendar/events');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_create_event()
    {
        $data = [
            'title' => 'Test Meeting',
            'event_type' => 'meeting',
            'start_date' => '2026-03-27',
            'is_all_day' => true,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson('/api/admin/calendar/events', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('calendar_events', ['title' => 'Test Meeting']);
    }

    public function test_can_update_event()
    {
        $event = CalendarEvent::factory()->create(['created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->putJson("/api/admin/calendar/events/{$event->id}", [
                'title' => 'Updated Title'
            ]);

        $response->assertStatus(200);
        $this->assertEquals('Updated Title', $event->fresh()->title);
    }

    public function test_employee_can_fetch_public_events()
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('employee');

        CalendarEvent::factory()->create(['visible_to' => 'all', 'title' => 'Public Event']);
        CalendarEvent::factory()->create(['visible_to' => 'admin_only', 'title' => 'Private Event']);

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/employee/calendar/events');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Public Event'])
            ->assertJsonMissing(['title' => 'Private Event']);
    }

    public function test_employee_cannot_create_event()
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('employee');

        $response = $this->actingAs($user, 'api')
            ->postJson('/api/admin/calendar/events', ['title' => 'Should Fail']);

        $response->assertStatus(403);
    }
}
