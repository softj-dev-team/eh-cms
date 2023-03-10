@if(isset($my_room) && $my_room == true)
    <a href="{{route('egardenFE.room.myroom.list')}}" class="alert bg-white-1" style="display: block;margin-left: 10px;" idRoom="{{$id}}">{{__('egarden.room.my_room')}}</a>
@else
    @switch($status)
        @case('publish')
        <a  href="javascript:void(0)" class="alert bg-white-1 exitRoom" title="Remove room" data-toggle="modal" data-value="{{$id}}" data-target="#confirmExit{{$id}}" >
                {{__('egarden.room.approved')}}
        </a>
        <!-- Modal -->
            <div class="modal fade modal--confirm" id="confirmExit{{$id}}" tabindex="-1" role="dialog"
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
                                <input type=" text" id="hint" value="{{__('egarden.room.confirm_exit')}}" placeholder="&nbsp;" readonly>
                                </label>
                            </div>
                            </div>
                            <form action="{{route('egardenFE.ajaxJoinRoom',['id'=>$id])}}" method="post">
                            @csrf
                            <div class="button-group mb-2">
                                <input type="hidden" value="" id="room_id" name="room_id">
                                <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">{{__('egarden.room.cancel')}}</button>
                                <button type="submit" class="btn btn-primary">{{__('egarden.room.exit')}}</button>
                            </div>
                        </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            @break
        @case('pending')
            <a class="alert bg-white-1 syn" href="javascript:void(0)"  style="display: block;margin-left: 10px;" title="{{__('egarden.room.pending')}}" idRoom="{{$id}}">{{__('egarden.room.pending')}}</a>
            @break
        @case('draft')
        <a class="alert bg-white-1 syn"  href="javascript:void(0)" style="display: block;margin-left: 10px;" title="{{__('egarden.room.join_us')}}" idRoom="{{$id}}">{{__('egarden.room.join_us')}}</a>
            @break
        @default

    @endswitch
@endif
