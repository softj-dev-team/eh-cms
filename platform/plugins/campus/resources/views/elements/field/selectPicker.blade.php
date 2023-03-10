<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
        font-size: unset;
    }
</style>

<div class="form-group" style="z-index: 1;">

    <label for="status" class="control-label" aria-required="true">개설학과/전공</label>

    <select class="form-control select-full select2-hidden-accessible" multiple name="major[]" tabindex="-1"
        aria-hidden="true">

        @foreach ($options['major'] as $key => $item)
        @if($item->getChild()->count() > 0)
            @foreach ($item->getChild() as $key => $subitem)
            <option value="{{$subitem->id}}"
                    @if (!is_null($options['evaluation_id']))
                        @if (count( $subitem->getItemById($options['evaluation_id'],$options['type'] ?? 0 )->get() ) > 0 )
                            selected
                        @endif
                    @endif>
                {{$subitem->name}}
            </option>
            @endforeach
        @else
        <option value="{{$item->id}}"
                @if (!is_null($options['evaluation_id']))
                    @if (count( $item->getItemById($options['evaluation_id'],$options['type'] ?? 0)->get() ) > 0 )
                        selected
                    @endif
                @endif>
            {{$item->name}}
        </option>
        @endif


        @endforeach


    </select>
</div>


{{-- @if (!is_null($options['evaluation_id']))
@if (count( $item->getItemById($options['evaluation_id'],$options['type'] ?? 0)->get() ) > 0 )
    selected
@endif
@endif --}}
