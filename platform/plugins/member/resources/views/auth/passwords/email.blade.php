@extends('plugins.member::layouts.skeleton')
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ trans('plugins/member::dashboard.find_id') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('public.member.findID') }}">
              @csrf
              <div class="form-group row">
                <label for="email_findId" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.email') }}</label>
                <div class="col-md-6">
                  <input id="email_findId" type="email_findId" class="form-control{{ $errors->has('email_findId') ? ' is-invalid' : '' }}" name="email_findId" value="{{ old('email_findId') }}" required>
                  @if ($errors->has('email_findId'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('email_findId') }}</strong>
                </span>
                  @endif
                </div>
              </div>
              <input type="hidden" name="type" value="1">
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn default-btn fw6">
                    {{ trans('plugins/member::dashboard.find_id') }}
                  </button>
                  <a href="{{ route('public.member.login') }}" class="btn btn-link gray-text">{{ trans('plugins/member::dashboard.cancel-link') }}</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

     <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ trans('plugins/member::dashboard.reset-password-title') }}</div>
          <div class="card-body">
            <form method="POST" action="{{ route('public.member.password.email') }}">
              @csrf
              <div class="form-group row">
                <label for="id_login" class="col-md-4 col-form-label text-md-right">ID</label>
                <div class="col-md-6">
                  <input id="id_login" type="text" class="form-control{{ $errors->has('id_login') ? ' is-invalid' : '' }}" name="id_login" value="{{ old('id_login') }}" required>
                  @if ($errors->has('id_login'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('id_login') }}</strong>
                </span>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('plugins/member::dashboard.email') }}</label>
                <div class="col-md-6">
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                  @if ($errors->has('email'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
                </span>
                  @endif
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn default-btn fw6">
                    {{ trans('plugins/member::dashboard.reset-password-cta') }}
                  </button>
                  <a href="{{ route('public.member.login') }}" class="btn btn-link gray-text">{{ trans('plugins/member::dashboard.cancel-link') }}</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
