<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ActivityLogsModel;
use App\Models\Main\SystemErrorModel;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use hisorange\BrowserDetect\Parser as Browser;

class ActivityLogsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.main_dashboard') ]);
        session(['eform_code'=> config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get all logs
        $list = ActivityLogsModel::orderBy('created_at', 'desc')->get();
        // dd($certs);
        $params = [
            'list' => $list
        ];

        self::store( $request, "list logs","view", "checking the list of activity logs", "");

        return view('main.logs.index')->with($params);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request
     * @param $action
     * @param $action_type
     * @param $comment
     * @param $meta_data
     */
    public static function store( $request, $action,$action_type, $comment, $meta_data)
    {
        try {
            //BROWSER
            if (Browser::isMobile()) {
                $device_type = "Mobile";
            }
            if (Browser::isTablet()) {
                $device_type = "Tablet";
            }
            if (Browser::isDesktop()) {
                $device_type = "Desktop / Laptop";
            }
            if (Browser::isBot()) {
                $device_type = "Bot";
            }
            $device = Browser::deviceFamily();
            $os = Browser::platformName();
            $os_version = Browser::platformVersion();
            $browser = Browser::browserName();
            $browser_version = Browser::browserVersion();

            //get ip
            $ip_address = $request->getClientIp();
            //GEO DATA
            $Location = GeoIP::getLocation($ip_address);

            //CREATE THE LOG
            $activity_log = ActivityLogsModel::create([
                //user
                'user_id'=> $request->user()->id,
                'staff_no' => $request->user()->staff_no,
                'staff_profile' => $request->user()->profile,
                'username'=> $request->user()->name,
                'user_email' => $request->user()->email,

                'eform_code' => $request->session()->get('eform_code'),
                'eform_id' => $request->session()->get('eform_id'),

                //request
                'ip_address' => $ip_address,
                'request_method' => $request->method(),
                'request_params' => json_encode($request->all()),
                'route_url' => $request->url(),
                'previous_url' => $request->session()->previousUrl(),
                //action
                'action_name' => $action,
                'action_type' => $action_type,
                'comment' => $comment,
                'meta_data' => $meta_data,
                //device
                'device' => $device,
                'device_type' => $device_type,
                'os' => $os,
                'os_version' => $os_version,
                'browser' => $browser,
                'browser_version' => $browser_version,
                //loc
                'iso_code' => $Location->iso_code,
                'country' => $Location->country,
                'city' => $Location->city,
                'state' => $Location->state,
                'state_name' => $Location->state_name,
                'postal_code' => $Location->postal_code,
                'latitude' => $Location->lat,
                'longitude' => $Location->lon,
                'timezone' => $Location->timezone,
                'continent' => $Location->continent,
                'currency' => $Location->currency,
                'value' => $Location->iso_code,
            ]);

        } catch (Exception $exe) {
            //save system errors
            SystemErrorModel::create([
                'class' => 'ActivityLogsController',
                'function' => 'addLog',
                'msg' => $exe->getMessage(),
                'type' => 'medium',
                'user' => "system",
            ]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = ActivityLogsModel::find($id);
        $logs = ActivityLogsModel::where('ip_address',$log->ip_address)->get();
        $params = [
            'activity_log' => $log ,
            'activity_logs' => $logs
        ];

        return view('main.logs.view')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Add logs by the system
     */
    public function addLog( )
    {
        try {

            $request = Request::capture();
            //BROWSER
            if (Browser::isMobile()) {
                $device_type = "Mobile";
            }
            if (Browser::isTablet()) {
                $device_type = "Tablet";
            }
            if (Browser::isDesktop()) {
                $device_type = "Desktop / Laptop";
            }
            if (Browser::isBot()) {
                $device_type = "Bot";
            }
            $device = Browser::deviceFamily();
            $os = Browser::platformName();
            $os_version = Browser::platformVersion();
            $browser = Browser::browserName();
            $browser_version = Browser::browserVersion();

            //get ip
            $ip_address = $request->getClientIp();
            //GEO DATA
            $Location = GeoIP::getLocation($ip_address);

            //CREATE THE LOG
            $activity_log = ActivityLogsModel::create([

                //user
                'user_id'=> 0,
                'staff_no' =>  "system",
                'staff_profile' =>  "system",
                'username'=>  "system",
                'user_email' =>  "system",

                'eform_code' => $request->session()->get('eform_code'),
                'eform_id' => $request->session()->get('eform_id'),

                //request
                'ip_address' => $ip_address,
                'request_method' => $request->method(),
                'request_params' => json_encode($request->all()),
                'route_url' => "system function",
                'previous_url' => "system function",
                //action
                'action_name',
                'action_type',
                'comment',
                'meta_data',
                //device
                'device' => $device,
                'device_type' => $device_type,
                'os' => $os,
                'os_version' => $os_version,
                'browser' => $browser,
                'browser_version' => $browser_version,
                //loc
                'iso_code' => $Location->iso_code,
                'country' => $Location->country,
                'city' => $Location->city,
                'state' => $Location->state,
                'state_name' => $Location->state_name,
                'postal_code' => $Location->postal_code,
                'latitude' => $Location->lat,
                'longitude' => $Location->lon,
                'timezone' => $Location->timezone,
                'continent' => $Location->continent,
                'currency' => $Location->currency,
                'value' => $Location->iso_code,
            ]);

        } catch (Exception $exe) {
            //save system errors
            SystemErrorModel::create([
                'class' => 'ActivityLogsController',
                'function' => 'addLog',
                'msg' => $exe->getMessage(),
                'type' => 'medium',
                'user' => "system",
            ]);
        }

    }


    /**
     * Show logs that belong to a particular user based on user id
     *
     * @param $id
     * @return mixed
     */
    public function myLogs($id){
        //brings all for logs based on user id
        $logs = ActivityLogsModel::where('user_id',$id)->get();
        $params = [
            'activity_logs' => $logs
        ];
        return view('pages.modules.logs.list')->with($params);
    }



}
