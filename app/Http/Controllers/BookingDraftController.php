<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Building;
use App\Room;
use App\BookingDraft;
use App\Facility;
use App\Booking;
use App\Signature;
use Carbon\Carbon;

class BookingDraftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $context = [
            'buildings' => Building::all(),
            'title' => 'Create new booking'
        ];
        $id = $request->input('r_id');
        if (isset($id)) {
            $current = Room::find($id);
            $context['current'] = $current;
        }
        return view('dashboard.draft.create', self::getContextData($context));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $draft = new BookingDraft;
        $draft->room_id = $request->input('room');
        $draft->purpose = $request->input('purpose');
        $draft->comments = $request->input('comment');
        $sdt = strtotime(str_replace('/', '-', $request->input('startDateTime')));
        $draft->start_datetime = $sdt ? $sdt : null;
        $edt = strtotime(str_replace('/', '-', $request->input('endDateTime')));
        $draft->end_datetime = $edt ? $edt : null;
        $draft->committed = false;
        $draft->booker_id = $request->user()->id;
        $draft->save();
        if($request->input('facility')) {
            $ids = array_keys($request->input('facility'));
            $facilities = Facility::find($ids);
            $draft->facilities()->attach($facilities, [
                'room_id' => $draft->room_id
            ]);
        }
        // return response()->json($draft, 200);
        return redirect()->action('BookingDraftController@show', ['id' => $draft->trimmed_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $draft = BookingDraft::where('booker_id', '=', Auth::user()->id)->findOrFail('BD-'.$id);
        if($draft->committed) {
            return redirect()->action('BookingController@show', ['id' => $draft->booking->trimmed_id]);
        } else {
            $context = [
                'title' => 'Draft '.$draft->id,
                'draft' => $draft,
            ];
            return view('dashboard.draft.show', self::getContextData($context));
        }
        // return response()->json(,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $draft = BookingDraft::where('booker_id', '=', Auth::user()->id)->findOrFail('BD-'.$id);
        $bookings = [];
        $r_id = $draft->room_id;
        if(isset($r_id)){
            $bookings = Booking::whereHas('details', function($query) use($r_id){
                $query->where('room_id', $r_id);
            })->with('details')->where('status', Booking::ACCEPTED_STATUS)->get();
        }
        $context = [
            'buildings' => Building::all(),
            'title' => 'Edit draft BD-'.$id,
            'draft' => $draft,
            'bookings' => $bookings,
        ];
        return view('dashboard.draft.edit', self::getContextData($context));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $draft = BookingDraft::where('booker_id', '=', Auth::user()->id)->findOrFail('BD-'.$id);
        $draft->room_id = $request->input('room');
        $draft->purpose = $request->input('purpose');
        $draft->comments = $request->input('comment');
        $sdt = strtotime(str_replace('/', '-', $request->input('startDateTime')));
        $draft->start_datetime = $sdt ? $sdt : null;
        $edt = strtotime(str_replace('/', '-', $request->input('endDateTime')));
        $draft->end_datetime = $edt ? $edt : null;
        $draft->committed = false;
        $draft->booker_id = $request->user()->id;
        $draft->save();

        $draft->bookedFacilities()->delete();
        if($request->input('facility')) {
            $ids = array_keys($request->input('facility'));
            $facilities = Facility::find($ids);
            $draft->facilities()->attach($facilities, [
                'room_id' => $draft->room_id
            ]);
        }
        // return response()->json($draft, 200);
        return redirect()->action('BookingDraftController@show', ['id' => $draft->trimmed_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $draft = BookingDraft::where('booker_id', '=', Auth::user()->id)->findOrFail('BD-'.$id);
        $draft->delete();
        return redirect()->action('BookingController@index');
    }

    /**
     * Commit a booking draft
     *
     * @param Request $request
     * @return Redirect
     */
    public function commit(Request $request, $id)
    {
        $draft = BookingDraft::where('booker_id', $request->user()->id)->findOrFail('BD-'.$id);
        $booking = $draft->booking;
        if(!isset($booking))
        {
            $draft->committed = true;
            $draft->committed_at = Carbon::now();
            $draft->save();

            $booking = new Booking;
            $draft->booking()->save($booking);

            $signature = new Signature;
            $signature->signee_id = $request->user()->id;
            $signature->booking_id = $booking->id;
            $signature->save();
        }

        return redirect()->action('BookingController@show', ['id' => $booking->trimmed_id]);
    }

    /**
     * Get context data of request
     *
     * @param array $payload
     * @return array\
     *
     */
    private static function getContextData($payload)
    {
        $context = [
            'title' => 'Bookings',
            'active' => 'bookings',
        ];
        if(isset($payload)) {
            foreach ($payload as $key => $value) {
                $context[$key] = $value;
            }
        }
        return $context;
    }
}
