<script>
    $(document).ready(function() {
        $(".coupon-heading").click(function() {
            $(".show-coupon").slideToggle();
            $(this).toggleClass("add-style");
        });
        getCoupons();
        var apply_btn = document.querySelector('.apply-coupen #apply_btn');
        var apply_input = document.querySelector('.apply-coupen #apply_code_in');
        checkText(apply_input, apply_btn);

        apply_input.addEventListener('input', function() {
            checkText(apply_input, apply_btn);
        });
    });

    function checkText(input, button) {
        if ($(input).val() != "") {
            button.disabled = false;
        } else {
            button.disabled = true;
        }
    }

    function copyToClipboard(that) {
        var $button = $(that);
        var couponCode = $button.closest(".copy-coupon").find(".coupon-code").text();
        var tempInput = $("<input>");
        $("body").append(tempInput);
        tempInput.val(couponCode).select();
        document.execCommand("copy");
        tempInput.remove();
        $button.text("copied!");
        setTimeout(function() {
            $button.text("COPY");
        }, 1000);
    }

    function getCoupons() {
        $.ajax({
            url: '{{ url('frontend/available_coupons') }}',
            type: 'GET',
            success: function(response) {
                console.log(response);
                let usedClass = "";
                let i = 1;
                let couponHtml = ""; // Initialize couponHtml correctly
                response.forEach(coupon => {
                    const coup = coupon.code;
                    const coupDes = coupon.description;
                    usedClass = (i % 2 === 1) ? "used" : "unused";

                    couponHtml += `
                <div class="copy-coupon unused">
                    <div class="coupon-code-wrap">
                        <span class="coupon-code">${coup}</span>
                        <p>${coupDes}</p>
                    </div>
                    <button class="coupon-btn" onclick="copyToClipboard(this)">COPY</button>
                </div>
                `;
                    i++;
                });
                if (couponHtml == "") {
                    couponHtml = `
                <div style="text-align:center;">
                <p>No coupon available</p>
                </div>
                `;
                }
                $('.show-coupon').append(couponHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error occurred:', status, error);
            }
        });
    }
</script>
