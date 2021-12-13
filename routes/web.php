<?php

use TheSeer\Tokenizer\Exception;

/*
|-----------------------f---------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::post('/agent/bring-friend', 'AgentController@bringFriend')->name('agent.refer');
Route::get('/profile', 'AgentController@profile')->name('profile');

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', 'HomeController@home')->name('home');
    Route::get('/agent/dashboard/', '
    @agentDashboard')->name('agent.dashboard');
    Route::get('/agent/report', 'AgentController@report')->name('agent.report');
});

Route::group(['middleware' => ['can:accessProfile']], function() {

    Route::get('/listings', 'ListingController@index')->name('listings');
    Route::resource('orders', 'OrderController')->only([
        'index', 'edit', 'create', 'store', 'update', 'destroy'
    ]);

//    Route::prefix('user')->group(function () {
//        Route::get('settings', 'AgentController@settings')->name('user.settings');
//        Route::patch('update-settings', 'AgentController@updateSettings')->name('user.update-settings');
//    });

    Route::post('/start-contract', 'MailController@startContract');
    Route::post('/reserve-conference-room', 'MailController@reserveConferenceRoom');
    Route::post('/request-social-post', 'MailController@requestSocialMediaPost');
    Route::post('/request-tour', 'MailController@requestTour');
    Route::post('/order-client-gift', 'MailController@orderClientGift');
    Route::post('/buyer-rep-sign', 'MailController@buyerRepSign');
    Route::post('/vendor-list', 'MailController@vendorList');

});


//prevent registration, only login possible
Route::match(['get', 'post'], 'register', function(){
    return redirect('404');
});


//php artisan optimize:clear
//php artisan migrate
Route::get('artisan-clear-all', function () {
        /* php artisan storage:link */
        \Artisan::call('optimize:clear');
});

Route::group(['middleware' => ['can:accessAdminpanel']], function() {

    Route::prefix('admin')->group(function () {
        Route::get('/', function () {
            return redirect('admin/dashboard');
        });
        Route::resource('agents', 'AgentController')->only([
            'index', 'edit', 'create', 'store', 'update', 'destroy'
        ]);
        Route::resource('transactions', 'TransactionController')->only([
            'index', 'edit', 'create', 'store', 'update', 'destroy'
        ]);
        Route::resource('assistants', 'AssistantController')->only([
            'index', 'edit', 'create', 'store', 'update', 'destroy'
        ]);

        Route::resource('emails', 'EmailController')->only([
            'index', 'edit', 'update'
        ]);

        Route::resource('dashboard', 'DashboardController')->only([
            'index', 'edit', 'create', 'store', 'update', 'destroy'
        ]);
        Route::get('pipeline', 'PipelineController@index');
        Route::get('dashboard/requests', 'DashboardController@requests');
    });

    Route::prefix('orders')->group(function () {
        Route::post('updateRequest', 'OrderController@updateRequest');
        Route::post('updateRequestStatus', 'OrderController@updateRequestStatus');
        Route::post('isChecked', 'OrderController@isChecked');
    });

    Route::post('orders_request/store', 'OrderRequestController@store')->name('orders_request.store');

    //create simlink
    Route::get('create-simlink', function () {
        /* php artisan storage:link */
        $path = public_path().'/storage';
        File::isDirectory($path) or \Artisan::call('storage:link');
        dd("Done");
    });

    Route::get('artisan-cache', function () {
        /* php artisan storage:link */
        \Artisan::call('config:cache');
        dd("Done");
    });

    //php artisan migrate
//    Route::get('artisan-migrate', function () {
//        /* php artisan storage:link */
//        \Artisan::call('migrate');
//        dd("Done");
//    });

    //php artisan migrate:refresh --seed
//    Route::get('artisan-migrate-refresh-seed', function () {
//        /* php artisan storage:link */
//        \Artisan::call('migrate:refresh');
//        // Fill tables with seeds
//        \Artisan::call('db:seed');
//        dd("Done");
//    });

    Route::get('dump-autoload', function () {
        /* php artisan storage:link */
        \Artisan::call('migrate:refresh');
        // Fill tables with seeds
        \Artisan::call('db:seed');
        dd("Done");
    });

});

Route::get('/agent/{hash}', 'AgentController@show')->name('agent.show');

Route::get('mailable', function () {
    $invoice = App\Invoice::find(1);

    return new App\Mail\AgentCommon($invoice);
});

Route::get('/send/test_email', 'TestEmailController@send');