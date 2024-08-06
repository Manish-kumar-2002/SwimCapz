<?php


use App\Http\Controllers\Front\AjaxController;
use App\Http\Controllers\Front\ToolController;
use App\Http\Controllers\Front\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('ajax/search-country', [AjaxController::class, 'getCountries'])->name('ajax.search.countries');
Route::get('ajax/search-states', [AjaxController::class, 'getStates'])->name('ajax.search.states');
Route::get('ajax/search-states/{id}', [AjaxController::class, 'getStatesByCountryId'])
        ->name('ajax.search.states.byCountryID');

Route::get('ajax/search-product-by-category/{id}', [AjaxController::class, 'getProductByCategoryId'])
        ->name('ajax.search.product.byCategoryID');

Route::get('ajax/search-product', [
        AjaxController::class, 'getProducts'])->name('ajax.search.products');
Route::get('ajax/search-featured', [
        AjaxController::class, 'getFeaturedOn'])->name('ajax.search.featuredon');

Route::prefix('frontend')->group(function () {
        
        Route::get('tool', [ToolController::class, 'index'])
                ->name('tool.details');

        Route::get('request-quotes', [FrontendController::class, 'requestQuotes'])
                ->name('request.quotes');
        Route::post('request-quotes', [FrontendController::class, 'storeRequestedQuotes'])
                ->name('request.quotes');
        
        /* report a problem */
        Route::get('report-problem', [FrontendController::class, 'reportProblem'])
                ->name('report.problem');
        Route::post('report-problem', [FrontendController::class, 'storereportProblem'])
                ->name('report.problem');

        Route::get('order/{id}', [FrontendController::class, 'orderprint'])
                ->name('guest-order-print');
});
  
