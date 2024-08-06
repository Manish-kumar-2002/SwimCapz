<script>

    /* alert clear */
    function alertClear() {
        setTimeout(function() {
            $('.errors').each(function() {
                $(this).text('');
            });
        }, 5000);
    }

    /* coupon apply */
    $(document).on('submit', '.coupon-apply-form', function(e) {
        e.preventDefault(0);

        let action = $(this).attr('action');
        let formData = new FormData($(this)[0]);

        let _form = $(this);

        _form.find('.coupon-info').html(`
            <span class="errors" style="color:green;text-align:center;">
                <i class="fa fa-spinner fa-spin"></i></span>`);
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success', response);

                _form.find('.coupon-info').html(`
                   <span class="errors" style="color:green;">${response.message}</span>`);
                alertClear();
               refreshMainCartOrderDetails();
            },
            error: function(reject) {
                _form.find('.coupon-info').html('');
                if (reject.status === 422) {
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors.errors, function(key, val) {
                        console.log('=======>', key, val[0]);

                        $('.error-' + key).text(val[0]);
                    });

                    setTimeout(function() {
                        _form.find('.coupon-info').html('');
                        alertClear();
                    }, 5000);
                }

                if (reject.status === 401 || reject.status === 402 || reject.status === 403) {
                    var errors = $.parseJSON(reject.responseText);
                    _form.find('.coupon-info').html(
                        `<p class="errors" style="color:red">${errors.error}</p>`);
                    console.log('===========', errors.error);
                    alertClear();
                }


            }
        });
    });

    $(document).on('click', '.coupon-removed', function(e) {
        e.preventDefault(0);

        let url=$(this).attr('href');
        $.get(url, function(response) {
            $('.coupon-info').html(`
                   <span class="errors" style="color:green;">${response.message}</span>`);
            alertClear();
            refreshMainCartOrderDetails();
        });
    });
</script>