{{--<img src="{{!empty($link ) ? get_image_url($link) : '/vendor/core/images/placeholder.png'}}" alt="">--}}
<!-- Button trigger modal -->
<div>
{{--  <img class="showImage" src="{{!empty($link ) ? get_image_url($link) : '/uploads/imga.jpg'}}" alt="Image">--}}
  <a href="javascript:void(0)" data-toggle="modal" data-target="#modalShowImage-{{$id}}">
    <img class="showImage" src="{{!empty($link ) ? get_image_url($link) : '/vendor/core/images/bg.png'}}" alt="Image">
  </a>
  <div class="modal fade" id="modalShowImage-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="
        background-image: url({{!empty($link ) ? get_image_url($link) : '/vendor/core/images/bg.png'}});
        background-position: center;
        background-repeat: no-repeat;
        background-size: auto;
        display: block;
        height: 500px;
        ">
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .showImage{
    background-position: center;
    background-repeat: no-repeat;
    background-size: auto;
    display: block;
    width: 60px;
  }
</style>
