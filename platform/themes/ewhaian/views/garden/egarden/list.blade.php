<style>
    .syn {
        width: 176px;
        margin: auto !important;
        height: 50px;
        font-size: 20px;
        display: flex !important;
        justify-content: center;
        align-items: center;
    }
    @media (max-width: 768px) {
        .date {
            padding: 0 18px;
            white-space: nowrap;
        }

        .garden-title {
            padding: 0 20px;
            white-space: nowrap;
        }
        .syn {
            margin: 0;
        }
    }
</style>

@if ($room->statusMember(auth()->guard('member')->user()->id) == 'publish' || $room->member_id == auth()->guard('member')->user()->id )
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
                <div class="sidebar-template__control">
                        <!-- create menu -->
                        <div class="nav nav-left">
                              <ul class="nav__list">
                                <p class="nav__title">
                                  <svg width="40" height="18" aria-hidden="true">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                                  </svg>
                                </p>
                                <li class="nav__item">
                                  <a class="active"  href="{{route('egardenFE.list',['id'=>$room->id])}}" title="{{__('egarden.egarden_list')}}">{{__('egarden.egarden_list')}}</a>
                                </li>
                                <li class="nav__item">
                                  <a href="{{route('egardenFE.create',['id'=>$room->id])}}" title="{{__('egarden.create_new_egarden')}}">{{__('egarden.create_new_egarden')}}</a>
                                </li>
                              </ul>
                            </div>
                        <!-- end of create menu -->
                      </div>
                <div class="sidebar-template__content">
                    <div class="heading">
                        <ul class="breadcrumb">
                            <li>
                                <a href="{{route('egardenFE.room.detail',['id'=>$room->id])}}">{{__('egarden.room')}} #{{$room->id}}</a>
                                <svg width="4" height="6" aria-hidden="true" class="icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                                </svg>
                            </li>
                            <li class="active">{{$room->name}}</li>
                        </ul>
                        <h3 class="title-main">{{$room->description}}</h3>
                        <br>
                    </div>

            <br/>
            @if (session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <div class="content">
                <div class=" table-responsive">
                    <table class="table table--content-middle">
                        <thead>
                            <th style="text-align: center;">{{__('egarden.id')}}</th>
                            <th style="text-align: left; padding-left: 0px">{{__('egarden.title')}}</th>
                            <th style="text-align: left; padding-left: 0px">{{__('egarden.author')}}</th>
                            <th style="text-align: center;">{{__('egarden.lookup')}}</th>
                            <th style="text-align: center;">{{__('egarden.action')}}</th>
                        </thead>
                        <tbody>

                            @if(count( $egarden ) )

                            @foreach ($egarden as $item)
                            <tr>
                                <td class="text-center">
                                    {{$item->id}}
                                </td>
                                <td>
                                    @if($item->member_id == auth()->guard('member')->user()->id)
                                        <a href="{{route('egardenFE.edit',['idEgarden'=>$item->id,'id'=>$room->id])}}"
                                            title="{{ strip_tags($item->title) }}">
                                            <div class="garden-title">
                                                    {!! highlightWords2($item->title,'') !!}{{' ('.$item->comments->count().')' }}
                                                    @if(getNew($item->created_at,1)) <span class="icon-label">N</span>@endif
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{route('egardenFE.details',['idEgarden'=>$item->id])}}"
                                            title="{{ strip_tags($item->title) }}">
                                            <div class="garden-title">
                                                    {!! highlightWords2($item->title,'') !!}{{' ('.$item->comments->count().')' }}
                                                    @if(getNew($item->created_at,1)) <span class="icon-label">N</span>@endif
                                            </div>
                                        </a>
                                    @endif

                                </td>
                                <td class="date" style="text-align: left;">
                                   {{$item->member->nickname}}
                                </td>
                                <td class="text-center">
                                    {{$item->lookup ?? 0}}
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

            </div>
        </div>
    </div>
        {!! Theme::partial('paging',['paging'=>$egarden->appends(request()->input()) ]) !!}
    </div>

</main>
@else
<main id="main-content" data-view="advertisement" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
                {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1,'my_room'=> ($room->member_id == auth()->guard('member')->user()->id ?  true : false )]) !!}
            <div class="sidebar-template__content">
                <div class="heading">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{route('egardenFE.room.list')}}">{{__('egarden.room')}}</a>
                            <svg width="4" height="6" aria-hidden="true" class="icon">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                            </svg>
                        </li>
                        <li class="active">{{$room->name}}</li>
                    </ul>
                    <h3 class="title-main">{{$room->description}}</h3>
                    <br>
                </div>
                {{-- Search --}}
                <!-- filter -->
                @if (session('permission'))
                <br>
                <div class="alert alert-danger" style="width: 100%">
                  {{ session('permission') }}
                </div>
                @endif
                <div class="content">
                        <div class="editor row justify-content-md-center ">
                                <div class="col-md-7 slick_banner">
                                        @if ($room->images !== null)
                                        <div class="block-img " style="width: 530px;height: 340px;">
                                            <div class="img-bg"
                                                style="width: 100%;height: 100%; background-image:url('{{  get_image_url($room->images, 'featured') }}"
                                                alt="{{$room->title}}')">
                                                <img src="{{  get_image_url($room->images, 'featured') }}" alt="{{$room->title}}">
                                            </div>
                                        </div>
                                        @endif
                                </div>
                        </div>
                        <br>
                        <div class=" table-responsive">
                                {!!
                                    Theme::partial('garden.elements.joinRoom',['status'=>$room->statusMember(auth()->guard('member')->user()->id),'id'=>$room->id])
                                !!}
                        </div>

                </div>
            </div>
            <!-- end of filter -->
            {{-- end search --}}

        </div>
    </div>
    </main>
@endif


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
                           $this.replaceWith(`<a class='alert bg-green-1 syn' href='javascript:void(0)' idRoom="${idRoom}">{{__('egarden.room.pending')}}</a>`);
                           break;
                        case 'draft':
                           $this.replaceWith(`<a class='alert bg-main syn' href='javascript:void(0)' idRoom="${idRoom}" >{{__('egarden.room.join_us')}}</a>`);
                           break;
                       default:
                           break;
                   }
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

        $(document.body).on("click",'.deleteEgarden', function(e){
                let $this = $(this);
                $('#egarden_id').val($this.attr('data-value'))
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
                <input type=" text" id="hint" value="Do you really want to delete this egarden?" placeholder="&nbsp;" readonly>
              </label>
            </div>
          </div>
          <form action="{{route('egardenFE.delete')}}" method="post">
            @csrf
            <div class="button-group mb-2">
                <input type="hidden" value="" id="egarden_id" name="egarden_id">
                <input type="hidden" value="{{$room->id}}" name="room_id">
                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button>
                <button type="submit" class="btn btn-primary">Delete</button>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
