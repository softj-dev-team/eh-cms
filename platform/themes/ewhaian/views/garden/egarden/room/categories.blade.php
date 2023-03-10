<main id="main-content" data-view="home" class="home-page ewhaian-page">
    <div class="container">
      <div class="sidebar-template">
        <div class="sidebar-template__control">
          <!-- category menu -->
          <div class="category-menu">
            {!! Theme::partial('garden.menu',['categories'=>$categories,'id'=>0,'egarden'=>1]) !!}
          </div>
          <!-- end of category menu -->
        </div>
        <div class="sidebar-template__content">
                <ul class="breadcrumb">
                @foreach (Theme::breadcrumb()->getCrumbs() as $i => $crumb)
                @if ($i != (count(Theme::breadcrumb()->getCrumbs()) - 1))
                    <li>
                      <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                      <svg width="4" height="6" aria-hidden="true" class="icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon_arrow_right"></use>
                      </svg>
                    </li>
                @else
                    <li class="active">{!! $crumb['label'] !!}</li>
                @endif
                @endforeach
                </ul>

            <form
                @if(\Request::route()->getName() == "egardenFE.room.categories.edit")
                    action="{{route('egardenFE.room.categories.update',['idRoom' => $idRoom, 'id'  => $categoriesRoom->id])}}"
                @else
                    action="{{route('egardenFE.room.categories.store',['idRoom' => $idRoom])}}"
                @endif
                  method="post" id="my_form" enctype="multipart/form-data">
             @csrf
            <div class="form form--border">
                    <h3 class="form__title">
                        @if(\Request::route()->getName() == "egardenFE.room.categories.edit")
                        Edit categories
                    @else
                        Create categories
                    @endif

                    </h3>
                    <p class="text-right"><span class="required">*</span>{{__('egarden.room.required')}}</p>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="display: block">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('err'))
                    <div class="alert alert-danger" style="display: block">
                      <p>{{ session('err') }}</p>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success" style="display: block" >
                      <p>{{ session('success') }}</p>
                    </div>
                    @endif
                    <div class="form-group form-group--1">
                            <label for="name" class="form-control">
                            <input type="text" id="name" placeholder="&nbsp;" name="name" value="{{isset($categoriesRoom) ? $categoriesRoom->name : '' }}" required>
                              <span class="form-control__label">{{__('egarden.room.name')}}<span class="required">*</span></span>
                            </label>
                    </div>
                    <div class="form-group">
                        <label for="background" class="text-bold">Background</label>
                        <input type="text" class="form-control jscolor" id="background" name="background" value="{{isset($categoriesRoom) ? $categoriesRoom->background : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="color" class="text-bold">Color</label>
                        <input type="text" class="form-control jscolor" id="color" name="color" value="{{isset($categoriesRoom) ? $categoriesRoom->color : '' }}">
                    </div>

                    <div class="text-center" style="margin-top: 20px;">
                        @if(\Request::route()->getName() == "egardenFE.room.create")
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('egarden.room.save')}}</a>
                        @else
                            <a href="javascript:{}" class="btn btn-primary" onclick="document.getElementById('my_form').submit();">{{__('egarden.room.update')}}</a>
                        @endif
                    </div>
            </div>
        </form>
        </div>

      </div>
    </div>
  </main>

  <script src="/themes/ewhaian/js/jscolor.js"></script>
