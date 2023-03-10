<?php

namespace Botble\Campus\Models\Evaluation;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Traits\EnumCastable;
use Botble\Campus\Models\Genealogy\Genealogy;
use Eloquent;

/**
 * Botble\Life\Models\Life
 *
 * @mixin \Eloquent
 */
class Major extends Eloquent
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'major';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parents_id',

    ];

    /**
     * @var string
     */
    protected $screen = MAJOR_MODULE_SCREEN_NAME;

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public function evaluation()
    {
        return $this->belongsToMany(Evaluation::class, 'major_evaluation');
    }

    public function getItemById($id,$type=0)
    {
        if($type == 1){
            return $this->belongsToMany(Genealogy::class, 'major_genealogy')->where('genealogy_id',$id);
        }
        return $this->belongsToMany(Evaluation::class, 'major_evaluation')->where('evaluation_id',$id);
    }

    public function parents()
    {
       return  Major::where('id',$this->parents_id)->first();
    }

    public function getChild(){
        return Major::where('status','publish')->where('parents_id',$this->id)->get();
    }
    


}
