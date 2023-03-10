<?php

namespace App\Traits;

use Botble\Member\Models\MemberSetting;
use Botble\Member\Models\MemberToken;

trait NotificationTrait
{

    public static function isGetNotification($id, $settings, $controller) {
        return true;
    }

    public static function notifyStatic($title, $body, $memberIds, $type_noti) {
        $registrationIds = [];

        if ($type_noti === 'site_notice') {
            $idFilters = $memberIds;

        } else {
            $idFilters = [];
            foreach($memberIds as $id) {
                $setting = MemberSetting::where('member_id', $id) -> first();
                if(@$setting[$type_noti] == 1) {
                    $idFilters[] = $id;
                }
            }
        }

        $tokens = MemberToken::whereIn('member_id', $idFilters)->get();

        foreach ($tokens as $token) {
            if($token->device && !in_array($token->device, $registrationIds)){
                $registrationIds[] = $token->device;
            }

        }

        $curl = curl_init();

        $data = [
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
//            'registration_ids' => array_unique($registrationIds)
            'registration_ids' =>$registrationIds
        ];

        $headers = [
            'Authorization: key=' . env('FIRE_BASE_SERVER_KEY'),
            'project_id: ' . env('FIRE_BASE_PROJECT_NUMBER'),
            'Content-Type: application/json'
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
    }

    public function notify($title, $body, $memberIds, $type_noti) {
        self::notifyStatic($title, $body, $memberIds, $type_noti);
    }
}
