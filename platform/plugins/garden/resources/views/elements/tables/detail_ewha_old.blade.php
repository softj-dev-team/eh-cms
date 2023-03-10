
@extends('core.base::layouts.master')
@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="main-form">
        <div class="form-body">
          <div class="form-group">
            <label for="title" class="control-label">이화이언 소개: </label>
            <input class="form-control" placeholder="이화이언 소개" name="title" type="text" value="{{$garden->BP_TITLE}}" id="title">

          </div>

          <div class="form-group">
            <label  class="control-label">{{__('core/base::tables.detail')}}: </label>
            <textarea disabled="true" class="form-control editor-tinymce" placeholder="내용" id="detail" rows="30" name="detail" cols="50"  aria-hidden="true">
             {{strip_tags($garden->BP_CONTENT)}}
            </textarea>
          </div>

          <div class="clearfix"></div>
        </div>
      </div>


    </div>
  </div>
@stop

