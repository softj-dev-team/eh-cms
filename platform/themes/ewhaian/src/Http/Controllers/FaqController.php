<?php

namespace Theme\Ewhaian\Http\Controllers;

use Botble\Introduction\Models\CategoriesIntroduction;
use Botble\Introduction\Models\Faq\FaqIntroduction;
use Illuminate\Routing\Controller;
use Theme;

class FaqController extends Controller
{

    public function index()
    {
        $faq = FaqIntroduction::where('status', 'publish')->orderby('id', 'DESC')->paginate(10);
        $categories = CategoriesIntroduction::where('status', 'publish')->orderby('created_at', 'DESC')->get();

        Theme::breadcrumb()->add(__('eh-introduction'), 'http:...')->add(__('eh-introduction.faqs'), 'http:...');

        Theme::setTitle(__('eh-introduction').' | ' .__('eh-introduction.faqs'));

        return Theme::scope('intro.faq.index', ['faq' => $faq,'categories'=>$categories])->render();
    }
}
