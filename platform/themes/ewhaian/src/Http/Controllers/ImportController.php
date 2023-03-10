<?php

namespace Theme\Ewhaian\Http\Controllers;

use App\Imports\JobsPartTimeImport;
use App\Imports\MemberAddInfoImport;
use App\Imports\MemberAuthKeyImport;
use App\Imports\MemberBlackListImport;
use App\Imports\MemberCareerImport;
use App\Imports\MemberCurrentIpImport;
use App\Imports\MemberFileImport;
use App\Imports\MemberFolderImport;
use App\Imports\MemberFreshToEwhainLogImport;
use App\Imports\MemberHideFlagImport;
use App\Imports\MemberImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Theme;

class ImportController extends Controller
{
    public function index()
    {
       $a =  DB::connection('sqlsrv')->select('select top 10 * from EWHA_BOARD_REPLE');
       dd($a);


        Theme::breadcrumb()->add('import', route('member.importDB'))
            ->add('index', 'http:...');

        Theme::setTitle('Import Database ');

        return Theme::scope('import.index')->render();
    }

    public function postImport(Request $request)
    {
        if ($request->hasFile('your_file')) {
//C:\xampp\htdocs\3forcom-ewhaian-2019\cms\public\uploads\import\TBL_MEMBER_INFO.csv
            $file = $request->file('your_file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            switch ($filename) {
                case 'TBL_MEMBER_ADDINFO':
                    Excel::import(new MemberAddInfoImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_AUTH_KEY':
                    Excel::import(new MemberAuthKeyImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_BLACKLIST':
                    Excel::import(new MemberBlackListImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_CAREER':
                    Excel::import(new MemberCareerImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_CURRENT_IP':
                    Excel::import(new MemberCurrentIpImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_FILE':
                    Excel::import(new MemberFileImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_FOLDER':
                    Excel::import(new MemberFolderImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_FRESH_TO_EWHAIN_LOG':
                    Excel::import(new MemberFreshToEwhainLogImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_HIDE_FLAG':
                    Excel::import(new MemberHideFlagImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_MEMBER_INFO':
                    Excel::import(new MemberImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'TBL_JOBs':
                    Excel::import(new JobsPartTimeImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
                case 'test':
                    Excel::import(new MemberHideFlagImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;

                default:
                    Excel::import(new MemberHideFlagImport, $request->file('your_file'));
                    return redirect()->route('member.importDB')->with('success', 'All good!');
                    break;
            }

        }

    }
}
