<script>
    $('#coupon').submit(function(e) {
        e.preventDefault(0);

        let _form = $(this);
        let formData=new FormData(_form[0]);
        let action=_form.attr('action');

        _form.find('.server-info').html(`
                <p class="errors" style="color:green;text-align:center;">Waiting....</p>`);

        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success', response);

                _form.find('.server-info').html(`
                    <p class="errors" style="color:green;text-align:center;">${response}</p>`);
                
                setTimeout(function() {
                    $('.errors').find('span').html('');
                }, 3000);
            },
            error: function(reject) {
                    var errors = $.parseJSON(reject.responseText);
                $.each(errors.errors, function(key, val) {
                    $('.error-' + key).find('span').text(val[0]);
                });

                setTimeout(function() {
                    $('.errors').find('span').html('');
                }, 3000);
                _form.find('.server-info').html('');
            }
        });
    });

    $(document).on('change', '#category-product', function(e) {
        $.get('{{url("ajax/search-product-by-category")}}/' + $(this).val(),{
            selected:"{{$coupon ? $coupon->product : null}}"
        },function(response) {
            $('#product').html(response);
        });
    });

    @if($coupon)
        $('#category-product').trigger('change');
    @endif
</script>