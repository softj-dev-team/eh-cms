<!DOCTYPE html>
<html>
<head>
  {{-- {!! Theme::header() !!} --}}
  {!! Theme::partial('head') !!}
  {!! Theme::partial('css') !!}
</head>
<body>

{!! Theme::partial('firebase_cloud_messaging') !!}

{!! Theme::partial('header') !!}

{!! Theme::content() !!}

{!! Theme::partial('footer') !!}

{!! Theme::partial('scripts') !!}

{!! Theme::footer() !!}
</body>
</html>
