<script type="text/javascript">
    //form submit
    $(document).on('submit', '.form', function(e) {
        e.preventDefault(0);

        let action = $(this).attr('action');
        let formData = new FormData($(this)[0]);

        let nextDiv = $(this).attr('data-next');
        /* formSubmit */
        formSubmit(formData, action, nextDiv);
    });

    /* common form submit */
    function formSubmit(_form, _url, nextActiveClass) {
        $.ajax({
            url: _url,
            type: "POST",
            data: _form,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success', response);

                let activeDiv = $('.accordian-list.active');
                activeDiv.removeClass('active');

                let nextDiv = $('.accordian-list.' + nextActiveClass);
                nextDiv.addClass('active allow-open');

                alertClear();
            },
            error: function(reject) {

                if (reject.status === 422) {
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors.errors, function(key, val) {
                        console.log('=======>', key, val[0]);

                        $('.error-' + key).text(val[0]);
                    });

                    alertClear();
                }
            }
        });
    }

    /* alert clear */
    function alertClear() {
        setTimeout(function() {
            $('.errors').each(function() {
                $(this).text('');
            });
        }, 3000);
    }

    $(function() {
        $('select').niceSelect('destroy');
        $('#shipping_country,#billing_country').select2({
            placeholder: '-Select-',
            ajax: {
                url: '{{ route('ajax.search.countries') }}',
                data: function(params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });

        $('#shipping_state').select2({
            placeholder: '-Select-',
            ajax: {
                url: '{{ route('ajax.search.states') }}',
                data: function(params) {
                    let country = $('#shipping_country').val();
                    var query = {
                        search: params.term,
                        country: country
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });

        $('#billing_state').select2({
            placeholder: '-Select-',
            ajax: {
                url: '{{ route('ajax.search.states') }}',
                data: function(params) {
                    let country = $('#billing_country').val();
                    var query = {
                        search: params.term,
                        country: country
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });

    });

    //onclick acordion
    $(document).on('click', '.allow-open', function() {
        $('.allow-open').each(function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            }
        });


        $(this).addClass('active');
    });

    $(document).on('click', '#billing_check', function() {
        if ($(this).prop('checked')) {
            $('.billingAddress').hide();
        } else {
            $('.billingAddress').show();
        }
    });

    /* card number validate */
    $(document).on('keydown', '.card-number-check', function(e) {

        var text = $(this).val();
        var maxlength = $(this).data('maxlength') - 1;
        if (maxlength > 0) {
            $(this).val(text.substr(0, maxlength));
        }
    });

    $(document).ready(function() {
        $('input.card-number-check').bind('copy paste', function(e) {
            e.preventDefault();
        });
    });

    /* coupon apply */
    $(document).on('submit', '.coupon-apply-form', function(e) {
        e.preventDefault(0);

        let action = $(this).attr('action');
        let formData = new FormData($(this)[0]);

        let _form = $(this);

        _form.find('.coupon-info').html(`
            <p class="errors" style="color:green;text-align:center;">
                <i class="fa fa-spinner fa-spin"></i></p>`);
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success', response);

                _form.find('.coupon-info').html(`
                   <p class="errors" style="color:green;text-align:center;">${response.message}</p>`);
                alertClear();
                loadRightPanel();
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

        let url = $(this).attr('href');
        $.get(url, function(response) {
            $('.coupon-info').html(`
                   <p class="errors" style="color:green;text-align:center;">${response.message}</p>`);
            alertClear();
            loadRightPanel();
        });
    });

    function loadRightPanel() {
        $('.right-block .right-panel').html(`<p class="errors" style="color:green;text-align:center;">
                <i class="fa fa-spinner fa-spin"></i></p>`);

        let _url = '{{ route('loadCheckoutRightPanel') }}';

        $.get(_url, function(response) {
            $('.right-block .right-panel').html(response);
        });
    }

    /* onload load right panel */
    loadRightPanel(); //load right panel
    /* rushshipping */
    $('input[type=radio][name=shipping_charge]').change(function() {
        let rush = this.value;

        let url = '{{ route('rushChargeManage') }}';
        $.get(url, {
            isRush: rush == 'FREE' ? 0 : 1
        }, function(response) {
            loadRightPanel();
        });
    });

    $('.payment-option').click(function(e) {
        e.preventDefault(0);

        $('.payment-option').find('.stripe-card-info').not(this).hide();
        $(this).find('.stripe-card-info').toggle();
    });

    $(document).on('submit', '.stripe-po-form', function(e) {
        e.preventDefault(0);
        e.stopImmediatePropagation;

        let form = $(this);
        let action = form.attr('action');
        let formData = new FormData(form[0]);

        let paymentMethod = form.attr('data-method');
        /* form1 */
        formData.append('form_1', $('#form1').serialize());
        formData.append('form_2', $('#form2').serialize());
        formData.append('form_3', $('#form3').serialize());

        let _form = $(this);

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

                if (paymentMethod == 'stripe-po') {

                    let radio = $('input[name="payment-option"]:checked').val();
                    if (radio == "stripe") {
                        $('#stripe-pay #order_no').val(response.order.order_number);
                        $('#stripe-pay .stripe-button-el').trigger('click');
                    } else {
                        _initPOPayout(response);
                    }

                } else {

                    _form.find('.server-info').html(`
                        <p class="errors" style="color:green;text-align:center;">${response.message}</p>`);

                    setTimeout(function() {
                        _form.find('.server-info').html('');
                    }, 3000);

                }

                alertClear();
            },
            error: function(reject) {
                _form.find('.server-info').html('');
                if (reject.status === 422) {
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors.errors, function(key, val) {
                        console.log('=======>', key, val[0]);

                        $('.error-' + key).text(val[0]);
                    });

                    _form.find('.server-info').html(`<p
                            class="errors" style="color:red;text-align:center;">Please fill all form details</p>`);

                    setTimeout(function() {
                        _form.find('.server-info').html('');
                        alertClear();
                    }, 5000);
                }

                if (reject.status === 401 || reject.status === 402 || reject.status === 403) {
                    var errors = $.parseJSON(reject.responseText);
                    _form.find('.server-info').html(
                        `<p class="errors" style="color:red">${errors.error}</p>`);
                    console.log('===========', errors.error);
                    alertClear();
                }


            }
        });
    });

    function _initPOPayout(response) {
        let classObj = $('#exampleModal .modal-dialog');
        if (classObj.hasClass('modal-xl')) {
            classObj
                .removeClass('modal-xl')
                .addClass('modal-lg');
        }

        $('#exampleModal .modal-header #exampleModalLabel').html('Po Number');
        $('#exampleModal').modal('show');
        $.get('{{ route('loadPo') }}', {
            order_no: response.order.order_number
        }, function(response) {
            $('#exampleModal .modal-body').html(response);
        });
    }

    $(document).on('submit', '#submitLoadPo', function(e) {
        e.preventDefault(0);
        e.stopImmediatePropagation;

        $('#po-order').on('keydown', function() {
            if ($(this).val() == '') {
                $('.error-po_order.error').show();
            } else {
                $('.error-po_order.error').hide();
            }
        });

        $('#attachement').on('input', function() {
            if ($('#attachement')[0].files.length === 0) {
                $('.error-attachement.error').show();
            } else {
                $('.error-attachement.error').hide();
            }
        });

        let form = $(this);
        let action = form.attr('action');
        let formData = new FormData(form[0]);
        let _form = $(this);

        _form.find('.server-info').html(`
                <p class="errors" style="color:green;text-align:center;">Waiting....</p>`);
        $.ajax({
            url: action,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                _form.find('.server-info').html(
                    `<p class="error" style="color:green">${response.message}</p>`);

            },
            error: function(reject) {
                _form.find('.server-info').html('');
                if (reject.status === 422) {
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors.errors, function(key, val) {
                        console.log('=======>', key, val[0]);
                        $('.error-' + key).text(val[0]).show();
                    });
                }

                if (reject.status === 401) {
                    var errors = $.parseJSON(reject.responseText);
                    _form.find('.server-info').html(
                        `<p class="error" style="color:red">${errors.error}</p>`);
                }
            }
        });
    });

    $("#common-modal").on('hide.bs.modal', function() {
        loadAddress();
        loadAddress('BILLING');
    });

    function loadAddress(type = 'SHIPPING') {
        let checked_id;
        let url;
        let panel;
        if (type == 'SHIPPING') {
            checked_id = $('input[name="shipping"]:checked').val();
            url = '{{ route('checkout.load.address') }}?shipping=true&selected_id=' + checked_id;
            panel = $('.shipping-panel');
        } else {
            checked_id = $('input[name="billing"]:checked').val();
            url = '{{ route('checkout.load.address') }}?billing=true&selected_id=' + checked_id;
            panel = $('.billing-panel');

        }

        $.get(url, function(response) {
            panel.html(response);

            if (type != 'SHIPPING') {

                let isAddress = 0;
                $('.billing-radio').each(function() {
                    isAddress += 1;
                });

                if (isAddress > 0) {
                    $('.billing-address').show();
                } else {
                    $('.billing-address').hide();
                }

            }
        });
    }
</script>

@include('user._assets._profileScript')
