<?php

namespace Botble\Campus\Models\Calculator;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Eloquent;

/**
 * Botble\Campus\Models\Campus
 *
 * @mixin \Eloquent
 */
class Calculator extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calculator';

    const DEFAULT_ITEM_1 = 3;
    const DEFAULT_ITEM_2 = 3;
    const DEFAULT_ITEM_3 = 2;
    const DEFAULT_ITEM_4 = 2;

    const GROUP_ITEM_1 = 1;
    const GROUP_ITEM_2 = 2;
    const GROUP_ITEM_3 = 3;
    const GROUP_ITEM_4 = 4;
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'member_id',
        'description',
        'factor'
    ];

    /**
     * @var string
     */
    protected $screen = CALCULATOR;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    protected $appends = [
        'total_point',
        'total_credits',
        'semester',
        'semester_point',
    ];

    public function getTotalPointAttribute()
    {
         //4.3
        $total_point_grades = 0;
        $total_point = 0;
        $detailCalculator = $this->detail()->get();
        foreach ($detailCalculator as $key => $item) {
            $total_point_grades = $total_point_grades + $item->count_point;
            $total_point = $total_point + $item->point;
        }
        if($total_point == 0) {
            return 0;
        }
        return round((float)$total_point_grades / (float)$total_point , 2) ;
    }
    public function getTotalCreditsAttribute()
    {
       return $this->detail()->sum('point');
    }

    public function getSemesterAttribute()
    {
        return [ $this->name . ' 1학기',$this->name . ' 2학기'];
    }

    private function getSemesterPoint($type) {
        switch ($type) {
            case 1:
                //4.3
                $total_point_grades = 0;
                $total_point = 0;
                $detailCalculator = $this->detail()->get();
                foreach ($detailCalculator as $key => $item) {
                    if($key % 2 == 0) {
                        $total_point_grades = $total_point_grades + $item->count_point;
                        $total_point = $total_point + $item->point;
                    }
                }
                if($total_point == 0) {
                    return 0;
                }
                return round((float)$total_point_grades / (float)$total_point , 2) ;
                break;
            case 2:
                //4.3
                $total_point_grades = 0;
                $total_point = 0;
                $detailCalculator = $this->detail()->get();
                foreach ($detailCalculator as $key => $item) {
                    if($key % 2 != 0) {
                        $total_point_grades = $total_point_grades + $item->count_point;
                        $total_point = $total_point + $item->point;
                    }
                }
                if($total_point == 0) {
                    return 0;
                }
                return round((float)$total_point_grades / (float)$total_point , 2) ;
                break;

            default:
                //4.3
                $total_point_grades = 0;
                $total_point = 0;
                $detailCalculator = $this->detail()->get();
                foreach ($detailCalculator as $key => $item) {
                    if($key % 2 == 0) {
                        $total_point_grades = $total_point_grades + $item->count_point;
                        $total_point = $total_point + $item->point;
                    }
                }
                if($total_point == 0) {
                    return 0;
                }
                return round((float)$total_point_grades / (float)$total_point , 2) ;
                break;
        }
    }
    public function getSemesterPointAttribute()
    {
        return [$this->getSemesterPoint(1), $this->getSemesterPoint(2) ];
    }

    public function detail()
    {
        return $this->hasMany(CalculatorDetail::class, 'id_calculator');
    }
    public function group($group)
    {
        return $this->hasMany(CalculatorDetail::class, 'id_calculator')->where('group', $group);
    }

    public function getClassification()
    {
        return [
            0 => ['label' => '전공필수', 'value' => 1],
            1 => ['label' => '전공선택', 'value' => 2],
            2 => ['label' => '필수교양','value' => 3],
            3 => ['label' => '일반교양','value' => 4],
            4 => ['label' => '기타','value' => 5],
            5 => ['label' => '일반교양','value' => 6],
            6 => ['label' => '필수교양','value' => 7],
            7 => ['label' => '전공기초','value' => 8],
        ];
    }

    public function getGrades()
    {
        return [
            0  => ['label' => 'A+','value' => 4.3],
            1  => ['label' => 'A','value' => 4.0],
            2  => ['label' => 'A-','value' => 3.7],
            3  => ['label' => 'B+','value' => 3.3],
            4  => ['label' => 'B','value' => 3.0],
            5  => ['label' => 'B-','value' => 2.7],
            6  => ['label' => 'C+','value' => 2.3],
            7  => ['label' => 'C','value' => 2.0],
            8  => ['label' => 'C-','value' => 1.7],
            9  => ['label' => 'D+','value' => 1.3],
            10 => ['label' => 'D','value' => 1.0],
            11 => ['label' => 'D-','value' => 0.7],
            12 => ['label' => 'F','value' =>0,],
            13 => ['label' => 'P','value' =>10,],
            14 => ['label' => 'NP','value' =>15,],
        ];
    }
}
