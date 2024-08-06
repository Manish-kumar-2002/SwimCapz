let text_val = '';
let originlaText = '';
let currentGroup = '';
let txtSpacing = 0;
let previousSelectedObject = '';
let arcValue = 50;
let selectEffect = 'straight';
let textIndexData = {};
let firstAddedeText = true;
let ShowOutline = 50;
let textColor = "#e0ffee";
let font_Family = "";
let strokeColor = "#f5f5f5";

// console.log('checking base url....',BASE_WEB_URL);

const designDetails = BASE_WEB_URL + "/frontend/tool?details-page=true";
const designPage = BASE_WEB_URL + "/frontend/tool?product-id=";
const cartPage = BASE_WEB_URL + "/carts";
const BASE_URL = BASE_API_URL;
const termAndConditions = BASE_WEB_URL + "/terms-condition";

$(document).ready(function () {
	let clickCount = 0;
	$(".cd_add_more").click(function () {
		clickCount++;
		let newElement = $(`<div class="cd_clone_wrap"></div>`);
		newElement.appendTo(".cd_clone_wrap_dynamic");
		let inputElement1 = $(`<input type='text' id='name' placeholder='name'/>`);
		let inputElement2 = $(`<input type='text' id='quantity' onkeydown="textValidation(event)" placeholder='quantity' class="cd_quantity" />`);
		let removeBtn = $(`<button type='button' class='cd_remove_btn'></button>`);
		inputElement1.appendTo(newElement);
		inputElement2.appendTo(newElement);
		removeBtn.appendTo(newElement);
	});

	$(document).on("click", ".cd_remove_btn", function () {
		$(this).parent().remove();
	});

	$('.popup-upload-img .btn-arc-close').click(function () {
		$('.popup-upload-img').fadeOut();
	});

});

$(document).ready(function () {
	$(".cd_resize_btn").on("click", function () {
		$('.cd_left_block').toggleClass('cd_zoom')
		$('.cd_right_block').toggleClass('cd_opacity')
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


// jquery tabbing
// Show the first tab and hide the rest
$('#tabs-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('#tabs-nav li').click(function () {
	$('#tabs-nav li').removeClass('active');
	$(this).addClass('active');
	$('.tab-content').hide();

	let activeTab = $(this).find('a').attr('href');
	$(activeTab).fadeIn();
	return false;
});


$(window).on('load', function () {
	$(".cd_magnifire_btn").click(function () {
		$(".cd_slide_overlay").css("display", "flex");
		$("body").css("overflow", "hidden");
		$(".popup_slider").slick({
			arrows: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			dots: true,
		});

	})

	$(".cd_close_popup").click(function () {
		$(this).parents(".cd_slide_overlay").css("display", "none");
		$("body").css("overflow", "auto");
	})

	$('.cd_add').click(function () {
		let inputField = $(this).siblings('.cd_input_value');
		let currentValue = parseInt(inputField.val(), 10);
		inputField.val(currentValue + 1);
	});

	$('.cd_remove').click(function () {
		let inputField = $(this).siblings('.cd_input_value');
		let currentValue = parseInt(inputField.val(), 10);
		if (currentValue > 0) {
			inputField.val(currentValue - 1);
		}
	});
});


