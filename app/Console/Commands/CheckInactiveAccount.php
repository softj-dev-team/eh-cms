<?php

namespace App\Console\Commands;

use Botble\Member\Models\Member;
use Illuminate\Console\Command;


class CheckInactiveAccount extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:check-inactive-account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check inactive account (no annual login)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        try {
            $oneYearAgo = date('Y-m-d H:i:s', strtotime('-1 year'));

            Member::where('last_login', '<', $oneYearAgo)
                ->update(['is_active' => 0]);

            echo 'Check inactive account successfully !!!' . PHP_EOL;

        } catch (\Exception $e) {
            echo 'Check inactive account failed !!!' . PHP_EOL;
        }
    }
}
