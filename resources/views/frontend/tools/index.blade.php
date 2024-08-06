<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwimCapz</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('') }}assets/admin/css/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('tools') }}/assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('tools') }}/assets/css/slick.css">
    <link rel="stylesheet" href="{{ asset('tools') }}/node_modules/cropperjs/dist/cropper.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js"></script>
    <script>
        /* define globaly */
        const BASE_WEB_URL = '{{ url('/') }}';
        const BASE_API_URL = '{{ url('api/v1') }}';
        var decodedString = ('{{ json_encode($color_collections) }}').replace(/&quot;/g, '"');
        const color_collection = JSON.parse(decodedString);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('tools') }}/assets/js/slick.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>

    <script>
        let user_id = '{{ $user_id }}';
        let variant_id = "{{ $product_id }}";
        let isCartEdit = 0;
        let isDesignEdit = 0;
        let cart_id = null;
        let design_id = null;

        @if (request()->has('cart') && request()->cart)
            isCartEdit = 1;
            isDesignEdit = 0;
            cart_id = '{{ request()->cart }}';
        @endif

        @if (request()->has('design') && request()->design)
            isDesignEdit = 1;
            isCartEdit = 0;
            design_id = '{{ request()->design }}';
        @endif
    </script>
    <script>
        const deleteobject = new Image();
        deleteobject.src = '{{ asset('tools') }}/assets/images/remove.png';
        const rotateobjects = new Image();
        rotateobjects.src = '{{ asset('tools') }}/assets/images/rotate.svg';
        const resizeobject = new Image();
        resizeobject.src = '{{ asset('tools') }}/assets/images/resize.png';
    </script>
    <script src="{{ asset('tools') }}/fabric.js"></script>
    <script>
        let isEnableToRedirct = 0;
        window.onbeforeunload = function(event) {
            if (!isEnableToRedirct) {
                return false;
            }
        };
    </script>
    <style>
        .form-check .text-danger{
            margin-left:-20px;
        }
    </style>
</head>

<body onload="myFunction()">
    <!-- Loader Start-->
    <div class="loader_anim_wrap" id="loaderStartEnd"
        style="
    display: flex;
    align-items: center;
    justify-content: center;
">
        <div class="loader_anim_box" style="
    display: flex;
    align-items: center;
    justify-content: center;
">
            <div class="loader_anim"></div>
        </div>
    </div>
    <!-- Loader End-->
    <div class="cd_wrapper header-content">
        <div class="cd_progress_block">
            <div class="cd_container">
                <div class="content-wrapper">
                    <button class="hamburger" type="button">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('front.index') }}"><img class="nav-logo lazy"
                            src="{{ asset('assets/images/' . $gs->logo) }}" alt="Image not found !"></a>
                    <div class="question-block">
                        <strong>Have questions? Need Help</strong>
                        <div class="contact-info">
                            <a href="tel:9059014722">905-901-4722</a> |
                            <a href="mailto:management@swimcapz.com">management@swimcapz.com</a>
                        </div>
                    </div>
                </div>
                <div class="heading-wrap">
                    <h2>Custom Your Swim Cap</h2>
                    <!-- <span>My Saved Designs</span> -->
                </div>
                <!-- navbar-start -->
                <nav class="navbar">
                    <i class="close-btn" aria-hidden="true"></i>
                    <!-- <button class="close-btn" onclick="closepopup()">
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </button> -->
                    <!-- <div class="navbar"> -->
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->path() == '/' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('front.index') }}">{{ __('HOME') }}</a>
                        </li>

                        <li class="nav-item  my-account-dropdown">
                            <a class="nav-link has-dropdown d-flex align-items-center text-dark text-decoration-none"
                                href="{{ url('category') }}">{{ __('PRODUCTS') }}</a>
                            <i class="arrow-up" aria-hidden="true"></i>
                            <div class="left-product">
                                <ul>
                                    <li
                                        class="list cat-item cat-parent {{ Request::url() == url('/category') ? 'active' : '' }}">
                                        <a href="{{ url('/category') }}"><strong>All Categories</strong></a>
                                    </li>

                                    @foreach (Helper::categories() as $category)
                                        <li
                                            class="list cat-item cat-parent
                       {{ Request::url() == route('front.category', $category->slug) ? 'active' : '' }}">
                                            <a href="{{ route('front.category', $category->slug) }}
                           {{ !empty(request()->input('search')) ? '?search=' . request()->input('search') : '' }}"
                                                class="category-link" id="cat">{{ $category->name }} <span
                                                    class="count"></span></a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </li>

                        <li class="nav-item {{ request()->path() == 'blog' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('category') }}">{{ __('DESIGN NOW') }}</a>
                        </li>
                    </ul>
                    <!-- </div> -->
                </nav>



                <!-- navbar-end -->

                <div class="cd_wrap">
                    <div class="cd_left_block">
                        <div class="cd_profile_box">
                            <div class="cd_btn_wrap">
                                <div id="saveDesignOpenPopup">
                                    @if (Auth::user() != null)
                                        @php
                                            $user_id = Auth::user() ? Auth::user()->id : null;
                                        @endphp
                                        <button class="cd_save_btn disable-btn" type="button"
                                            onclick="saveDesignOpenPopup()">
                                            <i class="cd_icon-file"></i>
                                            Save This Design
                                        </button>
                                    @else
                                        <button type="button" class="cd_save_btn disable-btn" data-toggle="modal"
                                            data-target="#loginmodel">
                                            <i class="cd_icon-file"></i>
                                            Save This Design
                                        </button>
                                    @endif
                                </div>

                                <div class="popup-design" id="saveDesignPopup">
                                    <div class="popup_design_wrap">
                                        <h2>Save Design</h2>
                                        <p>Name your design so that you can easily refer to it later.
                                        </p>
                                        <div class="popup_design_form">
                                            <label for="txtdesign">Design Name</label>
                                            <input type="text" name="Design Name" id="txtdesign" class="form-control"
                                                onkeypress="return lettersValidate(event)">
                                            <span id="nameError"></span>
                                        </div>
                                        <span class="note">Note: special characters are not accepted for design
                                            names.</span>
                                        <div class="form-buttons">
                                            <button type="button" class="btn btn_cancel"
                                                onclick="saveDesignClosePopup()">Cancel</button>
                                            <button type="button" class="btn save-design-btn"
                                                onclick="saveDesign(0)" data-action="0">Save Design</button>
                                        </div>

                                    </div>
                                </div>
                                <div class="popup-design design-overwrite" id="nameOverridePopup">
                                    <div class="popup_design_wrap">
                                        <h2>Design Name Already in Use.</h2>
                                        <span class="note" id="noteMessage"></span>
                                        <div class="form-buttons">
                                            <button type="button" class="btn btn_cancel"
                                                onclick="saveDesign(2)">EDITNAME</button>
                                            <button type="button" class="btn" onclick="saveDesign(1)">OVERWRITE
                                                DESIGN</button>
                                        </div>

                                    </div>
                                </div>
                                <div class="popup-design design-saved-message" id="displayMessagePopup">
                                    <div class="popup_design_wrap">
                                        <span id="designSavedMessage"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="cd_profile_wrap" id="back-ground-img">
                                <img style="opacity:0" src="{{ asset('tools') }}/assets/images/circle-box.svg"
                                    class="select-captype-img" alt="circle-box">
                            </div>
                            <button class="cd_resize_btn">
                                <i class="cd_icon-resize"></i>
                            </button>
                            <div class="cd_button_wrapper">
                                <div class="cd_border_wrap">
                                    <button class="cd_border_btn" type="button" id="undo"
                                        onclick="undoRedoFunctionality('undo')">
                                        <i class="icon_undo"></i>
                                        Undo
                                    </button>
                                    <button class="cd_border_btn" type="button" id="redo"
                                        onclick="undoRedoFunctionality('redo')">
                                        <i class="icon_redo"></i>
                                        Redo
                                    </button>
                                </div>
                                <div class="cd_bg_wrap">
                                    <button class="cd_function_btn popup_side" type="button">
                                        <i class="icon_side"></i>
                                        Side
                                    </button>
                                    <button class="cd_function_btn" type="button" onclick="clear()">
                                        <i class="icon_clear"></i>
                                        Clear
                                    </button>
                                    <button class="cd_function_btn" type="button">
                                        <i class="icon_select"></i>
                                        Select All
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="cd_profile_info">
                            <span class="cd_profile_type">Front View</span>
                            <button class="cd_change_side popup_change_product_side_open">
                                <i class="cd_icon_change"></i>
                                Change Product side
                            </button>
                            <div class="cd_profile_check_wrap">
                                <input type="checkbox" id="cd_profile_check">
                                <label for="cd_profile_check">Print the same on both sides</label>
                            </div>
                            <div>
                                <span>
                                    <ul id="backChangeColor">
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="cd_right_block">
                        <form>
                            <div class="cd_category_box cd_active" id="capType">
                                <div class="cd_left">
                                    <div class="cd_category_info">
                                        <span class="cd_category_name">Cap Type</span>
                                        <span class="cd_number">1</span>
                                    </div>
                                </div>
                                <div class="cd_right cd_current">
                                    <div class="cd_dashed">
                                        <label for="First label" class="cd_input_label">Choose Your Cap Type</label>
                                        <div class="select-cap-wrap">
                                            <button
                                                class="select-btn cd_form_select
                                        popup-button popup-button-captype captype-img-btn select-cap"
                                                type="button">
                                                <i class="cap-select-img">
                                                    <img src="{{ asset('tools') }}/assets/images/circle-box.svg"
                                                        class="select-captype-img" alt="circle-box">
                                                </i>
                                                <span class="product-label">Select cap type</span>
                                            </button>
                                            <ul class="captype-listing"></ul>
                                        </div>
                                    </div>
                                    <button class="cd_btn cd_next_btn" type="button"
                                        id="capTypeNextBtn">Next</button>
                                </div>
                            </div>

                            <div class="cd_category_box" id="capColor">
                                <div class="cd_left">
                                    <div class="cd_category_info">
                                        <span class="cd_category_name">Cap Color</span>
                                        <span class="cd_number">2</span>
                                    </div>
                                </div>
                                <div class="cd_right cd_rightblock ">
                                    <div class="cd_dashed choose-color-slider">
                                        <label class="cd_input_label">Choose Your Cap Color</label>
                                        <div class="cd_clr_slider" style="display:flex">
                                        </div>
                                    </div>
                                    <div class="cd_btn_wrap">
                                        <button class="cd_btn cd_back_btn" type="button"
                                            id="capColorBackBtn">back</button>
                                        <button class="cd_btn cd_next_btn" type="button"
                                            id="capColorNextBtn">Next</button>
                                    </div>
                                </div>
                            </div>

                            <div class="cd_category_box" id="designImageTextName">
                                <div class="cd_left">
                                    <div class="cd_category_info">
                                        <span class="cd_category_name">Design</span>
                                        <span class="cd_number">3</span>
                                    </div>
                                </div>
                                <div class="cd_right ">
                                    <div class="cd_popup cd_upload" id="upload_image_with_select_btn">
                                        <div class="back_btn_wrap">
                                            <button type="button" onclick="closeUploadImagePopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M19 12H5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19L5 12L12 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Upload Image
                                            </button>
                                            <button class="cd_cross" id="cd_design5" type="button" id="imageTypeBtn"
                                                onclick="closeUploadImagePopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.39705 4.55379L4.46967 4.46967C4.73594 4.2034 5.1526 4.1792 5.44621 4.39705L5.53033 4.46967L12 10.939L18.4697 4.46967C18.7626 4.17678 19.2374 4.17678 19.5303 4.46967C19.8232 4.76256 19.8232 5.23744 19.5303 5.53033L13.061 12L19.5303 18.4697C19.7966 18.7359 19.8208 19.1526 19.6029 19.4462L19.5303 19.5303C19.2641 19.7966 18.8474 19.8208 18.5538 19.6029L18.4697 19.5303L12 13.061L5.53033 19.5303C5.23744 19.8232 4.76256 19.8232 4.46967 19.5303C4.17678 19.2374 4.17678 18.7626 4.46967 18.4697L10.939 12L4.46967 5.53033C4.2034 5.26406 4.1792 4.8474 4.39705 4.55379Z"
                                                        fill="#707070" />
                                                </svg>
                                            </button>
                                        </div>

                                        <span>When you upload your artwork, you agree to our image upload terms and confirm
                                            that you have the legal right to use the images you upload. <a
                                                href="javascript:void(0)" onclick="moveToTermAndConditions()">View
                                                Terms</a></span>
                                        <div class="cd_dashed">
                                            <div class="cd_file_type">
                                                <div class="cd_left_block">
                                                    <h6>Recommended file formats:</h6>
                                                    <span class="cd_file"> JPG, PNG</span>
                                                </div>
                                                <span class="cd_file_size">(Maximum file size 25 MB)</span>
                                            </div>
                                            <div class="cd_select_file cd_select_file_img">
                                                <span class="cd_drop_text">Drag and drop file here</span>
                                                <input type="file" id="cd_select" name="cd_select">
                                                <label for="cd_select">Select File</label>
                                                <div id="error"></div>
                                                <div id="image-display"></div>
                                            </div>
                                            <span><strong class="cd_note">Note: </strong>The artwork you upload is safe,
                                                secure, and remains your property.</span>
                                        </div>
                                        <!-- <p style="margin-bottom: 5px;">Returning customer?</p>
                                <p>Start from one of your previous designs or uploaded images by viewing your
                                    saved art. </p>
                                <button class="cd_btn saved_art" type="button" onclick="mySavedDesign()">MY
                                    SAVED ART</button> -->
                                    </div>
                                    <div class="cd_popup cd_text" id="add_text_with_input_field">
                                        <div class="back_btn_wrap">
                                            <button type="button" onclick="closeAddTextPopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M19 12H5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19L5 12L12 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <!-- Upload Image -->
                                                Edit Text
                                            </button>
                                            <button class="cd_cross" id="cd_design1" type="button"
                                                onclick="closeAddTextPopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.39705 4.55379L4.46967 4.46967C4.73594 4.2034 5.1526 4.1792 5.44621 4.39705L5.53033 4.46967L12 10.939L18.4697 4.46967C18.7626 4.17678 19.2374 4.17678 19.5303 4.46967C19.8232 4.76256 19.8232 5.23744 19.5303 5.53033L13.061 12L19.5303 18.4697C19.7966 18.7359 19.8208 19.1526 19.6029 19.4462L19.5303 19.5303C19.2641 19.7966 18.8474 19.8208 18.5538 19.6029L18.4697 19.5303L12 13.061L5.53033 19.5303C5.23744 19.8232 4.76256 19.8232 4.46967 19.5303C4.17678 19.2374 4.17678 18.7626 4.46967 18.4697L10.939 12L4.46967 5.53033C4.2034 5.26406 4.1792 4.8474 4.39705 4.55379Z"
                                                        fill="#707070" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="cd_select_box">
                                            <input type="text" id="textInput" placeholder="New Text"
                                                oninput="textFunction()">
                                            <!-- <select class="cd_form_select font-size-select">
                                        <option value="">Select Font</option>
                                        <option value="Pacifico">Pacifico</option>
                                        <option value="Helvetica">Helvetica</option>
                                        <option value="Arial">Arial</option>
                                        <option value="Verdana">Verdana</option>
                                        <option value="serif">serif</option>
                                        <option value="sans-serif">sans-serif</option>
                                        <option value="Courier New">Courier New</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                    </select> -->
                                            <!-- <div class="arctype-block">
                                                <button
                                                    class="select-btn cd_form_select popup-button popup-button-font-family"
                                                    type="button">Select Font Family
                                                </button>
                                                <ul class="select-font-wrapper font-family-ulli"
                                                    style="display: none;">
                                                    <li>
                                                <a href="javascript:void(0)" class="select-img-wrap">
                                                    <div class="arctype-img">
                                                        <img src="{{ asset('tools') }}/assets/images/camel.png"
                                                            alt="circle-box">
                                                    </div>
                                                    <div class="arctypeimg-name">
                                                        <span>camel</span>
                                                    </div>
                                                </a>
                                            </li>
                                                </ul>
                                            </div>
                                            <div class="cd_select_wrap">
                                                <div class="cd_color_range">
                                                    <h4>Choose Color:</h4>
                                                    <input type="color" value="#e0ffee" id="colorPicker"
                                                        value="Choose a color:">
                                                </div>
                                                <div class="cd_color_range"> -->
                                            <div class="arctype-block">
                                                <button
                                                    class="select-btn cd_form_select popup-button popup-button-font-family"
                                                    type="button">Select Font Family
                                                </button>
                                                <ul class="select-font-wrapper font-family-ulli"
                                                    style="display: none;">
                                                </ul>
                                            </div>
                                            <div class="cd_select_wrap">
                                                <div class="cd_color_range">
                                                    <h4>Choose Color:</h4>
                                                    <input type="color" value="#e0ffee" id="colorPicker"
                                                        value="Choose a color:">
                                                </div>
                                                <div class="cd_color_range">
                                                    <h4>Outline-Color:</h4>
                                                    <input type="color" class="outline-color" value="#f5f5f5"
                                                        id="outlineColorPicker">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cd_range_wrap">
                                            <div class="cd_range">
                                                <div class="cd_range_counter">
                                                    <h4>Letter Spacing</h4>
                                                    <output id="rangevalue">0</output>
                                                </div>
                                                <input type="range" id="range" class="cd_range_input"
                                                    value="0" min="0" max="40"
                                                    oninput="rangevalue.value=value" />
                                            </div>
                                            <div class="cd_range">
                                                <!-- <div class="color-wrap">
                                                
                                            </div> -->
                                                <div class="cd_range_counter">
                                                    <h4>Text Outline</h4>
                                                    <output id="rangevalue2">
                                                        <span class="cd_bar"></span>
                                                    </output>
                                                </div>
                                                <input type="range" id="range2" class="cd_range_input"
                                                    value="0" min="0" max="40"
                                                    oninput="rangevalue2.value=value" />
                                            </div>
                                        </div>
                                        <div class="arctype-block">
                                            <button id="arcName"
                                                class="select-btn cd_form_select popup-button popup-button-arctype"
                                                type="button">Select Arc Type
                                            </button>
                                            <ul class="arctype-listing">
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Bulge')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Bulge.png"
                                                                alt="circle-box">
                                                        </div>
                                                        <div class="arctypeimg-name">
                                                            <span>Bulge</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Curve')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Curve.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Curve</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Arch')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Arch.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Arch</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Wedge')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Wedge.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Wedge</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Diagonal')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Diagonal.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Diagonal</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Wave')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Wave.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Wave</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Effect')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Fish.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Effect</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Upper')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/camel.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Upper</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Roof')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Roof.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Roof</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Bridge')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Bridge.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Bridge</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Hwedge')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Wedge.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Hwedge</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Slope')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/IText.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Slope</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'IText')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Slop.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>IText</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'SlopeIText')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/SlopTest.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>SlopeIText</span>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="arctypeimg-wrap"
                                                        onclick="changeArcValueEffect(this,'Test')">
                                                        <div class="arctype-img">
                                                            <img src="{{ asset('tools') }}/assets/images/Test.png"
                                                                alt="circle-box">
                                                        </div>

                                                        <div class="arctypeimg-name">
                                                            <span>Test</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="cd_actions_block" id="all_actions_two">
                                            <h4>Actions</h4>
                                            <div class="cd_actions_listing" id="image_actions_two">
                                                <button class="cd_action_btn" type="button" title="Flip Horizontal">
                                                    <i class="icon_vertical_mirror"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" title="Flip Vertical">
                                                    <i class="icon_horizontal_mirror"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button"
                                                    title="Center Horizontal">
                                                    <i class="icon_vertical_arrow"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" title="Center Vertical">
                                                    <i class="icon_horizontal_arrow"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" title="Copy"
                                                    onclick="copy()">
                                                    <i class="icon_copy"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" title="Paste"
                                                    onclick="paste()">
                                                    <i class="icon_paste"></i>
                                                </button>
                                            </div>
                                            <div class="cd_btn_wrap design-btn-wrap">
                                                <!-- <button class="cd_btn cd_back_btn" type="button" id="capColorBackBtn">back</button> -->
                                                <!-- <button class="cd_btn cd_next_btn" type="button" id="capColorNextBtn">Next</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cd_popup cd_saved_art" id="my_saved_Design">
                                        <div class="back_btn_wrap">
                                            <button type="button" onclick="closeMySaveArtPopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M19 12H5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19L5 12L12 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                My saved art
                                            </button>
                                            <button class="cd_cross" id="cd_design2" type="button"
                                                onclick="closeMySaveArtPopup()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.39705 4.55379L4.46967 4.46967C4.73594 4.2034 5.1526 4.1792 5.44621 4.39705L5.53033 4.46967L12 10.939L18.4697 4.46967C18.7626 4.17678 19.2374 4.17678 19.5303 4.46967C19.8232 4.76256 19.8232 5.23744 19.5303 5.53033L13.061 12L19.5303 18.4697C19.7966 18.7359 19.8208 19.1526 19.6029 19.4462L19.5303 19.5303C19.2641 19.7966 18.8474 19.8208 18.5538 19.6029L18.4697 19.5303L12 13.061L5.53033 19.5303C5.23744 19.8232 4.76256 19.8232 4.46967 19.5303C4.17678 19.2374 4.17678 18.7626 4.46967 18.4697L10.939 12L4.46967 5.53033C4.2034 5.26406 4.1792 4.8474 4.39705 4.55379Z"
                                                        fill="#707070" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="tabs">
                                            <ul id="tabs-nav">
                                                <li><a href="#tab1">My Design</a></li>
                                                <li><a href="#tab2">My Image</a></li>
                                            </ul> <!-- END tabs-nav -->
                                            <div id="tabs-content">
                                                <div id="tab1" class="tab-content">
                                                    <div class="cd_select_images_wrap" id="myDesigns">
                                                    </div>
                                                </div>
                                                <div id="tab2" class="tab-content">
                                                    <div class="cd_select_images_wrap" id="myImages">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cd_popup cd_add_art">
                                        <div class="back_btn_wrap">
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M19 12H5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19L5 12L12 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                My saved art
                                            </button>
                                            <button class="cd_cross" id="cd_design3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.39705 4.55379L4.46967 4.46967C4.73594 4.2034 5.1526 4.1792 5.44621 4.39705L5.53033 4.46967L12 10.939L18.4697 4.46967C18.7626 4.17678 19.2374 4.17678 19.5303 4.46967C19.8232 4.76256 19.8232 5.23744 19.5303 5.53033L13.061 12L19.5303 18.4697C19.7966 18.7359 19.8208 19.1526 19.6029 19.4462L19.5303 19.5303C19.2641 19.7966 18.8474 19.8208 18.5538 19.6029L18.4697 19.5303L12 13.061L5.53033 19.5303C5.23744 19.8232 4.76256 19.8232 4.46967 19.5303C4.17678 19.2374 4.17678 18.7626 4.46967 18.4697L10.939 12L4.46967 5.53033C4.2034 5.26406 4.1792 4.8474 4.39705 4.55379Z"
                                                        fill="#707070" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="cd_returning_wrap">
                                            <i class="icon-return">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M3.5 8V6.25C3.5 5.2835 4.2835 4.5 5.25 4.5H8.12868C8.32759 4.5 8.51836 4.57902 8.65901 4.71967L10.1893 6.25L8.65901 7.78033C8.51836 7.92098 8.32759 8 8.12868 8H3.5ZM5.25 3C3.45507 3 2 4.45507 2 6.25V17.75C2 19.5449 3.45507 21 5.25 21H13.1718C13.0562 20.6334 13 20.2541 13 19.875V19.772C13 19.6802 13.0045 19.5895 13.0132 19.5H5.25C4.2835 19.5 3.5 18.7165 3.5 17.75V9.5H8.12868C8.72542 9.5 9.29771 9.26295 9.71967 8.84099L11.5607 7H18.75C19.7165 7 20.5 7.7835 20.5 8.75V11.6273C21.4067 12.2598 22 13.3106 22 14.5V8.75C22 6.95507 20.5449 5.5 18.75 5.5H11.5607L9.71967 3.65901C9.29771 3.23705 8.72542 3 8.12868 3H5.25ZM21 14.5C21 15.8807 19.8807 17 18.5 17C17.1193 17 16 15.8807 16 14.5C16 13.1193 17.1193 12 18.5 12C19.8807 12 21 13.1193 21 14.5ZM23 19.875C23 21.4315 21.7143 23 18.5 23C15.2857 23 14 21.4374 14 19.875V19.772C14 18.7929 14.7937 18 15.7727 18H21.2273C22.2063 18 23 18.793 23 19.772V19.875Z"
                                                        fill="#008751" />
                                                </svg>
                                            </i>
                                            <div class="right_content">
                                                <h3>Returning customer?</h3>
                                                <p>Access your saved designs and previous uploaded images by signing
                                                    into your account.</p>
                                            </div>
                                            <button class="cd_return_btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M10 19L17 12L10 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="cd_popup cd_layering" id="showUploadedImageAndTextInLayer">
                                        <div class="cd_btn_block">
                                            <button class="cd_btn" type="button" onclick="uploadImage()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M12 4.5C9.58676 4.5 7.61684 6.39994 7.50502 8.78512C7.48627 9.18524 7.15641 9.5 6.75585 9.5H6.5C4.84315 9.5 3.5 10.8431 3.5 12.5C3.5 14.1569 4.84315 15.5 6.5 15.5H10.0764C10.0261 15.826 10 16.1599 10 16.5C10 16.6682 10.0064 16.835 10.0189 17H6.5C4.01472 17 2 14.9853 2 12.5C2 10.1564 3.79155 8.23125 6.07981 8.01936C6.54808 5.17188 9.02022 3 12 3C14.9798 3 17.4519 5.17189 17.9202 8.01936C20.2085 8.23125 22 10.1564 22 12.5C22 12.6664 21.991 12.8307 21.9734 12.9925C21.5031 12.2601 20.8898 11.6283 20.1729 11.1365C19.6764 10.165 18.6659 9.5 17.5 9.5H17.2442C16.8436 9.5 16.5137 9.18524 16.495 8.78512C16.3832 6.39994 14.4132 4.5 12 4.5ZM22 16.5C22 19.5376 19.5376 22 16.5 22C13.4624 22 11 19.5376 11 16.5C11 13.4624 13.4624 11 16.5 11C19.5376 11 22 13.4624 22 16.5ZM16 19.5C16 19.7761 16.2239 20 16.5 20C16.7761 20 17 19.7761 17 19.5V14.7071L18.6464 16.3536C18.8417 16.5488 19.1583 16.5488 19.3536 16.3536C19.5488 16.1583 19.5488 15.8417 19.3536 15.6464L16.8536 13.1464C16.6583 12.9512 16.3417 12.9512 16.1464 13.1464L13.6464 15.6464C13.4512 15.8417 13.4512 16.1583 13.6464 16.3536C13.8417 16.5488 14.1583 16.5488 14.3536 16.3536L16 14.7071V19.5Z"
                                                        fill="#707070" />
                                                </svg>
                                                Upload
                                            </button>
                                            <button class="cd_btn" type="button" onclick="addText()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.5 3.75C4.5 3.33579 4.83579 3 5.25 3H18.75C19.1642 3 19.5 3.33579 19.5 3.75V6.75C19.5 7.16421 19.1642 7.5 18.75 7.5C18.3358 7.5 18 7.16421 18 6.75V4.5H12.75V19.5H14.25C14.6642 19.5 15 19.8358 15 20.25C15 20.6642 14.6642 21 14.25 21H9.75C9.33577 21 9 20.6642 9 20.25C9 19.8358 9.33577 19.5 9.75 19.5H11.25V4.5H6V6.75C6 7.16421 5.66421 7.5 5.25 7.5C4.83579 7.5 4.5 7.16421 4.5 6.75V3.75Z"
                                                        fill="#707070" />
                                                </svg>
                                                Add Text
                                            </button>
                                            @if (Auth::user() != null)
                                                @php
                                                    $user_id = Auth::user() ? Auth::user()->id : null;
                                                @endphp
                                                <button class="cd_btn" type="button" onclick="showSavedDesign()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                                                            fill="#707070" />
                                                    </svg>
                                                    My Designs
                                                </button>
                                            @else
                                                <button type="button" class="cd_btn my-design" id="my_design2"
                                                    data-toggle="modal" data-target="#loginmodel">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                                                            fill="#707070" />
                                                    </svg>
                                                    My Designs
                                                </button>
                                            @endif

                                        </div>
                                        <ul class="cd_layer_listing" id="layerListId">
                                        </ul>
                                        <!-- <div class="cd_colors_block" id="image-getting-color-parent">
                                    <h3>Colors In Use (3)</h3>
                                    <ul class="cd_colors_listing" id="image-getting-color">
                                    </ul>
                                </div> -->
                                        <div class="cd_colors_block" id="nonChangableColorWithLayerParent">
                                            <h3 id="nonChangableColorWithLayerLength"></h3>
                                            <ul class="cd_colors_listing" id="nonChangableColorWithLayer">
                                            </ul>
                                        </div>
                                        <!-- <div class="cd_actions_block" id="all_actions_three">
                                    <h4>Actions</h4>
                                    <div class="cd_actions_listing" id="image_actions_three">
                                        <button class="cd_action_btn zz" type="button">
                                            <i class="icon_vertical_mirror"></i>
                                        </button>
                                        <button class="cd_action_btn" type="button">
                                            <i class="icon_horizontal_mirror"></i>
                                        </button>
                                        <button class="cd_action_btn" type="button">
                                            <i class="icon_vertical_arrow"></i>
                                        </button>
                                        <button class="cd_action_btn" type="button">
                                            <i class="icon_horizontal_arrow"></i>
                                        </button>
                                        <button class="cd_action_btn" type="button" onclick="copy()">
                                            <i class="icon_copy"></i>
                                        </button>
                                        <button class="cd_action_btn" type="button" onclick="paste()">
                                            <i class="icon_paste"></i>
                                        </button>
                                    </div>
                                </div> -->
                                        <div class="cd_btn_wrap">
                                            <button class="cd_btn cd_back_btn" type="button"
                                                id="designBackBtn">back</button>
                                            <button class="cd_btn cd_next_btn" type="button"
                                                id="designNextBtn">Next</button>
                                        </div>
                                    </div>
                                    <div class="cd_popup cd_edit_layer" id="editLayerImagePopup">
                                        <div class="back_btn_wrap">
                                            <button onclick="closeEditLayerPopup()" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M19 12H5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12 19L5 12L12 5" stroke="#707070" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Edit Layer
                                            </button>
                                            <button class="cd_cross" id="cd_design4" onclick="closeEditLayerPopup()"
                                                type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M4.39705 4.55379L4.46967 4.46967C4.73594 4.2034 5.1526 4.1792 5.44621 4.39705L5.53033 4.46967L12 10.939L18.4697 4.46967C18.7626 4.17678 19.2374 4.17678 19.5303 4.46967C19.8232 4.76256 19.8232 5.23744 19.5303 5.53033L13.061 12L19.5303 18.4697C19.7966 18.7359 19.8208 19.1526 19.6029 19.4462L19.5303 19.5303C19.2641 19.7966 18.8474 19.8208 18.5538 19.6029L18.4697 19.5303L12 13.061L5.53033 19.5303C5.23744 19.8232 4.76256 19.8232 4.46967 19.5303C4.17678 19.2374 4.17678 18.7626 4.46967 18.4697L10.939 12L4.46967 5.53033C4.2034 5.26406 4.1792 4.8474 4.39705 4.55379Z"
                                                        fill="#707070" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="cd_image_bg" id="editLayerImagePopupImageChild">
                                            <img id="editLayerImage"
                                                src="{{ asset('tools') }}/assets/images/img-1.jpg" alt="image">
                                        </div>
                                        <div class="cd_colors_block">
                                            <h3 id=edit_Image_colors_length>Colors In Use (3)</h3>
                                            <ul class="cd_colors_listing" id="edit_Image_colors">
                                            </ul>
                                        </div>
                                        <div class="cd_actions_block" id="all_actions_one">
                                            <h4>Actions</h4>
                                            <div class="cd_actions_listing" id="image_actions_one">
                                                <button class="cd_action_btn" type="button">
                                                    <i class="icon_vertical_mirror"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button">
                                                    <i class="icon_horizontal_mirror"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button">
                                                    <i class="icon_vertical_arrow"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button">
                                                    <i class="icon_horizontal_arrow"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" onclick="copy()">
                                                    <i class="icon_copy"></i>
                                                </button>
                                                <button class="cd_action_btn" type="button" onclick="paste()">
                                                    <i class="icon_paste"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cd_btn_block" id="imageTextArtBtns">
                                        <button class="cd_btn" type="button" onclick="uploadImage()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 4.5C9.58676 4.5 7.61684 6.39994 7.50502 8.78512C7.48627 9.18524 7.15641 9.5 6.75585 9.5H6.5C4.84315 9.5 3.5 10.8431 3.5 12.5C3.5 14.1569 4.84315 15.5 6.5 15.5H10.0764C10.0261 15.826 10 16.1599 10 16.5C10 16.6682 10.0064 16.835 10.0189 17H6.5C4.01472 17 2 14.9853 2 12.5C2 10.1564 3.79155 8.23125 6.07981 8.01936C6.54808 5.17188 9.02022 3 12 3C14.9798 3 17.4519 5.17189 17.9202 8.01936C20.2085 8.23125 22 10.1564 22 12.5C22 12.6664 21.991 12.8307 21.9734 12.9925C21.5031 12.2601 20.8898 11.6283 20.1729 11.1365C19.6764 10.165 18.6659 9.5 17.5 9.5H17.2442C16.8436 9.5 16.5137 9.18524 16.495 8.78512C16.3832 6.39994 14.4132 4.5 12 4.5ZM22 16.5C22 19.5376 19.5376 22 16.5 22C13.4624 22 11 19.5376 11 16.5C11 13.4624 13.4624 11 16.5 11C19.5376 11 22 13.4624 22 16.5ZM16 19.5C16 19.7761 16.2239 20 16.5 20C16.7761 20 17 19.7761 17 19.5V14.7071L18.6464 16.3536C18.8417 16.5488 19.1583 16.5488 19.3536 16.3536C19.5488 16.1583 19.5488 15.8417 19.3536 15.6464L16.8536 13.1464C16.6583 12.9512 16.3417 12.9512 16.1464 13.1464L13.6464 15.6464C13.4512 15.8417 13.4512 16.1583 13.6464 16.3536C13.8417 16.5488 14.1583 16.5488 14.3536 16.3536L16 14.7071V19.5Z"
                                                    fill="#707070" />
                                            </svg>
                                            Upload
                                        </button>
                                        <button class="cd_btn letter-t" type="button" onclick="addText()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M4.5 3.75C4.5 3.33579 4.83579 3 5.25 3H18.75C19.1642 3 19.5 3.33579 19.5 3.75V6.75C19.5 7.16421 19.1642 7.5 18.75 7.5C18.3358 7.5 18 7.16421 18 6.75V4.5H12.75V19.5H14.25C14.6642 19.5 15 19.8358 15 20.25C15 20.6642 14.6642 21 14.25 21H9.75C9.33577 21 9 20.6642 9 20.25C9 19.8358 9.33577 19.5 9.75 19.5H11.25V4.5H6V6.75C6 7.16421 5.66421 7.5 5.25 7.5C4.83579 7.5 4.5 7.16421 4.5 6.75V3.75Z"
                                                    fill="#707070" />
                                            </svg>
                                            Add Text
                                        </button>
                                        @if (Auth::user() != null)
                                            @php
                                                $user_id = Auth::user() ? Auth::user()->id : null;
                                            @endphp
                                            <button class="cd_btn" type="button" onclick="showSavedDesign()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                                                        fill="#707070" />
                                                </svg>
                                                My Designs
                                            </button>
                                        @else
                                            <button type="button" class="cd_btn my-design" id="my_design1"
                                                data-toggle="modal" data-target="#loginmodel">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                                                        fill="#707070" />
                                                </svg>
                                                My Designs
                                            </button>
                                        @endif
                                    </div>
                                    <div id="returning_customer">
                                        <!-- <h3>Returning customer?</h3>
                                <p>Start from one of your previous designs or uploaded images by viewing your saved
                                    art. </p>
                                <button class="cd_btn saved_art" type="button" onclick="mySavedDesign()">MY SAVED ART</button> -->
                                        <div class="cd_btn_wrap">
                                            <button class="cd_btn cd_back_btn" type="button"
                                                id="designSingleBackBtn">back</button>
                                            <!-- <button class="cd_btn cd_next_btn" type="button" id="designNextBtn">Next</button> -->
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="cd_category_box" id="addName">
                                <div class="cd_left">
                                    <div class="cd_category_info">
                                        <span class="cd_category_name">Add Name</span>
                                        <span class="cd_number">4</span>
                                    </div>
                                </div>
                                <div class="cd_right ">
                                    <div class="cd_dashed quantity-wrap">
                                        <label class="cd_input_label">Input Your Name or Upload Your List</label>
                                        <p id="costNamePerCap">Cost: $3.00/name per cap(Printed on both sides)</p>
                                        <div class="cd_clone_wrap" id="isExcelData">
                                            <input name="name" type="text" id="name1" placeholder="name">
                                            <input name="quantity" type="text" id="quantity"
                                                placeholder="quantity" class="cd_quantity">
                                        </div>
                                        <div class="cd_clone_wrap_dynamic"></div>
                                        <button type="button" class="cd_add_more">+ Add more</button>
                                        <h6>
                                            Recommended file formats:
                                            <span class="cd_file">.xlsx, .xls</span>
                                            [ <a href="{{ route('download.name.template') }}" download>Download
                                                Template</a> ]
                                        </h6>
                                        <div class="cd_select_file upload-xsl">
                                            <div>
                                                <form enctype="multipart/form-data">
                                                    <input id="upload" type=file
                                                        accept=".xlsx, .xls, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                        onchange="handleFileSelect(event)">
                                                </form>
                                            </div>
                                            <div id="file-error">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cd_btn_wrap">
                                        <button class="cd_btn cd_back_btn" type="button"
                                            id="nameBackBtn">back</button>
                                        <!-- <button type="button" class="cd_btn cd_next_btn"
                                    id="nameQuantityTypeBtnNext">done</button> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="upload_Design_Popup" id="uploadDesignPopup">
                    <div class="popup_design_wrap">
                        <h2>Replace Design</h2>
                        <p>There can only be one Design per side. Are you sure you want to replace your current design?.
                        </p>
                        <div class="form-buttons">
                            <button type="button" class="btn btn_cancel"
                                onclick="cencelUploadDesign()">Cancel</button>
                            <button type="button" class="btn" onclick="uploadDesignAfterRemoveObjects(0)">Replace
                                Design</button>
                        </div>
                    </div>
                </div>
                <div class="popup-overlay change_color_popup">
                    <div class="popup-content">
                        <div class="popup_content_wrap original-image">
                            <div class="left-block">
                                <img src="" alt="img" title="img" id="original_image_change_color">
                            </div>
                            <div class="right-block">
                                <select class="selectColorForOriginalImage">
                                    <option value="">Color Profile</option>
                                    <option value="1">1 color </option>
                                    <option value="2">2 color </option>
                                    <option value="3">3 color </option>
                                    <option value="4">4 color </option>
                                </select>
                                <div class="image-color-list">
                                </div>
                                <div id="doneButton">
                                    <button type="button" onclick="uploadObjectToCanvas()">Done</button>
                                </div>
                                <div id="doneButtonWithEdit">
                                    <button type="button" onclick="uploadEditObjectToCanvas()">Done</button>
                                </div>
                            </div>
                            <button class="close-button close-btn" type="button" onclick="closepopup()"></button>
                            <!-- <button class="close-btn" onclick="closepopup()">
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button> -->
                        </div>
                    </div>
                </div>
                <div class="popup-overlay select_color_popup">
                    <div class="popup-content" style="max-height:500px;overflow:hidden;">
                        <div class="popup_content_wrap">
                            <div class="color-panel">
                                <!--  Other pane;-->
                            </div>
                            <ul class="color_listing"></ul>
                        </div>
                        <button class="close-button close-btn select_color" type="button"
                            onclick="closeSelectColorPopup()"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas-img" id="canvas-img" width="400" height="400">
            <img src="" />
        </div>
        <div class="popup-overlay popup-change_product_side">
            <div class="popup-content">
                <div class="img-wrap">
                    <div class="popup-img" id="front_view" onclick="showFrontViewInCanvas()">
                        <img src="{{ asset('tools') }}/assets/images/circle-box.svg" alt="convas-img">
                        <div class="img-details">
                            <h3>Front View</h3>
                            <p>This is Circle Box Image</p>
                        </div>
                    </div>
                    <div class="horizontal-line"></div>
                    <div class="popup-img" id="back_view" onclick="showBackViewInCanvas()">
                        <img src="{{ asset('tools') }}/assets/images/circle-box.svg" alt="convas-img">
                        <div class="img-details">
                            <h3>Back View</h3>
                            <p>This is Circle One Image</p>
                        </div>
                    </div>
                </div>
                <button class="close-button popup_change_product_side_close" type="button"></button>
            </div>
        </div>
        <canvas id="dummyCanvas" style="display: none;"></canvas>
        <div class="modal fade manage-model" id="loginmodel" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Sign In</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="text-center">
                        
                        <span id="success" class="text-success"></span>
                    </div>

                    <div class="modal-body">
                        <form class="woocommerce-form-login" id="loginform" method="POST">
                            @csrf
                            <div id="form-message" class="alert" style="display: none;"></div>
                            <div class="form-group manage-form">
                                <label for="username">{{ __('Email address') }}<span
                                        class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" name="email" id="username"
                                    placeholder="{{ __('Enter your email') }}" autocomplete="off">
                                <span id="username_error" class="text-danger"></span>
                            </div>
                            <input type="hidden" name="model_login" value="1">
                            <div class="form-group manage-form pass-input">
                                <label for="password">{{ __('Password') }}<span class="required" style="color: red">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="{{ __('Enter your password') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"
                                            onclick="togglePasswordVisibility('password')"><i
                                                class="fa fa-fw fa-eye-slash toggle-password"></i></span>
                                    </div>
                                </div>
                                <span id="password_error" class="text-danger"></span>
                            </div>
                            <div class="text-start">
                        <span id="messege" class="text-danger "></span>
                        <span id="error" class="text-danger "></span>
                       
                    </div>
                            <input type="hidden" name="modal" value="1">
                            @if (Session::has('auth-modal'))
                                <input type="hidden" name="auth_modal" value="1">
                            @endif
                            <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">

                            <button type="submit" class="btn btn-form black">Sign In</button>
                            <div class="signuo-pop d-flex justify-content-center align-items-center">
                                <p class="m-0">Ready to get started?</p>
                                <button type="button" class="btn-modal-sign" data-toggle="modal"
                                    data-dismiss="modal" id='loginsubmitbtn' data-target="#signupmodel">Sign
                                    Up</button>
                            </div>
                            
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="modal fade manage-model" id="signupmodel" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Sign Up</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="text-center">
                        <span id="messege1" class="text-danger "></span>
                        <span id="error1" class="text-danger "></span>
                        <span id="success1" class="bg-success text-white "></span>
                    </div>
                    <div class="modal-body">
                        <form id="registerform">
                            @csrf
                            <div class="form-group manage-form">
                                <label for="fullname">Name*</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    required placeholder="{{ __('Name') }}">
                                <span id="nameError22" class="text-danger"></span>
                            </div>

                            <div class="form-group manage-form">
                                <label for="email">Email address*</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    required placeholder="{{ __('Email Address') }}">
                                <span id="emailError" class="text-danger"></span>
                            </div>

                            <div class="form-group manage-form">
                                <label for="phone">Phone number*</label>
                                <input type="text" class="form-control only-numeric" name="phone"
                                    id="phone" required placeholder="{{ __('Phone number') }}"
                                    maxlength="10">
                                <span id="phoneError" class="text-danger"></span>
                            </div>

                            <div class="form-group manage-form pass-input">
                                <label for="password1">Password*</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password1" name="password"
                                        required placeholder="{{ __('Password') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"
                                            onclick="togglePasswordVisibility('password1')">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="passcheck" class="text-danger"></span>
                            </div>
                            <input type="hidden" name="model_signup" value="1">
                            <div class="form-group manage-form pass-input">
                                <label for="conpassword">Confirm Password*</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="conpassword"
                                        name="password_confirmation" required
                                        placeholder="{{ __('Confirm Password') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"
                                            onclick="togglePasswordVisibility('conpassword')">
                                            <i class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="conpasscheck" class="text-danger"></span>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="get_updates" name="terms"
                                    value="1">
                                <label class="form-check-label" for="get_updates">
                                    <a class="form-check-text" href="{{ url('terms-condition') }}"
                                        target="_blank">Accept Terms &
                                        Conditions*</a>
                                </label>
                                <span id="get_updatesError" class="text-danger"></span>
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="receive_occasion_news"
                                    name="receive_occasion_news" value="1">
                                <label class="form-check-label" for="receive_occasion_news">
                                    Get occasional news updates and special offers from SwimCapz.com - Custom Swim Caps
                                    Made to Order. Your information will remain private, and you can unsubscribe at any
                                    time
                                </label>
                            </div>
                            <span id="receive_occasion_newsError" class="text-danger"></span>
                            <span id="g-recaptcha-responseError" class="text-danger"></span>
                            @if ($gs->is_capcha == 1)
                                <div class="form-group mb-3">
                                    {!! NoCaptcha::display() !!}
                                    {!! NoCaptcha::renderJs() !!}
                                    @error('g-recaptcha-response')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                            <button type="button" id="submitbtn" class="btn btn-form black" name="register"
                                value="Register">{{ __('Register') }}</button>

                            <div class="signuo-pop d-flex justify-content-center align-items-center">
                                <p class="m-0">Do have any account?</p>
                                <button type="button" class="btn-modal-sign" data-dismiss="modal"
                                    data-toggle="modal" data-target="#loginmodel">Log In</button>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer class="cd_footer">
            <div class="cd_container">
                <div class="left-block">
                    <h3>Long Hair Wrinkle Free Silicone Cap</h3>
                    <div class="cd_cap" id="add_product_color">
                    </div>
                    <div class="btn_clr_wrap">
                        <button class="cd_btn cd_clr_btn" type="button" onclick="selectImageColorInFooter()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 18 18" fill="none">
                                <path
                                    d="M8.74955 0C9.12925 0 9.44315 0.28201 9.49285 0.64808L9.49975 0.74985L9.50095 8H16.7541C17.1683 8 17.5041 8.3358 17.5041 8.75C17.5041 9.1297 17.2219 9.4435 16.8559 9.4932L16.7541 9.5H9.50095L9.50295 16.7491C9.50305 17.1633 9.16735 17.4993 8.75315 17.4993C8.37345 17.4993 8.05955 17.2173 8.00985 16.8512L8.00295 16.7494L8.00095 9.5H0.751953C0.337733 9.5 0.00195312 9.1642 0.00195312 8.75C0.00195312 8.3703 0.284103 8.0565 0.650183 8.0068L0.751953 8H8.00095L7.99975 0.75015C7.99965 0.33594 8.33535 0 8.74955 0Z"
                                    fill="#212121" />
                            </svg>
                            COLORS
                        </button>
                        <div class="btn-clr-box" id="imageColorPopupInFooter">
                            <i class="btn-clr-close" onclick="closeImageColorPopupInFooter()"></i>
                            <div class="show-clr-wrap">
                                <ul class="cd_colors_listing" id="changeImageColorInFooter">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="next-btn-error errors">
                    <!-- alert -->
                </div>

                <div class="cd_btn_wrap" id="submit_next">
                    @if (Auth::user() != null)
                        @php
                            $user_id = Auth::user() ? Auth::user()->id : null;
                        @endphp
                        <button class="cd_btn cd_next_btn" type="button" id="moveToNextPageBtn"
                            onclick="moveToNextPage({{ $user_id }})">Next</button>
                    @else
                        <button type="button" class="cd_btn cd_next_btn" id="moveToNextPageBtn"
                            data-toggle="modal" data-target="#loginmodel">Next</button>
                    @endif
                </div>
            </div>
        </footer>
    </div>
    <script>
        function replaceButton() {
            // Create the new button element
            var newButton1 = document.createElement('button');
            newButton1.className = 'cd_btn';
            newButton1.type = 'button';
            newButton1.onclick = showSavedDesign; // Attach onclick event handler
            newButton1.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none">
            <path d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                  fill="#707070" />
            </svg>
            My Designs
            `;

            var newButton2 = document.createElement('button');
            newButton2.className = 'cd_btn';
            newButton2.type = 'button';
            newButton2.onclick = showSavedDesign; // Attach onclick event handler
            newButton2.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none">
            <path d="M17.75 3C19.5449 3 21 4.45507 21 6.25V17.75C21 19.5449 19.5449 21 17.75 21H6.25C4.45507 21 3 19.5449 3 17.75V6.25C3 4.45507 4.45507 3 6.25 3H17.75ZM18.3305 19.4014L12.5247 13.7148C12.2596 13.4553 11.8501 13.4316 11.5588 13.644L11.4752 13.7148L5.66845 19.4011C5.8504 19.4651 6.04613 19.5 6.25 19.5H17.75C17.9535 19.5 18.1489 19.4653 18.3305 19.4014ZM17.75 4.5H6.25C5.2835 4.5 4.5 5.2835 4.5 6.25V17.75C4.5 17.9584 4.53643 18.1583 4.60326 18.3437L10.4258 12.643C11.2589 11.8273 12.5675 11.7885 13.4458 12.5266L13.5742 12.6431L19.3964 18.3447C19.4634 18.159 19.5 17.9588 19.5 17.75V6.25C19.5 5.2835 18.7165 4.5 17.75 4.5ZM15.2521 6.5C16.4959 6.5 17.5042 7.50831 17.5042 8.75212C17.5042 9.99592 16.4959 11.0042 15.2521 11.0042C14.0083 11.0042 13 9.99592 13 8.75212C13 7.50831 14.0083 6.5 15.2521 6.5ZM15.2521 8C14.8367 8 14.5 8.33673 14.5 8.75212C14.5 9.1675 14.8367 9.50423 15.2521 9.50423C15.6675 9.50423 16.0042 9.1675 16.0042 8.75212C16.0042 8.33673 15.6675 8 15.2521 8Z"
                  fill="#707070" />
            </svg>
            My Designs
            `;

            var submt_btn1 = document.getElementById('my_design1');
            var submt_btn2 = document.getElementById('my_design2');
            submt_btn1.parentNode.replaceChild(newButton1, submt_btn1);
            submt_btn2.parentNode.replaceChild(newButton2, submt_btn2);

        }
    </script>
    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.3/clipboard.min.js"></script>
    <script src="{{ asset('tools') }}/assets/js/custom.js"></script>
    <script src="{{ asset('tools') }}/node_modules/cropperjs/dist/cropper.min.js"></script>
    <script>
        var dummyCanvas = new fabric.Canvas('dummyCanvas');
        let selectCan = [];
        let activeView = 0;
        let query_params_product_id = 0;
        let query_params_isEdit = false;
        let defaultSelectedProduct = '';
        let varients = [];
        let canvas = '';
        let mySavedDesignDataObj = {};
        let selectedProductColorImage = [];
        let finalDesignArr = [];
        let allColorsInRight = [];
        let capTypeIds = [];
        let totalColorsViewWise = [];
        var productDropDownCreatedFlag = true;
        var finalDesignedData = {};
        let imageId = 0
        let currentResponseObject = {};
        let uploadedImageColorAndImageId = [];
        let editvariantProducts = [];
        let isReplaceCanData = {};
        let product_updated = [];
        let designName = "";
        let coordinateArray = {};
        let arrayForShorting = [];
    </script>
    <script src="{{ asset('tools') }}/index.js"></script>
    <script src="{{ asset('tools') }}/arcEffect.js"></script>

    <script src="{{ asset('tools') }}/global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function togglePasswordVisibility(id) {
            var field = document.getElementById(id);
            var icon = field.nextElementSibling.querySelector('.toggle-password');
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
        $(document).ready(function() {
            $('#loginform').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                document.getElementById("loaderStartEnd").style.display = "flex";
                document.getElementById("submitbtn").disabled = true;
                $.ajax({
                    url: "{{ route('user.login.submit') }}", // Your form action URL
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        document.getElementById("loaderStartEnd").style.display = "none";

                        if (response.success) {
                            // Handle successful login (e.g., close the modal and show a success message)
                            var model = document.querySelector('#loginmodel');
                            if (response.message) {
                                $('#messege').text(response.message)
                                    .show(); // Display the message
                                setTimeout(function() {
                                    $('#messege')
                                        .hide(); // Hide the message after 1 second
                                }, 2000);
                            }
                            if (response.success) {
                                $('#success').text("You've successfully signed in")
                                    .show(); // Display the message
                                setTimeout(function() {
                                    $('#success')
                                        .hide(); // Hide the message after 1 second
                                }, 2000);
                            }
                            setTimeout(function() {
                                document.querySelector('#loginmodel .close').click();
                            }, 2000);

                            var submt_btn = document.getElementById('submit_next');
                            user_id = response.user_id;
                            submt_btn.innerHTML =
                                '<button class="cd_btn cd_next_btn" type="button" id="moveToNextPageBtn" onclick="moveToNextPage(' +
                                user_id + ')">Next</button>';

                            var submt_btn22 = document.getElementById('saveDesignOpenPopup');
                            submt_btn22.innerHTML =
                                ' <button class="cd_save_btn disable-btn" type="button" onclick="saveDesignOpenPopup()"><i class="cd_icon-file"></i>Save This Design</button>';

                            replaceButton();

                        } else {
                            document.getElementById("submitbtn").disabled = false;
                            document.getElementById("loaderStartEnd").style.display = "none";
                            if (response.errors) {
                                if (response.errors) {
                                    if (response.errors.email) {
                                        $('#username_error').text(response.errors.email)
                                            .show(); // Display the message
                                    } else if (response.errors.password) {
                                        $('#password_error').text(response.errors.password)
                                            .show(); // Display the message
                                    } else {
                                        $('#messege').text(response.errors)
                                            .show(); // Display the message
                                    }
                                    setTimeout(function() {
                                        $('#username_error')
                                            .hide(); // Hide the message after 4 second
                                        $('#password_error')
                                            .hide(); // Hide the message after 4 second
                                        $('#messege')
                                            .hide(); // Hide the message after 4 second
                                    }, 4000);
                                }
                                // Handle login failure (e.g., show an error message)
                            }
                        }
                    },
                    error: function(xhr) {
                        document.getElementById("loaderStartEnd").style.display = "none";
                        // Handle any errors that occurred during the request
                        alert('An error occurred: ' + xhr.responseText);
                        document.getElementById("submitbtn").disabled = false;
                    }
                });
            });


            $('#registerform').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                document.getElementById("loaderStartEnd").style.display = "flex";
                document.getElementById("loginsubmitbtn").disabled = true;
                $.ajax({
                    url: "{{ route('user-register-submit') }}", // Your form action URL
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        document.getElementById("loaderStartEnd").style.display = "none";
                        if (response.success) {
                            // Handle successful login (e.g., close the modal and show a success message)
                            var model = document.querySelector('#signupmodel');
                            if (response.message) {
                                $('#success1').text(response.message)
                                    .show(); // Display the message
                                setTimeout(function() {
                                    $('#success1')
                                        .hide(); // Hide the message after 1 second
                                }, 2000);
                            }
                            setTimeout(function() {
                                document.querySelector('#signupmodel .close').click();
                            }, 2000);
                            if (response.user_id) {
                                var submt_btn = document.getElementById('submit_next');
                                user_id = response.user_id;
                                submt_btn.innerHTML =
                                    '<button class="cd_btn cd_next_btn" type="button" id="moveToNextPageBtn" onclick="moveToNextPage(' +
                                    user_id + ')">Next</button>';
                            }
                        } else {
                            document.getElementById("loaderStartEnd").style.display = "none";

                            document.getElementById("loginsubmitbtn").disabled = false;
                            // Handle login failure (e.g., show an error message)
                            const shownErrors = [];

                            $.each(response.errors, function(key, value) {
                                const errorElementId = '#' + key + 'Error';
                                $(errorElementId).text(value).show();
                                shownErrors.push(
                                errorElementId); // Store the shown error element ID
                            });

                            setTimeout(function() {
                                shownErrors.forEach(function(selector) {
                                    $(selector).hide();
                                });
                            }, 4000);

                        }


                    },
                    error: function(xhr) {
                        document.getElementById("loaderStartEnd").style.display = "none";
                        document.getElementById("loginsubmitbtn").disabled = false;
                        console.log(xhr);
                        // Handle any errors that occurred during the request
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        $("#passcheck").hide();
        let passwordError = true;
        $("#password1").keyup(function() {
            validatePassword();
        });

        function validatePassword() {
            let passwordValue = $("#password1").val();
            if (passwordValue.length == "") {
                $("#passcheck").show();
                $("#passcheck").html("Please enter a password");
                $("#passcheck").css("color", "red");
                passwordError = false;
                return false;
            }
            if (passwordValue.length < 8) {
                $("#passcheck").show();
                $("#passcheck").html(
                    "your password contains at least 8 characters."
                );
                $("#passcheck").css("color", "red");
                passwordError = false;
                return false;
            }

            // New password complexity checks
            let hasUppercase = /[A-Z]/.test(passwordValue);
            let hasLowercase = /[a-z]/.test(passwordValue);
            let hasNumbers = /[0-9]/.test(passwordValue);
            let hasSpecialChars = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(passwordValue);

            if (!(hasUppercase && hasLowercase && hasNumbers && hasSpecialChars)) {
                $("#passcheck").show();
                $("#passcheck").html("Your password is weak.<br>1.your password contains both upper and lower case letters symbols like @, _, #, * and numbers.");
                $("#passcheck").css("color", "red");
                passwordError = false;
                return false;
            } else {
                $("#passcheck").hide();
                passwordError = true;
                return passwordError;
            }
        }

        // Validate Confirm Password
        $("#conpasscheck").hide();
        let confirmPasswordError = true;
        $("#conpassword").keyup(function() {
            validateConfirmPassword();
        });

        function validateConfirmPassword() {
            let confirmPasswordValue = $("#conpassword").val();
            let passwordValue = $("#password1").val();
            if (passwordValue != confirmPasswordValue) {
                $("#conpasscheck").show();
                $("#conpasscheck").html("Passwords do not match. Please enter the same password in both fields");
                $("#conpasscheck").css("color", "red");
                confirmPasswordError = false;
                return false;
            } else {
                $("#conpasscheck").hide();
                confirmPasswordError = true;
                return confirmPasswordError;
            }
        }

        //Submit button
        $("#submitbtn").click(function() {
            var passwordError = validatePassword();
            var confirmPasswordError = validateConfirmPassword();
            var name = $("#name").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var nameError = false;
            var emailError = false;
            var phoneError = false;
            var termsChecked = $("#get_updates").prop("checked");

            // Validate name
            if (name.trim() === "") {
                $("#nameError22").text("Name is required");
                //nameError = true;
            } else {
                $("#nameError22").text("");
            }
            setTimeout(() => {
                $("#nameError22").text("");
            }, 3000);

            // Validate email
            if (email.trim() === "") {
                $("#emailError").text("Email is required");
                //emailError = true;
            } else if (!isValidEmail(email)) {
                $("#emailError").text("Please enter a valid email address.");
                //emailError = true;
            } else {
                $("#emailError").text("");
            }
            setTimeout(() => {
                $("#emailError").text("");
            }, 3000);
            const phoneRegex = /^\d{0,11}$/;

            // Validate phone
            if (phone.trim() === "") {
                $("#phoneError").text("Phone no. is required");
                // phoneError = true;
            } else if (!phone.match(phoneRegex)) {
                $("#phoneError").text("Phone no. can have a maximum of 11 digits");
                // phoneError = true;
            } else {
                $("#phoneError").text("");
            }
            setTimeout(() => {
                $("#phoneError").text("");
            }, 3000);

            // Validate terms and conditions checkbox
            if (!termsChecked) {
                $("#get_updatesError").text("You must accept the terms and conditions to proceed.");
            } else {
                $("#get_updatesError").text("");
            }
            setTimeout(() => {
                $("#get_updatesError").text("");
            }, 3000);
            setTimeout(() => {
                $("#passcheck").text("");
            }, 3000);

            if (passwordError && confirmPasswordError && !nameError && !emailError && !phoneError && termsChecked) {
                $('#submitbtn').attr('type', 'submit');
            } else {
                $('#submitbtn').attr('type', 'button');
            }
        });

        function isValidEmail(email) {
            // Regular expression for basic email validation
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        }


        $("input").on("focus", function() {
            $(".error").html("");
        });

        $("#phone").keydown(function(event) {
            let _text = $(this).val();

            if (event.keyCode == 46 || event.keyCode == 8) {
                // Allow only backspace and delete
            } else {

                if (event.keyCode < 48 || event.keyCode > 57) {
                    event.preventDefault();
                    // Ensure that it is a text and stop the keypress
                }

                if (_text.length >= 11) {
                    event.preventDefault();
                }
            }
        });
    </script>
    <script>
        function togglePasswordVisibility(id) {
            var element = document.getElementById(id);
            var icon = element.nextElementSibling.querySelector('i');
            if (element.type === "password") {
                element.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                element.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>

</html>
