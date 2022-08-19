<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\AttachedFileModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EFormCategoryModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        session(['eform_id' => config('constants.eforms_id.main_dashboard')]);
        session(['eform_code' => config('constants.eforms_name.main_dashboard')]);
    }

    public static function getUserResponsibleUnits(User $user)
    {
        // get your user units
        $my_profiles = ProfileAssigmentModel::where('user_id', $user->id)->get();
        $delegation = ProfileDelegatedModel::where('delegated_to', $user->id)->where('config_status_id', config('constants.active_state'))->get();


        $user_units = [];

        //check assigned
        if ($my_profiles->count() > 0) {

            $has_units = true;
            $my_profiles = $my_profiles->load('profiles');
            // dd($my_profiles);
            foreach ($my_profiles->pluck('profiles')->unique() as $key => $item) {

                if ($key != 0) {
                    $user_units_1 = ConfigWorkFlow::
                    where($item->code_column, $user->job_code)
                        ->where($item->unit_column, $user->user_unit_code)
                        ->get();
                    $user_units->merge($user_units_1);
                } else {
                    $user_units = ConfigWorkFlow::
                    where($item->code_column, $user->job_code)
                        ->where($item->unit_column, $user->user_unit_code)
                        ->get();
                }
            }
        }

        // check delegations
        if ($delegation->count() > 0) {

            $delegation->load('profile');
            foreach ($delegation as $key => $item) {

                if ($key != 0) {
                    $user_units->merge(ConfigWorkFlow::
                    where($item->profile->code_column, $item->delegated_job_code)
                        ->where($item->profile->unit_column, $item->delegated_user_unit)
                        ->get());

                } else {
                    if (sizeof($user_units) > 0) {
                        $user_units->merge(ConfigWorkFlow::
                        where($item->profile->code_column, $item->delegated_job_code)
                            ->where($item->profile->unit_column, $item->delegated_user_unit)
                            ->get());
                    } else {
                        $user_units = ConfigWorkFlow::
                        where($item->profile->code_column, $item->delegated_job_code)
                            ->where($item->profile->unit_column, $item->delegated_user_unit)
                            ->get();

                    }
                }

            }
        }

        //return
        return $user_units;

    }

    public static function getMyProfile($eform_id)
    {

        $code_column = 'id' ;
        $unit_column = 'user_unit_code' ;

        if (auth()->check()) {
            //get the profile associated with
            $user = auth()->user();

            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('eform_id', $eform_id)
                ->where('user_id', $user->id)->first();

            if ($profile_assignement != null) {

                //here we have a profile
                $profile_assignement->load('profiles');


                $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $user->user_unit_code;
                $user->profile_job_code = $user->job_code;
                $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                $user->unit_column = $profile_assignement->profiles->unit_column ?? 'user_unit_code';
                $code_column = $profile_assignement->profiles->code_column ?? 'id';
                $unit_column = $profile_assignement->profiles->unit_column ?? 'user_unit_code';
               // $user->save();

            } else {
                //here the default is claimant
                $default_profile = config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $user->user_unit_code;
                $user->profile_job_code = $user->id;
                $user->code_column = 'id';
                $user->unit_column = 'user_unit_code';
            }


            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::
            where('eform_id', $eform_id)
                ->where('delegated_to', $user->id)
                ->where('config_status_id', config('constants.active_state'));

            if ($profile_delegated->exists()) {

//                dd($profile_delegated->first()->profile->code_column );
                //
                $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');

                $user->profile_id = $default_profile;
                $user->profile_id_delegated = $default_profile;
                $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ;
                $user->profile_job_code = $profile_delegated->first()->delegated_job_code ;
                $user->code_column = $profile_delegated->first()->delegated_code_column ;
                $user->unit_column = $profile_delegated->first()->delegated_unit_column ;
                $code_column = $profile_delegated->first()->delegated_code_column ?? $profile_delegated->first()->profile->code_column ;
                $unit_column = $profile_delegated->first()->delegated_unit_column ?? $profile_delegated->first()->profile->unit_column ;

            }

            $user->code_column = $code_column ;
            $user->unit_column = $unit_column ;

            $user->save();


            //for security, auditor and expenditure
            if ($user->profile_id == config('constants.user_profiles.EZESCO_014')
                || $user->profile_id == config('constants.user_profiles.EZESCO_013')
                || $user->profile_id == config('constants.user_profiles.EZESCO_011')) {
                $my_user_units = ConfigWorkFlow::where($user->unit_column , $user->profile_unit_code)
                    ->orderBy('user_unit_description')
                    ->get();
            }
            else {

                $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
                    ->where($user->code_column, $user->profile_job_code)
                    ->orderBy('user_unit_description')
                    ->get();

            }

            return $my_user_units;

        }
    }

    public static function getUserProfile(User $user)
    {

        if (auth()->check()) {
            //get the profile associated with
            $pro = ProfileModel::find($user->profile_id);


            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('profile', $pro->code)
                ->where('user_id', $user->id)->first();

            if ($profile_assignement != null) {
                $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
                if ($default_profile == config('constants.user_profiles.EZESCO_002')) {
                    $user->profile_id = $default_profile;
                    $user->profile_unit_code = $user->user_unit_id;
                    $user->profile_job_code = $user->user_unit_code;
                    $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                    $user->unit_column = $profile_assignement->profiles->unit_column ?? 'user_unit_code';
                } else {
                    $user->profile_id = $default_profile;
                    $user->profile_unit_code = $user->user_unit_code;
                    $user->profile_job_code = $user->job_code;
                    $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                    $user->unit_column = $profile_assignement->profiles->unit_column ?? 'user_unit_code';
                }


            } else {
                $default_profile = config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $user->user_unit_code;
                $user->profile_job_code = $user->id;
                $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                $user->unit_column = $profile_assignement->profiles->unit_column ?? 'user_unit_code';
            }

            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::
            where('delegated_profile', $pro->code)
                ->where('delegated_to', $user->id)
                ->where('config_status_id', config('constants.active_state'));
            if ($profile_delegated->exists()) {
                //
                $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
                $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
                $user->code_column = $profile_delegated->first()->profile->code_column ?? 'id';
                $user->unit_column = $profile_delegated->first()->profile->unit_column ?? 'user_unit_code';

            }
            $user->save();

            //for security, auditor and expenditure
            if ($user->profile_id == config('constants.user_profiles.EZESCO_014')
                || $user->profile_id == config('constants.user_profiles.EZESCO_013')
                || $user->profile_id == config('constants.user_profiles.EZESCO_011')) {
                $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
                    ->where('user_unit_cc_code', '!=', '0')
                    ->orderBy('user_unit_description')
                    ->get();
            } else {
                $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
                    ->where($user->code_column, $user->profile_job_code)
                    ->where('user_unit_cc_code', '!=', '0')
                    ->orderBy('user_unit_description')
                    ->get();
            }

            return $my_user_units;

        }
    }

    public static function getMyViisbleUserUnitsProcessed()
    {
        //get the profile associated
        $user = auth()->user();
        $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->orderBy('user_unit_description')
            ->get();
        $myUnits = $my_user_units->pluck('user_unit_code')->unique();
        return $myUnits->toArray();
    }

    public static function getMyViisbleUserUnits()
    {
        //get the profile associated
        $user = auth()->user();
        $my_user_units = ConfigWorkFlow::
        where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->orderBy('user_unit_description')
            ->get();
        return $my_user_units;
    }

    public static function getMyViisbleDirectorates()
    {
        //get the profile associated
        $user = auth()->user();
        $my_user_units = ConfigSystemWorkFlow::select('directorate_id', 'directorate_name')
            ->where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->groupBy('directorate_id', 'directorate_name')
            ->get();
        return $my_user_units;
    }

    public static function getMySuperior_old($user_unit_code, ProfileModel $profile)
    {

        //
        $user_unit_code = ConfigWorkFlow:: select($profile->code_column . ' as code_column', $profile->unit_column . ' as unit_column')
            ->where('user_unit_code', $user_unit_code)
            ->first();

        //users with the profile code
        $users = User::
        where('profile_job_code', $user_unit_code->code_column)
            ->where('profile_unit_code', $user_unit_code->unit_column)
            ->where('con_st_code', config('constants.phris_user_active'))
            ->get();

//        $users = User::
//        where('user_unit_code', $user_unit_code->unit_column)
//            ->where('job_code', $user_unit_code->code_column)
//            ->where('con_st_code', config('constants.phris_user_active') )
//            ->get();

        if (sizeof($users) < 1) {
            $users = User::
            where('user_unit_code', $user_unit_code->unit_column)
                ->where('job_code', $user_unit_code->code_column)
                ->where('con_st_code', config('constants.phris_user_active'))
                ->get();
        } else {
            $users_1 = User::
            where('user_unit_code', $user_unit_code->unit_column)
                ->where('job_code', $user_unit_code->code_column)
                ->where('con_st_code', config('constants.phris_user_active'))
                ->get();
            $users->merge($users_1);
        }

        //  dd(compact('user_unit_code', 'users'));

        return $users;
    }

    /**
     * Show the main application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {

        //give the user a subsistence profile equal to the one they have in petty cash

        $user = auth()->user();

     //   dd($user);

        //[1]  get sub profile
        $profile_assignement = ProfileAssigmentModel::
        where('eform_id', 41)
            ->where('user_id', $user->id)->first();
        //get petty cash profile

        if ($profile_assignement == null) {
            $profile_assignement2 = ProfileAssigmentModel::

                where('user_id', $user->id)->first();


            if($profile_assignement2 != null){
                //create subsitence profile
                $model = ProfileAssigmentModel::UpdateOrCreate(
                    [
                        "eform_id" => 41,
                        "profile" => $profile_assignement2->profile ,
                        "user_id" => $profile_assignement2->user_id,
                        "created_by" => $profile_assignement2->created_by,
                        "profile_id" => $profile_assignement2->profile_id,
                    ],
                    [
                        "eform_id" => 41,
                        "profile" => $profile_assignement2->profile,
                        "user_id" => $profile_assignement2->user_id,
                        "created_by" => $profile_assignement2->created_by,
                        "profile_id" => $profile_assignement2->profile_id,
                    ]
                );

            }


        }


        $categories = EFormCategoryModel::all();
        $categories->load('eforms');
        //return view
        return view('main.dashboard')->with(compact('categories'));
    }

    public function getMySuperiorAPI($user_unit_code, $profile)
    {
        $profile = ProfileModel::find($profile);
        $users = self::getMySuperior($user_unit_code, $profile);
        // dd($users->toArray());
        return json_encode($users->toArray());

    }

    public static function getMySuperior($user_unit_code, ProfileModel $profile)
    {

        $user_unit_code = ConfigWorkFlow:: where('user_unit_code', $user_unit_code)
            ->first();

        $user_unit_code->load($profile->unit_column . '_user', $profile->unit_column . '_delegate_user');
        $users = $user_unit_code[$profile->unit_column . '_user'];
        $delegated = $user_unit_code[$profile->unit_column . '_delegate_user'];
        $users = $users->merge($delegated);

       // dd($user_unit_code);


        //check for the profile
        $users = $users->where('code_column' , $profile->code_column )
            ->where('unit_column', $profile->unit_column );



//        "code_column" => "hrm_code"
//    "unit_column" => "hrm_unit"//

//        $user_unit_code->load( $profile->unit_column . '_delegate_user');
//        $delegated = $user_unit_code[$profile->unit_column . '_delegate_user'];

//        dd($users);
       // $users->merge($delegated);


        return $users;
    }

    public function getManySuperiorAPI(Request $request, $profile)
    {
        $user_unit_codes = $request->array;
        $profile = ProfileModel::find($profile);
        foreach ($user_unit_codes as $key => $user_unit_code) {

            if ($key != 0) {
                $users = $users->merge(self::getMySuperior($user_unit_code, $profile));
            } else {
                $users = self::getMySuperior($user_unit_code, $profile);
            }
        }
        return json_encode($users->toArray());
    }

    public function changeFile(Request $request)
    {

        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('change_file')) {
            $file = $request->file('change_file');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path, $fileNameToStore);

            //update
            $model = AttachedFileModel::find($request->id);
            //unlink the old one
            $old_name = $model->name;

//          $fast = $request->path.''.$old_name;
//          unlink(storage_path($fasdf));

            $model->name = $fileNameToStore;
            $model->extension = $extension;
            $model->file_size = $size;
            $model->location = $path;
            $model->save();
        }
        return Redirect::route('main.home')->with('message', "File Has Been Updated Successfully");

    }

    public function addFile(Request $request)
    {
        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('add_file1')) {

            $file = $request->file('add_file1');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path1, $fileNameToStore);

            //add the receipt record
            $file = AttachedFileModel::updateOrCreate(
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id1,
                    'form_type' => $request->form_type1,
                    'file_type' => $request->file_type1
                ],
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id1,
                    'form_type' => $request->form_type1,
                    'file_type' => $request->file_type1
                ]
            );

            //test -
            return Redirect::route('main.home')->with('message', "File Has Been Added Successfully");

        }
        return Redirect::route('main.home')->with('error', "File failed to upload");

    }

    public function addFile2(Request $request)
    {
        /** upload quotation files */
        // upload the receipt files
        if ($request->hasFile('add_file2')) {

            $file = $request->file('add_file2');
            $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = $file->getSize() * 0.000001;
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs($request->path2, $fileNameToStore);

            //add the receipt record
            $file = AttachedFileModel::updateOrCreate(
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id2,
                    'form_type' => $request->form_type2,
                    'file_type' => $request->file_type2
                ],
                [
                    'name' => $fileNameToStore,
                    'location' => $path,
                    'extension' => $extension,
                    'file_size' => $size,
                    'form_id' => $request->form_id2,
                    'form_type' => $request->form_type2,
                    'file_type' => $request->file_type2
                ]
            );

            //
            return Redirect::route('main.home')->with('message', "File Has Been Added Successfully");

        }
        return Redirect::route('main.home')->with('error', "File failed to upload");

    }


}
