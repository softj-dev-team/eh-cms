<style>
  .more_item {
    position: relative;
    padding-right: 1.21429em;
    margin-top: 0.92857em;
    right: 0;
    text-align: right;
  }

  .more_item a {
    color: #EC1469;
  }

  .item__post-links {
    /* min-height: calc(4* 1.42857em + 3 * 1.356714em); */
  }

  .item__post-link {
    display: block;
  }

  .item-notices {

  }

  .highlight-posts__item > .item {
    display: flex;
    flex-direction: column;
  }

  .highlight-posts__item .item__head ~ div {
    flex: auto;
  }

  .highlight-posts__item .item-notices {
    display: flex;
    flex-direction: column;
    padding-bottom: 10px;
  }

  .highlight-posts__item .tab-pane {
    height: 100%;
  }


  .highlight-posts__item .tab-pane .item-notices {
    height: 100%;
  }

  .highlight-posts__item .item-notices .item__post-links {
    flex: auto;
  }

  .sidebar-template__content > .slide_mobile {
    display: none;
  }

  @media (max-width: 1024px) {
    .highlight-posts{
      margin-top: 0;
    }

    .sidebar-template__content > .banner {
      height: 195px;
    }
  }

  @media only screen and (max-device-width: 768px) {
    .sidebar-template__content > .slide_mobile {
      margin-top: 20px;
      display: block;
    }
  }

  .loading-section {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: transparent;

    display: flex;
    flex-flow: column nowrap;
    justify-content: center; /* aligns on vertical for column */
    align-items: center; /* aligns on horizontal for column */
    z-index: 99999;
  }

  .loading-section.hide {
    display: none;
  }

  .loading-image {
    width: 68px;
    height: 66px;
    -webkit-animation: spin 4s linear infinite;
    -moz-animation: spin 4s linear infinite;
    animation: spin 4s linear infinite;
  }

  @-moz-keyframes spin {
    100% {
      -moz-transform: rotate(360deg);
    }
  }

  @-webkit-keyframes spin {
    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
</style>

<div class="loading-section hide">
  <img class="loading-image" src="/storage/uploads/back-end/logo/logo-spinner.png"/>
</div>

<main id="main-content" data-view="home" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">

      <div class="sidebar-template__control">
      @if (!auth()->guard('member')->check())
        <!-- login -->
          <form method="POST" action="{{ route('public.member.login') }}" class="login-form">
            @csrf
            <div class="login-form__inner">
              <div class="form-group">
                <input id="id_login" type="text" class="form-control{{ $errors->has('id_login') ? ' is-invalid' : '' }}"
                       name="id_login" value="{{ old('id_login') }}" placeholder="{{__('home.id')}}" autofocus
                       autocomplete="off">
                @if ($errors->has('id_login'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('id_login') }}</strong>
                </span>
                @endif
                @if ($errors->has('verifySMS'))
                  <span id="id_login_valid" style="width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">
                        <strong>{!! $errors->first('verifySMS') !!}</strong>
                    </span>
                @endif
                <span id="id_login_valid"
                      style="display: none;width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">
                    <strong> {{__('home.warming_caplock')}}</strong>
                </span>
              </div>
              <div class="form-group">
                <input id="passwordLogin" type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                       placeholder="{{__('home.password')}}" value="{{ old('password') }}">
                @if ($errors->has('password'))
                  <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
                <span id="passwordLogin_valid"
                      style="display: none;width: 100%;margin-top: .25rem;font-size: 80%;color: #dc3545;">
                    <strong> {{__('home.warming_caplock')}}</strong>
                </span>
              </div>
              @if (session('permission'))
                <div>
                  <div class="alert alert-danger" style="margin-bottom: 6px;margin-top: 6px;
                display: flex;
                justify-content: space-between;">
                    {{ session('permission') }}
                  </div>
                </div>
              @endif
              <div class="d-flex justify-content-between">
                <div class="form-check form-check--checkbox">
                  <input type="checkbox" class="form-check-input" id="remember"
                         name="remember" {{ !empty(Cookie::get('id_login')) ? 'checked' : '' }} />
                  <label class="form-check-label" for="remember">
                    <svg width="12" height="9" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check"></use>
                    </svg>
                    {{__('home.remember_me')}}
                  </label>
                </div>
                <div class="form-check form-check--checkbox">
                  <input type="checkbox" class="form-check-input" id="keep-login"
                         name="keep_login" {{ old('keep_login') ? 'checked' : '' }} />
                  <label class="form-check-label" for="keep-login">
                    <svg width="12" height="9" aria-hidden="true" class="icon">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check"></use>
                    </svg>
                    {{__('home.keep_login')}}
                  </label>
                </div>
              </div>
              <button type="submit" class="form-submit"> {{__('home.log_in')}}</button>
            </div>
            <div class="d-flex login-form__footer justify-content-between">
              <a href="{{ route('public.member.register') }}" title="Sign Up" class="login-form__link" style="margin-right: 10px;">
                <svg width="20" height="16" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_create_user"></use>
                </svg>
                {{__('home.sign_up')}}
              </a>
              <a href="{{ route('public.member.password.request') }}" title="Forgot ID/Password"
                 class="login-form__link">
                <svg width="16" height="17" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_forgot_password"></use>
                </svg>
                {{__('home.forgot_password')}}
              </a>
            </div>
            <div class="d-flex login-form__footer justify-center">
              <a href="javascript:void(0);" title="Forgot ID/Password" onclick="$('#addErrorPage').modal('show')"
                 class="login-form__link">
                사이트 오류신고
              </a>
            </div>
          </form>
          <!-- end of login -->
          <div id="addErrorPage" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  이화이언
                </div>
                <div class="modal-body with-padding">
                  <div class="alert alert-success d-none" id="alertSuccess">성공</div>
                  * 오류 내용을 구체적으로 적어주시길 바랍니다. [업로드한 이미지 첨부하기]   [이미지 업로드하기]
                  <div class="form-group mt-3">
                    <textarea class="ckeditor" name="detail" id="content" style="visibility: hidden; display: none;"></textarea>
                    <script>
                      CKEDITOR.replace( 'detail', {
                          filebrowserUploadUrl: "{{route('media.files.upload.from.editor', ['_token' => csrf_token() ])}}",
                          filebrowserUploadMethod: 'form'
                      });
                    </script>
                  </div>
                  <div class="form-actions text-right">
                    <button type="submit" class="btn btn-success" onclick="sendError()">{{ trans('core/base::system.user.create') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @else
          {!! Theme::partial('account_management') !!}
        @endif

        @if ($composer_SLIDES_ACCOUNT != null )
          {!! Theme::partial('left_banner', ['composer_SLIDES_ACCOUNT' => $composer_SLIDES_ACCOUNT, 'class' => 'desktop']) !!}
        @endif
      </div>

      <div class="sidebar-template__content">
        {!! Theme::partial('slides',['slides'=> $composer_SLIDES_HOME ?? null ]) !!}
        <div class="slide_mobile">
          {!! Theme::partial('slides',['slides'=> $composer_SLIDES_HOME_MOBILE ?? null ]) !!}
        </div>

        <!-- highlight post -->
        <div class="highlight-posts">
          <div class="row">
            <div class="col-lg-6 highlight-posts__item">
              <div class="item">
                <div class="item__head">
                  <h4 class="item__name"><a
                      href="{{route('contents.contents_list', ['idCategory'=>$composer_CONTENTS_SUB_MENU->first() ]) }}"
                      title="{{__('home.contents')}}">{{__('home.contents')}}</a></h4>
                  <ul class="item__categories nav ">
                    @if($composer_CONTENTS_SUB_MENU)
                      @foreach ($composer_CONTENTS_SUB_MENU as $key => $item)
                        <li class="item__category font-reduction">
                          <a class="item__link  @if($key == 0) active @endif" data-toggle="tab" href="#contents{{$key}}"
                             title="{{$item->name}}">{{$item->name}}</a>
                        </li>
                      @endforeach
                    @endif
                  </ul>
                </div>
                <div class="tab-content">
                  <div id="contents0" class="tab-pane active" role="tabpanel">
                      <div class="item-notices">
                        <ul class="item__post-links">
                          @if(count($composer_CONTENTS_MAIN_COLORFUL) > 0)
                            @foreach ($composer_CONTENTS_MAIN_COLORFUL as $item)
                              <li class="item__post">
                                <a class="item__post-link" href="{{route('contents.details',['idCategory'=>$item->categories_contents_id,'id'=>$item->id])}}"
                                   title="{{$item->title}}">
                                  <span class="text">{{$item->title}}</span>
                                  @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                    <span class="icon icon--gallery">
                                          <svg width="15" height="13" aria-hidden="true">
                                          <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                               xlink:href="#icon_gallery"></use>
                                          </svg>
                                      </span>
                                  @endif
                                  @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                    <span class="icon icon--n">N</span>
                                  @endif
                                </a>
                              </li>
                            @endforeach
                          @else
                            <ul class="item__post-links">
                              <li class="item__post">
                                {{__('home.no_item')}}
                              </li>
                            </ul>
                          @endif
                        </ul>
                        <div class="more_item"><a href="{{route('contents.contents_list',['idCategory'=>$item->categories_contents_id])}}"> {{__('home.more')}}</a></div>
                      </div>
                    </div>
                  <div id="contents1" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @if(count($composer_CONTENTS_MAIN_CAULTURE) > 0)
                          @foreach ($composer_CONTENTS_MAIN_CAULTURE as $item)
                            <li class="item__post">
                              <a class="item__post-link" href="{{route('contents.details',['idCategory'=>$item->categories_contents_id,'id'=>$item->id])}}"
                                 title="{{$item->title}}">
                                <span class="text">{{$item->title}}</span>
                                @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                  <span class="icon icon--gallery">
                                          <svg width="15" height="13" aria-hidden="true">
                                          <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                               xlink:href="#icon_gallery"></use>
                                          </svg>
                                      </span>
                                @endif
                                @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                  <span class="icon icon--n">N</span>
                                @endif
                              </a>
                            </li>
                          @endforeach
                        @else
                          <ul class="item__post-links">
                            <li class="item__post">
                              {{__('home.no_item')}}
                            </li>
                          </ul>
                        @endif
                      </ul>
                      <div class="more_item"><a href="{{route('contents.contents_list',['idCategory'=>$item->categories_contents_id])}}"> {{__('home.more')}}</a></div>
                    </div>
                  </div>
                  <div id="contents2" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @if(count($composer_CONTENTS_MAIN_NOTEBOOK) > 0)
                          @foreach ($composer_CONTENTS_MAIN_NOTEBOOK as $item)
                            <li class="item__post">
                              <a class="item__post-link" href="{{route('contents.details',['idCategory'=>$item->categories_contents_id,'id'=>$item->id])}}"
                                 title="{{$item->title}}">
                                <span class="text">{{$item->title}}</span>
                                @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                  <span class="icon icon--gallery">
                                          <svg width="15" height="13" aria-hidden="true">
                                          <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                               xlink:href="#icon_gallery"></use>
                                          </svg>
                                      </span>
                                @endif
                                @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                  <span class="icon icon--n">N</span>
                                @endif
                              </a>
                            </li>
                          @endforeach
                        @else
                          <ul class="item__post-links">
                            <li class="item__post">
                              {{__('home.no_item')}}
                            </li>
                          </ul>
                        @endif
                      </ul>
                      <div class="more_item"><a href="{{route('contents.contents_list',['idCategory'=>$item->categories_contents_id])}}"> {{__('home.more')}}</a></div>
                    </div>
                  </div>
                  <div id="contents3" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @if(count($composer_CONTENTS_MAIN_CONTRIBUTE) > 0)
                          @foreach ($composer_CONTENTS_MAIN_CONTRIBUTE as $item)
                            <li class="item__post">
                              <a class="item__post-link" href="{{route('contents.details',['idCategory'=>$item->categories_contents_id,'id'=>$item->id])}}"
                                 title="{{$item->title}}">
                                <span class="text">{{$item->title}}</span>
                                @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                  <span class="icon icon--gallery">
                                          <svg width="15" height="13" aria-hidden="true">
                                          <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                               xlink:href="#icon_gallery"></use>
                                          </svg>
                                      </span>
                                @endif
                                @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                  <span class="icon icon--n">N</span>
                                @endif
                              </a>
                            </li>
                          @endforeach
                        @else
                          <ul class="item__post-links">
                            <li class="item__post">
                              {{__('home.no_item')}}
                            </li>
                          </ul>
                        @endif
                      </ul>
                      <div class="more_item"><a href="{{route('contents.contents_list',['idCategory'=>$item->categories_contents_id])}}"> {{__('home.more')}}</a></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 highlight-posts__item">
              <div class="item">
                <div class="item__head">
                  <h4 class="item__name"><a href="{{route('life.open_space_list')}}"
                                            title="{{__('home.life')}}">{{__('home.life')}}</a></h4>

                  <ul class="item__categories nav ">
                    <li class="item__category">
                      <a class="item__link active" data-toggle="tab" href="#lifeTag0"
                         title="{{__('home.open_space')}}">{{__('home.open_space')}}</a>
                    </li>
                    <li class="item__category">
                      <a class="item__link " data-toggle="tab" href="#lifeTag1"
                         title="{{__('home.flea_market')}}">{{__('home.flea_market')}}</a>
                    </li>
                    <li class="item__category">
                      <a class="item__link" data-toggle="tab" href="#lifeTag2"
                         title="{{__('home.part-time')}}">{{__('home.part-time')}}</a>
                    </li>
                    <li class="item__category">
                      <a class="item__link" data-toggle="tab" href="#lifeTag3"
                         title="{{__('home.shelter_info')}}">{{__('home.shelter_info')}}</a>
                    </li>
                    <li class="item__category">
                      <a class="item__link" title="Advertisements" data-toggle="tab" href="#lifeTag4"
                         title="{{__('home.advertisements')}}">{{__('home.advertisements')}}</a>
                    </li>
                  </ul>

                </div>
                <div class="tab-content">

                  <div id="lifeTag0" class="tab-pane active" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @foreach ($composer_OPEN_SPACE as $item)
                          <li class="item__post">
                            <a class="item__post-link" href="{{route('life.open_space_details',['id'=>$item->id])}}"
                               title="{{$item->title}}">
                              <span class="text">{{$item->title}}</span>
                              @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                <span class="icon icon--gallery">
                                        <svg width="15" height="13" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#icon_gallery"></use>
                                        </svg>
                                    </span>
                              @endif
                              @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                <span class="icon icon--n">N</span>
                              @endif
                            </a>
                          </li>
                        @endforeach
                      </ul>
                      <div class="more_item"><a href="{{route('life.open_space_list')}}"> {{__('home.more')}}</a></div>
                    </div>
                  </div>
                  <div id="lifeTag1" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @foreach ($composer_LIFE_FLARE as $item)
                          <li class="item__post">
                            <a class="item__post-link" href="{{route('life.flare_market_details',['id'=>$item->id])}}"
                               title="{{$item->title}}">
                              <span class="text">{{$item->title}}</span>
                              @if(geFirsttImageInArray($item->images, 'event-thumb')  != "/vendor/core/images/placeholder.png" )
                                <span class="icon icon--gallery">
                                        <svg width="15" height="13" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#icon_gallery"></use>
                                        </svg>
                                    </span>
                              @endif
                              @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                <span class="icon icon--n">N</span>
                              @endif
                            </a>
                          </li>
                        @endforeach
                      </ul>
                      <div class="more_item"><a href="{{route('life.flare_market_list')}}"> {{__('home.more')}}</a>
                      </div>
                    </div>
                  </div>
                  <div id="lifeTag2" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @foreach ($composer_LIFE_JOBS as $item)
                          <li class="item__post">
                            <a class="item__post-link" href="{{route('life.part_time_jobs_details',['id'=>$item->id])}}"
                               title="{{$item->title}}">
                              <span class="text">{{$item->title}}</span>
                              @if($item->images )
                                <span class="icon icon--gallery">
                                        <svg width="15" height="13" aria-hidden="true">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                             xlink:href="#icon_gallery"></use>
                                        </svg>
                                    </span>
                              @endif
                              @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                <span class="icon icon--n">N</span>
                              @endif
                            </a>
                          </li>
                        @endforeach
                      </ul>
                      <div class="more_item"><a href="{{route('life.part_time_jobs_list')}}"> {{__('home.more')}}</a>
                      </div>
                    </div>
                  </div>
                  <div id="lifeTag3" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @foreach ($composer_LIFE_SHELTER as $item)
                          <li class="item__post">
                            <a class="item__post-link" href="{{route('life.shelter_list_details',['id'=>$item->id])}}"
                               title="{{$item->title}}">
                              <span class="text">{{$item->title}}</span>
                              @if($item->images )
                                <span class="icon icon--gallery">
                                            <svg width="15" height="13" aria-hidden="true">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon_gallery"></use>
                                            </svg>
                                        </span>
                              @endif
                              @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                <span class="icon icon--n">N</span>
                              @endif
                            </a>
                          </li>
                        @endforeach
                      </ul>
                      <div class="more_item"><a href="{{route('life.shelter_list')}}"> {{__('home.more')}}</a></div>
                    </div>
                  </div>
                  <div id="lifeTag4" class="tab-pane" role="tabpanel">
                    <div class="item-notices">
                      <ul class="item__post-links">
                        @foreach ($composer_LIFE_ADS as $item)
                          <li class="item__post">
                            <a class="item__post-link" href="{{route('life.advertisements_details',['id'=>$item->id])}}"
                               title="{{$item->title}}">
                              <span class="text">{{$item->title}}</span>
                              @if($item->images )
                                <span class="icon icon--gallery">
                                                <svg width="15" height="13" aria-hidden="true">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#icon_gallery"></use>
                                                </svg>
                                            </span>
                              @endif
                              @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->published))))
                                <span class="icon icon--n">N</span>
                              @endif
                            </a>
                          </li>
                        @endforeach
                      </ul>
                      <div class="more_item"><a href="{{route('life.advertisements_list')}}"> {{__('home.more')}}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 highlight-posts__item">
              <div class="item">
                <div class="item__head">
                  <h4 class="item__name"><a href="{{route('eh_introduction.notices.list') }}"
                                            title="{{__('home.notice')}}">{{__('home.notice')}}</a></h4>
                </div>
                <div class="item-notices">
                  <ul class="item__post-links">
                    @foreach ($composer_NOTICE as $item)
                      <li class="item__post">
                        <a class="item__post-link" href="{{route('eh_introduction.notices.detail',['id'=>$item->id])}}"
                           title="{{$item->name}}">
                          <span class="text">{{$item->name}}</span>
                          @if (checkImageInContent($item->notices))
                            <span class="icon icon--gallery">
                                            <svg width="15" height="13" aria-hidden="true">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon_gallery"></use>
                                            </svg>
                                        </span>
                          @endif

                          @if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime(  date("Y-m-d", strtotime($item->created_at))))
                            <span class="icon icon--n">N</span>
                          @endif
                        </a>
                      </li>
                    @endforeach
                  </ul>
                  <div class="more_item"><a href="{{route('eh_introduction.notices.list')}}"> {{__('home.more')}}</a>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-lg-6 highlight-posts__item">
              <div class="item">
                <div class="item__head">
                  <h4 class="item__name"><a
                      href="{{route('event.event_list',['idCategory'=>$composer_EVENT_SUB_MENU->first()])}}"
                      title="{{__('home.event')}}">{{__('home.event')}}</a></h4>
                  <ul class="item__categories nav">
                    @if($composer_EVENT_SUB_MENU)
                      @foreach ($composer_EVENT_SUB_MENU as $key => $item)
                        <li class="item__category">
                          <a class="item__link @if($key == 0) active @endif " title="{{$item->name}}" data-toggle="tab"
                             href="#events{{$key}}">{{$item->name}}</a>
                        </li>
                      @endforeach
                    @endif
                  </ul>
                </div>
                <div class="tab-content">
                  @foreach ($composer_EVENT_SUB_MENU as $key => $category)
                    <div id="events{{$key}}" class="tab-pane @if($key ==0) active @endif " role="tabpanel">
                      <div class="item-notices">
                        <ul class="item__post-links">
                          @if(count($category->events) > 0 )
                            @foreach ($category->events as $keysub => $item)
                              @if($keysub==3) @break  @endif
                              <li class="item__post">
                                <a class="item__post-link"
                                   href="{{route('event.details',['idCategory'=>$category->id,'id'=>$item->id])}}"
                                   title="{{$item->title}}">
                                  <span class="text">{{ $item->title }}</span>
                                  @if($item->banner)
                                    <span class="icon1 icon icon--gallery">
                                                <svg width="15" height="13" aria-hidden="true">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     xlink:href="#icon_gallery"></use>
                                                </svg>
                                            </span>
                                  @endif
                                  @if(getNew($item->published,1)) <span class="icon1 icon-label">N</span>@endif
                                </a>
                              </li>
                            @endforeach
                          @else
                            <li class="item__post">{{__('home.no_item')}}</li>
                          @endif
                        </ul>
                        <div class="more_item"><a
                            href="{{route('event.event_list',['idCategory' => $category->id ])}}"> {{__('home.more')}}</a>
                        </div>
                      </div>

                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          <!-- end of highlight post -->
        </div>
      </div>

      <div class="sidebar-template__control">
        @if ($composer_SLIDES_ACCOUNT != null )
          {!! Theme::partial('left_banner', ['composer_SLIDES_ACCOUNT' => $composer_SLIDES_ACCOUNT, 'class' => 'mobile']) !!}
        @endif
      </div>
    </div>
</main>

{{-- popup show image --}}
{!! Theme::partial('popup.showImage',['slides'=> $composer_HOME]) !!}
{{--  popup show image --}}

<script>
  const input = document.getElementById('id_login');
  const text = document.getElementById('id_login_valid');

  const input2 = document.getElementById('passwordLogin');

  const text2 = document.getElementById('passwordLogin_valid');

  if (input != null) {
    input.addEventListener('keyup', function (event) {
      if (event.getModifierState('CapsLock')) {
        text.style.display = 'block';
      } else {
        text.style.display = 'none'
      }
    });
  }
  if (input != null) {
    input2.addEventListener('keyup', function (event) {
      if (event.getModifierState('CapsLock')) {
        text2.style.display = 'block';
      } else {
        text2.style.display = 'none'
      }
    });
  }

  jQuery(function ($) {
    $(window).bind('beforeunload', function () {
      $('.loading-section').removeClass('hide');
    });

    $('.item__post-link').each(function () {
      let $textEle = $('> .text', this);
      let iconWidth = 0;
      let width = $(this).innerWidth();
      $('> .icon1', this).each(function () {
        iconWidth += $(this).outerWidth(true);
      });
      let defaultHeight = 30;
      let t = '';
      let wrapper = this;
      let flag = false;
      while ($(this).height() > defaultHeight) {
        flag = true;
        t = $textEle.text().slice(0, -1);
        $textEle.text(t);
      }
      if (flag) {
        let text = $textEle.text().slice(0, -3);
        text = text.trim();
        text += '...';
        $textEle.text(text);
      }
    });

    $('.banner_content').slick({
      dots: false,
      nextArrow:
        '<button type="button" class="slick-next"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M.85 14.169a.483.483 0 0 1-.35.145.495.495 0 0 1-.35-.845l6.155-6.155L.15 1.159a.495.495 0 0 1 .7-.7l6.505 6.505a.495.495 0 0 1 0 .7z"/></g></g></svg></button>',
      prevArrow:
        '<button type="button" class="slick-prev"><svg class="icon" xmlns="http://www.w3.org/2000/svg" width="8" height="15" viewBox="0 0 8 15"><g><g><path fill="#fff" d="M6.65 14.169a.483.483 0 0 0 .35.145.495.495 0 0 0 .35-.845L1.195 7.314 7.35 1.159a.495.495 0 0 0-.7-.7L.145 6.964a.495.495 0 0 0 0 .7z"/></g></g></svg></button>',
      autoplay: true,
      autoplaySpeed: 3000,
      variableWidth: false,
    });
  });
  function sendError(){
    $.post('/error', {
      data: CKEDITOR.instances['content'].getData()
    }).then((response) => {
      $('#alertSuccess').removeClass('d-none')
    })
  }
</script>
