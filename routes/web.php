<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\admin\archivedController;
use App\Http\Controllers\admin\calendarController;
use App\Http\Controllers\admin\dailylogsController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\employeesController;
use App\Http\Controllers\admin\profileController as AdminProfileController;
use App\Http\Controllers\admin\projectsController;
use App\Http\Controllers\admin\tasksController;

/*
|--------------------------------------------------------------------------
| Employee Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\employee\calendarController as EmployeeCalendarController;
use App\Http\Controllers\employee\dailylogsController as EmployeeDailyLogsController;
use App\Http\Controllers\employee\dashboardController as EmployeeDashboardController;
use App\Http\Controllers\employee\profileController as EmployeeProfileController;
use App\Http\Controllers\employee\projectsController as EmployeeProjectsController;
use App\Http\Controllers\employee\tasksController as EmployeeTasksController;
use App\Http\Controllers\employee\teamController;

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
        return view('admin.dashboard');
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
    Route::get('/employees/show', function () {
        return view('admin.employees.show');
    });

    // Projects
    Route::get('/projects', function () {
        return view('admin.projects.index');
    });
    Route::get('/projects/create', function () {
        return view('admin.projects.create');
    });
    Route::get('/projects/edit', function () {
        return view('admin.projects.edit');
    });
    Route::get('/projects/show', function () {
        return view('admin.projects.show');
    });

    // Tasks
    Route::get('/tasks', function () {
        return view('admin.tasks.index');
    });
    Route::get('/tasks/create', function () {
        return view('admin.tasks.create');
    });
    Route::get('/tasks/edit', function () {
        return view('admin.tasks.edit');
    });
    Route::get('/tasks/show', function () {
        return view('admin.tasks.show');
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
        return view('admin.calendar');
    });
    Route::get('/timeline', function () {
        return view('admin.timeline');
    });
    Route::get('/archived', function () {
        return view('admin.archived');
    });

    // Profile
    Route::get('/profile/my-profile', function () {
        return view('admin.profile.my-profile');
    });
});

/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    });

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
    Route::get('/dailylogs/edit', function () {
        return view('employee.dailylogs.edit');
    });

    // Others
    Route::get('/calendar', function () {
        return view('employee.calendar');
    });
    Route::get('/team', function () {
        return view('employee.team');
    });

    // Profile
    Route::get('/profile/my-profile', function () {
        return view('employee.profile.my-profile');
    });
});
