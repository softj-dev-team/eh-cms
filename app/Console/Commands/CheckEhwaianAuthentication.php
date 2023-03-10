<?php

namespace App\Console\Commands;

use Botble\Member\Models\Member;
use Illuminate\Console\Command;

class CheckEhwaianAuthentication extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:check-ehwaian-authentication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check ehwaian authentication by 31st of March (every year)';

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
            Member::where('status_fresh2', '<>', 2)
                ->update([
                    'status_fresh1' => 0,
                    'status_fresh2' => 0,
                    'note_freshman1' => NULL,
                    'note_freshman2' => NULL,
                    'update_freshman1' => NULL,
                    'update_freshman2' => NULL,
                    'freshman1' => NULL,
                    'freshman2' => NULL,
                    'sprouts_number' => NULL,
                    'auth_studentid' => NULL,
                ]);

            echo 'Check ehwaian authentication successfully !!!' . PHP_EOL;

        } catch (\Exception $e) {
            echo 'Check ehwaian authentication failed !!!' . PHP_EOL;
        }
    }
}
