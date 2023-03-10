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
.table-list {
    border: 1px solid #333333 !important;
    border-bottom: 1px solid #333333 !important;
    border-radius: 15px
}
.table-list td {
    border-top : none;
    border-bottom: 1px solid #333333;
}
.table-list tr:last-child td {
    border-bottom: 1px solid #333333;
}
.table--content-middle .alert {
    max-width:unset;
}
.checkbox-name {
    position: absolute;
    top: 35%;
    left: 0;
    height: 15px;
    width: 15px;
    background-color: white;
    border: 1px solid #EC1469;
}
.important {
    color: #EC1469;
}
</style>
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
            <div class="sidebar-template__content">
                <ul class="breadcrumb">
                    <li><a href="{{route('egardenFE.home')}}" title="{{__('egarden')}}">{{__('egarden')}}</a></li>
                    <li>
                        <svg width="4" height="6" aria-hidden="true" class="icon">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                        </svg>
                    </li>
                    <li>나의 E- 화원 목록</li>
                </ul>
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
                <div class="d-flex align-items-center popular-search">
                    <span >나의 E- 화원 목록</span>
                </div>

                @if (session('permission'))
                    <div class="alert alert-danger" style="width: 100%">
                        {{ session('permission') }}
                    </div>
                @endif
                {!! Theme::partial('garden.elements.order',[
                    'room' =>null,
                    'canSearch' => false,
                    'canOrder' => true,
                    'route' => route('egardenFE.room.list'),
                    'labelOrderby1' => '이름순',
                    'labelOrderby2' => '즐겨찾기순',
                    'labelOrderby3' => '최신글순'
                    ]) !!}
                <div style="overflow-x: auto;">
                    <table class="table table--content-middle table-list " style="width: 880px">
                        <tbody>
                            @foreach ($room as $item)
                            <tr style="opacity: 100%;">
                                <td>
                                    <div class="form-check form-check-inline" >
                                        <label class="form-check-label" for="checkbox{{$item->id}}" style="cursor: auto;">
                                            <h6 style="cursor: auto;">  {{$item->name}}</h6>
                                            <input type="checkbox" disabled='true' id="{{$item->id}}" name="nope" style="cursor: auto;">
                                            <span class="checkbox-name"></span>
                                        </label>
                                    </div>
                                </td>
                                <td >
                                    {{$item->description}}
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex">
                                        <a href="{{route('egardenFE.create',['id' => $item->id])}}" class="alert bg-white-1" >글쓰기</a>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex">
                                      @if($item->id)
                                        <a href="javascript:void(0)" class="alert bg-white-1 confirmImportant {{checkImportan($item->id)}} " data-id ="{{$item->id}}" >주목글보기</a>
                                      @endif
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    {!!
                                        Theme::partial('garden.elements.joinRoom',['status'=>$item->statusMember(auth()->guard('member')->user()->id),'id'=>$item->id,'my_room'=> ($item->member_id == auth()->guard('member')->user()->id ?  true : false )])
                                    !!}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="filter filter--1 align-items-end">
                    <div class="filter__item d-flex  align-items-end justify-content-md-center  mx-3">

                    </div>
                    @if(!is_null( auth()->guard('member')->user() ) && auth()->guard('member')->user()->hasPermission('egardenFE.room.create') )
                    <a href="{{route('egardenFE.room.create')}}" title="{{__('egarden.room.create_new_room')}}"
                        class="filter__item btn btn-primary mx-3 btn-reset-padding">
                        <span style="white-space: nowrap;">E-화원 만들기</span>
                    </a>
                    @endif
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
                       case 'publish':
                           $this.replaceWith(`<a class='alert bg-green exitRoom' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}">{{__('egarden.room.approved')}}</a>`);
                           $('.syn[idRoom="'+idRoom+'"]').replaceWith(`<a class='alert bg-green exitRoom' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}">{{__('egarden.room.approved')}}</a>`);
                           break;
                       case 'pending':
                           $this.replaceWith(`<a class='alert bg-green-1 syn' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}">{{__('egarden.room.pending')}}</a>`);
                           break;
                        case 'draft':
                           $this.replaceWith(`<a class='alert bg-main syn' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}" >{{__('egarden.room.join_us')}}</a>`);
                           $('.exitRoom[idRoom="'+idRoom+'"]').replaceWith(`<a class='alert bg-green exitRoom' href='javascript:void(0)' style='display: block;margin-left: 10px;' idRoom="${idRoom}">{{__('egarden.room.approved')}}</a>`);

                           break;
                       default:
                           break;
                   }
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

        $(document.body).on('click', '.confirmImportant', function() {
            let $this = $(this);
            let $id = $this.attr('data-id');

            if($this.data('isRunAjax')==true){return;}
            $this.css('pointer-events', 'none').data('isRunAjax', true);
            $.ajax({
                type:'POST',
                url:'{{route('egardenFE.room.important.update')}}',
                data:{
                        _token: "{{ csrf_token() }}",
                        'id' : parseInt($id)
                },
                success: function( data ) {
                    if(data.important === 1) {
                        $this.addClass('important');
                    } else {
                        $this.removeClass('important');
                    }
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        })
    });
</script>
