<style>
    .bg-green {
        background-color: rgb(89, 198, 177);
        color: #ffffff;
    }

    .bg-main:hover,
    .bg-main:focus {
        color: #FFFFFF;
    }

    .icon-label{
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

    .item--custom{
        overflow: hidden;
        position: relative;
    }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="{{route('egardenFE.room.list')}}" title="{{__('egarden')}}">{{__('egarden')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('egarden.room')}}</li>
                </ul>
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                        {{__('egarden.room')}}
                    </div>
                    <div class="heading__description" style="word-break: break-word;margin-left: 20px;">
                        {!!$description->description ?? __('egarden.no_have_description')!!}
                    </div>
                </div>
                @if($countAccess >0 )
                <div class="d-flex align-items-center popular-search">
                    <span class="text-bold mr-3">{{__('garden.current_member')}} : </span>
                    <ul class="popular__list">
                        <li >
                            <!-- <div class="popular__item alert bg-white-1">{{$countAccess}} {{__('garden.people')}}</div> -->
                       <div class="popular__item alert bg-white-1">{{$countAccess}}</div>
                        </li>
                    </ul>
                </div>
                @endif

                <div class="notice-alert">
                    <div class="notice-alert__title" style="white-space: nowrap;">{{__('egarden.notice')}}</div>
                    @if($notices->count() > 0 )
                        <div class="notice-alert__description">
                            @foreach ($notices as $item)
                                <div>
                                <a href="{{$item->link}}">{!!$item->notices !!}</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="notice-alert__description">
                            {!!__('egarden.no_have_notices')!!}
                        </div>
                    @endif
                </div>
                @if (session('permission'))
                    <div class="alert alert-danger" style="width: 100%">
                        {{ session('permission') }}
                    </div>
                @endif
                <div class="content">
                    <div class=" table-responsive">
                        <table class="table table--content-middle">
                            <thead>
                                <th style="text-align: center;">{{__('egarden.id')}}</th>
                                <th style="text-align: center;">name</th>
                                <th style="text-align: center;">{{__('egarden.action')}}</th>
                            </thead>
                            <tbody>
                                @if(count( $categoriesRoom ) )

                                @foreach ($categoriesRoom as $item)
                                <tr>
                                    <td class="text-center">
                                        {{$item->id}}
                                    </td>
                                    <td style="width: 13%">
                                        <a href="{{route('egardenFE.room.categories.edit',['idRoom' => $idRoom, 'id' => $item->id])}}" title="{{$item->name}}">
                                            <div > {!! Theme::partial('garden.elements.showCategories',['item'=>$item]) !!} </div>
                                        </a>

                                    </td>
                                    <td style="text-align: center;">
                                        <div class="item__image mb-10">
                                            <a  href="javascript:void(0)" class="deleteEgarden" title="{{__('egarden.remove_egarden')}}" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                                    <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center">{{__('egarden.no_item')}}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div style="position: relative;">
                        <div style=" position: absolute;  right: 0;">
                            <a href="{{route('egardenFE.room.categories.create',['id'=>$idRoom])}}" title="{{__('egarden.create_new_egarden')}}"
                                class="filter__item btn btn-primary mx-3 btn-reset-padding">
                                <span style="white-space: nowrap;">{{__('egarden.write')}}</span>
                            </a>
                        </div>
                    </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$categoriesRoom->appends(request()->input()) ]) !!}
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
                <input type=" text" id="hint" value="Do you really want to delete this category?" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('egardenFE.room.categories.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="" id="categories_id" name="categories_id">
                <input type="hidden" value="{{$idRoom}}" name="idRoom">
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
$( document ).ready(function() {
    $(document.body).on("click",'.deleteEgarden', function(e){
            let $this = $(this);
            $('#categories_id').val($this.attr('data-value'))
    });
});

</script>
