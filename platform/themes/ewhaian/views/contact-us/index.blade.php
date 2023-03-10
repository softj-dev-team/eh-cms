<main id="main-content" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- intro menu -->
                {!! Theme::partial('intro.menu',['contact'=>1,'categories'=>$categories]) !!}
                <!-- end of intro menu -->
            </div>
            <div class="sidebar-template__content">
                <div class="event-comments">
                    <ul class="breadcrumb">
                        @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                        @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                        <li>
                            <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                            <svg width="4" height="6" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                            </svg>
                        </li>
                        @else
                        <li class="active">{!! $crumb['label'] !!}</li>
                        @endif
                        @endforeach
                    </ul>
                    <div class="heading">
                        <div class="heading" style="display: flex;">
                            <div class="heading__title" style="white-space: nowrap;">
                                {{__('eh-introduction.contact')}}
                            </div>
                            <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                                {{__('eh-introduction.contact.heading__description')}}
                            </div>
                        </div>
                    </div>
                    @if (session()->has('error_msg'))
                    <div class="alert alert-danger" style="display: block;">
                        {{session('error_msg')}}
                    </div>
                    @endif
                    @if (session()->has('success_msg'))
                    <div class="alert alert-success" style="display: block;">
                        {{session('success_msg')}}
                    </div>
                    @endif
                        {{-- <form action="{{route('public.send.contact')}}" method="POST" id="my_form" accept-charset="UTF-8">
                            @csrf
                            <div class="filter align-items-center" style="display:block">
                                @if ($errors->any())
                                <div class="alert alert-danger" style="display: block;">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div style="display:flex">
                                    <div class="form-group form-group--1 flex-grow-1 mb-3"
                                        style="padding: 0 90px 0 0;">
                                        <label for="contact_email" class="form-control form-control--hint"
                                            style="border-bottom: 1px solid #EC1469;">
                                            <input type="email" id="email" name="email" placeholder="&nbsp;"
                                                value="{{auth()->guard('member')->user()->email ?? null}}" required
                                                style=" padding: 10px 0;">
                                            <span class="form-control__label" style="color:#EC1469">{{__('eh-introduction.contact.your_email')}}</span>
                                        </label>
                                    </div>

                                    <div class="form-group form-group--1 flex-grow-1 mb-3">
                                        <label for="contact_subject" class="form-control form-control--hint"
                                            style="border-bottom: 1px solid #EC1469;">
                                            <input type="text" id="subject" name="subject" placeholder="&nbsp;" value=""
                                                required style=" padding: 10px 0;">
                                            <span class="form-control__label">{{__('eh-introduction.contact.subject')}}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group form-group--1  mr-lg-20 mb-3">
                                    <label for="contact_content" class="form-control form-control--hint"
                                        style="border-bottom: 1px solid #EC1469;">
                                        <input type="text" id="content" name="content" placeholder="&nbsp;" value=""
                                            required style=" padding: 10px 0;">
                                        <span class="form-control__label">{{__('eh-introduction.contact.your_message')}}</span>
                                    </label>
                                </div>
                                <input type="hidden" name="name"
                                    value="{{auth()->guard('member')->user()->fullname ?? "anonymous"}}">
                            </div>
                            <div class="text-center">
                                <a href="javascript:;" class="btn btn-primary"
                                    onclick="document.getElementById('my_form').submit()">{{__('eh-introduction.contact.send')}}</a>
                            </div>
                        </form> --}}
                        <div class=" align-items-center" style="display:block">
                                <img src="/storage/uploads/contact.png" alt="" width="885px">
                        </div>
                </div>


            </div>
        </div>
    </div>
</main>
