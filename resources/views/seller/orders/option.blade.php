@foreach($row->attributes as $attribute)
    <label  class="badge badge-light-pill"  >{{ $attribute->attribute->name }} - {{ $attribute->variation->name }}</label>
@endforeach
@foreach ($row->options as $key => $option)
    <div class="mb-1">
        <span @if ($option->is_required != 1) style="display: inline-block;" @endif>
            <label style="float: left;margin-right:5px;color:#212121;">{{ $option->name }}
                : </label>
            @if ($option->is_required == 1)
                <span class="text-danger" style="font-size: 16px;">*</span>
            @endif
        </span>
        <div class="demo-inline-spacing">
        @foreach ($option->childrenCategories as $item)
            @if ($option->select_type == 0)
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="radio"
                        name="option[{{ $option->id }}]" id="option_{{ $item->id }}"
                        @if ($option->is_required == 1) required @endif
                        value="{{ $item->id }}" data-amount="{{ $item->amount }}"
                        data-amount-type="{{ $item->amount_type }}">
                    <label class="form-check-label"
                        for="option_{{ $item->id }}">{{ $item->name }}</label>
                </div>
            @else
                <div class="form-check form-check-warning">
                    <input class="form-check-input" type="checkbox"
                        id="option_{{ $item->id }}"
                        name="option[{{$option->id}}][]"
                        id="option_{{ $item->id }}" value="{{ $item->id }}"
                        @if ($option->is_required == 1) required @endif
                        data-amount="{{ $item->amount }}"
                        data-amount-type="{{ $item->amount_type }}">
                    <label for="option_{{ $item->id }}">{{ $item->name }}</label>
                </div>
            @endif
        @endforeach
        </div>
    </div>
@endforeach