<?php

namespace Botble\Member\Forms;

use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\Base\Forms\FormAbstract;
use Botble\Member\Forms\Fields\FreshmanField;
use Botble\Member\Http\Requests\MemberCreateRequest;
use Botble\Member\Models\Member;
use Carbon\Carbon;

class MemberForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $selected_roles = [];
        $start_block_time = null;
        $end_block_time = null;
        if ($this->getModel()) {
            $selected_roles = $this->getModel()->roles;
            $roles = app(RoleInterface::class)->getModel()->all();
            $start_block_time = $this->getModel()->start_block_time ? Carbon::parse($this->getModel()->start_block_time)->format('Y-m-d') : null;
            $end_block_time = $this->getModel()->end_block_time ? Carbon::parse($this->getModel()->end_block_time)->format('Y-m-d') : null;
        }

        if (empty($selected_roles)) {
            $roles = app(RoleInterface::class)->getModel()->all();

        }
        if (!$this->formHelper->hasCustomField('freshman')) {
            $this->formHelper->addCustomField('freshman', FreshmanField::class);
        }

        $this
            ->setModuleName(MEMBER_MODULE_SCREEN_NAME)
            ->setValidatorClass(MemberCreateRequest::class)
            ->withCustomFields()
            ->add('id_login', 'text', [
                'label'      => __('사용자 ID'),
                'label_attr' => ['class' => 'control-label required '],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('nickname', 'text', [
                'label'      => __('닉네임'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => '닉네임',
                    'data-counter' => 120,
                ],
            ])
            ->add('fullname', 'text', [
                'label'      => __('사용자 이름'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('email', 'email', [
                'label'      => trans('plugins/member::member.form.email'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => __('campus.evaluation.exam').': example@gmail.com',
                    'data-counter' => 60,
                ],
            ])
            ->add('is_change_password', 'checkbox', [
                'label'      => trans('plugins/member::member.form.change_password'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class' => 'hrv-checkbox',
                ],
                'value'      => 1,
            ])
            ->add('password', 'password', [
                'label'      => trans('plugins/member::member.form.password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? ' hidden' : null),
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label'      => trans('plugins/member::member.form.password_confirmation'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ($this->getModel() ? ' hidden' : null),
                ],
            ])
            ->add('passwd_garden', 'hidden', [
                'value'      => old('role_member_id', $this->getModel() ? $this->getModel()->passwd_garden  : strtoupper( substr(md5(microtime()),rand(0,26),4) )),
            ])
            ->add('freshman1', 'freshman', [
                // 'label'        => 'Freshman 1',
                'label'        => '새싹 인증',
                'status_name'  => 'status_fresh1',
                'value_status' => $this->getModel()  ? $this->getModel()->status_fresh1 : 0,
                'note_value' => $this->getModel()  ? $this->getModel()->note_freshman1 : '',
                'note_label' => '신입생 1 비고',
                'note_name' => 'note_freshman1',
            ])
            ->add('freshman2', 'freshman', [
                // 'label'      => 'Freshman 2',
                'label'      => '이화이언 인증',
                'status_name'      => 'status_fresh2',
                'value_status' => $this->getModel()  ? $this->getModel()->status_fresh2 : 0,
                'note_value' => $this->getModel()  ? $this->getModel()->note_freshman2 : '',
                'note_label' => '신입생 2 비고',
                'note_name' => 'note_freshman2',
            ])

            ->add('block_user', 'select', [
                'label' => __('block_user'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    =>  STATUS_BLOCK_USER,
                'value'      => old('block_user', $this->getModel()->block_user ?? 0),
            ])

            ->add('start_block_time', 'text', [
                'label'         => '차단시작일자',
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker add_index',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
                'value'      => $start_block_time,
            ])

            ->add('end_block_time', 'text', [
                'label'         => '차단종료일자',
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'class'            => 'form-control datepicker add_index',
                    'data-date-format' => 'yyyy-mm-dd',
                ],
                'value'      => $end_block_time,
            ])

            ->add('block_reason', 'text', [
                'label'      => '차단 이유',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => '차단 이유',
                    'data-counter' => 120,
                ],
            ])

            ->add('role_member_id', 'select', [
                // 'label' => 'Roles',
                'label' => '역할',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => getCategoryEventsName($roles),
                'value'      => old('role_member_id',$selected_roles->name ?? 0),
            ])
            ->add('certification', 'select', [
                // 'label' => 'Certification',
                'label' => '인증 상태',
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ["real_name_certification" => "실명 인증", "sprout" => "새싹 인증","certification" => "이화인 인증"],
            ])
            ->add('student_number', 'text', [
                'label'      => __('student_number'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  =>__('student_number'),
                    'data-counter' => 120,
                ],
            ])
//            ->add('is_blacklist', 'select', [
//                // 'label' => "Is Blacklist",
//                'label' => "차단 여부",
//                'label_attr' => ['class' => 'control-label required'],
//                'attr' => [
//                    'class' => 'form-control select-full',
//                ],
//                'choices' => Member::isBlackList() ,
//            ])
            ->setBreakFieldPoint('role_member_id');
    }
}
