<?php

use App\Http\Controllers\Front\FrontendController;
use App\Http\Controllers\Front\CouponController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/under-maintenance', 'Front\FrontendController@maintenance')->name('front-maintenance');
Route::get('cache-clear', function () {

    Artisan::call('optimize:clear');
    return "cacheed cleared.";
});

Route::group(['middleware' => 'maintenance'], function () {

    // ************************************ FRONT SECTION **********************************************

    Route::post('/item/report', 'Front\CatalogController@report')->name('product.report');

    Route::get('/', 'Front\FrontendController@index')->name('front.index');
    Route::get('/view', 'Front\CartController@view_cart')->name('front.cart-view');
    Route::get('/extras', 'Front\FrontendController@extraIndex')->name('front.extraIndex');
    Route::get('/aboutUs', 'Front\FrontendController@showAboutUs')->name('about-us');


    Route::get('/currency/{id}', 'Front\FrontendController@currency')->name('front.currency');
    Route::get('/language/{id}', 'Front\FrontendController@language')->name('front.language');
    Route::get('/order/track/{id}', 'Front\FrontendController@trackload')->name('front.track.search');
    // BLOG SECTION
    Route::get('/blog', 'Front\FrontendController@blog')->name('front.blog');
    Route::get('/blog/{slug}', 'Front\FrontendController@blogshow')->name('front.blogshow');
    Route::get('/blog/category/{slug}', 'Front\FrontendController@blogcategory')->name('front.blogcategory');
    Route::get('/blog/tag/{slug}', 'Front\FrontendController@blogtags')->name('front.blogtags');
    Route::get('/blog-search', 'Front\FrontendController@blogsearch')->name('front.blogsearch');
    Route::get('/blog/archive/{slug}', 'Front\FrontendController@blogarchive')->name('front.blogarchive');
    // BLOG SECTION ENDS

    // FAQ SECTION
    Route::get('/faq', 'Front\FrontendController@faq')->name('front.faq');
    // FAQ SECTION ENDS

    // CONTACT SECTION
    Route::get('/contact', 'Front\FrontendController@contact')->name('front.contact');
    Route::post('/contact', 'Front\FrontendController@contactemail')->name('front.contact.submit');
    Route::get('/contact/refresh_code', 'Front\FrontendController@refresh_code');
    // CONTACT SECTION  ENDS

    // PRODCT AUTO SEARCH SECTION
    Route::get('/autosearch/product/{slug}', 'Front\FrontendController@autosearch');
    // PRODCT AUTO SEARCH SECTION ENDS

    // CATEGORY SECTION
    Route::get('/categories', 'Front\CatalogController@categories')->name('front.categories');
    Route::get('/category/{category?}/{subcategory?}/{childcategory?}', 'Front\CatalogController@category')
        ->name('front.category');
    // CATEGORY SECTION ENDS

    // TAG SECTION
    Route::get('/tag/{slug}', 'Front\CatalogController@tag')->name('front.tag');
    // TAG SECTION ENDS

    // TAG SECTION
    Route::get('/search', 'Front\CatalogController@search')->name('front.search');
    // TAG SECTION ENDS

    // PRODCT SECTION

    Route::get('/item/{slug}/{id}', 'Front\ProductDetailsController@product')
        ->name('front.product');
    Route::get('/item/show/cross/products/{id}', 'Front\ProductDetailsController@showCrossProduct')
        ->name('front.show.cross.product');
    Route::get('/afbuy/{slug}', 'Front\ProductDetailsController@affProductRedirect')->name('affiliate.product');
    Route::get('/item/quick/view/{id}/', 'Front\ProductDetailsController@quick')->name('product.quick');
    Route::post('/item/review', 'Front\ProductDetailsController@reviewsubmit')->name('front.review.submit');
    Route::get('/item/view/review/{id}', 'Front\ProductDetailsController@reviews')->name('front.reviews');
    Route::get('/item/view/side/review/{id}', 'Front\ProductDetailsController@sideReviews')->name('front.side.reviews');
    // PRODCT SECTION ENDS

    // COMMENT SECTION
    Route::post('/item/comment/store', 'Front\ProductDetailsController@comment')->name('product.comment');
    Route::post('/item/comment/edit/{id}', 'Front\ProductDetailsController@commentedit')
        ->name('product.comment.edit');
    Route::get('/item/comment/delete/{id}', 'Front\ProductDetailsController@commentdelete')
        ->name('product.comment.delete');
    // COMMENT SECTION ENDS

    // REPORT SECTION
    Route::post('/item/report', 'Front\ProductDetailsController@report')->name('product.report');
    // REPORT SECTION ENDS

    // REPLY SECTION
    Route::post('/item/reply/{id}', 'Front\ProductDetailsController@reply')->name('product.reply');
    Route::post('/item/reply/edit/{id}', 'Front\ProductDetailsController@replyedit')->name('product.reply.edit');
    Route::get('/item/reply/delete/{id}', 'Front\ProductDetailsController@replydelete')->name('product.reply.delete');
    // REPLY SECTION ENDS

    // CART SECTION
    Route::get('/carts/view', 'Front\CartController@cartview');
    Route::get('/carts', 'Front\CartController@cart')->name('front.cart');
    Route::get('/addcart/{id}', 'Front\CartController@addcart')->name('product.cart.add');
    Route::get('/addtocart/{id}', 'Front\CartController@addtocart')->name('product.cart.quickadd');
    Route::get('/addnumcart', 'Front\CartController@addnumcart')->name('details.cart');
    Route::get('/addtonumcart', 'Front\CartController@addtonumcart');
    Route::get('/addbyone', 'Front\CartController@addbyone');
    Route::get('/reducebyone', 'Front\CartController@reducebyone');
    Route::get('/upcolor', 'Front\CartController@upcolor');
    Route::get('/cart-details-update', 'Front\CartController@cartDetailsUpdate')
        ->name('cartDetailsUpdate');
    Route::get('/removecart/{id}', 'Front\CartController@removecart')->name('product.cart.remove');

    Route::get('/load-mini-cart', 'Front\CartController@loadMiniCart')
        ->name('load.mini.cart');

    Route::get('/carts/coupon', 'Front\CouponController@coupon');
    Route::get('/cart-name-remove/{id}', 'Front\CartController@cartNameRemove')
        ->name('front.cart-name-remove');
    Route::get('/download-cart-name-list/{id}', 'Front\CartController@downloadCartNameList')
        ->name('front.download-cart-name-list');
    // CART SECTION ENDS

    //tool
    Route::resource('tools', Front\ToolController::class)
        ->except(['destroy']);


    // COMPARE SECTION
    Route::get('/item/compare/view', 'Front\CompareController@compare')->name('product.compare');
    Route::get('/item/compare/add/{id}', 'Front\CompareController@addcompare')->name('product.compare.add');
    Route::get('/item/compare/remove/{id}', 'Front\CompareController@removecompare')->name('product.compare.remove');
    // COMPARE SECTION ENDS

    // CHECKOUT SECTION
    Route::get('/buy-now/{id}', 'Front\CheckoutController@buynow')->name('front.buynow');
    // Checkout
    Route::get('/checkout', 'Front\CheckoutController@checkout')->name('front.checkout');
    Route::get('/carts/coupon/check', 'Front\CouponController@couponcheck');
    Route::get('/checkout/payment/{slug1}/{slug2}', 'Front\CheckoutController@loadpayment')->name('front.load.payment');
    Route::get('/checkout/payment/return', 'Front\CheckoutController@payreturn')->name('front.payment.return');
    Route::get('/checkout/payment/cancle', 'Front\CheckoutController@paycancle')->name('front.payment.cancle');
    Route::get('/checkout/payment/wallet-check', 'Front\CheckoutController@walletcheck')->name('front.wallet.check');

    Route::get('/checkout/load-addresses', 'Front\CheckoutController@loadAdresses')
        ->name('checkout.load.address');
    // Paypal
    Route::post('/checkout/payment/paypal/submit', 'Payment\Checkout\PaypalController@store')
        ->name('front.paypal.submit');
    Route::get('/checkout/payment/paypal-notify', 'Payment\Checkout\PaypalController@notify')
        ->name('front.paypal.notify');

    // Stripe
    Route::post('/checkout/payment/stripe-submit', 'Payment\Checkout\StripeController@store')
        ->name('front.stripe.submit');

    // Instamojo
    Route::post('/checkout/payment/instamojo-submit', 'Payment\Checkout\InstamojoController@store')
        ->name('front.instamojo.submit');
    Route::get('/checkout/payment/instamojo-notify', 'Payment\Checkout\InstamojoController@notify')
        ->name('front.instamojo.notify');

    // Paystack
    Route::post('/checkout/payment/paystack-submit', 'Payment\Checkout\PaystackController@store')
        ->name('front.paystack.submit');

    // PayTM
    Route::post('/checkout/payment/paytm-submit', 'Payment\Checkout\PaytmController@store')
        ->name('front.paytm.submit');
    Route::post('/checkout/payment/paytm-notify', 'Payment\Checkout\PaytmController@notify')
        ->name('front.paytm.notify');

    // Molly
    Route::post('/checkout/payment/molly-submit', 'Payment\Checkout\MollieController@store')
        ->name('front.molly.submit');
    Route::get('/checkout/payment/molly-notify', 'Payment\Checkout\MollieController@notify')
        ->name('front.molly.notify');

    // RazorPay
    Route::post('/checkout/payment/razorpay-submit', 'Payment\Checkout\RazorpayController@store')
        ->name('front.razorpay.submit');
    Route::post('/checkout/payment/razorpay-notify', 'Payment\Checkout\RazorpayController@notify')
        ->name('front.razorpay.notify');

    // Authorize.Net
    Route::post('/checkout/payment/authorize-submit', 'Payment\Checkout\AuthorizeController@store')
        ->name('front.authorize.submit');

    // Mercadopago
    Route::post('/checkout/payment/mercadopago-submit', 'Payment\Checkout\MercadopagoController@store')
        ->name('front.mercadopago.submit');

    // Flutter Wave
    Route::post('/checkout/payment/flutter-submit', 'Payment\Checkout\FlutterwaveController@store')
        ->name('front.flutter.submit');

    // 2checkout
    Route::post('/checkout/payment/twocheckout-submit', 'Payment\Checkout\TwoCheckoutController@store')
        ->name('front.twocheckout.submit');

    // SSLCommerz
    Route::post('/checkout/payment/ssl-submit', 'Payment\Checkout\SslController@store')
        ->name('front.ssl.submit');
    Route::post('/checkout/payment/ssl-notify', 'Payment\Checkout\SslController@notify')
        ->name('front.ssl.notify');

    // Voguepay
    Route::post('/checkout/payment/voguepay-submit', 'Payment\Checkout\VoguepayController@store')
        ->name('front.voguepay.submit');

    // Wallet
    Route::post('/checkout/payment/wallet-submit', 'Payment\Checkout\WalletPaymentController@store')
        ->name('front.wallet.submit');

    // Manual
    Route::post('/checkout/payment/manual-submit', 'Payment\Checkout\ManualPaymentController@store')
        ->name('front.manual.submit');

    // Cash On Delivery
    Route::post('/checkout/payment/cod-submit', 'Payment\Checkout\CashOnDeliveryController@store')
        ->name('front.cod.submit');

    // Flutterwave Notify Routes

    // Deposit
    Route::post('/dflutter/notify', 'Payment\Deposit\FlutterwaveController@notify')->name('deposit.flutter.notify');

    // Subscription
    Route::post('/uflutter/notify', 'Payment\Subscription\FlutterwaveController@notify')->name('user.flutter.notify');

    // Checkout
    Route::post('/cflutter/notify', 'Payment\Checkout\FlutterwaveController@notify')->name('front.flutter.notify');

    // CHECKOUT SECTION ENDS

    //   Mobile Checkout section

    Route::get('/payment/checkout', 'Api\Payment\CheckoutController@checkout')->name('payment.checkout');
    Route::post('/payment/stripe-submit', 'Api\Payment\StripeController@store')->name('payment.stripe');

    Route::get('/deposit/app/payment/{slug1}/{slug2}', 'Api\Payment\CheckoutController@depositloadpayment')
        ->name('deposit.app.payment');

    Route::get('/checkout/payment/{slug1}/{slug2}', 'Front\CheckoutController@loadpayment')->name('front.load.payment');

    Route::post('/api/flutter/submit', 'Payment\FlutterWaveController@store')->name('api.flutter.submit');
    //Route::post('/flutter/notify', 'Payment\FlutterWaveController@notify')->name('api.flutter.notify');

    Route::get('/payment/successfull/{get}', 'Front\FrontendController@success')->name('front.payment.success');

    Route::post('/api/cod/submit', 'Api\Payment\CashOnDeliveryController@store')->name('api.cod.submit');
    Route::post('/api/manual/submit', 'Api\Payment\ManualController@store')->name('api.manual.submit');

    Route::post('/api/paystack/submit', 'Api\Payment\PaystackController@store')->name('api.paystack.submit');

    Route::post('/api/instamojo/submit', 'Api\Payment\InstamojoController@store')->name('api.instamojo.submit');

    Route::get('/api/checkout/instamojo/notify', 'Api\Payment\InstamojoController@notify')
        ->name('api.instamojo.notify');

    //flutter
    Route::post('/api/flutter/submit', 'Api\Payment\FlutterWaveController@store')->name('api.flutter.submit');
    Route::post('/api/flutter/notify', 'Api\Payment\FlutterWaveController@notify')->name('api.flutter.notify');

    // ssl Routes
    Route::post('/api/ssl/submit', 'Api\Payment\SslController@store')->name('api.ssl.submit');
    Route::post('/api/ssl/notify', 'Api\Payment\SslController@notify')->name('api.ssl.notify');
    Route::post('/api/ssl/cancle', 'Api\Payment\SslController@cancle')->name('api.ssl.cancle');
    Route::get('/deposit/payment/{number}', 'Api\User\DepositController@sendDeposit')->name('user.deposit.send');

    // Paypal
    Route::post('/checkout/payment/paypal-submit', 'Api\Payment\PaypalController@store')->name('api.paypal.submit');
    Route::get('/api/checkout/paypal/notify', 'Api\Payment\PaypalController@notify')->name('api.paypal.notify');
    Route::get('/api/checkout/payment/return', 'Api\Payment\PaypalController@payreturn')->name('api.paypal.return');
    Route::get('/api/checkout/payment/cancle', 'Api\Payment\PaypalController@paycancle')->name('api.paypal.cancle');

    Route::post('/api/payment/stripe-submit', 'Api\Payment\StripeController@store')->name('api.stripe.submit');

    // Molly Routes
    Route::post('/api/molly/submit', 'Api\Payment\MollyController@store')->name('api.molly.submit');
    Route::get('/api/molly/notify', 'Api\Payment\MollyController@notify')->name('api.molly.notify');

    //PayTM Routes
    Route::post('/api/paytm-submit', 'Api\Payment\PaytmController@store')->name('api.paytm.submit');
    Route::post('/api/paytm-callback', 'Api\Payment\PaytmController@paytmCallback')->name('api.paytm.notify');

    Route::post('/api/authorize-submit', 'Api\Payment\AuthorizeController@store')->name('api.authorize.submit');

    //RazorPay Routes
    Route::post('/api/razorpay-submit', 'Api\Payment\RazorpayController@store')->name('api.razorpay.submit');
    Route::post('/api/razorpay-callback', 'Api\Payment\RazorpayController@razorCallback')->name('api.razorpay.notify');

    //   Mobile Checkout section

    // Mercadopago Routes
    Route::get('/api/checkout/mercadopago/return', 'Api\Payment\MercadopagoController@payreturn')
        ->name('api.mercadopago.return');
    Route::post('/api/checkout/mercadopago/notify', 'Api\Payment\MercadopagoController@notify')
        ->name('api.mercadopago.notify');
    Route::post('/api/checkout/mercadopago/submit', 'Api\Payment\MercadopagoController@store')
        ->name('api.mercadopago.submit');


    // SUBSCRIBE SECTION

    Route::post('/subscriber/store', 'Front\FrontendController@subscribe')->name('front.subscribe');

    // SUBSCRIBE SECTION ENDS

    // LOGIN WITH FACEBOOK OR GOOGLE SECTION
    Route::get('auth/{provider}', 'Auth\User\SocialRegisterController@redirectToProvider')->name('social-provider');
    Route::get('auth/{provider}/callback', 'Auth\User\SocialRegisterController@handleProviderCallback');
    // LOGIN WITH FACEBOOK OR GOOGLE SECTION ENDS



    Route::post('the/genius/ocean/2441139', 'Front\FrontendController@subscription');
    Route::get('finalize', 'Front\FrontendController@finalize');
    Route::get('update-finalize', 'Front\FrontendController@updateFinalize');



    Route::get('/country/tax/check', 'Front\CartController@country_tax');
    Route::get('/{slug}', 'Front\FrontendController@page')->name('front.page');


    // ************************************ FRONT SECTION ENDS**********************************************

});
Route::post('the/genius/ocean/2441139', 'Front\FrontendController@subscription');
Route::get('finalize', 'Front\FrontendController@finalize');

/* temp order store */
Route::post('store-temp-order', [FrontendController::class, 'createTempOrder'])->name('createTempOrder');
Route::post('stripe-call-back', [FrontendController::class, 'stripeCallBack'])
    ->name('stripe.callback');

Route::get('checkout/load-po', [FrontendController::class, 'loadPo'])
    ->name('loadPo');

Route::post('checkout/submit-load-po', [FrontendController::class, 'submitloadPo'])
    ->name('submit.loadPo');

Route::get('view/order/{order_no}', [FrontendController::class, 'showOrder'])
    ->name('show-order');



//success
Route::get('paypal/success', [FrontendController::class, 'paypalSuccess'])->name('paypalSuccess');
//cancel
Route::get('paypal/cancel', [FrontendController::class, 'paypalCancel'])
    ->name('paypalCancel');

Route::get('frontend/load-checkout-right-panel', [FrontendController::class, 'loadCheckoutRightPanel'])
    ->name('loadCheckoutRightPanel');
Route::get('frontend/rush-charge-manage', [FrontendController::class, 'rushChargeManage'])
    ->name('rushChargeManage');

/* download name upload template */
Route::get('frontend/download-name-template', [FrontendController::class, 'downloadNameTemplate'])
    ->name('download.name.template');

Route::group(['middleware' => 'auth'], function () {

    Route::post('apply-coupon', [CouponController::class, 'applyCoupon'])
        ->name('applyCoupon');

    Route::get('coupon/remove', [CouponController::class, 'appliedCouponRemove'])
        ->name('appliedCouponRemove');
});
Route::get('frontend/available_coupons', [CouponController::class, 'availableCoupons'])
    ->name('coupons.available');

