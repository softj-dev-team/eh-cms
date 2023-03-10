<style>
    .header_title_post {
        display: flex;
        justify-content: space-evenly;
    }
    .header_title_post .disable {
        opacity: 50%;
    }
</style>

<div class="header_title_post">

    @if(count($firstParent) > 1)
        @if($firstParent->get(0)->id == $parent_id )
            <a  href="{{route($route,['categoryId'=>$firstParent->get(0)->id ])}}">
                <div >
                    <h3 class="form__title">{{$firstParent->get(0)->name }}</h3>
                </div>
            </a>
            <a  href="{{route($route,['categoryId'=>$firstParent->get(1)->id ])}}">
                <div class="disable">
                    <h3 class="form__title">{{$firstParent->get(1)->name }}</h3>
                </div>
            </a>
        @else
            <a  href="{{route($route,['categoryId'=>$firstParent->get(0)->id ])}}">
                <div class="disable">
                    <h3 class="form__title">{{$firstParent->get(0)->name }}</h3>
                </div>
            </a>
            <a  href="{{route($route,['categoryId'=>$firstParent->get(1)->id ])}}">
                <div>
                    <h3 class="form__title">{{$firstParent->get(1)->name }}</h3>
                </div>
            </a>
        @endif
    @else
        <div >
            <h3 class="form__title">{{$firstParent->get(0)->name }}</h3>
        </div>
    @endif





</div>
