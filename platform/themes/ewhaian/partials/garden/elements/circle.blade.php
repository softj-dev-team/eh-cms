<style>
.dot {
  height: 100px;
  width: 100px;
  background-color: white;
  border-radius: 50%;
  display: inline-block;
  border-style: solid;
  border-color: #EC1469;
  background-size: cover;
  background-position: center;
}
.mini-dot {
  height: 30px;
  width: 30px;
  background-color: white;
  border-radius: 50%;
  display: inline-block;
  text-align: center;
  border-style: solid;
  border-color: #EC1469;
}
.garden-list {
    margin: 10px 0;
    display: flex;
    overflow-x: auto;
    overflow-y: hidden;
    padding-bottom: 15px;
}
.name-room {
    width: 100px;
    background-color: #F4F4F4;
    padding: 8px 0;
    text-align: center;
    margin-top: 10px;
    font-size: 12px;
}
.name-room span {
    white-space: pre-wrap;
}
.room {
    margin-right: 20px;
}
.group-garden-list {
    display: flex;
}
</style>
<div class="group-garden-list">
    <div class="garden-list" style="max-width: 110px;margin-right: 15px ">
        @foreach ($roomCreated as $item)
        <a href="{{route('egardenFE.room.detail',['id'=>$item->id])}}" title="{{$item->name}}">
            <div class="room" @if($loop->last) style="margin-right:0px;" @endif>
                <div class="dot" style="background-image: url('{{ get_image_url($item->images, 'thumb') }}')">
                    @if( getStatusDateByDate($item->published) == "Today" )
                    <div class="mini-dot" >
                        <div style="color: #EC1469;margin-top: 3px;">N</div>
                    </div>
                    @endif
                </div>

                <div>
                    <div class="name-room">
                    <span>{{$item->name}}</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="garden-list" style=" ">
        @foreach ($roomJoined as $item)
        <a href="{{route('egardenFE.room.detail',['id'=>$item->id])}}" title="{{$item->name}}">
            <div class="room">
                <div class="dot" style="background-image: url('{{ get_image_url($item->images, 'thumb') }}')">
                    @if( getStatusDateByDate($item->getMemberJoined()->created_at) == "Today" )
                    <div class="mini-dot" >
                        <div style="color: #EC1469;margin-top: 3px;">N</div>
                    </div>
                    @endif
                </div>

                <div>
                    <div class="name-room">
                        <span>{{$item->name}}</span>
                    </div>
                </div>
            </div>
        </a>

        @endforeach
    </div>

</div>
