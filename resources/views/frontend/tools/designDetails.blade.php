<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwimCapz</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tools') }}/assets/css/style2/style.css">
    <link rel="stylesheet" href="{{ asset('tools') }}/assets/css/slick.css">
    <link rel="stylesheet" href="{{ asset('tools') }}/node_modules/cropperjs/dist/cropper.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        /* define globaly */
        const BASE_WEB_URL='{{ url("/") }}';
        const BASE_API_URL='{{ url("api/v1") }}';
    </script>
    <script src="{{ asset('tools') }}/assets/js/slick.js"></script>
    <script src="{{ asset('tools') }}/global.js"></script>
    <script src="{{ asset('tools') }}/fabric.js"></script>
    <script>
        let user_id=null;
        let isCartEdit=0;
        let isDesignEdit=0;
        let cart_id=null;
        let design_id=null;
        @if(Auth::check())
            user_id = '{{Auth::user()->id}}';
        @endif

        @if(request()->has('cart') && request()->cart)
            isCartEdit=1;
            isDesignEdit=0;
            cart_id='{{request()->cart}}';
        @endif

        @if(request()->has('design') && request()->design)
            isDesignEdit=1;
            isCartEdit=0;
            design_id='{{request()->design}}';
        @endif

    </script>

</head>

<body onload="startMyDetails()">
    <!-- Loader Start-->
    <div class="loader_anim_wrap" id="loaderStartEnd">
        <div class="loader_anim_box">
            <div class="loader_anim"></div>
        </div>
    </div>
    <!-- Loader End-->

    <div class="cd_slide_overlay" id="show-front-back-image-design">
        <div class="cd_slide_popup">
            <h3>Product View</h3>
            <div class="popup_slider parent-wrapper" id="popup-slide-frontId">
                
            </div>
            <div class="popup_slider parent-wrapper" id="popup-slide-backId">
                
            </div>
            <div class="cd_back_btn_wrap">
                <button class="close-btn cd_close_popup" onclick="closePricePopup()">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="cd_wrapper">
        <section class="design_studio">
            <div class="container">
                <div class="cd_design_content">
                    <button class="edit_btn" onclick="goToDesignPage()"><i class="fa-solid fa-arrow-left"></i> Edit Design</button>
                    <h3> Enter Quantity </h3>
                    <p>Choose the sizes and quantities for each style below</p>
                </div>
                <div class="alert-panel">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <p class="alert-panel-msg"></p>
                </div>
                <div id="set-image-design">
                </div>
                <footer class="cd_footer">
                    <div class="cd_container get-price">
                        <div class="cd_btn_wrap">
                            <button
                                class="cd_btn cd_next_btn"
                                type="button"
                                id="priceButton"
                                onclick="getPriceOfDesign()"
                            >Get Price</button>
                        </div>
                    </div>
                </footer>

                <div class="popup-overlay popup-box" id="price_popup">
                    <div class="popup-content pop-text">
                        
                        <h2>Estimated Price Summary</h2>
                        <div id="showImageInPricePopup">
                            <!-- images -->
                        </div>
                        <div id="totalEstimation">
                            <!-- total estimations -->
                        </div>
                        <div
                            class="errors"
                            id="show_errors"
                            style="color:red;font-size:bold;"
                        ></div>
                        <div class="btn-wrapper">
                            <a
                                href="javascript:void(0)"
                                class="edit-btn"
                                onclick="closePricePopup()"
                            ><i class="fa-solid fa-arrow-left"></i> Edit Quantity</a>
                            <button
                                class="buy-btn "
                                onclick="addToCart()"
                                style="cursor: pointer"
                            >Continue to cart </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <script src="{{ asset('tools') }}/designDetails.js"></script>
    <script src="{{ asset('tools') }}/index.js"></script>
    <script src="{{ asset('tools') }}/assets/js/slick.js"></script>
    <script src="{{ asset('tools') }}/arcEffect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
        </script>
    <script src="{{ asset('tools') }}/assets/js/custom.js"></script>
</body>

</html>