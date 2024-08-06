<script>
    $(document).on('click', '.default-address', function(e) {

        funcClearAllSelection();
        $(this).prop('checked', true);

        let id = $(this).val();
        funcSetDefaultAddress(id);

    });

    function funcClearAllSelection() {
        $('.default-address').each(function() {
            $(this).prop('checked', false);
        });
    }

    function funcSetDefaultAddress(id) {
        $.post('{{ route('setDefaultAddress') }}', {
            _token: '{{ csrf_token() }}',
            id: id
        }, function(response) {
            console.log(response);
        });
    }


    /* load saved address of this user */
    function loadUserAddresses() {
        let url = '{{ route('user-profile') }}';
        $.get(url, {
            'load-address': true
        }, function(response) {
            $('.user-addresses').html(response);
        });
    }

    $(function() {
        loadUserAddresses();
        //form submit
        $(document).on('submit', '.address-form', function(e) {
            e.preventDefault(0);
            e.stopImmediatePropagation;

            let formData = new FormData(this);
            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log('Success', data);
                    loadUserAddresses();
                    toastr.success(data.message);
                    $('#common-modal').modal('hide');
                },
                error: function(reject) {
                    console.log('Error', reject);

                    var errors = reject.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        $("#error-" + key).text(val[0]);
                    });
                }
            });
        });
    });

    $(document).on('focus', 'input,textarea', function() {
        $('.errors span').html('');
    });

    $(document).on('click', '.address-destroy', function(e) {
        e.preventDefault(); // Removed the argument 0, it should be called without arguments.
        e.stopImmediatePropagation();

        // Show confirmation dialog
        if (!confirm('Are you sure you want to delete this address?')) {
            return; // If the user clicks "Cancel", do nothing.
        }

        let action = $(this).attr('href');
        let formData = new FormData();
        $.ajax({
            method: 'GET',
            url: action,
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                console.log('Success', data);
                loadUserAddresses();
                toastr.success(data.message);
            },
            error: function(reject) {
                console.log('Error', reject);
                toastr.error('Failed');
            }
        });
    });
</script>

<script>
    function isValidEmail(email) {
        // Regular expression for basic email validation
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    $(function() {
        $('.userform').submit(function(e) {
            e.preventDefault(0);

            var name = $("#name").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var isValidate = true;

            // Validate name
            if (name.trim() === "") {
                $('strong.error-name').html("Name is required");
                isValidate = false;
            } else {
                $('strong.error-name').html("");
            }

            if (email.trim() === "") {
                $('strong.error-email').html("Email is required");
                isValidate = false;
            } else if (!isValidEmail(email)) {
                $('strong.error-email').html("Please enter a valid email address.");
                isValidate = false;
            } else {
                $('strong.error-email').html("");
            }

            const phoneRegex = /^\d{0,11}$/;
            if (phone.trim() === "") {
                $('strong.error-phone').html("Phone no. is required");
                isValidate = false;
            } else if (!phone.match(phoneRegex)) {
                $('strong.error-phone').html("Phone no. can have a maximum of 11 digits");
                isValidate = false;
            } else {
                $('strong.error-phone').html("");
            }

            if (isValidate == false) {
                return;
            }

            $("button.submit-btn").prop("disabled", true);
            $.ajax({
                method: "POST",
                url: $(this).prop("action"),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data);
                    $("button.submit-btn").prop("disabled", false);
                },
                error: function(reject) {
                    let errors = reject.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('strong.error-' + key).html(value[0]);
                    });
                    $("button.submit-btn").prop("disabled", false);
                }
            });
        });
    });

    $(document).on('focus', 'input, textarea', function() {
        $('.errors').html('');
    })
</script>
