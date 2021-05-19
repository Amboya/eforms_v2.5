<?php

namespace App\Http\Controllers\LoginAPI;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\LoginAPI\ClientSystem;
use App\Models\PhrisUserDetailsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ClientSystemController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.main_dashboard')]);
        session(['eform_code' => config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //get all the categories
        $list = ClientSystem::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];
        //return with the data
        return view('login_api.index')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $access_key = base64_encode(random_bytes(64) . "nimbus");
        $status_id = config('constants.active_state');

        $model = ClientSystem::firstOrCreate(
            [
                'name' => $request->name,
                'url' => $request->url,
                'ip_address' => $request->ip_address,
                'status_id' => $status_id,
            ],
            [
                'name' => $request->name,
                'url' => $request->url,
                'ip_address' => $request->ip_address,
                'access_key' => $access_key,
                'status_id' => $status_id,
                'created_by' => $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of Client System", "create", "client system created", json_encode($model));
        return Redirect::back()->with('message', 'Created successfully');
    }

    public function update(Request $request)
    {
        $model = ClientSystem::find($request->id);
        $model->name = $request->name;
        $model->url = $request->url;
        $model->ip_address = $request->ip_address;
        $model->status_id = $request->status_id;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of Client System", "create", "client system updated", json_encode($model));
        return Redirect::back()->with('message', 'Updated successfully');
    }


    public function updateKey(Request $request)
    {
        $access_key = base64_encode(random_bytes(64) . "nimbus");
        $model = ClientSystem::find($request->id);
        $model->access_key = $access_key ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of Client System Key", "create", "client system key updated", json_encode($model));
        return Redirect::back()->with('message', 'Updated successfully');
    }


    public function LoginVerify(Request $request)
    {
        //get
        $man_no   = $request->man_no ;
        $password = $request->password ;

        //check the user details from phris
        $user = PhrisUserDetailsModel::where('con_per_no', $man_no )
            ->where('con_st_code', config('constants.phris_user_active'));
        //check
        if($user->exists()){
            $login_user = User::where('staff_no', $man_no )
                ->where('con_st_code', config('constants.phris_user_active'));
            if($login_user->exists()){
                $login_user = $login_user->first();
                //compare the passwords
                if (Hash::check($password, $login_user->password))
                {
                    $params = [
                        'error' => 'false',
                        'msg'=> $login_user
                    ];
                    return response()->json($params);
                }else{
                    $params = [
                        'error' => 'true',
                        'msg'=> 'user password did not match with our records'
                    ];
                    return response()->json($params);
                }

            }else{
                $params = [
                    'error' => 'true',
                    'msg'=> 'user with this man number could not be found'
                ];
                return response()->json($params);
            }
        }else{
            $params = [
                'error' => 'true',
                'msg'=> 'user with this man number could not be found'
            ];
            return response()->json($params);
        }
    }


}
