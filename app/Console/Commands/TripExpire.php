<?php

namespace App\Console\Commands;

use App\Models\EForms\Trip\Trip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TripExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trip:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all trips that have expired as of today
        $allTrips = Trip::where('date_to', '<', Carbon::now())
            ->where('config_status_id', config('constants.trip_status.new_trip') )
            ->get() ;

        foreach ($allTrips as $trip){
            $trip->config_status_id = config('constants.trip_status.trip_closed') ;
            $trip->save();
            echo "  ".$trip->name . " Expired and Closed.   :   " ;
        }

    }
}
