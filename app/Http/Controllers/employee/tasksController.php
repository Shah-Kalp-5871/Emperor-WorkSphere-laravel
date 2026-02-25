<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class tasksController extends Controller
{
    public function index()
    {
        return view('employee.tasks.index');
    }
}
