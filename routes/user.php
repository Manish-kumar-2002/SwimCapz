<?php

use App\Http\Controllers\User\DesignController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'maintenance'], function () {

    Route::prefix('user')->group(function () {

        // USER AUTH SECION
        Route::get('/login', 'User\LoginController@showLoginForm')->name('user.login');

        Route::get('/success/{status}', 'User\LoginController@status')->name('user.success');

        Route::get('/register', 'User\RegisterController@showRegisterForm')->name('user.register');

        // User Login
        Route::post('/login', 'Auth\User\LoginController@login')->name('user.login.submit');
        // User Login End

        // User Register
        Route::post('/register', 'Auth\User\RegisterController@register')->name('user-register-submit');
        Route::get('/register/verify/{token}', 'Auth\User\RegisterController@token')->name('user-register-token');
       
        //------------ USER FORGOT SECTION ------------
        Route::get('/forgot', 'Auth\User\ForgotController@index')->name('user.forgot');
        Route::post('/forgot', 'Auth\User\ForgotController@forgot')->name('user.forgot.submit');
        Route::get('/change-password/{token}', 'Auth\User\ForgotController@showChangePassForm')
            ->name('user.change.token');
        Route::post('/change-password', 'Auth\User\ForgotController@changepass')->name('user.change.password');

        //  --------------------- Reward Point Route ------------------------------//
        Route::get('reward/points', 'User\RewardController@rewards')->name('user-reward-index');
        Route::get('reward/convert', 'User\RewardController@convert')->name('user-reward-convernt');
        Route::post('reward/convert/submit', 'User\RewardController@convertSubmit')->name('user-reward-convert-submit');

        // User Logout
        Route::get('/logout', 'User\LoginController@logout')->name('user-logout');
        // User Logout Ends

        // USER AUTH AUCTION ENDS

        // User Dashboard
        Route::get('/dashboard', 'User\UserController@index')->name('user-dashboard');
        Route::get('/email-verified', 'User\UserController@verified_email')->name('user-email-verified');

        // User Reset
        Route::get('/reset', 'User\UserController@resetform')->name('user-reset');
        Route::post('/reset', 'User\UserController@reset')->name('user-reset-submit');
        // User Reset End

        // User Profile
        Route::get('/profile', 'User\UserController@profile')->name('user-profile');
        Route::post('/profile', 'User\UserController@profileupdate')->name('user-profile-update');
        Route::post('/profile/set-default-address', 'User\UserController@setDefaultAddress')->name('setDefaultAddress');
        
        /*  address manage */
        Route::get('/address/edit/{id}', 'User\UserController@addressEdit')->name('addressEdit');
        Route::post('/address/update/{id}', 'User\UserController@addressUpdate')->name('addressUpdate');
        Route::get('/address/add', 'User\UserController@addressAdd')->name('addressAdd');
        Route::post('/address/store', 'User\UserController@addressStore')->name('addressStore');
        Route::get('/address/destroy/{id}', 'User\UserController@addressDestroy')->name('addressDestroy');
        Route::get('/address/destroy/{id}', 'User\UserController@addressDestroy')->name('addressDelete');
        // User Profile Ends

        // Display important Codes For Payment Gatweways
        Route::get('/payment/{slug1}/{slug2}', 'User\UserController@loadpayment')->name('user.load.payment');
        Route::get('/country/wise/state/{country_id}', 'Front\CheckoutController@getState')->name('country.wise.state');

        // User Wishlist
        Route::get('/wishlists', 'User\WishlistController@wishlists')->name('user-wishlists');
        Route::get('/wishlist/add/{id}', 'User\WishlistController@addwish')->name('user-wishlist-add');
        Route::get('/wishlist/remove/{id}', 'User\WishlistController@removewish')->name('user-wishlist-remove');
        // User Wishlist Ends

        // User Review
        Route::post('/review/submit', 'User\UserController@reviewsubmit')->name('front.review.submit');
        // User Review Ends

        // User Orders

        Route::get('/orders', 'User\OrderController@orders')->name('user-orders');
        Route::get('/order/tracking', 'User\OrderController@ordertrack')->name('user-order-track');
        Route::get('/order/trackings/{id}', 'User\OrderController@trackload')->name('user-order-track-search');
        Route::get('/order/{id}', 'User\OrderController@order')->name('user-order');
        Route::get('/download/order/{slug}/{id}', 'User\OrderController@orderdownload')->name('user-order-download');
        Route::get('print/order/print/{id}', 'User\OrderController@orderprint')->name('user-order-print');
        Route::get('/json/trans', 'User\OrderController@trans');

        /* design */
        Route::resource('user-designs', User\DesignController::class);

        Route::get('/deposit/transactions', 'User\DepositController@transactions')->name('user-transactions-index');
        Route::get('/deposit/transactions/{id}/show', 'User\DepositController@transhow')->name('user-trans-show');
        Route::get('/deposit/index', 'User\DepositController@index')->name('user-deposit-index');
        Route::get('/deposit/create', 'User\DepositController@create')->name('user-deposit-create');

        // Subscription Payment Redirect
        Route::get('/deposit/payment/cancle', 'User\DepositController@paycancle')->name('deposit.payment.cancle');
        Route::get('/deposit/payment/return', 'User\DepositController@payreturn')->name('deposit.payment.return');

        // Paypal
        Route::post('/deposit/paypal-submit', 'Payment\Deposit\PaypalController@store')->name('deposit.paypal.submit');
        Route::get('/deposit/paypal-notify', 'Payment\Deposit\PaypalController@notify')->name('deposit.paypal.notify');

        // Stripe
        Route::post('/deposit/stripe-submit', 'Payment\Deposit\StripeController@store')->name('deposit.stripe.submit');

        // Instamojo
        Route::post('/deposit/instamojo-submit', 'Payment\Deposit\InstamojoController@store')
            ->name('deposit.instamojo.submit');
        Route::get('/deposit/instamojo-notify', 'Payment\Deposit\InstamojoController@notify')
            ->name('deposit.instamojo.notify');

        // Paystack
        Route::post('/deposit/paystack-submit', 'Payment\Deposit\PaystackController@store')
            ->name('deposit.paystack.submit');

        // PayTM
        Route::post('/deposit/paytm-submit', 'Payment\Deposit\PaytmController@store')->name('deposit.paytm.submit');
        Route::post('/deposit/paytm-notify', 'Payment\Deposit\PaytmController@notify')->name('deposit.paytm.notify');

        // Molly
        Route::post('/deposit/molly-submit', 'Payment\Deposit\MollieController@store')->name('deposit.molly.submit');
        Route::get('/deposit/molly-notify', 'Payment\Deposit\MollieController@notify')->name('deposit.molly.notify');

        // RazorPay
        Route::post('/deposit/razorpay-submit', 'Payment\Deposit\RazorpayController@store')
            ->name('deposit.razorpay.submit');
        Route::post('/deposit/razorpay-notify', 'Payment\Deposit\RazorpayController@notify')
            ->name('deposit.razorpay.notify');

        // Authorize.Net
        Route::post('/deposit/authorize-submit', 'Payment\Deposit\AuthorizeController@store')
            ->name('deposit.authorize.submit');

        // Mercadopago
        Route::post('/deposit/mercadopago-submit', 'Payment\Deposit\MercadopagoController@store')
            ->name('deposit.mercadopago.submit');

        // Flutter Wave
        Route::post('/deposit/flutter-submit', 'Payment\Deposit\FlutterwaveController@store')
            ->name('deposit.flutter.submit');

        // 2checkout
        Route::post('/deposit/twocheckout-submit', 'Payment\Deposit\TwoCheckoutController@store')
            ->name('deposit.twocheckout.submit');

        // SSLCommerz
        Route::post('/deposit/ssl-submit', 'Payment\Deposit\SslController@store')
            ->name('deposit.ssl.submit');
        Route::post('/deposit/ssl-notify', 'Payment\Deposit\SslController@notify')
            ->name('deposit.ssl.notify');

        // Voguepay
        Route::post('/deposit/voguepay-submit', 'Payment\Deposit\VoguepayController@store')
            ->name('deposit.voguepay.submit');

        // Manual
        Route::post('/deposit/manual-submit', 'Payment\Deposit\ManualPaymentController@store')
            ->name('deposit.manual.submit');

        Route::post('/user/contact', 'User\MessageController@usercontact');
        Route::get('/messages', 'User\MessageController@messages')->name('user-messages');
        Route::get('/message/{id}', 'User\MessageController@message')->name('user-message');
        Route::post('/message/post', 'User\MessageController@postmessage')->name('user-message-post');
        Route::get('/message/{id}/delete', 'User\MessageController@messagedelete')->name('user-message-delete');
        Route::get('/message/load/{id}', 'User\MessageController@msgload')->name('user-vendor-message-load');

        // Tickets
        Route::get('admin/tickets', 'User\MessageController@adminmessages')->name('user-message-index');
        // Disputes
        Route::get('admin/disputes', 'User\MessageController@adminDiscordmessages')->name('user-dmessage-index');

        Route::get('admin/message/{id}', 'User\MessageController@adminmessage')->name('user-message-show');
        Route::post('admin/message/post', 'User\MessageController@adminpostmessage')->name('user-message-store');
        Route::get('admin/message/{id}/delete', 'User\MessageController@adminmessagedelete')
            ->name('user-message-delete1');
        Route::post('admin/user/send/message', 'User\MessageController@adminusercontact')->name('user-send-message');
        Route::get('admin/message/load/{id}', 'User\MessageController@messageload')->name('user-message-load');
        // User Admin Send Message Ends

        Route::get('/affilate/program', 'User\UserController@affilate_code')->name('user-affilate-program');
        Route::get('/affilate/history', 'User\UserController@affilate_history')->name('user-affilate-history');

        Route::get('/affilate/withdraw', 'User\WithdrawController@index')->name('user-wwt-index');
        Route::get('/affilate/withdraw/create', 'User\WithdrawController@create')->name('user-wwt-create');
        Route::post('/affilate/withdraw/create', 'User\WithdrawController@store')->name('user-wwt-store');

        // User Favorite Seller

        Route::get('/favorite/seller', 'User\UserController@favorites')->name('user-favorites');
        Route::get('/favorite/{id1}/{id2}', 'User\UserController@favorite')->name('user-favorite');
        Route::get('/favorite/seller/{id}/delete', 'User\UserController@favdelete')->name('user-favorite-delete');

        // Mobile Deposit Route section

        Route::get('/api/checkout/instamojo/notify', 'Api\User\Payment\InstamojoController@notify')
            ->name('api.user.deposit.instamojo.notify');

        Route::post('/api/paystack/submit', 'Api\User\Payment\PaystackController@store')
            ->name('api.user.deposit.paystack.submit');
        Route::post('/api/voguepay/submit', 'Api\User\Payment\VoguepayController@store')
            ->name('api.user.deposit.voguepay.submit');

        Route::post('/api/instamojo/submit', 'Api\User\Payment\InstamojoController@store')
            ->name('api.user.deposit.instamojo.submit');
        Route::post('/api/paypal-submit', 'Api\User\Payment\PaymentController@store')
            ->name('api.user.deposit.paypal.submit');
        Route::get('/api/paypal/notify', 'Api\User\Payment\PaymentController@notify')
            ->name('api.user.deposit.payment.notify');
        Route::post('/api/authorize-submit', 'Api\User\Payment\AuthorizeController@store')
            ->name('api.user.deposit.authorize.submit');

        Route::post('/api/payment/stripe-submit', 'Api\User\Payment\StripeController@store')
            ->name('api.user.deposit.stripe.submit');

        // ssl Routes
        Route::post('/api/ssl/submit', 'Api\User\Payment\SslController@store')->name('api.user.deposit.ssl.submit');
        Route::post('/api/ssl/notify', 'Api\User\Payment\SslController@notify')->name('api.user.deposit.ssl.notify');
        Route::post('/api/ssl/cancle', 'Api\User\Payment\SslController@cancle')->name('api.user.deposit.ssl.cancle');

        // Molly Routes
        Route::post('/api/molly/submit', 'Api\User\Payment\MollyController@store')
            ->name('api.user.deposit.molly.submit');
        Route::get('/api/molly/notify', 'Api\User\Payment\MollyController@notify')
            ->name('api.user.deposit.molly.notify');

        //PayTM Routes
        Route::post('/api/paytm-submit', 'Api\User\Payment\PaytmController@store')
            ->name('api.user.deposit.paytm.submit');
        Route::post('/api/paytm-callback', 'Api\User\Payment\PaytmController@paytmCallback')
            ->name('api.user.deposit.paytm.notify');

        //RazorPay Routes
        Route::post('/api/razorpay-submit', 'Api\User\Payment\RazorpayController@store')
            ->name('api.user.deposit.razorpay.submit');
        Route::post('/api/razorpay-callback', 'Api\User\Payment\RazorpayController@razorCallback')
            ->name('api.user.deposit.razorpay.notify');

        // Mercadopago Routes
        Route::get('/api/checkout/mercadopago/return', 'Api\User\Payment\MercadopagoController@payreturn')
            ->name('api.user.deposit.mercadopago.return');
        Route::post('/api/checkout/mercadopago/notify', 'Api\User\Payment\MercadopagoController@notify')
            ->name('api.user.deposit.mercadopago.notify');
        Route::post('/api/checkout/mercadopago/submit', 'Api\User\Payment\MercadopagoController@store')
            ->name('api.user.deposit.mercadopago.submit');
        // Flutterwave Routes
        Route::post('/api/flutter/submit', 'Api\User\Payment\FlutterWaveController@store')
            ->name('api.user.deposit.flutter.submit');
        Route::post('/api/flutter/notify', 'Api\User\Payment\FlutterWaveController@notify')
            ->name('api.user.deposit.flutter.notify');

        //2checkout Routes
        Route::post('/api/twocheckout-submit', 'Api\User\Payment\TwoCheckoutController@store')
            ->name('api.user.deposit.twocheckout.submit');

    });

});

