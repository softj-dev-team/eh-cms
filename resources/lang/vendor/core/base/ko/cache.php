<?php

return [
    'cache_management' => '캐시 관리',
    'cache_commands' => '캐시 명령 지우기',
    'commands' => [
        'clear_cms_cache' => [
            'title' => '모든 CMS 캐시 지우기',
            'description' => 'CMS 캐싱 지우기: 데이터베이스 캐싱, 정적 블록... 데이터를 업데이트한 후 변경 사항이 표시되지 않으면 이 명령을 실행하십시오.',
            'success_msg' => '캐시 정리됨',
        ],
        'refresh_compiled_views' => [
            'title' => '컴파일된 보기 새로 고침',
            'description' => '보기를 최신 상태로 만들려면 컴파일된 보기를 지웁니다.',
            'success_msg' => '캐시 보기 새로고침',
        ],
        'clear_config_cache' => [
            'title' => '구성 캐시 지우기',
            'description' => '프로덕션 환경에서 무언가를 변경할 때 구성 캐싱을 새로 고쳐야 할 수도 있습니다.',
            'success_msg' => '구성 캐시 정리됨',
        ],
        'clear_route_cache' => [
            'title' => '경로 캐시 지우기',
            'description' => '캐시 라우팅을 지웁니다.',
            'success_msg' => '경로 캐시가 정리되었습니다.',
        ],
        'clear_log' => [
            'title' => '로그 지우기',
            'description' => '시스템 로그 파일 지우기',
            'success_msg' => '시스템 로그가 정리되었습니다',
        ],
    ],
];
