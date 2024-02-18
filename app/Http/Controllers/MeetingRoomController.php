<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; // 假设你有一个 Reservation 模型来管理预约

class MeetingRoomController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        return view('meeting-room.index', compact('reservations'));
    }

    public function create()
    {
        return view('meeting-room.create');
    }

    public function store(Request $request)
    {
        // dd($request->all()); 
        // // 在实际应用中，你需要添加验证规则和其他安全性措施
        Reservation::insert([
            'room_name' => $request->room_name,
            'user_name' => $request->user_name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect('/meetingroom')->with('success', '预约成功！');
    }
}
