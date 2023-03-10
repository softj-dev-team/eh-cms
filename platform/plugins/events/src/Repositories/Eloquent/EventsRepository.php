<?php

namespace Botble\Events\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Events\Repositories\Interfaces\EventsInterface;
use Language;

class EventsRepository extends RepositoriesAbstract implements EventsInterface
{
    /**
     * @var string
     */
    protected $screen = EVENTS_MODULE_SCREEN_NAME;

    public static  function getDataByCurrentLanguageCode($data, $model, $screen)
    {
        $data =  $data
        ->join('language_meta', 'language_meta.lang_meta_content_id','=', $model . '.id')
        ->where('language_meta.lang_meta_reference', '=',$screen )
        ->where('language_meta.lang_meta_code', '=', Language::getCurrentLocaleCode() );
        return $data;
    }
}
