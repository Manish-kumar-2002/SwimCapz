<form
    class="address-form"
    action="{{ route('addressStore') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
   
    @include('user.address.form', ['address' => null])
    <div class="row mb-4">
        <div class="col-md-12" style="display: inherit;">
            <button class="submit-btn btn btn-primary" type="submit">{{ __('Save') }}</button>
            <a class="btn btn-primary" data-bs-dismiss="modal">{{ __('Cancel') }}</a>
        </div>
    </div>
</form>
@include('user.address.script')