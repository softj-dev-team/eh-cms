<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Introduction;
use Illuminate\Routing\Controller;
use Theme;

class IntroController extends Controller
{

    public function index()
    {
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();
        $detail = Introduction::where('status', 'publish')->orderby('categories_introductions_id', 'DESC')->orderby('created_at', 'DESC')->firstOrFail();

        Theme::breadcrumb()->add(__('eh-introduction'), route('eh_introduction.list'))
//            ->add($detail->title, 'http:...')
        ;

        Theme::setTitle(__('eh-introduction').' | ' . $detail->title);

        return Theme::scope('intro.details', [ 'detail' => $detail,'categories'=>$categories])->render();
    }

    public function show($id)
    {
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();
        $detail = Introduction::where('id',$id)->where('status', 'publish')->firstOrFail();

        Theme::breadcrumb()->add(__('eh-introduction'), route('eh_introduction.list'))
//            ->add($detail->title, 'http:...')
        ;

        Theme::setTitle(__('eh-introduction').' | ' . $detail->title);

        return Theme::scope('intro.details', [ 'detail' => $detail,'categories'=>$categories])->render();
    }
}
