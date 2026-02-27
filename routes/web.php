<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\ArchivedController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DailylogsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectsController;
use App\Http\Controllers\Admin\TasksController;

/*
|--------------------------------------------------------------------------
| Employee Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Employee\CalendarController as EmployeeCalendarController;
use App\Http\Controllers\Employee\DailylogsController as EmployeeDailyLogsController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\Employee\ProjectsController as EmployeeProjectsController;
use App\Http\Controllers\Employee\TasksController as EmployeeTasksController;
use App\Http\Controllers\Employee\TeamController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    });

    // Employees
    Route::get('/employees', function () {
        return view('admin.employees.index');
    });
    Route::get('/employees/create', function () {
        return view('admin.employees.create');
    });
    Route::get('/employees/edit', function () {
        return view('admin.employees.edit');
    });
    Route::get('/employees/{id}', function ($id) {
        return view('admin.employees.show', ['employeeId' => $id]);
    });

    // Projects
    Route::get('/projects', function () {
        return view('admin.projects.index');
    });
    Route::get('/projects/archived', function () {
        return view('admin.projects.archived');
    });
    Route::get('/projects/create', function () {
        return view('admin.projects.create');
    });
    Route::get('/projects/{id}/edit', function ($id) {
        return view('admin.projects.edit', ['projectId' => $id]);
    });
    Route::get('/projects/{id}', function ($id) {
        return view('admin.projects.show', ['projectId' => $id]);
    });

    // Tasks
    Route::get('/tasks', function () {
        return view('admin.tasks.index');
    });
    Route::get('/tasks/archived', function () {
        return view('admin.tasks.archived');
    });
    Route::get('/tasks/create', function () {
        return view('admin.tasks.create');
    });
    Route::get('/tasks/edit', function () {
        return view('admin.tasks.edit');
    });
    Route::get('/tasks/{id}', function ($id) {
        return view('admin.tasks.show', ['taskId' => $id]);
    });

    // Daily Logs
    Route::get('/dailylogs', function () {
        return view('admin.dailylogs.index');
    });
    Route::get('/dailylogs/show', function () {
        return view('admin.dailylogs.show');
    });

    // Others
    Route::get('/calendar', function () {
        return view('admin.calendar.index');
    });
    Route::get('/timeline', function () {
        return view('admin.timeline.index');
    });
    Route::get('/archived', function () {
        return view('admin.archived.index');
    });

    // Profile
    Route::get('/profile/my-profile', function () {
        return view('admin.profile.index');
    });

    // Anonymous Chat
    Route::get('/anonymous-chat', function () {
        return view('admin.anonymous-chat');
    })->name('admin.anonymous-chat');

    Route::get('/tickets', function () {
        return view('admin.tickets.index');
    })->name('admin.tickets.index');

    Route::get('/login', function () {
        return view('admin.login');
    })->name('admin.login');

    Route::get('/attendance', function () {
        return view('admin.attendance.index');
    })->name('admin.attendance.index');
});

/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard.index');
    });

    Route::get('/login', function () {
        return view('employee.login');
    })->name('employee.login');

    // Projects
    Route::get('/projects', function () {
        return view('employee.projects.index');
    });
    Route::get('/projects/details', function () {
        return view('employee.projects.project_details');
    });

    // Tasks
    Route::get('/tasks', function () {
        return view('employee.tasks.index');
    });
    Route::get('/tasks/show', function () {
        return view('employee.tasks.show');
    });

    // Daily Logs
    Route::get('/dailylogs', function () {
        return view('employee.dailylogs.index');
    });
    Route::get('/dailylogs/create', function () {
        return view('employee.dailylogs.create');
    });
    Route::get('/dailylogs/show', function () {
        return view('employee.dailylogs.show');
    });
    Route::get('/dailylogs/edit', function () {
        return view('employee.dailylogs.edit');
    });

    // Others
    Route::get('/calendar', function () {
        return view('employee.calendar.index');
    });
    // Team
    Route::get('/team', function () {
        return view('employee.team.index');
    });
    Route::get('/team/show', function () {
        return view('employee.team.show');
    });

    // Profile
    Route::get('/profile/my-profile', function () {
        return view('employee.profile.index');
    });

    Route::get('/profile/edit', function () {
        return view('employee.profile.edit');
    });

    // Tickets
    Route::get('/tickets', function () {
        return view('employee.tickets.index');
    })->name('employee.tickets.index');

    Route::get('/tickets/create', function () {
        return view('employee.tickets.create');
    })->name('employee.tickets.create');

    Route::post('/tickets', function () {
        return redirect()->route('employee.tickets.index');
    })->name('employee.tickets.store');

    // Anonymous Chat
    Route::get('/anonymous-chat', function () {
        return view('employee.anonymous-chat');
    })->name('employee.anonymous-chat');
});
