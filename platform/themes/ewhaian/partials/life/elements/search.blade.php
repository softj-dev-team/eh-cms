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
        <ul class="popular__list nav">
            <?php $count =0 ; $parentCategories =0 ?>
            <li class="popular__item">
                <a class="alert {{request('parentCategories') == null || request('parentCategories') == 0 ? 'active' : ''}} " data-toggle="tab" href="javascript:{}" title="전체" data-value="0" id="parent0">전체</a>
            </li>
            @foreach ($categories as $item)
            @if($item->parent_id == 1)
            <li class="popular__item">
                <a class="alert  <?php

                if(request('parentCategories') != null ){
                    if(request('parentCategories') == $item->id){
                        echo "active";
                        $count =1 ;
                        $parentCategories = $item->id;
                    }
                }

                ?>" data-toggle="tab" href="javascript:{}" title="{{$item->name}}"
                    data-value="{{$item->id}}" id="parent{{ $item->id ?? '0' }}" >{{$item->name}}</a>
                    <style>#parent{{ $item->id ?? '0' }} {background-color: #{{$item->background ?? '000000'}};color: #{{$item->color ?? 'FFFFFF' }};}</style>
            </li>
            <?php $count = 1; ?>
            @endif
            @endforeach
        </ul>
    </div>
    {{------------------- children -------------------}}
    <div class="tab-content">
        <?php $count =0; ?>
        <div id="flare0"
            class="tab-pane <?php

            if(request('parentCategories') == null || request('parentCategories') == 0 ){
                echo "active";
                $count =1 ;
            }
            ?>"
            role="tabpanel">
            <div class="popular-search">
                <ul class="popular__list ">
                    <li class="popular__item">
                        <a class="alert bg-white-1 @if(request('childCategories') == 0) active @endif all" href="javascript:{}" title="전체" data-value="0">전체</a>
                    </li>
                    <?php $childrenCategories = 0?>
                    @foreach ($categories as $subItem)
                    @if($subItem->parent_id == 2)
                    <li class="popular__item">
                        <a class="alert <?php
                            if(request('childCategories') != null ){
                                if(request('childCategories') == $subItem->id){
                                    echo "active";
                                    $count =1 ;
                                    $childrenCategories = $subItem->id;
                                }
                            }

                            ?> " href="javascript:{}" title="{{$subItem->name}}"
                            data-value="{{$subItem->id}}" id="children{{$subItem->id ?? '0' }}" >{{$subItem->name}}</a>
                            <style>#children{{ $subItem->id ?? '0' }} {background-color: #{{$subItem->background ?? '000000'}};color: #{{$subItem->color ?? 'FFFFFF' }};}</style>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @foreach ($categories as $item)
        @if($item->parent_id == 1)
        <div id="flare{{$item->id}}"
            class="tab-pane <?php

            if(request('parentCategories') != null ){
                if(request('parentCategories') == $item->id){
                    echo "active";
                    $count =1 ;
                }
            } else{
                    if ($count == 0){
                        echo "active";
                        $count =1 ;
                    };
            }

            ?>"
            role="tabpanel">
            <div class="popular-search">
                <ul class="popular__list ">
                    <li class="popular__item">
                        <a class="alert bg-white-1 @if(request('childCategories') == 0) active @endif all" href="javascript:{}" title="전체" data-value="0">전체</a>
                    </li>
                    <?php $childrenCategories = 0?>
                    @foreach ($categories as $subItem)
                    @if($subItem->parent_id == 2)
                    <li class="popular__item">
                        <a class="alert <?php
                            if(request('childCategories') != null ){
                                if(request('childCategories') == $subItem->id){
                                    echo "active";
                                    $count =1 ;
                                    $childrenCategories = $subItem->id;
                                }
                            }

                            ?> " href="javascript:{}" title="{{$subItem->name}}"
                            data-value="{{$subItem->id}}" id="children{{$subItem->id ?? '0' }}" >{{$subItem->name}}</a>
                            <style>#children{{ $subItem->id ?? '0' }} {background-color: #{{$subItem->background ?? '000000'}};color: #{{$subItem->color ?? 'FFFFFF' }};}</style>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <?php $count = 1; ?>
        @endif
        @endforeach
        <form id="categories_search" action="{{$route ?? route('flare.search')}}" method="get">
            <input type="hidden" name="parentCategories" id="parentCategories" value="{{$parentCategories ?? 0}}">
            <input type="hidden" name="childCategories" id="childCategories" value="{{$childrenCategories ?? 0}}">
            <input type="hidden" name="style" value="{{request('style') ?? 0}}">

        </form>


    </div>
</div>
<form action="{{$route ?? route('flare.search')}}" method="GET" id="form-search-1">
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
                   id="startDate1" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off" readonly>
          </div>
          <span class="filter__connect">-</span>
          <div class="d-flex align-items-end ml-lg-2">
            <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate1"
                   name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off" readonly>
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

      <input type="hidden" name="parentCategories" id="parentCategories2" value="{{request('parentCategories') }}">
      <input type="hidden" name="childCategories" id="childCategories2" value="{{request('childCategories')}}">
      <input type="hidden" name="style" value="{{request('style') ?? 0}}">

      <input type="submit" title="Enrollment" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"style="display:none">

  </form>
</div>
