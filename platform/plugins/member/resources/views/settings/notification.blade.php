@extends('plugins.member::layouts.skeleton')

@section('custom-css')
  <style>
    #confirmDeletePopup .modal-header {
      color: #ec1569;
      text-transform: uppercase;
      font-weight: bold;
    }

    .with-actions {
      margin-bottom: 1rem;
    }

    label {
      margin-bottom: 0;
    }

    .custom-control {
      display: inline-block;
    }

    .option-title {
      margin-left: 30px;
    }

    .custom-control-input:checked ~ .custom-control-label:before {
      color: #fff;
      border-color: #FA006A;
      background-color: #fff1f6;
    }

    .custom-switch .custom-control-input:checked ~ .custom-control-label:after {
      background-color: #FA006A;
      transform: translateX(.75rem);
    }

  </style>
@endsection

@section('content')
  <!---->
  @if(session('message'))
    <span class="alert alert-success" role="alert">
      <strong>{{ session('message') }}</strong>
    </span>
  @endif
  <div class="settings">
    <div class="container">
      <div class="row">
        @include('plugins.member::settings.sidebar')
        <div class="col-12 col-md-9">

          <div class="mb-5">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4 class="with-actions">
                                    <span>
                                        {{ trans('plugins/member::dashboard.notification_title') }}
                                    </span>
                  <span><i class="fas fa-bell ml-2"></i></span>
                </h4>
              </div>
            </div>

            <!-- Content -->
            <div class="row">
              <div class="col-lg-8">
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif
                <form method="POST" action="{{ route('public.member.post.notification') }}"
                      class="settings-reset">
                @csrf

                <!-- Site Notice -->
                  <div class="form-group">
                    <label>사이트 공지사항</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">공지사항 수신 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input" value="true" checked>
                      <label class="custom-control-label" for="site-notice"></label>
                    </div>
                  </div>
                  <!-- END Site Notice -->

                  <!-- EH content -->
                  <div class="form-group">
                    <label>이화이언 컨텐츠</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">컨텐츠 수신 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" {{ @$setting['eh_content'] == 1 ? 'checked' : '' }}
                      class="custom-control-input" id="eh-content" value="true" name="ehContent">
                      <label class="custom-control-label" for="eh-content"></label>
                    </div>
                  </div>
                  <!-- END EH content -->

                  <!-- Bulletin board notification settings -->
                  <div class="form-group">
                    <label>게시판 알림 설정</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox"
                             {{ @$setting['bulletin_comment_on_post'] == 1 ? 'checked' : '' }}
                             class="custom-control-input" id="bulletin-comment-on-post" value="true"
                             name="bulletinCommentOnPost">
                      <label class="custom-control-label" for="bulletin-comment-on-post"></label>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="option-title">대댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input"
                             {{ @$setting['bulletin_comment_on_comment'] == 1 ? 'checked' : '' }}
                             id="bulletin-comment-on-comment" value="true"
                             name="bulletinCommentOnComment">
                      <label class="custom-control-label" for="bulletin-comment-on-comment"></label>
                    </div>
                  </div>
                  <!-- END Bulletin board notification settings -->

                  <!-- Secret garden notification settings -->
                  <div class="form-group">
                    <label>비밀의 화원 알림 설정</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input"
                             {{ @$setting['secret_garden_comment_on_post'] == 1 ? 'checked' : '' }}
                             id="secret-garden-comment-on-post" value="true"
                             name="secretGardenCommentOnPost">
                      <label class="custom-control-label" for="secret-garden-comment-on-post"></label>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="option-title">대댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input"
                             {{ @$setting['secret_garden_comment_on_comment'] == 1 ? 'checked' : '' }}
                             id="secret-garden-comment-on-comment" value="true"
                             name="secretGardenCommentOnComment">
                      <label class="custom-control-label"
                             for="secret-garden-comment-on-comment"></label>
                    </div>
                  </div>
                  <!-- END Secret garden notification settings -->


                  <!-- E-flower garden notification settings -->
                  <div class="form-group">
                    <label>E-화원 알림 설정</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">E-화원 공지사항</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input" id="garden-notice"
                             {{ @$setting['garden_notice'] == 1 ? 'checked' : '' }} value="true"
                             name="gardenNotice">
                      <label class="custom-control-label" for="garden-notice"></label>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="option-title">즐겨찾기한 E-화원 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input" id="garden-new-post"
                             {{ @$setting['garden_new_post'] == 1 ? 'checked' : '' }} value="true"
                             name="gardenNewPost">
                      <label class="custom-control-label" for="garden-new-post"></label>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="option-title">댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input" id="garden-comment-on-post"
                             {{ @$setting['garden_comment_on_post'] == 1 ? 'checked' : '' }}
                             value="true" name="gardenCommentOnPost">
                      <label class="custom-control-label" for="garden-comment-on-post"></label>
                    </div>
                  </div>

                  <div class="form-group">
                    <span class="option-title">대댓글 알림 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input"
                             {{ @$setting['garden_comment_on_comment'] == 1 ? 'checked' : '' }}
                             id="garden-comment-on-comment" value="true" name="gardenCommentOnComment">
                      <label class="custom-control-label" for="garden-comment-on-comment"></label>
                    </div>
                  </div>
                  <!-- END E-flower garden notification settings -->

                  <!-- Message Receive Settings -->
                  <div class="form-group">
                    <label>메세지 수신 설정</label>
                  </div>

                  <div class="form-group">
                    <span class="option-title">메세지 수신 설정</span>

                    <div class="custom-control custom-switch float-right">
                      <input type="checkbox" class="custom-control-input" id="message-notification"
                             {{ @$setting['message_notification'] == 1 ? 'checked' : '' }} value="true"
                             name="messageNotification">
                      <label class="custom-control-label" for="message-notification"></label>
                    </div>
                  </div>
                  <!-- END Message Receive Settings -->

                  <button type="submit"
                          class="btn default-btn fw6">{{ trans('plugins/member::dashboard.save') }}</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\UpdatePasswordRequest::class) !!}

  <script>
    $(function () {
      $(document.body).on('change', '#password', function () {
        let current_password = $('#current_password').val();
        let password = $('#password').val();
        if (password != '' && current_password == password && password.length >= 8 && password
          .length <= 16) {
          $('#password-warning').show();
        } else {
          $('#password-warning').hide();
        }
      });

      $('#deleteAccountBtn').click(() => {
        $('#confirmDeletePopup').modal('show');
      });

      $('#confirmDeleteBtn').click(() => {
        let password = $('#password-temp').val();
        let passwordConfirmation = $('#password-confirmation-temp').val();
        $('#password-delete').val(password);
        $('#password-confirmation-delete').val(passwordConfirmation);
        $('#deleteAccountForm').submit();
      });
    });

  </script>
@endpush
