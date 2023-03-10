<style>
  .item_profile {
    cursor: pointer;
  }
  .account-management .account__btn {
    position: relative;
    top: 17px;
    left:0;
  }
  .account-management .account__btn.opened {
    top: 10px;
  }
  .account-management {
    padding-bottom:10px;
  }
  .redItem {
    height: 100%;
  }
  .redItemIcon {
    vertical-align: top;
    padding-top: 18px;
  }
  .redItemText {
      vertical-align: bottom;
      /*width: 45px;*/
      position: absolute;
      bottom: 15px;
      left: 0px;
      right: 0px;
  }
  .item__inner{
    height: 100%;
  }
  @media (max-width: 991px) {
      .account-management .account .menu__control {
          display: flex;
          margin: 0 -7px;
          margin-top: 1.57143em;
          margin-bottom: 0;
          justify-content: space-around;
      }
      .account-management .account .menu__item {
          max-width: 100%;
      }
      .account-management .account .menu__item a {
          display: inline-block;
          width: 8vw;
          height: 8vw;
          min-width: 37px;
          border-radius: 50%;
          background-color: #f4f4f4;
          position: relative;
          color: #444444;
          min-height: 37px;
      }
    }
</style>

<script>
  $(document).ready(function () {
    $('#hide-button-message').click(() => {
        $('#hide-button-message').hide();
        $('.account__level').hide();
        $('#show-button-message').show();
      })

      $('#show-button-message').click(() => {
        $('#hide-button-message').show();
        $('.account__level').show();
        $('#show-button-message').hide();
      })
  });
</script>
<section class="account-management">
  <div class="account">
    <div class="account__info">
      <a href="{{ route('public.member.dashboard') }}">
        <img class="account__image" src="{{ getLvlImage() }}" alt="account image"></a>
      <div class="account__name"
           style="display: inline-block;padding-left: 4.33333em;">{{ auth()->guard('member')->user()->nickname }}
           <span id="show-button-message" style="cursor: pointer;color:#444444">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="padding-bottom: 1px"
                 class="bi bi-eye-fill" viewBox="0 0 16 16">
              <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
              <path
                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
            </svg>
          </span>
          <span id="hide-button-message" style="cursor: pointer;display:none;color:#444444">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
              <path
                d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.027 7.027 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.088z"/>
              <path
                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6l-12-12 .708-.708 12 12-.708.707z"/>
            </svg>
          </span>
      </div>
    </div>
    @if (session('permission'))
      <div style="padding: 0 1.42857em;">
        <div class="alert alert-danger" style="margin-bottom: 6px;margin-top: 6px;
            display: flex;
            justify-content: space-between;">
          {{ session('permission') }}
        </div>
      </div>
    @endif
    <div class="account__level_menu">
      <div class="account__level level" style="display: none">
        <p class="level__label">
          <span class="level__number">{{__('home.level')}} {{ getLevelMember() }}</span>
          <span class="level__percent">{{getPercentLevelMember().'%'}}</span>
        </p>
        <div class="level__bar"><span style="width: {{getPercentLevelMember().'%'}}" class="level__progress"></span></div>
      </div>

      <div class="account__menu menu togglefade">
        <div class="menu__control">
          <div class="menu__item">
            <a href="{{route('public.member.message.index')}}" title="{{__('home.reply')}}">
              @if( $composer_MEMBER_NOTES > 0)
                <span class="menu__number">{{$composer_MEMBER_NOTES}}</span>
              @endif
              <svg width="18" height="13" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_email"></use>
              </svg>
            </a>
            <span class="menu__text">{{__('home.reply')}}</span>
          </div>
          <div class="menu__item">
            <a href="#" title="" class="togglefade__control" title="{{__('home.note')}}<">
              <svg width="14" height="14" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_pencil"></use>
              </svg>
            </a>
            <span class="menu__text">{{__('home.note')}}</span>
          </div>
          <div class="menu__item">
            <a href="javascript:void(0)" title="{{__('home.personal')}}" data-toggle="modal" class="open-AddBookDialog"
               data-target="#confirmPopupSetting" data-id="info">
              <img src="{{Theme::asset()->url('img/person-24px.svg')}}" alt="Image"
                   style="opacity: 0.5;display: block; position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">
            </a>
            <span class="menu__text">{{__('home.personal')}}</span>
          </div>
          <div class="menu__item">
            <a href="javascript:void(0)" title="{{__('header.setting')}}" data-toggle="modal" class="open-AddBookDialog"
               data-target="#confirmPopupSetting" data-id="notify">
              {{--                        <span class="menu__number">2</span>--}}
              <svg width="17" height="17" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_setting"></use>
              </svg>
            </a>
            <span class="menu__text">{{__('header.setting')}}</span>
          </div>
          <div class="menu__item">
            <a href="javascript:void(0)" title="{{__('home.sign_out')}}"
               onclick="event.preventDefault(); document.getElementById('logout-form-account').submit();">
              <svg width="16" height="15" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_logout"></use>
              </svg>
            </a>
            <span class="menu__text">{{__('home.sign_out')}}</span>
            <form id="logout-form-account" action="{{ route('public.member.logout') }}" method="POST"
                  style="display: block;">
              @csrf
            </form>
          </div>
        </div>

        <!-- note -->
        <div class="note-form togglefade__content" style="margin-top: -5px; ">
          <div class="form-group" style="margin-top:0.51rem">

            {{-- <input type="text" class="form-control" placeholder="{{__('home.search_for_recipent')}}" required
                   id="id_login_to"> --}}
            <input type="text" class="form-control" placeholder="받는사람 아이디" required
                   id="id_login_to">
          </div>
          <div class="form-group">
                      <textarea style="padding-top:0.51rem !important;" name="" id="message_for_member" cols="30" rows="10"
                                class="form-control form-control--textarea"
                                placeholder="{{__('home.message')}}" required></textarea>
          </div>
          <button class="note-form__submit btn" id="note-form__submit" style="margin-left:0px">{{__('home.send')}}</button>
          <button class="note-form__submit btn" id="note-form__show" style="display: none">{{__('home.reply')}}</button>
        </div>
        <!-- end of note -->
      </div>
    </div>

    <div class="account__interesting interesting togglefade" id="interesting">
      <div class="interesting__list">
        <div class="row no-gutters">
          <div class="col">
            <div class="item item_profile" data-href="{{route('gardenFE.list')}}" title="{{__('home.secret_garden')}}">
              <div class="item__inner">
                <div class="redItem">
                  <div class="redItemIcon">
                    <svg width="26" height="12" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_secret_garden">
                      </use>
                    </svg>
                  </div>
                  <div class="redItemText">
                    <p>{{__('home.secret_garden')}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href="{{route('egardenFE.home')}}" title="{{__('home.your_e-flower')}}">
              <div class="item__inner">
                <div class="redItem">
                  <div class="redItemIcon">
                    <span class="icon icon--e">E</span>
                  </div>
                  <div class="redItemText">
                    <p>{{__('home.your_e-flower')}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href="{{route('life.flare_market_list')}}"
                 title="{{__('home.flea_market')}}">
              <div class="item__inner">
                <div class="redItem">
                  <div class="redItemIcon">
                    <svg width="18" height="17" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_flea_market">
                      </use>
                    </svg>
                  </div>
                  <div class="redItemText">
                    <p>{{__('home.flea_market')}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href="{{route('life.part_time_jobs_list')}}"
                 title="{{__('home.lets_alvar')}}">
              <div class="item__inner">
                <div class="redItem">
                  <div class="redItemIcon">
                    <svg width="23" height="14" aria-hidden="true">
                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                    </svg>
                  </div>
                  <div class="redItemText">
                    <p>{{__('home.lets_alvar')}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href="https://www.ewha.ac.kr/ewha/index.do"
                 title="{{__('home.school')}}">
              <div class="item__inner">
                <div class="redItemIcon">
                  <svg width="21" height="21" aria-hidden="true">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_school"></use>
                  </svg>
                </div>
                <div class="redItemText">
                  <p>{{__('home.school')}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href="javascript:void(0)" title="{{__('home.long-lasting')}}">
              <div class="item__inner">
                <div class="redItemIcon">
                  <span class="icon icon--d">D-{{getCampusLastDay()}}</span>
                </div>
                <div class="redItemText">
                  <p>{{__('home.long-lasting')}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item item_profile" data-href='{{route('scheduleFE.list')}}' title="{{__('home.schedule')}}">
              <div class="item__inner">
                <div class="redItemIcon">
                  <span class="icon icon--e">S</span>
                </div>
                <div class="redItemText">
                  <p>{{__('home.schedule')}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">{{-- item item--more togglefade__control --}}
              <div class="item__inner">
                <p></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="interesting__list interesting__list--all togglefade__content">
        <div class="row no-gutters">
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <svg width="26" height="12" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_secret_garden">
                  </use>
                </svg>
                <p>{{__('home.secret_garden')}}</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <span class="icon icon--e">E</span>
                <p>{{__('home.your_e-flower')}}</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <svg width="18" height="17" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_flea_market">
                  </use>
                </svg>
                <p>{{__('home.flea_market')}}</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <svg width="23" height="14" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_alvar"></use>
                </svg>
                <p>{{__('home.lets_alvar')}}</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <svg width="21" height="21" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_school"></use>
                </svg>
                <p>{{__('home.school')}}</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="item">
              <button class="item__close">
                <svg width="4" height="4" aria-hidden="true">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_close"></use>
                </svg>
              </button>
              <div class="item__inner">
                <span class="icon icon--d">D-81</span>
                <p>{{__('home.long-lasting')}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="position: relative;text-align:center">
      <button class="account__btn" data-id="interesting">
        <svg width="16" height="7" aria-hidden="true">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow"></use>
        </svg>
      </button>
    </div>
  </div>
</section>
<div class="d-flex login-form__footer justify-center">
  <a href="javascript:void();" title="Forgot ID/Password" onclick="$('#addErrorPage').modal('show')"
     class="login-form__link">
    사이트 오류신고
  </a>
</div>
<div id="addErrorPage" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body with-padding">
        <div class="alert alert-success d-none" id="alertSuccess">성공</div>
        * 오류 내용을 구체적으로 적어주시길 바랍니다.
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
          <button type="submit" class="btn btn-success" onclick="sendError()">확인</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">닫다</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function sendError(){
    $.post('/error', {
      data: CKEDITOR.instances['content'].getData()
    }).then((response) => {
      $('#alertSuccess').removeClass('d-none')
    })
  }
  $(function () {
    $('.togglefade__control').on('click', function () {
      $('#note-form__submit').css('display', 'block');
      $('#note-form__show').css('display', 'none');

      $('#id_login_to').attr('readonly', false);
      $('#id_login_to').val('');
      $('#message_for_member').attr('readonly', false);
      $('#message_for_member').val('');
    });
    $('#note-form__submit').on('click', function (e) {
      e.preventDefault();

      $('#id_login_to').attr('readonly', false);
      if ($('#id_login_to').val() == '') {
        // alert('Please enter your ID.');
        alert('메시지를 수신할 회원의 ID를 입력해주세요');
        $('#id_login_to').focus();
        return;
      }
      if ($('#message_for_member').val() == '') {
        // alert('Please enter your message.');
        alert('메시지를 입력해 주세요.');
        $('#message_for_member').focus();
        return;
      }
      var $this = $(this);
      if ($this.data('isRunAjax') == true) {
        return;
      }
      $this.css('pointer-events', 'none').data('isRunAjax', true);

      $.ajax({
        type: 'POST',
        url: '{{route('public.member.message.send')}}',
        data: {
          _token: "{{ csrf_token() }}",
          id_login_to: $('#id_login_to').val(),
          member_from_id: {{auth()->guard('member')->user()->id}},
          contents: $('#message_for_member').val()
        },
        success: function (data) {
          if (data['status'] == false) {
            alert(data['message']);
            $('#id_login_to').focus();
          }
          if (data['status'] == true) {
            alert(data['message']);
            $('#id_login_to').val('');
            $('#message_for_member').val('');
            $('#id_login_to').focus();
          }
        }
      }).always(function () {
        $this.css('pointer-events', 'auto').data('isRunAjax', false);
      });
    });
  });
  $('.item_profile').on('click', function () {
    window.location.href = $(this).data('href');
  })
</script>
