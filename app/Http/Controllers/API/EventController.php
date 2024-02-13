<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function all_events()
    {
        $events = Event::latest()->paginate(10);

        return response()->json([
            'status_code'=> 200,
            'events' => $events
        ], 200);
    }
}
