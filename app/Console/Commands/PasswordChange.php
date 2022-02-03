<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class PasswordChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:change {staff_no} {password}';

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
        $staff_no = $this->argument('staff_no');
        $password = $this->argument('password');

        $user = User::where('staff_no', $staff_no)->first();
        $user->password = Hash::make($password);
        //reset your password
        $user->password_changed = config('constants.password_not_changed');
        $user->save();
        $this->info('Password Changed Successfully');
    }
}
