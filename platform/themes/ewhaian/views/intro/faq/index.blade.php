<style>
  .answer{
    border: none!important;
  }
  .table td {
    border-top: none!important;
  }
    .popular-search .popular__list  .popular__item .active{
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
    .popular__item {
        cursor: pointer;
    }
    .popular-search a.active,
    .popular-search a:hover {
        color: #ffffff !important;
        background-color: #EC1469 !important;
    }
    .events_c{cursor:pointer;}
    @media (max-width: 991px) {
      .table--custom tbody tr {
        display: table-row;
    }
    .table tbody td {
        border-top: none;
        padding: 17px 3px;
    }
    }
    .events_c{cursor:pointer;
      border-top: 1px solid #dee2e6;
      border-bottom: 1px solid #dee2e6;
    }
</style>
<main id="main-content" data-view="event-comments" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__control">
        <!-- intro menu -->
      {!! Theme::partial('intro.menu',['faq'=>1,'categories'=>$categories]) !!}
      <!-- end of intro menu -->
      </div>
      <div class="sidebar-template__content">
        <div class="event-comments">
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
          <div class="heading">
              <div class="heading__title">
                  {{__('eh-introduction.faqs')}}
              </div>
          </div>

          <div class="tab-content">
            <div class="popular-search">
                <ul class="popular__list ">
                    <li class="popular__item">
                        <a class="alert bg-white  @if(request('categories_id') == '') active @endif all" href="javascript:{}" onclick="submitSearch('')" title="전체" data-value="" style="border: 1px solid #EC1469 ;">전체</a>
                    </li>

                    <li class="popular__item">
                        <a class="alert bg-purple @if(request('categories_id') == '1') active @endif" title="회원" data-value="1" onclick="submitSearch(1)" id="children3">회원</a>
                    </li>

                    <li class="popular__item">
                        <a class="alert  bg-green @if(request('categories_id') == '3') active @endif" href="javascript:{}" title="운영진" data-value="3" onclick="submitSearch(3)" id="children4">운영진</a>
                    </li>
                    <li class="popular__item">
                        <a class="alert  bg-yellow-1 @if(request('categories_id') == '2') active @endif  " href="javascript:{}" title="이화이언 인증" data-value="2" onclick="submitSearch(2)" id="children5">이화이언 인증</a>
                    </li>
                </ul>

                <script>
                </script>
            </div>
          </div>
        <!-- end tab -->

          <form action="{{route('eh_introduction.faq.search')}}" method="GET" id="form-search-1">
            <div class="filter align-items-center">
              <div class="filter__item filter__title mr-3">{{__('eh-introduction.faqs.search')}}</div>
              <!-- <div class="filter__item d-flex align-items-md-center justify-content-md-center  mr-3">
                <div class="d-flex align-items-center">
                                    <span class="arrow">
                                        <svg width="6" height="15" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon_play_arrow">
                                            </use>
                                        </svg>
                                    </span>
                  <input data-datepicker-start type="text"
                         class="form-control form-control--date startDate" id="startDate"
                         name="startDate" value="{{request('startDate') ?: getToDate() }}" autocomplete="off">
                </div>
                <span class="filter__connect">-</span>
                <div class="d-flex align-items-md-center">
                  <input data-datepicker-end type="text"
                         class="form-control form-control--date endDate" id="endDate" name="endDate"
                         value="{{request('endDate') ?: getToDate() }}" autocomplete="off">
                  <span class="arrow arrow--next">
                                        <svg width="6" height="15" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 xlink:href="#icon_play_arrow">
                                            </use>
                                        </svg>
                                    </span>
                </div>
              </div> -->

              <div class="filter__item d-flex align-items-center justify-content-center flex-grow-1">
                <select class="form-control form-control--select mx-3" name="type"
                        value="{{ request('type') }}">
                  <option value="0"
                          @if(request('type')==0) selected @endif>{{__('eh-introduction.faqs.question')}}</option>
                  <option value="1"
                          @if(request('type')==1) selected @endif>{{__('eh-introduction.faqs.anwser')}}</option>
                </select>

                <input name="categories_id" id="categories_id" value="" type="hidden"/>

                <div class="form-group form-group--search  flex-grow-1  mx-3">
                  <a href="javascript:{}" class="submitSearch">
                                    <span class="form-control__icon">
                                        <svg width="14" height="14" aria-hidden="true" class="icon">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_search">
                                            </use>
                                        </svg>
                                    </span>
                  </a>
                  <script>
                    function submitSearch(val){
                          $('#categories_id').val(val);
                          $('#form-search-1').submit();
                    }

                    $(document).ready(function () {
                        $('.submitSearch').click(function () {
                           var activeCatID = $('.popular__item .alert.active').attr('data-value');
                            submitSearch(activeCatID);
                        });
                    });
                      // document.getElementById('form-search-1').submit();

                  </script>
                  <input type="text" class="form-control" placeholder="{{__('search.title_or_content')}}"
                         name="keyword"
                         value="{{ request('keyword') }}">
                </div>
              </div>
            </div>
{{--            <input type="submit" class="filter__item btn btn-primary mx-3 btn-reset-padding" value="Search"--}}
{{--                   style="display:none">--}}
          </form>
          <!-- end of filter -->
          @if (session('err'))
            <div class="alert alert-danger" style="display: block">
              {{ session('err') }}
            </div>
          @endif
        <!-- table -->
          <table class="table table--custom table--event-comments">
            <thead>
            <tr>
              <th style="text-align: center;">{{__('eh-introduction.faqs.no')}}</th>
              <th style="text-align: center;">{{__('eh-introduction.faqs.category')}}</th>
              <th style="text-align: center;">{{__('eh-introduction.faqs.question')}}</th>
              <th style="text-align: center;"></th>
            </tr>
            </thead>
            <tbody>
            @if (count($faq) > 0)
              @foreach ($faq as $key => $item)
                <tr data-toggle="collapse" data-target="#demo{{$key}}" class="events_c">
                  <td style="text-align: center;">{{$item->id}}</td>
                  <td data-content="Category" style="text-align: center;">{{$item->categories->name}}</td>
                  <td title="{{$item->question}}">
                    @if (request('type') == 0 ) {!! highlightWords2($item->question,request('keyword'))
                                    !!}
                    @else
                      {!! highlightWords2($item->question,null) !!}
                    @endif
                  </td>
                  <td data-content="View more detail">
                    {{-- <button type="button" class="table__btn events_comments">
                      <svg width="16" height="7" aria-hidden=" true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow">
                        </use>
                      </svg>
                    </button> --}}
                    <i class="fas fa-angle-down table__btn events_comments" data-toggle="collapse" data-target="#demo{{$key}}"></i>
                  </td>
                </tr>
                {{-- accordian-body collapse --}}
                <tr style="padding: 0 !important;" class="answer">
                  <td colspan="4" class="single comments item" style="padding: 0 !important; width: 100%;">
                    <div class="event-details single accordian-body collapse" id="demo{{$key}}"
                         style="margin-bottom: 30px;">

                      <!-- comments -->
                      <div class="comments" style="margin-top:0px;">
                        <div class="comments__list">
                          @if(isset($item->answer)>0)


                            <div class="item">
                              {!! $item->answer !!}
                            </div>

                          @else
                            <div class="item">{{__('eh-introduction.faqs.no_comments')}}</div>
                          @endif
                        </div>
                      </div>

                      <!-- end of comments -->
                    </div>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4" style="text-align: center">{{__('eh-introduction.faqs.no_have_faqs')}}</td>
              </tr>
            @endif

            </tbody>
          </table>
          <!-- end of table -->

          {!! Theme::partial('paging',['paging'=>$faq->appends(request()->input()) ]) !!}
        </div>
      </div>
    </div>
  </div>
</main>
