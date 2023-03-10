<style>
  .single .comments {
    margin-top: 3.2em;
}
</style>
@switch(Route::currentRouteName() )
@case('egardenFE.details')
<div class="account__icon" style="position: absolute; right: 70px">
  <a href="{{route($editItem, ['idEgarden'=> $idDetail,'id'=>$idRoom])}}" title="수정" target="_parent">
    <span class="icon-label" style="line-height: 3em; width: 6em; height: 3em; border-radius: 5px; border: 1px solid #f4f4f4; background: #f4f4f4; font-size: 1em; color: #EC1469; text-align: center; display: inline-block;">
    <!-- 수정 -->
    {{__('comments.edit')}}
    </span>
  </a>
</div>
<div class="account__icon" style="position: absolute; right: 0">
  <a href="javascript:void(0)" class="deleteRoom" title="삭제" data-toggle="modal" data-value="{{$idDetail}}"
     data-target="#confirmDelete">
    <span class="icon-label" style="line-height: 3em; width: 6em; height: 3em; border-radius: 5px; border: 1px solid #f4f4f4; background: #f4f4f4; font-size: 1em; color: #EC1469; text-align: center; display: inline-block;">
    <!-- 삭제 -->
      {{__('comments.delete')}}
    </span>
  </a>
</div>
@break
@case('gardenFE.details')
@if($canEdit ?? false)
  <div class="account__icon"
       style="position: absolute; @if($canDelete ?? false) right: 70px @else right: 0px @endif">
    <a href="{{route($editItem, ['id'=> $idDetail])}}?without_popup=true" title="수정" target="_parent"
       @if(isset($show_pwd_post) && $show_pwd_post == 1) data-toggle="modal" data-target="#confirmPwdPost" @endif
    >
      <span class="icon-label" style="border-radius: 5px;">
      <!-- 수정 -->
        {{__('comments.edit')}}
      </span>
    </a>
  </div>
@endif
@if($canDelete ?? false)
  <div class="account__icon" style="position: absolute; right: 0">
    <a href="javascript:void(0)" class="deleteRoom" title="삭제" data-toggle="modal" data-value="{{$idDetail}}"
       data-target="#confirmDelete">
      <span class="icon-label" style="border-radius: 5px;">삭제</span>
    </a>
  </div>
@endif
@break
@default
@if($canEdit ?? false)
  <div class="account__icon" style="margin-right: 10px">
    <a href="{{route($editItem, ['id'=> $idDetail])}}" title="수정" target="_parent">
      <span class="icon-label">
      <!-- 수정 -->
        {{__('comments.edit')}}
      </span>
    </a>
  </div>
@endif
@if($canDelete ?? false)
  <div class="account__icon">
    <a href="javascript:void(0)" class="deleteRoom" title="삭제" data-toggle="modal" data-value="{{$idDetail}}"
       data-target="#confirmDelete">
      <span class="icon-label">
      <!-- 삭제 -->
        {{__('comments.delete')}}
      </span>
    </a>
  </div>
@endif
@break
@endswitch
<div class="clear-fix"></div>
