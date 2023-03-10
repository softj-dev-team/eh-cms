<main id="main-content" data-view="event-details" class="home-page ewhaian-page">
    <div class="container">
        <div class="sidebar-template">
            <div class="sidebar-template__control">
                <!-- intro menu -->
                    <div class="nav nav-left">
                        <ul class="nav__list">
                            <p class="nav__title">Import</p>
                            <li class="nav__item">
                                <a class="active" href="{{route('member.importDB')}}" title="Import">Import</a>
                            </li>

                        </ul>
                    </div>

                <!-- end of intro menu -->
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
                <div class="heading" style="display: flex;">
                    <div class="heading__title" style="white-space: nowrap;">
                       Import Database
                    </div>

                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{route('member.importDB.postImport')}}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <input type="file" name="your_file" id="">
                    <input type="submit" value="Submit">
                </form>

            </div>
        </div>
    </div>
</main>
