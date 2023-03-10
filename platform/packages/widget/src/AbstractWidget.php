<?php

namespace Botble\Widget;

use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Theme;

abstract class AbstractWidget
{
    /**
     * The number of seconds before each reload.
     * False means no reload at all.
     *
     * @var int|float|bool
     */
    public $reloadTimeout = false;

    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = false;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $frontendTemplate = 'frontend';

    /**
     * @var string
     */
    protected $backendTemplate = 'backend';

    /**
     * @var string
     */
    protected $widgetDirectory;

    /**
     * @var bool
     */
    protected $isCore = false;

    /**
     * @var WidgetInterface
     */
    protected $widgetRepository;

    /**
     * @var string
     */
    protected $theme = null;

    /**
     * AbstractWidget constructor.
     * @param array $config
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }

        $this->widgetRepository = app(WidgetInterface::class);

        $this->theme = setting('theme') . $this->getCurrentLocaleCode();
    }

    /**
     * Placeholder for async widget.
     * You can customize it by overwriting this method.
     *
     * @return string
     */
    public function placeholder()
    {
        return '';
    }

    /**
     * Async and reloadable widgets are wrapped in container.
     * You can customize it by overriding this method.
     *
     * @return array
     */
    public function container()
    {
        return [
            'element'    => 'div',
            'attributes' => 'style="display:inline" class="botble-widget-container"',
        ];
    }

    /**
     * Cache key that is used if caching is enabled.
     *
     * @param $params
     *
     * @return string
     * @author Sang Nguyen
     */
    public function cacheKey(array $params = [])
    {
        return 'botble.widgets.' . serialize($params);
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        Theme::uses(setting('theme'));
        $args = func_get_args();
        $data = $this->widgetRepository->getFirstBy([
            'widget_id'  => $this->getId(),
            'sidebar_id' => $args[0],
            'position'   => $args[1],
            'theme'      => $this->theme,
        ]);

        if (!empty($data)) {
            $this->config = array_merge($this->config, $data->data);
        }

        if (!$this->isCore) {
            return Theme::loadPartial($this->frontendTemplate, Theme::getThemeNamespace('/../widgets/' . $this->widgetDirectory . '/templates'), [
                'config'  => $this->config,
                'sidebar' => $args[0],
            ]);
        }

        return view($this->frontendTemplate, [
            'config'  => $this->config,
            'sidebar' => $args[0],
        ]);
    }

    /**
     * @return string
     * @author Sang Nguyen
     */
    public function getId()
    {
        return get_class($this);
    }

    /**
     * @param null $sidebar_id
     * @param int $position
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @author Sang Nguyen
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function form($sidebar_id = null, $position = 0)
    {
        Theme::uses(setting('theme'));
        if (!empty($sidebar_id)) {
            $data = $this->widgetRepository->getFirstBy([
                'widget_id'  => $this->getId(),
                'sidebar_id' => $sidebar_id,
                'position'   => $position,
                'theme'      => $this->theme,
            ]);
            if (!empty($data)) {
                $this->config = array_merge($this->config, $data->data);
            }
        }

        if (!$this->isCore) {
            return Theme::loadPartial($this->backendTemplate, Theme::getThemeNamespace('/../widgets/' . $this->widgetDirectory . '/templates'), [
                'config' => $this->config,
            ]);
        }

        return view($this->backendTemplate, [
            'config' => $this->config,
        ]);
    }

    /**
     * Add defaults to configuration array.
     *
     * @param array $defaults
     * @author Sang Nguyen
     */
    protected function addConfigDefaults(array $defaults)
    {
        $this->config = array_merge($this->config, $defaults);
    }

    /**
     * @return null|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @author Sang Nguyen
     */
    protected function getCurrentLocaleCode()
    {
        $language_code = null;
        if (is_plugin_active('language')) {
            $current_locale = is_in_admin() ? \Language::getCurrentAdminLocaleCode() : \Language::getCurrentLocaleCode();
            $language_code = $current_locale && $current_locale != \Language::getDefaultLocaleCode() ? '-' . $current_locale : null;
        }

        return $language_code;
    }
}
