<?php

namespace Botble\Contents\Repositories\Observers;

use Botble\Contents\Models\Contents;

class ContentsObserver
{
    /**
     * Handle the contents "created" event.
     *
     * @param  \App\Contents  $contents
     * @return void
     */
    public function created(Contents $contents)
    {

    }

    public function updating(Contents $contents)
    {
        if ($contents->slide_no > Contents::IS_NOT_SLIDE) {
            $contents->is_slides = Contents::IS_SLIDE;
        } else {
            $contents->is_slides = Contents::IS_NOT_SLIDE;
        }
    }

    /**
     * Handle the contents "deleted" event.
     *
     * @param  \App\Contents  $contents
     * @return void
     */
    public function deleted(Contents $contents)
    {
        //
    }

    /**
     * Handle the contents "restored" event.
     *
     * @param  \App\Contents  $contents
     * @return void
     */
    public function restored(Contents $contents)
    {
        //
    }

    /**
     * Handle the contents "force deleted" event.
     *
     * @param  \App\Contents  $contents
     * @return void
     */
    public function forceDeleted(Contents $contents)
    {
        //
    }
}
