var text_val = '';
var originlaText = '';
var currentGroup = '';
var txtSpacing = 0;
var previousSelectedObject='';
var arcValue = 50;
var selecectEffect = 'straight';
var textIndexData = {};
var firstAddedeText = true;
var ShowOutline = 0;
var textColor = "";
var font_Family = "";
var strokeColor = "blue";

$(document).ready(function () {
  var clickCount = 0;
  $(".cd_add_more").click(function () {
    clickCount++;
    var newElement = $(`<div class="cd_clone_wrap"></div>`);
    newElement.appendTo(".cd_clone_wrap_dynamic");
    var inputElement1 = $("<input type='text' placeholder='name'/>");
    var inputElement2 = $(`<input type='text' placeholder='quantity' class="cd_quantity" />`);
    var removeBtn = $(`<button type='button' class='cd_remove_btn'></button>`);
    inputElement1.appendTo(newElement);
    inputElement2.appendTo(newElement);
    removeBtn.appendTo(newElement);
  });

  $(document).on("click", ".cd_remove_btn", function () {
    $(this).parent().remove();
  });

  $('.cd_clr_slider').slick({
    infinite: false,
    arrows: true,
    slidesToShow: 5,
    slidesToScroll: 1,
  });
  $('.popup_change_product_side_open').click(function() {
    $('.popup-change_product_side').fadeIn();
  });

$('.popup_side').click(function() {
  $('.popup-change_product_side').fadeIn();

});

$('.popup_change_product_side_close').click(function() {
    $('.popup-change_product_side').fadeOut();
});

$('.popup_change_product_side_close').click(function() {
  $('.popup-change_product_side').fadeOut();
});

$('.popup-upload-img .btn-arc-close').click(function() {
  $('.popup-upload-img').fadeOut();
});
  
});

$(document).ready(function(){
  $(".cd_resize_btn").on("click", function() 
  {
    $('.cd_left_block').toggleClass('cd_zoom')
    $('.cd_right_block').toggleClass('cd_opacity')
  // document.fullScreenElement && null !== document.fullScreenElement || !document.mozFullScreen && !document.webkitIsFullScreen ? document.documentElement.requestFullScreen ? document.documentElement.requestFullScreen() : document.documentElement.mozRequestFullScreen ? document.documentElement.mozRequestFullScreen() : document.documentElement.webkitRequestFullScreen && document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT) : document.cancelFullScreen ? document.cancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitCancelFullScreen && document.webkitCancelFullScreen()
  });	
 });



const rangeInputs = document.querySelectorAll('input[type="range"]');

function handleInputChange(e) {
  let target = e.target;
  if (e.target.type !== "range") {
    target = document.getElementById("range");
  }
  const min = target.min;
  const max = target.max;
  const val = target.value;

  target.style.backgroundSize = ((val - min) * 100) / (max - min) + "% 100%";
}

rangeInputs.forEach((input) => {
  input.addEventListener("input", handleInputChange);
});
