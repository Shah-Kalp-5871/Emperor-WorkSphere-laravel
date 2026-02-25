<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class projectsController extends Controller
{
    public function index()
    {
        return view('employee.projects.index');
    }
}
