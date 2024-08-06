let mySavedDesignData = [];
let backgroundData = [];
let totalVarientData = [];
let updatedProducts = [];
let variantsInDetailsPage = [];
let selectedProductColorArrayInDetailsPage = [];
let result = [];
let dataForTotalPrice = [];
let dataForAddToCart = [];
let qty = 1;
let isPrintBothSide = false;

function startMyDetails() {
    document.getElementsByClassName("alert-panel")[0].style.display = "none";
    document.getElementById("show-front-back-image-design").style.display = "none";
    getDesignDetails();
}

function getDesignDetails() {
    let url = BASE_API_URL + '/get-temp-page-details/' + user_id;
    fetch(url).then(data => {
        return data.json();
    }).then(post => {
        if (post.status) {
            console.log("get-temp-page-details", post);
            result = post?.data;
            mySavedDesignData = post?.data?.design;
            isPrintBothSide = post?.data?.printBothSide;
            backgroundData = post?.data?.backgroundImages;
            getVarientDetails(post?.data?.variant_id);
        }
    });
}

function getVarientDetails(data) {
    fetch(BASE_URL + '/get-variant-details?variant_id=' + data).then(data => {
        return data.json();
    }).then(post => {
        if (post.status) {
            totalVarientData = post.data;
            setCanvasData(post.data);
        }
    });
}

function setCanvasData(data) {
    selectedProductColorArrayInDetailsPage.push(data[0].id);
    let canvasHtml = '';
    data?.forEach((ele) => {
        let current = JSON.stringify(ele)
        let product=ele.product_id + '_' +ele.id;
        if (mySavedDesignData[0].design) {
            canvasHtml += `
            <div class="quantity-ds-card">
                <div class="cd_left_block">
                    <div class="color-wrap">
                        <h2>${ele.product_name}</h2>
                        <input
                            type="radio"
                            class="input-colors"
                            style="background-color:${ele.color_code}"
                            name="cap_color"
                        />
                    </div>
                    <div class="alert-panel" id=alertPanel>
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <p><strong>Style Minimum:</strong>To purchase this item, your order must include at least ${ele.minimum_order} of this color (sizes can vary).</p>
                    </div>
                    <div class="cd_product_quantity" id="cd_product_qty">
                        1 items
                    </div>
            
                    <div class="cd_add_quantity">
                       <div>
                       <span style="font-weight:bold;">Size :</span>
                       <span class="cd_text">${ele.size}</span></div>
                        <div style="display:flex;align-items:center;">
                        <span style="font-weight:bold;margin-right:5px;display:block;">Qty :</span>
                        <div class="cd_quantity_wrap">
                        <button class="cd_remove" id="minus_qty" onclick="addQuantity('minus')">-</button>
                        <input
                            type="text"
                            class="cd_input_value product-list only-numeric"
                            id="input_qty"
                            oninput="inputQuantity(event)"
                            value="1"
                            max="4"
                            data-product="${product}"
                        />
                        <button class="cd_add" id="plus_qty" onclick="addQuantity('plus')">+</button>
                    </div>
                        </div>
                    </div>
                </div>
                <div class="cd_right_block">
                    <div class="image-wrapper parent-wrapper">
                        <img src=${mySavedDesignData[0] != undefined ? mySavedDesignData[0].designWithImage : backgroundData[0].imageWithoutDesign} alt="frontImage">
                    </div>
                    <button class="cd_magnifire_btn" onclick='showBothSideDesign(${JSON.stringify(current)})'><i class="fa-solid fa-magnifying-glass-plus"></i></button>
                </div>
            </div>`;
        }
    });
    document.getElementById("set-image-design").innerHTML = canvasHtml;
    document.getElementById("loaderStartEnd").style.display = "none";
    updatedProducts = data;
    
    /* modify cart qty */
    if (isCartEdit) {
		modifyCartQty();
	} 
}

function modifyCartQty()
{
    document.getElementById("loaderStartEnd").style.display = "flex";
    let url=BASE_API_URL +'/getCart/' + cart_id
    $.get(url, function(response) {
        let product=response.result;
        $.each(response.result.details, function(index, item) {
            $( ".product-list" ).each(function() {
                let obj=$(this);
                let dataKey=($(this).attr('data-product')).split("_");
                let product_id=dataKey[0];
                let variant_id=dataKey[1];

                if (product_id == product.product_id && variant_id == item.product_variant_id) {
                    obj.val();
                    obj.attr('value', item.total_qty)
                        .trigger('change');
                    qty=item.total_qty;

                    if (qty >= parseInt(updatedProducts[0].minimum_order)) {
                        document.getElementById("alertPanel").style.display = "none";
                    } else {
                        document.getElementById("alertPanel").style.display = "block";
                    }
                }
                document.getElementById("loaderStartEnd").style.display = "none";
            });
            document.getElementById("loaderStartEnd").style.display = "none";
        });
        document.getElementById("loaderStartEnd").style.display = "none";
    });
}

function showBothSideDesign() {
    document.getElementById("show-front-back-image-design").style.display = "flex";
    if ((mySavedDesignData[0].designWithImage || mySavedDesignData[1].designWithImage) && !isPrintBothSide) {
        document.getElementById("popup-slide-frontId").innerHTML = `<div class="image-wrapper">
            <div class="front_image_slider">
                <div class="front1" >
                    <img class="first-child" src=${mySavedDesignData[0] != undefined ? mySavedDesignData[0].designWithImage : backgroundData[0].imageWithoutDesign} alt="frontDesign">
                    <h1 style="text-align:center">Front</h1>
                </div>

                <div class="back1"> 
                    <img class="first-child" src=${mySavedDesignData[1] != undefined ? mySavedDesignData[1].designWithImage : backgroundData[1].imageWithoutDesign} alt="backDesign">
                    <h1 style="text-align:center">Back</h1>
                </div>
            </div>
        </div>`;
    }else if(mySavedDesignData[0].designWithImage && isPrintBothSide){
        document.getElementById("popup-slide-frontId").innerHTML = `<div class="image-wrapper">
            <div class="front_image_slider">
                <div class="front1" >
                    <img class="first-child" src=${mySavedDesignData[0] != undefined ? mySavedDesignData[0].designWithImage : backgroundData[0].imageWithoutDesign} alt="frontDesign">
                    <h1 style="text-align:center">Front</h1>
                </div>

                <div class="back1"> 
                    <img class="first-child" src=${mySavedDesignData[0] != undefined ? mySavedDesignData[0].designWithImage : backgroundData[0].imageWithoutDesign} alt="backDesign">
                    <h1 style="text-align:center">Back</h1>
                </div>
            </div>
        </div>`;
    }

    $(".front_image_slider").slick({
        infinite: false,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
    });
}

function checkNumber(_text) {
    isAllow = false;
    let _array = _text.split('.');
    if (_array.length == 2 && _array[1].length >= 2) {
        isAllow = true;
    }
    return isAllow;
}



$(document).on('keydown', '.only-numeric', function(event) {
    let _text = $(this).val();
    let decimalCount = _text.split('.').length - 1;
    if (!(
            (event.keyCode >= 48 && event.keyCode <= 57) ||
            event.keyCode == 38 || event.keyCode == 40 ||
            event.keyCode == 46 || event.keyCode == 8 ||
            event.keyCode == 190 && decimalCount == 0 && _text.indexOf('.') == -1) ||
        event.key == "@" || event.key == "#" ||
        event.key == "!" || event.key == "$" ||
        event.key == "%" || event.key == "&" ||
        event.key == "*" || event.key == "(" ||
        event.key == ")" || event.key == "_" ||
        event.key == "-" || event.key == "+" ||
        event.key == "~" || event.key == "?" ||
        event.key == "!" || event.key == "/" ||
        event.key == "{" || event.key == "}" ||
        event.key == "\\"
    ) {
        event.preventDefault();
    } else {
        let xyz = checkNumber(_text);
        if (event.keyCode != 8 && xyz) {
            event.preventDefault();
        }
    }

    if (parseInt(_text) <= 1) {
        $(this).val(1);
    }

    let maxChar = $(this).attr('max');
    if (maxChar && event.keyCode != 8) {
        if (_text.length >= maxChar) {
            event.preventDefault();
        }
    }
});

function inputQuantity(event) {
    qty = event.target.value;
    document.getElementById("input_qty").value = qty;
    document.getElementById("cd_product_qty").innerHTML = `${qty} items`;
    if (qty >= parseInt(updatedProducts[0].minimum_order)) {
        document.getElementById("alertPanel").style.display = "none";
    } else {
        document.getElementById("alertPanel").style.display = "block";
    }
}


function addQuantity(sign) {
    if (sign == 'plus') {
        qty++;
    }
    if (sign == 'minus' && qty > 1) {
        qty--;
    }

    document.getElementById("input_qty").value = qty;
    document.getElementById("cd_product_qty").innerHTML = `${qty} items`;
    if (qty >= parseInt(updatedProducts[0].minimum_order)) {
        document.getElementById("alertPanel").style.display = "none";
    } else {
        document.getElementById("alertPanel").style.display = "block";
    }
}

/********************************Check get Price functionality*************************/
function getPriceOfDesign() {
    document.getElementById("loaderStartEnd").style.display = "flex";
    let colorAlertMsg = document.getElementsByClassName("alert-panel-msg");
    if (result?.totalColors[0].length > 4 || result?.totalColors[1].length > 4) {
        document.getElementsByClassName("alert-panel")[0].style.display = "block";
        document.getElementById("loaderStartEnd").style.display = "none";
        if (colorAlertMsg[0]) {
            colorAlertMsg[0].innerHTML = `<strong>Product Minimum:</strong> Unable to price ${result?.totalColors[0].length} color art, please change the artwork to have 4 colors or fewer.`
        }
    } else {
        getTotalPriceOfAllProduct();
    }
}

function getTotalPriceOfAllProduct() {

    const data = {};
    data['varient_id'] = updatedProducts[0].id;
    data['cap_quantity'] = qty > 1 ? qty : 1;
    data['front_noc'] = result?.totalColors[0].length > 0 ? result?.totalColors[0].length : 0;
    data['back_noc'] = result?.totalColors[1].length > 0 ? result?.totalColors[1].length : 0;
    data['names'] = result?.buyers;
    data['design_name'] = result?.design_name;
    data['product_id'] = updatedProducts[0].product_id;
    data['product_name'] = updatedProducts[0].product_name;
    data['discount_price'] = updatedProducts[0].discount_price;
    data['price'] = updatedProducts[0].price;
    data['background'] = backgroundData;
    data['color_code'] = updatedProducts[0].color_code;
    data['design'] = mySavedDesignData;


    let url = BASE_URL + '/price-calculate';
    fetch(url,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "payloads": [data]
            })
        })
        .then(data => {
            if (!data.ok) {
                throw Error(data.status);
            }
            return data.json();
        }).then(res => {
            document.getElementById("loaderStartEnd").style.display = "none";
            if (res['status']) {
                if (res?.result.length > 0) {
                    dataForAddToCart = res;
                    showCalculatedPrice(res);
                }
            }
        }).catch(e => {
            console.log(e);
        });
}

function showCalculatedPrice(data) {
    document.getElementById("price_popup").style.display = "block";
    document.getElementById("showImageInPricePopup").innerHTML = "";
    document.getElementById("totalEstimation").innerHTML = "";
    let currentData = data;
    if (currentData?.result.length > 0) {
        document.getElementById("loaderStartEnd").style.display = "none";
        currentData?.result?.forEach((ele) => {
            if (mySavedDesignData[0].design) {
                document.getElementById("showImageInPricePopup").innerHTML += `<div class="long-hairs-content">
                    <div class="img-wrapper">
                        <div class="front-img"><img src=${mySavedDesignData[0] != undefined ? mySavedDesignData[0].designWithImage : backgroundData[0].imageWithoutDesign} alt="frontImage"></div>
                    </div>
                    <div class="text-wrap">
                        <div class="contents">
                            <div class="heading-color-wrap">
                            <h3>${ele.product_name}</h3>
                            <input type="radio" class="input-colors" style="background-color:${ele.color_code}" name="cap_color">
                            </div>
                            <table>
                                <tr>
                                    <td> One Size  </td>
                                    <td>(${ele.cap_quantity}) x ${ele.discount_price != null ? ele.discount_price : ele.price} each</td>
                                </tr>
                            </table>
                        </div>
                        <div class="price">
                            <h3>${ele.cap_quantity} item / ${ele.price_with_symbol}</h3>
                        </div>
                    </div>
                </div>`;
            }
        });
    }

    document.getElementById("totalEstimation").innerHTML = `<div class="quantity-wrapper">
        <div class="quantity">
            <span> Total Quantity</span>
            <span class="quantity-num">${currentData?.total_cap_qty}</span>
        </div>
        <div class="total-quantity">
            <div class="total">
                <h3>Estimated Item Subtotal</h3>
                <span>Does not include tax or shipping</span>
            </div>
            <div class="total-price">
            <h3>${currentData?.with_symbol}</h3>
            </div>
        </div>
    </div>`;

}
/********************************Check get Price functionality*************************/

/********************************Remove Perticular Product*****************************/
// function removePerticularProduct(id) {
//     let remove_id = id.split("/");
//     let v_id = parseInt(remove_id[1]);
//     if (selectedProductColorArrayInDetailsPage.length > 1) {
//         selectedProductColorArrayInDetailsPage.splice(selectedProductColorArrayInDetailsPage.indexOf(v_id), 1);
//         getSelectedProduct(selectedProductColorArrayInDetailsPage);
//     }
// }
/********************************Remove Perticular Product*****************************/

/*****************************Start Add Product Color in Details Page*******************/
function selectImageColorInFooterInDetailsPage() {
    document.getElementById('imageColorPopupInFooterInDetailsPage').style.display = "block";
}

function closeImageColorPopupInFooterInDetailsPage() {
    document.getElementById('imageColorPopupInFooterInDetailsPage').style.display = "none";
}
/*****************************End Add Product Color in Details Page********************/

/*************************Start calculating Price of design***************************/
function closePricePopup() {
    document.getElementById("price_popup").style.display = "none";
}

/*************************End calculating Price of design ****************************/


/*******************************Start Go To Design Page*******************************/

function goToDesignPage() {
    let data = {};
    data['user_id'] = result['user_id'];
    data['capTypeData'] = result['capTypeData'];
    data['buyers'] = result['buyers'];
    data['design'] = result['design'];
    data['printBothSide'] = result['printBothSide'];
    data['variant_id'] = selectedProductColorArrayInDetailsPage;
    data['design_name'] = result['design_name'];
    fetch(BASE_URL + '/store-temp-page-details',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "result": data,
                "user_id": user_id ? user_id : 0,
            })
        })
        .then(data => {
            if (!data.ok) {
                throw Error(data.status);
            }
            return data.json();
        }).then(res => {
            if (res.status == true) {
                let url=designPage + `${selectedProductColorArrayInDetailsPage[0]}&isEdit=true`;
                if (isCartEdit) {
                    url +="&cart=" + cart_id;
                } else if(isDesignEdit){
                    url +="&design=" + design_id;
                }

                window.location = url;
            }
        }).catch(e => {
            console.log(e);
        });
}


/*********************************End Go To Design Page*******************************/

/*********************************Start Add To Cart Functionality*********************/

function addToCart() {
    let data = {};
    let dummyArray = [];
    data['user_id'] = user_id ? user_id : 0;
    data['product_id'] = dataForAddToCart ? dataForAddToCart?.result[0]?.product_id : 0;
    data['design_name'] = dataForAddToCart ? dataForAddToCart?.result[0]?.design_name : null;;
    data['noc_front'] = dataForAddToCart ? dataForAddToCart?.result[0]?.front_noc : 0;
    data['noc_back'] = dataForAddToCart ? dataForAddToCart?.result[0]?.back_noc : 0;
    data['front_design'] = dataForAddToCart.result[0]?.design[0] ? dataForAddToCart?.result[0]?.design[0]?.design : 0;
    data['back_design'] = dataForAddToCart.result[0]?.design[1] ? dataForAddToCart?.result[0]?.design[1]?.design : 0;
    data['names'] = dataForAddToCart ? dataForAddToCart?.result[0]?.names : null;
    dataForAddToCart?.result?.forEach((el, index) => {
        let obj = {};
        obj['variant_id'] = el ? el.varient_id : 0;
        obj['total_qty'] = el ? el.cap_quantity : 0;
        obj['total_price'] = el ? el.calculated_price : 0;
        obj['remarks'] = `Hello Testing${index}`;
        obj['front_design'] = dataForAddToCart?.result[0]?.design[0] ? dataForAddToCart?.result[0]?.design[0]?.designWithImage : 0;
        obj['back_design'] = dataForAddToCart?.result[0]?.design[1] ? dataForAddToCart?.result[0]?.design[1]?.designWithImage : 0;
        dummyArray.push(obj);
    });
    data['variant_details'] = dummyArray;
    if (isCartEdit) {
        data['cart_id'] =cart_id;
    }
    
    fetch(BASE_URL + '/add-to-cart',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(data => {
            if (!data.ok) {
                throw Error(data.status);
            }
            return data.json();
        }).then(res => {
            if (res.status == 200) {
                window.location = cartPage
            } else {
                let entries = Object.entries(res.error)
                entries.map(([key, val] = entry) => {
                    document.getElementById("show_errors").innerHTML = `${val[0]}`;
                    alertClear();
                });
            }
        }).catch(e => {
            console.log(e);
        });
}
/*********************************End Add To Cart Functionality***********************/
