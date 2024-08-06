<option value="">{{ __('Select State') }}</option>

@foreach($states as $state)
    @php
        $_selected=$state->id == $selected ? 'selected' : '';
    @endphp
    <option value="{{$state->id}}" {{$_selected}}>{{ $state->state }}</option>
@endforeach
