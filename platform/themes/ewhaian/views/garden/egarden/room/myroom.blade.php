<style>
    .bg-green {
        background-color: rgb(89, 198, 177);
        color: #ffffff;
    }

    .bg-main:hover,
    .bg-main:focus {
        color: #FFFFFF;
    }
    .item__new {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 0.85714em;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        padding: 0.83333em 1.25em;
    }
    .item__rectangle {
        position: absolute;
        top: -59px;
        left: -60px;
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
    .icon-label {
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

    .item__image {
        overflow: hidden;
    }
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                    <!-- category menu -->
                    <div class="category-menu">
                        <h4 class="category-menu__title">{{__('egarden.room')}}</h4>
                        <ul class="category-menu__links">
                        <li class="category-menu__item active">
                            <a href="{{route('egardenFE.room.myroom.list') }}" title="{{__('egarden.room.my_room')}}">{{__('egarden.room.my_room')}}</a>
                        </li>
                        <li class="category-menu__item">
                            <a href="{{route('egardenFE.room.create')}}" title="{{__('egarden.room.create_new_room')}}">{{__('egarden.room.create_new_room')}}</a>
                        </li>
                        </ul>
                    </div>
                    <!-- end of category menu -->
            </div>
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="{{route('egardenFE.room.list')}}" title="Event">
                        {{__('egarden.room')}}
                        </a>
                    </li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>{{__('egarden.room.my_room')}}</li>
                </ul>
                <div class="heading">
                    <div class="heading__title">
                        {{__('egarden.room')}}
                    </div>
                </div>
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="content event-list">
                    <div class="row mb-10" @if(count($room) <=0 ) style="justify-content: center;" @endif>
                        @if(count($room) > 0 )
                        @foreach ($room as $item)
                        <div class="col-lg-4 col-md-6">
                            <div class="item item--custom">
                                <div class="item__image mb-10">
                                    @if(getNew($item->updated_at))
                                        <div class="item__new">
                                            <div class="item__rectangle" style="z-index: 1">

                                            </div>
                                            <div class="item__eye" style="z-index: 2;margin-right: -9px;">
                                            <span class="icon-label">N</span>
                                                <div style="margin-left: -4px;">New</div>
                                            </div>
                                        </div>
                                    @endif
                                    <a href="{{route('egardenFE.room.edit',['id'=>$item->id])}}" title="{{ strip_tags($item->title) }}">
                                        <img src="{{ get_image_url($item->images, 'event-thumb')  }}" alt="" />
                                    </a>
                                    <a  href="javascript:void(0)" class="btn_remove_image deleteRoom" title="{{__('egarden.room.remove_room')}}" data-toggle="modal" data-value="{{$item->id}}" data-target="#confirmDelete" >
                                            <i class="fa fa-times"></i>
                                    </a>

                                </div>
                                <div class="item__tag d-flex align-items-center justify-content-between mb-10 pr-1">
                                    <div style="margin-left: -10px;">{!!
                                        Theme::partial('garden.elements.joinRoom',['status'=>$item->statusMember(auth()->guard('member')->user()->id),'id'=>$item->id,'my_room'=> ($item->member_id == auth()->guard('member')->user()->id ?  true : false )])
                                        !!}</div>
                                    <div class="text-right">
                                        @if($item->member_id ==null )
                                        <span class="icon-label disable">
                                            <svg width="2.359" height="15" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xlink:href="#icon_exclamation"></use>
                                            </svg>
                                        </span>
                                        @else
                                        <span class="icon-label pink">
                                            <svg width="12" height="12" aria-hidden="true" class="icon">
                                                <use xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xlink:href="#icon_check"></use>
                                            </svg>
                                        </span>
                                        @endif
                                        {{$item->author->nickname ?? 'Admin'}}
                                    </div>
                                </div>
                                <div class="item__caption mb-10 pr-1">
                                    <h4 class="item__title">
                                        <a href="{{route('egardenFE.room.edit',['id'=>$item->id])}}">
                                            @if(request('type') > 0 )
                                            {!! highlightWords2($item->detail ?? "No have details",request('keyword'))
                                            !!}
                                            @else
                                            {!! highlightWords2($item->name,request('keyword')) !!}

                                            @endif
                                        </a>
                                    </h4>
                                </div>
                                <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                                    <div class="pr-2">
                                        <span class="item__text-gray">{{__('egarden.date')}} |</span>
                                        @if( getStatusDateByDate($item->created_at) == "Today" ) <span
                                            class="icon-label">N</span> @endif
                                        {{getStatusDateByDate($item->published)}}
                                    </div>
                                    <div class="pl-2">
                                        <a href="{{route('egardenFE.room.approved',['id'=>$item->id])}}" >{{__('egarden.members')}} |</a >
                                        {{$item->member_count ?? 0}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div>{{__('egarden.no_room')}}</div>
                        @endif


                    </div>
                </div>
                {!! Theme::partial('paging',['paging'=>$room->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>

<script>
    $( document ).ready(function() {
        $(document.body).on("click",'.syn', function(e){
            e.preventDefault();
            var $this = $(this);
            var idRoom = $this.attr('idRoom');

            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url:'{{route('egardenFE.ajaxJoinRoom')}}',
                data:{
                        _token: "{{ csrf_token() }}",
                        'id' : idRoom
                },
                success: function( data ) {
                    switch (data.status) {
                        case 'pending':
                            $this.replaceWith(`<a class='alert bg-green-1 syn' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}">{{__('egarden.room.pending')}}</a>`);
                            break;
                        case 'draft':
                            $this.replaceWith(`<a class='alert bg-main syn' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}" >{{__('egarden.room.join_us')}}</a>`);
                            break;
                        default:
                            break;
                    }
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

        $(document.body).on("click",'.deleteRoom', function(e){
            let $this = $(this);
            $('#room_id').val($this.attr('data-value'))
        });
    });
</script>

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
                <input type=" text" id="hint" value="{{__('egarden.room.confirm_delete')}}" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('egardenFE.room.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="" id="room_id" name="room_id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('egarden.room.cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{__('egarden.room.delete')}}</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
