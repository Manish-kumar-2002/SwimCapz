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
                    <input
                        id="billing-{{ $item->id }}"
                        type="radio" class="input-radio billing-radio" {{ $checked }}
                        name="billing" value="{{ $item->id }}">

                    <label for="billing-{{ $item->id }}">
                        {{ $item->name . ', ' . $item->address }}
                    </label>
                </div>
                <a href="{{ route('addressEdit', $item->id) }}" class="link-modal edit-btn"
                    link-url="{{ route('addressEdit', $item->id) }}" link-title="Edit Address" link-isFooter="1"><i
                        class="fas fa-pencil-alt"></i>Edit</a>
            </div>
        @endforeach

        <div>
            <strong style="color:red;font-weight:bold;">
                <p class="error error-billing errors"></p>
            </strong>
        </div>
    </div>
</div>
