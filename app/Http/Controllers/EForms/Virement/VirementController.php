<?php

namespace App\Http\Controllers\Eforms\Virement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EForms\Virement\VirementModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Http\Controllers\EForms\Virement\HomeController;
use App\Models\main\TotalsModel;
use Illuminate\Support\Facades\Auth;

class VirementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.virement')]);
        session(['eform_code' => config('constants.eforms_name.virement')]);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
        ];

        return view('eforms.virement.create')->with($params);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $value)
    {

        //get list of all petty cash forms for today
        if ($value == "all") {
            $list = VirementModel::all();
            $category = "All";
        } else if ($value == "pending") {
            $list = VirementModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
                ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.petty_cash_status.new_application')) {
            $list = VirementModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.petty_cash_status.closed')) {
            $list = VirementModel::where('config_status_id', config('constants.petty_cash_status.closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.petty_cash_status.rejected')) {
            $list = VirementModel::where('config_status_id', config('constants.petty_cash_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        }


        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'category' => $category,
        ];
        //return view
        return view('eforms.virement.list')->with($params);

    }


    public function store(Request $request)
    {
        dd($request->all());
    }


    public function reports(Request $request)
    {
        dd($request->all());
    }




}
