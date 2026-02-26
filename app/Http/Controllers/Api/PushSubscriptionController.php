<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $subscription = PushSubscription::updateOrCreate(
            [
                'endpoint' => $request->endpoint,
            ],
            [
                'user_id' => auth('api')->id(),
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
                'content_encoding' => $request->input('contentEncoding'),
            ]
        );

        return response()->json(['message' => 'Subscription stored successfully.', 'data' => $subscription], 201);
    }

    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|url']);
        
        PushSubscription::where('endpoint', $request->endpoint)
            ->where('user_id', auth('api')->id())
            ->delete();

        return response()->json(['message' => 'Subscription deleted successfully.']);
    }
}
