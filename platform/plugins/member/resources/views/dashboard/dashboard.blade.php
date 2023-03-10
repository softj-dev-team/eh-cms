@extends('plugins.member::layouts.skeleton')
@section('custom-css')
    <style>
        .main_image_dashboard{
            height: 2195px;
        }

        @media (max-width: 425px) {
            .main_image_dashboard{
                height: 1172px;
            }
        }
    </style>
@endsection

@section('content')
  <div class="dashboard crop-avatar">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-3 dn db-ns">
          <div class="mb3">
            <div class="sidebar-profile">
                <div class="avatar-container mb-2">
                    <div class="profile-image" style="cursor: unset">
                    <div class="mt-card-avatar-circle" style="max-width: 150px">
                        <img src="{{ getLvlImage() }}" alt="Avatar" class="br-100" style="width: 150px;">
                    </div>
                    </div>
                </div>
              <div class="f4 b">{{ $user->fullname }}</div>
              <div class="f6 mb3 light-gray-text">
                <i class="fas fa-envelope mr2"></i><a href="mailto:{{ $user->email }}" class="gray-text">{{ $user->email }}</a>
              </div>

            </div>
          </div>
        </div>
        <div class="col-md-9 mb-3">
            <div>
                <div style="
                    background-image: url('/themes/ewhaian/img/profile_level.png');
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: contain;" class="main_image_dashboard"
                >
                </div>
            </div>
          </div>
      </div>
    </div>
    @include('plugins.member::modals.avatar')
  </div>
@endsection
