<form action="{{route('submit.loadPo')}}" method="post" id="submitLoadPo" enctype="multipart/form-data">
    @csrf
    <div class="container">
        <input type="hidden" name="order_no" id="order_no" value="{{$order_no}}">
        <div class="input-wrap">
            <label for="fname">PO Number</label>
            <input type="text" name="po_order" id="po-order" placeholder="Enter PO Number" />
            <strong style="color:red;font-weight:bold;">
                <p class="error-po_order error" style="display: none"></p>
            </strong>
        </div>
        <div class="input-wrap">
            <label for="fname">Attachement</label>
            <input type="file" name="attachement" id="attachement" />
            <strong style="color:red;font-weight:bold;">
                <p class="error-attachement error" style="display: none"></p>
            </strong>
        </div>
        <strong style="color:red;font-weight:bold;">
                <p class="error-common_error error-order_no errors"></p>
        </strong>
        <strong class="server-info" style="color:red;font-weight:bold;">
            <!-- error -->
        </strong>
        <div class="input-wrap">
            <button type="submit" class="btn btn-primary">
                Place Order
            </button>
        </div>
    </div>
</form>
