<?php

namespace App\Http\Livewire\Eforms\Trip;

use App\Models\Main\ConfigWorkFlow;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads ;

class TripsCreate extends Component
{
    public $max_date ;
    public $date_from  = null ;
    public $date_to ;
    public $no_of_days = 0 ;
    public $destination ;
    public $description ;
    public $file ;
    public $destination_units = [];
    public $users = [];
    public $selectedDestinations = [];
    public $selectedUsers= [];
    public $selectedBudgetUnit = [];

    public function render()
    {

       if( $this->date_from != null ){
           $date_from = strtotime( $this->date_from );
           $this->max_date  = strtotime("+7 day", $date_from);
       }
       else{
           $date_from = strtotime( date('Y-m-d') );
           $this->max_date  = strtotime("+7 day", $date_from);
       }
//        $this->destination_units = ConfigWorkFlow::select('id', 'user_unit_description', 'user_unit_code', 'user_unit_bc_code', 'user_unit_cc_code')
//            ->where('user_unit_status', config('constants.user_unit_active'))
//            ->get();

//        $this->users= User::select('id', 'name', 'staff_no', 'job_code')->where('con_st_code', config('constants.phris_user_active'))->get();


        return view('livewire.eforms.trip.trips-create');
    }

    public function noOfDays(){
        $earlier = new \DateTime($this->date_from);
        $later = new \DateTime($this->date_to );


        $this->no_of_days = $later->diff($earlier)->format("%a"); //3
    }


    public function submit()
    {
//        $validatedData = $this->validate([
//            'title' => 'required',
//            'file' => 'required',
//        ]);

        dd( $this->file);

        $validatedData['name'] = $this->file->store('files', 'public');

        File::create($validatedData);

        session()->flash('message', 'Trip successfully Uploaded.');
    }
}
