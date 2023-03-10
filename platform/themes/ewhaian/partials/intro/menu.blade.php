<div class="nav nav-left">
    <p class="nav__title">이화이언<i class="fas fa-angle-right d-lg-none" style="margin-left: 15px; cursor: pointer" onclick="collapseList(event)"></i></p>
    <ul id="nav-list-menu" class="nav__list collapse d-lg-block">
        @foreach ($categories as $item)
            <li class="nav__item">
                <a class="@if(isset($detail) && $item->id == $detail->categories->id) active @endif" href="{{route('eh_introduction.detail',['id'=>$item->getFirstIntro()->id])}}" title="{{$item->name}}">{{$item->name}}</a>
                @if( isset($detail) && $item->id == $detail->categories->id)
                    <ul class="nav-sub">
                        @foreach ($item->intro as $subItem)
                        <li>
                                <a class="@if($subItem->id == $detail->id) active @endif" href="{{route('eh_introduction.detail',['id'=>$subItem->id])}}" title="{{$subItem->title}}">{{$subItem->title}}</a>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
        <li class="nav__item">
            <a class=" @if(isset($faq)) active @endif" href="{{route('eh_introduction.faq')}}" title="{{__('eh-introduction.faqs')}}">{{__('eh-introduction.faqs')}}</a>
        </li>
        <li class="nav__item">
                <a class=" @if(isset($contact)) active @endif" href="{{route('eh_introduction.contact')}}" title="{{__('eh-introduction.contact')}}">{{__('eh-introduction.contact')}}</a>
        </li>
        <li class="nav__item">
            <a class=" @if(isset($notices)) active @endif" href="{{route('eh_introduction.notices.list')}}"
                title="{{__('eh-introduction.notices')}}">{{__('eh-introduction.notices')}}</a>
        </li>

    </ul>
</div>
