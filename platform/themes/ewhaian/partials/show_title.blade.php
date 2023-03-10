@php
  //set default text title
  $setNumText = 50;

  $keyword = $keyword ?? null;
  $num = $num ?? $setNumText;

@endphp

{!! highlightWords2($text ,$keyword, $num) !!}
