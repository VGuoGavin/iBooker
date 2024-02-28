<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\Controller;
use App\Room;
use App\Booking;
use App\Signature;
use Carbon\Carbon;

class APIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Return rooms in building as json
     *
     * @param Request $request
     * @return string
     */
    public function roomsInBuilding(Request $request)
    {
        //dd($request);
        $b_id = $request->input('b_id');
        return response()->json(Room::where('building_id', $b_id)->orderBy('name', 'asc')->get(), 200);
    }

    public function roomDetail(Request $request)
    {
        $r_id = $request->input('r_id');
        $room = Room::findOrFail($r_id);
        $room['facilities'] = $room->facilities;
        return response()->json($room, 200);
    }

    public function roomBookings(Request $request)
    {
        $id = $request->input('r_id');
        $bookings = Booking::whereHas('details', function($query) use($id){
            $query->where('room_id', $id);
        })->with('details')->where('status', Booking::ACCEPTED_STATUS)->get();
        return response()->json($bookings, 200);
    }

    public function generateAccessCode(Request $request)
    {
        //dd($request);
        $user_id = $request->user()->id;
        $booking_id = $request->input('bid');
        $booking = Booking::whereHas('details', function($query) use($user_id) {
            $query->where('booker_id', $user_id);
        })->findOrFail($booking_id);
        
        // 定义要用于生成随机字符串的字符集
        $chars = "ABCDEFGHJKLMNPQRSTUVWXY3456789";
        // 生成随机字符串
        $access_code = substr(str_shuffle($chars), 0, 6);
        // 将生成的随机字符串赋值给 $booking->access_code
        $booking->access_code = $access_code;
        $booking->code_expiry = Carbon::now()->addHour();
        $booking->save();

        return response()->json([
            'code' => $booking->access_code,
            'expiry' => $booking->code_expiry->timestamp,
        ], 200);
    }

    public function accessBooking(Request $request)
    {
        if(!$request->user()->is_authority) {
            return response('Unauthorized', 403);
        } else {
            $code = $request->input('code');
            $booking = Booking::where('access_code', $code)
                            ->where('code_expiry', '>=', Carbon::now())
                            ->where('status', Booking::INCOMPLETE_STATUS)
                            ->with('details')
                            ->firstOrFail();
            $booking['details']['start'] = $booking->details->start_datetime;
            $booking['details']['end'] = $booking->details->end_datetime;
            $booking['details']['facilities'] = $booking->details->facilities;
            $booking['details']['room'] = $booking->details->room;
            $booking['details']['building'] = $booking->details->room->building;
            return response()->json($booking, 200);
        }
    }

    public function sign(Request $request)
    {
        if(!$request->user()->is_authority) {
            return response('Unauthorized', 403);
        } else {
            $bid = $request->input('bid');
            $booking = Booking::findOrFail($bid);
            $booking->status = Booking::ACKNOWLEDGED_STATUS;
            $booking->status_changed_at = Carbon::now();
            $booking->save();

            $signature = new Signature;
            $signature->signee_id = $request->user()->id;
            $signature->booking_id = $booking->id;
            $signature->save();

            return response()->json([
                'status' => intval($signature->is_valid)
            ], 200);
        }
    }

    public function checkSignature(Request $request)
    {
        $sgn = base64_decode($request->input('sgn'));
        $sid = base64_decode($request->input('sid'));
        $msg = base64_decode($request->input('msg'));
        $pub = User::findOrFail($sid)->public_key;
        return response()->json([
            'valid' => sodium_crypto_sign_verify_detached($sgn, $msg, $pub),
        ], 200);
    }

    public function bookingStatus(Request $request)
    {
        $user_id = $request->user()->id;
        $booking_id = $request->input('bid');
        $booking = Booking::whereHas('details', function($query) use($user_id) {
            $query->where('booker_id', $user_id);
        })->findOrFail($booking_id);
        return response()->json($booking->status, 200);
    }

    public function accept(Request $request)
    {
        if(!$request->user()->is_admin) {
            return response('Unauthorized', 403);
        } else {
            $bid = $request->input('bid');
            $msg = $request->input('msg');
            $booking = Booking::findOrFail($bid);
            $booking->status = Booking::ACCEPTED_STATUS;
            $booking->status_changed_at = Carbon::now();
            $booking->admin_message = $msg;
            $booking->save();

            $signature = new Signature;
            $signature->signee_id = $request->user()->id;
            $signature->booking_id = $booking->id;
            $signature->save();

            return response()->json([
                'status' => intval($signature->is_valid)
            ], 200);
        }
    }

    public function reject(Request $request)
    {
        if(!$request->user()->is_admin) {
            return response('Unauthorized', 403);
        } else {
            $bid = $request->input('bid');
            $msg = $request->input('msg');
            $booking = Booking::findOrFail($bid);
            $booking->status = Booking::REJECTED_STATUS;
            $booking->status_changed_at = Carbon::now();
            $booking->admin_message = $msg;
            $booking->save();

            $signature = new Signature;
            $signature->signee_id = $request->user()->id;
            $signature->booking_id = $booking->id;
            $signature->save();

            return response()->json([
                'status' => intval($signature->is_valid)
            ], 200);
        }
    }

    /**
     * Generate random string
     *
     * @param string $chars
     * @param int $length
     * @param boolean $unique
     * @return string
     */
    private static function rand_chars($chars, $length, $unique = FALSE) {
        // if (!$unique) {
        //     // If $unique is false, generate a random string of $length characters
        //     // by iterating $length times and appending a random character from $chars each time
        //     for ($s = '', $i = 0, $z = strlen($chars)-1; $i < $length; $x = random_int(0,$z), $s .= $chars{$x}, $i++);
        // } else {
        //     // If $unique is true, generate a random string of $length characters
        //     // by ensuring that no two consecutive characters are the same
        //     for ($i = 0, $z = strlen($chars)-1, $s = $chars{random_int(0,$z)}, $i = 1; $i != $length; $x = random_int(0,$z), $s .= $chars{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
        // }
        // return $s;
    }
    
}
