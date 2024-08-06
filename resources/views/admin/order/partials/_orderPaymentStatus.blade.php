<select class="form-control" name="payment_status" id="payment_status">
    <option value="{{ Helper::PAYMENT_PENDING }}"
        {{ $order->payment_status == Helper::PAYMENT_PENDING ? 'selected' : '' }}>
        {{ Helper::getPaymentStatusLabelByStatus(Helper::PAYMENT_PENDING) }}
    </option>
    <option value="{{ Helper::PAYMENT_SUCCESS }}"
        {{ $order->payment_status == Helper::PAYMENT_SUCCESS ? 'selected' : '' }}>
        {{ Helper::getPaymentStatusLabelByStatus(1) }}
    </option>
</select>
