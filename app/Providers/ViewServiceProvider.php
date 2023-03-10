<?php


namespace App\Providers;

use Botble\Contents\Models\CategoriesContents;
use Botble\Contents\Models\Contents;
use Botble\Events\Models\CategoryEvents;
use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Introduction;
use Botble\Introduction\Models\Notices\NoticesIntroduction;
use Botble\Life\Models\Ads\Ads;
use Botble\Life\Models\Flare;
use Botble\Life\Models\Jobs\JobsPartTime;
use Botble\Life\Models\OpenSpace\OpenSpace;
use Botble\Life\Models\Shelter\Shelter;
use Botble\Member\Models\MemberNotes;
use Botble\Slides\Models\Slides;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{
    /*public function register()
    {

    }*/

    public function boot()
    {
        view()->composer([
            'theme.ewhaian::partials.header'
        ], function ($view) {
            View::share('composer_EVENT_SUB_MENU',CategoryEvents::orderby('created_at','DESC')->take(5)->get());
            View::share('composer_CONTENTS_SUB_MENU', CategoriesContents::orderby('created_at','DESC')->take(5)->get());
            View::share('composer_INTRODUCTION_SUB_MENU', CategoriesIntroduction::orderby('created_at','DESC')->take(5)->get());
        });

        view()->composer([
            'theme.ewhaian::views.index'
        ], function ($view) {
            View::share('composer_EVENT_SUB_MENU',CategoryEvents::orderby('created_at','DESC')->take(4)->get());
            View::share('composer_CONTENTS_SUB_MENU', CategoriesContents::orderby('created_at','DESC')->take(4)->get());
            View::share('composer_CONTENTS_MAIN_COLORFUL', Contents::where('is_main_content', true)->where('categories_contents_id', 5)->orderby('created_at','DESC')->take(4)->get());
            View::share('composer_CONTENTS_MAIN_CAULTURE', Contents::where('is_main_content', true)->where('categories_contents_id', 4)->orderby('created_at','DESC')->take(4)->get());
            View::share('composer_CONTENTS_MAIN_NOTEBOOK', Contents::where('is_main_content', true)->where('categories_contents_id', 3)->orderby('created_at','DESC')->take(4)->get());
            View::share('composer_CONTENTS_MAIN_CONTRIBUTE', Contents::where('is_main_content', true)->where('categories_contents_id', 2)->orderby('created_at','DESC')->take(4)->get());
            View::share('composer_SLIDES_HOME', Slides::where('code','SLIDES')->where('status','publish')->first() );
            View::share('composer_HOME', Slides::where('code','HOME')->where('status','publish')->first() );

            View::share('composer_OPEN_SPACE', OpenSpace::withCount(['dislikes'])->has('dislikes','<',10)->where('status', 'publish')->orderby('published','DESC')->take(4)->get() );
            View::share('composer_LIFE_FLARE', Flare::withCount(['dislikes'])->has('dislikes','<',10)->where('status','!=', 'draft')->orderBy('published', 'DESC')->take(4)->get() );
            View::share('composer_LIFE_JOBS',JobsPartTime::withCount(['dislikes'])->has('dislikes','<',10)->rejectcategories()->orderBy('published', 'DESC')->take(4)->get() );
            View::share('composer_LIFE_SHELTER', Shelter::withCount(['dislikes'])->has('dislikes','<',10)->where('status', 'publish')->orderby('published','DESC')->take(4)->get() );
            View::share('composer_LIFE_ADS', Ads::where(function ($query) {
                $today = date("Y-m-d 00:00:00");
                $query->where('deadline', '>=', $today)->orWhere('is_deadline', 0);
            })->orderby('published','DESC')->take(4)->get() );

            View::share('composer_NOTICE', NoticesIntroduction::where('status','publish')->orderby('created_at','DESC')->take(3)->get() );
            View::share('composer_SLIDES_HOME_MOBILE', Slides::where('code','SLIDES_MOBILE')->where('status','publish')->first() );
            View::share('composer_SLIDES_ACCOUNT', Slides::where('code','ACCOUNT')->where('status','publish')->first() );
        });

        view()->composer([
            'plugins.member::components.header'
        ], function ($view) {
            View::share('composer_EVENT_SUB_MENU',CategoryEvents::orderby('created_at','DESC')->take(5)->get());
            View::share('composer_CONTENTS_SUB_MENU', CategoriesContents::orderby('created_at','DESC')->take(5)->get());
            View::share('composer_INTRODUCTION_SUB_MENU', CategoriesIntroduction::orderby('created_at','DESC')->take(5)->get());
        });
        view()->composer([
            'theme.ewhaian::partials.account_management'
        ], function ($view) {
            View::share('composer_MEMBER_NOTES',MemberNotes::where('member_to_id',auth()->guard('member')->user()->id)->where('status','unread')->orderBy('created_at','DESC')->count()   );
        });

        view()->composer([
            'theme.ewhaian::views.garden.index'
        ], function ($view) {
            View::share('composer_SLIDES_HOME', Slides::where('code','SLIDES')->where('status','publish')->first() );
        });

        view()->composer([
            'theme.ewhaian::partials.garden.menu'
        ], function ($view) {
            View::share('composer_SLIDES_ACCOUNT', Slides::where('code','ACCOUNT_GARDEN')->where('status','publish')->first() );
        });
    }
}
