<style>
    .popular-search .nav li {
        margin-left: 0px;
    }

    .popular-search .nav li a {
        padding: 5px 1.33333em;
        max-height: 30px;
        min-height: 30px;
        font-size: 0.85714em;
    }

    .popular-search li a.active,
    .popular-search li a:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
</style>

<div class="item">
    {{-- --------------------parent -------------------}}
    <div class="popular-search ">
        <ul class="popular__list tab-pane ">

            @if ($categories == 'egarden')
                        {{-- <li class="popular__item">
                            <a class="alert alert bg-green " href="javascript:{}" title="Approved" id="categories0" data-value="publish" >Approved</a>
                        </li>
                        <li class="popular__item">
                            <a class="alert bg-green-1 " href="javascript:{}" title="Pending" id="categories1" data-value="pending" >Pending</a>
                        </li>
                        <li class="popular__item ">
                            <a class="alert bg-main" href="javascript:{}" title="Join us" id="categories2" data-value="draft" >Join us</a>
                        </li> --}}
            @else
            <li class="popular__item">
                    <a class="alert bg-gray @if(request('categories')==0) active @endif all" href="javascript:{}" title="{{__('egarden.room.all')}}" data-value="0">{{__('egarden.room.all')}}</a>
                </li>
                    @foreach ($categories as $item)
                        <li class="popular__item">
                            <a class="alert <?php
                            if(request('categories') != null ){
                                if(request('categories') == $item->id){
                                    echo "active";
                                }
                            }

                            ?>" href="javascript:{}" title="All" id="categories{{ $item->id ?? '0' }}" data-value="{{$item->id}}" > {{$item->name}}</a>
                        </li>
                    <style>#categories{{ $item->id ?? '0' }} {background-color: #{{$item->background ?? '000000'}};color: #{{$item->color ?? 'FFFFFF' }};}</style>
                    @endforeach
            @endif

        </ul>
        <form id="categories_search" action="{{route($route ?? 'jobs.search')}}" method="get">
                <input type="hidden" name="categories" id="childCategories" value="{{request('categories') ?? 0}}">
        </form>
    </div>
</div>
<form action="{{route($route ?? 'jobs.search')}}" method="GET" id="form-search-1">
    <div class="filter align-items-center">
      <div class="filter__item filter__title mr-3">{{__('egarden.room.search')}}</div>
      <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
        <div class="d-flex align-items-center">
          <span class="arrow">
            <svg width="6" height="15" aria-hidden="true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
            </svg>
          </span>
          <input data-datepicker-start type="text" class="form-control form-control--date startDate"
            id="startDate" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
        </div>
        <span class="filter__connect">-</span>
        <div class="d-flex align-items-md-center">
          <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate"
            name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
          <span class="arrow arrow--next">
            <svg width="6" height="15" aria-hidden="true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
            </svg>
          </span>
        </div>
      </div>

      <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
        <select class="form-control form-control--select mx-3" name="type" value="{{ request('type') }}">
          <option value="0" @if(request('type')==0) selected @endif>{{__('egarden.title')}}</option>
          <option value="1" @if(request('type')==1) selected @endif>{{__('egarden.detail')}}</option>
        </select>
        <div class="form-group form-group--search  flex-grow-1  mx-3">
            <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
            <span class="form-control__icon">
                <svg width="14" height="14" aria-hidden="true" class="icon">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                </svg>
            </span>
            </a>
          <input type="text" class="form-control" placeholder="{{__('egarden.enter_title')}}" name="keyword"
            value="{{ request('keyword') }}">
        </div>
      </div>
      <input type="hidden" name="categories" id="childCategories2" value="{{request('categories')}}">

      <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
        style="display:none">
  </form>
</div>
