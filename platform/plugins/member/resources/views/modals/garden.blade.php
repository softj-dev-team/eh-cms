<!-- Modal -->
<div class="modal fade modal--confirm" id="confirmPopup" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header align-items-center justify-content-lg-center">
                <span class="modal__key">
                    <svg width="40" height="18" aria-hidden="true">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_key"></use>
                    </svg>
                </span>
            </div>
            <div class="modal-body">

                <div class="d-lg-flex align-items-center mx-lg-2">
                    <div class="d-lg-flex align-items-start flex-grow-1">
                        <div class="form-group form-group--1 flex-grow-1 mr-lg-30 mb-3">
                            <label for="hint" class="form-control">
                                <input type=" text" id="hint" value="What if it was your favorite cup?"
                                    placeholder="&nbsp;" readonly>
                                {{-- <span class="form-control__label"> Hint</span> --}}
                            </label>
                        </div>
                        <form action="{{route('gardenFE.passwd')}}" method="post" target="_parent">
                            @csrf
                            <div class="form-group form-group--1 flex-grow-1 mr-lg-20 mb-3">
                                <label for="password-garden" class="form-control form-control--hint">
                                    <input type="password" id="password-garden" name="password" placeholder="&nbsp;"
                                        value="{{Cookie::get('password_garden') ?? ''}}" maxlength="16" required>
                                    <span class="form-control__label"> Set password </span>
                                </label>
                                <span class="form-control__hint">비밀번호는 최대 16자까지</span>
                            </div>
                    </div>
                    <div class="button-group mb-2">
                        <button type="button" class="btn btn-outline mr-lg-10" data-dismiss="modal">cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
