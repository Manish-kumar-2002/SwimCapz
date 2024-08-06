const textFunction = () => {
    text_val = document.getElementById("textInput").value;
    firstAddedeText = true;
    if(canvas.getActiveObject()) {
        firstAddedeText = false;
    }
    updateGroupText();
};
const updateGroupText = () => {
    var activeObj = canvas.getActiveObject();
    if (activeObj && ((activeObj['name'] === 'singleLineText') || (activeObj['name'] === 'mutliLineText'))) {
        if (text_val) {
            if (text_val.indexOf('\n') >= 0) {
                previousSelectedObject = angular.copy(currentGroup);
                if (activeObj['name'] === 'singleLineText') {
                    textIndexData['ind'] = canvas.getObjects().indexOf(currentGroup);
                    textIndexData['old'] = 'change';
                    canvas.remove(currentGroup);
                    var txt = new fabric.Text(text_val, {
                        'left': previousSelectedObject['left'],
                        'textSrc': text_val,
                        'top': previousSelectedObject['top'],
                        'scaleX': previousSelectedObject['scaleX'],
                        'scaleY': previousSelectedObject['scaleY'],
                        'originX': 'center',
                        'originY': 'center',
                        'name': 'mutliLineText',
                        'stroke': strokeColor ? strokeColor : '',
                        'strokeWidth': ShowOutline ? strokeWidth : 1,
                        'fontFamily' : font_Family ? font_Family : 'normal',
                        'fontWeight': fontStyleBoldObj ? 'normal' : 'bold',
                        'fontStyle': fontStyleItalicObj ? 'normal' : 'italic',
                        'fill': textColor ? textColor:'red',
                        'arcValue': arcValue,
                        'align': textAlignObj,
                        'txtSpacing': txtSpacing,
                    });
                    txt['price'] = factoryData.updateTextPrice(txtcolor);
                    currentGroup = txt;
                    canvas.add(txt).setActiveObject(txt).renderAll().calcOffset();
                    if (textIndexData['old'] === 'change') {
                        canvas.moveTo(txt, textIndexData['ind']);
                    }
                } else {
                    currentGroup.setText(text_val);
                    currentGroup['textSrc'] = text_val;
                    canvas.renderAll().calcOffset();
                }
            } else {
                originlaText = currentGroup['textSrc'];
                previousSelectedObject = {...currentGroup};
                canvas.remove(currentGroup);
                addTextFromTextArea();
            }
        } else {
            canvas.remove(activeObj);
        }
        canvas.renderAll();
    } else {
        originlaText = text_val;
        canvas.discardActiveObject()
        if (text_val) {
            addTextFromTextArea();
        } else {
            canvas.remove(currentGroup);
        }
    }
};
const addTextFromTextArea = () => {
    dummyCanvas.clear();
    var charList = [];
    if (text_val) {
        if (text_val.indexOf('\n') >= 0) {
            var txt = new fabric.Text(text_val, {
                'left': 0,
                'textSrc': text_val,
                'top': 0,
                'scaleX': 1,
                'scaleY': 1,
                'originX': 'center',
                'originY': 'center',
                'name': 'mutliLineText',
                'stroke': strokeColor ? strokeColor : '',
                'strokeWidth': ShowOutline ? ShowOutline: 1,
                'fontFamily' : font_Family ? font_Family : 'normal',
                'fill': textColor ? textColor:'red',
                'globalEffect': 'straight',
                'txtSpacing': txtSpacing,
            });
            currentGroup = txt;
            canvas.add(txt).setActiveObject(txt).renderAll().calcOffset();
        } else {
            for (var i = 0; i < text_val['length']; i++) {
                var txt = new fabric.Text(text_val[i], {
                    'fontFamily' : font_Family ? font_Family : 'normal',
                    'stroke': strokeColor ? strokeColor : '',
                    'strokeWidth':ShowOutline ? ShowOutline : 1,
                    'fill': textColor ? textColor:'red',
                    'originX': 'center',
                    'originY': 'center',
                    'txtSpacing': txtSpacing,
                });
                charList.push(txt);
            }
            currentGroup = new fabric.Group(charList, {
                'textSrc': text_val,
            });
            dummyCanvas.add(currentGroup).renderAll();
            arrange(txtSpacing);
        }
    } else {
        canvas.remove(currentGroup).renderAll();
    }
};
const arrange = (spa) => {
    var objects = currentGroup.getObjects();
    var pos = 0;
    var disLength = 0;
    var spacing = spa ? spa : 0;
    var squashFactor = 1.100000E+000 + spacing * 1.000000E-001;
    for (var i = 0; i < objects['length']; i++) {
        disLength += ((text_val[i] === ' ') ? objects[i]['width'] : objects[i]['width']) * 5.000000E-001 * squashFactor;
    }
    pos = -disLength;
    for (var k = 0; k < objects['length']; k++) {
        objects[k].set({
            'left': (pos + (objects[k]['width'] / 2)),
            'top': 0,
            'angle': 0
        });
        pos = pos + ((text_val[k] === ' ') ? objects[k]['width'] : objects[k]['width']) * squashFactor + spacing;
    }
    addUpdatedArcText();
};
const addUpdatedArcText = function () {
    var charList = currentGroup.getObjects();
    dummyCanvas.remove(currentGroup);
    currentGroup = new fabric.Group(charList, {
        'left': previousSelectedObject ? previousSelectedObject['left'] : currentGroup['left'],
        'textSrc': currentGroup['textSrc'],
        'stroke':strokeColor ? strokeColor : '',
        'strokeWidth': ShowOutline ? ShowOutline : 1,
        'top': previousSelectedObject ? previousSelectedObject['top'] : currentGroup['top'],
        'scaleX': previousSelectedObject ? previousSelectedObject['scaleX'] : currentGroup['scaleX'],
        'scaleY': previousSelectedObject ? previousSelectedObject['scaleY'] : currentGroup['scaleY'],
        'originX': 'center',
        'originY': 'center',
        'name': 'singleLineText',
        'fontFamily' : font_Family ? font_Family : 'normal',
        'fill': textColor ? textColor:'red',
        'txtSpacing': txtSpacing,
    });
    dummyCanvas.add(currentGroup).setActiveObject(currentGroup).renderAll();
    currentGroup.set({
        'globalEffect': selecectEffect,
        'txtSpacing': txtSpacing,
        'arcValue': arcValue,
        'fill': textColor ? textColor:'red',
        'fontFamily' : font_Family ? font_Family : 'normal',
    });
    currentGroup.setCoords();
    makeEffect();
    canvas.renderAll();
};
const makeEffect = () => {
    var val = arcValue;
    if (currentGroup && currentGroup['name'] === 'singleLineText') {
        currentGroup.set({
            arcValue: val,
            globalEffect: selecectEffect,
            txtSpacing: txtSpacing
        });
        
        if (selecectEffect === 'Bulge') {
            bulgeEffect(val);
        } else if (selecectEffect === 'Curve') {
            archEffect(val);
        } else if (selecectEffect === 'Arch') {
            curveEffect(val);
        } else if (selecectEffect === 'Wedge') {
            wedgeEffect(val);
        } else if (selecectEffect === 'Diagonal') {
            diagonalEffect(val);
        } else if (selecectEffect === 'Wave') {
            effectsEffect(val);
        } else if (selecectEffect === 'Effect') {
            waveEffect(val);
        } else if (selecectEffect === 'Upper') {
            upperEffect(val);
        } else if (selecectEffect === 'Roof') {
            roofEffect(val);
        } else if (selecectEffect === 'Bridge') {
            roofEffect(val, 'data');
        } else if (selecectEffect === 'Hwedge') {
            hWedgeEffect(val);
        } else if (selecectEffect === 'Slope') {
            skewXEffect(val, 'skewXEffect');
        } else if (selecectEffect === 'IText') {
            skewXEffect(val, 'skewYEffect');
        } else if (selecectEffect === 'SlopeIText') {
            skewXEffect(val, 'skewXYEffect');
        } else if (selecectEffect === 'Test') {
            skewXEffect(val, 'testEffect');
        } else if (selecectEffect === 'straight') {
            improvedUpdate(currentGroup.getObjects());
        }
    }
    canvas.renderAll();
};
const improvedUpdate = (arr) => {
    var grpObj = new fabric.Group(arr, {
        'left': currentGroup['left'],
        'angle': currentGroup['angle'],
        'top': currentGroup['top'],
        'scaleX': currentGroup['scaleX'] === 1.5 ? 2.0 : currentGroup['scaleX'],
        'scaleY': currentGroup['scaleY'] === 1.5 ? 2.0 : currentGroup['scaleX'],
        'lockUniScaling': false,
        'originX': 'center',
        'originY': 'center',
        'name': 'singleLineText',
        'stroke':strokeColor ? strokeColor : '',
        'strokeWidth': ShowOutline ? ShowOutline : 1,
        'arcValue': arcValue,
        'globalEffect': selecectEffect,
        'txtSpacing': txtSpacing,
        'fill': textColor ? textColor:'red',
    });
    currentGroup.set({
        'width': grpObj['width'],
        'height': grpObj['height'],
        'scaleX': grpObj['scaleX'],
        'scaleY': grpObj['scaleY'],
        'name': 'singleLineText',
    });
    var dumact = dummyCanvas.getObjects()[0];
    var act = canvas.getActiveObject();
    if (act) {
        canvas.remove(act);
    }
    canvas.add(dumact).setActiveObject(dumact).renderAll();
    if (firstAddedeText) {
        firstAddedeText = false;
        dumact.set({
            'left': (canvas['width'] / 2), // + (dummyratio['canWidth'] / 2),
            'top': (canvas['height'] / 2) // + (dummyratio['canHeight'] / 2)
        });
    }
    dumact['flipX'] = (previousSelectedObject && previousSelectedObject['flipX']) ? true : false;
    dumact['flipY'] = (previousSelectedObject && previousSelectedObject['flipY']) ? true : false;
    dumact['angle'] = (previousSelectedObject) ? previousSelectedObject['angle'] : 0;
    currentGroup.setCoords();
    canvas.calcOffset();
};
const getAngle = (rad) => {
    return rad * Math.PI / 180;
};
const bulgeEffect = (val) => {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var charList = [];
    var objVal = ((Math.abs(val)) / 100) * (90 / 100) * objHeight;
    for (var i = 0; i < object['length']; i++) {
        var h = objVal * Math.sin(getAngle((i + 1) * 180 / (object['length'])));
        var heig = (val >= 0) ? (objHeight - h) : (objHeight - objVal + h);
        var scaleY = heig * 1 / objHeight;
        object[i].set({
            scaleY: scaleY
        });
        charList.push(object[i]);
    }
    improvedUpdate(charList);
};
const archEffect = (val) => {
    var object = currentGroup.getObjects();
    var objVal = ((Math.abs(val)) / 100) * (90 / 100) * currentGroup.getHeight();
    var charList = [];
    for (var i = 0; i < object['length']; i++) {
        var h = objVal * Math.sin(getAngle((i + 1) * 180 / (object['length'] + 1)));
        var heig = (val >= 0) ? (currentGroup['top'] - h) : (currentGroup['top'] - objVal + h);
        object[i].set({
            top: (currentGroup['top'] - heig) / 2
        });
        charList.push(object[i]);
    }
    improvedUpdate(charList);
};
const curveEffect = (archValue) => {
    var distortPercent = Math.abs(archValue);
    var distortValue = distortPercent * 0.005;
    if (distortPercent === 0) {
        var objects = currentGroup.getObjects();
        improvedUpdate(objects);
        return;
    }
    var squashFactor = 1.1 - distortValue + currentGroup['txtSpacing'] * 0.1;
    drawFullArch(archValue, distortValue, squashFactor);
};
const drawFullArch = (archValue, distortValue, squashFactor) => {
    var objects = currentGroup.getObjects();
    var charList = [];
    var disLength = 0;
    for (var i = 0; i < objects['length']; i++) {
        disLength = disLength + ((text_val[i] === ' ') ? objects[i].getWidth() : objects[i].getWidth()) * squashFactor;
    }
    var disHeight = disLength * distortValue;
    var xOffset = -disLength * 0.5;
    var yOffset = disHeight * 0.5;
    var radFirstPart = Math.sqrt(disHeight * disHeight + disLength * disLength / 4);
    var disRadius = radFirstPart / (2 * Math.cos(Math.atan(disLength / (2 * disHeight))));
    disRadius = disRadius > 15 ? disRadius : 15;
    var phraseWidth = 0;
    var letterWidth = 0;
    for (var i = 0; i < objects['length']; i++) {
        letterWidth = (text_val[i] === ' ') ? objects[i].getWidth() : objects[i].getWidth();
        var letterPlacement = phraseWidth + letterWidth * 0.5 * squashFactor;
        phraseWidth = phraseWidth + letterWidth * squashFactor;
        var letterAngle = 3.141593 / 4 - (2 - 4 * letterPlacement / disLength) * (3.141593 / 2 - Math.atan(disLength / (2 * disHeight)));
        var letterAngleDegrees = letterAngle * (360 / 3.141593);
        var left = -(disRadius / 2 * Math.cos(letterAngle * 2) - disLength / 2 - letterPlacement + letterPlacement) + xOffset;
        var top = (disRadius * Math.sin(letterAngle * 2) - (disRadius - disHeight) - yOffset) / 2;
        var angle = letterAngleDegrees - 90;
        if (archValue > 0) {
            top = -top;
        } else {
            angle = -angle;
        }
        objects[i].set({
            left: left,
            top: top,
            angle: angle
        });
        charList.push(objects[i]);
    }
    improvedUpdate(charList);
};

const wedgeEffect = function (val) {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var objVal = ((100 - Math.abs(val)) / 100) * objHeight;
    var charList = [];
    var d = Math.abs((objVal - objHeight) / (object['length'] - 1));
    if (val >= 0) {
        for (var i = 0; i < object['length']; i++) {
            object[i].set({
                scaleY: ((objHeight - i * d) * 1 / objHeight)
            });
            charList.push(object[i]);
        }
    } else {
        for (var i = object['length'] - 1; i >= 0; i--) {
            object[i].set({
                scaleY: ((objHeight - (object['length'] - 1 - i) * d) * 1 / objHeight)
            });
            charList.push(object[i]);
        }
    }
    improvedUpdate(charList);
};

const diagonalEffect = function (val) {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var charList = [];
    var objVal = ((100 - Math.abs(val)) / 100) * objHeight;
    var d = Math.abs((objVal - objHeight) / (object['length'] - 1));
    if (val >= 0) {
        for (var i = 0; i < object['length']; i++) {
            object[i].set({
                top: (currentGroup['top'] + i * d / 2)
            });
            charList.push(object[i]);
        }
    } else {
        for (var i = object['length'] - 1; i >= 0; i--) {
            object[i].set({
                top: currentGroup['top'] + (object['length'] - 1 - i) * d / 2
            });
            charList.push(object[i]);
        }
    }
    improvedUpdate(charList);
};

const effectsEffect = function (val) {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var charList = [];
    var objVal = ((100 - Math.abs(val)) / 100) * objHeight;
    var d = Math.abs((objVal - objHeight) / (object['length'] - 1));
    if (val >= 0) {
        for (var i = 0; i < object['length']; i++) {
            object[i].set({
                scaleY: (objHeight - i * d) * 1 / objHeight,
                top: (currentGroup['top'] + i * d / 2)
            });
            charList.push(object[i]);
        }
    } else {
        for (var i = object['length'] - 1; i >= 0; i--) {
            var scaleY = (objHeight - (object['length'] - 1 - i) * d) * 1 / objHeight;
            object[i].set({
                scaleY: scaleY,
                top: currentGroup['top'] + (object['length'] - 1 - i) * d / 2
            });
            charList.push(object[i]);
        }
    }
    improvedUpdate(charList);
};

const waveEffect = function (val) {
    var object = currentGroup.getObjects();
    var tn = ((Math.abs(val)) / 100) * 0.8 * currentGroup.getHeight();
    var min = 0;
    var charList = [];
    for (var i = 0; i < object['length']; i++) {
        var hei = tn * Math.sin(getAngle((i + 1) * 360 / (object['length'])));
        min = (min >= hei) ? hei : min;
    }
    for (var i = 0; i < object['length']; i++) {
        var hei = tn * Math.sin(getAngle((i + 1) * 360 / (object['length'])));
        var heig = (val >= 0) ? (hei - min) : (tn - hei);
        object[i].set({
            top: heig / 2
        });
        charList.push(object[i]);
    }
    improvedUpdate(charList);
};

const upperEffect = function (val) {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var charList = [];
    val = (val + 100) / 2;
    var tn = ((Math.abs(val)) / 100) * 0.8 * objHeight;
    var min = 0;
    var ovalNunber = parseInt(object['length'] * 3 / 5);
    var sIndex = parseInt(object['length'] / 5);
    for (var i = 0; i < ovalNunber; i++) {
        var h = tn * Math.sin(getAngle((i) * 180 / (ovalNunber)));
        var heig = objHeight - tn + h;
        var scaleY = heig * 1 / objHeight;
        object[sIndex + i].set({
            scaleY: scaleY,
            top: ((objHeight - heig) / 2)
        });
        charList[sIndex + i] = object[sIndex + i];
        min = min === 0 ? heig : (min >= heig) ? heig : min;
    }
    for (var i = 0; i < object['length']; i++) {
        if (i < sIndex || i >= sIndex + ovalNunber) {
            var scaleY = min * 1 / objHeight;
            object[i].set({
                scaleY: scaleY,
                top: (objHeight - min) / 2
            });
            charList[i] = object[i];
        }
    }
    improvedUpdate(charList);
};

const roofEffect = function (val, type) {
    var object = currentGroup.getObjects();
    var objHeight = currentGroup.getHeight();
    var tn = ((Math.abs(val)) / 100) * 0.8 * objHeight;
    var charList = [];
    for (var i = 0; i < object['length']; i++) {
        var hei = tn * Math.sin(getAngle((i) * 180 / (object['length'])));
        var scaleY = 1;
        if (type) {
            scaleY = (objHeight - hei) * 1 / objHeight;
        } else {
            scaleY = (objHeight + hei) * 1 / objHeight;
        }
        var top = Math.abs((objHeight - scaleY * objHeight) / 4);
        object[i].set({
            scaleY: scaleY,
            top: (val >= 0) ? top : -top
        });
        charList.push(object[i]);
    }
    improvedUpdate(charList);
};

const hWedgeEffect = function (val) {
    val = (val > 90) ? 80 : (val < -90) ? -80 : val;
    var object = currentGroup.getObjects();
    var objWidth = currentGroup.getWidth();
    var tn = ((100 - Math.abs(val)) / 100) * objWidth;
    var d = Math.abs((tn - objWidth) / (object['length'] - 1));
    if (val >= 0) {
        for (var i = 0; i < object['length']; i++) {
            object[i].set({
                scaleX: (objWidth - i * d) * 1 / objWidth
            });
        }
    } else {
        for (var i = object['length'] - 1; i >= 0; i--) {
            object[i].set({
                scaleX: (objWidth - (object['length'] - 1 - i) * d) * 1 / objWidth
            });
        }
    }
    improvedUpdate(object);
};

const skewXEffect = function (val, type) {
    val = val / 100;
    var object = currentGroup.getObjects();
    for (var i = 0; i < object['length']; i++) {
        if (type === 'skewXEffect') {
            object[i].set({
                transformMatrix: [1, val, 0, 1, 0, 0]
            });
        } else if (type === 'skewYEffect') {
            object[i].set({
                transformMatrix: [1, 0, val, 1, 0, 0]
            });
        } else if (type === 'skewXYEffect') {
            object[i].set({
                transformMatrix: [1, val, -val, 1, 0, 0]
            });
        } else if (type === 'testEffect') {
            object[i].set({
                transformMatrix: [Math.abs(val), -val, val, Math.abs(val), 0, 0]
            });
        }
    }
    improvedUpdate(object);
};