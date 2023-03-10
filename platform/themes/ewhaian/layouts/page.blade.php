<!DOCTYPE html>
<html>

<head>
    {{-- {!! Theme::header() !!} --}}
    {!! Theme::partial('head') !!}
    {!! Theme::partial('css') !!}

</head>

<body>
    <style>
        a {
            color: #EC1469;
        }
    </style>
    {!! Theme::partial('header') !!}

    <main id="main-content" data-view="home" class="home-page ewhaian-page">
        <div class="container">
            <div class="sidebar-template">
                <div class="sidebar-template__control">
                    <!-- category menu -->

                    <!-- end of category menu -->
                </div>

                <div class="sidebar-template__content">
                    {!! Theme::content() !!}

                </div>
            </div>
        </div>
    </main>

    {!! Theme::partial('footer') !!}

    {!! Theme::partial('scripts') !!}

    {!! Theme::footer() !!}
</body>

</html>
