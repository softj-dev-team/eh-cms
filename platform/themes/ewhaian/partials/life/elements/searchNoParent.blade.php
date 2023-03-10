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
            <li class="popular__item">
                <a class="alert bg-gray @if(request('categories') == 0) active @endif all" href="javascript:{}" title="전체" data-value="0">전체</a>
            </li>
            @foreach ($categories as $item)
                <li class="popular__item">
                    <a class="alert <?php
                    if(request('categories') != null ){
                        if(request('categories') == $item->id){
                            echo "active";
                        }
                    }

                    ?>" href="javascript:{}" title="전체" id="categories{{ $item->id ?? '0' }}" data-value="{{$item->id}}" > {{$item->name}}</a>
                </li>
            <style>#categories{{ $item->id ?? '0' }} {background-color: #{{$item->background ?? '000000'}};color: #{{$item->color ?? 'FFFFFF' }};}</style>
            @endforeach
        </ul>
        <form id="categories_search" action="{{route($route ?? 'jobs.search')}}" method="get">
            @if(request('parents'))
             <input type="hidden" name="parents" value="{{request('parents') }}">
            @else
            <input type="hidden" name="parents" value="1">
            @endif
                <input type="hidden" name="categories" id="childCategories" value="{{request('categories') ?? 0}}">
                <input type="hidden" name="style" value="{{request('style') ?? 0}}">

        </form>
    </div>
</div>
@if (
    Route::currentRouteName() != 'life.shelter_list' &&
    Route::currentRouteName() != 'life.advertisements_list'
    )
{!! Theme::partial('orderby',[
    'route'=> route($route ?? 'jobs.search'),
    'style'=>"width: 150px;margin-bottom: 20px;",
    'have_like' => $have_like ?? 0,
]) !!}
@endif
<form action="{{route($route ?? 'jobs.search')}}" method="GET" id="form-search-1">
    <div class="filter align-items-center">
      <div class="filter filter--1 align-items-end" style="width:100%;margin-bottom: 0">
        <div class="filter__item filter__title mr-3">{{__('life.flea_market.search')}}</div>
        <div class="filter__item d-flex  align-items-end justify-content-md-center  mx-3">
          <div class="d-flex align-items-center mr-2">
                            <span class="arrow">
                                <svg width="6" height="15" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                                </svg>
                            </span>
            <input data-datepicker-start type="text" class="form-control form-control--date startDate"
                   id="startDate1" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off" readonly="readonly">
          </div>
          <span class="filter__connect">-</span>
          <div class="d-flex align-items-end ml-lg-2">
            <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate1"
                   name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off" readonly="readonly">
            <span class="arrow arrow--next">
                                <svg width="6" height="15" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                                </svg>
                            </span>
          </div>
        </div>

        <div class="filter__item d-flex  align-items-end justify-content-md-center flex-grow-1">
          <select class="form-control form-control--select" name="type" value="{{ request('type') }}">
            <option value="0" @if(request('type')==0) selected @endif>{{__('life.flea_market.title')}}</option>
            <option value="1" @if(request('type')==1) selected @endif>{{__('life.flea_market.detail')}}</option>
          </select>

          <div class="form-group form-group--search  flex-grow-1  mx-3">
            <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                <span class="form-control__icon">
                    <svg width="14" height="14" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search">
                        </use>
                    </svg>
                </span>
            </a>
            <input type="text" class="form-control" placeholder="{{__('search.title_or_content')}}" name="keyword"
                   value="{{ request('keyword') }}">
          </div>
        </div>
      </div>

      <input type="hidden" name="categories" id="childCategories2" value="{{request('categories')}}">
      <input type="hidden" name="parents" id="parents2" value="{{request('parents')}}">
      <input type="hidden" name="style" value="{{request('style') ?? 0}}">

      <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
        style="display:none">
  </form>
</div>
