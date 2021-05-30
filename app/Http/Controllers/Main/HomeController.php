<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DepartmentModel;
use App\Models\Main\EFormCategoryModel;
use App\Models\Main\EFormModel;
use App\Models\main\TotalsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        session(['eform_id' => config('constants.eforms_id.main_dashboard') ]);
        session(['eform_code'=> config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //get the list of categories
        $categories = EFormCategoryModel::all();
        //get list of all eforms
        $eforms = EFormModel::all();
        //count all
        $totals= TotalsModel::where('eform_id', config('constants.eforms_id.main_dashboard'))->get();
        //data to send to the view

        $user_unit = ConfigWorkFlow::select('*')->orderBy('user_unit_code')->get();
        $params = [
            'eforms' => $eforms,
            'categories' => $categories,
            'totals' => $totals,
            'user_unit' => $user_unit
        ];

        //return view
        return view('main.dashboard')->with($params);

    }




}
