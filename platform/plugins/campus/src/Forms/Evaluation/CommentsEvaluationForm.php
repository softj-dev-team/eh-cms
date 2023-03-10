<?php

namespace Botble\Campus\Forms\Evaluation;

use Botble\Base\Forms\FormAbstract;
use Botble\Campus\Forms\Fields\ChosenCommentField;
use Botble\Campus\Forms\Fields\VotesField;
use Botble\Campus\Http\Requests\Evaluation\CommentsEvaluationRequest;

class CommentsEvaluationForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        if (!$this->formHelper->hasCustomField('votesFied')) {
            $this->formHelper->addCustomField('votesFied', VotesField::class);
        }
        if (!$this->formHelper->hasCustomField('chosenComment')) {
            $this->formHelper->addCustomField('chosenComment', ChosenCommentField::class);
        }

        $this
            ->setModuleName(COMMENTS_EVALUATION_MODULE_SCREEN_NAME)
            ->setValidatorClass(CommentsEvaluationRequest::class)
            ->withCustomFields()
            ->add('votes', 'votesFied', [
                'readonly'=>$this->getModel() ? 'true' : 'false',
            ])
            ->add('grade', 'chosenComment', [
                'label' => __('campus.evaluation.grade'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'normal', 'title' => '보통'],
                    '1' => ['name' => 'rose_knife', 'title' => '장미칼'],
                ],
                'multiple'=>false,
                'readonly'=>$this->getModel() ? true : false

            ])
            ->add('assignment', 'chosenComment', [
                'label' => __('campus.evaluation.assignment'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'a_lot', 'title' => '많음'],
                    '1' => ['name' => 'normal', 'title' => '보통'],
                    '2' => ['name' => 'none', 'title' => '없음'],
                ],
                'multiple'=>false,
                'readonly'=>$this->getModel() ? true : false
            ])
            ->add('attendance', 'chosenComment', [
                'label' => __('campus.evaluation.attendance'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'care_student', 'title' => '직접호명'],
                    '1' => ['name' => 'designated_seat', 'title' => '지정좌석'],
                    '2' => ['name' => 'electronic_attendance', 'title' => '전자출결'],
                    '3' => ['name' => 'dont_care_student', 'title' => '반영안함'],
                ],
                'multiple'=>false,
                'readonly'=>$this->getModel() ? true : false

            ])
            ->add('team_project', 'chosenComment', [
                'label' => __('campus.evaluation.team_project'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'a_lot', 'title' => '많음'],
                    '1' => ['name' => 'normal', 'title' => '보통'],
                    '2' => ['name' => 'none', 'title' => '없음'],
                ],
                'multiple'=>false,
                'readonly'=>$this->getModel() ? true : false

            ])
            ->add('textbook', 'chosenComment', [
                'label' => __('campus.evaluation.textbook'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'textbook', 'title' => '교재'],
                    '1' => ['name' => 'ppt', 'title' => 'PPT'],
                    '2' => ['name' => 'none', 'title' => '없음'],
                ],
                'multiple'=>true,
                'readonly'=>$this->getModel() ? true : false

            ])
            ->add('number_times', 'chosenComment', [
                'label' => __('campus.evaluation.number_of_times'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'over_4_times', 'title' => '4회 이상'],
                    '1' => ['name' => '3_times', 'title' => '3 회'],
                    '2' => ['name' => '2_times', 'title' => '2 회'],
                    '3' => ['name' => '1_times', 'title' => '1 회'],
                    '4' => ['name' => 'none', 'title' => '없음'],
                ],
                'multiple'=>false,
                'readonly'=>$this->getModel() ? true : false

            ])
            ->add('type', 'chosenComment', [
                'label' => __('campus.evaluation.type'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => [
                    '0' => ['name' => 'multiple_choices', 'title' => '객관식'],
                    '1' => ['name' => 'short_answer', 'title' => '단답형'],
                    '3' => ['name' => 't_f', 'title' => 'T/F'],
                    '4' => ['name' => 'short_essay', 'title' => '약술형'],
                    '5' => ['name' => 'long_essay', 'title' => '논술형'],
                    '6' => ['name' => 'oral', 'title' => '구술'],
                    '7' => ['name' => 'alternative', 'title' => '대체'],
                    '8' => ['name' => 'other', 'title' => '그 외'],
                ],
                'multiple'=>true,
                'readonly'=>$this->getModel() ? true : false

            ])

            ->add('comments', 'textarea', [
                'label' => '댓글이',
                'label_attr' => ['class' => 'control-label required '],
                'attr' => [
                    'placeholder' => '-이 강의에 대해 도움이 될 수 있게 작성해주세요. \n작성 후 수정/삭제가 불가능하며 반복 또는 관련성이 없는 글을 삭제됩니다. 관리자 삭제 후에는 작성 제한이 있을 수 있습니다.',
                    'readonly'=> $this->getModel() ? 'readonly' : false
                ],
            ]);
    }
}
