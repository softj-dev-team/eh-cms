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
                {!! Theme::partial('garden.elements.search',['categories'=> 'egarden','route'=>"room.search"]) !!}
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
                                    <a href="{{route('egardenFE.room.detail',['id'=>$item->id])}}"
                                        title="{{$item->title}}">
                                        <img src="{{ get_image_url($item->images, 'event-thumb')  }}" alt="" />
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
                                        <a href="{{route('egardenFE.room.detail',['id'=>$item->id])}}">
                                            @if(request('type') > 0 )
                                            {!! highlightWords2($item->description ?? "No have room",request('keyword'))
                                            !!}
                                            @else
                                            {!! highlightWords2($item->name ?? "No have room" ,request('keyword')) !!}

                                            @endif
                                        </a>
                                    </h4>
                                </div>
                                <div class="item__addon d-flex justify-content-between mb-10 pr-1">
                                    <div class="pr-2">
                                        <span class="item__text-gray">{{__('egarden.date')}} |</span>
                                        @if( getStatusDateByDate($item->created_at) == "Today" ) <span
                                            class="icon-label">N</span> @endif
                                        {{getStatusDateByDate($item->created_at)}}
                                    </div>
                                    <div class="pl-2">
                                        <span class="item__text-gray">{{__('egarden.members')}} |</span>
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
                <div id="form-search-2">
                  <div class="filter filter--1 align-items-end">
                    <button class="filter__item btn btn-secondary"
                        onClick="window.location.href='{{route('egardenFE.room.list')}}'">
                        <svg width="18" height="20.781" aria-hidden="true">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_menu-list"></use>
                        </svg>
                        <span>{{__('egarden.last_list')}}</span>
                    </button>
                    @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('egardenFE.room.create') )
                    <a href="{{route('egardenFE.room.myroom.list')}}" title="{{__('egarden.room.create_new_room')}}"
                        class="filter__item btn btn-primary mx-3 btn-reset-padding">
                        <span style="white-space: nowrap;">{{__('egarden.write')}}</span>
                    </a>
                    @endif
                </div>
                {!! Theme::partial('paging',['paging'=>$room->appends(request()->input()) ]) !!}
            </div>

        </div>

    </div>
</main>

{{-- popup show image --}}
{!! Theme::partial('popup.showImage',['slides'=> $slides]) !!};
{{--  popup show image --}}

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
    });
</script>
