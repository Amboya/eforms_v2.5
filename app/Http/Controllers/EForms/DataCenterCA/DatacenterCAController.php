<?php

namespace App\Http\Controllers\EForms\DataCenterCA;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EForms\DataCenterCA\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\EForms\DataCenterCA\DataCenterCAModel;
use App\Http\Requests\EForms\DataCenterCARequest;
use App\Models\Main\TotalsModel;
use App\Models\Main\AttachedFileModel;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\StatusModel;
use App\Models\User;



class DatacenterCAController extends Controller
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
    public function index( $value)
    {

        //get list of all petty cash forms for today
        if ($value == "all") {
            $list = DataCenterCAModel::all();
            $category = "All";
        } else if ($value == "critical") {
            $list = DataCenterCAModel::where('criticality', 'Critical'  )
                ->get();
            $category = "Critical";
        } else if ($value == config('constants.data_center_ca_status.new_submission')) {
            $list = DataCenterCAModel::where('status_id', config('constants.data_center_ca_status.new_submission'))
                ->get();
            $category = "New Application";
        } else if ($value == "very_critical") {
            $list = DataCenterCAModel::where('criticality', 'Very Critical'  )
                ->get();
            $category = "Very Critical";
        } else if ($value == config('constants.data_center_ca_status.reject_submission')) {
            $list = DataCenterCAModel::where('status_id', config('constants.data_center_ca_status.reject_submission'))
                ->get();
            $category = "Rejected";
        }


        //data to send to the view
        $params = [
            'list' => $list,
            'category' => $category,
        ];
        //return view
        return view('eforms.datacenter-ca.list')->with($params);

    }


    public function create()
    {
        //count all that needs me
        //$totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            //'totals_needs_me' => $totals_needs_me,
        ];

        return view('eforms.datacenter-ca.create')->with($params);
    }



    public function store(DataCenterCARequest $request)
    {
        $user = Auth::user();
        //generate random
        $code = self::randGenerator("DCCF");

        //insert new form into the database through the model
        $form = DataCenterCAModel::firstOrCreate(
            [
                'asset_name'  => $request->asset_name,
                'asset_category' => $request->asset_category,
                'rack_location' => $request->rack_location,
                'criticality' => $request->criticality,
                'physical_location' => $request->physical_location,
                'status_id' => config('constants.data_center_ca_status.new_submission'),
            ],
            [
                'code' => $code,
                'asset_name'  => $request->asset_name,
                'asset_category' => $request->asset_category,
                'rack_location' => $request->rack_location,
                'criticality' => $request->criticality,
                'physical_location' => $request->physical_location,
                'status_id' => config('constants.data_center_ca_status.new_submission'),
                'created_by' => $user->id,

                'staff_name' => $request->claimant_name,
                'staff_no' => $request->sig_of_claimant,
                'submitted_date' => $request->date_claimant,

                'profile' => Auth::user()->profile_id,

            ]);

        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.datacenter_ca'))
            ->where('id', config('constants.totals.data_center_ca_new'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();

        //e-form model totals update
        $eform_model = EFormModel::find(config('constants.eforms_id.datacenter_ca'));
        $eform_model->total_new = $totals->value;
        $eform_model->total_pending = $totals->value;
        $eform_model->save();

        //log the activity
        ActivityLogsController::store($request, "Creating of".config('constants.eforms_name.datacenter_ca'), "update", config('constants.eforms_name.datacenter_ca')." created", $form->id);
        // return the view
        return Redirect::route('datacenter-ca-home')->with('message', config('constants.eforms_name.datacenter_ca').' Details for ' . $form->code . ' have been Created successfully');
    }


    public function randGenerator($head)
    {
        //random number
        $random = rand(1, 9999999);
        $random = sprintf("%07d", $random);
        $random = $head . $random;
        $check = DataCenterCAModel::where('code', $random)->get();

        if ($check->isEmpty()) {
            return $random;
        } else {
            self::randGenerator($head);
        }
    }





    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //find the form with that id
        $form = DataCenterCAModel::find($id);

        //data to send to the view
        $params = [
            'form' => $form,
        ];

        //return view
        return view('eforms.datacenter-ca.show')->with($params);

    }

    public function approve(Request  $request)
    {
    }



    }
