
<style>
    .table td, .table th {
        padding: .75rem; */
        vertical-align: none;
        border-top: none;
        text-alid
    }
    .table th {
    text-align: left;
    }
    .table tr:last-child td {
    border-bottom: none;
}
</style>
<main id="main-flare-detail" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- category menu -->
                {!! Theme::partial('campus.menu',['active'=>"studyRoom"]) !!}
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
                      <li><a href="{{route('campus.evaluation_comments_major')}}" title="{{__('campus')}}">{{__('campus')}}</a></li>
                      <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                      </li>
                    <li class="active">{!! $crumb['label'] !!}</li>
                    @endif
                    @endforeach
                </ul>

              <div class="event-details single">
                <div class="single__head">
                  <h3 class="single__title title-main">{{$notices->name}}</h3>
                  <div class="single__info" style="white-space: nowrap;">
                    <div class="single__eye">
                      <svg width="16" height="10" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_eye"></use>
                      </svg>
                      {{$notices->lookup  ?? 0}}
                    </div>
                  </div>
                </div>

                <div class="editor">
                  {!!$notices->notices !!}
                </div>
                {!! Theme::partial('campus.study_room_index_sub',[
                      'studyRoom' => $subList['studyRoom'],
                      'categories' => $subList['categories'],
                      'style' => $subList['style'],
                      'canCreate' => $subList['canCreate']
                    ]) !!}
              </div>

            </div>
        </div>
    </div>
</main>
