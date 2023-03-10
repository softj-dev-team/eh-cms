<?php

namespace App\Console\Commands;

use App\Imports\MemberImport;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\Garden;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Member\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import_old_data {model} {name}';

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
     * @return mixed
     */
    public function handle()
    {
        $model = $this->argument('model');
        $name = $this->argument('name');

        switch ($model) {
            case 'open_space':
                    $open_space = '';
                    $categories = 0;
                    switch ($name) {
                        case 1: //공동구매
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 45');
                            $categories = 0;
                            break;
                        case 2: //분실물
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 46');
                            $categories = 1;
                            break;
                        case 3: //기타
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 47');
                            $categories = 2;
                            break;
                        default:
                            # code...
                            break;
                    }
                    $members = Member::pluck('id');
                    $createOpenSpace = [];
                    foreach ($open_space as $key => $item) {
                        $openSpace['id'] = $item->BP_IDX;
                        $openSpace['title'] = $item->BP_TITLE;
                        $openSpace['detail'] = $item->BP_CONTENT;
                        if($members->contains($item->MEM_IDX)) {
                            $openSpace['member_id'] = $item->MEM_IDX;
                        } else {
                            $openSpace['member_id'] = null;
                        }

                        $openSpace['created_at'] = $item->BP_DATE;
                        $openSpace['updated_at'] = $item->BP_DATE;
                        $openSpace['published'] = $item->BP_DATE;
                        $openSpace['categories_id'] = $categories;
                        array_push($createOpenSpace,$openSpace);
                    }
                OpenSpace::insert($createOpenSpace);
                break;
            case 'ads':
                    $open_space = '';
                    $categories = 0;
                    switch ($name) {
                        case 1: //교내동아리
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 48');
                            $categories = 1;
                            break;
                        case 3: //대외활동
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 49');
                            $categories = 3;
                            break;
                        case 4: //광고
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 50');
                            $categories = 4;
                            break;
                        case 6: //기타
                            $open_space =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 51');
                            $categories = 6;
                            break;
                        default:
                            # code...
                            break;
                    }
                    $members = Member::pluck('id');
                    $createOpenSpace = [];
                    foreach ($open_space as $key => $item) {
                        $openSpace['id'] = $item->BP_IDX;
                        $openSpace['title'] = $item->BP_TITLE;
                        $openSpace['details'] = $item->BP_CONTENT;
                        if($members->contains($item->MEM_IDX)) {
                            $openSpace['member_id'] = $item->MEM_IDX;
                        } else {
                            $openSpace['member_id'] = null;
                        }

                        $openSpace['created_at'] = $item->BP_DATE;
                        $openSpace['updated_at'] = $item->BP_DATE;
                        $openSpace['published'] = $item->BP_DATE;
                        $openSpace['categories'] = $categories;
                        $openSpace['is_deadline'] = 0;
                        array_push($createOpenSpace,$openSpace);
                    }
                Ads::insert($createOpenSpace);
                break;
                // case 'jobs':
                //     $data =  DB::connection('sqlsrv')->select('select top (1) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 55');
                //     dd($data);
                case 'sprout':
                        $categories_gardens_id = '';
                        $sproutList = [];
                        switch ($name) {
                            case 1: //ct_idx = 66
                                $sproutList =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 66');
                                $categories_gardens_id = CategoriesGarden::SPROUT_GARDEN;
                                break;
                            case 2: //ct_idx = 67
                                $sproutList =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 67');
                                $categories_gardens_id = CategoriesGarden::SPROUT_GARDEN;
                                break;
                            case 3: //ct_idx = 68
                                $sproutList =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 68');
                                $categories_gardens_id = CategoriesGarden::SPROUT_GARDEN;
                                break;

                            default: //ct_idx = 66
                                $sproutList =  DB::connection('sqlsrv')->select('select top (100) * from [ewhaian].[dbo].[ewha_board_post] where ct_idx = 66');
                                $categories_gardens_id = CategoriesGarden::SPROUT_GARDEN;
                                break;
                        }

                        $createSprout = [];
                        foreach ($sproutList as $key => $item) {
                           $sprout['id'] = $item->BP_IDX;
                           $sprout['title'] = $item->BP_TITLE;
                           $sprout['detail'] = $item->BP_CONTENT;
                           $sprout['created_at'] = $item->BP_DATE;
                           $sprout['updated_at'] = $item->BP_DATE;
                           $sprout['published']  = $item->BP_DATE;
                           $sprout['lookup']  = (int)$item->BP_COUNT;
                           $sprout['categories_gardens_id']  = $categories_gardens_id;
                           array_push($createSprout,$sprout);
                        }
                Garden::insert($createSprout);
                break;
            default:
                # code...
                break;
        }
        $this->info('***************       Done         *************');
        //
    }
}
