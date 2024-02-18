<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MakeSignature extends Controller
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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('dashboard.sign', self::getContextData([]));
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
            'title' => 'Sign',
            'active' => 'sign',
        ];
        foreach ($payload as $key => $value) {
            $context[$key] = $value;
        }
        return $context;
    }
}
