@if (isset($item->role_id))
    {{ $item->role_name }}
@else
   {{ __('No role assigned') }}
@endif