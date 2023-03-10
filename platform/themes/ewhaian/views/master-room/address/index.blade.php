<style>
    .sub-table .title{
        background-color: #e5e5e5;
        padding-left: 0px;
        text-align: center;
    }
    .sub-table td{
        border: none !important;
        padding: 0.21429em 4px;
        padding: 0 5px 10px 5px;
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
                <a href="{{route('masterRoomFE.address.list')}}" title="{{__('master_room.address.list')}}">{{__('master_room.address.list')}}</a>
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
        <h3 class="title-main">{!!Theme::breadcrumb()->getCrumbs()[1]['label']!!}</h3> <br>
        {{-- --------------------------- --}}
        <!-- filter -->
            <form action="{{route('masterRoom.address.search')}}" method="GET" id="form-search-1">
                <div class="filter align-items-center">
                <div class="filter__item filter__title mr-3">{{__('master_room.search')}}</div>
                <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3 ">
                    <div class="d-flex align-items-center">
                        <span class="arrow">
                        <svg width="6" height="15" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                        </svg>
                        </span>
                        <input data-datepicker-start type="text" class="form-control form-control--date startDate"
                        id="startDate1" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
                    </div>
                <span class="filter__connect">-</span>
                <div class="d-flex align-items-md-center">
                    <input data-datepicker-end type="text" class="form-control form-control--date endDate" id="endDate1"
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
                    <option value="1" @if(request('type')== 1) selected @endif>{{__('master_room.address.address')}}</option>
                    {{-- <option value="2" @if(request('type')== 2) selected @endif>{{__('master_room.address.classification')}}</option> --}}
                    <option value="3" @if(request('type')== 3) selected @endif>{{__('master_room.address.email')}}</option>
                    {{-- <option value="4" @if(request('type')== 4) selected @endif>{{__('master_room.address.home_page')}}</option> --}}
                    {{-- <option value="5" @if(request('type')== 5) selected @endif>{{__('master_room.address.zip_code')}}</option> --}}
                    {{-- <option value="6" @if(request('type')== 6) selected @endif>{{__('master_room.address.home_phone')}}</option> --}}
                    <option value="7" @if(request('type')== 7) selected @endif>{{__('master_room.address.mobile_phone')}}</option>
                    {{-- <option value="8" @if(request('type')== 8) selected @endif>{{__('master_room.address.company_phone')}}</option> --}}
                    <option value="9" @if(request('type')==9) selected @endif>{{__('master_room.address.memo')}}</option>
                </select>
                <div class="form-group form-group--search  flex-grow-1  mx-3">
                    <a href="javascript:{}" onclick="document.getElementById('form-search-1').submit();">
                    <span class="form-control__icon">
                        <svg width="14" height="14" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                        </svg>
                    </span>
                    </a>
                    <input type="text" class="form-control" placeholder="{{__('master_room.address.enter_keyword')}}" name="keyword"
                    value="{{ request('keyword') }}">
                </div>
                </div>

                <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="{{__('master_room.address.search')}}"
                style="display:none">
            </form>
      </div>
      <!-- end of filter -->
        @if (session('err'))
            <div class="alert alert-danger" style="display: block">
                {{ session('err') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
      <!-- table -->
      <div class="content">
        <div class=" table-responsive">
            <table class="table table--content-middle">
            <thead>
            <tr>
                <th style="text-align: center;">{{__('master_room.address.id')}}</th>
                <th style="text-align: center;padding-left: 0px;width: 52%;">{{__('master_room.address.nickname')}}</th>
                <th style="text-align: center;padding-left: 0px;">{{__('master_room.address.writer')}}</th>
                <th style="text-align: center;padding-left: 0px;">{{__('master_room.address.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @if(count($address) > 0)
                @foreach ($address as $key => $item)
                <tr>
                    <td style="text-align: center;border-bottom: 1px solid #FFF;">{!! $item->id !!}</td>
                    <td style="text-align: center;border-bottom: 1px solid #FFF;">{!!  $item->member_id ? $item->member->nickname : "Admin" !!}</td>
                    <td style="text-align: center;border-bottom: 1px solid #FFF;">
                        {!! $item->member_id ? $item->member->fullname : "Admin" !!}(    {!! $item->member_id ? $item->member->id_login : "Admin" !!})
                    </td>
                    <td style="text-align: center;border-bottom: 1px solid #FFF;">{{ date("Y-m-d",strtotime( $item->published) ) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top: 0;">
                        <table class="sub-table table--content-middle">
                            <tr>
                                {{-- <td class="title" style="width: 20%">{{__('master_room.address.classification')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->classification ?? "--",request('keyword')) !!}</td> --}}
                                <td class="title"  style="width: 20%">{{__('master_room.address.address_id')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->address_id ?? "--",request('keyword')) !!}</td>
                                <td class="title" style="width: 20%">{{__('master_room.address.email')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->email ?? "--",request('keyword')) !!}</td>
                            </tr>
                            {{-- <tr>
                                <td class="title" style="width: 20%">{{__('master_room.address.email')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->email ?? "--",request('keyword')) !!}</td>
                                <td class="title"  style="width: 20%">{{__('master_room.address.home_phone')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->home_phone ?? "--",request('keyword')) !!}</td>
                            </tr> --}}
                            <tr>
                                {{-- <td class="title" style="width: 20%">{{__('master_room.address.home_page')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->home_page ?? "--",request('keyword')) !!}</td> --}}
                                <td class="title"  style="width: 20%">{{__('master_room.address.mobile_phone')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->mobile_phone ?? "--",request('keyword')) !!}</td>
                                <td class="title" style="width: 20%">{{__('master_room.address.memo')}}</td>
                                <td style="width: 30%" >{!! highlightWords2($item->memo ?? "--",request('keyword')) !!}</td>

                            </tr>
                            <tr>
                                {{-- <td class="title" style="width: 20%">{{__('master_room.address.zip_code')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->zip_code ?? "--",request('keyword')) !!}</td> --}}
                                {{-- <td class="title"  style="width: 20%">{{__('master_room.address.company_phone')}}</td>
                                <td  style="width: 30%">{!! highlightWords2($item->company_phone ?? "--",request('keyword')) !!}</td> --}}
                            </tr>
                            <tr>
                                <td class="title" style="width: 20%">{{__('master_room.address.address')}}</td>
                                <td colspan="3" >{!! highlightWords2($item->address ?? "--",request('keyword')) !!}</td>
                            </tr>
                            {{-- <tr>
                                <td class="title" style="width: 20%">{{__('master_room.address.memo')}}</td>
                                <td colspan="3" >{!! highlightWords2($item->memo ?? "--",request('keyword')) !!}</td>
                            </tr> --}}
                            @if($item->member_id == auth()->guard('member')->user()->id)
                            <tr >
                                <td></td>
                                <td></td>
                                <td>
                                    <div style="display: flex">
                                        <div class="account__icon" ">
                                            <a href="{{route("masterRoomFE.address.edit", ['id'=> $item->id])}}" title="수정" target="_parent">
                                                <span class="icon-label">수정</span>
                                            </a>
                                        </div>
                                        <div class="account__icon">
                                            <a href="javascript:void(0)" class="deleteAddress" title="삭제" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete">
                                                <span class="icon-label">삭제</span>
                                            </a>
                                        </div>
                                    </div>

                                </td>
                                <td></td>
                            </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="4" style="text-align: center">{{__('master_room.no_contents')}}</td>
            </tr>
            @endif
            </tbody>
      </table>
      <!-- end of table -->

      <!-- filter -->
      <form action="{{route('masterRoom.address.search')}}" method="GET" id="form-search-2">
        <div class="filter filter--1 align-items-end">
          <div class="filter__item d-flex  align-items-end justify-content-md-center  mx-3">
            <div class="d-flex align-items-center mr-2">
              <span class="arrow">
                <svg width="6" height="15" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                </svg>
              </span>
              <input data-datepicker-start type="text" class="form-control form-control--date text-center startDate"
                id="startDate" name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
            </div>
            <span class="filter__connect">-</span>
            <div class="d-flex align-items-end ml-lg-2">
              <input data-datepicker-end type="text" class="form-control form-control--date text-center endDate"
                id="endDate" name="endDate" value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
              <span class="arrow arrow--next">
                <svg width="6" height="15" aria-hidden="true" class="icon">
                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_play_arrow"></use>
                </svg>
              </span>
            </div>
          </div>

          <div class="filter__item d-flex  align-items-end justify-content-md-center flex-grow-1">
            <select class="form-control form-control--select mx-3" name="type">
              <option value="1" @if(request('type')== 1) selected @endif>{{__('master_room.address.address')}}</option>
              {{-- <option value="2" @if(request('type')== 2) selected @endif>{{__('master_room.address.classification')}}</option> --}}
              <option value="3" @if(request('type')== 3) selected @endif>{{__('master_room.address.email')}}</option>
              {{-- <option value="4" @if(request('type')== 4) selected @endif>{{__('master_room.address.home_page')}}</option> --}}
              {{-- <option value="5" @if(request('type')== 5) selected @endif>{{__('master_room.address.zip_code')}}</option> --}}
              {{-- <option value="6" @if(request('type')== 6) selected @endif>{{__('master_room.address.home_phone')}}</option> --}}
              <option value="7" @if(request('type')== 7) selected @endif>{{__('master_room.address.mobile_phone')}}</option>
              {{-- <option value="8" @if(request('type')== 8) selected @endif>{{__('master_room.address.company_phone')}}</option> --}}
              <option value="9" @if(request('type')==9) selected @endif>{{__('master_room.address.memo')}}</option>
            </select>

            <div class="form-group form-group--search  flex-grow-1  mx-3">
                <a href="javascript:{}" onclick="document.getElementById('form-search-2').submit();">
                <span class="form-control__icon">
                    <svg width="14" height="14" aria-hidden="true" class="icon">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search"></use>
                    </svg>
                </span>
                </a>
              <input type="text" class="form-control" placeholder="{{__('master_room.enter_word')}}" name="keyword"
                value="{{ request('keyword') }}">
            </div>
          </div>
          @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('masterRoomFE.create') )
          <a href="{{route('masterRoomFE.address.create')}}" title="{{__('master_room.create_master_room')}}" class="filter__item btn btn-primary mx-3 btn-reset-padding">
                <span>{{__('master_room.write')}}</span>
            </a>
          @endif
        </div>
        <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"
          style="display:none">
      </form>
      <!-- end of filter -->
      {{-- --------------------------- --}}
      {!! Theme::partial('paging',['paging'=>$address->appends(request()->input()) ]) !!}
    </div>
  </div>
  </div>
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
               <input type=" text" id="hint" value="{{__('master_room.address.confirm_delete')}}" placeholder="&nbsp;" readonly>
             </label>
           </div>
         </div>
         <form action="{{route('masterRoomFE.address.delete')}}" method="post">
           @csrf
           <div class="button-group mb-2">
               <input type="hidden" value="0" id="id" name="id">
               <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('master_room.cancel')}}</button>
               <button type="submit" class="btn btn-primary">{{__('master_room.delete')}}</button>
           </div>
       </form>
       </div>
     </div>
   </div>
 </div>
 </div>
<script>
    $(function(){
        $('.deleteAddress').on('click',function(){
            $('#id').val( $(this).attr('data-value') )   ;
        })
    })
</script>
