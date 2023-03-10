<style>
  .modal-body b {
    font-size: 25px;
  }

  .hint-text {
    text-align: left;
    margin: 15px auto 0;
    max-width: 649px;
    color: #c54d39;
    font-size: 12px;
  }
  @media screen and (max-width: 775px) {
    .modal-body b {
      font-size: 30px;
    }

    .button-group {
      display: flex;
    }

    .btn-default, .btn-primary {
      padding: 0;
    }

  }
  .btn {
    width : 14em;
    margin-right : 8px;
  }
  .btn-default {
    background-color : #dddddd;
  }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__control">
        <div class="nav nav-left">
          <ul class="nav__list">
            <p class="nav__title">
              <svg width="40" height="18" aria-hidden="true">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
              </svg>
            </p>
            <li class="nav__item">
              <a class="active" href="javascript:void(0);" style="cursor: inherit;"
                 title="{{__('garden.set_password')}}">{{__('garden.set_password')}}</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="sidebar-template__content">
        <div class="heading">
          <div>
            <div style="display: flex">
              <div class="heading__title">
                <div>
                  비밀단어 입력
                </div>
              </div>

              <div style="margin-left: 20px;font-size: 15px;margin-top: 7px;">
                <div style="color: #EC1469; font-weight: 600">
                  비밀의 화원 입장을 위해 비밀단어를 입력해주세요.
                </div>
              </div>
            </div>

          </div>

          <div class="content">
            <div>
              @if (session('permission'))
                <br>
                <div class="alert alert-danger" style="width: 100%">
                  {{ session('permission') }}
                </div>
              @endif
              @if ($errors->any())
                <div class="alert alert-danger" style="width: 100%">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
            </div>
            <div>
              <div style="
                            background-image: url('/themes/ewhaian/img/pwgarden.png');
                            background-position: center;
                            min-height: 200px;
                            max-height: 500px;
                            height: 36vw;
                            background-repeat: no-repeat;
                            background-size: contain;"
              >

              </div>
            </div>
            <div class="d-lg-flex align-items-center mx-3">

              <div class="align-items-start flex-grow-1">
                <form action="{{route('gardenFE.passwd')}}" method="post">
                  @csrf
                  <div class="form-group form-group--1 flex-grow-1 mb-3">
                    <label for="password" class="form-control form-control--hint">
                      <input type="password" id="password2" name="password" placeholder="&nbsp;"
                             oninput="this.setCustomValidity('')"
                             value="{{Cookie::get('password_garden') ?? ''}}" minlength="10" maxlength="16">
                      <span class="form-control__label" style="pointer-events: none;">{{__('garden.set_password')}}</span>
                    </label>
                  </div>
                  <div class="button-group mb-2" >
                  <button type="submit" class="btn btn-primary">{{__('garden.confirm')}}</button>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#showGardenKey">{{__('garden.show')}}</button>
                  </div>
                </form>
                  <div class="button-group mb-2" >
                  </div>
              </div>
            </div>

          </div>
        </div>

      </div>

    </div>
</main>
<!-- Modal -->

<!-- Modal -->
<div class="modal fade modal--confirm" id="showGardenKey" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-lg" role="document" style="text-align: center;">
    <div class="modal-content">
      <div class="modal-body">
        <table>
          <th></th>
          <tr>
            <td>
              <img src="{{Theme::asset()->url('img/bimil_1.png')}}" alt="Image">
            </td>
          </tr>
          <tr style="color: black;">
            <td>
              <b>{{ auth()->guard('member')->user()->passwd_garden }}</b>
              <div class="hint-text">
                <div>- {{ __('garden.secret_password_modified', ['date' => $date]) }}</div>
                <div>- {{ __('garden.policy_text') }}</div>
              </div>
            </td>
          </tr>
          <tr>
            <!-- <td>
              <img src="{{Theme::asset()->url('img/bimil_2.png')}}" alt="Image">
            </td> -->
          </tr>
          <tr>
            <td>
              <button type="button" class="btn btn-primary" data-dismiss="modal"
                      style="margin-bottom: 10px; margin-top: 10px;">{{__('garden.close')}}</button>
            </td>
          </tr>
        </table>

      </div>
    </div>
  </div>
</div>
<!-- Modal-->

<script>
  {{--document.getElementById('password2').setCustomValidity("{{__('garden.set_password')}}");--}}
</script>
