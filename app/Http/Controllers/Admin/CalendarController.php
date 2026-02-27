<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class calendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }
}
