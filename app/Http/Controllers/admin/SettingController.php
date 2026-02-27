<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getOfficeLocation()
    {
        $settings = Setting::first();
        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    public function updateOfficeLocation(Request $request)
    {
        $request->validate([
            'office_latitude' => 'required|numeric',
            'office_longitude' => 'required|numeric',
            'office_radius' => 'required|integer|min:1',
        ]);

        $settings = Setting::first() ?: new Setting();
        $settings->fill($request->only([
            'office_latitude',
            'office_longitude',
            'office_radius'
        ]))->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Office location updated successfully.',
            'data' => $settings
        ]);
    }
}
