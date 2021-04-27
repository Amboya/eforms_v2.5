<?php

namespace App\Http\Controllers\EForms\DataCenterCA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EForms\DataCenterCA\DataCenterCAModel;
use App\Models\main\StatusModel;

class HomeController extends Controller
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
        session(['eform_id' => config('constants.eforms_id.datacenter_ca')]);
        session(['eform_code' => config('constants.eforms_name.datacenter_ca')]);

    }


    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //get the latest 5 entries
        $list  = DataCenterCAModel::whereDate('created_at', \Carbon::today() )->get();

        //count new forms
        $new_forms  = DataCenterCAModel::whereDate('created_at', \Carbon::today() )
            ->count();
        //count all forms
        $all_forms  = DataCenterCAModel::all()
            ->count();
        //count very critical forms
        $very_critical  = DataCenterCAModel::where('criticality', 'Very Critical'  )
            ->count();
        //count critical forms
        $critical  = DataCenterCAModel::where('criticality', 'Critical' )
            ->count();

        //add to totals
        $totals['new_forms'] = $new_forms;
        $totals['all_forms'] = $all_forms;
        $totals['critical'] = $critical;
        $totals['very_critical'] = $very_critical;
        //data to send to the view
        $params = [
            'totals' => $totals,
            'list' => $list,
        ];
        //return view
        return view('eforms.datacenter-ca.dashboard')->with($params);

    }


}
