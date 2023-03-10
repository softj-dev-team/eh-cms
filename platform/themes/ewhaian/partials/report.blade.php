<style>
.single__date, .single__eye {
    display: inline-block;
    margin-left: 4px;
}
.single__date {
    margin-right: 0em;
}

.single__info svg {
   top: -1px;
}
.alert{
  font-size:14px;
}
</style>
<div class="item__action" style="cursor: pointer;">
  @if(!is_null( auth()->guard('member')->user() ) )
    <a href="javascript:void(0)" class="report_post" data-target="#reportPost{{$type_report}}{{$id_post}}" data-toggle="modal">
      <img src="{{Theme::asset()->url('img/dependence.svg')}}" alt="{{__('comments.declaration')}}" style="width:14px; height:15px;margin-bottom:6px">
      {{__('comments.declaration')}}
    </a>
  @endif
</div>
