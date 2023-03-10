
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$id}}">
    <i class="fas fa-images"></i>
  </button>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="
        background-image: url({{!empty($link ) ? get_image_url($link) : '/vendor/core/images/placeholder.png'}});
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
