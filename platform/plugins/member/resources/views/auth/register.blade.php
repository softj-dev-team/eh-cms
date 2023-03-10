@extends('plugins.member::layouts.skeleton')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ trans('plugins/member::dashboard.register-title') }}</div>
        <div class="card-body">
          <form method="POST" action="{{ route('public.member.register.post') }}">
            @csrf
            <div class="form-group row">
              <label for="id_login" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.id_login') }}</label>
              <div class="col-md-6">
                <input id="id_login" type="text" class="form-control{{ $errors->has('id_login') ? ' is-invalid' : '' }}" name="id_login" value="{{ old('id_login') }}" required autofocus>
                @if ($errors->has('id_login'))
                <span class="invalid-feedback">
                  <strong>{{ $errors->first('id_login') }}</strong>
                </span>
                @endif
              </div>
            </div>
            {{-- <div class="form-group row">
                <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.last_name') }}</label>
            <div class="col-md-6">
              <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required>
              @if ($errors->has('last_name'))
              <span class="invalid-feedback">
                <strong>{{ $errors->first('last_name') }}</strong>
              </span>
              @endif
            </div>
        </div> --}}
        <div class="form-group row">
          <label for="password-1" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.password') }}</label>
          <div class="col-md-6">
            <input id="password-1" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
            <span class="invalid-feedback">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
        </div>
        <div class="form-group row">
          <label for="password-confirm-2" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.password-confirmation') }}</label>
          <div class="col-md-6">
            <input id="password-confirm-2" type="password" class="form-control" name="password_confirmation" required>
          </div>
        </div>
        {{-- <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.email') }}</label>
        <div class="col-md-6">
          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
          @if ($errors->has('email'))
          <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
          @endif
        </div>
      </div> --}}
      <div class="form-group row">
        <label for="nickname" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.nickname') }}</label>
        <div class="col-md-6">
          <input id="nickname" type="text" class="form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" name="nickname" value="{{ old('nickname') }}" required>
          @if ($errors->has('nickname'))
          <span class="invalid-feedback">
            <strong>{{ $errors->first('nickname') }}</strong>
          </span>
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="fullname" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.fullname') }}</label>
        <div class="col-md-6">
          <input id="fullname" type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required>
          @if ($errors->has('fullname'))
          <span class="invalid-feedback">
            <strong>{{ $errors->first('fullname') }}</strong>
          </span>
          @endif
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.email') }}</label>
        <div class="col-md-6">

          <input id="namemail" type="text" class="form-control{{ $errors->has('namemail') ? ' is-invalid' : '' }}" name="namemail" value="{{ old('namemail') }}" required>
          @
          <input id="domainmail" type="text" class="form-control{{ $errors->has('domainmail') ? ' is-invalid' : '' }}" name="domainmail"  value="ewhain.net">
          <input type="hidden" name="email" value="" id="email">

          @if ($errors->has('email'))
          <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
          @endif

{{--          <div id='btnSendEmail' style="padding-top: 5px; display:none;" >--}}
{{--          <button id="send_email" type="button" class="btn default-btn fw6" style="padding : 6px 8px">--}}
{{--            {{ trans('plugins/member::dashboard.send_email') }}--}}
{{--          </button>--}}
{{--          </div>--}}
        </div>
      </div>

{{--      <div class="form-group row" id="verify_email_code" style="display:none">--}}
{{--        <label for="verify_code" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.verify_email_code') }}</label>--}}
{{--        <div class="col-md-6">--}}
{{--          <input id="verify_code" type="text" class="form-control{{ $errors->has('verify_code') ? ' is-invalid' : '' }}" name="verify_code" value="{{ old('verify_code') }}" required>--}}
{{--          @if ($errors->has('verify_code'))--}}
{{--            <span class="invalid-feedback">--}}
{{--            <strong>{{ $errors->first('verify_email_code') }}</strong>--}}
{{--            </span>--}}
{{--          @endif--}}
{{--          <div id='btnConfirmEmail' style="padding-top: 5px;" >--}}
{{--            <button id="confirm_email" type="button" class="btn default-btn fw6" style="padding : 6px 8px">--}}
{{--              {{ trans('plugins/member::dashboard.verify_email_code') }}--}}
{{--            </button>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}

      <div class="form-group row">
        <label for="phone" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.phone_number') }}</label>
        <div class="col-md-4">

          <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
          @if ($errors->has('phone'))
          <span class="invalid-feedback">
            <strong>{{ $errors->first('phone') }}</strong>
          </span>
          @endif
        </div>
{{--        <div class="col-md-2">--}}
{{--          <input type="hidden" name="recaptcha_response" id="recaptcha_response">--}}
{{--          <input type="hidden" name="sessionInfo" id="sessionInfo">--}}
{{--          <button id="send_sms" type="button" class="btn default-btn fw6" style="padding : 6px 8px">--}}
{{--            {{ trans('plugins/member::dashboard.send_sms') }}--}}
{{--          </button>--}}
{{--        </div>--}}

      </div>

      <div class="form-group row">
        <div id="UserRule" class="col-md-12">
          <div class="tit">
            <b>이용약관</b>
          </div>

          <div>
            <textarea id="Content1" readonly="" style="height: 210px;width: 100%;font-size: 12px;">			이화이언 커뮤니티 이용자 약관

제 1장   총 칙

제1조(약관의 목적과 동의)

1) 이화이언 커뮤니티이용자 약관(이하 "본 약관"이라 합니다)은 이용자가 이화이언 커뮤니티(이하 "이화이언 커뮤니티")에서 제공하는 인터넷 관련 서비스(이하 "서비스")를 이용함에 있어 이용자와 이화이언 커뮤니티의 권리•의무 및 책임사항을 규정함을 목적으로 합니다.
2) 이용자가 되고자 하는 자가 이화이언 커뮤니티가 정한 소정의 절차를 거쳐서 "등록하기" 단추를 클릭하면 본 약관에 동의하는 것으로 간주합니다. 본 약관에 정하는 이외의 이용자와 이화이언 커뮤니티의 권리, 의무 및 책임사항에 관해서는 전기통신사업법 기타 대한민국의 관련 법령과 상관습에 의합니다.

제2조(약관의 개정)

1) 이화이언 커뮤니티는 약관의 규제 등에 관한 법률, 전자거래기본법, 전자서명법, 정보통신망 이용촉진 등에 관한 법률 등 관련법을 위배하지 않는 범위에서 본 약관을 개정할 수 있습니다.
2) 이화이언 커뮤니티가 본 약관을 개정할 경우에는 적용일자 및 개정사유를 명시하여 현행약관과 함께 초기화면에 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다.
3) 이화이언 커뮤니티가 본 약관을 개정할 경우에는 그 개정약관은 개정된 내용이 관계 법령에 위배되지 않는 한 개정 이전에 회원으로 가입한 이용자에게도 적용됩니다.
4) 변경된 약관에 이의가 있는 이용자는 제6조 제1항에 따라 탈퇴할 수 있습니다.

제3조(이용자의 정의)
"이용자"란 이화이언 커뮤니티에 접속하여 본 약관에 따라 이화이언 커뮤니티 회원으로 가입한 다음 이화이언 커뮤니티가 제공하는 서비스를 받는 자를 말합니다.

제4조 (회원 가입)

1) 이용자가 되고자 하는 자는 이화이언 커뮤니티가 정한 가입 양식에 따라 회원정보를 입력하고 "등록하기" 단추를 클릭하는 방법으로 회원 가입을 신청합니다.
2) 이화이언 커뮤니티는 제1항과 같이 회원으로 가입할 것을 신청한 자가 다음 각 호에 해당하지 않는 한 신청한 자를 회원으로 등록합니다.

1. 가입신청자가 본 약관 제6조 제3항에 의하여 이전에 회원자격을 상실한 적이 있는 경우. 다만 제6조 제3항에 의한 회원자격 상실 후 3년이 경과한 자로서 이화이언 커뮤니티로부터 회원 재가입 승낙을 얻은 경우에는 예외로 합니다.
2. 등록 내용에 허위, 기재누락, 오기가 있는 경우
3. 기타 회원으로 등록하는 것이 이화이언 커뮤니티의 기술상 현저히 지장이 있다고 판단되는 경우
3) 회원가입계약의 성립시기는 이화이언 커뮤니티의 승낙이 가입신청자에게 도달한 시점으로 합니다.
4) 회원은 제1항의 회원정보 기재 내용에 변경이 발생한 경우, 즉시 변경사항을 정정하여야 합니다. (기재한 주소가 명확하지 않으면 이벤트 상품 전달 시 불이익을 받으실 수 있습니다)


제 2장 서비스의 이용계약

제5조(서비스의 제공 및 변경)

1) 이화이언 커뮤니티는 이용자에게 아래와 같은 서비스를 제공합니다.
1. 웹 메일 서비스
2. 인터넷 커뮤니티 이용서비스
3. 회원을 위한 섹션 및 컨텐츠 서비스, 맞춤 서비스
4. 기타 이화이언 커뮤니티가 자체 개발하거나 다른 회사와의 협력계약 등을 통해 회원들에게 제공할 일체의 서비스
2) 이화이언 커뮤니티는 그 변경될 서비스의 내용 및 제공일자를 제7조 제2항에서 정한 방법으로 이용자에게 통지하고, 제1항에 정한 서비스를 변경하여 제공할 수 있습니다.
3) 이화이언 커뮤니티는 타대생인 자(이화여자대학교에 재학 중이지 아니하거나 졸업생 또는 휴학생이 아니한 자), 미성년자인 회원에 대하여 비밀화원의 서비스의 제공을 제한할 수 있습니다.
4) 이화이언 커뮤니티는 서비스의 변경, 중지로 발생하는 문제에 관하여 어떠한 책임도지지 않습니다.

제6조(서비스의 중단)

1) 이화이언 커뮤니티는 컴퓨터 등 정보통신설비의 보수점검•교체 및 고장, 통신 두절 등의 사유가 발생한 경우에는 서비스의 제공을 일시적으로 중단할 수 있고, 새로운 서비스로의 교체 기타 이화이언 커뮤니티가 적절하다고 판단하는 사유에 의하여 현재 제공되는 서비스를 완전히 중단할 수 있습니다.
2) 제1항에 의한 서비스 중단의 경우에는 이화이언 커뮤니티는 제7조 제2항에서 정한 방법으로 이용자에게 통지합니다. 다만, 이화이언 커뮤니티가 통제할 수 없는 사유(시스템 관리자의 고의, 천재지변, 디스크 장애, 시스템 다운 등)로 인한 서비스의 중단으로 인하여 사전 통지가 불가능한 경우에는 그러하지 아니합니다.

제6조(이용자 탈퇴 및 자격 상실 등)

1) 이용자는 이화이언 커뮤니티에 언제든지 자신의 회원 등록을 말소해 줄 것(이용자 탈퇴)을 요청할 수 있으며 이화이언 커뮤니티는 위 요청을 받은 즉시 해당 이용자의 회원 등록 말소를 위한 절차를 밟습니다.
2) 이용자가 다음 각 호의 사유에 해당하는 경우, 이화이언 커뮤니티는 이용자의 회원자격을 적절한 방법으로 제한 및 정지, 상실 시킬 수 있습니다.
1. 가입 신청 시에 허위 내용을 등록한 경우
2. 다른 사람의 이화이언 커뮤니티 이용을 방해하거나 그 정보를 도용하는 등 전자거래질서를 위협하는 경우
3. 이화이언 커뮤니티를 이용하여 법령과 본 약관이 금지하거나 사회 일반의 도덕관념에 반하는 행위를 하는 경우
3) 이화이언 커뮤니티가 이용자의 회원자격을 상실시키기로 결정한 경우에는 회원등록을 말소합니다.

제7조(이용자에 대한 통지)

1) 이화이언 커뮤니티가 특정 이용자에 대한 통지를 하는 경우 이화이언 커뮤니티가 부여한 메일주소로 할 수 있습니다.
2) 이화이언 커뮤니티가 불특정다수 이용자에 대한 통지를 하는 경우 1주일이상 이화이언 커뮤니티 게시판에 게시함으로써 개별 통지에 갈음할 수 있습니다.

제8조(이용자의 개인정보보호)

이화이언 커뮤니티는 관련법령이 정하는 바에 따라서 이용자 등록정보를 포함한 이용자의 개인정보를 보호하기 위하여 노력합니다. 이용자의 개인정보보호에 관해서는 관련법령 및 이화이언 커뮤니티가 정하는 "개인정보보호정책"에 정한 바에 의합니다.


제 3장 서비스의 이용

제9조(회원의 게시물)
1) 회원의 게시물이라 함은 이화이언 커뮤니티 서비스 내에 회원이 올린 그림, 글, 동영상, 사진, 각종 파일 및 링크, 리플 등을 칭함.
2) 회원의 게시물 저작권은 이화이언 커뮤니티에 있으며, 이화이언 커뮤니티는 게시물을 올린 회원의 동의를 얻지 않고도 이를 자유로이 이용할 수 있습니다.
3) 회원은 자유로이 게시물을 올리는 등 서비스를 이용할 수 있으나, 그 게시물이 서비스 이용약관과 맞지 않는 경우 그 이용을 제한하거나, 이화이언 커뮤니티는 회원의 동의 없이 삭제할 권리를 가진다. 이 경우 게시물의 삭제에 관해서는 제3장 제12조 공개게시물의 삭제에 의거합니다.


제10조(이화이언 커뮤니티의 의무)

1) 이화이언 커뮤니티는 법령과 본 약관이 금지하거나 사회 일반의 도덕관념에 반하는 행위를 하지 않으며 본 약관이 정하는 바에 따라 지속적이고, 안정적으로 서비스를 제공하기 위해서 노력합니다.
2) 이화이언 커뮤니티는 이용자가 안전하게 인터넷 서비스를 이용할 수 있도록 이용자의 개인정보(신용정보 포함)보호를 위한 보안 시스템을 구축합니다.
3) 이화이언 커뮤니티는 이용자가 원하지 않는 영리목적의 광고성 전자우편을 발송하지 않습니다.
4) 이화이언 커뮤니티는 이용자가 서비스를 이용함에 있어 이화이언 커뮤니티의 고의 또는 중대한 과실로 인하여 입은 손해를 배상할 책임을 부담합니다.

제11조(이용자의 ID 및 비밀번호에 대한 의무)

1) 이화이언 커뮤니티가 관계법령, "개인정보보호정책"에 의해서 그 책임을 지는 경우를 제외하고, 자신의 ID와 비밀번호에 관한 관리책임은 각 이용자에게 있습니다.
2) 이용자는 자신의 ID 및 비밀번호를 제3자에게 이용하게 해서는 안됩니다.
3) 이용자는 자신의 ID 및 비밀번호를 도난 당하거나 제3자가 사용하고 있음을 인지한 경우에는 바로 이화이언 커뮤니티에 통보하고 이화이언 커뮤니티의 안내가 있는 경우에는 그에 따라야 합니다.

제12조(이용자의 의무)

1) 이용자는 다음 각 호의 행위를 하여서는 안됩니다.
1. 회원가입신청 또는 변경 시 허위내용을 등록하는 행위
2. 이화이언 커뮤니티에 게시된 정보를 변경하는 행위
3. 이화이언 커뮤니티 기타 제3자의 인격권 또는 지적재산권을 침해하거나 업무를 방해하는 행위
4. 다른 회원의 ID를 도용하는 행위
5. 정크메일(junk mail), 스팸메일(spam mail), 행운의 편지(chain letters), 피라미드 조직에 가입할 것을 권유하는 메일, 외설 또는 폭력적인 메시지 •화상•음성 등이 담긴 메일을 보내거나 기타 사회 일반의 도덕관념에 반하는 정보를 공개 또는 게시하는 행위.
6. 관련 법령에 의하여 그 전송 또는 게시가 금지되는 정보(상업용 컴퓨터 프로그램 등)의 전송 또는 게시하는 행위
7. 이화이언 커뮤니티의 직원이나 이화이언 커뮤니티 서비스의 관리자를 가장하거나 사칭하여 또는 타인의 명의를 도용하여 글을 게시하거나 메일을 발송하는 행위
8. 컴퓨터 소프트웨어, 하드웨어, 전기통신 장비의 정상적인 가동을 방해, 파괴할 목적으로 고안된 소프트웨어 바이러스, 기타 다른 컴퓨터 코드, 파일, 프로그램을 포함하고 있는 자료를 게시하거나 전자우편으로 발송하는 행위
9. 스토킹(stalking) 등 다른 이용자를 괴롭히는 행위
10. 다른 이용자에 대한 개인정보를 그 동의 없이 수집, 저장, 공개하는 행위
11. 이화이언 커뮤니티가 제공하는 서비스에 정한 약관 기타 서비스 이용에 관한 규정을 위반하는 행위
12. 이화이언 커뮤니티가 공식적으로 인정한 경우(물물교환, 중고품 판매 등 전문적인 상업의 목적 없이 이화이언 커뮤니티 내 벼룩시장 서비스를 이용하는 경우)를 제외하고 서비스를 이용(서비스 내에 광고물을 게시하거나 제3자에게 광고물을 발송하는 방식을 포함)하여 상품을 판매하는 영업활동 등의 상행위를 할 수 없으며, 특히 해킹, 광고를 통한 수익, 음란사이트를 통한 상업행위, 상용소프트웨어 불법배포 등을 할 수 없습니다. 이를 위반하여 발생한 상행위의 결과 및 손실, 관계기관에 의한 구속 등 법적 조치에 관해서는 이화이언 커뮤니티가 책임을 지지 않으며, 회원은 이와 같은 행위와 관련하여 이화이언 커뮤니티에 대하여 손해배상 의무를 집니다.
13. 회원은 커뮤니티의 사이트 운영을 저해하는 행위를 할 수 없으며, 이러한 행위시 당 사이트 운영자는 회원의 이용계약 해지와 이용을 제한할 권리를 가집니다.
14. 다른 사용자의 개인정보를 수집 또는 저장하는 행위.
15. 사람을 비방할 목적으로 사실 또는 허위의 사실을 게시하여 사람의 명예를 훼손하는 행위.
16. 내용이 음란하거나 그 내용이 잔인하여 상대방에게 수치심 및 공포심을 일으킬 여지가 있는 게시물(그림, 글, 동영상, 링크 등 자료)을 올리는 행위.
2) 제1항에 해당하는 행위를 한 이용자가 있을 경우 이화이언 커뮤니티는 본 약관 제6조 제2, 3항에서 정한 바에 따라 이용자의 회원자격을 적절한 방법으로 제한 및 정지, 상실 시킬 수 있습니다.
3) 이용자는 그 귀책사유로 인하여 이화이언 커뮤니티나 다른 이용자가 입은 손해를 배상할 책임이 있습니다.

제 13조 (공개게시물의 삭제)

이용자의 공개게시물의 내용이 다음 각 호에 해당하는 경우 이화이언 커뮤니티는 이용자에게 사전 통지 없이 해당 공개게시물을 삭제할 수 있고, 해당 이용자의 회원 자격을 제한, 정지 또는 상실시킬 수 있습니다.
1. 다른 이용자 또는 제3자를 비방하거나 중상모략으로 명예를 손상시키는 내용
2. 사회 일반의 도덕관념에 위반되는 내용의 정보, 문장, 도형 등을 유포하는 내용
3. 범죄행위와 관련이 있다고 판단되는 내용
4. 다른 이용자 또는 제3자의 저작권 등 기타 권리를 침해하는 내용
5. 이화이언 커뮤니티의 이익을 저해하는 행위.
6. 음란, 폭력물 등 불건전한 자료의 게재, 유포하는 행위.
7. 타인의 ID, 성명 등을 무단으로 도용하여 작성한 내용이거나, 타인이 입력한 정보를 무단으로 위, 변조한 내용인 경우.
7. 기타 관계 법령 및 본 서비스의 약관에 위배된다고 판단되는 내용


제14조(저작권의 귀속 및 이용제한)

1) 이화이언 커뮤니티가 작성한 저작물에 대한 저작권 기타 지적재산권은 이화이언 커뮤니티에 귀속합니다.
2) 이용자는 이화이언 커뮤니티를 이용함으로써 얻은 정보를 이화이언 커뮤니티의 사전승낙 없이 복제, 전송, 출판, 배포, 방송 기타 방법에 의하여 영리목적으로 이용하거나 제3자에게 이용하게 하여서는 안됩니다.


제4장 이화이언 커뮤니티 서비스의 상세내용

제15조(커뮤니티서비스)

1) 전체 이화이언을 지칭하는 이화이언 커뮤니티 이외에 회원이 자유롭게 만들 수 있는 클럽도 커뮤니티라 지칭한다. (용어의 이해를 위하여 아래의 조항에 관해서 이화이언 커뮤니티를 이화이언이라 지칭합니다.)
2) 이화이언 커뮤니티의 사용 용량 지정 권한은 이화이언 웹 마스터에게 있으며, 이화이언의 서버문제 혹은 그 외 기타 사유로 전체 메일이나 공지 이후 용량이 감소될 수 있습니다.
3) 이화이언은 클럽 서비스를 통하여 생성된 클럽의 게시물 등에 대한 책임이 없으며, 모든 게시물에 대해서는 이용자에게 책임이 있다. 또한 그에 대한 신뢰성을 이화이언 커뮤니티가 보장하지 않으며, 그에 관해 문제가 발생할 경우 이화이언 커뮤니티는 책임을 지지 아니합니다.
4) 이화이언은 회원들의 원활한 사이트 이용을 위하여 각 커뮤니티의 자율성을 보장합니다. 단, 커뮤니티의 성격이 서비스 이용약관에 적합하지 않는 경우는 예외입니다.
5) 이화이언 웹마스터는 해당 커뮤니티의 규정에 관계없이 이화이언 운영취지에 반하는 게시물과 자료 등에 대해서 게재를 제한하거나 등록을 거부 또는 삭제 할 수 있습니다. 또 경고 이후 커뮤니티를 폐쇄할 수 있습니다.
6) 커뮤니티 운영자는 어떠한 경우에도 회원의 허락 없이 개인 신상정보를 함부로 공개할 수 없으며, 이를 상업적인 용도에 이용할 수 없으며 이를 위반 시 이화이언 웹마스터는 커뮤니티를 폐쇄할 수 있습니다. 또 이로 인해 발생하는 피해에 대해서 커뮤니티 운영자는 모든 책임을 지어야 합니다.
7) 이화이언 웹마스터가 모니터링 중 커뮤니티의 상업용자료, 불법자료, 음란자료 등 사회 통념상 도덕에 벗어나는 자료를 발견하면 커뮤니티 운영자는 즉시 그 자료를 삭제해야 합니다. 동 자료의 방치가 3회 이상 적발되는 경우 웹마스터는 해당 커뮤니티를 임의로 폐쇄할 수 있습니다.
8) 다음 항에 해당하는 경우 웹마스터는 해당 커뮤니티를 폐쇄할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 개설되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 해당 커뮤니티 운영자의 관리 소홀로 일정기간 게시판의 업로드가 되지 않아 커뮤니티 활성화가 제대로 되지 않은 경우. (이 때 일정기간이란 한 달을 의미합니다.)
③ 커뮤니티의 운영자 이외에 회원이 5명 이하인 경우.
④ 개인 자료실의 목적으로 만든 경우. 이 경우 웹 마스터는 해당 커뮤니티 운영자에게 메일을 보내 커뮤니티 폐쇄를 공지하고 커뮤니티 운영자는 웹마스터가 지정한 날짜 이내에
커뮤니티 자료를 이동하여야 하며, 지정한 날짜 이후에 웹 마스터는 그 커뮤니티를 폐쇄할 수 있고 삭제된 커뮤니티 안의 자료들에 대해서는 책임을 지지 아니 합니다.
⑤ 헌법 및 실정법에 위반되거나 저촉되는 내용을 목적으로 한 경우.
⑥ 웹 마스터의 승인을 받지 않고 무단으로 상업적인 커뮤니티 활동을 하는 경우.
⑦ 2회에 걸친 경고에도 불구하고 동일한 문제에 대해 시정이 되지 않는 경우.

제16조(열린광장 서비스)

1) 이화이언 약관에 동의하고 이화이언에 가입한 회원들은 모두 열린광장을 이용할 수 있습니다.
2) 다음의 경우 웹 마스터는 이화광장의 글을 게시자 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그 외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우

제17조(비밀의화원 서비스)

1) 이화이언 회원 중 이화여자대학교에 재학 중이거나 졸업생, 휴학생만 이용할 수 있는 익명게시판으로서 타대생의 출입을 금합니다.
2) 비밀화원을 이용하기 위해서는 이화이언 웹마스터가 한 달에 한번씩 변경하는 비밀단어를 입력해야 하고, 이 비밀단어는 이화여자대학교 자유게시판에 게시됩니다.
3) 다음의 경우 이화이언 웹마스터는 비밀의화원의 글을 게시자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우
(1) 저속 또는 음란한 데이터, 텍스트, 소프트웨어, 음악, 사진, 그래픽, 비디오 메시지, 동영상 등을 게시하는 경우.
(2) 게시물에 음란 사이트의 주소를 링크시키는 경우.
(3) 음란 사이트의 주소를 요청하는 경우.
(4) 게시물을 보는 사람으로 하여금 수치심이나 혐오감을 일으켜 상대방의 일상적인 생활을 방해하는 심각한 음란성 글.
② 타인으로 가장하는 행위 및 타인과의 관계를 허위로 명시하는 경우.
③ 사람을 비방할 목적으로 사실 또는 허위의 사실을 게시하여 사람의 명예를 훼손하는 행위.
(1) 공개 당하는 사람의 동의 없이 사진을 올리는 경우.
(2) 공개 당하는 사람의 동의 없이 SNS 주소를 링크시키는 경우.
④ 자신 또는 타인에게 재산상의 이익을 주거나 타인에게 손해를 가할 목적으로 허위의 정보를 유통시키는 행위.
⑤ 개인 혹은 단체의 영리추구를 위해 상업적인 글을 게시하는 경우.
⑥ 게시물이 비밀의화원 내에 논란을 일으킬 가능성이 있는 경우.
(1) 학벌, 직업, 외모, 경제적 능력 등에 관하여 개인의 사사로운 감정으로 타인 혹은 다른 학교, 직업 등을 폄하하는 경우
⑦ 타인에게 공포감을 유도할 수 있는 게시물의 경우.
⑧ 읽는 사람으로 하여금 불쾌감을 유도할 수 있는 리플의 경우.
⑨ 기타 미풍 양속이나 건전한 사회질서 및 이화이언 전체 이용약관에 어긋나는 게시물의 경우.

제18조(비밀의화원 훌리건 서비스)

1) 사람에게 혐오감, 공포감, 수치심 및 불쾌감을 유발하는 게시물 혹은 리플을 게시한 자에게 훌리건 제도를 적용 할 수 있으며 이는 비밀의화원을 이용하는 모든 회원들에게 이용권한이 있습니다.
2) 다음의 게시물에 훌리건 제도를 적용할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우
(1) 저속 또는 음란한 데이터, 텍스트, 소프트웨어, 음악, 사진, 그래픽, 비디오 메시지, 동영상 등을 게시하는 경우.
(2) 게시물에 음란 사이트의 주소를 링크시키는 경우.
(3) 음란 사이트의 주소를 요청하는 경우.
(4) 게시물을 보는 사람으로 하여금 수치심이나 혐오감을 일으켜 상대방의 일상적인 생활을 방해하는 심각한 패스성 글.
② 타인으로 가장하는 행위 및 타인과의 관계를 허위로 명시하는 경우.
③ 사람을 비방할 목적으로 사실 또는 허위의 사실을 게시하여 사람의 명예를 훼손하는 행위.
(1) 피공개인의 동의 없이 사진을 올리는 경우.
(2) 피공개인의 동의 없이 홈페이지 주소를 링크 및 게재하는 경우.
④ 자신 또는 타인에게 재산상의 이익을 주거나 타인에게 손해를 가할 목적으로 허위의 정보를 유통시키는 행위.
⑤ 개인 혹은 단체의 영리추구를 위해 상업적인 글을 게시하는 경우.
⑥ 게시물이 비밀화원 내에 논란을 일으킬 가능성이 있는 경우.
(1) 학벌, 직업, 외모, 경제적 능력 등에 관하여 개인의 사사로운 감정으로 타인 혹은 다른 학교, 직업 등을 폄하하는 경우
⑦ 타인에게 공포감을 유도할 수 있는 게시물의 경우.
⑧ 읽는 사람으로 하여금 불쾌감을 유도할 수 있는 리플의 경우.
⑨ 기타 미풍 양속이나 건전한 사회질서 및 이화이언 전체 이용약관에 어긋나는 게시물의 경우.
3) 게시물 혹은 리플로 훌리건 신고를 받은 회원에게는 다음의 약관을 적용한다.
① 동일한 게시물 혹은 리플로 다른 회원으로부터 훌리건 신고를 받은 회원에게는 웹마스터가 경고할 수 있고, 이것이 3번 누적되면 웹마스터는 이 회원을 탈퇴시킬 수 있으며 여하에 따라 영구금지조치를 취할 수 도 있습니다. (단 웹마스터는 신고를 받은 게시물이 부당하게 신고를 받은 경우 경고조치를 취하지 않을 수 있습니다.)
② 훌리건 신고를 받은 게시물의 작성자가 이화여자대학교에 재학 중이거나 졸업생, 휴학생이 아닌 경우, 게시물이 부당하게 신고를 받은 경우라도 비밀화원 이용약관에 어긋나므로 웹마스터는 이 회원을 탈퇴조치를 취할 수 있다.
③ 다른 게시물로 훌리건 신고를 3번 받은 경우 웹 마스터는 회원을 탈퇴조치 혹은 이화이언 접근금지조치를 취할 수 있습니다.
4) 사사로운 개인감정으로 기인한 훌리건 신고나 훌리건 제도에 대한 정확한 숙지 없이 단순한 재미로 여겨 훌리건 신고를 남발한 회원에게도 웹 마스터는 탈퇴 혹은 경고, 접근 금지 조치가 취할 수 있습니다.
① 훌리건으로 오인 받을 만한 정당한 이유 없는 게시물에 대해 훌리건 신고를 한 회원에게는 1차 경고 조치가 취해집니다.
② 이러한 경고가 3번 누적되면 이 회원에 대해서도 웹 마스터는 탈퇴 혹은 접근 금지 조치를 취할 수 있습니다.

제19조(비밀의화원 이용자의 의무)

1) 비밀화원 내 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 상업적인 목적, 이화이언, 이화여자대학교, 제3자에게 재산상의 손해를 주거나 명예를 훼손할 목적으로 게시물을 도용, 유통 시키는 행위를 한 회원에게는 1차 경고 조치 없이 바로 탈퇴조치를 취할 수 있습니다.
① 이화이언의 웹 마스터, 혹은 작성자의 승인 없이 무단으로 게시물을 복사하여 정보를 유통시킨 경우 해당하는 회원은 이화이언 및 당사자에게 발생하는 모든 손해를 배상하여야 합니다.
② 이화이언을 음해할 목적으로 개설된 다음 카페 등으로 비밀화원의 게시물을 유통시키는 것은 금지되어 있으며 이를 어기는 회원에게는 즉시 탈퇴조치가 취해집니다.
③ 기사작성 등을 이유로 신문사 혹은 잡지사에서 비밀화원 내 게시물을 복사 또는 캡쳐 하여 도용할 경우 웹 마스터의 승인을 받아야 하며 이로 인해 발생하는 모든 손해는 이화이언이 책임지지 않습니다.
④ 비밀의 화원 내부 운영규칙은 사이트 이용 방침에 따라 공개하지 않습니다.
⑤ 비밀의 화원에서 신고 횟수가 누적된 회원들은 내부 운영규칙에 따라 일정 기간의 사이트 이용조치가 취해집니다.
⑥ 비밀의 화원을 포함한 모든 사이트에서 비밀 단어와 관련된 정보를 간, 직접적으로 물어보거나 유포한 회원에게는 1차 경고 조치 없이 바로 탈퇴조치를 취할 수 있습니다.

제20조(강의평가 서비스)
1) 이화이언 내에서 모든 회원들이 수강신청, 혹은 강의 평가를 위한 목적으로 이용할 수 있는 게시판을 강의평가라 지칭합니다.
2) 강의평가 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 다음의 경우 웹 마스터는 게시물을 작성자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업적인 글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우

제21조(알바하자 서비스)
1)이화이언의 모든 회원들이 직업 혹은 아르바이트를 구하기 위해 이용하는 게시판을 알바하자 라고 지칭합니다.
2)알바하자 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 다음의 경우 웹 마스터는 게시물을 작성자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업적인 글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우

제22조(벼룩시장 서비스)
1) 이화이언의 이화인임을 인증 받은 회원들이 물건을 팔고 사기 위한 목적으로 이용하는 중고판매 게시판을 벼룩시장이라 지칭합니다.
2) 벼룩시장 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 다음의 경우 웹 마스터는 게시물을 작성자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업적인 글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그 외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우
3) 벼룩시장에서의 개개인간의 물물거래에 대해서 이화이언은 관여하지 않으며 이로 인해 발생하는 모든 손해에 대해서 이화이언은 책임을 지지 아니 합니다.

제23조(주거정보 서비스)
1) 이화이언의 모든 회원들이 하숙정보를 제공하거나, 개개인간 거래를 위해 이용하는 게시판을 주거정보라 지칭합니다.
2) 하숙정보 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 다음의 경우 웹 마스터는 게시물을 작성자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업적인 글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우
3) 주거정보에서의 개개인간의 물물거래에 대해서 이화이언은 관여하지 않으며 이로 인해 발생하는 모든 손해에 대해서 이화이언은 책임을 지지 아니 합니다.


제24조(광고홍보 서비스)
1) 이화이언의 모든 회원들이 광고, 홍보를 위한 목적으로 이용하는 게시판을 광고홍보라 지칭합니다.
2) 벼룩시장 게시물의 권한은 이화이언의 웹 마스터에게 있으며, 다음의 경우 웹 마스터는 게시물을 작성자의 동의 없이 삭제할 수 있습니다.
① 사회의 공공질서와 미풍양속을 침해하거나 음란, 저속한 정보를 교류하는 목적으로 게시되었을 경우(야동, 망가, 야오이 등이 해당됨)
② 영리추구를 목적으로 한 전문적인 상업적인 글의 경우.
③ 다른 사람의 명예를 손상시키거나 불이익을 주는 글. (예: 실명이나 학번, 그 외 신상정보를 알 수 있는 정보를 공개하거나 사진 등을 게시하는 경우.)
④ 허위정보를 게시한 경우.
⑤ 해당 게시판의 공지에 맞지 않는 글을 게시했을 경우

제25조(컨텐츠서비스)
1) 이화이언 내 문화적 정보 혹은 기타 목적으로 이화이언 운영진이 운영하는 게시판을 지칭하며 컨텐츠 내의 주제는 운영진에 의해 바뀔 수 있습니다.
2) 컨텐츠 내의 게시물에 대해서 이화이언이 권한을 가지며, 상업적인 목적, 이화이언에 불이익을 끼치는 목적에 의한 게시물의 이동은 금합니다.
3) 이화이언의 모든 회원은 컨텐츠 서비스를 이용할 수 있습니다.

제26조(이벤트서비스)
1) 이화이언 내에서 이루어지는 오프라인, 온라인 이벤트를 이벤트 서비스라 지칭하며, 이화이언의 모든 회원은 이벤트 서비스를 이용할 수 있다.
2) 이벤트에 당첨된 회원의 허위정보작성 및 실제 회원의 정보와 다른 경우, 경품수령이 원활하지 않거나 전달이 되지 않을 수 있습니다. 이 경우 이화이언은 이에 대해 책임을 지지 않습니다.

귀하는 상업적인 목적, 그 외 기타 이화이언의 명예를 훼손하기 위해 본 서비스 자체, 서비스의 이용 또는 서비스에의 접속을 복사, 복제, 판매, 재판매, 또는 이용하지 않을 것에 동의 합니다.

제27조(재판관할)

이화이언 커뮤니티와 이용자간에 발생한 서비스 이용에 관한 분쟁으로 인한 소는 민사소송법상의 관할을 가지는 대한민국의 법원에 제기합니다.


부    칙
이 약관은 2016년 6월 1일부터 시행 됩니다.

                        </textarea><br>
          </div>
          <div style="padding-top:10px;">
            <input type="checkbox" name="term_use" id="term_use" tabindex="16" required>&nbsp;동의합니다.
            @if ($errors->has('term_use'))
            <span class="invalid-feedback">
              <strong>{{ $errors->first('term_use') }}</strong>
            </span>
            @endif
          </div>
        </div>
      </div>

      <div class="form-group row">
        <div id="UserRule" class="col-md-12">
          <div class="tit">
            <b>개인정보 보호 정책</b>
          </div>

          <div>
            <textarea id="Content2" readonly="" style="height: 210px;width: 100%;font-size: 12px;">			이화이언 커뮤니티 개인정보보호 정책

&lt;이화이언&gt;('http://ewhaian.com'이하 '이화이언')은(는) 개인정보보호법에 따라 이용자의 개인정보 보호 및 권익을 보호하고 개인정보와 관련한 이용자의 고충을 원활하게 처리할 수 있도록 다음과 같은 처리방침을 두고 있습니다.
&lt;이화이언&gt;('이화이언') 은(는) 회사는 개인정보처리방침을 개정하는 경우 웹사이트 공지사항(또는 개별공지)을 통하여 공지할 것입니다.
○ 본 방침은부터 2017년 1월 1일부터 시행됩니다.


1. 개인정보의 처리 목적 &lt;이화이언&gt;('http://ewhaian.com'이하 '이화이언')은(는) 개인정보를 다음의 목적을 위해 처리합니다. 처리한 개인정보는 다음의 목적 이외의 용도로는 사용되지 않으며 이용 목적이 변경될 시에는 사전동의를 구할 예정입니다.
가. 홈페이지 회원가입 및 관리
회원 가입의사 확인, 회원제 서비스 제공에 따른 본인 식별•인증, 회원자격 유지•관리, 제한적 본인확인제 시행에 따른 본인확인 등을 목적으로 개인정보를 처리합니다.


나. 민원사무 처리
민원인의 신원 확인, 민원사항 확인, 사실조사를 위한 연락•통지, 처리결과 통보 등을 목적으로 개인정보를 처리합니다.


다. 재화 또는 서비스 제공
서비스 제공, 콘텐츠 제공, 본인인증 등을 목적으로 개인정보를 처리합니다.


라. 마케팅 및 광고에의 활용
신규 서비스(제품) 개발 및 맞춤 서비스 제공, 이벤트 및 광고성 정보 제공 및 참여기회 제공, 서비스의 유효성 확인, 접속빈도 파악 또는 회원의 서비스 이용에 대한 통계 등을 목적으로 개인정보를 처리합니다.




2. 개인정보 파일 현황
1. 개인정보 파일명 : 이화 포털 캡쳐 화면
- 개인정보 항목 : 이메일, 휴대전화번호, 자택주소, 자택전화번호, 비밀번호, 로그인ID, 성별, 생년월일, 이름(한글,영문), 학력, 학번, 입학년월, 졸업(예정)년월
- 수집방법 : 홈페이지, 서면양식
- 보유근거 : 이화인 인증
- 보유기간 : 지체 없이 파기
- 관련법령 : 신용정보의 수집/처리 및 이용 등에 관한 기록 : 3년




3. 개인정보의 처리 및 보유 기간

① &lt;이화이언&gt;('이화이언')은(는) 법령에 따른 개인정보 보유•이용기간 또는 정보주체로부터 개인정보를 수집 시에 동의 받은 개인정보 보유, 이용기간 내에서 개인정보를 처리, 보유합니다.

② 각각의 개인정보 처리 및 보유 기간은 다음과 같습니다.
1.&lt;홈페이지 회원가입 및 관리&gt;
&lt;홈페이지 회원가입 및 관리&gt;와 관련한 개인정보는 수집.이용에 관한 동의일로부터&lt;지체 없이 파기&gt;까지 위 이용목적을 위하여 보유.이용됩니다.
-보유근거 : 이화인 인증
-관련법령 : 신용정보의 수집/처리 및 이용 등에 관한 기록 : 3년





4. 개인정보의 제3자 제공에 관한 사항

① &lt;이화이언&gt;('http://ewhaian.com'이하 '이화이언')은(는) 정보주체의 동의, 법률의 특별한 규정 등 개인정보 보호법 제17조 및 제18조에 해당하는 경우에만 개인정보를 제3자에게 제공합니다.
② &lt;이화이언&gt;('http://ewhaian.com')은(는) 다음과 같이 개인정보를 제3자에게 제공하고 있습니다.



1. &lt;&gt;
- 개인정보를 제공받는 자 : 경찰(사이버 수사대)
- 제공받는 자의 개인정보 이용목적 : 고소 등 법적 절차 진행
- 제공받는 자의 보유.이용기간: 최대 3년



5. 정보주체의 권리, 의무 및 그 행사방법 이용자는 개인정보주체로서 다음과 같은 권리를 행사할 수 있습니다.
① 정보주체는 이화이언(‘http://ewhaian.com’이하 ‘이화이언) 에 대해 언제든지 다음 각 호의 개인정보 보호 관련 권리를 행사할 수 있습니다.
1. 개인정보 열람요구
2. 오류 등이 있을 경우 정정 요구
3. 삭제요구
4. 처리정지 요구
② 제1항에 따른 권리 행사는 이화이언(‘http://ewhaian.com’이하 ‘이화이언) 에 대해 개인정보 보호법 시행규칙 별지 제8호 서식에 따라 서면, 전자우편, 모사전송(FAX) 등을 통하여 하실 수 있으며 &lt;기관/회사명&gt;(‘사이트URL’이하 ‘사이트명) 은(는) 이에 대해 지체 없이 조치하겠습니다.
③ 정보주체가 개인정보의 오류 등에 대한 정정 또는 삭제를 요구한 경우에는 &lt;기관/회사명&gt;(‘사이트URL’이하 ‘사이트명) 은(는) 정정 또는 삭제를 완료할 때까지 당해 개인정보를 이용하거나 제공하지 않습니다.
④ 제1항에 따른 권리 행사는 정보주체의 법정대리인이나 위임을 받은 자 등 대리인을 통하여 하실 수 있습니다. 이 경우 개인정보 보호법 시행규칙 별지 제11호 서식에 따른 위임장을 제출하셔야 합니다.



6. 처리하는 개인정보의 항목 작성

① &lt;이화이언&gt;('http://ewhaian.com'이하 '이화이언')은(는) 다음의 개인정보 항목을 처리하고 있습니다.
1&lt;홈페이지 회원가입 및 관리&gt;
- 필수항목 : 이메일, 휴대전화번호, 비밀번호, 로그인ID, 이름




7. 개인정보의 파기&lt;이화이언&gt;('이화이언')은(는) 원칙적으로 개인정보 처리목적이 달성된 경우에는 지체 없이 해당 개인정보를 파기합니다. 파기의 절차, 기한 및 방법은 다음과 같습니다.
-파기절차이용자가 입력한 정보는 목적 달성 후 별도의 DB에 옮겨져(종이의 경우 별도의 서류) 내부 방침 및 기타 관련 법령에 따라 일정기간 저장된 후 혹은 즉시 파기됩니다. 이 때, DB로 옮겨진 개인정보는 법률에 의한 경우가 아니고서는 다른 목적으로 이용되지 않습니다.-파기기한이용자의 개인정보는 개인정보의 보유기간이 경과된 경우에는 보유기간의 종료일로부터 5일 이내에, 개인정보의 처리 목적 달성, 해당 서비스의 폐지, 사업의 종료 등 그 개인정보가 불필요하게 되었을 때에는 개인정보의 처리가 불필요한 것으로 인정되는 날로부터 5일 이내에 그 개인정보를 파기합니다.
-파기방법
전자적 파일 형태의 정보는 기록을 재생할 수 없는 기술적 방법을 사용합니다.



8. 개인정보의 안전성 확보 조치 &lt;이화이언&gt;('이화이언')은(는) 개인정보보호법 제29조에 따라 다음과 같이 안전성 확보에 필요한 기술적/관리적 및 물리적 조치를 하고 있습니다.
1. 정기적인 자체 감사 실시
개인정보 취급 관련 안정성 확보를 위해 정기적(분기 1회)으로 자체 감사를 실시하고 있습니다.

2. 내부관리계획의 수립 및 시행
개인정보의 안전한 처리를 위하여 내부관리계획을 수립하고 시행하고 있습니다.

3. 해킹 등에 대비한 기술적 대책
&lt;이화이언&gt;('이화이언')은 해킹이나 컴퓨터 바이러스 등에 의한 개인정보 유출 및 훼손을 막기 위하여 보안프로그램을 설치하고 주기적인 갱신•점검을 하며 외부로부터 접근이 통제된 구역에 시스템을 설치하고 기술적/물리적으로 감시 및 차단하고 있습니다.

4. 개인정보의 암호화
이용자의 개인정보는 비밀번호는 암호화 되어 저장 및 관리되고 있어, 본인만이 알 수 있으며 중요한 데이터는 파일 및 전송 데이터를 암호화 하거나 파일 잠금 기능을 사용하는 등의 별도 보안기능을 사용하고 있습니다.

5. 접속기록의 보관 및 위변조 방지
개인정보처리시스템에 접속한 기록을 최소 6개월 이상 보관, 관리하고 있으며, 접속 기록이 위변조 및 도난, 분실되지 않도록 보안기능 사용하고 있습니다.

6. 개인정보에 대한 접근 제한
개인정보를 처리하는 데이터베이스시스템에 대한 접근권한의 부여, 변경, 말소를 통하여 개인정보에 대한 접근통제를 위하여 필요한 조치를 하고 있으며 침입차단시스템을 이용하여 외부로부터의 무단 접근을 통제하고 있습니다.



9. 개인정보 보호책임자 작성

① 이화이언(‘http://ewhaian.com’이하 ‘이화이언) 은(는) 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제 등을 위하여 아래와 같이 개인정보 보호책임자를 지정하고 있습니다.

▶ 개인정보 보호책임자
성명 : 정은지
직급 : 마스터
연락처 : wwwewhaiancom@hanmail.net

② 정보주체께서는 이화이언(‘http://ewhaian.com’이하 ‘이화이언) 의 서비스(또는 사업)을 이용하시면서 발생한 모든 개인정보 보호 관련 문의, 불만처리, 피해구제 등에 관한 사항을 개인정보 보호책임자 및 담당부서로 문의하실 수 있습니다. 이화이언(‘http://ewhaian.com’이하 ‘이화이언) 은(는) 정보주체의 문의에 대해 지체 없이 답변 및 처리해드릴 것입니다.




10. 개인정보 처리방침 변경
①이 개인정보처리방침은 시행일로부터 적용되며, 법령 및 방침에 따른 변경내용의 추가, 삭제 및 정정이 있는 경우에는 변경사항의 시행 7일 전부터 공지사항을 통하여 고지할 것입니다.

                        </textarea><br>
          </div>
          <div style="padding-top:10px;">
            <input type="checkbox" name="privacy_policy" id="privacy_policy" tabindex="16">&nbsp;동의합니다.
            @if ($errors->has('privacy_policy'))
            <span class="invalid-feedback">
              <strong>{{ $errors->first('privacy_policy') }}</strong>
            </span>
            @endif
          </div>
        </div>
      </div>
      <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
          <button type="submit" class="btn default-btn fw6">
            {{ trans('plugins/member::dashboard.register-cta') }}
          </button>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-auth.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
       https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  const firebaseConfig = {
    apiKey: "AIzaSyCHI1fPqoPqsDp98dOJcDKeJ5lFVX7GMlY",
    authDomain: "eh-notifications.firebaseapp.com",
    projectId: "eh-notifications",
    storageBucket: "eh-notifications.appspot.com",
    messagingSenderId: "764713474979",
    appId: "1:764713474979:web:448998667371ac4f921816",
    measurementId: "G-HCQL1XMPYP"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
<script>
  // window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('send_sms', {
  //   'size': 'invisible'
  // });
  $(function() {
    // $('#domainmail').change(function() {
    //   //Get
    //   var namemail = $('#namemail').val();
    //   var domainmail = $('#domainmail').val();
    //
    //   //Set
    //   $('#email').val(namemail + '@' + domainmail);
    // })

    // $('#namemail').change(function() {
    //   //Get
    //   var namemail = $('#namemail').val();
    //   var domainmail = $('#domainmail').val();
    //
    //   //Set
    //   $('#email').val(namemail + '@' + domainmail);
    // })

    $('#domainmail').change(function() {
      //Get
      var namemail = $('#namemail').val();
      var domainmail = $('#domainmail').val();

      //Set
      $('#email').val(namemail + '@' + domainmail);

      if (namemail != '' && domainmail != '') {
        $('#btnSendEmail').show();
      }
    })

    $('#namemail').change(function() {
      //Get
      var namemail = $('#namemail').val();
      var domainmail = $('#domainmail').val();

      //Set
      $('#email').val(namemail + '@' + domainmail);

      if (namemail != '' && domainmail != '') {
        $('#btnSendEmail').show();
      }
    })

    $('#send_sms').on('click', function(e) {

      e.preventDefault();
      var $input = $('#phone');
      var $this = $(this);
      if ($input.val() == '') {
        alert('Please enter phone number');
        $input.focus();
        return;
      }
      // var phone = $input.val().replace($input.val().charAt(0), '+82');
      var phone = $input.val();
      window.recaptchaVerifier.verify().then(function(response) {
        $('#recaptcha_response').val(response);
        if ($this.data('isRunAjax') == true) {
          return;
        }
        $this.css('pointer-events', 'none').data('isRunAjax', true);
        $.ajax({
          type: 'POST',
          url: '{{route('public.member.send.sms')}}',
          data: {
            _token: "{{ csrf_token() }}",
            'phone': phone,
            'recaptcha': $('#recaptcha_response').val()
          },
          success: function(data) {
            if (data.err != null) {
              alert('Send sms fail!!');
              console.log(data.err);
              return false;
            }
            $('#sessionInfo').val(data.msg);
            alert('send sms success');
            return true;

          }
        }).always(function() {
          $this.css('pointer-events', 'auto').data('isRunAjax', false);
        });
      });
      return false;

    })

    // 이메일 발송
    $('#send_email').on('click', function(e){
      // e.preventDefault();
      $("#verify_email_code").show();
      var email = $('#email').val();
      console.log(email);
    })

    // 이메일 코드 인증
    $('confirm_email').on('click', function(e) {

    })

    $('#phone').on('keypress', function (evt){
      if (evt.which !== 8 && evt.which !== 0 && evt.which < 48 || evt.which > 57)
      {
        evt.preventDefault();
      }
      let value = $(this).val();
      if (value.indexOf('#') != -1) {
        $(this).val(value.replace(/\#/g, ""));
      }
    })

      // password confirm_password validate
    $('#password-1, #password-confirm-2').on('change', function () {
      let password = $('#password-1').val()
      let confirmPassword = $('#password-confirm-2').val()
      if (password === confirmPassword){
        $('#password-confirm-2').removeClass('is-invalid')
        $('#password-confirm-2').addClass('is-valid')
      }else{
        $('#password-confirm-2').removeClass('is-valid')
        $('#password-confirm-2').addClass('is-invalid')
      }
    });
    $('#phone').on('keypress', function (){
      let phoneVal = $('#phone').val();
      if (phoneVal.length >= 11){
        $('#phone').addClass('is-invalid')
        $('#phone').removeClass('is-valid')
      }else{
        $('#phone').removeClass('is-invalid')
        $('#phone').addClass('is-valid')
      }
    })
  })
</script>
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest(\Botble\Member\Http\Requests\VerifyEmailSendRequest::class); !!}
@endpush
