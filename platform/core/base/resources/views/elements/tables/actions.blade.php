<div class="table-actions">
    {!! $extra !!}
    @if (!empty($edit))
        @if (Auth::user()->hasPermission($edit))
            <a href="{{ route($edit, $item->id) }}" class="btn btn-icon btn-sm btn-primary tip" data-original-title="수정"><i class="fa fa-edit"></i></a>
        @endif
    @endif

    @if (!empty($delete))
        @if (Auth::user()->hasPermission($delete))
            <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog tip" data-toggle="modal" data-section="{{ route($delete, $item->id) }}" role="button" data-original-title="삭제" >
                <i class="fa fa-trash"></i>
            </a>
        @endif
    @endif
</div>
