$(document).ready(function () {
  $(".hamburger").click(function () {
    $('body').addClass('open-menu');
  })
  $('.navbar .close-btn').click(function () {
    $('body').removeClass('open-menu');
  })

  $('.navbar .nav-item').click(function () {
    $(this).find('.left-product').slideToggle();
  })
});

let selectCapType = document.getElementsByClassName("popup-button-captype");

document.querySelectorAll(".popup_change_product_side_open, .popup_change_product_side_close, .popup_side").forEach((ele) => {
  ele.addEventListener('click', () => {
    let str = 'block';
    if (ele.className.indexOf('close-button') >= 0) {
      str = 'none';
    }
    document.getElementsByClassName('popup-change_product_side')[0].style.display = str;
  })
})

function getProductIdFromURL() {
  const params = window.location.search;
  const searchParams2 = new URLSearchParams(params);
  query_params_product_id = searchParams2.get("product-id");
  query_params_isEdit = searchParams2.get("isEdit");
  getCapTypeData();
  addFontFamily();
};


/********************************Start Get Default Product***********************************/
function getDefaultProduct() {
  activeView = 0;
  defaultSelectedProduct = '';
  let productdropdown = '';
  selectCan = [];
  let imageName = '';
  for (let i = 0; i < capTypeData?.list?.data?.length; i++) {
    if (capTypeData?.list?.data[i]?.variant.length > 0) {
      for (let j = 0; j < capTypeData?.list?.data[i]?.variant.length; j++) {
        if (j === 0 && productDropDownCreatedFlag) {
          productdropdown += `<li>
            <a href="javascript:void(0)" class="captypeimg-wrap" data-id="${capTypeData?.list?.data[i]?.variant[0].id}">
              <div class="captype-img">
                <img id="" src = "${capTypeData?.list?.data[i]?.variant[0].front_image_url}" alt = "circle-box">
              </div>
              <div class="captypeimg-name">
                  <span>${capTypeData?.list?.data[i].name}</span>
              </div>
            </a>
          </li>`;
        }
        if (query_params_product_id) {
          capTypeIds[0] = parseInt(query_params_product_id);
          if (capTypeData?.list?.data[i]?.variant[j].id == query_params_product_id && !defaultSelectedProduct) {
            defaultSelectedProduct = capTypeData?.list?.data[i]?.variant[j];
            imageName = capTypeData.list.data[i].name;
          }
        } else {
          if (capTypeData?.list?.data[i]?.variant[j]?.default == 1 && !defaultSelectedProduct) {
            defaultSelectedProduct = capTypeData?.list?.data[i]?.variant[j];
            imageName = 'Select cap type';
          }
        }
      }
    }
  }

  document.getElementById("loaderStartEnd").style.display = "none";
  document.getElementsByClassName('cd_profile_type')[0].innerHTML = `${activeView == 0 ? 'Front View' : 'Back View'}`;

  let canvasHtml = '';
  if (defaultSelectedProduct.front_image_url) {
    canvasHtml += `<div class="canvas front_canvas">
    <img style="display:none" src="${defaultSelectedProduct.front_image_url}" />
    <canvas id="front_canvas"></canvas>
    </div>`;
  }

  if (defaultSelectedProduct.back_image_url) {
    canvasHtml += `<div class="canvas back_canvas">
    <img style="display:none" src="${defaultSelectedProduct.back_image_url}" />
    <canvas id="back_canvas"></canvas>
    </div>`;
  }

  document.getElementById("back-ground-img").innerHTML = canvasHtml;
  setTimeout(() => {
    createCanvas(document.querySelectorAll('.canvas'), 0);
    document.querySelector(".cap-select-img img").setAttribute('src', defaultSelectedProduct.front_image_url);
    document.getElementById("add_product_color").innerHTML = `<div class="add_product_wrap"><img src=${defaultSelectedProduct.front_image_url} class="select-captype-img"
      id=${defaultSelectedProduct.front_image_url} alt="circle-box"></div>`
    $('.captype-img-btn span').html(imageName);
    if (productDropDownCreatedFlag) {
      document.getElementsByClassName("captype-listing")[0].innerHTML = productdropdown;
      addClickOnproductDropDown();
      if (query_params_isEdit) {
        dataFromDesignDetails(imageName);
      }
    }
    designName = "";
    productDropDownCreatedFlag = false;
  }, 0);

  document.getElementById("costNamePerCap").innerHTML = `Cost: ${defaultSelectedProduct.name_charge_each}/name per cap(Printed on both sides)`
}

/********************************End Get Default Product***********************************/

/*****************************Start Select Cap Type***************************/

function addClickOnproductDropDown() {
  document.querySelectorAll('.captype-listing .captypeimg-wrap').forEach((ele) => {
    ele.addEventListener('click', () => {
      query_params_product_id = ele.getAttribute('data-id');
      selectCan.forEach((ele, ind) => {
        product_updated[ind] = { canvasData: JSON.stringify(ele.canvas) };
      });
      getDefaultProduct();
    })
  })
}

/*****************************End Select Cap Type***************************/

/*********************************** Start Open CapType List functionality*********************/
const isDescendant = (parent, child) => {
  let node = child.parentNode;
  while (node != null) {
    if (node === parent) {
      return true;
    }
    node = node.parentNode;
  }
  return false;
};


window.addEventListener('click', (event) => {
  const captypeListing = document.getElementsByClassName('captype-listing')[0];
  const button = selectCapType[0];

  const isClickOnABC = event.target.classList.contains('product-label');
  if (!isDescendant(captypeListing, event.target) && (event.target !== button && !isClickOnABC)) {
    if (captypeListing) {
      captypeListing.style.display = 'none';
    }
  }
});


selectCapType[0]?.addEventListener('click', () => {
  const captypeListing = document.getElementsByClassName('captype-listing')[0];;
  captypeListing.style.display = (captypeListing.style.display === 'block') ? 'none' : 'block';
});

/**********************************End Open CapType List functionality***************************/


/*****************************Start Next And Back Buttons*******************************/
var capTypeElement = document.getElementById("capType");
var capColorElement = document.getElementById("capColor");
var designElement = document.getElementById("designImageTextName");
var addNameElement = document.getElementById("addName");

var capTypeNextBtn = document.getElementById("capTypeNextBtn");
if (capTypeNextBtn) {
  capTypeNextBtn.addEventListener('click', () => {
    capTypeElement.classList.add('cd_active');
    capTypeElement.children[1].classList.remove("cd_current");
    capColorElement.classList.add('cd_active');
    capColorElement.children[1].classList.add('cd_current');
  })
}

var colorTypeBackBtn = document.getElementById("capColorBackBtn");
if (colorTypeBackBtn) {
  colorTypeBackBtn.addEventListener('click', () => {
    capTypeElement.children[1].classList.add('cd_current');
    capColorElement.children[1].classList.remove('cd_current');
  });
}

var colorTypeNextBtn = document.getElementById("capColorNextBtn");
if (colorTypeNextBtn) {
  colorTypeNextBtn.addEventListener('click', () => {
    capColorElement.classList.add('cd_active');
    capColorElement.children[1].classList.remove('cd_current');
    designElement.classList.add('cd_active');
    designElement.children[1].classList.add('cd_current');
  });
}

var designNextBtn = document.getElementById("designNextBtn");
if (designNextBtn) {
  designNextBtn.addEventListener('click', () => {
    designElement.classList.add('cd_active');
    designElement.children[1].classList.remove('cd_current');
    addNameElement.classList.add('cd_active');
    addNameElement.children[1].classList.add('cd_current');
  })
}

var designBackBtn = document.getElementById("designBackBtn");
if (designBackBtn) {
  designBackBtn.addEventListener('click', () => {
    capColorElement.children[1].classList.add('cd_current');
    designElement.children[1].classList.remove('cd_current');
  })
}

var designSingleBackBtn = document.getElementById("designSingleBackBtn");
if (designSingleBackBtn) {
  designSingleBackBtn.addEventListener('click', () => {
    capColorElement.children[1].classList.add('cd_current');
    designElement.children[1].classList.remove('cd_current');
  })
}

var nameBackBtn = document.getElementById("nameBackBtn");
if (nameBackBtn) {
  nameBackBtn.addEventListener('click', () => {
    designElement.children[1].classList.add('cd_current');
    addNameElement.children[1].classList.remove('cd_current');
  });
}

var finalBack = document.getElementById("colorTypeBtnBack");
if (finalBack) {
  finalBack.addEventListener("click", () => {
    addNameElement.children[1].classList.add('cd_current');
  })
}

/*****************************End Next And Back Buttons*******************************/

const textFunction = () => {
  text_val = document.getElementById("textInput").value;
  designName = "";
  updateGroupText();
  updateView();
  undoRedoObject()
};


/***************************Start Font Family functionality*******************************/
var fontFamilyBtn = document.getElementsByClassName("popup-button-font-family")
var fontFamilyUlli = document.getElementsByClassName("font-family-ulli");

if (fontFamilyBtn[0]) {
  fontFamilyBtn[0].addEventListener('click', (e) => {
    toggleFontFamily();
    e.stopPropagation();
  });
}
function toggleFontFamily() {
  if (fontFamilyUlli) {
    if (fontFamilyUlli[0].style.display === 'block') {
      fontFamilyUlli[0].style.display = 'none';
    } else {
      fontFamilyUlli[0].style.display = 'block';
    }
  }
}


function addFontFamily() {
  fetch(BASE_URL + '/ttf-fonts').then(data => {
    return data.json();
  }).then(post => {
    if (post.status) {
      fontFamilyUlli[0].innerHTML = "";
      post.data?.forEach((item) => {
        fontFamilyUlli[0].innerHTML += `<li>
        <a href="javascript:void(0)" id=${item?.ttf_path} class="select-img-wrap" onclick="chooseFontFamily(this)">
            <div class="arctype-img">
                <img src=${item?.preview_path} alt="circle-box">
            </div>
            
        </a>
    </li>`
      });
    }
  });
}

function chooseFontFamily(target) {
  for (let i = 0; i < document.getElementsByClassName("font-family-ulli")[0].children.length; i++) {
    if (document.getElementsByClassName("font-family-ulli")[0].children[i].querySelector(".arctype-img").classList.contains("font-family-style")) {
      document.getElementsByClassName("font-family-ulli")[0].children[i].querySelector(".arctype-img").classList.remove("font-family-style");
    }
  }
  target.querySelector(".arctype-img").classList.add("font-family-style");
  let fontUrl = target.id;
  let name = target.id;
  const fontFace = new FontFace(name, `url(${fontUrl})`);
  fontFace.load().then(function (loadedFont) {
    document.fonts.add(loadedFont);
    applyFont(name);
  }).catch(function (error) {
    console.error('Failed to load font:', error);
  });
}

function applyFont(fontName) {
  if (canvas.getActiveObject()) {
    font_Family = fontName;
    updateGroupText();
    updateView();
    undoRedoObject()
  }
}
/***************************End Font Family functionality*******************************/

/***************************Start color of text functionality***************************/
const input = document.getElementById("colorPicker");
if (input) {
  input.addEventListener("input", setColor);
}
function setColor() {
  if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {
  } else {
    textColor = input.value;
    updateGroupText();
    updateView();
    undoRedoObject();
  }
}

/***************************End color of text functionality***************************/

/***************************Start letter Spacing of text functionality****************/
var letterSpacing = document.getElementById("range");
if (letterSpacing) {
  letterSpacing.addEventListener('input', (e) => {
    if (canvas.getActiveObject()) {
      txtSpacing = parseInt(e.target.value, 10);
      updateGroupText();
      updateView();
      undoRedoObject()
    }
  })

  letterSpacing.addEventListener('change', (e) => {
    var activeObj = canvas.getActiveObject();
    if (activeObj && ((activeObj['name'] === 'singleLineText') || (activeObj['name'] === 'mutliLineText'))) {
      undoRedoObject();
    }
  })
}
/***************************End letter Spacing of text functionality****************/

/***************************Start Strock Color of text functionality*********************/
const outlineInput = document.getElementById("outlineColorPicker");
if (outlineInput) {
  outlineInput.addEventListener("input", setOutlineColor);
  function setOutlineColor() {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
      strokeColor = outlineInput.value;
      updateGroupText();
      updateView();
      undoRedoObject()
    }
  }
}
/***************************End Strock Color of text functionality*********************/


/***************************Start outLine of text functionality***********************/
var outlet = document.getElementById("range2");
if (outlet) {
  outlet.addEventListener('input', (e) => {
    if (canvas.getActiveObject()) {
      ShowOutline = e.target.value;
      updateGroupText();
      updateView();
      undoRedoObject()
    }
  })
  outlet.addEventListener('change', (e) => {
    var activeObj = canvas.getActiveObject();
    if (activeObj && ((activeObj['name'] === 'singleLineText') || (activeObj['name'] === 'mutliLineText'))) {
      undoRedoObject();
    }
  })
}
/***************************End outLine of text functionality***********************/

/*************************Start Upload Image Text Art Button***********************************/
function uploadImage() {
  document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
  document.getElementById("upload_image_with_select_btn").style.display = "block";
}

function addText() {
  document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
  document.getElementById("add_text_with_input_field").style.display = "block";
  removeTextFields();
}

function showSavedDesign() {
  if (user_id != '') {
    mySavedDesign();
  } else {
    $('.next-btn-error').html(`<div class="row">
      <div class="col-md-12" style="color:red;font-weight:bold;">Please login before proceed.</div>
    </div>`);
    alertClear();
    return;
  }
}

/*************************End Upload Image Text Art Button***********************************/

/*******************************Start Close Popup Image and Text and Art*********************************/
function closeAddTextPopup() {
  if (selectCan[activeView]?.canvas?._objects.length > 0) {
    document.getElementById("upload_image_with_select_btn").style.display = "none";
    document.getElementById("add_text_with_input_field").style.display = "none";
    document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
  } else {
    document.getElementById("add_text_with_input_field").style.display = "none";
  }
  canvas.discardActiveObject();
  canvas.renderAll();
}

function closeUploadImagePopup() {
  if (selectCan[activeView]?.canvas?._objects.length > 0) {
    document.getElementById("upload_image_with_select_btn").style.display = "none";
    document.getElementById("add_text_with_input_field").style.display = "none";
    document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
  } else {
    document.getElementById("upload_image_with_select_btn").style.display = "none";
  }
}

function closeMySaveArtPopup() {
  if (selectCan[activeView]?.canvas?._objects.length > 0) {
    document.getElementById("my_saved_Design").style.display = "none";
    document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
  } else {
    document.getElementById("my_saved_Design").style.display = "none";
  }
}
/***************************************End Close Popup Image and Text and Art***************************/

/****************Drag And Drop File********************/
var uploadButton = document.getElementById("cd_select");
var img_container = document.querySelector(".cd_select_file_img");
var error = document.getElementById("error");
var imageDisplay = document.getElementById("image-display");
var imageContainer = document.createElement("figure");
let imageColorList = document.getElementsByClassName("image-color-list");
var imageName = "";
let uploadedImageUrl = "";
var img1 = "";


/*********************************Start Upload Image From Browser********************************************/
const fileHandler = (file, name, type) => {
  imageName = name;
  if (file == '' && name == "" && type == '') {
    imageContainer.innerHTML = '';
  } else {
    if (type.split("/")[0] !== "image") {
      error.innerText = "Please upload an image file";
      return false;
    } else if (type.split("/")[1] !== "png" || type.split("/")[1] !== "jpg" || type.split("/")[1] !== "pdf") {
      error.innerText = "";
      replacedColor = [];
      let reader = new FileReader();
      reader.onloadend = () => {
        currentResponseObject['original_image'] = reader.result;
        currentResponseObject['imageName'] = name;
        if(reader.result != null){
          showImageInPopup(reader.result, name);
        }
      };
      reader.readAsDataURL(file);
    }
  }
};

/*********************************End Upload Image From Browser********************************************/

/*********************************Start Show Image In Popup************************************************/
function showImageInPopup(src, name) {
  document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "block";
  document.getElementById("original_image_change_color").setAttribute("src", `${src}`);
  imageContainer.innerHTML = `<figcaption>${name}</figcaption>`;
  imageDisplay.appendChild(imageContainer);
  imageColorList[0].innerHTML = '';
  document.getElementsByClassName("selectColorForOriginalImage")[0].value = '';
  document.getElementById("doneButton").style.display = "none";
  document.getElementById("doneButtonWithEdit").style.display = "none";
  $('#doneButton button').attr('data-edit', 0);
}

/*********************************Start Show Image In Popup************************************************/


/**********************************Start Change Number of colors for original image****************************/
let select_number_of_color_for_o_image = document.getElementsByClassName("selectColorForOriginalImage");
if (select_number_of_color_for_o_image[0]) {
  select_number_of_color_for_o_image[0].addEventListener('change', (e) => {
    if (e.target.value > 0) {
      imageContainer.innerHTML = '';
      imageDisplay.appendChild(imageContainer);
      getProcessedImage(currentResponseObject['original_image'], e.target.value,currentResponseObject.imageName);
    }
  })
}

/**********************************End Change Number of colors for original image***************************/

/*******************************************Start Getting top Image Color***********************************/
function getProcessedImage(src, numberOfColor,name) {
  // showOverlayProgress();
  document.getElementById("loaderStartEnd").style.display = "flex";
  fetch('https://sis1110-python.devsparxit.com/ImageChanged/GetApi/', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      "image": src,
      "num_colors": +numberOfColor,
      "imageName": name
    })
  }).then(data => {
    if (!data.ok) {
      throw Error(data.status);
    }
    // hideOverlayProgress();
    document.getElementById("loaderStartEnd").style.display = "none";
    return data.json();
  }).then(res => {
    document.getElementById("loaderStartEnd").style.display = "none";
    if (res[0]?.RealDominantColorsArray.length > 0) {
      document.getElementById("doneButton").style.display = "block";
      document.getElementById("doneButtonWithEdit").style.display = "none";
      showImageColor(res[0]?.DominantColorsFromDB, res[0]?.ImageId);
      currentResponseObject['realDominantColorsArray'] = res[0]?.RealDominantColorsArray;
      currentResponseObject['dominantColorsFromDB'] = res[0]?.DominantColorsFromDB;
      currentResponseObject['imageVecs'] = res[0]?.imageVecs;
      if (res[0]?.Dominant_image) {
        currentResponseObject['dominant_image'] = `${res[0]?.Dominant_image}`;
        currentResponseObject['original_image'] = `${res[0]?.Original_image}`;
        currentResponseObject['ImageId'] = `${res[0]?.ImageId}`;
        document.getElementById("original_image_change_color").setAttribute("src", '');
        document.getElementById("original_image_change_color").setAttribute("src", `${res[0]?.Dominant_image}`);
      } else {
        console.log("Dominant_image error");
      }
    }
    // hideOverlayProgress();
    document.getElementById("loaderStartEnd").style.display = "none";
  }).catch(e => {
    console.log(e);
    // hideOverlayProgress();
    document.getElementById("loaderStartEnd").style.display = "none";
  });

}

/*******************************************End Getting top Image Color***********************************/

/*****************************************Start Show Image Color In Popup*********************************/
function showImageColor(colors_arr, img_id) {
  let allDominantColor = [];
  for (i = 0; i < colors_arr.length; i++) {
    let rgb = "";
    let rgb_arr = colors_arr[i];
    if (rgb_arr != 'transparent') {
      if (rgb_arr && rgb_arr.length > 0) {
        rgb = rgbaToHex(rgb_arr);
      }
    } else {
      rgb = rgb_arr;
    }
    allDominantColor.push(rgb);
  }
  imageColorList[0].innerHTML = '';
  for (let j = 0; j < allDominantColor.length; j++) {
    imageColorList[0].innerHTML += `<div class="image-color m-b-1">
			<div class="circle large m-r-1">
				<span class="color-swatch with-border" style="background-color:${allDominantColor[j]}; border-color:${allDominantColor[j]}; border:1px solid;"></span>
			</div>
			<i class="mat-icon notranslate m-r-1 text-grey-secondary material-icons"></i>
			<button class="select-color-btn" onclick="changeImageColor(${j},'${allDominantColor[j]}','${img_id}',${allDominantColor.length})">
				<input type="button" class="select_image_color" value="${allDominantColor[j]}">Select Color</input>
			</button>
		</div>`
  }
}
/*****************************************End Show Image Color In Popup*********************************/

/******************************************Start Change color In Popup**********************************/
function changeImageColor(ind, target_color, image_id,numColor) {
  let html = `<li>
		<div class="image-color m-b-1">
			<div class="circle large m-r-1">
				<span
					title="Selected"
					class="color-swatch with-border"
					style="background-color: ${target_color}; border-color:black;"
				></span>
			</div>
		</div>
	</li>`;
  $('.popup_content_wrap .color-panel').html(`
		<h4>Current Color :-</h4>
		<ul class="color_selected">${html}</ul><br><hr><br>`);

  document.getElementsByClassName("color_listing")[0].innerHTML = '';
  document.getElementById("loaderStartEnd").style.display = "flex";
  // showOverlayProgress();
  $.each(color_collection, function (i, value) {
    let isTrans = (i == 0) ? 1 : 0;
    let title = "";
    let transparentClass = "";
    let randomColor = "#" + value[0];
    if (isTrans) {
      randomColor = "#ffffff";
      transparentClass = "color-transparent";
      title = "Transparent";
    } else {
      title = value[1];
    }

    document.getElementsByClassName("color_listing")[0].innerHTML += ` <li>
			<div class="image-color m-b-1">
				<div class="circle large m-r-1">
					<span
						title="${title}"
						class="color-swatch with-border ${transparentClass}"
						onclick="replaceImageColor(${ind},${numColor},'${target_color}','${randomColor}','${image_id}', ${isTrans})"
						style="background-color: ${randomColor}; border-color:black;"
					></span>
				</div>
			</div>
		</li>`
  });

  document.getElementsByClassName("select_color_popup")[0].style.display = 'block';
  // hideOverlayProgress();
  document.getElementById("loaderStartEnd").style.display = "none";
}
/******************************************End Change color In Popup**********************************/

/*****************************************Start Select Color*********************************/

function replaceImageColor(ind, numColor,targetColor, selected_color, image_id, isTransparent = 0) {
  document.getElementsByClassName("select_color_popup")[0].style.display = 'none';
  let new_color = hexToRgbaArray(selected_color);
  let target_color = hexToRgbaArray(targetColor);
  let color = new_color.length > 0 ? new_color : [];
  if (isTransparent) {
    color = [0, 0, 0, 0];
  }

  if (typeof image_id === 'string' && image_id.indexOf('_') !== -1) {
    image_id = image_id.split('_')[0];
  }
  document.getElementById("loaderStartEnd").style.display = "flex";
  fetch('https://sis1110-python.devsparxit.com/ImageChanged/GetApiColorChangedData/', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      "ColorIndexValues": ind,
      "ImageId": image_id,
      "targetColor": target_color,
      "newColor": color,
      "imageVecs": currentResponseObject['imageVecs'],
      "NumColor": numColor > 0 ? numColor : 0,
      "base64_string_Original": currentResponseObject['original_image'],
    })
  })
    .then(data => {
      if (!data.ok) {
        throw Error(data.status);
      }
      return data.json();
    }).then(res => {
      document.getElementById("loaderStartEnd").style.display = "none";
      if (res[0]?.DominantColorsFromDB.length > 0) {
        showImageColor(res[0]?.DominantColorsFromDB, res[0]?.ImageId);
        currentResponseObject['realDominantColorsArray'] = res[0]?.RealDominantColorsArray;
        currentResponseObject['dominantColorsFromDB'] = res[0]?.DominantColorsFromDB;
        currentResponseObject['ImageId'] = res[0]?.ImageId;
        if (res[0]?.Dominant_image) {
          currentResponseObject['dominant_image'] = res[0]?.Dominant_image;
          currentResponseObject['original_image'] = res[0]?.Original_image;
          document.getElementById("original_image_change_color").setAttribute("src", '');
          document.getElementById("original_image_change_color").setAttribute("src", `${res[0]?.Dominant_image}`);
        } else {
          console.log("Dominant_image error");
        }
      }
    }).catch(e => {
      console.log(e);
    });
}
/*****************************************End Select Color**********************************/
function setVerticleCenter(img1) {
  let boundingBox = img1.getBoundingRect();
  let visualHeight = parseInt(boundingBox.height);

  let centerY = canvas.getHeight();
  let y = (centerY - visualHeight) / 2;

  img1.set({
    left: img1.left,
    top: y + 30
  });
}

function setHorizontalCenter(img1) {
  let boundingBox = img1.getBoundingRect();
  let visualWidth = parseInt(boundingBox.width);

  let centerX = canvas.getWidth();
  let x = (centerX - visualWidth) / 2;

  img1.set({
    left: x + 30,
    top: img1.top
  });
}
/*****************************************Start Upload Image In Canvas****************************/
function uploadObjectToCanvas() {
  designName = "";
  document.getElementsByClassName("change_color_popup")[0].style.display = "none";
  let isEdit = $('#doneButton button').attr('data-edit');
  if (isEdit == "1") {
    uploadEditObjectToCanvas();
    return;
  }
  if (currentResponseObject['dominant_image'] != '') {
    const canvas = selectCan[activeView].canvas;
    fabric.Image.fromURL(`${currentResponseObject['dominant_image']}`, function (oimg) {
      var dummyratio = maintainRation(oimg['width'], oimg['height'], canvas.getWidth(), canvas.getHeight());
      let imageID = currentResponseObject['ImageId'];
      let image_id = imageID + '_' + imageID;
      img1 = oimg.set({
        image_id: image_id,
        colorCode: currentResponseObject['dominantColorsFromDB'],
        imageVecs: currentResponseObject['imageVecs'],
        width: 150,
        height: 150,
        'scaleX': dummyratio['canRatio'],
        'scaleY': dummyratio['canRatio'],
        'left': ((canvas.getWidth() / canvas.getZoom()) / 2) - (dummyratio['canWidth'] / 2),
        'top': ((canvas.getHeight() / canvas.getZoom()) / 2) - (dummyratio['canHeight'] / 2),
      });
      let pk_id = (img1.image_id).split('_')[0];
      img1.set('pk_id', pk_id);
      canvas
        .add(img1)
        .setActiveObject(img1);

      setVerticleCenter(img1);
      setHorizontalCenter(img1);
      img1.setCoords();

      const imageUrl = canvas.toDataURL();
      selectCan[activeView].imageUrl = imageUrl;

      document
        .querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView]
        .querySelector("img")
        .setAttribute('src', imageUrl);
      canvas.discardActiveObject();
      canvas.renderAll();
      showUploadedImageWithLayering();
      undoRedoObject();
      updateView();
    }, { crossOrigin: 'anonymous' });
    document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "none";
  }
}
/*****************************************End Upload Image In Canvas****************************/
/*************************************Start EditImageWithLayering Function**********************/
async function setActiveObjectById(id) {
  await canvas.forEachObject(function (obj) {
    if (obj.image_id == id) {
      canvas.setActiveObject(obj);
      return;
    }
  });
}

async function editImage(that) {
  let _that = $(that);
  let id = _that.attr('id');
  await setActiveObjectById(id);
  let activeObject = canvas.getActiveObject();
  if (activeObject != null && activeObject != undefined) {
    // $('#editLayerImagePopup').show();
    const imageUrl = activeObject.toDataURL();
    // $('#editLayerImage').attr('src', imageUrl);
    currentResponseObject['original_image'] = imageUrl;
    currentResponseObject['ImageId'] = activeObject.image_id;
    currentResponseObject['imageName'] = '';
    document.getElementById("original_image_change_color").setAttribute("src", '');
    document.getElementById("original_image_change_color").setAttribute("src", imageUrl);
    document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "block";
    document.getElementById("doneButtonWithEdit").style.display = "block";
    document.getElementById("doneButton").style.display = "none";
    $('#doneButton button').attr('data-edit', 1);
    document.getElementsByClassName("selectColorForOriginalImage")[0].value = activeObject.colorCode.length;
    showImageColor(activeObject.colorCode, id);
  }
}

function uploadEditObjectToCanvas() {
  designName = "";
  document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "none";
  document.getElementsByClassName("popup-overlay select_color_popup")[0].style.display = "none";
  let activeObject = canvas.getActiveObject();
  document.getElementById("editLayerImage").setAttribute("src", `${currentResponseObject['dominant_image']}`);
  if (activeObject != null && activeObject != undefined) {
    activeObject.setSrc(`${currentResponseObject['dominant_image']}`, function (img) {
      var dummyratio = maintainRation(img['width'], img['height'], canvas.getWidth(), canvas.getHeight());
      let imageID = currentResponseObject['ImageId'];
      let image_id = imageID + '_' + imageID;
      img1 = img.set({
        image_id: image_id,
        colorCode: currentResponseObject['dominantColorsFromDB'],
        imageVecs: currentResponseObject['imageVecs'],
        'scaleX': img.scaleX,
        'scaleY': img.scaleY,
        'left': ((canvas.getWidth() / canvas.getZoom()) / 2) - (dummyratio['canWidth'] / 2),
        'top': ((canvas.getHeight() / canvas.getZoom()) / 2) - (dummyratio['canHeight'] / 2),
        'height': 150,
        'width': 150,
      });
      setVerticleCenter(img1);
      setHorizontalCenter(img1);
      img1.setCoords();
      canvas.setActiveObject(img);
      const imageUrl = canvas.toDataURL();
      $('li#' + img1.pk_id)
        .find('.layering_dots .cd_image img')
        .attr('src', imageUrl);
      $('li#' + img1.pk_id).attr('data-src', imageUrl);

      selectCan[activeView].imageUrl = imageUrl;
      document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector("img").setAttribute('src', imageUrl);
      // canvas.discardActiveObject(); 
      canvas.renderAll();
      undoRedoObject()
      updateView();
    }, { crossOrigin: 'annonymous' });
  }
}

/******************************************End EditImageWithLayering Function*******************************************/

/*********************************************Start Change Image Color**************************************/

function changeImageColorWhileEditing(ind,target_color) {
  let html = `<li>
		<div class="image-color m-b-1">
			<div class="circle large m-r-1">
				<span
					title="Selected"
					class="color-swatch with-border"
					style="background-color: ${target_color}; border-color:black;"
				></span>
			</div>
		</div>
	</li>`;
  $('.popup_content_wrap .color-panel').html(`
		<h4>Current Color :-</h4>
		<ul class="color_selected">${html}</ul><br><hr><br>`);

  document.getElementsByClassName("color_listing")[0].innerHTML = '';
  document.getElementById("loaderStartEnd").style.display = "flex";
  // showOverlayProgress();
  $.each(color_collection, function (i, value) {
    let isTrans = (i == 0) ? 1 : 0;
    let title = "";
    let transparentClass = "";
    let randomColor = "#" + value[0];
    if (isTrans) {
      randomColor = "#ffffff";
      transparentClass = "color-transparent";
      title = "Transparent";
    } else {
      title = value[1];
    }

    document.getElementsByClassName("color_listing")[0].innerHTML += ` <li>
			<div class="image-color m-b-1">
				<div class="circle large m-r-1">
					<span
						title="${title}"
						class="color-swatch with-border ${transparentClass}"
						onclick="replaceColorWhileEditng(${ind},'${target_color}','${randomColor}',${isTrans})"
						style="background-color: ${randomColor}; border-color:black;"
					></span>
				</div>
			</div>
		</li>`
  });

  document.getElementsByClassName("select_color_popup")[0].style.display = 'block';
  // hideOverlayProgress();
  document.getElementById("loaderStartEnd").style.display = "none";
}

/*********************************************End Change Image Color**************************************/

function replaceColorWhileEditng(ind, targetColor, selected_color, isTransparent = 0) {
  let activeObj = canvas.getActiveObject();
  document.getElementsByClassName("select_color_popup")[0].style.display = 'none';
  let new_color = hexToRgbaArray(selected_color);
  let target_color = hexToRgbaArray(targetColor);
  let color = new_color.length > 0 ? new_color : [];
  if (isTransparent) {
    color = [0, 0, 0, 0];
  }
  let image_id = canvas.getActiveObject().image_id.split('_')[0];
  document.getElementById("loaderStartEnd").style.display = "flex";
  fetch('https://sis1110-python.devsparxit.com/ImageChanged/GetApiColorChangedData/',
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        "ColorIndexValues": ind ? ind : 0,
        "ImageId": image_id ? image_id : 0,
        "targetColor": target_color ? target_color : '',
        "newColor": color,
        "imageVecs":canvas.getActiveObject().imageVecs,
        "NumColor": activeObj.colorCode.length>0?activeObj.colorCode.length:0,
        "base64_string_Original": '',
      })
    })
    .then(data => {
      if (!data.ok) {
        throw Error(data.status);
      }
      return data.json();
    }).then(res => {
      document.getElementById("loaderStartEnd").style.display = "none";
      if (res[0]?.Dominant_image) {
        document.getElementById("editLayerImagePopup").style.display = "block";
        document.getElementById("editLayerImage").setAttribute("src", `${res[0]?.Dominant_image}`);
        showImageInRight(res[0]?.Dominant_image, res[0]?.DominantColorsFromDB, res[0]?.ImageId);
      } else {
        console.log("Dominant_image error");
      }
    }).catch(e => {
      console.log(e);
    });
}

function showImageInRight(src, colors, imgId) {
  let activeObject = canvas.getActiveObject();
  activeObject.setSrc(`${src}`, function (oimg) {
    var dummyratio = maintainRation(oimg['width'], oimg['height'], canvas.getWidth(), canvas.getHeight());
    img1 = oimg.set({
      image_id: imgId,
      colorCode: colors,
      'scaleX': dummyratio['canRatio'],
      'scaleY': dummyratio['canRatio'],
      'left': ((canvas.getWidth() / canvas.getZoom()) / 2) - (dummyratio['canWidth'] / 2),
      'top': ((canvas.getHeight() / canvas.getZoom()) / 2) - (dummyratio['canHeight'] / 2),
      'height': 150,
      'width': 150,
    });
    canvas.setActiveObject(img1);
    setVerticleCenter(img1);
    setHorizontalCenter(img1);
    img1.setCoords();
    const imageUrl = canvas.toDataURL();
    selectCan[activeView].imageUrl = imageUrl;
    document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector("img").setAttribute('src', imageUrl);
    canvas.renderAll();
    undoRedoObject();
    updateView();
  }, { crossOrigin: 'annonymous' });
}

/***************************************Start Getting top Image Color**************************************/

/************************************Start Hex To Rgba Functionality ***************************************/
function hexToRgbaArray(hex, alpha = 255) {
  hex = hex.replace(/^#/, '');
  if (hex.length === 3) {
    hex = hex.split('').map(char => char + char).join('');
  }
  const bigint = parseInt(hex, 16);
  const r = (bigint >> 16) & 255;
  const g = (bigint >> 8) & 255;
  const b = bigint & 255;
  return [r, g, b, alpha];
}

/************************************Start Hex To Rgba Functionality ***************************************/

function showUploadedImageWithLayering() {
  document.getElementById("upload_image_with_select_btn").style.display = "none"
  document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
}

/****************************Start Back From Layering functionality************************************/
function closeEditLayerPopup() {
  document.getElementById("editLayerImagePopup").style.display = "none";
  document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
  canvas.discardActiveObject();
  canvas.renderAll();
}
/****************************End Back From Layering functionality************************************/


/********************************Start change color popup*********************/
function closepopup() {
  document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "none";
  fileHandler('', '', '');
}

function closeSelectColorPopup() {
  document.getElementsByClassName("popup-overlay select_color_popup")[0].style.display = "none";
  fileHandler('', '', '');
}

/********************************Start change color popup*********************/

/*****************************Start Center Horizontal  Center Vertical flipX flipY******************************/
var actions = document.getElementById("image_actions_one");
if (actions) {
  let actionsChildrenforOne = actions.children;
  for (i = 0; i < actionsChildrenforOne.length; i++) {
    actionsChildrenforOne[i].addEventListener('click', (e) => {
      const img1 = canvas.getActiveObject();
      if (canvas.getActiveObject()) {
        if (img1?._element?.tagName == 'IMG') {
          if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
            img1.set('flipX', !img1.flipX);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
            img1.set('flipY', !img1.flipY);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
            setHorizontalCenter(img1);
            img1.setCoords();
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
            setVerticleCenter(img1);
            img1.setCoords();
            canvas.renderAll();
            undoRedoObject();

          }
        }
        if (img1?.textSrc) {
          if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
            img1.set('flipX', !img1.flipX);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
            img1.set('flipY', !img1.flipY);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
            img1.centerH()
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
            img1.centerV();
            canvas.renderAll();
            undoRedoObject();
          }
        }
      }
      showImageAndTextInLayering();
    });
  }
}


var actions = document.getElementById("image_actions_two");
if (actions) {

  let actionsChildrenforTwo = actions.children;
  for (i = 0; i < actionsChildrenforTwo.length; i++) {

    actionsChildrenforTwo[i].addEventListener('click', (e) => {
      const img1 = canvas.getActiveObject();
      if (canvas.getActiveObject()) {
        if (img1?._element?.tagName == 'IMG') {
          if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
            img1.set('flipX', !img1.flipX);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
            img1.set('flipY', !img1.flipY);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
            img1.centerH();
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
            img1.centerV();
            canvas.renderAll();
            undoRedoObject();
          }
        }
        if (img1?.textSrc) {
          if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
            img1.set('flipX', !img1.flipX);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
            img1.set('flipY', !img1.flipY);
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
            let centerX = (canvas.getWidth()) / 2;
            img1.set({
              left: (centerX + 50),
              top: img1.top
            });
            canvas.renderAll();
            undoRedoObject();
          } else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
            let centerY = (canvas.getHeight()) / 2;
            img1.set({
              left: img1.left,
              top: (centerY + 50)
            });
            canvas.renderAll();
            undoRedoObject();
          }
        }
      }
    });
  }
}

/*****************************End Center Horizontal  Center Vertical flipX flipY******************************/

/**************************Start Delete Current Object From Canvas**********************************/
function deleteCurrentObjFromChooseArea() {
  if (canvas.getActiveObject() && canvas.getActiveObject()?._originalElement?.tagName) {
    fileHandler('', '', '');
  }
}
/**************************End Delete Current Object From Canvas**********************************/


/*****************************Start Copy functionality***********************************/
function copy() {
  if (canvas.getActiveObject()) {
    canvas.getActiveObject().clone(function (cloned) {
      _clipboard = cloned;
    });
  }
}
/*****************************End Copy functionality**************************************/
async function clonePythonDB(activatedObj) {
  let url = 'https://sis1110-python.devsparxit.com/ImageChanged/cloneImage/';
  return await $.post(url, {
    "ImageId": (activatedObj.image_id).split('_')[1],
    "imageVecs": activatedObj.imageVecs,
    "num_colors": activatedObj.colorCode.length > 0 ? activatedObj.colorCode.length : 0,
    "base64_string_Original": activatedObj?._originalElement?.src,
  });
}
/*****************************Start Paste functionality***********************************/
async function paste() {
  let activatedObj = canvas.getActiveObject();
  if (canvas.getActiveObject()) {
    _clipboard.clone(async function (clonedObj) {
      canvas.discardActiveObject();
      clonedObj.set({
        left: clonedObj.left + 10,
        top: clonedObj.top + 10,
        evented: true,
      });
      let image_id = generateUniqueNumber();
      if (activatedObj._originalElement) {
        if (activatedObj.image_id) {
          let imageID = (activatedObj.image_id).split('_');
          const result = await clonePythonDB(activatedObj);
          image_id = result.image_id + '_' + imageID[0];
          clonedObj.set('image_id', image_id);

        }
        let imageIDs = image_id.split('_');
        clonedObj.set('pk_id', imageIDs[0]);
        if (clonedObj.type === 'activeSelection') {
          clonedObj.canvas = canvas;
          clonedObj.forEachObject(function (obj) {
            canvas.add(obj);
            canvas.renderAll();
            updateView();
          });
          clonedObj.setCoords();
        } else {
          canvas.add(clonedObj);
          canvas.renderAll();
          updateView();
        }
        _clipboard.top += 10;
        _clipboard.left += 10;
        canvas.setActiveObject(clonedObj);
        canvas.renderAll();
        updateView();
      }
      if (activatedObj.textSrc) {
        if (activatedObj.image_id) {
          let id = activatedObj.image_id + 1;
          clonedObj.set('image_id', id);
        }

        if (clonedObj.type === 'activeSelection') {
          clonedObj.canvas = canvas;
          clonedObj.forEachObject(function (obj) {
            canvas.add(obj);
            canvas.renderAll();
            updateView();
          });
          clonedObj.setCoords();
        } else {
          canvas.add(clonedObj);
          canvas.renderAll();
          updateView();
        }
        _clipboard.top += 10;
        _clipboard.left += 10;
        canvas.setActiveObject(clonedObj);
        undoRedoObject();
        canvas.renderAll();
        updateView();
      }
    });
  }
}

function generateUniqueNumber() {
  return Math.floor(Math.random() * 900000) + 100000;
}
/*****************************End Paste functionality********************************/

/*****************************Start Uploaded Files***********************************/
if (uploadButton) {
  uploadButton.onclick = function () {
    imageDisplay.innerHTML = "";
    this.value = '';
  };

  uploadButton.onchange = function () {
    if (uploadButton.files.length > 0) {
      Array.from(uploadButton.files).forEach((file) => {
        fileHandler(file, file.name, file.type);
      });
    }
  };
}


/*****************************End Uploaded Files***********************************/

/*****************************Start Upload files by Drag And Drop******************/
if (img_container) {
  img_container.addEventListener(
    "dragenter",
    (e) => {
      e.preventDefault();
      e.stopPropagation();
    },
    false
  );

  img_container.addEventListener(
    "dragleave",
    (e) => {
      e.preventDefault();
      e.stopPropagation();
    },
    false
  );

  img_container.addEventListener(
    "dragover",
    (e) => {
      e.preventDefault();
      e.stopPropagation();
    },
    false
  );

  img_container.addEventListener(
    "drop",
    (e) => {
      e.preventDefault();
      e.stopPropagation();
      let draggedData = e.dataTransfer;
      let files = draggedData.files;
      imageDisplay.innerHTML = "";
      Array.from(files).forEach((file) => {
        fileHandler(file, file.name, file.type);
      });
    },
    false
  );
}

/*****************************End Upload files by Drag And Drop******************/

/**********************Start Save The Design***********************************/
function saveDesignOpenPopup(action = 0) {
  if (selectCan[activeView].canvas._objects.length > 0) {
    document.getElementById('saveDesignPopup').style.display = "flex";
    $('.save-design-btn').attr('data-action', action);
  }
}

function saveDesignClosePopup() {
  document.getElementById('saveDesignPopup').style.display = "none";
  document.getElementById('txtdesign').value = "";
  document.getElementById('nameError').innerHTML = "";
}

function saveDesign(isOverwrite) {
  if (isOverwrite == 2) {
    document.getElementById('nameOverridePopup').style.display = "none";
    return
  } else {
    if (document.getElementById('txtdesign').value.length > 0) {
      designName = document.getElementById('txtdesign').value;
      mySavedDesignDataObj['name'] = document.getElementById('txtdesign').value;
      mySavedDesignDataObj['user_id'] = user_id ? user_id : null;
      mySavedDesignDataObj['isOverwrite'] = isOverwrite == 1 ? isOverwrite : 0;
      let saveDesignArr = [];
      selectCan.forEach((data, ind) => {
        if (data.canvas.getObjects().length) {
          const obj = {};
          data.canvas.getObjects().forEach((ele) => {
            if (ele._originalElement) {
              obj.imageData = ele._originalElement.src;
              obj.colorCode = ele.colorCode;
              obj.image_id = ele.image_id;
            }
          });
          data.canvas.discardActiveObject();
          data.canvas.backgroundImage.opacity = 0;
          data.canvas.overlayImage.opacity = 0;
          obj.design = data.canvas.toDataURL();
          obj.canvasData = JSON.stringify(data.canvas);
          saveDesignArr[ind] = obj;
          data.canvas.backgroundImage.opacity = 1;
          data.canvas.overlayImage.opacity = 1;
          updateView();

        } else {
          saveDesignArr[ind] = {};
        }
      })

      let svgImage = findSVGImageOfCanvas();

      mySavedDesignDataObj['design'] = saveDesignArr;
      mySavedDesignDataObj['svg-data'] = svgImage != null ? svgImage : null;
      mySavedDesignDataObj['finalDesignedData'] = finalDesignedData;
      document.getElementById("loaderStartEnd").style.display = "flex";
      fetch(BASE_URL + '/design',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(mySavedDesignDataObj)
        })
        .then(data => {
          if (!data.ok) {
            throw Error(data.status);
          }
          return data.json();
        }).then(res => {
          document.getElementById("loaderStartEnd").style.display = "none";
          if (res.status == 201) {
            document.getElementById('nameOverridePopup').style.display = "flex";
            document.getElementById("noteMessage").innerHTML = res.message;
          }
          if (res.status == true) {
            document.getElementById("saveDesignPopup").style.display = "none";
            document.getElementById('nameOverridePopup').style.display = "none";
            document.getElementById("displayMessagePopup").style.display = "flex";
            document.getElementById("designSavedMessage").innerHTML = res.message;

            setTimeout(() => {
              document.getElementById("displayMessagePopup").style.display = "none";
              document.getElementById("designSavedMessage").innerHTML = '';
              let isNext = $('.save-design-btn').attr('data-action');
              if (isNext == 1) {
                $('#colorTypeBtn').trigger('click');
              }
            }, 2000);
          }
        }).catch(e => {
          console.log(e);
        });
    } else {
      document.getElementById('nameError').innerHTML = "Name is Required"
    }
  }
}
/**********************End Save The Design***********************************/


/***********************Start to find Svg Image *****************************/

function findSVGImageOfCanvas() {
  canvas.backgroundImage.opacity = 0;
  canvas.overlayImage.opacity = 0;

  var svg = canvas.toSVG({
    viewBox: {
      x: 100,
      y: 100,
      width: canvas.width,
      height: canvas.height
    }
  });
  // console.log("svg", svg);
  const base64URL = svgToBase64(svg);
  // console.log("base64URL", base64URL);
  canvas.backgroundImage.opacity = 1;
  canvas.overlayImage.opacity = 1;
  return base64URL;
}

function svgToBase64(svg) {
  const base64 = btoa(svg);
  const base64URL = `data:image/svg+xml;base64,${base64}`;
  return base64URL;
}

/***********************End to find Svg Image *******************************/


/*****************************Start Get My Design***************/
var saveDesignArray = [];
function mySavedDesign() {
  document.getElementById("loaderStartEnd").style.display = "flex";
  document.getElementById("myDesigns").innerHTML = "";
  document.getElementById("myImages").innerHTML = "";
  fetch(BASE_URL + '/design?user_id=' + user_id).then(data => {
    return data.json();
  }).then(post => {
    if (post['status']) {
      document.getElementById("loaderStartEnd").style.display = "none";
      saveDesignArray = [];
      saveDesignArray.push(post?.data);
      post?.data.forEach((ele) => {
        let data = JSON.stringify(ele)
        document.getElementById("myDesigns").innerHTML += `<div class="cd_image_box">
            <img src=${ele?.design[0]?.design} id=${ele.id} alt="img" onclick='designUploadToCanvas(this.id,${JSON.stringify(ele?.name)})'>
            <p>${ele.name}</p>
          </div>`

        ele?.design?.forEach((d) => {
          document.getElementById("myImages").innerHTML += `<div class="cd_image_box">
          <img src=${d?.imageData} id=${d?.imageData} alt="img" onclick='imageUploadToCanvas(this.id,${JSON.stringify(data)})'>
          <p>${ele.name}</p>
        </div>`
        });

      });
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
      document.getElementById("upload_image_with_select_btn").style.display = "none";
      document.getElementById("my_saved_Design").style.display = "block";
    }
  });
}
/*****************************End Get My Design***************/

function loadMySavedDesign(data, ind) {
  if (data.length > ind) {
    dummyCanvas.loadFromJSON(data[ind].canvasData, function () {
      dummyCanvas.forEachObject(function (obj) {
        selectCan && selectCan[ind]?.canvas.add(obj).setActiveObject(obj);
        if (obj.textSrc && query_params_isEdit) {
          loadFontFamilyWhileEditng(obj);
        }
        let id = obj.id;
        if (!id) {
          id = obj.image_id;
        }
        selectCan && selectCan[ind]?.canvas.discardActiveObject();
        selectCan && selectCan[ind]?.canvas.renderAll();
        undoRedoObject();
        updateView();
        const imageUrl = selectCan && selectCan[ind].canvas.toDataURL();
        document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[ind].querySelector('img').setAttribute('src', imageUrl);
      });
      loadMySavedDesign(data, ++ind)
    });
    document.getElementById("uploadDesignPopup").style.display = "none"
  } else {
    product_updated = [];
    console.log('completed');
  }
}

/*********************Load Font Family While Editing*****************************/

function loadFontFamilyWhileEditng(obj) {
  let fontUrl = obj.fontFamily;
  let fontName = obj.fontFamily;
  loadFont(fontName, fontUrl).then(function () {
    obj.set('fontFamily', fontName);
    canvas.renderAll();
  }).catch(function (error) {
    console.error('Font loading error:', error);
  });
}

function loadFont(fontName, fontUrl) {
  var newStyle = document.createElement('style');
  newStyle.appendChild(document.createTextNode(
    `@font-face {
      font-family: '${fontName}';
      src: url('${fontUrl}') format('truetype');
    }`
  ));
  document.head.appendChild(newStyle);

  return new Promise(function (resolve, reject) {
    var font = new FontFace(fontName, `url(${fontUrl})`);
    font.load().then(function (loadedFont) {
      document.fonts.add(loadedFont);
      resolve();
    }).catch(function (error) {
      reject(error);
    });
  });
}

/*********************Load Font Family While Editing*****************************/

function loadMySavedImage(data, ind) {
  if (data.length > ind) {
    dummyCanvas.loadFromJSON(data[ind].canvasData, function () {
      dummyCanvas.forEachObject(function (obj) {
        if (obj.type == "image") {
          selectCan && selectCan[ind]?.canvas.add(obj);
          selectCan && selectCan[ind]?.canvas.renderAll();
          undoRedoObject();
          updateView();
          const imageUrl = selectCan && selectCan[ind].canvas && selectCan[ind].canvas.toDataURL();
          document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[ind].querySelector('img').setAttribute('src', imageUrl);
        }
      });
      loadMySavedImage(data, ++ind)
    });
  } else {
    console.log('completed');
  }
}

/****************************Start Saved Design Upload to Canvas*********/
function designUploadToCanvas(id, name) {
  designName = name ? name : '';
  let canData = saveDesignArray[0].filter((ele) => ele.id == id);
  if (canData.length) {
    canData = canData[0];
    isReplaceCanData = canData;
    if (selectCan[activeView].canvas?._objects.length > 0) {
      document.getElementById("uploadDesignPopup").style.display = "flex";
    } else {
      loadMySavedDesign(canData.design, 0);
      document.getElementById("my_saved_Design").style.display = "none";
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
    }
  }
}

function cencelUploadDesign() {
  isReplaceCanData = {};
  document.getElementById("uploadDesignPopup").style.display = "none";
}

function uploadDesignAfterRemoveObjects(act) {
  if (selectCan[act].canvas._objects.length > 0) {
    selectCan[act].canvas.clear();
    selectCan[act]?.canvas.renderAll();
    updateView();
  }
  if (act == 1) {
    loadMySavedDesign(isReplaceCanData.design, 0);
  } else {
    uploadDesignAfterRemoveObjects(1);
  }
}

function imageUploadToCanvas(id, obj) {
  let data = JSON.parse(obj);
  data?.design?.forEach((d) => {
    if (d?.imageData == id) {
      if (d?.imageData != '') {
        fabric.Image.fromURL(`${d?.imageData}`, function (oimg) {
          var dummyratio = maintainRation(oimg['width'], oimg['height'], canvas.getWidth(), canvas.getHeight());
          imagesrcImagecolorImageidObj['colors'] = d?.colorCode;
          imagesrcImagecolorImageidObj['imgId'] = d?.image_id;
          img1 = oimg.set({
            image_id: d?.image_id,
            colorCode: d?.colorCode,
            'scaleX': dummyratio['canRatio'],
            'scaleY': dummyratio['canRatio'],
            'left': ((canvas.getWidth() / canvas.getZoom()) / 2) - (dummyratio['canWidth'] / 2),
            'top': ((canvas.getHeight() / canvas.getZoom()) / 2) - (dummyratio['canHeight'] / 2),
          });
          canvas.add(img1).setActiveObject(img1);
          const imageUrl = canvas.toDataURL();
          selectCan[activeView].imageUrl = imageUrl;
          document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector("img").setAttribute('src', imageUrl);
          canvas.discardActiveObject();
          canvas.renderAll();
          showUploadedImageWithLayering();
          undoRedoObject();
          updateView();
        }, { crossOrigin: 'anonymous' });
      }
      document.getElementById("my_saved_Design").style.display = "none";
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
    }
  });
}


/***************************End Saved Design Upload to Canvas*************/

/************************ START SIDE CLEAR SELECT ALL**************/

var Side_Clear_SelectAll = document.getElementsByClassName("cd_bg_wrap");
if (Side_Clear_SelectAll[0]) {
  var Side_Clear_SelectAll_Children = Side_Clear_SelectAll[0].children;
  for (let i = 0; i < Side_Clear_SelectAll_Children.length; i++) {
    Side_Clear_SelectAll_Children[i].addEventListener('click', (e) => {
      if (e?.target?.classList?.value === 'icon_clear' || e?.target?.children[0]?.classList?.value === 'icon_clear') {
        fileHandler('', '', '');
        document.getElementById('textInput').value = '';
        // fontSize[0].value = '';
        canvas.clear();
        updateView();
      } else if (e?.target?.classList?.value === 'icon_select' || e?.target?.children[0]?.classList?.value === 'icon_select') {
        canvas.discardActiveObject();
        if (canvas.getActiveObject() == null) {
          if (canvas.getObjects().length > 1) {
            var objs = canvas.getObjects().map(function (o) {
              return o.set('active', true);
            });
            var group = new fabric.Group(objs, {
              originX: 'center',
              originY: 'center'
            });
            canvas._activeObject = null;
            canvas.setActiveGroup(group.setCoords()).renderAll();
          } else if (canvas.getObjects().length == 1) {
            canvas.setActiveObject(canvas.getObjects()[0]);
            canvas.renderAll();
          }
        }
      }
    })
  }
}


/************************ END SIDE CLEAR SELECT ALL*****************************/

/*******************************Start Select Arc Type **************************/
var select_arcType = document.getElementsByClassName("popup-button-arctype");
var arctypeListing = document.getElementsByClassName('arctype-listing')[0];

if (select_arcType[0]) {
  select_arcType[0].addEventListener('click', (e) => {
    toggleArctypeListing();
    e.stopPropagation(); // Prevent the click event from propagating to the document
  });
}
// Add an event listener to the document
document.addEventListener('click', (e) => {
  var target = e.target;
  // Check if the clicked element is outside the arctype-listing and popup-button-arctype
  if (arctypeListing) {
    if (!arctypeListing.contains(target) && !select_arcType[0].contains(target)) {
      arctypeListing.style.display = 'none';
    }
  }
});


function toggleArctypeListing() {
  if (arctypeListing) {
    if (arctypeListing.style.display === 'block') {
      arctypeListing.style.display = 'none';
    } else {
      arctypeListing.style.display = 'block';
    }
  }
}

function changeArcValueEffect(target, curv_value) {
  for (let i = 0; i < document.getElementsByClassName("arctype-listing")[0].children.length; i++) {
    if (document.getElementsByClassName("arctype-listing")[0].children[i].querySelector(".arctypeimg-wrap").classList.contains("font-family-style")) {
      document.getElementsByClassName("arctype-listing")[0].children[i].querySelector(".arctypeimg-wrap").classList.remove("font-family-style")
    }
  }
  target.classList.add("font-family-style");
  selectEffect = curv_value;
  updateGroupText();
  updateView();
  undoRedoObject();
}
/*******************************End Select Arc Type *************************************/

/*******************************Start Only Number Functionality****************************/

var bothSide = document.getElementById("cd_profile_check");
if (bothSide) {

  bothSide.addEventListener('click', (e) => {
    finalDesignedData["printBothSide"] = e.target.checked;
    if (e.target.checked) {
      document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].disabled = true;
      document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].classList.add("dis-ele");
      document.getElementsByClassName('cd_function_btn popup_side')[0].disabled = true;
      document.getElementsByClassName('cd_function_btn popup_side')[0].classList.add("dis-ele");
    } else {
      document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].disabled = false;
      document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].classList.remove("dis-ele");
      document.getElementsByClassName('cd_function_btn popup_side')[0].disabled = false;
      document.getElementsByClassName('cd_function_btn popup_side')[0].classList.remove("dis-ele");
    }
    updateView();
  });
}

var inputField = document.querySelector('#quantity');
if (inputField != null) {
  inputField.onkeydown = function (event) {
    if (isNaN(event.key) && event.key !== 'Backspace') {
      event.preventDefault();
    }
  };
}

function textValidation(event) {
  if (isNaN(event.key) && event.key !== 'Backspace') {
    event.preventDefault();
  }
}

/*******************************End Only Number Functionality****************************/

/*******************************Start done button****************************************/
function addNameAndQuantity() {
  finalDesignArr = [];
  let index = 0;
  let buyer = [];
  let frontBack = [];
  $('.cd_clone_wrap').each(function () {
    let buyerObj = {};
    let name = $(this).find('#name').val();
    let quantity = $(this).find('#quantity').val();
    buyerObj['name'] = name;
    buyerObj['quantity'] = quantity;
    buyer.push(buyerObj);
    index += 1;
  });

  let obj = {};
  selectCan.forEach((data, index) => {
    canvas.renderAll();
    let imgObj = {};
    imgObj['imageWithoutDesign'] = data.imageUrl;
    frontBack.push(imgObj);
    if (data.canvas.getObjects().length) {
      obj = {};
      data.canvas.discardActiveObject();
      obj.designWithImage = data.canvas.toDataURL();
      data.canvas.backgroundImage.opacity = 0;
      data.canvas.overlayImage.opacity = 0;
      obj.design = data.canvas.toDataURL();
      obj.canvasData = JSON.stringify(data.canvas);
      finalDesignArr.push(obj);
      data.canvas.backgroundImage.opacity = 1;
      data.canvas.overlayImage.opacity = 1;
    }
  })

  if (buyer.length > 0) {
    buyer.forEach((ele, index) => {
      if (ele.name == null || ele.name == '' || ele.name == undefined) {
        buyer.splice(index, 1);
      }
    });
  }

  finalDesignedData['buyers'] = buyer;
  finalDesignedData['backgroundImages'] = frontBack;
}

/*******************************End done button***********************************/

/*******************************Fetch Data From Excel****************************/

function uploadExcel(file) {
  let formData = new FormData();
  formData.append('excelFile', file);
  let url = BASE_API_URL + '/parseExcel';
  $.ajax({
    method: "POST",
    url: url,
    data: formData,
    dataType: "json",
    contentType: false,
    cache: false,
    processData: false,
    success: function (response) {
      setNameQuantityFields(response.result);
    },
    error: function (response) {
    }
  });
}

function handleFileSelect(event) {
  const selectedFile = event.target.files[0];
  if (selectedFile) {
    const fileName = selectedFile.name;
    const fileType = selectedFile.type;
    if (fileType !== 'application/vnd.ms-excel') {
      document.getElementById("file-error").innerHTML = `<p style="color:red">Please Upload  Xlsx or xls file</p>`;
      document.getElementById("upload").value = "";
      return
    } else {
      document.getElementById("file-error").innerHTML = " ";
      uploadExcel(selectedFile);
    }
  }
}

/*******************************Fetch Data From Excel****************************/

/***************************Start Add Name Field And Quantity Field **************/
function setNameQuantityFields(data) {
  document.getElementsByClassName("cd_clone_wrap_dynamic")[0].innerHTML = "";
  if (data.length > 0) {
    var fieldCount = 0;
    data?.forEach((field) => {
      fieldCount++;
      var newElement = $(`<div class="cd_clone_wrap"></div>`);
      newElement.appendTo(".cd_clone_wrap_dynamic");
      var inputElement1 = $(`<input type='text' value=${field.name} id='name'/>`);
      var inputElement2 = $(`<input type='text' id='quantity' onkeydown="textValidation(event)" value=${field.quantity} class="cd_quantity"/>`);
      var removeBtn = $(`<button type='button' class='cd_remove_btn'></button>`);
      inputElement1.appendTo(newElement);
      inputElement2.appendTo(newElement);
      removeBtn.appendTo(newElement);
    });
  }
}
/***************************End Add Name Field And Quantity Field ****************/

function alertClear() {
  setTimeout(function () {
    $('.errors').html('');
  }, 5000);
}

async function parseObject(finalDesignedData) {
  return finalDesignedData;
}

// /*************************************Start Convert SBG Function***********************/
// function svgToBase64(svg) {
//   const base64 = btoa(svg);
//   const base64URL = `data:image/svg+xml;base64,${base64}`;
//   return base64URL;
// }
// /*************************************End Convert SBG Function************************/

/********************************Move To Next Page********************************/
async function moveToNextPage(user_id1) {
  let totalColor = [];
  totalColor[0] = [...new Set(totalColorsViewWise[0])];
  totalColor[1] = [...new Set(totalColorsViewWise[1])];
  if (!user_id1) {
    $('.next-btn-error').html(`<div class="row">
      <div class="col-md-12" style="color:red;font-weight:bold;">Please login before proceed.</div>
    </div>`);
    alertClear();
    return;
  }

  if (totalColor[0].length > 4 || totalColor[1].length > 4) {
    let total_color = 0;
    if (totalColor[0].length > 4) {
      total_color = totalColor[0].length;
    }

    if (totalColor[1].length > 4) {
      total_color = totalColor[1].length;
    }

    $('.next-btn-error').html(`<div class="row">
      <div
        class="col-md-12"
        style="color:red;font-weight:bold;"
      ><p>We regret that we cannot price artwork containing ${total_color} colors.</p><p>Please alter the design to utilize 4 colors or fewer.</div>
    </p></div>`);
    alertClear();
    return;
  }

  addNameAndQuantity();
  finalDesignedData['user_id'] = user_id;
  finalDesignedData['design'] = finalDesignArr;
  finalDesignedData['variant_id'] = capTypeIds;
  finalDesignedData['totalColors'] = totalColor;
  finalDesignedData['design_name'] = designName;
  finalDesignedData['printBothSide'] = finalDesignedData["printBothSide"] == true ? true : false;
  finalDesignedData['coordinate'] = coordinateArray ? coordinateArray : {};
  /* design save */
  if (designName == "" || designName == null || designName == undefined) {
    saveDesignOpenPopup();
    return;
  }

  document.getElementById("loaderStartEnd").style.display = "flex";
  fetch(BASE_API_URL + '/store-temp-page-details', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      "result": finalDesignedData,
      "user_id": user_id,
    })
  })
    .then(data => {
      if (!data.ok) {
        throw Error(data.status);
      }
      return data.json();
    }).then(res => {
      document.getElementById("loaderStartEnd").style.display = "none";
      if (res.status == true) {
        let url = designDetails;
        if (isCartEdit) {
          url += "&cart=" + cart_id;
        }
        isEnableToRedirct = 1;
        window.location = url;
      }
    }).catch(e => {
      console.log(e);
    });
}

/********************************Move To Next Page********************************/

/***************************Start Zoom Functionality******************************/

$(".cd_resize_btn").click(function () {
  if ($(this).hasClass('shrink')) {
    $("canvas").width(
      $("canvas").width() / 1.5
    );
    $("canvas").height(
      $("canvas").height() / 1.5
    );
    $(this).removeClass("shrink");
  } else {
    $("canvas").width(
      $("canvas").width() * 1.5
    );
    $("canvas").height(
      $("canvas").height() * 1.5
    );
    $(this).addClass("shrink");
  }
});

/***************************End Zoom Functionality******************************/

/**********************************Start Only Text in input field********************/
function lettersValidate(key) {
  document.getElementById('txtdesign').setAttribute('maxlength', 40);
  var keycode = (key.which) ? key.which : key.keyCode;
  if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || (keycode > 47 && keycode < 58) || (keycode == 32)) {
    return true;
  }
  else {
    return false;
  }

}
/***************************************Only Text in input field**********************************/

/****************************End Start on Load Function*******************/
function myFunction() {
  getProductIdFromURL();
  hideNextPageButton();
}

/****************************End on Load Function*******************/

/****************************Start Getting Data from Design Details Page********************/
function dataFromDesignDetails(imageName) {
  document.getElementById("loaderStartEnd").style.display = "flex";
  let url = BASE_URL + '/get-temp-page-details/' + user_id;
  if (isCartEdit) {
    url += "?cart_id=" + cart_id;
  } else if (isDesignEdit) {
    url += "?design_id=" + design_id;
  }
  fetch(url).then(data => {
    return data.json();
  }).then(post => {
    if (post.status) {
      let buyers = post?.data?.buyers;
      if (buyers && buyers != 'undefined') {
        setNameQuantityFields(post?.data?.buyers);
      }
      if (post.data.printBothSide) {
        check();
      } else {
        uncheck();
      }
      designName = post?.data?.design_name;
      document.getElementById("loaderStartEnd").style.display = "none";
      loadMySavedDesign(post?.data?.design, 0);
      document.getElementById("my_saved_Design").style.display = "none";
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
      /* select product name */
      $('.captype-img-btn span').html(imageName);
    }
  });
}
/****************************End Getting Data from Design Details Page********************/


/****************************Start Set Checkbox value************************************/
function check() {
  document.getElementById("cd_profile_check").checked = true;
  finalDesignedData["printBothSide"] = true;
  document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].disabled = true;
  document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].classList.add("dis-ele");
}

function uncheck() {
  document.getElementById("cd_profile_check").checked = false;
  finalDesignedData["printBothSide"] = false;
  document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].disabled = false;
  document.getElementsByClassName('cd_change_side popup_change_product_side_open')[0].classList.remove("dis-ele");
}

/****************************Start Set Checkbox value************************************/

/**********************************Start Color Functionality in Footer***********************/

function selectImageColorInFooter() {
  document.getElementById('imageColorPopupInFooter').style.display = "block";
}

function closeImageColorPopupInFooter() {
  document.getElementById('imageColorPopupInFooter').style.display = "none";
}


function addProductColor() {
  capTypeData?.list?.data?.forEach(vari => {
    if (vari.id == defaultSelectedProduct?.product_id) {
      varients = vari.variant;
    }
  })

  let color = '';
  varients?.forEach(col => {
    let selected = '';
    if (defaultSelectedProduct.id === col.id) {
      selected = 'checked="checked"';
    }
    color += `<li><input type="radio" ${selected} id="backColor-${col?.id}" name="color" product_id="${col?.product_id}" class="product-color"  value="color" style="background-color:${col?.color_code}" onchange="addProductColorChange(${col.id})"></li>`

  })
  document.getElementById('changeImageColorInFooter').innerHTML = color;
}

function addProductColorChange(id) {
  const seletctProduct = varients.filter((data, ind) => {
    return data.id === id;
  });

  if (seletctProduct.length > 0) {
    capTypeIds[0] = seletctProduct[0].id;
    defaultSelectedProduct = seletctProduct[0];
    document.getElementsByClassName('front_canvas')[0].querySelector('img').setAttribute('src', defaultSelectedProduct.front_image_url);
    document.getElementsByClassName('back_canvas')[0].querySelector('img').setAttribute('src', defaultSelectedProduct.back_image_url);
    document.getElementById("add_product_color").innerHTML = `<div class="add_product_wrap"><button type="button" class="close_color"></button> <img src=${defaultSelectedProduct.front_image_url} class="select-captype-img"
    id=${defaultSelectedProduct.front_image_url} alt="circle-box"></div>`
    setBackgroundImageOnCanvas(document.querySelectorAll('.canvas'), 0);
    setChangeColorOverlayImage(['front_image_overlay_url', 'back_image_overlay_url'], 0);
  }
}
/************************End Backgroung Color Change Functionality in Footer****************************/

// function showOverlayProgress() {
//   $('#loaderStartEnd').show();
// }

// // Function to hide overlay progress
// function hideOverlayProgress() {
//   $('#loaderStartEnd').hide();
// }

function moveToTermAndConditions() {
  window.location = termAndConditions;
}