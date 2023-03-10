<?php

namespace Botble\Member\Providers;

use Illuminate\Routing\Events\RouteMatched;
use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Member\Http\Middleware\RedirectIfMember;
use Botble\Member\Http\Middleware\RedirectIfNotMember;
use Botble\Member\Models\ForbiddenKeywords;
use Botble\Member\Models\Member;
use Botble\Member\Models\MemberActivityLog;
use Botble\Member\Models\MemberNotify;
use Botble\Member\Models\Notify;
use Botble\Member\Repositories\Caches\ForbiddenKeywordsCacheDecorator;
use Botble\Member\Repositories\Caches\MemberActivityLogCacheDecorator;
use Botble\Member\Repositories\Caches\MemberCacheDecorator;
use Botble\Member\Repositories\Caches\MemberNotifyCacheDecorator;
use Botble\Member\Repositories\Caches\NotifyCacheDecorator;
use Botble\Member\Repositories\Eloquent\ForbiddenKeywordsRepository;
use Botble\Member\Repositories\Eloquent\MemberActivityLogRepository;
use Botble\Member\Repositories\Eloquent\MemberNotifyRepository;
use Botble\Member\Repositories\Eloquent\MemberRepository;
use Botble\Member\Repositories\Eloquent\NotifyRepository;
use Botble\Member\Repositories\Interfaces\ForbiddenKeywordsInterface;
use Botble\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\Member\Repositories\Interfaces\MemberInterface;
use Botble\Member\Repositories\Interfaces\MemberNotifyInterface;
use Botble\Member\Repositories\Interfaces\NotifyInterface;
use Event;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MailVariable;

class MemberServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        config([
            'auth.guards.member'     => [
                'driver'   => 'session',
                'provider' => 'members',
            ],
            'auth.providers.members' => [
                'driver' => 'eloquent',
                'model'  => Member::class,
            ],
            'auth.passwords.members' => [
                'provider' => 'members',
                'table'    => 'member_password_resets',
                'expire'   => 60,
            ],
            'auth.guards.member-api' => [
                'driver'   => 'passport',
                'provider' => 'members',
            ],
        ]);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('member', RedirectIfNotMember::class);
        $router->aliasMiddleware('member.guest', RedirectIfMember::class);

        $this->app->singleton(MemberInterface::class, function () {
            return new MemberCacheDecorator(new MemberRepository(new Member));
        });

        $this->app->singleton(MemberActivityLogInterface::class, function () {
            return new MemberActivityLogCacheDecorator(new MemberActivityLogRepository(new MemberActivityLog));
        });

        //Open Space Comments
        $this->app->singleton(ForbiddenKeywordsInterface::class, function () {
            return new ForbiddenKeywordsCacheDecorator(new ForbiddenKeywordsRepository(new ForbiddenKeywords));
        });
        //Notify
        $this->app->singleton(NotifyInterface::class, function () {
            return new NotifyCacheDecorator(new NotifyRepository(new Notify));
        });
        //MemberNotify
        $this->app->singleton(MemberNotifyInterface::class, function () {
            return new MemberNotifyCacheDecorator(new MemberNotifyRepository(new MemberNotify));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->setNamespace('plugins/member')
            ->loadAndPublishConfigurations(['general', 'permissions', 'assets'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api'])
            ->loadMigrations()
            ->publishAssetsFolder()
            ->publishPublicFolder();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'menu-member',
                    'priority'    => 22,
                    'parent_id'   => null,
                    'name'        => '회원 관련 관리',
                    'icon'        => 'fa fa-users',
                    'url'         => '',
                    'permissions' => ['member.list'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-member',
                    'priority'    => 23,
                    'parent_id'   => 'menu-member',
                    'name'        => '회원 관련 관리',
                    'icon'        => '',
                    'url'         => route('member.list'),
                    'permissions' => ['member.list'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-member-forbidden',
                    'priority'    => 24,
                    'parent_id'   => '',
                    'name'        => '검색 금지 단어',
                    'icon'        => 'fas fa-basketball-ball',
                    'url'         => route('member.forbidden.list'),
                    'permissions' => ['member.forbidden.list'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-member-notify',
                    'priority'    => 25,
                    'parent_id'   => 'menu-member',
                    'name'        => '푸시 알림 보내기',
                    'icon'        => '',
                    'url'         => route('member.notify.list'),
                    'permissions' => ['member.nofity.list'],
                ]);
//                ->registerItem([
//                    'id'          => 'cms-core-member-blacklist',
//                    'priority'    => 26,
//                    'parent_id'   => 'menu-member',
//                    'name'        => '신고 회원',
//                    'icon'        => '',
//                    'url'         => route('member.blacklist.list'),
//                    'permissions' => ['member.blacklist.list'],
//                ]);
        });
        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-authentication',
                    'priority'    => 22,
                    'parent_id'   => '',
                    'name'        => '회원 인증 관리',
                    'icon'        => 'fas fa-user-astronaut',
                    'url'         => route('member.authentication.sprout.list'),
                    'permissions' => ['member.authentication'],
                ])
                ->registerItem([
                    'id'          => 'cms-authentication-sprout',
                    'priority'    => 28,
                    'parent_id'   => 'cms-authentication',
                    'name'        => '새내기 인증 관리',
                    'icon'        => '',
                    'url'         => route('member.authentication.sprout.list'),
                    'permissions' => ['member.authentication.sprout.list'],
                ])
                ->registerItem([
                    'id'          => 'cms-authentication-ewhaian',
                    'priority'    => 29,
                    'parent_id'   => 'cms-authentication',
                    'name'        => '이화인 인증 관리',
                    'icon'        => '',
                    'url'         => route('member.authentication.ewhaian.list'),
                    'permissions' => ['member.authentication.ewhaian.list'],
                ]);
        });
        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-visit',
                    'priority'    => 23,
                    'parent_id'   => null,
                    'name'        => '통계 관리',
                    'icon'        => 'fas fa-glasses',
                    'url'         => '',
                    'permissions' => ['member.visits'],
                ])
                ->registerItem([
                    'id'          => 'visits-daily',
                    'priority'    => 24,
                    'parent_id'   => 'cms-visit',
                    'name'        => '일별 접속자수',
                    'icon'        => '',
                    'url'         => route('member.daily.visits'),
                    'permissions' => ['member.daily.visits'],
                ])
                ->registerItem([
                    'id'          => 'visits-weekly',
                    'priority'    => 25,
                    'parent_id'   => 'cms-visit',
                    'name'        => '주별 접속자수',
                    'icon'        => '',
                    'url'         => route('member.weekly.visits'),
                    'permissions' => ['member.weekly.visits'],
                ])
                ->registerItem([
                    'id'          => 'visits-monthly',
                    'priority'    => 26,
                    'parent_id'   => 'cms-visit',
                    'name'        => '월별 접속자수',
                    'icon'        => '',
                    'url'         => route('member.monthly.visits'),
                    'permissions' => ['member.monthly.visits'],
                ])
                ->registerItem([
                    'id'          => 'visits-user-types',
                    'priority'    => 27,
                    'parent_id'   => 'cms-visit',
                    'name'        => '회원 등급별 통계',
                    'icon'        => '',
                    'url'         => route('member.user.types.visits'),
                    'permissions' => ['member.user.types.visits'],
                ]);
        });

        MailVariable::setModule(SEND_MAIL_TO_MEMBER)
            ->addVariables([
                'member_name'    => "" ?? 'N/A',
                'member_mail'    => "" ?? 'N/A',
                'member_content'    => "" ?? 'N/A',
            ]);
        $this->app->register(EventServiceProvider::class);
    }
}
