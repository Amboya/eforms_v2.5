<?php

namespace App\Http\Middleware;

use App\Models\LoginAPI\ClientSystem;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class VerifyClientAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //get
        $access_key = $request->access_key ;
        //check system with that access key exists
        $system = ClientSystem::where('access_key',$access_key )->where('status_id', config('constants.active_state'));
        if($system->count() == 1){
            return $next($request);
        }else{
            $params = [
                'error' => 'true',
                'msg'=> 'Yangu-tata eh! Access Key maybe wrong or deactivated'
            ];
            return response()->json($params);
        }

    }
}
