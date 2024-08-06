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

var select_capType = document.getElementsByClassName("popup-button-captype");

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
};


/********************************Start Get Default Product***********************************/
function getDefaultProduct() {
	activeView = 0;
	defaultSelectedProduct = '';
	let productdropdown = '';
	selectCan = [];
	for (let i = 0; i < capTypeData ? .list ? .data ? .length; i++) {
		if (capTypeData ? .list ? .data[i] ? .variant.length > 0) {
			for (let j = 0; j < capTypeData ? .list ? .data[i] ? .variant.length; j++) {
				if (j === 0 && productDropDownCreatedFlag) {
					productdropdown += `<li>
            <a href="#" class="captypeimg-wrap" data-id="${capTypeData?.list?.data[i]?.variant[0].id}">
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
					if (capTypeData ? .list ? .data[i] ? .variant[j].id == query_params_product_id && !defaultSelectedProduct) {
						defaultSelectedProduct = capTypeData ? .list ? .data[i] ? .variant[j];
					}
				} else {
					if (capTypeData ? .list ? .data[i] ? .variant[j] ? .default == 1 && !defaultSelectedProduct) {
						defaultSelectedProduct = capTypeData ? .list ? .data[i] ? .variant[j];
					}
				}
			}
		}
	}

	document.getElementById("loaderStartEnd").style.display = "none";
	document.getElementsByClassName('cd_profile_type')[0].innerHTML = `${activeView == 0 ? 'Front' : 'Back'}`;

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
		document.getElementById("add_product_color").innerHTML = `<div class="add_product_wrap"><button type="button" class="close_color"></button> <img src=${defaultSelectedProduct.front_image_url} class="select-captype-img"
      id=${defaultSelectedProduct.front_image_url} alt="circle-box" onclick="setSelectedProductColorImage(${defaultSelectedProduct.id},${defaultSelectedProduct.product_id})"></div>`
		if (productDropDownCreatedFlag) {
			document.getElementsByClassName("captype-listing")[0].innerHTML = productdropdown;
			addClickOnproductDropDown();
			addProductColor();
			if (query_params_isEdit) {
				dataFromDesignDetails();
			}

		}
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
	const button = select_capType[0];
	if (!isDescendant(captypeListing, event.target) && event.target !== button) {
		if (captypeListing) {
			captypeListing.style.display = 'none';
		}
	}

});


select_capType[0] ? .addEventListener('click', () => {
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
	updateGroupText();
	updateView();
	undoRedoObject()
};


/***************************Start Font Family functionality*******************************/

var fontSize = document.getElementsByClassName("font-size-select");
if (fontSize[0]) {
	fontSize[0].addEventListener('change', (e) => {
		if (canvas.getActiveObject()) {
			font_Family = e.target.value;
			updateGroupText();
			updateView();
		}
	});
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
	let active = canvas.getActiveObject()
	if (canvas.getActiveObject()) {} else {
		removeTextFields();
	}
}

function showSavedDesign() {
	mySavedDesign();
}

/*************************End Upload Image Text Art Button***********************************/

/*******************************Start Close Popup Image and Text and Art*********************************/
function closeAddTextPopup() {
	if (selectCan[activeView] ? .canvas ? ._objects.length > 0) {
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
	if (selectCan[activeView] ? .canvas ? ._objects.length > 0) {
		document.getElementById("upload_image_with_select_btn").style.display = "none";
		document.getElementById("add_text_with_input_field").style.display = "none";
		document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
	} else {
		document.getElementById("upload_image_with_select_btn").style.display = "none";
	}
}

function closeMySaveArtPopup() {
	if (selectCan[activeView] ? .canvas ? ._objects.length > 0) {
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
let finalDominantImage = '';


/*********************************Start Upload Image From Browser********************************************/
const fileHandler = (file, name, type) => {
	imageName = name;
	if (file == '' && name == "" && type == '') {
		imageContainer.innerHTML = '';
	} else {
		if (type.split("/")[0] !== "image") {
			//File Type Error
			error.innerText = "Please upload an image file";
			return false;
		} else if (type.split("/")[1] !== "png" || type.split("/")[1] !== "jpg" || type.split("/")[1] !== "pdf") {
			error.innerText = "";
			replacedColor = [];
			let reader = new FileReader();
			reader.onloadend = () => {
				finalDominantImage = reader.result;
				imagesrcImagecolorImageidObj['dominant'] = reader.result;
				imagesrcImagecolorImageidObj['src'] = reader.result;
				showImageInPopup(reader.result, name);
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
	document.getElementById("doneButton").style.display = "none";
	document.getElementById("doneButtonWithEdit").style.display = "none";
}

/*********************************Start Show Image In Popup************************************************/


/**********************************Start Change Number of colors for original image****************************/
let select_number_of_color_for_o_image = document.getElementsByClassName("selectColorForOriginalImage");
if (select_number_of_color_for_o_image[0]) {
	select_number_of_color_for_o_image[0].addEventListener('change', (e) => {
		if (imagesrcImagecolorImageidObj.src != '' && e.target.value >= 0) {
			imageContainer.innerHTML = '';
			imageDisplay.appendChild(imageContainer);
			getProcessedImage(imagesrcImagecolorImageidObj.src, e.target.value);
		}
	})
}

/**********************************End Change Number of colors for original image***************************/

/*******************************************Start Getting top Image Color***********************************/
function getProcessedImage(src, numberOfColor) {
	fetch('https://sis1110-python.uatsparxit.xyz/ImageChanged/GetApi/', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				"image": src,
				"num_colors": +numberOfColor,
				"imageName": imageName
			})
		})
		.then(data => {
			if (!data.ok) {
				throw Error(data.status);
			}
			return data.json();
		}).then(res => {
			if (res[0] ? .RealDominantColorsArray.length > 0) {
				document.getElementById("doneButton").style.display = "block";
				showImageColor(res[0] ? .RealDominantColorsArray, res[0] ? .ImageId, res[0].Dominant_image)
				imagesrcImagecolorImageidObj['colors'] = res[0] ? .RealDominantColorsArray;
				if (res[0] ? .Dominant_image) {
					imagesrcImagecolorImageidObj['dominant'] = `${res[0]?.Dominant_image}`;
					imagesrcImagecolorImageidObj['src'] = `${res[0]?.Original_image}`;
					imagesrcImagecolorImageidObj['imgId'] = `${res[0]?.ImageId}`
					document.getElementById("original_image_change_color").setAttribute("src", '');
					document.getElementsByClassName("selectColorForOriginalImage")[0].value = '';
					document.getElementById("original_image_change_color").setAttribute("src", `${res[0]?.Dominant_image}`);
				} else {
					console.log("Dominant_image error");
				}
			}
		}).catch(e => {
			console.log(e);
		});

}

/*******************************************End Getting top Image Color***********************************/

/*****************************************Start Show Image Color In Popup*********************************/
function showImageColor(color_arr, img_id, dominantImg) {
	let allDominantColor = [];
	imageId = img_id;
	for (let i = 0; i < color_arr.length; i++) {
		let rgb_arr = color_arr[i];
		let rgb = "#" + rgb_arr.map(e => e.toString(16).padStart(2, 0)).join("");
		allDominantColor.push(rgb);
	}
	imagesrcImagecolorImageidObj['colors'] = allDominantColor;
	imageColorList[0].innerHTML = '';
	for (let j = 0; j < allDominantColor.length; j++) {
		imageColorList[0].innerHTML += `<div class="image-color m-b-1">
      <div class="circle large m-r-1">
          <span class="color-swatch with-border" style="background-color:${allDominantColor[j]}; border-color:${allDominantColor[j]};"></span>
      </div>
      <i class="mat-icon notranslate m-r-1 text-grey-secondary material-icons"></i>
      <button class="select-color-btn" onclick="changeImageColor(${j},'${allDominantColor[j]}',${img_id})">
      <input type="button" class="select_image_color" value="#e0ffee">Select Color
      </button>
    </div>`
	}
}
/*****************************************End Show Image Color In Popup*********************************/


/******************************************Start Change color In Popup**********************************/
function changeImageColor(ind, target_color, image_id) {
	document.getElementsByClassName("color_listing")[0].innerHTML = '';
	for (let i = 0; i < 180; i++) {
		const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
		document.getElementsByClassName("color_listing")[0].innerHTML += ` <li>
      <div class="image-color m-b-1">
          <div class="circle large m-r-1">
              <span class="color-swatch with-border" onclick="selectedChangeImageColor(${ind},'${target_color}','${randomColor}',${image_id})" style="background-color: ${randomColor}; border-color:${randomColor};"></span>
          </div>
          </div>
      </li>`
	}
	document.getElementsByClassName("select_color_popup")[0].style.display = 'block';
}
/******************************************End Change color In Popup**********************************/

/*****************************************Start Select Color*********************************/

function selectedChangeImageColor(ind, targetColor, selected_color, image_id) {
	let targetColorArray = [];
	document.getElementsByClassName("select_color_popup")[0].style.display = 'none';
	let new_color = [hexToRgb(selected_color).r, hexToRgb(selected_color).g, hexToRgb(selected_color).b];
	let target_color = [hexToRgb(targetColor).r, hexToRgb(targetColor).g, hexToRgb(targetColor).b]
	const index = imagesrcImagecolorImageidObj['colors'].indexOf(targetColor);
	if (index !== -1) {
		imagesrcImagecolorImageidObj['colors'][index] = selected_color;
	}
	for (let i = 0; i < imagesrcImagecolorImageidObj['colors'].length; i++) {
		targetColorArray.push(imagesrcImagecolorImageidObj['colors']);
	}
	fetch('https://sis1110-python.uatsparxit.xyz/ImageChanged/GetApiColorChangedData/', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				"ColorIndexValues": ind ? ind : 0,
				"ImageId": image_id ? image_id : 0,
				"targetColor": target_color ? target_color : '',
				"newColor": new_color.length > 0 ? new_color : [],
				"NumColor": targetColorArray.length > 0 ? targetColorArray.length : 0,
				"base64_string_Original": imagesrcImagecolorImageidObj['src'],
			})
		})
		.then(data => {
			if (!data.ok) {
				throw Error(data.status);
			}
			return data.json();
		}).then(res => {
			showImageColor(res[0] ? .DominantColorsFromDB, res[0] ? .ImageId, res[0].Dominant_image);
			imagesrcImagecolorImageidObj['colors'] = res[0] ? .DominantColorsFromDB;
			imagesrcImagecolorImageidObj['imgId'] = res[0] ? .ImageId;
			if (res[0] ? .Dominant_image) {
				imagesrcImagecolorImageidObj['dominant'] = res[0] ? .Dominant_image;
				imagesrcImagecolorImageidObj['src'] = `${res[0]?.Original_image}`;
				document.getElementById("original_image_change_color").setAttribute("src", '');
				document.getElementById("original_image_change_color").setAttribute("src", `${res[0]?.Dominant_image}`);
			} else {
				console.log("Dominant_image error");
			}
		}).catch(e => {
			console.log(e);
		});
}
/*****************************************End Select Color**********************************/

/*****************************************Start Upload Image In Canvas****************************/
function sendImageWithNumberOfColor() {
	document.getElementsByClassName("change_color_popup")[0].style.display = "none";
	if (imagesrcImagecolorImageidObj['dominant'] != '') {
		const canvas = selectCan[activeView].canvas;
		fabric.Image.fromURL(`${imagesrcImagecolorImageidObj['dominant']}`, function (oimg) {
			var dummyratio = maintainRation(oimg['width'], oimg['height'], canvas.getWidth(), canvas.getHeight());
			img1 = oimg.set({
				image_id: imagesrcImagecolorImageidObj['imgId'],
				colorCode: imagesrcImagecolorImageidObj['colors'],
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
		}, {
			crossOrigin: 'anonymous'
		});
		document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "none";
	}
}
/*****************************************End Upload Image In Canvas****************************/

function editImage(image_url) {
	let activeObject = canvas.getActiveObject();
	if (activeObject != null && activeObject != undefined) {
		document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "block";
		document.getElementById("original_image_change_color").setAttribute("src", '');
		document.getElementById("original_image_change_color").setAttribute("src", `${image_url}`);
		document.getElementById("doneButtonWithEdit").style.display = "block";
		document.getElementById("doneButton").style.display = "none";
		showImageColor(activeObject.colorCode, activeObject.image_id, activeObject.src);
	}
}


/******************************************Start EditImageWithLayering Function*******************************************/
function editImageWithLayering() {
	document.getElementsByClassName("popup-overlay change_color_popup")[0].style.display = "none";
	document.getElementsByClassName("popup-overlay select_color_popup")[0].style.display = "none";
	let activeObject = canvas.getActiveObject();
	document.getElementById("editLayerImage").setAttribute("src", `${imagesrcImagecolorImageidObj['dominant']}`);
	if (activeObject != null && activeObject != undefined) {
		activeObject.setSrc(`${imagesrcImagecolorImageidObj['dominant']}`, function (img) {
			var dummyratio = maintainRation(img['width'], img['height'], canvas.getWidth(), canvas.getHeight());
			img1 = img.set({
				image_id: imagesrcImagecolorImageidObj['imgId'],
				colorCode: imagesrcImagecolorImageidObj['colors'],
				'scaleX': dummyratio['canRatio'],
				'scaleY': dummyratio['canRatio'],
				'left': ((canvas.getWidth() / canvas.getZoom()) / 2) - (dummyratio['canWidth'] / 2),
				'top': ((canvas.getHeight() / canvas.getZoom()) / 2) - (dummyratio['canHeight'] / 2),
			});
			canvas.setActiveObject(img);
			const imageUrl = canvas.toDataURL();
			selectCan[activeView].imageUrl = imageUrl;
			document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector("img").setAttribute('src', imageUrl);
			canvas.discardActiveObject();
			canvas.renderAll();
			undoRedoObject()
			updateView();
		}, {
			crossOrigin: 'annonymous'
		});
	} else {}

}

/******************************************End EditImageWithLayering Function*******************************************/

/*********************************************Start Change Image Color**************************************/


function changeImageColorInRight(ind, target_color, image_id) {
	document.getElementsByClassName("color_listing")[0].innerHTML = '';
	for (let i = 0; i < 180; i++) {
		const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
		document.getElementsByClassName("color_listing")[0].innerHTML += ` <li>
      <div class="image-color m-b-1">
          <div class="circle large m-r-1">
              <span class="color-swatch with-border" onclick="selectedChangeImageColorInRight(${ind},'${target_color}','${randomColor}',${image_id})" style="background-color: ${randomColor}; border-color:${randomColor};"></span>
          </div>
          </div>
      </li>`
	}
	document.getElementsByClassName("select_color_popup")[0].style.display = 'block';
}

/*********************************************End Change Image Color**************************************/

function selectedChangeImageColorInRight(ind, targetColor, selected_color, image_id) {
	let NumColor = 0
	document.getElementsByClassName("select_color_popup")[0].style.display = 'none';
	let new_color = [hexToRgb(selected_color).r, hexToRgb(selected_color).g, hexToRgb(selected_color).b];
	let target_color = [hexToRgb(targetColor).r, hexToRgb(targetColor).g, hexToRgb(targetColor).b]
	if (image_id > 0 && uploadedImageColorAndImageId.length > 0) {
		uploadedImageColorAndImageId ? .forEach((col, ind) => {
			if (col.imgId == image_id) {
				NumColor = uploadedImageColorAndImageId[ind].colors.length;
			}
		});
	}

	fetch('https://sis1110-python.uatsparxit.xyz/ImageChanged/GetApiColorChangedData/', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				"ColorIndexValues": ind ? ind : 0,
				"ImageId": image_id ? image_id : 0,
				"targetColor": target_color ? target_color : '',
				"newColor": new_color.length > 0 ? new_color : [],
				"NumColor": NumColor,
				"base64_string_Original": '',
			})
		})
		.then(data => {
			if (!data.ok) {
				throw Error(data.status);
			}
			return data.json();
		}).then(res => {
			if (res[0] ? .Dominant_image) {
				document.getElementById("editLayerImagePopup").style.display = "block";
				document.getElementById("editLayerImage").setAttribute("src", `${res[0]?.Dominant_image}`);
				showImageInRight(res[0] ? .Dominant_image, res[0] ? .DominantColorsFromDB, res[0] ? .ImageId);
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
		});
		canvas.setActiveObject(img1);
		const imageUrl = canvas.toDataURL();
		selectCan[activeView].imageUrl = imageUrl;
		document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector("img").setAttribute('src', imageUrl);
		canvas.renderAll();
		undoRedoObject();
		updateView();
	}, {
		crossOrigin: 'annonymous'
	});
}

/***************************************Start Getting top Image Color**************************************/

function hexToRgb(hex) {
	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;
}



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
	var actionsChildrenforTwo = actions.children;
	for (let i = 0; i < actionsChildrenforTwo.length; i++) {
		actionsChildrenforTwo[i].addEventListener('click', (e) => {
			const img1 = canvas.getActiveObject();
			if (canvas.getActiveObject()) {
				if (img1 ? ._element ? .tagName == 'IMG') {
					if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
						img1.set('flipX', !img1.flipX);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
						img1.set('flipY', !img1.flipY);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
						img1.centerH()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
						img1.centerV()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					}
				}
				if (img1 ? .textSrc) {
					if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
						img1.set('flipX', !img1.flipX);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
						img1.set('flipY', !img1.flipY);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
						img1.centerH()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
						img1.centerV();
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					}
				}
			}
		});
	}
}


var actions = document.getElementById("image_actions_two");
if (actions) {
	var actionsChildrenforTwo = actions.children;
	for (let i = 0; i < actionsChildrenforTwo.length; i++) {
		actionsChildrenforTwo[i].addEventListener('click', (e) => {
			const img1 = canvas.getActiveObject();
			if (canvas.getActiveObject()) {
				if (img1 ? ._element ? .tagName == 'IMG') {
					if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
						img1.set('flipX', !img1.flipX);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
						img1.set('flipY', !img1.flipY);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
						img1.centerH()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
						img1.centerV()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					}
				}
				if (img1 ? .textSrc) {
					if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
						img1.set('flipX', !img1.flipX);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
						img1.set('flipY', !img1.flipY);
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
						img1.centerH()
						canvas.add(img1);
						canvas.renderAll();
						undoRedoObject();
					} else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
						img1.centerV();
						canvas.add(img1);
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
	if (canvas.getActiveObject() && canvas.getActiveObject() ? ._originalElement ? .tagName) {
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

/*****************************Start Paste functionality***********************************/
function paste() {
	if (canvas.getActiveObject()) {
		_clipboard.clone(function (clonedObj) {
			canvas.discardActiveObject();
			clonedObj.set({
				left: clonedObj.left + 10,
				top: clonedObj.top + 10,
				evented: true,
			});
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
		});
	}
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



/**********************Start Save The Design***********************************/
function saveDesignOpenPopup() {
	if (selectCan[activeView].canvas._objects.length > 0) {
		document.getElementById('saveDesignPopup').style.display = "flex";
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

			mySavedDesignDataObj['design'] = saveDesignArr;

			fetch(BASE_URL + '/design', {
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


/*****************************Start Get My Design***************/
var saveDesignArray = [];

function mySavedDesign() {
	document.getElementById("myDesigns").innerHTML = "";
	document.getElementById("myImages").innerHTML = "";
	fetch(BASE_URL + '/design?user_id=' + user_id).then(data => {
		return data.json();
	}).then(post => {
		if (post['status']) {
			saveDesignArray = [];
			saveDesignArray.push(post ? .data);
			post ? .data.forEach((ele) => {
				let data = JSON.stringify(ele)
				document.getElementById("myDesigns").innerHTML += `<div class="cd_image_box">
            <img src=${ele?.design[0]?.design} id=${ele.id} alt="img" onclick="designUploadToCanvas(this.id)">
            <p>${ele.name}</p>
          </div>`

				ele ? .design ? .forEach((d) => {
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
				selectCan[ind] ? .canvas.add(obj);
				selectCan[ind] ? .canvas.renderAll();
				updateView();
				const imageUrl = selectCan[ind].canvas.toDataURL();
				document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[ind].querySelector('img').setAttribute('src', imageUrl);
			});
			loadMySavedDesign(data, ++ind)
		});
		document.getElementById("uploadDesignPopup").style.display = "none"
	} else {
		console.log('completed');
	}
}

function loadMySavedImage(data, ind) {
	if (data.length > ind) {
		dummyCanvas.loadFromJSON(data[ind].canvasData, function () {
			dummyCanvas.forEachObject(function (obj) {
				if (obj.type == "image") {
					selectCan[ind] ? .canvas.add(obj);
					selectCan[ind] ? .canvas.renderAll();
					updateView();
					const imageUrl = selectCan[ind].canvas.toDataURL();
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
function designUploadToCanvas(id) {
	let canData = saveDesignArray[0].filter((ele) => ele.id == id);
	if (canData.length) {
		canData = canData[0];
		isReplaceCanData = canData;
		if (selectCan[activeView].canvas._objects.length > 0) {
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
	document.getElementById("uploadDesignPopup").style.display = "none"
}

function uploadDesignAfterRemoveObjects(act) {
	if (selectCan[act].canvas._objects.length > 0) {
		selectCan[act].canvas.clear();
		selectCan[act] ? .canvas.renderAll();
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
	data ? .design ? .forEach((d) => {
		if (d ? .imageData == id) {
			if (d ? .imageData != '') {
				fabric.Image.fromURL(`${d?.imageData}`, function (oimg) {
					var dummyratio = maintainRation(oimg['width'], oimg['height'], canvas.getWidth(), canvas.getHeight());
					imagesrcImagecolorImageidObj['colors'] = d ? .colorCode;
					imagesrcImagecolorImageidObj['imgId'] = d ? .image_id;
					img1 = oimg.set({
						image_id: d ? .image_id,
						colorCode: d ? .colorCode,
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
				}, {
					crossOrigin: 'anonymous'
				});
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
			if (e ? .target ? .classList ? .value === 'icon_clear' || e ? .target ? .children[0] ? .classList ? .value === 'icon_clear') {
				fileHandler('', '', '');
				document.getElementById('textInput').value = '';
				fontSize[0].value = '';
				canvas.clear();
				updateView();
			} else if (e ? .target ? .classList ? .value === 'icon_select' || e ? .target ? .children[0] ? .classList ? .value === 'icon_select') {
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

function changeArcValueEffect(curv_value) {
	selectEffect = curv_value;
	updateGroupText();
	updateView();
	undoRedoObject();
}
/*******************************End Select Arc Type *************************************/

/*******************************Start Only Number Functionality****************************/

// var bothSide = document.getElementById("cd_profile_check");
// if (bothSide) {
//   bothSide.addEventListener('click', (e) => {
//     finalDesignedData["printBothSide"] = e.target.checked;
//   });
// }

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
	selectCan.forEach((data) => {
		let imgObj = {};
		imgObj['imageWithoutDesign'] = data.imageUrl;
		frontBack.push(imgObj);
		if (data.canvas.getObjects().length) {
			const obj = {};
			data.canvas.discardActiveObject();
			obj.designWithImage = data.canvas.toDataURL();
			data.canvas.backgroundImage.opacity = 0;
			data.canvas.overlayImage.opacity = 0;
			obj.design = data.canvas.toDataURL();
			obj.canvasData = JSON.stringify(data.canvas);
			finalDesignArr.push(obj);
			data.canvas.backgroundImage.opacity = 1;
			data.canvas.overlayImage.opacity = 1;
			updateView();
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
var ExcelToJSON = function () {
	this.parseExcel = function (file) {
		var reader = new FileReader();
		reader.onload = function (e) {
			var data = e.target.result;
			var workbook = XLSX.read(data, {
				type: 'binary'
			});
			workbook.SheetNames.forEach(function (sheetName) {
				// Here is your object
				var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
				var json_object = JSON.stringify(XL_row_object);
				productList = JSON.parse(json_object);
				if (productList.length > 0) {
					setNameQuantityFields(productList);
				}
			})
		};
		reader.onerror = function (ex) {
			console.log("ex", ex);
		};
		reader.readAsBinaryString(file);
	};
};

function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object
	var xl2json = new ExcelToJSON();
	xl2json.parseExcel(files[0]);
}

if (document.getElementById('upload')) {
	document.getElementById('upload').addEventListener('change', handleFileSelect, false);
}


/*******************************Fetch Data From Excel****************************/

/***************************Start Add Name Field And Quantity Field **************/
function setNameQuantityFields(data) {
	document.getElementsByClassName("cd_clone_wrap_dynamic")[0].innerHTML = "";
	if (data.length > 0) {
		var fieldCount = 0;
		data ? .forEach((field) => {
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

/********************************Move To Next Page********************************/
function moveToNextPage() {
	addNameAndQuantity();
	finalDesignedData['user_id'] = user_id ? user_id : 0;
	finalDesignedData['design'] = [...finalDesignArr];
	finalDesignedData['variant_id'] = capTypeIds;
	finalDesignedData['totalColors'] = totalColorsViewWise;
	finalDesignedData['capTypeData'] = capTypeData;

	fetch(BASE_URL + '/store-temp-page-details', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				"result": finalDesignedData,
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
				window.location = designDetails;
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

/****************************Start on Load Function*******************/
function myFunction() {
	getProductIdFromURL();
	// finalDesignedData["printBothSide"] = false;
	document.getElementById("colorTypeBtn").disabled = true;
	document.getElementById("colorTypeBtn").style.opacity = 0.5;
	// document.getElementById("colorTypeBtnBack").disabled = true;
	// document.getElementById("colorTypeBtnBack").style.opacity = 0.5;
}

/****************************End on Load Function*******************/

/****************************Start Getting Data from Design Details Page********************/
function dataFromDesignDetails() {
	document.getElementById("loaderStartEnd").style.display = "flex";
	let url = BASE_URL + '/get-temp-page-details/' + user_id;
	fetch(url).then(data => {
		return data.json();
	}).then(post => {
		if (post.status) {
			document.getElementById("loaderStartEnd").style.display = "none";
			loadMySavedDesign(post ? .data ? .result ? .design, 0);
			document.getElementById("my_saved_Design").style.display = "none";
			document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
		}
	});
}
/****************************End Getting Data from Design Details Page********************/

/**********************************Start Color Functionality in Footer***********************/

function selectImageColorInFooter() {
	document.getElementById('imageColorPopupInFooter').style.display = "block";
}

function closeImageColorPopupInFooter() {
	document.getElementById('imageColorPopupInFooter').style.display = "none";
}


function addProductColor() {
	capTypeData ? .list ? .data ? .forEach(vari => {
		if (vari.id == defaultSelectedProduct ? .product_id) {
			varients = vari.variant;
		}
	})

	let color = '';
	varients ? .forEach(col => {
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
    id=${defaultSelectedProduct.front_image_url} alt="circle-box" onclick="setSelectedProductColorImage(${defaultSelectedProduct.id},${defaultSelectedProduct.product_id})"></div>`
		setBackgroundImageOnCanvas(document.querySelectorAll('.canvas'), 0);
		setChangeColorOverlayImage(['front_image_overlay_url', 'back_image_overlay_url'], 0);
	}
}
/************************End Backgroung Color Change Functionality in Footer****************************/