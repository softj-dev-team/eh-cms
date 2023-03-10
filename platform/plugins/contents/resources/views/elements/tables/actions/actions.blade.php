@foreach ($bulk_changes as $key => $bulk_change)
<a
    href="#"
    data-key="{{ $key }}"
    data-class-item="{{ $class }}"
    data-save-url="{{ $url }}"
   class="bulk-change-item btn btn-icon btn-sm btn-primary tip">
   <i class="fas fa-check"></i>
</a>
@endforeach
