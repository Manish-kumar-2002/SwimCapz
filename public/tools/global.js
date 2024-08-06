/*************************************Start Maintain Ratio ****************************************/
const maintainRation = function (canvaswidth, canvasheight, maxWidth, maxHeight) {
  var canW = canvaswidth;
  var canH = canvasheight;
  var canR = 1;
  if ((canvaswidth <= maxWidth) && (canvasheight <= maxHeight)) {

  } else {
    var cR = canvaswidth / canvasheight;
    var mR = maxWidth / maxHeight;
    if (cR >= mR) {
      canW = maxWidth;
      canR = maxWidth / canvaswidth;
      canH = canR * canvasheight;
    } else {
      canH = maxHeight;
      canR = maxHeight / canvasheight;
      canW = canR * canvaswidth;
    }
  }
  return { canWidth: canW, canHeight: canH, canRatio: canR };
};


/*************************************End Maintain Ratio ****************************************/

/*************************************Start Get CapType Data Function****************************************/
function getCapTypeData() {
  fetch(BASE_URL + '/products').then(data => {
    return data.json();
  }).then(post => {
    capTypeData = post;
    if (capTypeData != undefined) {
      getDefaultProduct();
    } else {
      console.log("capTypeData error", capTypeData);
    }
  });
}
/*************************************End Get CapType Data Function****************************************/


/*************************************Start Get Ratio ****************************************/
function getRatio(newImage) {
  if (newImage) {
    const canvasParent = document.getElementById('back-ground-img');
    return maintainRation(newImage.width, newImage.height, canvasParent.clientHeight, canvasParent.clientWidth);
  }
  return { canWidth: 400, canHeight: 400, canRatio: 1 };
}

/*************************************End Get Ratio ****************************************/


function changeAfterBlurText() {
  getActiveCanvas();
  var activeObj = canvas.getActiveObject();
  if (activeObj && activeObj['textSrc'] !== originlaText) {
    originlaText = activeObj['textSrc'];
    undoRedoObject();
  }
};
/*************************************Start Create Canvas ****************************************/

function createCanvas(data, i) {
  if (i < data.length) {
    if (i !== activeView) {
      data[i].classList.add('hide');
    }
    const newImage = new Image();
    newImage.onload = () => {
      const ratio = getRatio(newImage);
      const candyCanvas = new fabric.Canvas(data[i].querySelector('canvas').id, {
        height: ratio.canHeight,
        width: ratio.canWidth
      });

      candyCanvas.on('object:modified', function (e) {
        updateView();
        undoRedoObject(e);
      });

      candyCanvas.on('object:removed', function () {
        updateView();
      });

      candyCanvas.on("object:updated", function () {
        updateView();
      });

      candyCanvas.on('mouse:up', function (event) {
        if (event.target) {
          if (event.target.name == "singleLineText") {
            currentGroup = event.target;
            document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
            document.getElementById("add_text_with_input_field").style.display = "block";
            document.getElementById("editLayerImagePopup").style.display = "none";
            setTextFields(event.target);
          }

          if (event?.target?._originalElement?.currentSrc) {
            document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
            document.getElementById("editLayerImagePopup").style.display = "block";
            document.getElementById("add_text_with_input_field").style.display = "none";
            document.getElementById("editLayerImage").src = `${event?.target?._originalElement?.currentSrc}`;
            editLayerImagePopupFunction(event?.target)
          }
          showAllActions();
        } else {
          removeTextFields();
          document.getElementById("editLayerImagePopup").style.display = "none";
          document.getElementById("add_text_with_input_field").style.display = "none";
          if (selectCan[activeView].canvas._objects.length > 0) {
            document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
          } else {
            document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
          }
        }
      });
      candyCanvas.setZoom(ratio.canRatio);
      fabric.Image.fromURL(data[i].querySelector('img').getAttribute('src'), function (img) {
        candyCanvas.setBackgroundImage(img, () => {
          candyCanvas.renderAll();
          const imageUrl = candyCanvas.toDataURL();
          selectCan.push({ canvas: candyCanvas, ratio, imageUrl });
          startIndexData[i] = -1;
          canvasUndoRedoObject[i] = [];
          undoRedoObject('previousData', i);
          setDefaultOverlayImage(candyCanvas, i);
          createCanvas(data, ++i);
        });
      }, { crossOrigin: 'anonymous' });
    };
    newImage.src = data[i].querySelector('img').getAttribute('src');
  } else {
    createSide();
    createProductVarient();
    addProductColor();
    getActiveCanvas();
    if (product_updated.length) {
      loadMySavedDesign(product_updated, 0);
    }
  }
}

/*********************************Start To Show Image And Color In Edit Layer*********************************/
function editLayerImagePopupFunction(activeObj) {
  let colors = [];
  for (let i = 0; i < activeObj.colorCode.length; i++) {
    let hex = rgbaToHex(activeObj.colorCode[i]);
    colors.push(hex);
  }
  showImageColorWhileEditing(colors);
}

function showImageColorWhileEditing(colors) {
  for (let i = 0; i < colors.length; i++) {
    if (colors[i] == '#00000000') {
      colors.splice(i, 1);
      i--;
    }
  }
  let updatedColors = [...new Set(colors)]
  let editColorListing = document.getElementById("edit_Image_colors");
  let editColorListingLength = document.getElementById("edit_Image_colors_length");
  editColorListing.innerHTML = '';
  editColorListingLength.innerHTML = "";
  if (updatedColors.length > 0) {
    editColorListingLength.innerHTML = `Colors In Use (${updatedColors.length})`;
    for (let j = 0; j < updatedColors.length; j++) {
      editColorListing.innerHTML += `<li class="color-bodr">
      <input checked="checked" type="radio" style="background-color:${colors[j]}" name="cap_color" onclick="changeImageColorWhileEditing(${j},'${colors[j]}')">
      </li> `;
    }
  }
}

/*********************************End To Show Image And Color In Edit Layer*********************************/
function setDefaultOverlayImage(canvas, _default = 0) {
  let overlay_img = defaultSelectedProduct.front_image_overlay_url;
  if (_default == 1) {
    overlay_img = defaultSelectedProduct.back_image_overlay_url;
  }

  fabric.Image.fromURL(overlay_img, function (img) {
    canvas.setOverlayImage(img, () => {
      canvas.renderAll();
    });
  }, { crossOrigin: 'anonymous' });
}
/*************************************End Create Canvas ****************************************/

/*************************************Start Create Side****************************************/
function createSide() {
  let str = '';
  selectCan.forEach((sc, ind) => {
    str += `<div class="popup-img"  id="front_view_${ind}" onclick="changeView(${ind})">
      <img src="${sc.imageUrl}">
      <div class="img-details">
        <h3>${selectCan[ind].canvas.lowerCanvasEl.id === 'front_canvas' ? 'Front View' : 'Back View'}</h3>
        <p>This is Circle Box Image</p>
      </div>
    </div>`;
    if (ind !== selectCan.length) {
      str += '<div class="horizontal-line"></div>';
    }
  });
  document.querySelector('.popup-overlay.popup-change_product_side .img-wrap').innerHTML = str;
}

/*************************************End Create Side ****************************************/

/*************************************Start Change View****************************************/
function changeView(ind) {
  activeView = ind;
  document.getElementsByClassName('cd_profile_type')[0].innerHTML = `${activeView == 0 ? 'Front View' : 'Back View'}`; document.querySelectorAll('.canvas').forEach((ele, index) => {
    ele.classList.add('hide');
    if (index === ind) {
      ele.classList.remove('hide');
      document.getElementsByClassName('popup-change_product_side')[0].style.display = 'none';
    }
  });
  getActiveCanvas();
  canvas.deactivateAll();
  canvas.renderAll();
  removeTextFields();
  undoRedoObject()
  if (canvas.getActiveObject()) {
  } else {
    document.getElementById("editLayerImagePopup").style.display = "none";
    document.getElementById("add_text_with_input_field").style.display = "none";
    if (selectCan[activeView].canvas._objects.length > 0) {
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
    } else {
      document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
    }
  }
  showImageAndTextInLayering();
  showImageAndTextColorInLayering();
}
/*************************************End Change View****************************************/

function createProductVarient() {
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
    color += `<input ${selected} type="radio" style="background-color:${col?.color_code}" name="cap_color" id="color-${col?.id}" 
    product_id="${col?.product_id}" onchange="changeColor(${col.id})"></input>`
  })
  document.getElementsByClassName("cd_clr_slider")[0].innerHTML = color;
}

function changeColor(varient_id) {
  const seletctProduct = varients.filter((data) => {
    return data.id === varient_id;
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

function setChangeColorOverlayImage(overlay, index) {
  if (index < overlay.length) {
    const candyCanvas = selectCan[index].canvas;
    fabric.Image.fromURL(defaultSelectedProduct[overlay[index]], function (img) {
      candyCanvas.setOverlayImage(img, () => {
        candyCanvas.renderAll();
        setChangeColorOverlayImage(overlay, ++index);
      });
    }, { crossOrigin: 'anonymous' });
  }
}

function setBackgroundImageOnCanvas(data, i) {
  if (i < data.length) {
    const newImage = new Image();
    newImage.onload = () => {
      const ratio = getRatio(newImage);
      fabric.Image.fromURL(data[i].querySelector('img').getAttribute('src'), function (img) {
        const candyCanvas = selectCan[i].canvas;
        candyCanvas.setBackgroundImage(img, () => {
          candyCanvas.setWidth(ratio.canWidth);
          candyCanvas.setHeight(ratio.canHeight);
          candyCanvas.setZoom(ratio.canRatio);
          candyCanvas.renderAll();
          const imageUrl = candyCanvas.toDataURL();
          selectCan[i].imageUrl = imageUrl;
          selectCan[i].ratio = ratio;
          document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[i].querySelector('img').setAttribute('src', imageUrl);
          setBackgroundImageOnCanvas(data, ++i);
        });
      }, { crossOrigin: 'anonymous' });
    };
    newImage.src = data[i].querySelector('img').getAttribute('src');
  } else {
    getActiveCanvas();
  }
}

function getActiveCanvas() {
  canvas = selectCan[activeView]?.canvas;
}

function updateView() {
  console.log("updateView.........");
  getActiveCanvas();
  var activeObj = canvas?.getActiveObject();
  var activeGroup = canvas?.getActiveGroup();
  if (activeObj) {
    canvas.discardActiveObject().renderAll();
  } else if (activeGroup) {
    canvas.discardActiveGroup().renderAll();
  }
  const imgUrl = canvas.toDataURL();
  selectCan[activeView].imageUrl = imgUrl;
  document.querySelectorAll('.popup-overlay.popup-change_product_side .popup-img')[activeView].querySelector('img').setAttribute('src', imgUrl);

  if (activeObj) {
    canvas.setActiveObject(activeObj);

  } else if (activeGroup) {
    var group = new fabric.Group();
    group.canvas = canvas;
    activeGroup['_objects'].forEach(function (object) {
      group.addWithUpdate(object);
    });
    canvas.setActiveGroup(group.setCoords()).renderAll();
  }
  chooseColors();
  showImageAndTextInLayering();
  showImageAndTextColorInLayering();
  printOnBothSide();
  if (activeObj) {
    editLayerImagePopupFunction(activeObj);
  }
  generateBase64ForEachObject();
};



/*********************************Start to get co-ordinate *******************************************/

function generateBase64ForEachObject() {
  let tempArr = [];
  canvas.forEachObject(function (obj) {
    convertObjectToBase64(obj);
  });
  function convertObjectToBase64(obj) {
    let tempObj = {};
    obj.clone(function (clonedObj) {
      var tempCanvas = new fabric.StaticCanvas(null, {
        width: clonedObj.width,
        height: clonedObj.height
      });
      clonedObj.set({
        left: clonedObj.width / 2,
        top: clonedObj.height / 2,
        originX: 'center',
        originY: 'center'
      });
      tempCanvas.add(clonedObj);
      let base64Image = tempCanvas.toDataURL({
        format: 'png'
      });
      tempObj['image'] = base64Image;
      tempObj['object'] = obj;
      tempArr.push(tempObj);
    });
  }
  coordinateArray[activeView == 0 ? "front" : 'back'] = tempArr;
}
/*********************************End to get co-ordinate**********************************************/

/***************************Start Print ON Both Side**************************/
function printOnBothSide() {
  if (selectCan[1] && selectCan[1].canvas._objects.length > 0) {
    document.getElementById('cd_profile_check').disabled = true;
    document.getElementsByClassName('cd_profile_check_wrap')[0].classList.add("check-dis");
  } else {
    document.getElementById('cd_profile_check').disabled = false;
    document.getElementsByClassName('cd_profile_check_wrap')[0].classList.remove("check-dis");
  }
}
/***************************End Print ON Both Side**************************/


/********************************Start Show Image and Text In Layering****************************/
function showImageAndTextInLayering() {
  console.log("showImageAndTextInLayering...");
  if (selectCan[activeView].canvas._objects.length > 0) {
    showNextPageButton();
    document.getElementsByClassName('cd_save_btn')[0].classList.remove("disable-btn");
    document.getElementsByClassName('cd_layer_listing')[0].innerHTML = '';
    selectCan[activeView].canvas._objects.forEach((o, index) => {
      let image_id = o.image_id;
      if (o._originalElement) {
        document.getElementsByClassName('cd_layer_listing')[0].innerHTML += `
        <li
          style="cursor:move;"
          class="listitemClass"
          id=${image_id}
          data-set=${index}
          onmousedown="sortOrderLayering(this.id,${index})"
          data-src="${o._originalElement?.src}"
          data-image_id="${o.image_id}"
          > 
          <div class="layering_dots">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" style="cursor : pointer">
                  <path
                      d="M15.5 17C16.3284 17 17 17.6716 17 18.5C17 19.3284 16.3284 20 15.5 20C14.6716 20 14 19.3284 14 18.5C14 17.6716 14.6716 17 15.5 17ZM8.5 17C9.32843 17 10 17.6716 10 18.5C10 19.3284 9.32843 20 8.5 20C7.67157 20 7 19.3284 7 18.5C7 17.6716 7.67157 17 8.5 17ZM15.5 10C16.3284 10 17 10.6716 17 11.5C17 12.3284 16.3284 13 15.5 13C14.6716 13 14 12.3284 14 11.5C14 10.6716 14.6716 10 15.5 10ZM8.5 10C9.32843 10 10 10.6716 10 11.5C10 12.3284 9.32843 13 8.5 13C7.67157 13 7 12.3284 7 11.5C7 10.6716 7.67157 10 8.5 10ZM15.5 3C16.3284 3 17 3.67157 17 4.5C17 5.32843 16.3284 6 15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3ZM8.5 3C9.32843 3 10 3.67157 10 4.5C10 5.32843 9.32843 6 8.5 6C7.67157 6 7 5.32843 7 4.5C7 3.67157 7.67157 3 8.5 3Z"
                      fill="#707070" />
              </svg>
              <div class="cd_image">
                  <img src=${o._originalElement?.src} alt="img">
              </div>
          </div>
          <div class="cd_edit_delete">
              <button
                style="cursor:pointer;"
                type="button"
                id=${image_id}
                data-src="${o._originalElement?.src}"
                onclick="editImage(this)"
                data-image_id="${o.image_id}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                      viewBox="0 0 24 24" fill="none" style="cursor : pointer">
                      <path
                          d="M20.9519 3.0481C19.5543 1.65058 17.2885 1.65064 15.8911 3.04825L3.94103 14.9997C3.5347 15.4061 3.2491 15.9172 3.116 16.4762L2.02041 21.0777C1.96009 21.3311 2.03552 21.5976 2.21968 21.7817C2.40385 21.9659 2.67037 22.0413 2.92373 21.981L7.52498 20.8855C8.08418 20.7523 8.59546 20.4666 9.00191 20.0601L20.952 8.10861C22.3493 6.71112 22.3493 4.4455 20.9519 3.0481ZM16.9518 4.10884C17.7634 3.29709 19.0795 3.29705 19.8912 4.10876C20.7028 4.9204 20.7029 6.23632 19.8913 7.04801L19 7.93946L16.0606 5.00012L16.9518 4.10884ZM15 6.06084L17.9394 9.00018L7.94119 18.9995C7.73104 19.2097 7.46668 19.3574 7.17755 19.4263L3.76191 20.2395L4.57521 16.8237C4.64402 16.5346 4.79168 16.2704 5.00175 16.0603L15 6.06084Z"
                          fill="#707070" />
                  </svg>
              </button>
              <button
                style="cursor:pointer;"
                type="button"
                id=${image_id}
                data-src="${o._originalElement?.src}"
                onclick="deleteImage(this.id)"
                data-image_id="${o.image_id}"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" style="cursor : pointer">
                      <path
                          d="M10 5H14C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5ZM8.5 5C8.5 3.067 10.067 1.5 12 1.5C13.933 1.5 15.5 3.067 15.5 5H21.25C21.6642 5 22 5.33579 22 5.75C22 6.16421 21.6642 6.5 21.25 6.5H19.9309L18.7589 18.6112C18.5729 20.5334 16.9575 22 15.0263 22H8.97369C7.04254 22 5.42715 20.5334 5.24113 18.6112L4.06908 6.5H2.75C2.33579 6.5 2 6.16421 2 5.75C2 5.33579 2.33579 5 2.75 5H8.5ZM10.5 9.75C10.5 9.33579 10.1642 9 9.75 9C9.33579 9 9 9.33579 9 9.75V17.25C9 17.6642 9.33579 18 9.75 18C10.1642 18 10.5 17.6642 10.5 17.25V9.75ZM14.25 9C14.6642 9 15 9.33579 15 9.75V17.25C15 17.6642 14.6642 18 14.25 18C13.8358 18 13.5 17.6642 13.5 17.25V9.75C13.5 9.33579 13.8358 9 14.25 9ZM6.73416 18.4667C6.84577 19.62 7.815 20.5 8.97369 20.5H15.0263C16.185 20.5 17.1542 19.62 17.2658 18.4667L18.4239 6.5H5.57608L6.73416 18.4667Z"
                          fill="#707070" />
                  </svg>
              </button>
          </div>
        </li>`;
      }

      if (o.textSrc != '') {
        document.getElementsByClassName('cd_layer_listing')[0].innerHTML += `
        <li
          style="cursor:move;"
          class="listitemClass"
          id=${image_id}
          data-text=${o.textSrc}
          data-set=${index}
          onmousedown="sortOrderLayering(this.id,${index})"
          data-image_id="${image_id}"
          >
          <div style="display:flex;align-items:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" style="cursor : pointer">
              <path
              d="M15.5 17C16.3284 17 17 17.6716 17 18.5C17 19.3284 16.3284 20 15.5 20C14.6716 20 14 19.3284 14 18.5C14 17.6716 14.6716 17 15.5 17ZM8.5 17C9.32843 17 10 17.6716 10 18.5C10 19.3284 9.32843 20 8.5 20C7.67157 20 7 19.3284 7 18.5C7 17.6716 7.67157 17 8.5 17ZM15.5 10C16.3284 10 17 10.6716 17 11.5C17 12.3284 16.3284 13 15.5 13C14.6716 13 14 12.3284 14 11.5C14 10.6716 14.6716 10 15.5 10ZM8.5 10C9.32843 10 10 10.6716 10 11.5C10 12.3284 9.32843 13 8.5 13C7.67157 13 7 12.3284 7 11.5C7 10.6716 7.67157 10 8.5 10ZM15.5 3C16.3284 3 17 3.67157 17 4.5C17 5.32843 16.3284 6 15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3ZM8.5 3C9.32843 3 10 3.67157 10 4.5C10 5.32843 9.32843 6 8.5 6C7.67157 6 7 5.32843 7 4.5C7 3.67157 7.67157 3 8.5 3Z"
              fill="#707070" />
            </svg>
     
            <p style="margin-left:15px;">${o.textSrc}</p>
          </div>
          <div class="cd_edit_delete">
            <button
              style="cursor:pointer;"
              type="button"
              id=${image_id}
              data-text=${o.textSrc}
              onclick="editText(this,${index})"
              data-image_id="${image_id}"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" style="cursor : pointer" >
                  <path
                      d="M20.9519 3.0481C19.5543 1.65058 17.2885 1.65064 15.8911 3.04825L3.94103 14.9997C3.5347 15.4061 3.2491 15.9172 3.116 16.4762L2.02041 21.0777C1.96009 21.3311 2.03552 21.5976 2.21968 21.7817C2.40385 21.9659 2.67037 22.0413 2.92373 21.981L7.52498 20.8855C8.08418 20.7523 8.59546 20.4666 9.00191 20.0601L20.952 8.10861C22.3493 6.71112 22.3493 4.4455 20.9519 3.0481ZM16.9518 4.10884C17.7634 3.29709 19.0795 3.29705 19.8912 4.10876C20.7028 4.9204 20.7029 6.23632 19.8913 7.04801L19 7.93946L16.0606 5.00012L16.9518 4.10884ZM15 6.06084L17.9394 9.00018L7.94119 18.9995C7.73104 19.2097 7.46668 19.3574 7.17755 19.4263L3.76191 20.2395L4.57521 16.8237C4.64402 16.5346 4.79168 16.2704 5.00175 16.0603L15 6.06084Z"
                      fill="#707070" />
              </svg>
            </button>
            <button
              style="cursor:pointer;"
              type="button"
              id=${image_id}
              data-text=${o.textSrc}
              onclick="deleteImage(this.id)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" style="cursor : pointer">
                  <path
                      d="M10 5H14C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5ZM8.5 5C8.5 3.067 10.067 1.5 12 1.5C13.933 1.5 15.5 3.067 15.5 5H21.25C21.6642 5 22 5.33579 22 5.75C22 6.16421 21.6642 6.5 21.25 6.5H19.9309L18.7589 18.6112C18.5729 20.5334 16.9575 22 15.0263 22H8.97369C7.04254 22 5.42715 20.5334 5.24113 18.6112L4.06908 6.5H2.75C2.33579 6.5 2 6.16421 2 5.75C2 5.33579 2.33579 5 2.75 5H8.5ZM10.5 9.75C10.5 9.33579 10.1642 9 9.75 9C9.33579 9 9 9.33579 9 9.75V17.25C9 17.6642 9.33579 18 9.75 18C10.1642 18 10.5 17.6642 10.5 17.25V9.75ZM14.25 9C14.6642 9 15 9.33579 15 9.75V17.25C15 17.6642 14.6642 18 14.25 18C13.8358 18 13.5 17.6642 13.5 17.25V9.75C13.5 9.33579 13.8358 9 14.25 9ZM6.73416 18.4667C6.84577 19.62 7.815 20.5 8.97369 20.5H15.0263C16.185 20.5 17.1542 19.62 17.2658 18.4667L18.4239 6.5H5.57608L6.73416 18.4667Z"
                      fill="#707070" />
              </svg>
            </button>
          </div>
        </li>`
      }
    })
  } else {
    hideNextPageButton();
    document.getElementById("editLayerImage").src = '';
    document.getElementById("editLayerImagePopup").style.display = "none";
    document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
    document.getElementsByClassName('cd_layer_listing')[0].innerHTML = '';
  }
}
/********************************End Show Image and Text In Layering******************************/

/*********************************Start Show Next Page Button*************************************/
function showNextPageButton() {
  document.getElementById("moveToNextPageBtn").style.display = "block";
  document.getElementById("moveToNextPageBtn").style.opacity = 1;
  document.getElementById("moveToNextPageBtn").disabled = false;
}

function hideNextPageButton() {
  document.getElementById("moveToNextPageBtn").disabled = true;
  document.getElementById("moveToNextPageBtn").style.opacity = 0.5;
}
/*********************************Start Show Next Page Button*************************************/
/**************************Start Show Image and Text Color In Layering****************************/
function showImageAndTextColorInLayering() {
  let colors = [];
  let imgId = 0;
  if (selectCan[activeView].canvas._objects.length > 0) {
    selectCan[activeView].canvas._objects.forEach((o, index) => {
      imgId = o.image_id;
      if (o._originalElement) {
        for (let i = 0; i < o.colorCode.length; i++) {
          let hex = rgbaToHex(o.colorCode[i]);
          colors.push(hex);
        }
      }
      if (o.textSrc != '') {
        colors.push(o.fill);
        if (o.stroke != '' && o.strokeWidth > 0) {
          colors.push(o.stroke);
        }
      }
    })
  }
  nonChangableColors(colors, imgId);
}

function nonChangableColors(colors, imgId) {
  for (let i = 0; i < colors.length; i++) {
    if (colors[i] == '#00000000') {
      colors.splice(i, 1);
      i--;
    }
  }
  // let obj = {};
  // obj['imgId'] = imgId;
  let updatedColors = [...new Set(colors)];
  document.getElementById("nonChangableColorWithLayerParent").style.display = "block";
  let colorListing = document.getElementById("nonChangableColorWithLayer");
  let colorListingLength = document.getElementById("nonChangableColorWithLayerLength");
  colorListing.innerHTML = '';
  colorListingLength.innerHTML = '';
  if (updatedColors.length > 0) {
    // obj['colors'] = updatedColors;
    colorListingLength.innerHTML = `Colors In Use (${updatedColors.length})`;
    for (let j = 0; j < updatedColors.length; j++) {
      colorListing.innerHTML += `<li class="color-bodr">
      <input checked="checked" type="radio" style="background-color:${updatedColors[j]}" name="cap_color">
      </li> `;
    }
  }
  // uploadedImageColorAndImageId.push(obj);
}
/**************************Start Show Image and Text Color In Layering****************************/

function showAllActions() {
  let active = canvas.getActiveObject();
  if (active != null && active != undefined) {
    document.getElementById("all_actions_one").style.display = "block";
    document.getElementById("all_actions_two").style.display = "block";
  } else {
    document.getElementById("all_actions_one").style.display = "none";
    document.getElementById("all_actions_two").style.display = "none";
  }

}

function chooseColors() {
  console.log("chooseColors.........");
  let arr1 = [];
  let arr2 = [];
  if (selectCan[0]?.canvas?._objects?.length > 0) {
    selectCan[0]?.canvas?._objects?.forEach((o, index) => {
      if (o._originalElement) {
        for (let i = 0; i < o.colorCode.length; i++) {
          let hex = rgbaToHex(o.colorCode[i]);
          arr1.push(hex);
        }
      }
      if (o.textSrc != '') {
        arr1.push(o.fill);
        if (o.stroke != '' && o.strokeWidth > 0) {
          arr1.push(o.stroke);
        }
      }
    })
  }
  if (selectCan[1]?.canvas?._objects?.length > 0) {
    selectCan[1]?.canvas?._objects?.forEach((o, index) => {
      if (o._originalElement) {
        for (let i = 0; i < o.colorCode.length; i++) {
          let hex = rgbaToHex(o.colorCode[i]);
          arr2.push(hex);
        }
      }
      if (o.textSrc != '') {
        arr2.push(o.fill);
        if (o.stroke != '' && o.strokeWidth > 0) {
          arr2.push(o.stroke);
        }
      }
    })
  }
  totalColorsViewWise[0] = arr1;
  totalColorsViewWise[1] = arr2;
}

/************************Start Convert Rgba To Hex*******************************/
function rgbaToHex(arr) {
  var outParts = [
    arr[0].toString(16),
    arr[1].toString(16),
    arr[2].toString(16),
    Math.round(arr[3] * 255).toString(16).substring(0, 2)
  ];
  outParts.forEach(function (part, i) {
    if (part.length === 1) {
      outParts[i] = '0' + part;
    }
  })
  return ('#' + outParts.join(''));
}

/************************End Convert Rgba To Hex*******************************/

/*********************Start Delete Image Functionality************************/
function deleteImage(id) {
  if (selectCan[activeView].canvas._objects.length > 0) {
    selectCan[activeView].canvas._objects.forEach((o) => {
      if (o.image_id == id) {
        if (o.image_id == id) {
          selectCan[activeView].canvas.remove(o);
          showImageAndTextInLayering();
        }
      }
      if (o.textSrc) {
        if (o.image_id == id) {
          selectCan[activeView].canvas.remove(o);
          showImageAndTextInLayering();
        }
      }
    })
  }
}
/*********************End Delete Image Functionality************************/

/*************************Start Edit Text unctionality *********************/
function editText(that, ind) {
  if (selectCan[activeView].canvas._objects.length > 0) {
    selectCan[activeView].canvas._objects.forEach((o, index) => {
      if (o.image_id == that.id && index == ind) {
        text_val = o.textSrc;
        canvas.setActiveObject(o);
        showImageAndTextInLayering();
        setTextFields(o);
        document.getElementById("showUploadedImageAndTextInLayer").style.display = "none";
        document.getElementById("add_text_with_input_field").style.display = "block";
      }
    })
  }
}
/*************************End Edit Text unctionality *********************/

/***************************Start Sortable Functionality*******************************/

$(function () {
  if ($("#layerListId").length > 0) {
    $("#layerListId").sortable({
      update: function (event, ui) {
        getIdsOfLayer();
      }
    });
  }
});

function getIdsOfLayer() {
  var values = [];
  $('.listitemClass').each(function () {
    values.push(parseInt($(this).attr("data-set")));
  });
  let index = values.indexOf(currentIndex);
  let activeObj = canvas.getActiveObject();
  if (activeObj != null && activeObj != undefined) {
    activeObj.moveTo(index);
    canvas.discardActiveObject(activeObj).renderAll();
  }
}


let currentIndex = 0;
function sortOrderLayering(txt, ind) {
  currentIndex = ind;
  if (selectCan[activeView].canvas._objects.length > 0) {
    selectCan[activeView].canvas._objects.forEach((o) => {
      if (o.image_id) {
        if (o.image_id == txt) {
          canvas.setActiveObject(o);
          console.log("Active Object.........");
        }
      }
      if (o.textSrc) {
        if (o.textSrc == txt) {
          canvas.setActiveObject(o);
          console.log("Active Text.........");
          setTextFields(o);
        }
      }
    })
  }
}

/***************************End Sortable Functionality*******************************/


/**********************************Start setTextFields function*********************/
function setTextFields(obj) {
  document.getElementById('textInput').value = obj.textSrc;
  text_val = obj.textSrc;

  document.getElementById("colorPicker").value = obj._objects[0].fill;
  textColor = obj._objects[0].fill;

  document.getElementsByClassName("font-family-ulli")[0].value = obj._objects[0].fontFamily;
  font_Family = obj._objects[0].fontFamily;

  for (let i = 0; i < document.getElementsByClassName("font-family-ulli")[0].children.length; i++) {
    if (document.getElementsByClassName("font-family-ulli")[0].children[i].lastElementChild.id === obj._objects[0].fontFamily) {
      document.getElementsByClassName("font-family-ulli")[0].children[i].querySelector(".arctype-img").classList.add("font-family-style");
    }
  }

  document.getElementsByClassName("arctype-listing")[0].value = obj.globalEffect;
  selectEffect = obj.globalEffect;

  for (let i = 0; i < document.getElementsByClassName("arctype-listing")[0].children.length; i++) {
    if (document.getElementsByClassName("arctype-listing")[0].children[i].querySelector("span").textContent === obj.globalEffect) {
      document.getElementsByClassName("arctype-listing")[0].children[i].querySelector("a").classList.add("font-family-style")
    }
  }

  document.getElementById("range").value = obj.txtSpacing;
  document.getElementById("rangevalue").value = obj.txtSpacing;
  txtSpacing = obj.txtSpacing;

  document.getElementById("range2").value = obj.strokeWidth;
  document.getElementById("rangevalue2").value = obj.strokeWidth;
  ShowOutline = obj.strokeWidth;

  document.getElementById("outlineColorPicker").value = obj.stroke;
  strokeColor = obj.stroke;
}

/**********************************End setTextFields function***********************/

/**************************Start EmptyTextFields*******************************/
function removeTextFields() {
  document.getElementById("textInput").value = '';
  text_val = '';
  document.getElementsByClassName("font-family-ulli")[0].value = '';
  font_Family = '';

  for (let i = 0; i < document.getElementsByClassName("font-family-ulli")[0].children.length; i++) {
    if (document.getElementsByClassName("font-family-ulli")[0].children[i].querySelector(".arctype-img").classList.contains("font-family-style")) {
      document.getElementsByClassName("font-family-ulli")[0].children[i].querySelector(".arctype-img").classList.remove("font-family-style");
    }
  }

  document.getElementById("colorPicker").value = '#b0adf3';
  textColor = '#b0adf3';
  document.getElementsByClassName("arctype-listing")[0].value = 'Select Arc Type';
  selectEffect = 'straight';
  for (let i = 0; i < document.getElementsByClassName("arctype-listing")[0].children.length; i++) {
    if (document.getElementsByClassName("arctype-listing")[0].children[i].querySelector(".arctypeimg-wrap").classList.contains("font-family-style")) {
      document.getElementsByClassName("arctype-listing")[0].children[i].querySelector(".arctypeimg-wrap").classList.remove("font-family-style")
    }
  }
  document.getElementById("range").value = 0;
  document.getElementById("rangevalue").value = 0;
  txtSpacing = 0;
  document.getElementById("range2").value = 0;
  document.getElementById("rangevalue2").value = 0;
  ShowOutline = 0;
  document.getElementById("outlineColorPicker").value = '#f5f5f5';
  strokeColor = '#f5f5f5';
}
/**************************End EmptyTextFields*********************************/

/*****************************Undo Redo Start******************************************/

let canvasUndoRedoObject = [];
let startIndexData = [];
function undoRedoObject(str, i) {
  var currentInd = activeView;
  if (str && typeof str === 'string') {
    currentInd = i;
  }
  var currentCan = selectCan[currentInd].canvas;
  startIndexData[currentInd]++;
  canvasUndoRedoObject[currentInd][startIndexData[currentInd]] = JSON.stringify(currentCan);
};

loadCanvasJsonData = function (data) {
  if (data) {
    var currentCan = selectCan[activeView].canvas;
    currentCan.clear();
    currentCan.setOverlayImage(null, function () {
      dummyCanvas.setBackgroundImage(null, function () {
        dummyCanvas.setOverlayImage(null, function () {
          dummyCanvas.loadFromJSON(data, function () {
            dummyCanvas.forEachObject(function (obj) {
              currentCan.add(obj.setCoords()).renderAll().calcOffset();
            });
            currentCan.deactivateAll();
            if (dummyCanvas['overlayImage']) {
              currentCan['overlayImage'] = dummyCanvas['overlayImage'];
              currentCan.renderAll().calcOffset();
            }
            removeTextFields();
            document.getElementById("showUploadedImageAndTextInLayer").style.display = "block";
            showImageAndTextInLayering();
            showImageAndTextColorInLayering();
          });
        });
      });
    });
  }
};

undoRedoFunctionality = function (str) {
  if (str === 'undo') {
    if (canvasUndoRedoObject[activeView]['length']) {
      if (startIndexData[activeView] > 0) {
        startIndexData[activeView]--;
        loadCanvasJsonData(canvasUndoRedoObject[activeView][startIndexData[activeView]]);
      }
    }
  } else if (str === 'redo') {
    if (canvasUndoRedoObject[activeView]['length'] - 1 > startIndexData[activeView]) {
      startIndexData[activeView]++;
      loadCanvasJsonData(canvasUndoRedoObject[activeView][startIndexData[activeView]]);
    }
  }
};