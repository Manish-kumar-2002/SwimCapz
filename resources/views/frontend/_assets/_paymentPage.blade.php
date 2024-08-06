
<form action="{{route('createTempOrder')}}" method="post"
    class="gateway-form stripe-po-form" data-method="stripe-po">
    @csrf
    <input type="hidden" name="paymentMethod" value="stripe-po" >
    
    <ul class="myradio">
        <li>
            <input type="radio" id="stripe" name="payment-option" value="stripe" checked>
            <label for="stripe">Stripe</label><br>
        </li>
        <li>
            <input type="radio" id="po" name="payment-option" value="po">
            <label for="po">Po Number</label><br>
        </li>
        <!-- <li>
            <strong class="server-info" style="color:red;font-weight:bold;">
                error 
            </strong>
        </li> -->
    </ul>
    <div class="btn-wrap">
        <button class="btn btn-checkout red" type="submit">Place Order</button>
    </div>
</form>

<form id="stripe-pay" action="{{route("stripe.callback")}}" method="post" style="display:none;">
    @csrf
    <input type="input" name="order_number" id="order_no">
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="{{ env('STRIPE_KEY') }}"
        data-amount=""
        data-name="Swimcapz"
        data-description="Pay"
        data-image="{{asset("assets/images/1692941494logopng.png")}}"
        data-locale="auto"
        data-allow-payment-request="true"
        data-currency="usd"
    ></script>
</form>



