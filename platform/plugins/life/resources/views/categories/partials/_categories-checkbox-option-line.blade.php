@php
/**
 * @var string $value
 */
$value = isset($value) ? (array)$value : [];
@endphp
@if($categories)
    <ul>
        @foreach($categories as $category)
            @if($category->id != $currentId)
                <li value="{{ $category->id ?? '' }}"
                        {{ $category->id == $value ? 'selected' : '' }}>
                    {{-- @if($isCheckbox) --}}
                    {!! Form::customCheckbox([
                        [
                            $name, $category->id, $category->name, in_array($category->id, $value),
                        ]
                    ]) !!}
                    {{-- @else 
                        <input type="radio" name="{{$name}}" value="{{$category->id}}" @if(in_array($category->id, $value)) checked @endif>{{$category->name}}<br>
                    @endif --}}
                   
                    @include('plugins.life::categories.partials._categories-checkbox-option-line', [
                        'categories' => $category->child_cats,
                        'value' => $value,
                        'currentId' => $currentId,
                        'name' => $name,
                        // 'isCheckbox' => true
                    ])
                </li>
            @endif
        @endforeach
    </ul>
@endif