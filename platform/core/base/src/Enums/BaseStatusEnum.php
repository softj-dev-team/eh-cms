<?php

namespace Botble\Base\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static BaseStatusEnum DRAFT()
 * @method static BaseStatusEnum PUBLISH()
 * @method static BaseStatusEnum PENDING()
 * @method static BaseStatusEnum CAN_APPLY()
 * @method static BaseStatusEnum APPROVE()
 * @method static BaseStatusEnum NOTICE()
 */
class BaseStatusEnum extends Enum
{
    public const PUBLISH = 'publish';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const CAN_APPLY = 'can_apply';
    public const APPROVE = 'approve';
    public const NOTICE = 'notice';

    /**
     * @var string
     */
    public static $langPath = 'core/base::enums.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        $arr = [
            'publish' => '등록',
            'draft' => '초안',
            'pending' => '보류중',
            'can_apply' => '화원장 신청 가능',
            'approve' => '승인',
            'notice' => '공지사항',

        ];
        switch ($this->value) {
            case self::DRAFT:
//                return Html::tag('span', self::DRAFT()->label(), ['class' => 'label-info status-label'])
                return Html::tag('span', $arr[self::DRAFT], ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::PENDING:
                return Html::tag('span', $arr[self::PENDING], ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::PUBLISH:
                return Html::tag('span', $arr[self::PUBLISH], ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::CAN_APPLY:
                return Html::tag('span', $arr[self::CAN_APPLY], ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::APPROVE:
                return Html::tag('span', $arr[self::APPROVE], ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::NOTICE:
                return Html::tag('span', $arr[self::NOTICE], ['class' => 'label-success status-label'])
                    ->toHtml();
            default:
                return null;
        }
    }

    public static function arrStatus() {
        return
            [
                'publish' => '등록',
                'draft' => '초안',
                'pending' => '보류중',
                'can_apply' => '화원장 신청 가능',
                'approve' => '승인',
                'notice' => '공지사항',

        ];
    }

    public static function arrStatus1() {
        return
            [
                'publish' => '등록',
                'draft' => '초안',
                'pending' => '보류중',
            ];
    }
}
