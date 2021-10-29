<?php

namespace App\Console\Commands;

use App\Mail\SendMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail';

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
        $code  = 'PT1212(TEST)';
        $details = [
            'name' => 'ISD',
            'url' => 'petty.cash.home',
            'subject' => '(TEST-MAIL) Petty-Cash Voucher Needs Your Attention',
            'title' => '(TEST-MAIL) Petty-Cash Voucher Needs Your Attention',
            'body' => 'This is to notify you that there is a petty-cash voucher ('.$code.') that needs your attention. Please login to ezesco by clicking on the button below to take action on the form.'
        ];
        //call the mail function

            $mail = Mail::to('nshubart@zesco.co.zm')->send(new SendMail($details));
         dd($mail);
            //get user details

    }
}
