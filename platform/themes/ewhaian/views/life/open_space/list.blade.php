<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
            <!-- category menu -->
            <div class="category-menu">
                <h4 class="category-menu__title">{{__('life')}}</h4>
                <ul class="category-menu__links">
                    <li class="category-menu__item active">
                        <a href="{{route('openSpaceFE.list') }}" title="{{__('life.open_space.open_space_list')}}">{{__('life.open_space.open_space_list')}}</a>
                    </li>

                    <li class="category-menu__item">
                        <a href="{{route('openSpaceFE.create')}}" title="{{__('life.open_space.create_open_space')}}">{{__('life.open_space.create_open_space')}}</a>
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

                <h3 class="title-main">{{__('life.open_space.open_space_list')}}</h3>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="event-list">
                    <div class="content">
                        <div class=" table-responsive" style="margin-top: 2.14286em;">
                            <table class="table table--content-middle">
                                <thead>
                                    <th style="text-align: left;padding-left: 0px;">{{__('life.open_space.title')}}</th>
                                    <th style="text-align: center;">{{__('life.open_space.lookup')}}</th>
                                    <th style="text-align: center;">{{__('life.open_space.action')}}</th>
                                </thead>
                                <tbody>

                                    @if(count( $openSpace ) > 0)

                                    @foreach ($openSpace as $item)
                                    <tr>
                                        <td>
                                            @if($item->member_id == auth()->guard('member')->user()->id)
                                                <a href="{{route('openSpaceFE.edit',['id'=>$item->id])}}"
                                                    title="{{ strip_tags($item->title) }}">
                                                    <div class="garden-title">
                                                            {!! highlightWords2($item->title,'') !!}{{' ('.$item->comments->count().')' }}
                                                            @if(getNew($item->created_at,1)) <span class="icon-label">N</span>@endif
                                                    </div>
                                                </a>
                                            @else
                                                <a href="#"
                                                    title="{{ strip_tags($item->title) }}">
                                                    <div class="garden-title">
                                                            {!! highlightWords2($item->title,'') !!}{{' ('.$item->comments->count().')' }}
                                                            @if(getNew($item->created_at,1)) <span class="icon-label">N</span>@endif
                                                    </div>
                                                </a>
                                            @endif

                                        </td>
                                        <td style="text-align: center;">
                                            {{$item->views ?? 0}}
                                        </td>
                                        <td style="text-align: center;">
                                            <div >
                                                <a  href="javascript:void(0)" class="deleteGarden" title="Remove Open Space" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                                        <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center">{{__('life.open_space.no_open_space')}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>

                    {!! Theme::partial('paging',['paging'=>$openSpace ]) !!}
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
                <input type=" text" id="hint" value="Do you really want to delete this Open Space?" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('openSpaceFE.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="0" id="garden_id" name="id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button>
                <button type="submit" class="btn btn-primary">Delete</button>
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
