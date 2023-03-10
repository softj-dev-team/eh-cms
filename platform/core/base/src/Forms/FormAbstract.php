<?php

namespace Botble\Base\Forms;

use Assets;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\CustomRadioField;
use Botble\Base\Forms\Fields\CustomSelectField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TimeField;
use Botble\Slug\Forms\Fields\PermalinkField;
use Illuminate\Support\Arr;
use JsValidator;
use Kris\LaravelFormBuilder\Fields\FormField;
use Kris\LaravelFormBuilder\Form;

abstract class FormAbstract extends Form
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $moduleName = '';

    /**
     * @var string
     */
    protected $validatorClass = '';

    /**
     * @var array
     */
    protected $metaBoxes = [];

    /**
     * @var string
     */
    protected $actionButtons = '';

    /**
     * @var string
     */
    protected $breakFieldPoint = '';

    /**
     * @var bool
     */
    protected $useInlineJs = false;

    /**
     * @var string
     */
    protected $wrapperClass = 'form-body';

    /**
     * @var string
     */
    protected $template = 'core.base::forms.form';

    /**
     * FormAbstract constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        $this->setMethod('POST');
        $this->setFormOption('template', $this->template);
        $this->setFormOption('id', 'form_' . md5(get_class($this)));
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     * @author Sang Nguyen
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * @param string $module
     * @return $this
     */
    public function setModuleName($module): self
    {
        $this->moduleName = $module;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetaBoxes(): array
    {
        uasort($this->metaBoxes, function ($before, $after) {
            if (Arr::get($before, 'priority', 0) > Arr::get($after, 'priority', 0)) {
                return 1;
            } elseif (Arr::get($before, 'priority', 0) < Arr::get($after, 'priority', 0)) {
                return -1;
            }

            return 0;
        });

        return $this->metaBoxes;
    }


    /**
     * @param string $name
     * @return string
     * @throws \Throwable
     */
    public function getMetaBox($name): string
    {
        if (!Arr::get($this->metaBoxes, $name)) {
            return '';
        }

        $meta_box = $this->metaBoxes[$name];

        return view('core.base::forms.partials.meta-box', compact('meta_box'))->render();
    }

    /**
     * @param array $boxes
     * @return $this
     */
    public function addMetaBoxes($boxes): self
    {
        if (!is_array($boxes)) {
            $boxes = [$boxes];
        }
        $this->metaBoxes = array_merge($this->metaBoxes, $boxes);

        return $this;
    }

    /**
     * @param string $name
     * @return FormAbstract
     */
    public function removeMetaBox($name): self
    {
        Arr::forget($this->metaBoxes, $name);
        return $this;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function getActionButtons(): string
    {
        if ($this->actionButtons === '') {
            return view('core.base::elements.form-actions')->render();
        }

        return $this->actionButtons;
    }

    /**
     * @return $this
     */
    public function removeActionButtons(): self
    {
        $this->actionButtons = '';
        return $this;
    }

    /**
     * @param string $actionButtons
     * @return $this
     */
    public function setActionButtons($actionButtons): self
    {
        $this->actionButtons = $actionButtons;
        return $this;
    }

    /**
     * @return string
     */
    public function getValidatorClass(): string
    {
        return $this->validatorClass;
    }

    /**
     * @param string $validatorClass
     * @return $this
     */
    public function setValidatorClass($validatorClass): self
    {
        $this->validatorClass = $validatorClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getBreakFieldPoint(): string
    {
        return $this->breakFieldPoint;
    }

    /**
     * @param string $breakFieldPoint
     * @return $this
     */
    public function setBreakFieldPoint(string $breakFieldPoint): self
    {
        $this->breakFieldPoint = $breakFieldPoint;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseInlineJs(): bool
    {
        return $this->useInlineJs;
    }

    /**
     * @param bool $useInlineJs
     * @return $this
     */
    public function setUseInlineJs(bool $useInlineJs): self
    {
        $this->useInlineJs = $useInlineJs;
        return $this;
    }

    /**
     * @return string
     */
    public function getWrapperClass(): string
    {
        return $this->wrapperClass;
    }

    /**
     * @param string $wrapperClass
     * @return $this
     */
    public function setWrapperClass(string $wrapperClass): self
    {
        $this->wrapperClass = $wrapperClass;
        return $this;
    }

    /**
     * @param string $model
     * @return $this
     * @deprecated 3.4
     */
    public function setModel($model): self
    {
        parent::setupModel($model);
        $this->rebuildForm();
        return $this;
    }

    /**
     * @author Sang Nguyen
     * @return $this
     */
    public function withCustomFields(): self
    {
        if (!$this->formHelper->hasCustomField('customSelect')) {
            $this->addCustomField('customSelect', CustomSelectField::class);
        }

        if (!$this->formHelper->hasCustomField('editor')) {
            $this->addCustomField('editor', EditorField::class);
        }
        if (!$this->formHelper->hasCustomField('onOff')) {
            $this->addCustomField('onOff', OnOffField::class);
        }
        if (!$this->formHelper->hasCustomField('customRadio')) {
            $this->addCustomField('customRadio', CustomRadioField::class);
        }
        if (!$this->formHelper->hasCustomField('mediaImage')) {
            $this->addCustomField('mediaImage', MediaImageField::class);
        }
        if (!$this->formHelper->hasCustomField('color')) {
            $this->addCustomField('color', ColorField::class);
        }
        if (!$this->formHelper->hasCustomField('time')) {
            $this->addCustomField('time', TimeField::class);
        }
        if (!$this->formHelper->hasCustomField('permalink') && config('packages.slug.general.supported')) {
            $this->addCustomField('permalink', PermalinkField::class);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function hasTabs(): self
    {
        $this->setFormOption('template', 'core.base::forms.form-tabs');
        return $this;
    }

    /**
     * @return int
     * @author Sang Nguyen
     */
    public function hasMainFields()
    {
        if (!$this->breakFieldPoint) {
            return count($this->fields);
        }

        $main_fields = [];

        /**
         * @var FormField $field
         */
        foreach ($this->fields as $field) {
            if ($field->getName() == $this->breakFieldPoint) {
                break;
            }

            $main_fields[] = $field;
        }

        return count($main_fields);
    }

    /**
     * @return $this
     */
    public function disableFields()
    {
        parent::disableFields();
        return $this;
    }

    /**
     * @param array $options
     * @param bool $showStart
     * @param bool $showFields
     * @param bool $showEnd
     * @return string
     * @author Sang Nguyen
     */
    public function renderForm(array $options = [], $showStart = true, $showFields = true, $showEnd = true): string
    {
        Assets::addAppModule(['form-validation'])
            ->addScripts(['are-you-sure']);

        apply_filters(BASE_FILTER_BEFORE_RENDER_FORM, $this, $this->moduleName, $this->getModel());

        return parent::renderForm($options, $showStart, $showFields, $showEnd);
    }

    /**
     * @return string
     * @throws \Exception
     * @author Sang Nguyen
     */
    public function renderValidatorJs(): string
    {
        $element = null;
        if ($this->getFormOption('id')) {
            $element = '#' . $this->getFormOption('id');
        } elseif ($this->getFormOption('class')) {
            $element = '.' . $this->getFormOption('class');
        }

        return JsValidator::formRequest($this->getValidatorClass(), $element);
    }

    /**
     * @param $name
     * @param $class
     * @return $this|Form
     */
    public function addCustomField($name, $class)
    {
        parent::addCustomField($name, $class);

        return $this;
    }
}