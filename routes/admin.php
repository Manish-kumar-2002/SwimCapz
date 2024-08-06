    <?php

    // ************************************ ADMIN SECTION **********************************************

    use App\Http\Controllers\Admin\TtfController;
    use Illuminate\Support\Facades\Artisan;
    use Illuminate\Support\Facades\Route;


    Route::prefix('admin')->group(function () {



        Route::get('/login', 'Auth\Admin\LoginController@showForm')->name('admin.login');
        Route::post('/login', 'Auth\Admin\LoginController@login')->name('admin.login.submit');
        Route::get('/logout', 'Auth\Admin\LoginController@logout')->name('admin.logout');


        Route::get('/forgot', 'Auth\Admin\ForgotController@showForm')->name('admin.forgot');
        Route::post('/forgot', 'Auth\Admin\ForgotController@forgot')->name('admin.forgot.submit');
        Route::get('/change-password/{token}', 'Auth\Admin\ForgotController@showChangePassForm')
            ->name('admin.change.token');
        Route::post('/change-password', 'Auth\Admin\ForgotController@changeForgotPass')->name('admin.change.password');


        Route::get('/all/notf/count', 'Admin\NotificationController@all_notf_count')->name('all-notf-count');

        // User Notification
        Route::get('/user/notf/show', 'Admin\NotificationController@user_notf_show')->name('user-notf-show');
        Route::get('/user/notf/clear', 'Admin\NotificationController@user_notf_clear')->name('user-notf-clear');
        // User Notification Ends

        // Order Notification
        Route::get('/order/notf/show', 'Admin\NotificationController@order_notf_show')->name('order-notf-show');
        Route::get('/order/notf/clear', 'Admin\NotificationController@order_notf_clear')->name('order-notf-clear');
        // Order Notification Ends

        // Product Notification
        Route::get('/product/notf/show', 'Admin\NotificationController@product_notf_show')->name('product-notf-show');
        Route::get('/product/notf/clear', 'Admin\NotificationController@product_notf_clear')->name('product-notf-clear');
        // Product Notification Ends

        // Product Notification
        Route::get('/conv/notf/show', 'Admin\NotificationController@conv_notf_show')->name('conv-notf-show');
        Route::get('/conv/notf/clear', 'Admin\NotificationController@conv_notf_clear')->name('conv-notf-clear');
        // Product Notification Ends


        //------------ ADMIN DASHBOARD & PROFILE SECTION ------------
        Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
        Route::get('/profile', 'Admin\DashboardController@profile')->name('admin.profile');
        Route::post('/profile/update', 'Admin\DashboardController@profileupdate')->name('admin.profile.update');
        Route::get('/password', 'Admin\DashboardController@passwordreset')->name('admin.password');
        Route::post('/password/update', 'Admin\DashboardController@changepass')->name('admin.password.update');
        //------------ ADMIN DASHBOARD & PROFILE SECTION ENDS ------------

        //------------ ADMIN ORDER SECTION ------------

        Route::group(['middleware' => 'permissions:orders'], function () {

            Route::get('/orders/datatables/{slug?}', 'Admin\OrderController@datatables')
                ->name('admin-order-datatables');

            Route::get('/orders', 'Admin\OrderController@orders')
                ->name('admin-orders-all');
            Route::get('/orders/pending/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-pending');
            Route::get('/orders/awating-approval/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-awating-approval');
            Route::get('/orders/accepted/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-accepted');
            Route::get('/orders/rejected/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-rejected');
            Route::get('/orders/shipped/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-shipped');
            Route::get('/orders/completed/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-completed');
            Route::get('/orders/cancelled/{status?}', 'Admin\OrderController@orders')
                ->name('admin-orders-cancelled');

            Route::get('/order/edit/{id}', 'Admin\OrderController@edit')->name('admin-order-edit');
            Route::post('/order/update/{id}', 'Admin\OrderController@update')->name('admin-order-update');
            Route::get('/order/{id}/show', 'Admin\OrderController@show')->name('admin-order-show');
            Route::get('/order/{id}/invoice', 'Admin\OrderController@invoice')->name('admin-order-invoice');
            Route::get('/order/{id}/print', 'Admin\OrderController@printpage')->name('admin-order-print');
            Route::get('/order/{id1}/status/{status}', 'Admin\OrderController@status')->name('admin-order-status');
            Route::post('/order/email/', 'Admin\OrderController@emailsub')->name('admin-order-emailsub');
            Route::post('/order/{id}/license', 'Admin\OrderController@license')->name('admin-order-license');
            Route::post('/order/product-submit', 'Admin\OrderController@product_submit')
                ->name('admin-order-product-submit');
            Route::get('/order/product-show/{id}', 'Admin\OrderController@product_show');
            Route::get('/order/addcart/{id}', 'Admin\OrderController@addcart');
            Route::get('/ordercart/product-edit/{id}/{itemid}/{orderid}', 'Admin\OrderController@product_edit')
                ->name('admin-order-product-edit');
            Route::get('/order/updatecart/{id}', 'Admin\OrderController@updatecart');
            Route::get('/ordercart/product-delete/{id}/{orderid}', 'Admin\OrderController@product_delete')
                ->name('admin-order-product-delete');

            Route::get('/orders/download/{status?}', 'Admin\OrderController@download')->name('order.download');
            // Order Tracking
            Route::get('/order-track/{order_id}', 'Admin\OrderController@trackOrder')
                ->name('track.order');

            Route::get('/download-order-file/{order_id}', 'Admin\OrderController@downloadOrderFile')
                ->name('download.order.file');
            // CREATE ORDER

            Route::get('/order/product/datatables', 'Admin\OrderCreateController@datatables')
                ->name('admin-order-product-datatables'); //JSON REQUEST
            Route::get('/order/create', 'Admin\OrderCreateController@create')->name('admin-order-create');
            Route::get('/order/product/add/{product_id}', 'Admin\OrderCreateController@addProduct')
                ->name('admin-order-product-add');
            Route::get('/order/product/add', 'Admin\OrderCreateController@orderStore')
                ->name('admin.order.store.new');
            Route::get('/order/product/remove/{product_id}', 'Admin\OrderCreateController@removeOrderProduct')
                ->name('admin.order.product.remove');
            Route::get('/order/create/product-show/{id}', 'Admin\OrderCreateController@product_show');
            Route::get('/order/create/addcart/{id}', 'Admin\OrderCreateController@addcart');
            Route::get('/order/remove/addcart/{id}', 'Admin\OrderCreateController@removeCart')
                ->name('admin.order.remove.cart');
            Route::get('/order/create/user-address', 'Admin\OrderCreateController@userAddress');
            Route::post('/order/create/user-address', 'Admin\OrderCreateController@userAddressSubmit')
                ->name('admin.order.create.user.address');
            Route::post('/order/create/order/view', 'Admin\OrderCreateController@viewCreateOrder')
                ->name('admin.order.create.view');
            Route::get('/order/create/order/submit', 'Admin\OrderCreateController@CreateOrderSubmit')
                ->name('admin-order-create-submit');

            Route::get('/order/{id}/track', 'Admin\OrderTrackController@index')->name('admin-order-track');
            Route::get('/order/{id}/trackload', 'Admin\OrderTrackController@load')->name('admin-order-track-load');
            Route::post('/order/track/store', 'Admin\OrderTrackController@store')->name('admin-order-track-store');
            Route::post('/order/trackNo/store', 'Admin\OrderController@createTracking')->name('admin-order-tracking-number-store');
            Route::get('/order/track/add', 'Admin\OrderTrackController@add')->name('admin-order-track-add');
            Route::get('/order/track/edit/{id}', 'Admin\OrderTrackController@edit')->name('admin-order-track-edit');
            Route::post('/order/track/update/{id}', 'Admin\OrderTrackController@update')->name('admin-order-track-update');
            Route::delete('/order/track/delete/{id}', 'Admin\OrderTrackController@delete')
                ->name('admin-order-track-delete');

            // Order Tracking Ends

        });

        //------------ ADMIN ORDER SECTION ENDS------------

        // --------------- ADMIN COUNRTY SECTION ---------------//
        Route::get('/country/datatables', 'Admin\CountryController@datatables')->name('admin-country-datatables');
        Route::get('/manage/country', 'Admin\CountryController@manageCountry')->name('admin-country-index');
        Route::get('/manage/country/status/{id1}/{id2}', 'Admin\CountryController@status')->name('admin-country-status');
        Route::get('/country/delete/{id}', 'Admin\CountryController@delete')->name('admin-country-delete');
        Route::get('/country/tax/datatables', 'Admin\CountryController@taxDatatables')
            ->name('admin-country-tax-datatables');
        Route::get('/manage/country/tax', 'Admin\CountryController@country_tax')->name('admin-country-tax');

        // --------------- ADMIN COUNRTY SECTION END -----------//

        // tax
        Route::get('/country/set-tax/{id}', 'Admin\CountryController@setTax')->name('admin-set-tax');
        Route::post('/country/set-tax/store/{id}', 'Admin\CountryController@updateTax')->name('admin-tax-update');

        // --------------- ADMIN STATE SECTION --------------------//

        Route::get('/state/datatables/{country}', 'Admin\StateController@datatables')->name('admin-state-datatables');
        Route::get('/manage/state/{country}', 'Admin\StateController@manageState')->name('admin-state-index');
        Route::get('/state/create/{country}', 'Admin\StateController@create')->name('admin-state-create');
        Route::post('/state/store/{country}', 'Admin\StateController@store')->name('admin-state-store');
        Route::get('/state/status/{id1}/{id2}', 'Admin\StateController@status')->name('admin-state-status');
        Route::get('/state/edit/{id}', 'Admin\StateController@edit')->name('admin-state-edit');
        Route::post('/state/update/{id}', 'Admin\StateController@update')->name('admin-state-update');
        Route::delete('/state/delete/{id}', 'Admin\StateController@delete')->name('admin-state-delete');

        // --------------- ADMIN STATE SECTION --------------------//

        //------------ ADMIN CATEGORY SECTION ENDS------------

        Route::group(['middleware' => 'permissions:earning'], function () {

            // -------------------------- Admin Total Income Route --------------------------//
            Route::get('tax/calculate', 'Admin\IncomeController@taxCalculate')
                ->name('admin-tax-calculate-income');
            Route::get('subscription/earning', 'Admin\IncomeController@subscriptionIncome')
                ->name('admin-subscription-income');
            Route::get('withdraw/earning', 'Admin\IncomeController@withdrawIncome')->name('admin-withdraw-income');
            Route::get('commission/earning', 'Admin\IncomeController@commissionIncome')->name('admin-commission-income');
            // -------------------------- Admin Total Income Route --------------------------//
        });


        Route::group(['middleware' => 'permissions:categories'], function () {

            Route::get('/category/datatables', 'Admin\CategoryController@datatables')
                ->name('admin-cat-datatables');

            Route::get('/category', 'Admin\CategoryController@index')->name('admin-cat-index');
            Route::get('/category/create', 'Admin\CategoryController@create')->name('admin-cat-create');
            Route::post('/category/create', 'Admin\CategoryController@store')->name('admin-cat-store');
            Route::get('/category/edit/{id}', 'Admin\CategoryController@edit')->name('admin-cat-edit');
            Route::post('/category/edit/{id}', 'Admin\CategoryController@update')->name('admin-cat-update');
            Route::delete('/category/delete/{id}', 'Admin\CategoryController@destroy')->name('admin-cat-delete');
            Route::get('/category/featured/{id1}/{id2?}', 'Admin\CategoryController@featured')->name('admin-cat-featured');
            Route::get('/category/status/{id1}/{id2?}', 'Admin\CategoryController@status')->name('admin-cat-status');
            Route::get('/category/download/{status?}', 'Admin\CategoryController@download')
                ->name('category.download');

            /* price structure resource */
            Route::resource('price-structure', 'Admin\PriceStructureController')
                ->except(['destroy']);


            Route::get('/attribute/datatables', 'Admin\AttributeController@datatables')
                ->name('admin-attr-datatables'); //JSON REQUEST
            Route::get('/attribute', 'Admin\AttributeController@index')->name('admin-attr-index');
            Route::get('/attribute/{catid}/attrCreateForCategory', 'Admin\AttributeController@attrCreateForCategory')
                ->name('admin-attr-createForCategory');
            Route::get('/attribute/{subcatid}/attrCreateForSubcategory', 'Admin\AttributeController@attrCreateForSubcategory')
                ->name('admin-attr-createForSubcategory');
            Route::get('/attribute/{childcatid}/attrCreateForChildcategory', 'Admin\AttributeController@attrCreateForChildcategory')
                ->name('admin-attr-createForChildcategory');
            Route::post('/attribute/store', 'Admin\AttributeController@store')->name('admin-attr-store');
            Route::get('/attribute/{id}/manage', 'Admin\AttributeController@manage')->name('admin-attr-manage');
            Route::get('/attribute/{attrid}/edit', 'Admin\AttributeController@edit')->name('admin-attr-edit');
            Route::post('/attribute/edit/{id}', 'Admin\AttributeController@update')->name('admin-attr-update');
            Route::get('/attribute/{id}/options', 'Admin\AttributeController@options')->name('admin-attr-options');
            Route::get('/attribute/delete/{id}', 'Admin\AttributeController@destroy')->name('admin-attr-delete');

            // SUBCATEGORY SECTION ------------

            Route::get('/subcategory/datatables', 'Admin\SubCategoryController@datatables')
                ->name('admin-subcat-datatables'); //JSON REQUEST
            Route::get('/subcategory', 'Admin\SubCategoryController@index')->name('admin-subcat-index');
            Route::get('/subcategory/create', 'Admin\SubCategoryController@create')->name('admin-subcat-create');
            Route::post('/subcategory/create', 'Admin\SubCategoryController@store')->name('admin-subcat-store');
            Route::get('/subcategory/edit/{id}', 'Admin\SubCategoryController@edit')->name('admin-subcat-edit');
            Route::post('/subcategory/edit/{id}', 'Admin\SubCategoryController@update')->name('admin-subcat-update');
            Route::delete('/subcategory/delete/{id}', 'Admin\SubCategoryController@destroy')->name('admin-subcat-delete');
            Route::get('/subcategory/status/{id1}/{id2}', 'Admin\SubCategoryController@status')
                ->name('admin-subcat-status');
            Route::get('/load/subcategories/{id}/', 'Admin\SubCategoryController@load')
                ->name('admin-subcat-load'); //JSON REQUEST

            // SUBCATEGORY SECTION ENDS------------

            // CHILDCATEGORY SECTION ------------

            Route::get('/childcategory/datatables', 'Admin\ChildCategoryController@datatables')
                ->name('admin-childcat-datatables'); //JSON REQUEST
            Route::get('/childcategory', 'Admin\ChildCategoryController@index')->name('admin-childcat-index');
            Route::get('/childcategory/create', 'Admin\ChildCategoryController@create')->name('admin-childcat-create');
            Route::post('/childcategory/create', 'Admin\ChildCategoryController@store')->name('admin-childcat-store');
            Route::get('/childcategory/edit/{id}', 'Admin\ChildCategoryController@edit')->name('admin-childcat-edit');
            Route::post('/childcategory/edit/{id}', 'Admin\ChildCategoryController@update')
                ->name('admin-childcat-update');
            Route::delete('/childcategory/delete/{id}', 'Admin\ChildCategoryController@destroy')
                ->name('admin-childcat-delete');
            Route::get('/childcategory/status/{id1}/{id2}', 'Admin\ChildCategoryController@status')
                ->name('admin-childcat-status');
            Route::get('/load/childcategories/{id}/', 'Admin\ChildCategoryController@load')
                ->name('admin-childcat-load'); //JSON REQUEST

            // CHILDCATEGORY SECTION ENDS------------

        });

        //------------ ADMIN PRODUCT SECTION ------------
        Route::group(['middleware' => 'permissions:products'], function () {

            Route::get('/products/datatables', 'Admin\ProductController@datatables')
                ->name('admin-prod-datatables');
            Route::get('/products', 'Admin\ProductController@index')->name('admin-prod-index');

            Route::post('/products/upload/update/{id}', 'Admin\ProductController@uploadUpdate')
                ->name('admin-prod-upload-update');

            Route::get('/products/deactive', 'Admin\ProductController@deactive')->name('admin-prod-deactive');

            Route::get('/products/catalogs/datatables', 'Admin\ProductController@catalogdatatables')
                ->name('admin-prod-catalog-datatables');
            Route::get('/products/catalogs/', 'Admin\ProductController@productscatalog')
                ->name('admin-prod-catalog-index');

            Route::get('/products/download', 'Admin\ProductController@download')
                ->name('admin-prod-download');

            Route::get('/optimize-clear', function () {
                Artisan::call('optimize:clear');
                return 'Optimized and cache cleared!';
            });

            // CREATE SECTION
            Route::get('/products/types', 'Admin\ProductController@types')->name('admin-prod-types');
            Route::get('/products/{slug}/create', 'Admin\ProductController@create')->name('admin-prod-create');
            Route::get('/products/create', 'Admin\ProductController@create')->name('admin-prod-create');
            Route::post('/products/store', 'Admin\ProductController@store')->name('admin-prod-store');
            Route::get('/getattributes', 'Admin\ProductController@getAttributes')->name('admin-prod-getattributes');
            Route::get('/get/crossproduct/{catid}', 'Admin\ProductController@getCrossProduct');
            // CREATE SECTION

            // EDIT SECTION
            Route::get('/products/edit/{id}', 'Admin\ProductController@edit')->name('admin-prod-edit');
            Route::post('/products/edit/{id}', 'Admin\ProductController@update')->name('admin-prod-update');
            // EDIT SECTION ENDS

            // DELETE SECTION
            Route::delete('/products/delete/{id}', 'Admin\ProductController@destroy')->name('admin-prod-delete');
            // DELETE SECTION ENDS

            Route::get('/products/catalog/{id1}/{id2}', 'Admin\ProductController@catalog')->name('admin-prod-catalog');

            Route::get('/products/product-settings', 'Admin\ProductController@productsettings')
                ->name('admin-gs-prod-settings');
            Route::post('/products/product-settings/update', 'Admin\ProductController@settingUpdate')
                ->name('admin-gs-prod-settings-update');

            Route::get('/products/latested-product/update/{id}', 'Admin\ProductController@latestProductUpdate')
                ->name('admin.latest.product.update');

            //product type
            Route::get('/products/type/datatables', 'Admin\ProductTypeController@datatables')
                ->name('admin-product-type-datatables'); //JSON REQUEST
            Route::get('/products/type', 'Admin\ProductTypeController@index')
                ->name('admin-product-type-index');
            Route::get('/products/type-create', 'Admin\ProductTypeController@create')
                ->name('admin-product-type-create');
            Route::post('/products/type-create', 'Admin\ProductTypeController@store')
                ->name('admin-product-type-store');
            Route::get('/products/type-edit/{id}', 'Admin\ProductTypeController@edit')
                ->name('admin-product-type-edit');
            Route::post('/products/type-edit/{id}', 'Admin\ProductTypeController@update')
                ->name('admin-product-type-update');
            Route::delete('/products/type-delete/{id}', 'Admin\ProductTypeController@destroy')
                ->name('admin-product-type-delete');
            Route::get('/products/type-status/{id1}/{id2}', 'Admin\ProductTypeController@status')
                ->name('admin-product-type-status');

            Route::get('/product-types/download', 'Admin\ProductTypeController@download')
                ->name('product-type-download');

            //product variants
            Route::post('/products/variant-store', 'Admin\ProductController@variantStore')
                ->name('admin-product-variant');
            Route::get('/products/show-variant/{prod}/{id}', 'Admin\ProductController@variantShow')
                ->name('product.show-variant');
            Route::post('/products/delete-variant/{prod}/{id}', 'Admin\ProductController@variantDelete')
                ->name('product.variant-delete');
            Route::post('/products/update-variant', 'Admin\ProductController@variantUpdate')
                ->name('product.update-variant');
            Route::post('/products/variants/images/delete/{id}', 'Admin\ProductController@varianImageDelete')
                ->name('product.variant-image-delete');

            //pricing break
            Route::post('/pricing-break/store', 'Admin\ProductController@pricingBreakStore')
                ->name('admin-pricing-break-store');
            Route::get('/pricing-break/show/{prod}', 'Admin\ProductController@pricingBreakShow')
                ->name('admin-pricing-break-variant');
            Route::post('/pricing-break/break-delete/{id}', 'Admin\ProductController@pricingBreakDelete')
                ->name('admin-pricing-break-delete');
        });

        //------------ ADMIN PRODUCT SECTION ENDS------------

        //------------ ADMIN AFFILIATE PRODUCT SECTION ------------

        Route::group(['middleware' => 'permissions:affilate_products'], function () {

            Route::get('/products/import/create-product', 'Admin\ImportController@createImport')
                ->name('admin-import-create');
            Route::get('/products/import/edit/{id}', 'Admin\ImportController@edit')
                ->name('admin-import-edit');

            Route::get('/products/import/datatables', 'Admin\ImportController@datatables')
                ->name('admin-import-datatables'); //JSON REQUEST
            Route::get('/products/import/index', 'Admin\ImportController@index')->name('admin-import-index');

            Route::post('/products/import/store', 'Admin\ImportController@store')->name('admin-import-store');
            Route::post('/products/import/update/{id}', 'Admin\ImportController@update')->name('admin-import-update');

            // DELETE SECTION
            Route::delete('/affiliate/products/delete/{id}', 'Admin\ProductController@destroy')
                ->name('admin-affiliate-prod-delete');
            // DELETE SECTION ENDS

        });

        Route::group(['middleware' => 'permissions:bulk_product_upload'], function () {

            Route::get('/products/import', 'Admin\ProductController@import')->name('admin-prod-import');
            Route::post('/products/import-submit', 'Admin\ProductController@importSubmit')->name('admin-prod-importsubmit');
        });

        Route::group(['middleware' => 'permissions:product_discussion'], function () {

            // RATING SECTION ENDS------------

            Route::get('/ratings/datatables', 'Admin\RatingController@datatables')
                ->name('admin-rating-datatables');
            Route::get('/ratings', 'Admin\RatingController@index')->name('admin-rating-index');
            Route::delete('/ratings/delete/{id}', 'Admin\RatingController@destroy')->name('admin-rating-delete');
            Route::get('/ratings/show/{id}', 'Admin\RatingController@show')->name('admin-rating-show');

            Route::get('/comments/datatables', 'Admin\CommentController@datatables')
                ->name('admin-comment-datatables');
            Route::get('/comments', 'Admin\CommentController@index')->name('admin-comment-index');
            Route::delete('/comments/delete/{id}', 'Admin\CommentController@destroy')->name('admin-comment-delete');
            Route::get('/comments/show/{id}', 'Admin\CommentController@show')->name('admin-comment-show');

            Route::get('/reports/datatables', 'Admin\ReportController@datatables')
                ->name('admin-report-datatables');
            Route::get('/reports', 'Admin\ReportController@index')->name('admin-report-index');
            Route::delete('/reports/delete/{id}', 'Admin\ReportController@destroy')->name('admin-report-delete');
            Route::get('/reports/show/{id}', 'Admin\ReportController@show')->name('admin-report-show');
        });

        Route::group(['middleware' => 'permissions:set_coupons'], function () {

            Route::get('/coupon/datatables', 'Admin\CouponController@datatables')
                ->name('admin-coupon-datatables');
            Route::get('/coupon', 'Admin\CouponController@index')->name('admin-coupon-index');
            Route::get('/coupon/create', 'Admin\CouponController@create')->name('admin-coupon-create');
            Route::post('/coupon/create', 'Admin\CouponController@store')->name('admin-coupon-store');
            Route::get('/coupon/edit/{id}', 'Admin\CouponController@edit')->name('admin-coupon-edit');
            Route::post('/coupon/edit/{id}', 'Admin\CouponController@update')->name('admin-coupon-update');
            Route::delete('/coupon/delete/{id}', 'Admin\CouponController@destroy')->name('admin-coupon-delete');
            Route::get('/coupon/status/{id1}/{id2?}', 'Admin\CouponController@status')->name('admin-coupon-status');
        });

        Route::group(['middleware' => 'permissions:customers'], function () {

            Route::get('/users/datatables', 'Admin\UserController@datatables')
                ->name('admin-user-datatables');
            Route::get('/users', 'Admin\UserController@index')->name('admin-user-index');
            Route::get('/users/create', 'Admin\UserController@create')->name('admin-user-create');
            Route::post('/users/store', 'Admin\UserController@store')->name('admin-user-store');
            Route::get('/users/edit/{id}', 'Admin\UserController@edit')->name('admin-user-edit');
            Route::post('/users/edit/{id}', 'Admin\UserController@update')->name('admin-user-update');
            Route::delete('/users/delete/{id}', 'Admin\UserController@destroy')->name('admin-user-delete');
            Route::get('/user/{id}/show', 'Admin\UserController@show')->name('admin-user-show');
            Route::get('/users/ban/{id1}/{id2}', 'Admin\UserController@ban')->name('admin-user-ban');
            Route::get('/user/default/image', 'Admin\GeneralSettingController@user_image')->name('admin-user-image');
            Route::get('/users/deposit/{id}', 'Admin\UserController@deposit')->name('admin-user-deposit');
            Route::post('/user/deposit/{id}', 'Admin\UserController@depositUpdate')->name('admin-user-deposit-update');

            Route::get('/users/download', 'Admin\UserController@download')
                ->name('admin-user-download');



            // WITHDRAW SECTION

            Route::get('/users/withdraws/datatables', 'Admin\UserController@withdrawdatatables')
                ->name('admin-withdraw-datatables'); //JSON REQUEST
            Route::get('/users/withdraws', 'Admin\UserController@withdraws')->name('admin-withdraw-index');
            Route::get('/user/withdraw/{id}/show', 'Admin\UserController@withdrawdetails')->name('admin-withdraw-show');
            Route::get('/users/withdraws/accept/{id}', 'Admin\UserController@accept')->name('admin-withdraw-accept');
            Route::get('/user/withdraws/reject/{id}', 'Admin\UserController@reject')->name('admin-withdraw-reject');

            // WITHDRAW SECTION ENDS

        });


        Route::group(['middleware' => 'permissions:customer_deposits'], function () {

            Route::get('/users/deposit/datatables/{status}', 'Admin\UserDepositController@datatables')
                ->name('admin-user-deposit-datatables');
            Route::get('/users/deposits/{slug}', 'Admin\UserDepositController@deposits')
                ->name('admin-user-deposits');
            Route::get('/users/deposits/status/{id1}/{id2}', 'Admin\UserDepositController@status')
                ->name('admin-user-deposit-status');
            Route::get('/users/transactions/datatables', 'Admin\UserTransactionController@transdatatables')
                ->name('admin-trans-datatables');
            Route::get('/users/transactions', 'Admin\UserTransactionController@index')
                ->name('admin-trans-index');
            Route::get('/users/transactions/{id}/show', 'Admin\UserTransactionController@transhow')
                ->name('admin-trans-show');
        });


        Route::group(['middleware' => 'permissions:messages'], function () {

            Route::get('/messages/datatables/{type}', 'Admin\MessageController@datatables')
                ->name('admin-message-datatables');
            Route::get('/tickets', 'Admin\MessageController@index')->name('admin-message-index');
            Route::get('/disputes', 'Admin\MessageController@dispute')->name('admin-message-dispute');
            Route::get('/message/{id}', 'Admin\MessageController@message')->name('admin-message-show');
            Route::get('/message/load/{id}', 'Admin\MessageController@messageshow')->name('admin-message-load');
            Route::post('/message/post', 'Admin\MessageController@postmessage')->name('admin-message-store');
            Route::delete('/message/{id}/delete', 'Admin\MessageController@messagedelete')->name('admin-message-delete');
            Route::post('/user/send/message', 'Admin\MessageController@usercontact')->name('admin-send-message');
        });

        //------------ ADMIN USER MESSAGE SECTION ENDS ------------

        //------------ ADMIN BLOG SECTION ------------

        Route::group(['middleware' => 'permissions:blog'], function () {

            Route::get('/blog/datatables', 'Admin\BlogController@datatables')->name('admin-blog-datatables'); //JSON REQUEST
            Route::get('/blog', 'Admin\BlogController@index')->name('admin-blog-index');
            Route::get('/blog/create', 'Admin\BlogController@create')->name('admin-blog-create');
            Route::post('/blog/create', 'Admin\BlogController@store')->name('admin-blog-store');
            Route::get('/blog/edit/{id}', 'Admin\BlogController@edit')->name('admin-blog-edit');
            Route::post('/blog/edit/{id}', 'Admin\BlogController@update')->name('admin-blog-update');
            Route::delete('/blog/delete/{id}', 'Admin\BlogController@destroy')->name('admin-blog-delete');

            Route::get('/blog/category/datatables', 'Admin\BlogCategoryController@datatables')
                ->name('admin-cblog-datatables');
            Route::get('/blog/category', 'Admin\BlogCategoryController@index')->name('admin-cblog-index');
            Route::get('/blog/category/create', 'Admin\BlogCategoryController@create')->name('admin-cblog-create');
            Route::post('/blog/category/create', 'Admin\BlogCategoryController@store')->name('admin-cblog-store');
            Route::get('/blog/category/edit/{id}', 'Admin\BlogCategoryController@edit')->name('admin-cblog-edit');
            Route::post('/blog/category/edit/{id}', 'Admin\BlogCategoryController@update')->name('admin-cblog-update');
            Route::delete('/blog/category/delete/{id}', 'Admin\BlogCategoryController@destroy')->name('admin-cblog-delete');

            Route::get('/blog/blog-settings', 'Admin\BlogController@settings')->name('admin-gs-blog-settings');
        });

        //------------ ADMIN BLOG SECTION ENDS ------------

        //------------ ADMIN GENERAL SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:general_settings'], function () {

            Route::get('/general-settings/logo', 'Admin\GeneralSettingController@logo')->name('admin-gs-logo');
            Route::get('/general-settings/favicon', 'Admin\GeneralSettingController@favicon')->name('admin-gs-fav');
            Route::get('/general-settings/loader', 'Admin\GeneralSettingController@loader')->name('admin-gs-load');
            Route::get('/general-settings/contents', 'Admin\GeneralSettingController@websitecontent')
                ->name('admin-gs-contents');
            Route::get('/general-settings/affilate', 'Admin\GeneralSettingController@affilate')
                ->name('admin-gs-affilate');
            Route::get('/general-settings/error-banner', 'Admin\GeneralSettingController@error_banner')
                ->name('admin-gs-error-banner');
            Route::get('/general-settings/popup', 'Admin\GeneralSettingController@popup')->name('admin-gs-popup');
            Route::get('/general-settings/breadcrumb', 'Admin\GeneralSettingController@breadcrumb')->name('admin-gs-bread');
            Route::get('/general-settings/maintenance', 'Admin\GeneralSettingController@maintain')
                ->name('admin-gs-maintenance');


            Route::get('/pickup/datatables', 'Admin\PickupController@datatables')
                ->name('admin-pick-datatables');
            Route::get('/pickup', 'Admin\PickupController@index')->name('admin-pick-index');
            Route::get('/pickup/create', 'Admin\PickupController@create')->name('admin-pick-create');
            Route::post('/pickup/create', 'Admin\PickupController@store')->name('admin-pick-store');
            Route::get('/pickup/edit/{id}', 'Admin\PickupController@edit')->name('admin-pick-edit');
            Route::post('/pickup/edit/{id}', 'Admin\PickupController@update')->name('admin-pick-update');
            Route::delete('/pickup/delete/{id}', 'Admin\PickupController@destroy')->name('admin-pick-delete');


            Route::get('/shipping/datatables', 'Admin\ShippingController@datatables')->name('admin-shipping-datatables');
            Route::get('/shipping', 'Admin\ShippingController@index')->name('admin-shipping-index');
            Route::get('/shipping/create', 'Admin\ShippingController@create')->name('admin-shipping-create');
            Route::post('/shipping/create', 'Admin\ShippingController@store')->name('admin-shipping-store');
            Route::get('/shipping/edit/{id}', 'Admin\ShippingController@edit')->name('admin-shipping-edit');
            Route::post('/shipping/edit/{id}', 'Admin\ShippingController@update')->name('admin-shipping-update');
            Route::delete('/shipping/delete/{id}', 'Admin\ShippingController@destroy')->name('admin-shipping-delete');


            Route::get('/package/datatables', 'Admin\PackageController@datatables')->name('admin-package-datatables');
            Route::get('/package', 'Admin\PackageController@index')->name('admin-package-index');
            Route::get('/package/create', 'Admin\PackageController@create')->name('admin-package-create');
            Route::post('/package/create', 'Admin\PackageController@store')->name('admin-package-store');
            Route::get('/package/edit/{id}', 'Admin\PackageController@edit')->name('admin-package-edit');
            Route::post('/package/edit/{id}', 'Admin\PackageController@update')->name('admin-package-update');
            Route::delete('/package/delete/{id}', 'Admin\PackageController@destroy')->name('admin-package-delete');
        });


        Route::group(['middleware' => 'permissions:home_page_settings'], function () {

            //------------ ADMIN SLIDER SECTION ------------

            Route::get('/slider/datatables', 'Admin\SliderController@datatables')
                ->name('admin-sl-datatables'); //JSON REQUEST
            Route::get('/slider', 'Admin\SliderController@index')->name('admin-sl-index');
            Route::get('/slider/create', 'Admin\SliderController@create')->name('admin-sl-create');
            Route::post('/slider/create', 'Admin\SliderController@store')
                ->name('admin-sl-store');
            Route::get('/slider/edit/{id}', 'Admin\SliderController@edit')->name('admin-sl-edit');
            Route::post('/slider/edit/{id}', 'Admin\SliderController@update')->name('admin-sl-update');
            Route::delete('/slider/delete/{id}', 'Admin\SliderController@destroy')->name('admin-sl-delete');

            //------------ ADMIN SLIDER SECTION ENDS ------------

            Route::get('/arrival/datatables', 'Admin\ArrivalsectionController@datatables')
                ->name('admin-arrival-datatables');
            Route::get('/arrival', 'Admin\ArrivalsectionController@index')->name('admin-arrival-index');
            Route::get('/arrival/create', 'Admin\ArrivalsectionController@create')->name('admin-arrival-create');
            Route::post('/arrival/create', 'Admin\ArrivalsectionController@store')->name('admin-arrival-store');
            Route::get('/arrival/edit/{id}', 'Admin\ArrivalsectionController@edit')->name('admin-arrival-edit');
            Route::post('/arrival/edit/{id}', 'Admin\ArrivalsectionController@update')->name('admin-arrival-update');
            Route::delete('/arrival/delete/{id}', 'Admin\ArrivalsectionController@destroy')
                ->name('admin-arrival-delete');
            Route::get('/country/status/{id1}/{id2}', 'Admin\ArrivalsectionController@status')
                ->name('admin-arrival-status');

            //------------ ADMIN SERVICE SECTION ------------

            Route::get('/service/datatables', 'Admin\ServiceController@datatables')
                ->name('admin-service-datatables'); //JSON REQUEST
            Route::get('/service', 'Admin\ServiceController@index')->name('admin-service-index');
            Route::get('/service/create', 'Admin\ServiceController@create')->name('admin-service-create');
            Route::post('/service/create', 'Admin\ServiceController@store')->name('admin-service-store');
            Route::get('/service/edit/{id}', 'Admin\ServiceController@edit')->name('admin-service-edit');
            Route::post('/service/edit/{id}', 'Admin\ServiceController@update')->name('admin-service-update');
            Route::delete('/service/delete/{id}', 'Admin\ServiceController@destroy')->name('admin-service-delete');

            //------------ ADMIN SERVICE SECTION ENDS ------------

            //------------ ADMIN BANNER SECTION ------------

            Route::get('/banner/datatables/{type}', 'Admin\BannerController@datatables')->name('admin-sb-datatables'); //JSON REQUEST
            Route::get('large/banner/', 'Admin\BannerController@large')->name('admin-sb-large');
            Route::get('large/banner/create', 'Admin\BannerController@largecreate')->name('admin-sb-create-large');
            Route::post('/banner/create', 'Admin\BannerController@store')->name('admin-sb-store');
            Route::get('/banner/edit/{id}', 'Admin\BannerController@edit')->name('admin-sb-edit');
            Route::post('/banner/edit/{id}', 'Admin\BannerController@update')->name('admin-sb-update');
            Route::delete('/banner/delete/{id}', 'Admin\BannerController@destroy')->name('admin-sb-delete');

            //------------ ADMIN BANNER SECTION ENDS ------------

            //------------ ADMIN PARTNER SECTION ------------

            Route::get('/partner/datatables', 'Admin\PartnerController@datatables')->name('admin-partner-datatables');
            Route::get('/partner', 'Admin\PartnerController@index')->name('admin-partner-index');
            Route::get('/partner/create', 'Admin\PartnerController@create')->name('admin-partner-create');
            Route::post('/partner/create', 'Admin\PartnerController@store')->name('admin-partner-store');
            Route::get('/partner/edit/{id}', 'Admin\PartnerController@edit')->name('admin-partner-edit');
            Route::post('/partner/edit/{id}', 'Admin\PartnerController@update')->name('admin-partner-update');
            Route::delete('/partner/delete/{id}', 'Admin\PartnerController@destroy')->name('admin-partner-delete');

            //------------ ADMIN PARTNER SECTION ENDS ------------

            //------------ ADMIN PAGE SETTINGS SECTION ------------

            Route::get('/page-settings/customize', 'Admin\PageSettingController@customize')
                ->name('admin-ps-customize');
            Route::get('/page-settings/big-save', 'Admin\PageSettingController@big_save')
                ->name('admin-ps-big-save');
            Route::get('/page-settings/best-seller', 'Admin\PageSettingController@best_seller')
                ->name('admin-ps-best-seller');
        });

        //------------ ADMIN HOME PAGE SETTINGS SECTION ENDS ------------

        Route::group(['middleware' => 'permissions:menu_page_settings'], function () {

            //------------ ADMIN MENU PAGE SETTINGS SECTION ------------

            //------------ ADMIN FAQ SECTION ------------

            Route::get('/faq/datatables', 'Admin\FaqController@datatables')->name('admin-faq-datatables'); //JSON REQUEST
            Route::get('/faq', 'Admin\FaqController@index')->name('admin-faq-index');
            Route::get('/faq/create', 'Admin\FaqController@create')->name('admin-faq-create');
            Route::post('/faq/create', 'Admin\FaqController@store')->name('admin-faq-store');
            Route::get('/faq/edit/{id}', 'Admin\FaqController@edit')->name('admin-faq-edit');
            Route::post('/faq/update/{id}', 'Admin\FaqController@update')->name('admin-faq-update');
            Route::delete('/faq/delete/{id}', 'Admin\FaqController@destroy')->name('admin-faq-delete');

            //------------ ADMIN FAQ SECTION ENDS ------------

            //------------ ADMIN PAGE SECTION ------------

            Route::get('/page/datatables', 'Admin\PageController@datatables')->name('admin-page-datatables'); //JSON REQUEST
            Route::get('/page', 'Admin\PageController@index')->name('admin-page-index');
            Route::get('/page/create', 'Admin\PageController@create')->name('admin-page-create');
            Route::post('/page/create', 'Admin\PageController@store')->name('admin-page-store');
            Route::get('/page/edit/{id}', 'Admin\PageController@edit')->name('admin-page-edit');
            Route::post('/page/update/{id}', 'Admin\PageController@update')->name('admin-page-update');
            Route::delete('/page/delete/{id}', 'Admin\PageController@destroy')->name('admin-page-delete');
            Route::get('/page/header/{id1}/{id2}', 'Admin\PageController@header')->name('admin-page-header');
            Route::get('/page/footer/{id1}/{id2}', 'Admin\PageController@footer')->name('admin-page-footer');
            Route::get('/page/banner', 'Admin\PageSettingController@page_banner')->name('admin-ps-page-banner');
            Route::get('/right/banner', 'Admin\PageSettingController@right_banner')->name('admin-ps-right-banner');
            Route::get('/menu/links', 'Admin\PageSettingController@menu_links')->name('admin-ps-menu-links');
            Route::get('/deal/of/day', 'Admin\PageSettingController@deal')->name('admin-ps-deal');
            //------------ ADMIN PAGE SECTION ENDS------------

            Route::get('/page-settings/contact', 'Admin\PageSettingController@contact')->name('admin-ps-contact');
            Route::post('/page-settings/update/all', 'Admin\PageSettingController@update')->name('admin-ps-update');

            //--------------------------FEATURED ON --------------------------
            Route::get('/featured/datatables', 'Admin\FeaturedOnController@datatables')
                ->name('admin-featured-datatables'); //JSON REQUEST
            Route::get('/featured', 'Admin\FeaturedOnController@index')->name('admin-featured-index');
            Route::get('/featured/create', 'Admin\FeaturedOnController@create')->name('admin-featured-create');
            Route::post('/featured/store', 'Admin\FeaturedOnController@store')->name('admin-featured-store');
            Route::get('/featured/{id}', 'Admin\FeaturedOnController@edit')
                ->name('admin-featured-edit');
            Route::post('/featured/{id}', 'Admin\FeaturedOnController@update')->name('admin-featured-update');
            Route::delete('/featured/{id}', 'Admin\FeaturedOnController@destroy')->name('admin-featured-delete');
            Route::get('/featured/status/{id1}/{id2}', 'Admin\FeaturedOnController@status')->name('admin-featured-status');

            //-------------------------------- Testimonial------------
            Route::get('/testimonial/datatables', 'Admin\TestimonialController@datatables')
                ->name('admin-testimonial-datatables'); //JSON REQUEST
            Route::get('/testimonial', 'Admin\TestimonialController@index')->name('admin-testimonial-index');
            Route::get('/testimonial/create', 'Admin\TestimonialController@create')->name('admin-testimonial-create');
            Route::post('/testimonial/store', 'Admin\TestimonialController@store')->name('admin-testimonial-store');
            Route::get('/testimonial/{id}', 'Admin\TestimonialController@edit')->name('admin-testimonial-edit');
            Route::post('/testimonial/{id}', 'Admin\TestimonialController@update')->name('admin-testimonial-update');
            Route::delete('/testimonial/{id}', 'Admin\TestimonialController@destroy')->name('admin-testimonial-delete');
            Route::get('/testimonial/status/{id1}/{id2}', 'Admin\TestimonialController@status')
                ->name('admin-testimonial-status');
            Route::get('/youtube/{id}', 'Admin\TestimonialController@editYoutube')->name('admin-youtube-edit');
            Route::post('/youtube/{id}', 'Admin\TestimonialController@youtubeUpdate')->name('admin-youtube-update');

            Route::get('/aboutus/{id}', 'Admin\TestimonialController@editAboutUs')->name('admin-aboutus-edit');
            Route::post('/aboutus/{id}', 'Admin\TestimonialController@aboutUsUpdate')->name('admin-aboutus-update');

            //--------------------------------Gallery Home------------
            Route::get('/gallery/datatables', 'Admin\GalleryHomeController@datatables')
                ->name('admin-gallery-datatables'); //JSON REQUEST
            Route::get('/gallery', 'Admin\GalleryHomeController@index')->name('admin-gallery-index');
            Route::get('/gallery/create', 'Admin\GalleryHomeController@create')->name('admin-gallery-create');
            Route::post('/gallery-store', 'Admin\GalleryHomeController@store')->name('admin-gallery-home-store');
            Route::get('/gallery/{id}', 'Admin\GalleryHomeController@edit')->name('admin-gallery-edit');
            Route::post('/gallery/{id}', 'Admin\GalleryHomeController@update')->name('admin-gallery-update');
            Route::delete('/gallery-home/{id}', 'Admin\GalleryHomeController@destroy')->name('admin-gallery-home-delete');
            Route::get('/gallery/status/{id1}/{id2}', 'Admin\GalleryHomeController@status')->name('admin-gallery-status');
        });

        //------------ ADMIN MENU PAGE SETTINGS SECTION ENDS ------------

        //------------ ADMIN EMAIL SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:email_settings'], function () {

            Route::get('/email-templates/datatables', 'Admin\EmailController@datatables')->name('admin-mail-datatables');
            Route::get('/email-templates', 'Admin\EmailController@index')->name('admin-mail-index');
            Route::get('/email-templates/{id}', 'Admin\EmailController@edit')->name('admin-mail-edit');
            Route::post('/email-templates/{id}', 'Admin\EmailController@update')->name('admin-mail-update');
            Route::get('/email-config', 'Admin\EmailController@config')->name('admin-mail-config');
            Route::get('/groupemail', 'Admin\EmailController@groupemail')->name('admin-group-show');
            Route::post('/groupemailpost', 'Admin\EmailController@groupemailpost')->name('admin-group-submit');
        });

        //------------ ADMIN EMAIL SETTINGS SECTION ENDS ------------

        //------------ ADMIN PAYMENT SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:payment_settings'], function () {

            // Payment Informations

            Route::get('/payment-informations', 'Admin\GeneralSettingController@paymentsinfo')->name('admin-gs-payments');

            // Payment Gateways

            Route::get('/paymentgateway/datatables', 'Admin\PaymentGatewayController@datatables')
                ->name('admin-payment-datatables'); //JSON REQUEST
            Route::get('/paymentgateway', 'Admin\PaymentGatewayController@index')->name('admin-payment-index');
            Route::get('/paymentgateway/create', 'Admin\PaymentGatewayController@create')->name('admin-payment-create');
            Route::post('/paymentgateway/create', 'Admin\PaymentGatewayController@store')->name('admin-payment-store');
            Route::get('/paymentgateway/edit/{id}', 'Admin\PaymentGatewayController@edit')->name('admin-payment-edit');
            Route::post('/paymentgateway/update/{id}', 'Admin\PaymentGatewayController@update')
                ->name('admin-payment-update');
            Route::delete('/paymentgateway/delete/{id}', 'Admin\PaymentGatewayController@destroy')
                ->name('admin-payment-delete');
            Route::get('/paymentgateway/status/{field}/{id1}/{id2}', 'Admin\PaymentGatewayController@status')
                ->name('admin-payment-status');

            // Currency Settings

            // MULTIPLE CURRENCY

            Route::get('/currency/datatables', 'Admin\CurrencyController@datatables')
                ->name('admin-currency-datatables'); //JSON REQUEST
            Route::get('/currency', 'Admin\CurrencyController@index')->name('admin-currency-index');
            Route::get('/currency/create', 'Admin\CurrencyController@create')->name('admin-currency-create');
            Route::post('/currency/create', 'Admin\CurrencyController@store')->name('admin-currency-store');
            Route::get('/currency/edit/{id}', 'Admin\CurrencyController@edit')->name('admin-currency-edit');
            Route::post('/currency/update/{id}', 'Admin\CurrencyController@update')->name('admin-currency-update');
            Route::delete('/currency/delete/{id}', 'Admin\CurrencyController@destroy')->name('admin-currency-delete');
            Route::get('/currency/status/{id1}/{id2}', 'Admin\CurrencyController@status')->name('admin-currency-status');

            // -------------------- Reward Section Route ---------------------//
            Route::get('rewards/datatables', 'Admin\RewardController@datatables')->name('admin-reward-datatables');
            Route::get('rewards', 'Admin\RewardController@index')->name('admin-reward-index');
            Route::get('/general-settings/reward/{status}', 'Admin\GeneralSettingController@isreward')
                ->name('admin-gs-is_reward');
            Route::post('reward/update/', 'Admin\RewardController@update')->name('admin-reward-update');
            Route::post('reward/information/update', 'Admin\RewardController@infoUpdate')->name('admin-reward-info-update');

            // -------------------- Reward Section Route ---------------------//

        });

        //------------ ADMIN PAYMENT SETTINGS SECTION ENDS------------

        //------------ ADMIN SOCIAL SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:social_settings'], function () {

            //------------ ADMIN SOCIAL LINK ------------

            Route::get('/social-link/datatables', 'Admin\SocialLinkController@datatables')
                ->name('admin-sociallink-datatables'); //JSON REQUEST
            Route::get('/social-link', 'Admin\SocialLinkController@index')->name('admin-sociallink-index');
            Route::get('/social-link/create', 'Admin\SocialLinkController@create')->name('admin-sociallink-create');
            Route::post('/social-link/create', 'Admin\SocialLinkController@store')->name('admin-sociallink-store');
            Route::get('/social-link/edit/{id}', 'Admin\SocialLinkController@edit')->name('admin-sociallink-edit');
            Route::post('/social-link/edit/{id}', 'Admin\SocialLinkController@update')
                ->name('admin-sociallink-update');
            Route::delete('/social-link/delete/{id}', 'Admin\SocialLinkController@destroy')
                ->name('admin-sociallink-delete');
            Route::get('/social-link/status/{id1}/{id2}', 'Admin\SocialLinkController@status')
                ->name('admin-sociallink-status');

            //------------ ADMIN SOCIAL LINK ENDS ------------
            Route::get('/social', 'Admin\SocialSettingController@index')->name('admin-social-index');
            Route::post('/social/update', 'Admin\SocialSettingController@socialupdate')->name('admin-social-update');
            Route::post('/social/update/all', 'Admin\SocialSettingController@socialupdateall')
                ->name('admin-social-update-all');
            Route::get('/social/facebook', 'Admin\SocialSettingController@facebook')->name('admin-social-facebook');
            Route::get('/social/google', 'Admin\SocialSettingController@google')->name('admin-social-google');
            Route::get('/social/facebook/{status}', 'Admin\SocialSettingController@facebookup')
                ->name('admin-social-facebookup');
            Route::get('/social/google/{status}', 'Admin\SocialSettingController@googleup')->name('admin-social-googleup');
        });
        //------------ ADMIN SOCIAL SETTINGS SECTION ENDS------------

        //------------ ADMIN LANGUAGE SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:language_settings'], function () {

            //  Multiple Language Section

            //  Multiple Language Section Ends

            Route::get('/languages/datatables', 'Admin\LanguageController@datatables')
                ->name('admin-lang-datatables'); //JSON REQUEST
            Route::get('/languages', 'Admin\LanguageController@index')->name('admin-lang-index');
            Route::get('/languages/create', 'Admin\LanguageController@create')->name('admin-lang-create');
            Route::get('/languages/import', 'Admin\LanguageController@import')->name('admin-lang-import');
            Route::get('/languages/edit/{id}', 'Admin\LanguageController@edit')->name('admin-lang-edit');
            Route::get('/languages/export/{id}', 'Admin\LanguageController@export')->name('admin-lang-export');
            Route::post('/languages/create', 'Admin\LanguageController@store')->name('admin-lang-store');
            Route::post('/languages/import/create', 'Admin\LanguageController@importStore')
                ->name('admin-lang-import-store');
            Route::post('/languages/edit/{id}', 'Admin\LanguageController@update')->name('admin-lang-update');
            Route::get('/languages/status/{id1}/{id2}', 'Admin\LanguageController@status')->name('admin-lang-st');
            Route::delete('/languages/delete/{id}', 'Admin\LanguageController@destroy')->name('admin-lang-delete');

            //------------ ADMIN PANEL LANGUAGE SETTINGS SECTION ------------

            Route::get('/adminlanguages/datatables', 'Admin\AdminLanguageController@datatables')
                ->name('admin-tlang-datatables'); //JSON REQUEST
            Route::get('/adminlanguages', 'Admin\AdminLanguageController@index')->name('admin-tlang-index');
            Route::get('/adminlanguages/create', 'Admin\AdminLanguageController@create')->name('admin-tlang-create');
            Route::get('/adminlanguages/edit/{id}', 'Admin\AdminLanguageController@edit')->name('admin-tlang-edit');
            Route::post('/adminlanguages/create', 'Admin\AdminLanguageController@store')->name('admin-tlang-store');
            Route::post('/adminlanguages/edit/{id}', 'Admin\AdminLanguageController@update')
                ->name('admin-tlang-update');
            Route::get('/adminlanguages/status/{id1}/{id2}', 'Admin\AdminLanguageController@status')
                ->name('admin-tlang-st');
            Route::delete('/adminlanguages/delete/{id}', 'Admin\AdminLanguageController@destroy')
                ->name('admin-tlang-delete');

            //------------ ADMIN PANEL LANGUAGE SETTINGS SECTION ENDS ------------

            //------------ ADMIN LANGUAGE SETTINGS SECTION ENDS ------------

        });

        //------------ADMIN FONT SECTION------------------
        Route::get('/fonts/datatables', 'Admin\FontController@datatables')->name('admin.fonts.datatables');
        Route::get('/fonts', 'Admin\FontController@index')->name('admin.fonts.index');
        Route::get('/fonts/create', 'Admin\FontController@create')->name('admin.fonts.create');
        Route::post('/fonts/create', 'Admin\FontController@store')->name('admin.fonts.store');
        Route::get('/fonts/edit/{id}', 'Admin\FontController@edit')->name('admin.fonts.edit');
        Route::post('/fonts/edit/{id}', 'Admin\FontController@update')->name('admin.fonts.update');
        Route::delete('/fonts/delete/{id}', 'Admin\FontController@destroy')->name('admin.fonts.delete');
        Route::get('/fonts/status/{id}', 'Admin\FontController@status')->name('admin.fonts.status');
        //------------ADMIN FONT SECTION------------------

        //------------ ADMIN SEOTOOL SETTINGS SECTION ------------

        Route::group(['middleware' => 'permissions:seo_tools'], function () {

            Route::get('/seotools/analytics', 'Admin\SeoToolController@analytics')->name('admin-seotool-analytics');
            Route::post('/seotools/analytics/update', 'Admin\SeoToolController@analyticsupdate')
                ->name('admin-seotool-analytics-update');
            Route::get('/seotools/keywords', 'Admin\SeoToolController@keywords')
                ->name('admin-seotool-keywords');
            Route::post('/seotools/keywords/update', 'Admin\SeoToolController@keywordsupdate')
                ->name('admin-seotool-keywords-update');
            Route::get('/products/popular/{id}', 'Admin\SeoToolController@popular')->name('admin-prod-popular');
        });

        //------------ ADMIN SEOTOOL SETTINGS SECTION ------------

        //------------ ADMIN STAFF SECTION ------------

        Route::group(['middleware' => 'permissions:manage_staffs'], function () {

            Route::get('/staff/datatables', 'Admin\StaffController@datatables')->name('admin-staff-datatables');
            Route::get('/staff', 'Admin\StaffController@index')->name('admin-staff-index');
            Route::get('/staff/create', 'Admin\StaffController@create')->name('admin-staff-create');
            Route::post('/staff/create', 'Admin\StaffController@store')->name('admin-staff-store');
            Route::get('/staff/edit/{id}', 'Admin\StaffController@edit')->name('admin-staff-edit');
            Route::post('/staff/update/{id}', 'Admin\StaffController@update')->name('admin-staff-update');
            Route::get('/staff/show/{id}', 'Admin\StaffController@show')->name('admin-staff-show');
            Route::delete('/staff/delete/{id}', 'Admin\StaffController@destroy')->name('admin-staff-delete');
        });

        //------------ ADMIN STAFF SECTION ENDS------------

        //------------ ADMIN SUBSCRIBERS SECTION ------------

        Route::group(['middleware' => 'permissions:subscribers'], function () {

            Route::get('/subscribers/datatables', 'Admin\SubscriberController@datatables')
                ->name('admin-subs-datatables'); //JSON REQUEST
            Route::get('/subscribers', 'Admin\SubscriberController@index')->name('admin-subs-index');
            Route::get('/subscribers/download', 'Admin\SubscriberController@download')->name('admin-subs-download');
        });

        //------------ ADMIN SUBSCRIBERS ENDS ------------

        // ------------ GLOBAL ----------------------
        Route::post('/general-settings/update/all', 'Admin\GeneralSettingController@generalupdate')
            ->name('admin-gs-update');
        Route::post('/general-settings/update/payment', 'Admin\GeneralSettingController@generalupdatepayment')
            ->name('admin-gs-update-payment');
        Route::post('/general-settings/update/mail', 'Admin\GeneralSettingController@generalMailUpdate')
            ->name('admin-gs-update-mail');
        Route::get('/general-settings/status/{field}/{status}', 'Admin\GeneralSettingController@status')
            ->name('admin-gs-status');

        // STATUS SECTION
        Route::get('/products/status/{id1}/{id2?}', 'Admin\ProductController@status')->name('admin-prod-status');
        // STATUS SECTION ENDS

        // FEATURE SECTION
        Route::get('/products/feature/{id}', 'Admin\ProductController@feature')->name('admin-prod-feature');
        Route::post('/products/feature/{id}', 'Admin\ProductController@featuresubmit')->name('admin-prod-feature');
        // FEATURE SECTION ENDS

        // GALLERY SECTION ------------

        Route::get('/gallery/show', 'Admin\GalleryController@show')->name('admin-gallery-show');
        Route::post('/gallery/store', 'Admin\GalleryController@store')->name('admin-gallery-store');
        Route::get('/gallery/delete', 'Admin\GalleryController@destroy')->name('admin-gallery-delete');

        // GALLERY SECTION ENDS------------

        Route::post('/page-settings/update/all', 'Admin\PageSettingController@update')->name('admin-ps-update');
        Route::post('/page-settings/update/home', 'Admin\PageSettingController@homeupdate')->name('admin-ps-homeupdate');
        Route::post('/page-settings/menu-update', 'Admin\PageSettingController@menuupdate')->name('admin-ps-menuupdate');

        // ------------ GLOBAL ENDS ----------------------

        Route::group(['middleware' => 'permissions:super'], function () {

            Route::get('/cache/clear', function () {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                return redirect()->route('admin.dashboard')->with('cache', 'System Cache Has Been Removed.');
            })->name('admin-cache-clear');

            Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
            Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
            Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
            Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
            Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');

            // ------------ ROLE SECTION ----------------------

            Route::get('/role/datatables', 'Admin\RoleController@datatables')->name('admin-role-datatables');
            Route::get('/role', 'Admin\RoleController@index')->name('admin-role-index');
            Route::get('/role/create', 'Admin\RoleController@create')->name('admin-role-create');
            Route::post('/role/create', 'Admin\RoleController@store')->name('admin-role-store');
            Route::get('/role/edit/{id}', 'Admin\RoleController@edit')->name('admin-role-edit');
            Route::post('/role/edit/{id}', 'Admin\RoleController@update')->name('admin-role-update');
            Route::delete('/role/delete/{id}', 'Admin\RoleController@destroy')->name('admin-role-delete');

            // ------------ ROLE SECTION ENDS ----------------------

            // ------------ ADDON SECTION ----------------------

            Route::get('/addon/datatables', 'Admin\AddonController@datatables')->name('admin-addon-datatables');
            Route::get('/addon', 'Admin\AddonController@index')->name('admin-addon-index');
            Route::get('/addon/create', 'Admin\AddonController@create')->name('admin-addon-create');
            Route::post('/addon/install', 'Admin\AddonController@install')->name('admin-addon-install');
            Route::get('/addon/uninstall/{id}', 'Admin\AddonController@uninstall')->name('admin-addon-uninstall');

            // ------------ ADDON SECTION ENDS ----------------------

            Route::resource('requested-quotes', Admin\RequestedQuotesController::class);

            Route::resource('reported-problems', Admin\ReportedProblemsController::class);

            Route::resource('ttf', Admin\TtfController::class)
                ->except(['destroy']);
            Route::get('ttf/destroy/{id}', 'Admin\TtfController@destroy')->name('ttf.destroy');

            Route::resource('colors', Admin\ColorController::class)
                ->except(['destroy']);
            Route::get('colors/destroy/{id}', 'Admin\ColorController@destroy')->name('colors.destroy');
        });

        Route::get('/check/movescript', 'Admin\DashboardController@movescript')->name('admin-move-script');
        Route::get('/generate/backup', 'Admin\DashboardController@generate_bkup')->name('admin-generate-backup');
        Route::get('/activation', 'Admin\DashboardController@activation')->name('admin-activation-form');
        Route::post('/activation', 'Admin\DashboardController@activation_submit')->name('admin-activate-purchase');
        Route::get('/clear/backup', 'Admin\DashboardController@clear_bkup')->name('admin-clear-backup');
    });
