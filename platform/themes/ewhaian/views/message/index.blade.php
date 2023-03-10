<style>
.account-management .account .note-form .form-control {
    color: #444444;
}
</style>

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
                            <input id="id_login" type="text"
                                class="form-control{{ $errors->has('id_login') ? ' is-invalid' : '' }}" name="id_login"
                                value="{{ old('id_login') }}" placeholder="ID" autofocus>
                            @if ($errors->has('id_login'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('id_login') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="passwordLogin" type="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                placeholder="Password">
                            @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="form-check form-check--checkbox">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                    {{ old('remember') ? 'checked' : '' }} />
                                <label class="form-check-label" for="remember">
                                    <svg width="12" height="9" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check"></use>
                                    </svg>
                                    {{__('message.remember_me')}}
                                </label>
                            </div>
                            <div class="form-check form-check--checkbox">
                                <input type="checkbox" class="form-check-input" id="keep-login" name="keep_login"
                                    {{ old('keep_login') ? 'checked' : '' }} />
                                <label class="form-check-label" for="keep-login">
                                    <svg width="12" height="9" aria-hidden="true" class="icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_check"></use>
                                    </svg>
                                    {{__('message.keep_login')}}
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="form-submit">{{__('message.log_in')}}</button>
                    </div>
                    <div class="d-flex login-form__footer justify-content-between">
                        <a href="{{ route('public.member.register') }}" title="{{__('message.sign_up')}}" class="login-form__link">
                            <svg width="20" height="16" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_create_user"></use>
                            </svg>
                            {{__('message.sign_up')}}
                        </a>
                        <a href="{{ route('public.member.password.request') }}" title="{{__('message.forgot_id_password')}}"
                            class="login-form__link">
                            <svg width="16" height="17" aria-hidden="true">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_forgot_password">
                                </use>
                            </svg>
                            {{__('message.forgot_id_password')}}
                        </a>
                    </div>
                </form>
                <!-- end of login -->
                @else

                {!! Theme::partial('account_management') !!}
                @endif

            </div>
            <div class="sidebar-template__content">
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

                <h3 class="title-main">{{__('message.message_list')}}</h3>

                <div class="event-list">
                    <div class="content">
                        <div style="margin-top: 20px;text-align: right;">
                            <a href="javascript:void(0)" title="{{__('message.remove_message')}}"
                                class="filter__item btn btn-primary mx-3 btn-reset-padding" id="deleteManyMessage" data-target="#confirmDeleteMany">
                                <span>{{__('message.delete_many')}}</span>
                            </a>
                        </div>
                        @if (session('success'))
                        <div class="alert alert-success" style="display: block;margin-top: 10px;">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger" style="display: block;margin-top: 10px;">
                            {{ session('error') }}
                        </div>
                        @endif
                        <div class=" table-responsive" style="margin-top: 2.14286em;">
                            <table class="table table--content-middle" style="width: 880px;overflow-x: auto;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>{{__('message.id')}}</th>
                                        <th>{{__('message.from')}}</th>
                                        <th>{{__('message.contents')}}</th>
                                        <th>{{__('message.times')}}</th>
                                        <th>{{__('message.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($message->count() > 0)
                                    @foreach ($message as $key => $item)


                                    <tr>
                                        <td style="text-align: center;width: 10px">
                                            <input type="checkbox"  class="delete_list_id" value="{{$item->id}}">
                                        </td>
                                        <td style="text-align: center;width: 20px">
                                            {{$message->count() -$key}}
                                        </td>
                                        <td style="text-align: center;width: 30px">
                                            <div>
                                                {{$item->member_from->id_login}}
                                            </div>
                                            <div>
                                                ({{$item->member_from->roles->name}})
                                            </div>

                                        </td>
                                        <td style="text-align: left;width: 40%" class="menu__item">
                                            <a href="javascript:void(0)"  class="showMessage" id_login="{{$item->member_from->id_login}}" data-value="{{$item->id}}" title="{!! $item->contents !!}" @if($item->status == 'unread') style="color:#EC1469 " @endif >
                                                {!! highlightWords2($item->contents,'') !!}
                                            </a>
                                        </td>
                                        <td style="text-align: center;width: 20px">
                                            {{getTimeToday($item->created_at)}}
                                        </td>
                                        <td style="text-align: center;width: 10px">
                                            <div class="item__image mb-10">
                                                <a href="javascript:void(0)" class="deleteMessage"
                                                    title="{{__('message.remove_message')}}" data-toggle="modal" data-value="{{$item->id}}"
                                                    data-target="#confirmDelete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr style="text-align: center">
                                        <td colspan="6">{{__('message.no_message')}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {!! Theme::partial('paging',['paging'=>$message->appends(request()->input()) ]) !!}
    </div>
</main>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmDelete" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
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
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <input type=" text" id="hint" value="{{__('message.confirm_delete')}}" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('public.member.message.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="0" id="message_id" name="message_id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('message.cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{__('message.delete')}}</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmDeleteMany" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
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
            <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
              <label for="hint" class="form-control">
                <input type=" text" id="hint" value="{{__('message.confirm_delete')}}" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('public.member.message.delete.many')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="0" id="message_many_id" name="message_many_id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('message.cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{__('message.delete')}}</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){
    $('.showMessage').on('click',function(){


        var $this = $(this);
            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);

            $.ajax({
                type:'POST',
                url:'{{route('public.member.message.show')}}',
                data:{
                        _token: "{{ csrf_token() }}",
                        id:  $this.attr('data-value'),
                },
                success: function( data ) {
                    if(data['status'] == false){
                        alert(data['message'] );
                    }
                    if(data['status'] == true){

                            $this.css('color','unset');
                            $('#note-form__submit').css('display','none');
                            $('#note-form__show').css('display','block');


                            $('#id_login_to').val();
                            $('#id_login_to').val($this.attr('id_login'));
                            $('#id_login_to').attr('readonly',true);

                            $('#message_for_member').val();
                            $('#message_for_member').val($this.attr('title'));
                            $('#message_for_member').attr('readonly',true);

                            $('.togglefade .menu__item .togglefade__control').addClass('opened');
                            $('.togglefade .togglefade__content ').css('display','block');

                    }
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

    $('#note-form__show').on('click',function(){

        $('#message_for_member').attr('readonly',false);
        $('#message_for_member').val('');
        $('#message_for_member').focus();

        $('#note-form__submit').css('display','block');

        $(this).css('display','none');
    });

    $('#checkAll').on('click',function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    })

    $('#deleteManyMessage').on('click',function(){
        var checkValue = [];
        $('.delete_list_id:checked').each(function(i){
            checkValue[i] = $(this).val();
        });
        if(checkValue.length  === 0){
            alert('최소 하나의 메시지를 선택해주세요')
            return;
        }
        $('#message_many_id').val(checkValue);
        $('#confirmDeleteMany').modal('show');
    })



})
$(document.body).on("click",'.deleteMessage', function(e){
                let $this = $(this);
                $('#message_id').val($this.attr('data-value'))
});
</script>
