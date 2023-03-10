@extends('core.base::layouts.master')
@section('content')
  <div class="max-width-1200">

    <div class="card" style="width: 1000px">
      <div class="card-body">
        <div class="form-group">
          <label class="text-title-field"
                 for="seo_description">욕설: (300x105px)</label>
          <input class="form-control" name="logo" type="file" hidden id="update-input" accept="image/*">
          <button class="btn btn-primary" id="btn-update">업로드</button>
        </div>
        <div class="form-group">
          <img width="300px" id="logo" src="/storage/uploads/back-end/logo/eh-logo.png" alt="">
        </div>
      </div>
    </div>

    <div class="card" style="width: 1000px; margin-top: 20px">
      <div class="card-body">
        <div class="form-group">
          <label class="text-title-field"
                 for="seo_description">Favicon: (640x640px)</label>
          <input class="form-control" name="favicon" type="file" hidden id="faviconInput" accept="image/*">
          <button class="btn btn-primary" id="btn-favicon" onclick="$('#faviconInput').click()">업로드</button>
        </div>
        <div class="form-group">
          <img width="300px" id="favicon" src="/storage/uploads/back-end/logo/favicon.png" alt="">
        </div>
      </div>
    </div>

    <div class="card" style="width: 1000px; margin-top: 20px">
      <div class="card-body">
        <div class="form-group">
          <label class="text-title-field" for="seo_description">Logo Bottom: (710*145px)</label>
          <input class="form-control" name="logoBottom" type="file" hidden id="logoBottom" accept="image/*">
          <button class="btn btn-primary" id="btnLogoBottom" onclick="$('#logoBottom').click()">업로드</button>
        </div>
        <div class="form-group">
          <img width="300px" id="logoBottomPreview" src="/storage/uploads/back-end/logo/ewha-logo-grey.png" alt="">
        </div>
      </div>
    </div>

    <div class="card" style="width: 1000px; margin-top: 20px">
      <div class="card-body">
        <div class="form-group">
          <label class="text-title-field" for="seo_description">Logo Loading: (710*145px)</label>
          <input class="form-control" name="logoLoading" type="file" hidden id="logoLoading" accept="image/*">
          <button class="btn btn-primary" id="btnLogoLoading" onclick="$('#logoLoading').click()">업로드</button>
        </div>
        <div class="form-group">
          <img width="300px" id="logoLoadingPreview" src="/storage/uploads/back-end/logo/logo-spinner.png" alt="">
        </div>
      </div>
    </div>

    <div class="card" style="width: 1000px; margin-top: 20px">
      <div class="card-body">
        <div class="form-group">
          <label class="text-title-field" for="seo_description">Contact Page Image: (770*1400px)</label>
          <input class="form-control" name="contactPageImage" type="file" hidden id="contactPageImage" accept="image/*">
          <button class="btn btn-primary" id="btnContactPageImage" onclick="$('#contactPageImage').click()">업로드</button>
        </div>
        <div class="form-group">
          <img width="300px" id="contactPageImagePreview" src="/storage/uploads/contact.png" alt="">
        </div>
      </div>
    </div>



  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    $(document).ready(function () {
      $("#btn-update").click(function () {
        $("#update-input").click();
      });
      $('#update-input').change(function () {
        const file = this.files[0];
        if (file) {
          let data = new FormData();
          data.append('image', file);
          data.append('type', 'logo');
          axios.post('/admin/settings/logo/update', data, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }).then(() => {
            let reader = new FileReader();
            reader.onload = function (event) {
              $('#logo').attr('src', event.target.result);
              alert('성공')
            }
            reader.readAsDataURL(file);
          })
        }
      });
      $('#faviconInput').change(function () {
        const file = this.files[0];
        if (file) {
          let data = new FormData();
          data.append('favicon', file);
          data.append('type', 'favicon');
          axios.post('/admin/settings/logo/update', data, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }).then(() => {
            let reader = new FileReader();
            reader.onload = function (event) {
              $('#favicon').attr('src', event.target.result);
              alert('성공')
            }
            reader.readAsDataURL(file);
          })
        }
      });

      $('#logoBottom').change(function () {
        const file = this.files[0];
        if (file) {
          let data = new FormData();
          data.append('logo', file);
          data.append('type', 'logo-bottom');
          axios.post('/admin/settings/logo/update', data, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }).then(() => {
            let reader = new FileReader();
            reader.onload = function (event) {
              $('#logoBottomPreview').attr('src', event.target.result);
              alert('성공')
            }
            reader.readAsDataURL(file);
          })
        }
      });

      $('#logoLoading').change(function () {
        const file = this.files[0];
        if (file) {
          let data = new FormData();
          data.append('logo', file);
          data.append('type', 'logo-loading');
          axios.post('/admin/settings/logo/update', data, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }).then(() => {
            let reader = new FileReader();
            reader.onload = function (event) {
              $('#logoLoadingPreview').attr('src', event.target.result);
              alert('성공')
            }
            reader.readAsDataURL(file);
          })
        }
      });

      $('#contactPageImage').change(function () {
        const file = this.files[0];
        if (file) {
          let data = new FormData();
          data.append('logo', file);
          data.append('type', 'contact-page');
          axios.post('/admin/settings/logo/update', data, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }).then(() => {
            let reader = new FileReader();
            reader.onload = function (event) {
              $('#contactPageImagePreview').attr('src', event.target.result);
              alert('성공')
            }
            reader.readAsDataURL(file);
          })
        }
      });
    })
  </script>
@endsection
