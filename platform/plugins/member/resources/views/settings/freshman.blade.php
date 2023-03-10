@extends('plugins.member::layouts.skeleton')
@section('content')
  <div class="settings crop-avatar">
    <div class="container">
      <div class="row">
        @include('plugins.member::settings.sidebar')

        <div class="col-6 mo-col-100">
          <!-- Setting Title -->
          <div class="row">
            <div class="col-12">
              <h4 class="with-actions">
                @if (Route::currentRouteName() == 'public.member.get.freshman.sprout')
                  새내기 인증
                @else
                  이화인 인증
                @endif
              </h4>
              <p><a href="http://www.ewhaian.com/Ewha_Notice/Notice_View.asp?n_idx=169&amp;page=1">인증방법</a>을 참고하여 이미지를 업로드해주세요.</p>
            </div>
          </div>
          <br>
          <form id="form-freshman" action="{{route('public.member.post.freshman')}}" method="post"
                enctype="multipart/form-data">
            @csrf

            @if (Route::currentRouteName() == 'public.member.get.freshman.sprout')
              @include('plugins.member::settings.freshman_sprout')
            @endif

            @if (Route::currentRouteName() == 'public.member.get.freshman.ewhain')
              @include('plugins.member::settings.freshman_ewhain')
            @endif
          </form>
        </div>
      </div>
    </div>
    @include('plugins.member::modals.avatar')
  </div>
@endsection

@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\FreshmenRequest::class); !!}

  <script>
    function deleteImage(type) {
      $.ajax({
        type: 'POST',
        url: '{{ route('public.member.del.delete-image', ['id' => $user->id]) }}',
        data: {
          _token: "{{ csrf_token() }}",
          type: type
        },
        dataType: 'json',
        success: function (data) {
          if (data.error === false) {
            Botble.showNotice('success', data.message);
            location.reload();
          }
        }
      });
    }

    function cancelRequest(type) {
      $.ajax({
        type: 'POST',
        url: '{{ route('public.member.put.cancel-request', ['id' => $user->id]) }}',
        data: {
          _token: "{{ csrf_token() }}",
          type: type
        },
        dataType: 'json',
        success: function (data) {
          if (data.error === false) {
            Botble.showNotice('success', data.message);
            location.reload();
          }
        }
      });
    }

    $(document).ready(function () {
      function filePreview1(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('.freshman-view-1 .br2').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      $('.freshman-view-1').click(function () {
        $('#uploadFile1').trigger('click');
      });
      $('#uploadFile1').change(function () {
        filePreview1(this);
      });

      function filePreview2(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('.freshman-view-2 .br2').attr('src', e.target.result);
          };
          reader.readAsDataURL(input.files[0]);
        }
      }

      $('.freshman-view-2').click(function () {
        $('#uploadFile2').trigger('click');
      });

      $('#uploadFile2').change(function () {
        filePreview2(this);
      });
    });
  </script>
@endpush
