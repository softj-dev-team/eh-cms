<?php

namespace Botble\Introduction\Forms\Notices;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FormAbstract;
use Botble\Garden\Models\CategoriesGarden;
use Botble\Garden\Models\Garden;
use Botble\Introduction\Forms\Fields\MultiSelectField;
use Botble\Contents\Repositories\Interfaces\CategoriesContentsInterface;
use Botble\Garden\Repositories\Interfaces\CategoriesGardenInterface;
use Botble\Introduction\Http\Requests\Notices\NoticesIntroductionRequest;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Member\Models\Member;

class NoticesIntroductionForm extends FormAbstract
{

    /**
     * @return void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $contentCategories = app(CategoriesContentsInterface::class)->getModel()->all();
        $gardenCategories = app(CategoriesGardenInterface::class)->getModel()->all();

        if (!$this->formHelper->hasCustomField('multiSelect')) {
            $this->formHelper->addCustomField('multiSelect', MultiSelectField::class);
        }

        $checkedCodes = $this->getModel() ? $this->getModel()->code : [];

        $categories = getCategoryContentKeyNameNotice($contentCategories, CONTENTS_MODULE_SCREEN_NAME) + getCategoryContentKeyNameNotice($gardenCategories, GARDEN_MODULE_SCREEN_NAME);

        $code = [
            'STUDY_ROOM_MODULE_SCREEN_NAME' => '스터디룸',
            'GENEALOGY_MODULE_SCREEN_NAME' => '이화계보',
            'OLD_GENEALOGY_MODULE_SCREEN_NAME' => '지난계보',
            'EVALUATION_MODULE_SCREEN_NAME' => '강의평가',
            'FLARE_MODULE_SCREEN_NAME'=>'벼룩시장',
            'JOBS_PART_TIME_MODULE_SCREEN_NAME'=>'알바하자',
            'ADS_MODULE_SCREEN_NAME'=>'광고홍보',
            'SHELTER_MODULE_SCREEN_NAME'=>'주거정보',
            'OPEN_SPACE_MODULE_SCREEN_NAME' => '열린광장',
        ];

        $codeValues = array_merge($categories, $code);

        $newCodes = [];
        foreach ($codeValues as $key => $value) {
            $objectCode = [];
            $objectCode['id'] = $key;
            $objectCode['name'] = $value;
            array_push($newCodes, $objectCode);
        }

        $this
            ->setModuleName(NOTICES_INTRODUCTION_MODULE_SCREEN_NAME)
            ->setValidatorClass(NoticesIntroductionRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => "공지사항 제목",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => "공지사항 제목 작성",
                    'data-counter' => 120,
                ],
            ])
            ->add('notices', 'editor', [
                'label' => "공지사항 본문",
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => "공지사항",
                ],
            ])
            ->add('allow_comment', 'checkbox', [
                'label' => '댓글 작성 가능',
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => $this->getModel() ? $this->getModel()->allow_comment : 0,
            ])
            ->add('code[]', 'multiSelect', [
                'data-value'=> $newCodes,
                'share_value' => $checkedCodes ?? 0,
                'label' => '대상 게시판',
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('status', 'select', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices' => BaseStatusEnum::arrStatus(),
            ])
            ->setBreakFieldPoint('code[]');
    }
}
