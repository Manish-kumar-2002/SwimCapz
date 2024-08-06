let box = document.getElementById("back-ground-img");
let width = box.offsetWidth;
let height = box.offsetHeight;
let scaledWidth = 0;
let scaledHeight = 0;
var canvas = new fabric.Canvas('canvas');
var dummyCanvas = new fabric.Canvas('dummyCanvas');
canvas.setWidth(width);
canvas.setHeight(height);
// save();
// saveCurrentState();
canvas.setBackgroundImage(basePath +'assets/images/circle-box.svg', () => {
    canvas.renderAll();
    save();
    saveCurrentState();
    viewImage(canvas.toDataURL());
}, {
    originX: 'left',
    originY: 'top',
    left: 0,
    top: 0
})


/**************************************************************************************/
function myFunction() {
    document.getElementsByClassName("popup-overlay popup-upload-img")[0].style.display = "none";
    document.querySelector(".popup-change_product_side").style.display = "none";
}

/**************************************************************************************/


var selected_varient = "circle-box.svg";
var select_capType = document.getElementsByClassName("popup-button-captype");
select_capType[0].addEventListener('click', () => {
    if (document.getElementsByClassName('captype-listing')[0].style.display === 'block') {
        document.getElementsByClassName('captype-listing')[0].style.display = 'none';
    } else {
        document.getElementsByClassName('captype-listing')[0].style.display = 'block';
    }
});



var selectCaptypeImg = document.querySelector(".select-captype-img");
var capTypeElement = document.getElementsByClassName("captype-listing");
for (let i = 0; i < capTypeElement[0].children.length; i++) {
    capTypeElement[0].children[i].addEventListener('click', (element) => {
        var currentSrc = element?.target?.querySelector('img').src.split('/')[3] + "/" + element?.target?.querySelector('img').src.split('/')[4] + "/" + element?.target?.querySelector('img').src.split('/')[5];
        selected_varient = element?.target?.querySelector('img').src.split('/')[5];
        selectCaptypeImg.src = currentSrc;
        canvas.setBackgroundImage(currentSrc, () => {
            canvas.renderAll();
            // document.getElementsByClassName('captype-listing')[0].style.display = 'none';
            save();
            saveCurrentState();
            viewImage(canvas.toDataURL());
        })
    })
}

var colorTypeElement = document.getElementById("colorType");
var capTypeElement = document.getElementById("capType");
var capTypeNextBtn = document.getElementById("capTypeBtn");
capTypeNextBtn.addEventListener('click', () => {
    capTypeElement.classList.add('cd_active');
    capTypeElement.children[1].classList.remove("cd_current");
    colorTypeElement.classList.add('cd_active');
    colorTypeElement.children[1].classList.add('cd_current');
    $('.cd_clr_slider').slick('refresh');
})


var col_v_circle1 = basePath + "assets/images/circle-box.svg";
var col_v_circle2 = basePath + "assets/images/circle-box1.svg";
var col_v_circle3 = basePath +"assets/images/circle-box2.svg";
var col_v_circle4 = basePath +"assets/images/circle-box3.svg";
var col_v_circle5 = basePath +"assets/images/circle-box4.svg";
var col_v_circle6 = basePath +"assets/images/circle-box5.svg";
var col_v_circle7 = basePath +"assets/images/circle-box6.svg";
var varient_circle = [col_v_circle1, col_v_circle2, col_v_circle3, col_v_circle4, col_v_circle5, col_v_circle6, col_v_circle7];


var col_v_simple1 = basePath +"assets/images/simplecap.svg";
var col_v_simple2 = basePath +"assets/images/simplecap1.svg";
var col_v_simple3 = basePath +"assets/images/simplecap2.svg";
var col_v_simple4 = basePath +"assets/images/simplecap3.svg";
var col_v_simple5 = basePath +"assets/images/simplecap4.svg";
var col_v_simple6 = basePath +"assets/images/simplecap5.svg";
var col_v_simple7 = basePath +"assets/images/simplecap6.svg";
var varient_simple = [col_v_simple1, col_v_simple2, col_v_simple3, col_v_simple4, col_v_simple5, col_v_simple6, col_v_simple7];




var col_v_design1 = basePath +"assets/images/designcap.svg";
var col_v_design2 = basePath +"assets/images/designcap1.svg";
var col_v_design3 = basePath +"assets/images/designcap2.svg";
var col_v_design4 = basePath +"assets/images/designcap3.svg";
var col_v_design5 = basePath +"assets/images/designcap4.svg";
var col_v_design6 = basePath +"assets/images/designcap5.svg";
var col_v_design7 = basePath +"assets/images/designcap6.svg";
var varient_design = [col_v_design1, col_v_design2, col_v_design3, col_v_design4, col_v_design5, col_v_design6, col_v_design7];


var col_v_mask1 = basePath +"assets/images/mask.svg";
var col_v_mask2 = basePath +"assets/images/mask1.svg";
var col_v_mask3 = basePath +"assets/images/mask2.svg";
var col_v_mask4 = basePath +"assets/images/mask3.svg";
var col_v_mask5 = basePath +"assets/images/mask4.svg";
var col_v_mask6 = basePath +"assets/images/mask5.svg";
var col_v_mask7 = basePath +"assets/images/mask6.svg";
var varient_mask = [col_v_mask1, col_v_mask2, col_v_mask3, col_v_mask4, col_v_mask5, col_v_mask6, col_v_mask7];


var colorInputTypeRadio = document.getElementsByClassName("cd_clr_slider");
for (let i = 0; i < colorInputTypeRadio[0].children.length; i++) {
    colorInputTypeRadio[0].children[i].addEventListener('click', (e) => {
        selectedColorId = e.target.id;
        if (selected_varient == 'designcap.svg') {
            if (selectedColorId == 'color-1') {
                canvas.setBackgroundImage(varient_design[0], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-2') {
                canvas.setBackgroundImage(varient_design[1], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-3') {
                canvas.setBackgroundImage(varient_design[2], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-4') {
                canvas.setBackgroundImage(varient_design[3], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-5') {
                canvas.setBackgroundImage(varient_design[4], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-6') {
                canvas.setBackgroundImage(varient_design[5], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-7') {
                canvas.setBackgroundImage(varient_design[6], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            }
        } else if (selected_varient == 'circle-box.svg') {
            if (selectedColorId == 'color-1') {
                canvas.setBackgroundImage(varient_circle[0], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-2') {
                canvas.setBackgroundImage(varient_circle[1], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-3') {
                canvas.setBackgroundImage(varient_circle[2], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-4') {
                canvas.setBackgroundImage(varient_circle[3], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-5') {
                canvas.setBackgroundImage(varient_circle[4], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-6') {
                canvas.setBackgroundImage(varient_circle[5], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-7') {
                canvas.setBackgroundImage(varient_circle[6], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            }
        } else if (selected_varient == 'mask.svg') {
            if (selectedColorId == 'color-1') {
                canvas.setBackgroundImage(varient_mask[0], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-2') {
                canvas.setBackgroundImage(varient_mask[1], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-3') {
                canvas.setBackgroundImage(varient_mask[2], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-4') {
                canvas.setBackgroundImage(varient_mask[3], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-5') {
                canvas.setBackgroundImage(varient_mask[4], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-6') {
                canvas.setBackgroundImage(varient_mask[5], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-7') {
                canvas.setBackgroundImage(varient_mask[6], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            }
        } else if (selected_varient == 'simplecap.svg') {
            if (selectedColorId == 'color-1') {
                canvas.setBackgroundImage(varient_simple[0], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-2') {
                canvas.setBackgroundImage(varient_simple[1], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-3') {
                canvas.setBackgroundImage(varient_simple[2], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-4') {
                canvas.setBackgroundImage(varient_simple[3], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-5') {
                canvas.setBackgroundImage(varient_simple[4], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-6') {
                canvas.setBackgroundImage(varient_simple[5], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            } else if (selectedColorId == 'color-7') {
                canvas.setBackgroundImage(varient_simple[6], () => {
                    canvas.renderAll();
                    save();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                })
            }
        }

    })
};

var uploadImageElement = document.getElementById("upload-image");
var addTextElement = document.getElementById("add-text");
var addNameElement = document.getElementById("add-name");
var colorTypeNextBtn = document.getElementById("colorTypeBtn");

colorTypeNextBtn.addEventListener('click', () => {
    colorTypeElement.classList.add('cd_active');
    colorTypeElement.children[1].classList.remove('cd_current');
    uploadImageElement.classList.add('cd_active');
    uploadImageElement.children[1].classList.add('cd_current');
});

var colorTypeBackBtn = document.getElementById("colorTypeBtnBack");
colorTypeBackBtn.addEventListener('click', () => {
    capTypeElement.children[1].classList.add('cd_current');
    colorTypeElement.children[1].classList.remove('cd_current');
});

function updateTextProp() {
    text_val = "new text"
    updateGroupText();
    save();
    viewImage(canvas.toDataURL());
    saveCurrentState();
    return false;
}

var fontSize = document.getElementsByClassName("font-size-select");
fontSize[0].addEventListener('change', (e) => {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
        font_Family = e.target.value;
        updateGroupText();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }
});

const input = document.getElementById("colorPicker");
input.addEventListener("input", setColor);
function setColor() {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
        textColor = input.value;
        updateGroupText();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }
}

var letterSpacing = document.getElementById("range");
letterSpacing.addEventListener('input', (e) => {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
        txtSpacing = parseInt(e.target.value, 10);
        updateGroupText();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }
})

const outlineInput = document.getElementById("outlineColorPicker");
outlineInput.addEventListener("input", setOutlineColor);
function setOutlineColor() {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
        strokeColor = outlineInput.value;
        updateGroupText();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }
}

var outlet = document.getElementById("range2");
outlet.addEventListener('input', (e) => {
    if (canvas.getActiveObject() == 'null' || canvas.getActiveObject() == undefined) {

    } else {
        ShowOutline = e.target.value;
        updateGroupText();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }
})

var textTypeNextBtn = document.getElementById("textTypeBtn");
textTypeNextBtn.addEventListener('click', () => {
    addTextElement.classList.add('cd_active');
    addTextElement.children[1].classList.remove('cd_current');
    addNameElement.classList.add('cd_active');
    addNameElement.children[1].classList.add('cd_current');
})

var textTypeBackBtn = document.getElementById("textTypeBtnBack");
textTypeBackBtn.addEventListener('click', () => {
    uploadImageElement.children[1].classList.add('cd_current');
    addTextElement.children[1].classList.remove('cd_current');
})


var nameTypeBackBtn = document.getElementById("nameTypeBtnBack");
nameTypeBackBtn.addEventListener('click', () => {
    addTextElement.children[1].classList.add('cd_current');
    addNameElement.children[1].classList.remove('cd_current');
});

canvas.renderAll();

/****************Drag And Drop File********************/
var uploadButton = document.getElementById("cd_select");
var img_container = document.querySelector(".cd_select_file_img");
var error = document.getElementById("error");
var imageDisplay = document.getElementById("image-display");
var imageContainer = document.createElement("figure");
var cropImageUrl = "";
var cropper = "";
var img1 = "";

const fileHandler = (file, name, type) => {
    if (file == '' && name == "" && type == '') {
        cropImageUrl = "";
        name = '';
        document.getElementById("cropImage").setAttribute("src", `${cropImageUrl}`);
        imageContainer.innerHTML = `<figcaption>${name}</figcaption>`;
        imageDisplay.appendChild(imageContainer);
    } else {
        if (type.split("/")[0] !== "image") {
            //File Type Error
            error.innerText = "Please upload an image file";
            return false;
        } else if (type.split("/")[1] !== "png" || type.split("/")[1] !== "jpg" || type.split("/")[1] !== "pdf") {
            error.innerText = "";
            let reader = new FileReader();
            reader.onloadend = () => {
                cropImageUrl = reader.result;
                document.getElementById("cropImage").setAttribute("src", `${cropImageUrl}`);
                document.getElementsByClassName("popup-overlay popup-upload-img")[0].style.display = "block";
                const crop_image = document.getElementById("cropImage");
                cropper = new Cropper(crop_image, {
                    aspectRatio: 0,
                    viewMode: 0
                })
                imageContainer.innerHTML = `<figcaption>${name}</figcaption>`;
                imageDisplay.appendChild(imageContainer);
            };
            reader.readAsDataURL(file);
        }
    }
};


/**************************Crop Image Start******************/
document.getElementById("cropImageBtn").addEventListener('click', () => {
    var cropedImage = cropper.getCroppedCanvas().toDataURL(cropImageUrl);
    fabric.Image.fromURL(`${cropedImage}`, function (oimg) {
        img1 = oimg.scale(0.1).set({ left: 80, top: 90 });
        oimg.scaleToWidth(250);
        oimg.scaleToHeight(200);
        canvas.add(img1).setActiveObject(img1);
        canvas.renderAll();
        save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    })
    document.getElementsByClassName("popup-overlay popup-upload-img")[0].style.display = "none";
    imageContainer.innerHTML = `<figcaption>${''}</figcaption>`;
    imageDisplay.appendChild(imageContainer);
    cropper.destroy();
});

/**************************Crop Image End********************/


var actions = document.getElementsByClassName("cd_actions_listing");
var actionsChildren = actions[0].children;
for (let i = 0; i < actionsChildren.length; i++) {
    actionsChildren[i].addEventListener('click', (e) => {
        const img1 = canvas.getActiveObject();
        if (canvas.getActiveObject()) {
            if (e.target.classList.value === 'icon_vertical_mirror' && img1 !== '') {
                img1.set('flipX', !img1.flipX);
                canvas.add(img1);
                canvas.renderAll();
                save();
                viewImage(canvas.toDataURL());
                undoRedoObject();
                saveCurrentState();
            } else if (e.target.classList.value == 'icon_horizontal_mirror' && img1 !== '') {
                img1.set('flipY', !img1.flipY);
                canvas.add(img1);
                canvas.renderAll();
                save();
                viewImage(canvas.toDataURL());
                undoRedoObject();
                saveCurrentState();
            } else if (e.target.classList.value == 'icon_vertical_arrow' && img1 !== '') {
                img1.set("left", 80);
                // img1.set("left",(canvas['width'] / 2));
                canvas.add(img1);
                canvas.renderAll();
                save();
                viewImage(canvas.toDataURL());
                undoRedoObject();
                saveCurrentState();
            } else if (e.target.classList.value == 'icon_horizontal_arrow' && img1 !== '') {
                img1.set("top",90);
                // img1.set("top",(canvas['height'] / 2));
                canvas.add(img1);
                canvas.renderAll();
                save();
                viewImage(canvas.toDataURL());
                undoRedoObject();
                saveCurrentState();
            }
        }
    });
}

function Copy() {
    if (canvas.getActiveObject()) {
        canvas.getActiveObject().clone(function (cloned) {
            _clipboard = cloned;
        });
    }

}

function Paste() {
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
                    // save();
                    // viewImage(canvas.toDataURL());
                    // saveCurrentState();
                });
                clonedObj.setCoords();
            } else {
                canvas.add(clonedObj);
                canvas.renderAll();
                // save();
                // viewImage(canvas.toDataURL());
                // saveCurrentState();
            }
            _clipboard.top += 10;
            _clipboard.left += 10;
            canvas.setActiveObject(clonedObj);
            undoRedoObject();
            canvas.renderAll();
        });
    }
}



canvas.renderAll();

//Upload Button
uploadButton.addEventListener("change", () => {
    imageDisplay.innerHTML = "";
    Array.from(uploadButton.files).forEach((file) => {
        fileHandler(file, file.name, file.type);
    });
});

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


var imageTypeNextBtn = document.getElementById("imageTypeBtn");
imageTypeNextBtn.addEventListener('click', () => {
    uploadImageElement.classList.add('cd_active');
    uploadImageElement.children[1].classList.remove('cd_current');
    addTextElement.classList.add('cd_active');
    addTextElement.children[1].classList.add('cd_current');
    updateTextProp();
    document.getElementById('textInput').value = 'new text';
})


var imageTypeBackBtn = document.getElementById("imageTypeBtnBack");
imageTypeBackBtn.addEventListener('click', () => {
    colorTypeElement.children[1].classList.add('cd_current');
    uploadImageElement.children[1].classList.remove('cd_current');
})

/**********************Save The Design Start***********************************/
var saveDesignButton = document.getElementsByClassName("cd_save_btn");
saveDesignButton[0].addEventListener('click', generatePDF);
function generatePDF() {
    var imgData = canvas.toDataURL();
    setTimeout(() => {
        var element = document.getElementById('canvas-img');
        html2pdf().from(element).toPdf().get('pdf').then(function (pdf) {
            pdf.addImage(imgData, "PNG", 0, 10, canvas.getWidth() / 2, canvas.getHeight() / 2);
        }).save();
        viewImage(canvas.toDataURL());
        saveCurrentState();
    }, 0);
}

/**********************Save The Design End***********************************/

/************************UNDO REDO START*****************************/
var state;
var undo = [];
var redo = [];
function save() {
    redo = [];
    $('#redo').prop('disabled', true);
    if (state) {
        undo.push(state);
        $('#undo').prop('disabled', false);
    }
    state = JSON.stringify(canvas);
}

/**
   * Save the current state in the redo stack, reset to a state in the undo stack, and enable the buttons accordingly.
   * Or, do the opposite (redo vs. undo)
   * @param playStack which stack to get the last state from and to then render the canvas as
   * @param saveStack which stack to push current state into
   * @param buttonsOn jQuery selector. Enable these buttons.
   * @param buttonsOff jQuery selector. Disable these buttons.
   */

function replay(playStack, saveStack, buttonsOn, buttonsOff) {
    saveStack.push(state);
    state = playStack.pop();
    var on = $(buttonsOn);
    var off = $(buttonsOff);
    on.prop('disabled', true);
    off.prop('disabled', true);
    canvas.clear();
    canvas.loadFromJSON(state, function () {
        canvas.renderAll();
        // viewImage(canvas.toDataURL());
        // saveCurrentState();
        on.prop('disabled', false);
        if (playStack.length) {
            off.prop('disabled', false);
        }
    });
}

canvas.on('object:modified', function () {
    save();
});

$('#undo').click(function () {
    replay(undo, redo, '#redo', this);
});
$('#redo').click(function () {
    replay(redo, undo, '#undo', this);
})

/************************UNDO REDO END*****************************/

/************************SIDE CLEAR SELECTALL START*****************************/

var Side_Clear_SelectAll = document.getElementsByClassName("cd_bg_wrap");
var Side_Clear_SelectAll_Children = Side_Clear_SelectAll[0].children;
for (let i = 0; i < Side_Clear_SelectAll_Children.length; i++) {
    Side_Clear_SelectAll_Children[i].addEventListener('click', (e) => {
        if (e?.target?.classList?.value === 'icon_clear' || e?.target?.children[0]?.classList?.value === 'icon_clear') {
            fileHandler('', '', '');
            document.getElementById('textInput').value = '';
            fontSize[0].value = '';
            canvas.clear();
            canvas.setBackgroundImage(basePath + 'assets/images/circle-box.svg', () => {
                canvas.renderAll();
                save();
                viewImage(canvas.toDataURL());
                saveCurrentState();
            })
        } else if (e?.target?.classList?.value === 'icon_select' || e?.target?.children[0]?.classList?.value === 'icon_select') {
            canvas.discardActiveObject();
            if (canvas.getActiveObject() == null) {
                if (canvas.getObjects().length > 1) {
                    // let sel = new fabric.ActiveSelection(canvas.getObjects(), { canvas: canvas });
                    // canvas.setActiveObject(sel);
                    // canvas.renderAll();
                    var objs = canvas.getObjects().map(function (o) {
                        return o.set('active', true);
                    });
                    var group = new fabric.Group(objs, {
                        originX: 'center',
                        originY: 'center'
                    });
                    canvas._activeObject = null;
                    canvas.setActiveGroup(group.setCoords()).renderAll();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                } else if (canvas.getObjects().length == 1) {
                    canvas.setActiveObject(canvas.getObjects()[0]);
                    canvas.renderAll();
                    viewImage(canvas.toDataURL());
                    saveCurrentState();
                }
            }
        }
    })
}

/************************SIDE CLEAR SELECTALL END*****************************/

/*******************************Start Select Arc Type ******************************************/
var select_arcType = document.getElementsByClassName("popup-button-arctype");
select_arcType[0].addEventListener('click', (e) => {
    if (document.getElementsByClassName('arctype-listing')[0].style.display === 'block') {
        document.getElementsByClassName('arctype-listing')[0].style.display = 'none';
    } else {
        document.getElementsByClassName('arctype-listing')[0].style.display = 'block';
    }
})

function changeArcValueEffect(curv_value) {
    selecectEffect = curv_value;
    updateGroupText();
    save();
    viewImage(canvas.toDataURL());
    saveCurrentState();

}
/*******************************End Select Arc Type ******************************************/



/***************************Start Zoom Functionality******************************/
function zoom() {
    let z = document.getElementsByClassName("cd_left_block");
    if (z[0].classList.contains("cd_zoom")) {
        canvas.setWidth(canvas.get('width') - 100);
        canvas.setHeight(canvas.get('height') - 100);
    } else {
        canvas.setWidth(canvas.get('width') + 100);
        canvas.setHeight(canvas.get('height') + 100);
    }
}
/***************************End Zoom Functionality******************************/


/************************Start Image for front view******************************/
function viewImage(img_src) {
    let element = document.getElementById("front_back_view");
    let front_img = element.children[0].querySelector("img");
    let back_img = element.children[2].querySelector("img")
    front_img.src = img_src;
    back_img.src = img_src;
};
/************************End Image for front view******************************/

/************************start current state******************************/
var currentState;
function saveCurrentState() {
    currentState = JSON.stringify(canvas);
}
/************************End current state******************************/
/************************start front view******************************/
function showFrontView() {
    canvas.loadFromJSON(currentState, function () {
        canvas.renderAll();
    });
}
/************************end front view******************************/
