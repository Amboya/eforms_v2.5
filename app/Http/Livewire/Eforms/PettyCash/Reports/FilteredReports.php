<?php

namespace App\Http\Livewire\Eforms\PettyCash\Reports;

use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\DivisionsModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FilteredReports extends Component
{

    public $totals_needs_me, $category;
    public $user_units = [];
    public $forms = [];
    public $status_select, $user_unit_select, $start_date, $end_date , $total = 0;


    public function render()
    {
        if( sizeof($this->user_units) > 0 ){}
        else {
            $this->user_units = DivisionsModel::select('id', 'name')->get();
        }

        $this->category = 'PT REPORTS';

        return view('livewire.eforms.petty-cash.reports.filtered-reports');
    }


    public function filterBy()
    {
        $this->user_unit_select;
        $this->status_select;
        $this->start_date;
        $this->end_date;

        $status_id_1 = 25;
        $status_id_2 = 26;
        $status_id_3 = 27;
        $status_id_4 = 28;
        $status_id_5 = 28;
        $status_id_6 = 29;
        $status_id_7 = 29;
        $status_id_8 = 29;
        $status_id_9 = 29;

        $date_range = "Cumulative Totals";
        $date_range = "Transactions for " . $this->start_date;

        $list = DB::
        select(
            "SELECT *

                    FROM (
                         SELECT *
                         from eform_petty_cash
                         where created_at BETWEEN '{$this->start_date}' AND '{$this->end_date}'
                        and division_id = '{$this->user_unit_select}'
                         )

                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                     ");


        $this->forms = PettyCashModel::hydrate($list) ;

        $this->forms->load('user_unit', 'directorate', 'division');

        $this->total =   number_format(  (array_sum(array_column($list, 'total_payment')) - array_sum(array_column($list, 'change')) ), 2) ;


    }

}
