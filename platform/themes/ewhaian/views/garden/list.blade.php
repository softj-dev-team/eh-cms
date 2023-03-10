<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- campus menu -->
                <div class="sidebar-template__control">
                        <div class="nav nav-left">
                          <ul class="nav__list">
                            <p class="nav__title">Campus</p>
                            <li class="nav__item">
                              <a  class="active" href="javascript:{}" title="Study Room List">Study Room List</a>
                            </li>
                            <li class="nav__item">
                              <a href="{{route('gardenFE.create')}}" title="Create Part Time Jobs">Create Study Room</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                <!-- end of campus menu -->
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

                <h3 class="title-main">Study Room List</h3>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="event-list">
                    <div class="row">


                        @foreach ($garden as $key=>$item)
                        @if($key > 0 )
                          @if($item->status == 'publish')
                        <div class="col-lg-4 col-md-6">
                            <div class="item">
                                <div class="item__image">
                                    <div
                                        style="background: url('{{ geFirsttImageInArray([$item->images], 'event-thumb')  }}') no-repeat center; background-size:auto; height: 180px;">
                                    </div>

                                    {{-- <img src="{{ $item->banner }}" alt="" /> --}}
                                    <div class="item__info">
                                        <div class="item__date">
                                            <svg width="15" height="17" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_date">
                                                </use>
                                            </svg>
                                            <?php
                                                $start = date("Y-m-d",strtotime( $item->created_at) );

                                                if (strtotime(  date("Y-m-d", strtotime('today'))  ) == strtotime( $start)) {
                                                    $item->start = "Today";
                                                } else if (strtotime(  date("Y-m-d", strtotime('tomorrow'))  ) == strtotime($start) )  {
                                                    $item->start = "Tomorrow";
                                                }else if(strtotime(  date("Y-m-d", strtotime('yesterday'))  ) == strtotime($start)){
                                                    $item->start = "Yesterday";
                                                }else{
                                                    $item->start = date('d M | H:i a', strtotime($item->start)) ;
                                                }

                                                ?>
                                            {{  $item->start }}
                                        </div>
                                        <div class="item__eye">
                                            <svg width="16" height="10" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye">
                                                </use>
                                            </svg>
                                            {{ $item->lookup}}
                                        </div>
                                    </div>
                                </div>

                                <div class="item__caption">
                                    <h4 class="item__title">
                                        <a href="{{route('gardenFE.edit',['id'=>$item->id])}}"
                                            title="{{ strip_tags($item->title) }}">
                                            <div
                                                style="  white-space: nowrap; overflow: hidden; text-overflow:ellipsis;">
                                                {{ strip_tags($item->title)}} </div>
                                        </a>
                                    </h4>

                                    <div class="item__desc"
                                        style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box; -webkit-line-clamp: 2; /* number of lines to show */-webkit-box-orient: vertical; min-height: 42px;">
                                        {!! strip_tags( $item->detail ) !!}
                                    </div>

                                    <div class="item__datetime">
                                        <span class="item__icon">
                                            <svg width="10" height="10" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xlink:href="#icon_datetime"></use>
                                            </svg>
                                        </span>
                                        <span class="item__datetime__detail">{{ $item->start}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                          @endif
                        @else
                          <div class="col-lg-4 col-md-6">
                            <div class="item__desc">
                              <br>
                              <h3>No Study Room</h3>
                            </div>
                          </div>
                        @endif
                        @endforeach

                    </div>

                    {!! Theme::partial('paging',['paging'=>$garden ]) !!}
                    {{-- {!! Theme::partial('paging') !!} --}}
                </div>
            </div>
        </div>
    </div>
</main>
