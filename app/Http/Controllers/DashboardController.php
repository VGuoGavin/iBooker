<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Booking;
use Illuminate\Support\Carbon;
use App\Announcement;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $upcoming =
            Booking::whereHas('details', function($query) {
                $query->where('booker_id', Auth::user()->id)
                      ->where('start_datetime', '>=', Carbon::now())
                      ->orderBy('start_datetime', 'asc');
            })->where('status', Booking::ACCEPTED_STATUS)->first();

        $pending =
            Booking::whereHas('details', function($query) {
                $query->where('booker_id', Auth::user()->id)
                      ->where('start_datetime', '>=', Carbon::now());
            })->where('status', '<>', Booking::ACCEPTED_STATUS)->count();

        $context_data = self::getContextData([
            'upcoming' => $upcoming,
            'pending_num' => $pending,
            'announcements' => Announcement::take(10)->get(),
        ]);
        return view('dashboard.index', $context_data);
    }

    /**
     * Get context data of requests
     *
     * @param array $payload
     * @return array
     */
    private static function getContextData($payload)
    {
        $context = [
            'title' => 'Dashboard',
            'active' => 'dashboard',
        ];
        foreach ($payload as $key => $value) {
            $context[$key] = $value;
        }
        return $context;
    }
}
