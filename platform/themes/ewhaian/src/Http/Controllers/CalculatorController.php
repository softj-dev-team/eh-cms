<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Campus\Models\Calculator\Calculator;
use Botble\Campus\Models\Calculator\CalculatorDetail;
use Botble\Report\Repositories\Interfaces\ReportInterface;
use Botble\Slides\Models\Slides;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Theme;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

class CalculatorController extends Controller
{
    /**
     * @var ReportInterface
     */
    protected $gardenRepository;

    /**
     * CalculatorController constructor.
     * @param ReportInterface $reportRepository
     * @author Thanh Tran
     */
    public function __construct()
    {
    }

    public function index($id_calculator = null)
    {
        Theme::breadcrumb()->add(__('campus'), route('campus.calculator_list'))
            ->add(__('campus.calculator'), 'http:...');

        $calculator = Calculator::where('member_id', auth()->guard('member')->user()->id )->limit(10)->get();
        Theme::setTitle(__('campus').' | 평점계산기');
        if($calculator->count() == 0){
            $newCalculatorData = [];
            for ($i = 0; $i < 4; $i++) {
                $itemCalculator = ['name' => $i + 1 . '학년', 'member_id' => auth()->guard('member')->user()->id, 'created_at' => now(), 'updated_at' => now()];
                 array_push($newCalculatorData, $itemCalculator);
            }

            Calculator::insert($newCalculatorData);
            $new_calculator = Calculator::where('member_id', auth()->guard('member')->user()->id )->get();

            return Theme::scope('campus.calculator.index',[
                'calculator' => $new_calculator,
                'id_calculator' => $new_calculator->first()->id,
            ])->render();
        }

        $totalCreditAllDetail = $calculator->sum('total_credits');
        if ($totalCreditAllDetail == 0){
            $pointAverageAllDetail = 0;
        }
        else{
            $totalPointCredit = 0;
            foreach($calculator as $item){
                $totalPointCredit = $totalPointCredit +  $item->total_point * $item->total_credits;
            }

            $pointAverageAllDetail = round($totalPointCredit / $totalCreditAllDetail, 2);
        }

        return Theme::scope('campus.calculator.index',[
            'calculator' => $calculator,
            'point_average_all_detail' => $pointAverageAllDetail,
            'total_credit_all_detail' => $totalCreditAllDetail,
            'id_calculator' => $id_calculator ?? ($calculator->first()->id ?? 0 ),
        ])->render();
    }

    public function create(Request $request)
    {
        $Calculators = Calculator::where('member_id', auth()->guard('member')->user()->id )->get();
        if(count($Calculators) > 9){
            return redirect()->route('campus.calculator_list')->with('error', '최대 학년을 초과하였습니다');
        }

        $name = count($Calculators) + 1;
        $factor = $Calculators->first()->factor;

        $calculator = Calculator::create([
            'name' => $name . '학년',
            'member_id' => auth()->guard('member')->user()->id,
            'factor' => $factor
        ]);
        // return redirect()->route('campus.calculator_list',['id_calculator'=>$calculator->id])->with('success', 'Create success');
        return redirect()->route('campus.calculator_list',['id_calculator'=>$calculator->id])->with('success', '추가하였습니다.');
    }

    public function createDetail(Request $request)
    {
        $calculatorIds = Calculator::where('member_id', auth()->guard('member')->user()->id )->pluck('id')->toArray();

        // clear all calculator detail
        CalculatorDetail::whereIn('id_calculator', $calculatorIds)->delete();

        $detail = [];
        foreach($calculatorIds as $k => $id){
            $classification = $request->{$id.'classification'};
            $description = $request->{$id.'description'} ;
            $point = $request->{$id.'point'};
            $grades = $request->{$id.'grades'};
            for ($i=1; $i < 5 ; $i++) {
                for ($y=0; $y < count( $point[$i]); $y++) {
                    if(is_null($point[$i][$y])) {
                        break;
                    }
                    $item['classification'] = $classification[$i][$y];
                    $item['description'] = $description[$i][$y] ?? 'N/A';
                    $item['point'] = (float)$point[$i][$y];
                    $item['grades'] = (float)$grades[$i][$y];
                    $item['id_calculator'] = $id;
                    $item['group'] = $i;
                    array_push($detail, $item);
                }
            }

        }

        CalculatorDetail::insert($detail);

        $idActive = $request->input('id_active') ?? $calculatorIds[0];

        return redirect()->route('campus.calculator_list',['id_calculator'=> $idActive ])->with('success', '평점 저장 완료');
    }

    public function reset(Request $request)
    {
        $id = $request->idActive;
        CalculatorDetail::where('id_calculator', $id)->delete();
        return redirect()->route('campus.calculator_list', ['id_calculator'=>$id])->with('success', '평점 초기화 완료');
    }
    public function factor(Request $request)
    {
        $factor = $request->factor;
        $calculator_id = $request->calculator_id;

        Calculator::where('member_id', auth()->guard('member')->user()->id )->update([
            'factor' => (int)$factor
        ]);

        return redirect()->route('campus.calculator_list',['id_calculator'=>$calculator_id])->with('success', '평점 저장 완료');
    }

    public function destroy(Request $request, $id)
    {
        $calculator = Calculator::findOrFail($id);
        $calculator->delete();

        $result = [
            'message' => __('controller.delete_successful', ['module'=>__('campus.calculator')]),
            'url' => route('campus.calculator_list')
        ];

        return response()->json($result);
    }
}
