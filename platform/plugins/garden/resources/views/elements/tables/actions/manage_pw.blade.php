
@extends('core.base::layouts.master')
@section('content')
  <div class="dt-buttons btn-group">
    <button  class="btn btn-secondary action-item" tabindex="0" aria-controls="table-plugins-garden" style="padding: 5px 10px;font-size: 12px;line-height: 1.5;background: #36c6d3;border-color: #36c6d3; color: #fff!important;">
      <span>
        <span data-action="reset_password" data-href=""><i class="fas fa-redo-alt"></i>
          <a href="{{route('garden.password.reset') }}" onclick="return confirm('비밀단어를 리셋하겠습니까?')" style="color: #fff">비밀번호 재설정</a>
        </span>
      </span>
    </button>
    <button class="btn btn-secondary action-item" tabindex="0" aria-controls="table-plugins-garden" style="margin-left: 10px;padding: 5px 10px;font-size: 12px;line-height: 1.5;background: #36c6d3;border-color: #36c6d3; color: #fff!important;">
      <span>
        <span data-action="edit_password" data-href=""><i class="fas fa-key"></i>
          <a href="{{route('garden.password.edit') }}" style="color: #fff">비밀번호 수정</a>
        </span>
      </span>
    </button>
  </div>
@stop

