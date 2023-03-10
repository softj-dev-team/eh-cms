<?php

namespace Botble\Widget\Widgets;

use Botble\Widget\AbstractWidget;

class Text extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'packages.widget::widgets.text.frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'packages.widget::widgets.text.backend';

    /**
     * @var bool
     */
    protected $isCore = true;

    /**
     * Text constructor.
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct()
    {
        parent::__construct([
            'name'        => trans('packages/widget::global.widget_text'),
            'description' => trans('packages/widget::global.widget_text_description'),
            'content'     => null,
        ]);
    }
}
