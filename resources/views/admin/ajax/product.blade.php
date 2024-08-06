<option value="">{{ __('Select Product') }}</option>

@foreach($products as $product)
    @php
        $_selected=$product->id == $selected ? 'selected' : '';
    @endphp

    <option value="{{$product->id}}" {{$_selected}}>{{ $product->name }}</option>
@endforeach
