<?php

namespace App\Console\Commands;

use App\Models\EForms\Subsistence\SubsistenceAccountModel;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\EForms\Trip\Destinations;
use App\Models\EForms\Trip\DestinationsApprovals;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\EForms\Trip\TripMembers;
use Illuminate\Console\Command;

class TruncateSubsitenceTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:subsistence';

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
        $modal = SubsistenceModel::truncate();
        echo "  Subsistence Model Cleared  " ;

        $modal = SubsistenceAccountModel::truncate();
        echo "  Subsistence Account Model Cleared  " ;

        $modal = Trip::truncate();
        echo "  Trip Model Cleared  " ;

        $modal = Invitation::truncate();
        echo "  Invitations Cleared  " ;

        $modal = DestinationsApprovals::truncate();
        echo "  Destination approvals Cleared  " ;


        $modal = Destinations::truncate();
        echo "  Destination Cleared  " ;
    }
}
