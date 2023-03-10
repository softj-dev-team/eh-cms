<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
            <!-- category menu -->
            <div class="category-menu">
                <h4 class="category-menu__title">{{__('event.event_comments')}}</h4>
                <ul class="category-menu__links">
                    <li class="category-menu__item active">
                        <a href="{{route('eventsFE.cmt.list') }}" title="{{__('event.event_comments.list')}}">{{__('event.event_comments.list')}}</a>
                    </li>
                    <li class="category-menu__item ">
                        <a href="{{route('eventsFE.cmt.create')}}" title="{{__('event.event_comments.create_event_comments')}}">{{__('event.event_comments.create_event_comments')}}</a>
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

                <h3 class="title-main">{{__('event.event_comments.list')}}</h3>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="event-list">
                    <div class="content">
                        <div class="table-responsive" style="margin-top: 2.14286em;">
                            <table class="table">
                                <thead>
                                    <th style="text-align: left">{{__('event.event_comments.title')}}</th>
                                    <th style="text-align: center">{{__('event.event_comments.category')}}</th>
                                    <th style="text-align: center">{{__('event.event_comments.views')}}</th>
                                    <th style="text-align: center">{{__('event.event_comments.action')}}</th>
                                </thead>
                                <tbody>

                                    @if(count( $events ) > 0)

                                    @foreach ($events as $item)
                                    <tr>
                                        <td>
                                            @if($item->member_id == auth()->guard('member')->user()->id)
                                                <a href="{{route('eventsFE.cmt.edit',['id'=>$item->id])}}"
                                                    title="{{ strip_tags($item->title) }}">
                                                    <div class="garden-title">
                                                            {!! highlightWords2($item->title,'') !!}
                                                    </div>
                                                </a>
                                            @else
                                                <a href="#"
                                                    title="{{ strip_tags($item->title) }}">
                                                    <div class="garden-title">
                                                            {!! highlightWords2($item->title,'') !!}
                                                    </div>
                                                </a>
                                            @endif

                                        </td>
                                        <td style="padding-left: 10px;text-align: center" >
                                            {{$item->category_events->name}}
                                        </td>
                                        <td style="padding-left: 10px;text-align: center">
                                            {{$item->views ?? 0}}
                                        </td>
                                        <td style="padding-left: 10px;text-align: center">
                                            <div >
                                                <a  href="javascript:void(0)" class="deleteGarden" title="Remove Garden" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                                        <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center">{{__('event.event_comments.no_events')}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                    {!! Theme::partial('paging',['paging'=>$events ]) !!}
                    {{-- {!! Theme::partial('paging') !!} --}}
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
                <input type=" text" id="hint" value="{{__('event.confirm_delete')}}" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('eventsFE.cmt.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="0" id="garden_id" name="id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('event.cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{__('event.delete')}}</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
 $(document.body).on("click",'.deleteGarden', function(e){
                let $this = $(this);
                $('#garden_id').val($this.attr('data-value'))
        });
</script>
