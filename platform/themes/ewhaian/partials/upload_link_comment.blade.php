<style>
  .input-group-image {
      margin-right: 17px;
  }

  .input-group-image .form-control--upload {
      height: 150px;
      width: 150px;
      max-width: 150px;
  }

  .data-origin-template .align-items-center {
      display: none !important;
  }
  </style>
<div class="d-flex">
  <div><label for="deadline" class="control-label" aria-required="true" style="font-weight: bold">이미지첨부</label></div>
  <div>
    <div data-upload-file class="upload-file mb-3">

      <input name="commentFile[]" id="commentFile" class="commentFile" type="file" style="display: none" accept="image/*" multiple />
      <span class="form-control__label iconUpload">
        <svg width="20.239" height="21.214" aria-hidden="true" class="icon">
            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_file">
            </use>
        </svg>
      </span>

      <ul class="list-group list-group-flush" id="listItemUpload">

      </ul>

    </div>
  </div>
</div>
<script>

  $(document).ready(function(){
    $(".iconUpload").click(function(){
      $(".commentFile").click();
    });

    // $(".commentFile").change(function(){
    //   if($(".commentFile").val() != ""){
    //     $(".uploadFileValue").html($(".commentFile").val().replace(/C:\\fakepath\\/i, ''));
    //     $(".deleteFile").show();
    //     $(".iconUpload").hide();
    //   }
    // });
    let filesInput = document.getElementById('commentFile');
    filesInput.addEventListener('change', (event)=> {
      $(".iconUpload").hide();
      const files = event.target.files; //FileList object
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        let html = `<li class="list-group-item">
          <span>` + file.name +`</span>
          <button class="btn btn-primary btn-icon mr-3 deleteFile" type="button" onclick="$(this).parent().remove()">
            <svg width="12.121" height="14.121" aria-hidden="true" class="icon">
              <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_minus"></use>
            </svg>
          </button>
        </li>`;
        $('#listItemUpload').append(html);
      }
    })
    $(".deleteFile").click(function(){
      $(".commentFile").val("");
      $(".uploadFileValue").html("");
      $(".deleteFile").hide();
      $(".iconUpload").show();
    });
  });
</script>


