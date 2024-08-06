<a href="{{ route('addressAdd') }}" class="link-modal address-btn" link-url="{{ route('addressAdd') }}"
    link-title="Add Address" link-isFooter="1">+ Add Address</a>
<div class="shipping-content-wrapper">
    <div class="shipping">

        @foreach (Helper::getAddresses() as $item)
            @php
                if ($selected_id != null) {
                    $checked = $selected_id == $item->id ? 'checked' : '';
                } else {
                    $checked = $item->isDefault ? 'checked' : '';
                }
            @endphp
            <div class="address-row">
                <div class="radio-btn-content">
                    <input type="radio" id="shipping-{{ $item->id }}" value="{{ $item->id }}"
                        class="input-radio" {{ $checked }} name="shipping">

                    <label for="shipping-{{ $item->id }}">
                        {{ $item->name . ', ' . $item->address }}
                    </label>
                </div>


                <a href="{{ route('addressEdit', $item->id) }}" class="link-modal edit-btn"
                    link-url="{{ route('addressEdit', $item->id) }}" link-title="Edit Address" link-isFooter="1"><i
                        class="fas fa-pencil-alt"></i> Edit</a>

            </div>
        @endforeach
        <div colspan="2">
            <span class=""></span>
            <strong style="color:red;font-weight:bold;">
                <p class="error error-shipping errors"></p>
            </strong>
        </div>

    </div>
</div>
