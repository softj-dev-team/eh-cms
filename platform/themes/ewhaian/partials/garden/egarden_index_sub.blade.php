<style>
  body {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
  }

  .bg-green {
    background-color: rgb(89, 198, 177);
    color: #ffffff;
  }

  .bg-main:hover,
  .bg-main:focus {
    color: #FFFFFF;
  }

  .icon-label {
    margin-right: 0.71429em;
  }

  .item__new {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
  }

  .item__rectangle {
    position: absolute;
    top: -59px;
    right: -60px;
    width: 118px;
    height: 118px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
  }

  .item__new .icon-label {
    width: 2em;
    height: 2em;
    display: inline-block;
    border: 1px solid #FFFFFF;
    border-radius: 50%;
    color: #FFFFFF;
    text-align: center;
    line-height: 2em;
    font-weight: 700;
    font-size: 0.71429em;
  }

  .item--custom {
    overflow: hidden;
    position: relative;
  }

  .select2-container--default .select2-results > .select2-results__options {
    max-height: 800px;
  }

  .icon--gallery {
    width: 1.42857em;
    height: 1.42857em;
    display: inline-block;
    border: 1px solid #EC1469;
    color: #EC1469;
    text-align: center;
    line-height: 1.28571em;
  }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
  <div class="container">
    <div class="sidebar-template">
      <div class="sidebar-template__content" style="width:100%">
        <div>
          <div style="overflow-x: auto;">
            <table class="table table--content-middle" style="width: 880px">
              <thead>
              <tr>
                <th style="text-align: center;">룸</th>
                <th style="text-align: center;">{{__('life.open_space.title')}}</th>
                <th style="text-align: center;">{{__('life.open_space.author')}}</th>
                <th style="text-align: center;">{{__('life.open_space.date')}}</th>
                <th style="text-align: center;">{{__('life.open_space.lookup')}}</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($egarden as $item)
                <tr style="opacity: 100%;">
                  <td style="text-align: center;">
                    <span class="alert bg-purple" title="공동구매">{{$item->room->name}}</span>
                  </td>
                  <td>
                    <a href="{{route('egardenFE.details',['id'=>$item->id])}}"
                       title="{{ strip_tags($item->title) }}{{' ('.$item->comments->count().')' }}">
                      <div class="garden-title">
                        @if(request('type') > 0 )
                          {!! highlightWords2($item->detail ?? "No have details",request('keyword'),40)
                          !!}{{' ('.$item->comments->count().')' }}
                        @else
                          {!! highlightWords2($item->title,request('keyword'),40) !!}{{' ('.$item->comments->count().')' }}

                        @endif
                        @if(!is_null($item->banner))
                          <span class="icon icon--gallery">
                                                    <svg width="15" height="13" aria-hidden="true">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xlink:href="#icon_gallery"></use>
                                                    </svg>
                                                </span>
                        @endif
                      </div>
                    </a>
                  </td>
                  <td>
                    <div style="display: flex">
                      @if($item->member_id ==null || @$item->member->nickname =="Anonymous" )
                        {!! getStatusWriter('real_name_certification') !!}
                      @else
                        {!! getStatusWriter(@$item->member->certification)  !!}
                      @endif
                      {{ @$item->member->nickname ?? 'admin' }}
                    </div>
                  </td>
                  <td style="text-align: center;">
                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->published ?? today())->format('Y-m-d') }}
                  </td>
                  <td style="text-align: center;">
                    {{$item->lookup ?? 0}}
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          {!! Theme::partial('paging',['paging'=>$egarden->appends(request()->input()) ]) !!}
        </div>

      </div>

    </div>
</main>
