<style>
.input-group-image .form-control--upload {
    height: 28em;
    width: 28em;
    max-width: 28em;
    background-size: 28em;
}
@media  screen and (max-width : 480px){
    .input-group-image .form-control--upload{
        height: 20em;
        width: 20em ;
        max-width: 20em;
        background-size: 20em ;
    }
}
@media  screen and (max-width : 360px){
    .input-group-image .form-control--upload{
        height: 15em;
        width: 15em ;
        max-width: 15em;
        background-size: 15em ;
    }
}
</style>
<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            <h4 class="category-menu__title">{{__('master_room')}}</h4>
            <ul class="category-menu__links">
              @foreach ($categories as $item)
              <li class="category-menu__item">
                <a href="{{route('masterRoomFE.list')}}"
                  title="{{$item->name}}">{{$item->name}}</a>
              </li>
              @endforeach
              <li class="category-menu__item active">
                  <a href="{{route('masterRoomFE.address.list')}}"
                    title="Address">Address</a>
                </li>
            </ul>
          </div>
          <!-- end of category menu -->
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
        @if(\Request::route()->getName() == "masterRoomFE.address.create")
            <form action="{{route('masterRoomFE.address.create')}}" method="post" id="my_form" enctype="multipart/form-data">
        @else
            <form action="{{route('masterRoomFE.address.edit',['id' => $address->id])}}" method="post" id="my_form" enctype="multipart/form-data">
        @endif
             @csrf
            <div class="form form--border">
                    <h3 class="form__title"> @if(\Request::route()->getName() == "masterRoomFE.address.create") {{__('master_room.address.create')}} @else {{__('master_room.address.edit')}} #{{$address->id}} @endif </h3>
                    <p class="text-right"><span class="required">*</span>{{__('master_room.required')}}</p>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('err'))
                    <div class="alert alert-danger" style="display: block">
                      <p>{{ session('err') }}</p>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success" style="display: block">
                      <p>{{ session('success') }}</p>
                    </div>
                    @endif
                    <div class="form-group form-group--1">
                      <label for="address" class="form-control">
                      <input type="text" id="address" placeholder="&nbsp;" name="address" value="{{ $address ? $address->address : '' }}">
                        <span class="form-control__label">{{__('master_room.address.address')}} <span class="required">*</span></span>
                      </label>
                    </div>
                    {{-- <div class="form-group form-group--1">
                        <label for="classification" class="form-control">
                        <input type="text" id="classification" placeholder="&nbsp;" name="classification" value="{{ $address ? $address->classification : '' }}">
                          <span class="form-control__label">{{__('master_room.address.classification')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}
                    <div class="form-group form-group--1">
                        <label for="email" class="form-control">
                        <input type="email" id="email" placeholder="&nbsp;" name="email" value="{{ $address ? $address->email : '' }}">
                          <span class="form-control__label">{{__('master_room.address.email')}} <span class="required">*</span></span>
                        </label>
                    </div>
                    {{-- <div class="form-group form-group--1">
                        <label for="home_page" class="form-control">
                        <input type="text" id="home_page" placeholder="&nbsp;" name="home_page" value="{{ $address ? $address->home_page : '' }}">
                          <span class="form-control__label">{{__('master_room.address.home_page')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}
                    {{-- <div class="form-group form-group--1">
                        <label for="zip_code" class="form-control">
                        <input type="text" id="zip_code" placeholder="&nbsp;" name="zip_code" value="{{ $address ? $address->zip_code : '' }}">
                          <span class="form-control__label">{{__('master_room.address.zip_code')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}
                    {{-- <div class="form-group form-group--1">
                        <label for="home_phone" class="form-control">
                        <input type="number" id="home_phone" placeholder="&nbsp;" name="home_phone" value="{{ $address ? $address->home_phone : '' }}">
                          <span class="form-control__label">{{__('master_room.address.home_phone')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}
                    <div class="form-group form-group--1">
                        <label for="mobile_phone" class="form-control">
                        <input type="number" id="mobile_phone" placeholder="&nbsp;" name="mobile_phone" value="{{ $address ? $address->mobile_phone : '' }}">
                          <span class="form-control__label">{{__('master_room.address.mobile_phone')}} <span class="required">*</span></span>
                        </label>
                    </div>
                    {{-- <div class="form-group form-group--1">
                        <label for="company_phone" class="form-control">
                        <input type="number" id="company_phone" placeholder="&nbsp;" name="company_phone" value="{{ $address ? $address->company_phone : '' }}">
                          <span class="form-control__label">{{__('master_room.address.company_phone')}} <span class="required">*</span></span>
                        </label>
                    </div> --}}
                    <div class="form-group form-group--1">
                        <label for="memo" class="form-control">
                        <input type="text" id="memo" placeholder="&nbsp;" name="memo" value="{{ $address ? $address->memo : '' }}">
                          <span class="form-control__label">{{__('master_room.address.memo')}} <span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="form-group form-group--1">
                        <label for="address_id" class="form-control">
                        <input type="text" id="address_id" placeholder="&nbsp;" name="address_id" value="{{ $address ? $address->address_id : '' }}">
                          <span class="form-control__label">{{__('master_room.address.address_id')}} <span class="required">*</span></span>
                        </label>
                    </div>
                    <div class="d-sm-flex" style="margin-top: 20px;">
                        <span class="text-bold mr-4 pr-2" style="margin-top: 10px;">{{__('master_room.status')}} <span class="required">*</span></span>
                        <div class="flex-grow-1">
                                <select class="form-control form-control--select mx-3" name="status">
                                    <option value="publish" @if(isset($address) && $address->status == "publish" ) selected @endif>{{__('master_room.publish')}}</option>
                                    <option value="draft" @if(isset($address) && $address->status == "draft" ) selected @endif>{{__('master_room.draft')}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        @if(\Request::route()->getName() == "masterRoomFE.address.create")
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('master_room.save')}}</a>
                        @else
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('master_room.update')}}</a>
                        @endif


                    </div>
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>
