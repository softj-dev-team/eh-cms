<style>
    .switch_menu {
    position: relative;
    display: flex
}
.switch_menu div {
    background-size: contain;
    width: 30px;
    height: 30px;
    position: absolute;
    margin-left: 10px;
    right: 0;
    cursor: pointer;
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
    width: 92px;
    height: 92px;
    font-size: 0.85714em;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    padding: 0.83333em 1.25em;
    background: #EC1469;
    transform: rotate(45deg);
}
.item__image {
    overflow: hidden;
    position: relative;
}

.icon-label-image {
  width: 2em;
  height: 2em;
  display: inline-block;
  border: 1px solid #FFFFFF;
  border-radius: 50%;
  color: #FFFFFF;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  font-size: 0.61429em;
}

.btn2:hover {
        border: 1px solid #EC1469;
}
.btn2 {
    border: 1px solid #EC1469;
    background-color: white;
    color: #EC1469 !important;
    margin-right: 10px;
    padding: 7px 10px 10px;
}
.btn_active {
    border: 1px solid #EC1469;
    background: #e8e8e8;
}
</style>

<div style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: flex-end;">

        <a
            href="{{url()->current().'?'.http_build_query(array_merge(request()->all(),['style' => 0]))  }}"
            class="btn btn2 {{$style == 0 ? 'btn_active' : ''}}"
            title="{{__('layout.list')}}"
            style="  @if($hidden == 0 ) display:none  @endif "
        >
            <img src="{{Theme::asset()->url('img/menu_1.png')}}" style="width: 20px; height: 20px;" />
            {{__('layout.list')}}
        </a>
        <a
            href="{{url()->current().'?'.http_build_query(array_merge(request()->all(),['style' => 1]))  }}"
            class="btn btn2 {{$style == 1 ? 'btn_active' : ''}}"
            title="{{__('layout.list_thumbnail')}}"
            style="  @if($hidden == 1 ) display:none @endif "
        >
            <img src="{{Theme::asset()->url('img/menu_2.png')}}" style="width: 20px; height: 20px;" />
            {{__('layout.list_thumbnail')}}
        </a>

        <a
            href="{{url()->current().'?'.http_build_query(array_merge(request()->all(),['style' => 2]))  }}"
            class="btn btn2 {{$style == 2 ? 'btn_active' : ''}}"
            title="{{__('layout.album')}}"
            style="  @if($hidden == 2 ) display:none  @endif "
        >
            <img src="{{Theme::asset()->url('img/menu_3.png')}}" style="width: 20px; height: 20px;" />
            {{__('layout.album')}}
        </a>

    </div>

</div>

