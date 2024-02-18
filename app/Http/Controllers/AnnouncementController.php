<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Announcement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnnouncementController extends Controller
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
        $context = [
            'drafts' =>
                Announcement::where('announcer_id', Auth::user()->id)
                            ->where('posted', false)
                            ->get(),
            'announcements' =>
                Announcement::where('posted', true)
                            ->where('expired_at', '>=', Carbon::now())
                            ->get(),

        ];
        return view('dashboard.announcement.index', self::getContextData($context));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $context = [
            'title' => 'Create announcement'
        ];
        return view('dashboard.announcement.create', self::getContextData($context));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $expiry = $request->input('expiry');
        $content = $request->input('content');

        $announcement = new Announcement;
        $announcement->title = $title;
        $announcement->expiry = $expiry;
        $announcement->content = $content;
        $announcement->announcer_id = $request->user()->id;
        $announcement->save();

        return redirect()->action('AnnouncementController@show', ['id' => $announcement->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        $context = [
            'title' => $announcement->title,
            'announcement' => $announcement,
        ];
        return view('dashboard.announcement.show', self::getContextData($context));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $draft = Announcement::where('announcer_id', Auth::user()->id)->findOrFail($id);
        $context = [
            'title' => 'Edit announcement',
            'draft' => $draft,
        ];
        return view('dashboard.announcement.edit', self::getContextData($context));
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
        $title = $request->input('title');
        $expiry = $request->input('expiry');
        $content = $request->input('content');

        $announcement = Announcement::where('announcer_id', $request->user()->id)->findOrFail($id);
        $announcement->title = $title;
        $announcement->expiry = $expiry;
        $announcement->content = $content;
        $announcement->announcer_id = $request->user()->id;
        $announcement->save();

        return redirect()->action('AnnouncementController@show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $announcement = Announcement::where('announcer_id', Auth::user()->id)->findOrFail($id);
        $announcement->delete();
        return redirect()->action('AnnouncementController@index');
    }

    public function post(Request $request, $id)
    {
        $announcement = Announcement::where('announcer_id', $request->user()->id)->findOrFail($id);
        $announcement->posted = true;
        $announcement->posted_at = Carbon::now();
        switch ($announcement->expiry) {
            case Announcement::ONE_HOUR_EXPIRY:
                $announcement->expired_at = Carbon::now()->addHour(1);
                break;
            case Announcement::ONE_DAY_EXPIRY:
                $announcement->expired_at = Carbon::now()->addDay(1);
                break;
            case Announcement::ONE_WEEK_EXPIRY:
                $announcement->expired_at = Carbon::now()->addWeek(1);
                break;
            case Announcement::ONE_MONTH_EXPIRY:
                $announcement->expired_at = Carbon::now()->addMonth(1);
                break;
            default:
                $announcement->expired_at = Carbon::now()->addYear(1);
                break;
        }
        $announcement->save();

        return redirect()->action('AnnouncementController@show', ['id' => $id]);
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
            'title' => 'Announcements',
            'active' => 'announcements',
        ];
        foreach ($payload as $key => $value) {
            $context[$key] = $value;
        }
        return $context;
    }
}
