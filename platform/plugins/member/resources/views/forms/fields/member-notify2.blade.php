
<style>
  .meta-boxes {
    margin-top: 0;
  }

  .btn.focus {
    background-color:transparent !important;
  }

  #checkboxes label{
    display:block;
    padding:5px;
  }

  label.active{
    background-color: #17A2C1 !important;
    color: #ffffff !important;
  }

</style>

<div class="form-group">
  <label >회원 검색 후 보내기</label>
</div>
<div class="form-group">
  <label >아래 필터를 이용해서 회원 검색 후 푸시알람을 보내세요</label>
</div>

<div class="btn-group-toggle student-option" id="student-option">
  <label>학번 선택: </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="11"> 13학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="86"> 14학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="3"> 15학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="91"> 16학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="93"> 17학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="94"> 18학번
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="option1[]" value="92"> 그이상
  </label>
</div>
</br>

<div class="btn-group-toggle certificate-option" id="certificate-option">
  <label>등급 선택: </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="certificate[]" value="certification"> 일반회원
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="certificate[]" value="real_name_certification"> 이화인회원
  </label>
</div>
</br>

<div class="btn-group-toggle filter-option" id="filter-option">
  <label>그외 선택: </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="filterOther[]" value="login_in_last_one_month"> 로그인 한달 이내 회원
  </label>
  <label class="btn btn-secondary">
    <input type="checkbox" name="filterOther[]" value="not_is_blacklist"> 금지회원 제외
  </label>
</div>
</br>


<div class="form-group">
  <label >현재 선택된 회원수: </label>
  <span style="color: red" id="total-search"></span>
  <input type="hidden" id="member-ids" name="member_ids" value=""/>
</div>

<div class="form-group">
{{--  <label >URL:</label>--}}
  <input type="text" class="form-control" name="title" placeholder="푸시 알람 URL을 입력하세요.">
</div>
<div class="form-group">
{{--  <label >Content:</label>--}}
  <textarea class="form-control" name="content" rows="5" placeholder="푸시 알람 내용을 입력하세요."></textarea>
</div>



<script>
    $('.student-option label').on('click', (e) => {
        e.target.classList.toggle('active');
        let inputObj = e.target.querySelector('input');
        inputObj.classList.toggle('active');

    });

    $('.certificate-option label').on('click', (e) => {
        e.target.classList.toggle('active');
        let inputObj = e.target.querySelector('input');
        inputObj.classList.toggle('active');
    });

    $('.filter-option label').on('click', (e) => {
        e.target.classList.toggle('active');
        let inputObj = e.target.querySelector('input');
        inputObj.classList.toggle('active');
    });

    $(document).ready(function() {

        $('.student-option label, .certificate-option label, .filter-option label').on('click', function (e) {
            e.preventDefault();
            var arrStudent = [];
            var arrCertificate = [];
            var arrOtherFilter = [];

            $('#student-option input').each(function(index, element) {

                if($(element).hasClass("active")){
                    arrStudent.push($(element).val());
                }
            });

            $('#certificate-option input').each(function(index, element) {

                if($(element).hasClass("active")){
                    arrCertificate.push($(element).val());
                }
            });

            $('#filter-option input').each(function(index, element) {

                if($(element).hasClass("active")){
                    arrOtherFilter.push($(element).val());
                }
            });

            var $this = $(this);
            $.ajax({
                type:'POST',
                url: "{{route('member.notify.search1')}}",
                data:{
                    _token: "{{ csrf_token() }}",
                    arrStudent :arrStudent,
                    arrCertificate :arrCertificate,
                    arrOtherFilter :arrOtherFilter
                },
                dataType: 'json',
                success: function( data) {
                    $('#total-search').html(data.count_data);
                    $('#member-ids').val(data.memberIds);
                }
            }).always(function(){
                $this.css('pointer-events', 'auto').data('isRunAjax', false);
            });
        });

    })
</script>
