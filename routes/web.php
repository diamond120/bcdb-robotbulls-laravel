<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middle-ware group. Now create something great!
|
*/

if(application_installed()){
    Route::get('/install/final', function(){
        return redirect('/');
    });
}

// Handle Main / Route
Route::get('/', 'Auth\LoginController@checkLoginState')->name('home');
Route::get('/locale', 'PublicController@set_lang')->name('language');

Route::post('/whatsapp/send', 'whatsappController@sendMessage')->name('whatsapp.send');
Route::post('/webhooks/whatsapp', 'whatsappController@receiveMessage')->name('webhooks.whatsapp');
Route::post('/webhooks/whatsapp-status', 'whatsappController@handleStatus')->name('webhooks.whatsappStatus');

Route::post('/sms/send', 'smsController@sendMessage')->name('sms.send');
Route::post('/webhooks/sms', 'smsController@receiveMessage')->name('webhooks.sms');
Route::post('/webhooks/sms-status', 'smsController@handleStatus')->name('webhooks.smsStatus');

//Route::post('/call/make', 'callController@make')->name('call.make');
//Route::post('/call/receive', 'callController@receive')->name('call.receive');

Route::get('/twilio/token', 'callController@getToken');
Route::post('/twilio/call', 'callController@makeCall');
Route::post('/twilio/voice', 'callController@voice')->name('twilio.voice');



// Authenticates Routes
Route::get('/auth/{service}', 'Auth\SocialAuthController@redirect')->name('social.login');
Route::get('/auth/{service}/callback', 'Auth\SocialAuthController@callback')->name('social.login.callback');
Route::post( '/auth/social/register', 'Auth\SocialAuthController@register' )->name('social.register');

// Authenticates Routes
Auth::routes();
Route::get('/3d-auth', 'PublicController@threeDAuth')->name('3d-auth');
Route::get('verify/', 'Auth\VerifyController@index')->name('verify');
Route::get('verify/resend', 'Auth\VerifyController@resend')->name('verify.resend');
Route::get('verify/{id}/{token}', 'Auth\VerifyController@verify')->name('verify.email');
Route::get('verify/success', 'Auth\LoginController@verified')->name('verified');
Route::get('register/success', 'Auth\LoginController@registered')->name('registered');
Route::any('log-out', 'Auth\LoginController@logout')->name('log-out');
Route::any('register/verification', 'Auth\RegisterController@verification')->name('verification');
// Google 2FA Routes 
Route::get('/login/2fa', 'Auth\SocialAuthController@show_2fa_form')->middleware('auth')->name('auth.2fa');
Route::get('/login/2fa/reset', 'Auth\SocialAuthController@show_2fa_reset_form')->name('auth.2fa.reset');
Route::post('/login/2fa/reset', 'Auth\SocialAuthController@reset_2fa');
Route::post('/login/2fa', function(){
    return redirect()->route('home');
})->middleware(['auth', 'g2fa']);

// if(is_maintenance()){
Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/login_email', 'Auth\LoginController@login_email');
Route::post('admin/logout', 'Auth\LoginController@logout')->name('admin.logout');
Route::get('admin/login/2fa', 'Auth\SocialAuthController@show_2fa_form')->middleware('auth')->name('admin.auth.2fa');
Route::post('admin/login/2fa', function(){
    return redirect()->route('home');
})->middleware(['auth', 'g2fa']);
// }

// User Routes
Route::prefix('user')->middleware(['auth', 'user', 'verify_user', 'g2fa'])->name('user.')->group(function () {
    Route::get('/', 'User\UserController@index')->name('home');
    Route::get('/demo', 'Demo\UserController@index')->name('demo.home');
    Route::get('/test', 'User\TestUserController@index')->name('test.home');
    Route::get('/account', 'User\UserController@account')->name('account');
    Route::get('/account/activity', 'User\UserController@account_activity')->name('account.activity');
    Route::get('/invest', 'User\InvestmentController@index')->name('investment');
    Route::get('/invest/cancel/{gateway?}', 'User\InvestmentController@payment_cancel')->name('payment.cancel');
    Route::get('/demo/invest', 'Demo\InvestmentController@index')->name('demo.investment');
    Route::get('/demo/invest/cancel/{gateway?}', 'Demo\InvestmentController@payment_cancel')->name('demo.payment.cancel');
    Route::get('/test/invest', 'User\TestInvestmentController@index')->name('test.investment');
    Route::get('/test/invest/cancel/{gateway?}', 'User\TestInvestmentController@payment_cancel')->name('test.payment.cancel');
    Route::get('/wallet', 'User\WalletController@index')->name('wallet');
    Route::get('/wallet/cancel/{gateway?}', 'User\WalletController@payment_cancel')->name('payment.cancel');
    Route::get('/test/contribute', 'User\TestTokenController@index')->name('test.token');
    Route::get('/test/contribute/cancel/{gateway?}', 'User\TestTokenController@payment_cancel')->name('payment.cancel'); 
    Route::get('/contribute', 'User\TokenController@index')->name('token');
    Route::get('/contribute/cancel/{gateway?}', 'User\TokenController@payment_cancel')->name('payment.cancel');
    Route::get('/transactions', 'User\TransactionController@index')->name('transactions');
    Route::get('/demo/transactions', 'Demo\TransactionController@index')->name('demo.transactions');
    Route::get('/kyc', 'User\KycController@index')->name('kyc');
    Route::get('/kyc/application', 'User\KycController@application')->name('kyc.application');
    Route::get('/kyc/application/view', 'User\KycController@view')->name('kyc.application.view');
    Route::get('/kyc-list/documents/{file}/{doc}', 'User\KycController@get_documents')->middleware('ico')->name('kycs.file');
    Route::get('/password/confirm/{token}', 'User\UserController@password_confirm')->name('password.confirm');
    // Referral v1.0.3 > v1.1.1
    Route::get('/buy-crypto', 'User\UserController@buy_crypto')->name('buycrypto');
    // My Token v1.1.2
    Route::get('/account/balance', 'User\UserController@mytoken_balance')->name('token.balance');
    Route::get('/activate/robot', 'User\UserController@activate_robot')->name('activate.robot');
    Route::get('/disable/robot', 'User\UserController@disable_robot')->name('disable.robot');
    Route::get('/change/robot', 'User\UserController@change_robot')->name('change.robot');
    Route::get('/submit/questionnaire', 'User\UserController@submit_questionnaire')->name('submit.questionnaire');

    // User Ajax Request
    Route::name('ajax.')->prefix('ajax')->group(function () {
        Route::post('/account/whitelisting-form', 'User\UserController@whitelisting_form')->name('account.whitelisting');
        Route::post('/account/get-key-form', 'User\UserController@get_key_form')->name('account.getkey');
        Route::post('/account/messages-form', 'User\UserController@messages_form')->name('account.messages');
        Route::post('/account/wallet-form', 'User\UserController@get_wallet_form')->name('account.wallet');
        Route::post('/account/update', 'User\UserController@account_update')->name('account.update')->middleware('demo_user');
        Route::post('/account/whitelisting', 'User\UserController@account_whitelisting')->name('account.whitelisting')->middleware('demo_user');
        Route::post('/whitelisting', 'User\UserController@whitelisting')->name('whitelisting')->middleware('demo_user');
        Route::post('/invest/access', 'User\InvestmentController@access')->name('investment.access');
        Route::post('/invest/payment', 'User\InvestmentController@payment')->name('payment');
        Route::post('/demo/invest/access', 'Demo\InvestmentController@access')->name('demo.investment.access');
        Route::post('/demo/invest/payment', 'Demo\InvestmentController@payment')->name('demo.payment');
        Route::post('/wallet/access', 'User\WalletController@access')->name('wallet.access');
        Route::post('/wallet/payment', 'User\WalletController@payment')->name('payment');
        Route::post('/contribute/access', 'User\TokenController@access')->name('token.access');
        Route::post('/contribute/payment', 'User\TokenController@payment')->name('payment');

        Route::post('/transactions/delete/{id}', 'User\TransactionController@destroy')->name('transactions.delete')->middleware('demo_user');
        Route::post('/transactions/view', 'User\TransactionController@show')->name('transactions.view');
        Route::post('/kyc/submit', 'User\KycController@submit')->name('kyc.submit');
        Route::post('/account/activity', 'User\UserController@account_activity_delete')->name('account.activity.delete')->middleware('demo_user');
        
        Route::post('/support/new_message', 'User\UserController@supportMessage')->name('support.new_message');
        Route::get('/support/past_messages', 'User\UserController@fetchMessages')->name('support.past_messages');
        Route::post('/get_equity', 'User\UserController@get_user_equity')->name('get_equity');
        
//        Route::post('/activate/robot', 'User\UserController@account_activity_delete')->name('activate.robot')->middleware('demo_user');
//        Route::post('/activate/robot', 'User\UserController@activate_robot')->name('activate.robot');
    });
});

Route::prefix('admin')->middleware(['auth', 'admin', 'g2fa', 'ico'])->name('admin.')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('home');
    Route::any('/system-info', 'Admin\AdminController@system_info')->name('system');
    Route::any('/tokenlite-register', 'Admin\AdminController@treatment')->name('tokenlite');
    Route::get('/profile', 'Admin\AdminController@profile')->middleware('ico')->name('profile');
    Route::get('/profile/activity', 'Admin\AdminController@activity')->middleware('ico')->name('profile.activity');
    Route::get('/password/confirm/{token}', 'Admin\AdminController@password_confirm')->name('password.confirm');
    Route::get('/transactions/{state?}', 'Admin\TransactionController@index')->middleware('ico')->name('transactions');
    Route::get('/stages/settings', 'Admin\IcoController@settings')->middleware('ico')->name('stages.settings');
    Route::get('/pages', 'Admin\PageController@index')->middleware('ico')->name('pages');
    Route::get('/settings', 'Admin\SettingController@index')->middleware(['ico', 'super_admin'])->name('settings');
    Route::get('/settings/email', 'Admin\EmailSettingController@index')->middleware(['ico', 'super_admin'])->name('settings.email');
    Route::get('/settings/referral', 'Admin\SettingController@referral_setting')->middleware(['ico', 'super_admin'])->name('settings.referral'); // v1.1.2
    Route::get('/settings/rest-api', 'Admin\SettingController@api_setting')->middleware(['ico', 'super_admin'])->name('settings.api'); // v1.0.6
    Route::get('/payment-methods', 'Admin\PaymentMethodController@index')->middleware(['ico', 'super_admin'])->name('payments.setup');
    Route::get('/payment-methods/edit/{slug}', 'Admin\PaymentMethodController@edit')->middleware(['ico', 'super_admin'])->name('payments.setup.edit');
    Route::get('/stages', 'Admin\IcoController@index')->middleware('ico')->name('stages');
    Route::get('/stages/{id}', 'Admin\IcoController@edit_stage')->middleware('ico')->name('stages.edit');
    Route::get('/users/{role?}', 'Admin\UsersController@index')->middleware('ico')->name('users'); //v1.1.0
    
    Route::get('/activities/{role?}', 'Admin\ActivitiesController@index')->middleware('ico')->name('activities'); //v1.1.0
    Route::get('/referrals/{role?}', 'Admin\ReferralsController@index')->middleware('ico')->name('referrals'); //v1.1.0
    Route::get('/wallets', 'Admin\WalletsController@index')->middleware('ico')->name('wallets'); //v1.1.0
    Route::get('/messages', 'Admin\MessagesController@index')->middleware('ico')->name('messages'); //v1.1.0
    
    Route::get('/users/wallet/change-request', 'Admin\UsersController@wallet_change_request')->middleware('ico')->name('users.wallet.change');
    Route::get('/kyc-list/{status?}', 'Admin\KycController@index')->middleware('ico')->name('kycs'); //v1.1.0
    Route::get('/kyc-list/documents/{file}/{doc}', 'Admin\KycController@get_documents')->middleware('ico')->name('kycs.file');
    Route::get('/transactions/view/{id}', 'Admin\TransactionController@show')->name('transactions.view');
//    Route::get('/transactions/withdraw_show/{id}', 'Admin\TransactionController@withdraw_show')->name('transactions.withdraw_show');
//    Route::get('/transactions/reinvest_show/{id}', 'Admin\TransactionController@reinvest_show')->name('transactions.reinvest_show');

    Route::get('/users/{id?}/{type?}', 'Admin\UsersController@show')->name('users.view');
    Route::get('/kyc/view/{id}/{type}', 'Admin\KycController@show')->name('kyc.view');
    Route::get('/pages/{slug}', 'Admin\PageController@edit')->middleware('ico')->name('pages.edit');
    Route::get('/export/{table?}/{format?}', 'ExportController@export')->middleware(['ico', 'demo_user', 'super_admin'])->name('export'); // v1.1.0
    Route::get('/languages', 'Admin\LanguageController@index')->middleware(['ico'])->name('lang.manage'); // v1.1.3
    Route::get('/languages/translate/{code}', 'Admin\LanguageController@translator')->middleware(['ico'])->name('lang.translate');
    
    Route::get('/reset-whitelisting/{userId}', 'Admin\WalletsController@resetWhitelisting')->name('resetWhitelisting');
// v1.1.3

    Route::get('/fetch-messages/{user_id}', 'Admin\UsersController@fetchMessages');
    Route::post('/support/send', 'Admin\SupportController@sendsupportMessage')->name('support.send');

    /* Admin Ajax Route */
    Route::name('ajax.')->prefix('ajax')->middleware(['ico'])->group(function () {
        
        Route::post('/expiring-transactions-by-user', 'Admin\TransactionController@getExpiringTransactionsByUser');
        Route::post('/addbonuses', 'Admin\ReferralsController@addBonuses')->name('users.addbonuses');
        Route::post('/getbonusestransactions', 'Admin\ReferralsController@getBonusesTransactions')->name('users.getbonusestransactions');
        
        Route::post('/users/view', 'Admin\UsersController@status')->name('users.view')->middleware('demo_user');
        Route::post('/users/showinfo', 'Admin\UsersController@show')->name('users.show');
        Route::post('/users/delete/all', 'Admin\UsersController@delete_unverified_user')->name('users.delete')->middleware('demo_user');
        Route::post('/users/email/send', 'Admin\UsersController@send_email')->name('users.email')->middleware('demo_user');
        Route::post('/users/sms/send', 'Admin\UsersController@send_sms')->name('users.sms');
        Route::post('/users/whatsapp/send', 'Admin\UsersController@send_whatsapp')->name('users.whatsapp');
        Route::post('/users/referrant/add', 'Admin\UsersController@add_referrant')->name('users.referrant');
        Route::post('/users/insert', 'Admin\UsersController@store')->middleware(['super_admin', 'demo_user'])->name('users.add');
        
        Route::post('/users/name_edit', 'Admin\UsersController@name_edit')->middleware(['super_admin', 'demo_user'])->name('users.name_edit');
        Route::post('/users/email_edit', 'Admin\UsersController@email_edit')->middleware(['super_admin', 'demo_user'])->name('users.email_edit');
        Route::post('/users/base_currency_edit', 'Admin\UsersController@base_currency_edit')->middleware(['super_admin', 'demo_user'])->name('users.base_currency_edit');
        Route::post('/users/two_fa_edit', 'Admin\UsersController@two_fa_edit')->middleware(['super_admin', 'demo_user'])->name('users.two_fa_edit');
        Route::post('/users/ambassador_edit', 'Admin\UsersController@ambassador_edit')->middleware(['super_admin', 'demo_user'])->name('users.ambassador_edit');
        Route::post('/users/referral_rights_edit', 'Admin\UsersController@referral_rights_edit')->middleware(['super_admin', 'demo_user'])->name('users.referral_rights_edit');
        Route::post('/users/vip_user_edit', 'Admin\UsersController@vip_user_edit')->middleware(['super_admin', 'demo_user'])->name('users.vip_user_edit');
        Route::post('/users/whitelisting_comptete_edit', 'Admin\UsersController@whitelisting_comptete_edit')->middleware(['super_admin', 'demo_user'])->name('users.whitelisting_comptete_edit');
        Route::post('/users/whitelisting_balance_edit', 'Admin\UsersController@whitelisting_balance_edit')->middleware(['super_admin', 'demo_user'])->name('users.whitelisting_balance_edit');
            
        Route::post('/profile/update', 'Admin\AdminController@profile_update')->name('profile.update')->middleware('demo_user');
        Route::post('/profile/activity', 'Admin\AdminController@activity_delete')->name('profile.activity.delete')->middleware('demo_user');
        Route::post('/users/wallet/action', 'Admin\UsersController@wallet_change_request_action')->name('users.wallet.action');
        Route::post('/payment-methods/view', 'Admin\PaymentMethodController@show')->middleware('super_admin')->name('payments.view');
        Route::post('/payment-methods/update', 'Admin\PaymentMethodController@update')->middleware(['super_admin', 'demo_user'])->name('payments.update');
        Route::post('/payment-methods/quick-update', 'Admin\PaymentMethodController@quick_update')->middleware(['super_admin', 'demo_user'])->name('payments.qupdate');
        Route::post('/kyc/view', 'Admin\KycController@ajax_show')->name('kyc.ajax_show');
        Route::post('/stages/update', 'Admin\IcoController@update')->name('stages.update')->middleware('demo_user');
        Route::post('/stages/pause', 'Admin\IcoController@pause')->middleware('ico')->name('stages.pause')->middleware('demo_user');
        Route::post('/stages/active', 'Admin\IcoController@active')->middleware('ico')->name('stages.active')->middleware('demo_user');
        Route::post('/stages/meta/update', 'Admin\IcoController@update_options')->name('stages.meta.update')->middleware('demo_user');
        Route::post('/stages/settings/update', 'Admin\IcoController@update_settings')->name('stages.settings.update')->middleware('demo_user');
        Route::post('/stages/actions', 'Admin\IcoController@stages_action')->middleware('ico')->name('stages.actions'); //v1.1.2
        Route::post('/kyc/update', 'Admin\KycController@update')->name('kyc.update')->middleware('demo_user');
        Route::post('/transactions/update', 'Admin\TransactionController@update')->name('transactions.update')->middleware('demo_user');

        Route::post('/transactions/adjust', 'Admin\TransactionController@adjustment')->name('transactions.adjustement');
        Route::post('/settings/email/template/view', 'Admin\EmailSettingController@show_template')->middleware('super_admin')->name('settings.email.template.view');
        Route::post('/transactions/view', 'Admin\TransactionController@show')->name('transactions.view');
        
        Route::post('/transactions/insert', 'Admin\TransactionController@store')->name('transactions.add')->middleware('demo_user');
        Route::post('/transactions/reinvest', 'Admin\TransactionController@reinvest')->name('transactions.reinvest')->middleware('demo_user');
        Route::post('/transactions/reinvest2', 'Admin\TransactionController@reinvest2')->name('transactions.reinvest2')->middleware('demo_user');
        Route::post('/transactions/withdraw', 'Admin\TransactionController@withdraw')->name('transactions.withdraw')->middleware('demo_user');
        
        Route::post('/transactions/editNote', 'Admin\TransactionController@editNote')->name('transactions.editNote');
        
        Route::post('/transactions/trnx_edit_plan', 'Admin\TransactionController@trnx_edit_plan')->middleware(['super_admin', 'demo_user'])->name('transactions.trnx_edit_plan');
        Route::post('/transactions/trnx_edit_status', 'Admin\TransactionController@trnx_edit_status')->middleware(['super_admin', 'demo_user'])->name('transactions.trnx_edit_status');
        Route::post('/transactions/trnx_edit_duration', 'Admin\TransactionController@trnx_edit_duration')->middleware(['super_admin', 'demo_user'])->name('transactions.trnx_edit_duration');

        
        Route::post('/transactions/change', 'Admin\TransactionController@change')->name('transactions.change');
        Route::post('/pages/upload', 'Admin\PageController@upload_zone')->name('pages.upload')->middleware('demo_user');
        Route::post('/pages/view', 'Admin\PageController@show')->name('pages.view');
        Route::post('/pages/update', 'Admin\PageController@update')->name('pages.update')->middleware('demo_user');
        Route::post('/settings/update', 'Admin\SettingController@update')->middleware(['super_admin','demo_user'])->name('settings.update');
        // Settings UpdateMeta v1.1.0
        Route::post('/settings/meta/update', 'Admin\SettingController@update_meta')->middleware(['super_admin','demo_user'])->name('settings.meta.update'); 
        Route::post('/settings/email/update', 'Admin\EmailSettingController@update')->middleware(['super_admin', 'demo_user'])->name('settings.email.update');
        Route::post('/settings/email/template/update', 'Admin\EmailSettingController@update_template')->middleware(['super_admin', 'demo_user'])->name('settings.email.template.update');
        Route::post('/languages', 'Admin\LanguageController@language_action')->middleware(['ico', 'demo_user'])->name('lang.action'); // v1.1.3
        Route::post('/languages/translate', 'Admin\LanguageController@language_action')->middleware(['ico', 'demo_user'])->name('lang.translate.action'); // v1.1.3
    });

    //Clear Cache facade value:
    Route::get('/clear', function () {
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:clear');
        $exitCode = Artisan::call('route:clear');
        $exitCode = Artisan::call('view:clear');

        $data = ['msg' => 'success', 'message' => 'Cache Cleared and Optimized!'];

        if (request()->ajax()) {
            return response()->json($data);
        }
        return back()->with([$data['msg'] => $data['message']]);
    })->name('clear.cache');
});

Route::name('public.')->group(function () {
    Route::get('/check/updater', 'PublicController@update_check');
    Route::get('/insert/database', 'PublicController@database')->name('database');
    Route::get('/kyc-application', 'PublicController@kyc_application')->name('kyc');
    Route::get('/invite', 'PublicController@referral')->name('referral');
    Route::post('/kyc-application/file-upload', 'User\KycController@upload')->name('kyc.file.upload');
    Route::post('/kyc-application/submit', 'User\KycController@submit')->name('kyc.submit');
    Route::get('/qrgen.png', 'PublicController@qr_code')->name('qrgen');

    Route::get('white-paper', function () {
        $filename = get_setting('site_white_paper');
        $path = storage_path('app/public/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        $file = \File::get($path);
        $type = \File::mimeType($path);
        $response = response($file, 200)->header("Content-Type", $type);
        return $response;
    })->name('white.paper');

    Route::get('/{slug}', 'PublicController@site_pages')->name('pages');
});

// Ajax Routes
Route::prefix('ajax')->name('ajax.')->group(function () {
    Route::post('/kyc/file-upload', 'User\KycController@upload')->name('kyc.file.upload');
});
