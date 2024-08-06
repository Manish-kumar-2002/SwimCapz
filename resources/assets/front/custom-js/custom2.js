$(document).ready(function () {
  $(".hamburger").click(function () {
    $(this).toggleClass("active");
    $(".navigation .navbar").slideToggle();
  })
  $(".category-hamburger").click(function () {
    $(".category-listing").toggleClass("active");
  })
  $('.category-listing li').click(function () {
    $('.category-listing li').removeClass("active");
    $(this).addClass("active");
  });
  $(document).on("click", ".filter-cards .cap_clr_slider .slick-slide", function () {
    $(this).children().children().addClass("active").parents().parents().siblings().children().children().removeClass("active");
  });
  $(".chatbot").click(function () {
    $(".chatbot-wrapper").slideToggle("slow");
  });
  if ($('#country_selector').length) {
    $("#country_selector").countrySelect({
      defaultCountry: "gb",
    });
  }
  if ($('.news-slider').length) {
    $(".news-slider").slick({
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      mobileFirst: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          }
        }
      ]
    });
  }
  if ($('.customize-cap-slider').length) {
    $(".customize-cap-slider").slick({
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      mobileFirst: true,
      dots: true,
      dotsClass: 'custom_paging',
      customPaging: function (slider, i) {
        return (i + 1) + '/' + slider.slideCount;
      },
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            arrows: false,
            slidesToShow: 5,
            customPaging: function (slider, i) {
              return (i + 5) + '/' + slider.slideCount;
            },
          }
        },
        {
          breakpoint: 1023,
          settings: {
            arrows: false,
            slidesToShow: 4,
            customPaging: function (slider, i) {
              return (i + 4) + '/' + slider.slideCount;
            },
          }
        },
        {
          breakpoint: 767,
          settings: {
            arrows: false,
            slidesToShow: 3,
            customPaging: function (slider, i) {
              return (i + 3) + '/' + slider.slideCount;
            },
          }
        },
        {
          breakpoint: 549,
          settings: {
            slidesToShow: 2,
            customPaging: function (slider, i) {
              return (i + 2) + '/' + slider.slideCount;
            },
          }
        }
      ]
    });
  }


  if ($('.testimonial-slider').length) {
    $(".testimonial-slider").slick({
      infinite: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      mobileFirst: true,
      responsive: [
        {
          breakpoint: 549,
          settings: {
            slidesToShow: 2
          }
        }
      ]
    });

  }


  if ($('.cap_clr_slider').length) {
    $(".cap_clr_slider").slick({
      infinite: false,
      slidesToShow: 7,
      slidesToScroll: 1,
      arrows: true,
    });
  }
  if ($('.slider-for').length) {
    $('.slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      fade: true,
      arrows: true,
      asNavFor: '.slider-nav'
    });
  }
  if ($('.slider-nav').length) {
    $('.slider-nav').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      asNavFor: '.slider-for',
      dots: true,
      focusOnSelect: true
    });

  }


  $('.category-listing .list').click(function () {
    const value = $(this).attr('data-filter');
    if (value == 'all') {
      $('.filter-cards .slide-card').show('1000');
    } else {
      $('.filter-cards .slide-card').not('.' + value).hide('1000');
      $('.filter-cards .slide-card').filter('.' + value).show('1000');
    }
  })

  $('.category-listing .list').click(function () {
    $(this).addClass('active').siblings().removeClass('active');
  })
  if ($('select').length) {
    $('select').niceSelect();
  }

  // prduct quantity selector
  $(document).ready(function () {
    const $quantityInput = $('.quantity-input');
    const $incrementBtn = $('.increment-btn');
    const $decrementBtn = $('.decrement-btn');

    $incrementBtn.on('click', function () {
      const currentValue = parseInt($quantityInput.val());
      $quantityInput.val(currentValue + 1);
    });

    $decrementBtn.on('click', function () {
      const currentValue = parseInt($quantityInput.val());
      if (currentValue > 1) {
        $quantityInput.val(currentValue - 1);
      }
    });

    $quantityInput.on('input', function () {
      const value = $(this).val().replace(/[^\d]/g, ''); 
      $(this).val(value);
    });
  });


});

function myPrev() {
  $(".customize-cap-slider").slick("slickPrev");
}

function myNext() {
  $(".customize-cap-slider").slick("slickNext");
}

function myPrev2() {
  $(".testimonial-slider").slick("slickPrev");
}

function myNext2() {
  $(".testimonial-slider").slick("slickNext");
}
function newsPrev() {
  $(".news-slider").slick("slickPrev");
}
function newsNext() {
  $(".news-slider").slick("slickPrev");
}



const header = document.querySelector(".navigation");
const toggleClass = "is-sticky";

window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset;
  if (currentScroll > 150) {
    header.classList.add(toggleClass);
  } else {
    header.classList.remove(toggleClass);
  }
});

function toggleCheckbox() {
  var checkbox1 = document.getElementById("billing_check");
  checkbox1.checked = !checkbox1.checked; 
}




var imgElement = document.querySelector("#finish-payment img");
//imgElement.src = "{{ asset('assets/front/custom-images/paypal-copy.png') }}";

var creditCardRadio = document.getElementById("creditCard");
var paypalRadio = document.getElementById("paypal");
var stripeRadio = document.getElementById("Stripe");
var checkoutButton = document.getElementById("finish-payment");
//paypalRadio.checked = true;

// paypalRadio.addEventListener("change", updateCheckoutButtonImage);
// creditCardRadio.addEventListener("change", updateCheckoutButtonImage);
// stripeRadio.addEventListener("change", updateCheckoutButtonImage);

// function updateCheckoutButtonImage() {
//   var selectedRadio = paypalRadio.checked ? paypalRadio : creditCardRadio.checked ? creditCardRadio : stripeRadio;
//   var dataSrc = selectedRadio.getAttribute("data-src");

//   if (dataSrc) {
//     imgElement.src = dataSrc;
//   }
// }

//updateCheckoutButtonImage();


