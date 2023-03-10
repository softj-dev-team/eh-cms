<?php $minText = "Please input 10 characters at least."?>
@if( auth()->guard('member')->check()  && isset($item) )
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                    <textarea id="reason" class="md-textarea form-control" style="height: 1.9em!important;" rows="3" name="reason" minlength="10" ></textarea>
                    <label for="reason" style="color: black;font-size:12px">비공감하시는 이유를 입력해 주세요.</label>
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="dislike" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup3" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">
            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                    <textarea id="reason_like" class="md-textarea form-control" style="height: 1.9em!important;" rows="3" name="reason" placeholder="{{$minText}}"></textarea>
                    <span class="warningLimitLike" style="display: none;color:red;font-size:10px">{{$minText}}</span>
                    <label for="reason_like ">공감취소하시는 이유를 알려주세요.</label>
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="like" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup2" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content" style="padding:0">
        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
              <div class="md-form" id="message_dislike">
              </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-ok" id="confirmPopoup2Close" >확인</button>
          </div>
        </div>

    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup4" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form change-text-like">
                    @if(!getDiskLikeToMember($item->id,auth()->guard('member')->user()->id))
                        이 글을 공감하시겠습니까?
                    @else
                        이 글을 공감취소하시겠습니까?
                    @endif
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="confirm_like" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup5" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content pr-0">
      <div class="modal-body">

        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
                <div class="md-form">
                    {{ __('comments.confirm_disklike') }}
                  </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-cancel mr-lg-10" data-dismiss="modal">취소</button>
            <button type="button" class="btn btn-ok" id="confirm_unlike" >확인</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade modal--confirm" id="confirmPopupReject" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
    <div class="modal-content" style="padding:0">
        <div class="align-items-center mx-lg-2">
          <div class="d-lg-flex align-items-start flex-grow-1">

            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 m-3">
              <div class="md-form" id="message_reject">
              </div>
            </div>
          </div>
          <div class="button-group mb-2" style="display: flex; justify-content: flex-end">
            <button type="button" class="btn btn-ok" id="confirmPopupRejectClose" >확인</button>
          </div>
        </div>

    </div>
  </div>
</div>
@endif
