<?php

namespace App\Console\Commands;

use Botble\Member\Models\Member;
use Illuminate\Console\Command;

class AutoDeleteAuthRequest extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:auto-delete-auth-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto delete auth request after 7 days';

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
            $date = date('Y-m-d H:i:s', strtotime('-7 days'));

            Member::where('update_freshman1', '<', $date)
                ->update(['freshman1' => NULL, 'freshman2' => NULL]);

            echo 'Auto delete auth request successfully !!!' . PHP_EOL;

        } catch (\Exception $e) {
            echo 'Auto delete auth request failed !!!' . PHP_EOL;
        }
    }
}
