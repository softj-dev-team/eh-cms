<footer id="footer" class="footer">
  <div class="container">
    <div class="footer__wrapper">
      <div class="footer__logo">
        <a href="/" title="">
          <img src="/storage/uploads/back-end/logo/ewha-logo-grey.png" width="100px" alt="">
        </a>
      </div>
      <div class="footer__content">
        <div class="row justify-content-between no-gutters">
          <div class="col-lg-6">
            <ul class="footer__menu menu">
              <li class="menu__item">{{__('footer.donation')}} 110-026-784849</li>
              <li class="menu__item"><a class="menu__link" href="{{route('eh_introduction.list')}}"
                                        title="{{__('eh-introduction.title')}}">{{__('eh-introduction.title')}}</a></li>
            </ul>
          </div>
          <div class="col-lg-6">
            <ul class="footer__menu footer__menu--right menu">
              <li class="menu__item"><a class="menu__link" href="{{getTermConditions()}}"
                                        title="{{__('footer.terms')}}">{{__('footer.terms')}}</a></li>
              <li class="menu__item"><a class="menu__link" href="{{getPolicyPage()}}"
                                        title="{{__('footer.policy')}}">{{__('footer.policy')}}</a></li>
              <li class="menu__item"><a class="menu__link" href="{{route('eh_introduction.contact')}}"
                                        title="{{__('footer.contact_us')}}">{{__('footer.contact_us')}}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="footer__copy">
      {{__('footer.copyright_by')}} <a href="/" title="Ewhaian.com"
                                       class="footer__link">Ewhaian.com </a>{{__('footer.all_right_reserved')}}
      <a href="https://www.facebook.com/ewhaian2001" title="Facebook" class="footer__social" target="_blank">
        <svg width="7" height="15" aria-hidden="true">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_facbook"></use>
        </svg>
      </a>
      <a href="https://blog.naver.com/ewhaianblog/" title="Naver" class="footer__social" target="_blank">
        <svg width="11" height="11" aria-hidden="true">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_naver"></use>
        </svg>
      </a>
      <a href="https://www.instagram.com/ewhaian_2001/" title="Instagram" class="footer__social" target="_blank">
        <svg width="16" height="16" aria-hidden="true">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_instagram"></use>
        </svg>
      </a>
    </div>
  </div>
</footer>

<div class="modal fade modal--post-confirm" id="post-confirm" tabindex="-1" role="dialog" aria-labelledby="post-confirm"
     aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-inner">
        <div class="modal-body">
          <p class="desc">Are you sure to submit this information? </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary">OK</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo file_get_contents("themes/ewhaian/img/symbol.svg"); ?>
<script type="text/javascript" src="/themes/ewhaian/js/popper.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/slick.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/datetimepicker.home.main.min.js"></script>
<script type="text/javascript" src="/themes/ewhaian/js/jquery.rateyo.js"></script>


<script type="text/javascript" src="/themes/ewhaian/js/jquery.mCustomScrollbar.js"></script>
<script type="text/javascript" src="/themes/ewhaian/dist/main.js"></script>


{{-- <script>
  $(function(){
      setTimeout(function () {
         $('body').addClass('pre-loaded');
     }, 3000);
  });
  </script> --}}

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopupSetting" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document" style="width: 600px">
    <div class="modal-content">
      <div class="modal-header align-items-center justify-content-lg-center">
        <span class="modal__key">
          <svg width="40" height="18" aria-hidden="true">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
          </svg>
        </span>
      </div>
      <div class="modal-body">

        <div class="d-lg-flex align-items-center mx-3">
          <div class="d-lg-flex align-items-start flex-grow-1">
            {{-- <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <!-- <input type=" text" id="hint" value="비밀번호를 입력하십시오" placeholder="&nbsp;" readonly> -->
                <input type=" text" id="hint" value="{{__('footer.password_placeholder')}}" placeholder="&nbsp;" readonly>
              </label>
            </div> --}}
            <div class="form-group form-group--1 flex-grow-1 mb-3">
              <label for="passwd_member" class="form-control form-control--hint">
                <input type="password" id="passwd_member" name="passwd_member" placeholder="{{__('footer.set_password')}}" value=""
                       maxlength="16" required>
                <!-- <span class="form-control__label" style="pointer-events: none;">비밀번호를 입력해 주세요.</span> -->
                {{-- <span class="form-control__label" style="pointer-events: none;">{{__('footer.set_password')}}</span> --}}
              </label>
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <span class="form-control__hint hint_setting"></span>
            </div>
          </div>
          <div class="button-group mb-2" style="display:flex">
            <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal" style="width: 70px;height:2.5em !important">
              <!-- <span style="display: block; width: 30px;">취소</span> -->
              <span style="display: block; width: 30px;">{{__('footer.cancel')}}</span>
            </button>
            <button type="button" class="btn btn-primary checkPasswdSetting" data-id="" style="width: 70px;height:2.5em !important">
              <!-- <span style="display: block; width: 30px;">저장</span> -->
              {{-- <span style="display: block; width: 30px;">{{__('footer.save')}}</span> --}}
              <span style="display: block; width: 30px;">확인</span>

            </button>
            <input type="hidden" name="linkId" id="linkId" value=""/>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>

  $(document).on("click", ".open-AddBookDialog", function () {
      var Infolink = $(this).data('id');
      $("#linkId").val( Infolink );
  });

  $(document.body).on('click', '.checkPasswdSetting', function (e) {
    e.preventDefault();
    if ($('[name="passwd_member"]').val() == '') {
      alert('Password is required');
      $('[name="passwd_member"]').focus();
      return;
    }

    var infoLink = $('#linkId').val();

    $.ajax({
      type: 'POST',
      url: '{{route("ewhaian.passwd")}}',
      data: {
        _token: "{{ csrf_token() }}",
        'passwd_member': $('[name="passwd_member"]').val(),
      },
      success: function (data) {
        if (data.check == true) {
            if(infoLink == 'info'){
                window.location.replace("{{ route('public.member.settings')}}")
            }else{
                window.location.replace("{{ route('public.member.notification')}}")
            }

        } else {
          $('.hint_setting').html(data.msg);
        }

      },
    });

  });

</script>
