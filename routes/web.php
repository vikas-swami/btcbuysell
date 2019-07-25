<?php


//Payment IPN
Route::post('/ipncoinpaybtc', 'PaymentController@ipnCoinPayBtc')->name('ipn.coinPay.btc');
Route::post('/ipncoinpayeth', 'PaymentController@ipnCoinPayEth')->name('ipn.coinPay.eth');
// Route::post('/ipncoinpaydoge', 'PaymentController@ipnCoinPayDoge')->name('ipn.coinPay.doge');
// Route::post('/ipncoinpayltc', 'PaymentController@ipnCoinPayLtc')->name('ipn.coinPay.ltc');

Route::get('/', 'FrontendController@index')->name('homepage');
Route::get('menu/{slug}', 'FrontendController@menu')->name('menu.view');
Route::get('/contact', 'FrontendController@contactUs')->name('contact.index');

Route::get('/sell/btc', 'FrontendController@sell_btc')->name('sell.bitcoin.view');
Route::get('/sell/eth', 'FrontendController@sell_eth')->name('sell.eth.view');
// Route::get('/sell/doge', 'FrontendController@sell_doge')->name('sell.doge.view');
// Route::get('/sell/lite', 'FrontendController@sell_lite')->name('sell.lite.view');

Route::get('/buy/btc', 'FrontendController@buy_btc')->name('buy.bitcoin.view');
Route::get('/buy/eth', 'FrontendController@buy_eth')->name('buy.eth.view');
// Route::get('/buy/doge', 'FrontendController@buy_doge')->name('buy.doge.view');
// Route::get('/buy/lite', 'FrontendController@buy_lite')->name('buy.lite.view');

Route::get('/search', 'FrontendController@searchRe')->name('quick.search');
Route::get('/profile/{username}', 'FrontendController@profileView')->name('user.profile.view');
Route::get('/terms', 'FrontendController@termsView')->name('terms.index');
Route::get('/policy', 'FrontendController@policyView')->name('policy.index');


Route::get('/ad/{id}/{payment}', 'FrontendController@viewSlug')->name('view');

Route::post('/contact-us', ['uses' => 'FrontendController@contactSubmit', 'as' => 'contact-submit']);


Auth::routes();

    Route::get('authorization', 'FrontendController@authCheck')->name('user.authorization');
    Route::post('/sendemailver', 'FrontendController@sendemailver')->name('sendemailver');
    Route::post('/emailverify', 'FrontendController@emailverify')->name('emailverify');
    Route::post('/sendsmsver', 'FrontendController@sendsmsver')->name('sendsmsver');
    Route::post('/smsverify', 'FrontendController@smsverify')->name('smsverify');
    Route::post('/g2fa-verify', 'FrontendController@verify2fa')->name('go2fa.verify');

Route::group(['prefix' => 'user'], function () {

    Route::get('/advertise/coin', 'HomeController@sell_coin')->name('sell.coin');
    Route::get('/advertise/history', 'HomeController@sell_buy_history')->name('sell.buy.history');

    Route::post('verification', 'HomeController@sendVcode')->name('user.send-vcode');
    Route::post('smsVerify', 'HomeController@smsVerify')->name('user.sms-verify');

    Route::post('verify-email', 'HomeController@sendEmailVcode')->name('user.send-emailVcode');
    Route::post('postEmailVerify', 'HomeController@postEmailVerify')->name('user.email-verify');


    Route::middleware(['CheckStatus', 'auth'])->group(function () {

        Route::get('/home', 'HomeController@index')->name('home');

        Route::get('/deposit', 'HomeController@deposit')->name('deposit');
        Route::post('/deposit-confirm/', 'PaymentController@depositConfirm')->name('deposit.confirm');

        Route::post('/advertise/coin', 'HomeController@currenyGet')->name('currency.check');
        Route::post('/advertise/create', 'HomeController@addStore')->name('sell.buy');
        Route::get('/advertise/edit/{id}', 'HomeController@addEdit')->name('sell_buy.edit');
        Route::put('/advertise/update/{id}', 'HomeController@addUpdate')->name('sell.buy.update');

        Route::post('/contact/deal/{id}', 'HomeController@storeDealBuy')->name('store.deal');
        Route::post('/send/message', 'HomeController@dealSendMessage')->name('send.message.deal');
        Route::post('/send/message/reply', 'HomeController@dealSendMessageReply')->name('send.message.deal.reply');
        Route::get('deal/{id}', 'HomeController@dealView');
        Route::get('deal-reply/{id}', 'HomeController@notiReply')->name('noti.message');

        Route::post('confirm/paid', 'HomeController@confirmPaid')->name('confirm.paid');
        Route::post('confirm/cancel', 'HomeController@confirmCencel')->name('confirm.cancel');

        Route::get('open/trade', 'HomeController@openTrade')->name('open.trade');
        Route::get('close/trade', 'HomeController@closeTrade')->name('close.trade');
        Route::get('complete/trade', 'HomeController@completeTrade')->name('complete.trade');
        Route::get('cancel/trade', 'HomeController@cancelTrade')->name('cancel.trade');

        Route::post('cancel/trade/reverse', 'HomeController@cancelTradeReverce')->name('confirm.cancel.reverse');
        Route::post('paid/trade/reverse', 'HomeController@paidTradeReverce')->name('confirm.paid.reverse');

        Route::get('deposits', 'HomeController@depHistory')->name('deposit.history');
        Route::get('transactions', 'HomeController@transHistory')->name('trans.history');

        Route::get('change-password', 'HomeController@changePassword')->name('user.change-password');
        Route::put('change-password', 'HomeController@submitPassword')->name('user.change-password');

        Route::get('edit-profile', 'HomeController@editProfile')->name('edit-profile');
        Route::put('edit-profile', 'HomeController@submitProfile')->name('edit-profile');

        Route::post('/store/ticket', 'TicketController@ticketStore')->name('ticket.store');
        Route::get('/comment/close/{ticket}', 'TicketController@ticketClose')->name('ticket.close');
        Route::get('/support/reply/{ticket}', 'TicketController@ticketReply')->name('ticket.customer.reply');
        Route::post('/support/store/{ticket}', 'TicketController@ticketReplyStore')->name('store.customer.reply');

        Route::get('/support', 'TicketController@ticketIndex')->name('support.index.customer');
        Route::get('/support/new', 'TicketController@ticketCreate')->name('add.new.ticket');
        
        Route::get('/security/two/step', 'HomeController@twoFactorIndex')->name('two.factor.index');
        Route::post('/g2fa-create', 'HomeController@create2fa')->name('go2fa.create');
        Route::post('/g2fa-disable', 'HomeController@disable2fa')->name('disable.2fa');
    });
});


Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'AdminLoginController@index')->name('admin.loginForm');
    Route::post('/', 'AdminLoginController@authenticate')->name('admin.login');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {


        Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');

        Route::get('/transactions', 'DepositController@transLog')->name('trans.log');
        Route::get('/deals', 'DepositController@dealLog')->name('deal.log');
        Route::get('/deals/search', 'DepositController@dealSearch')->name('trans.search');
        Route::get('/deals/{trans_id}', 'DepositController@dealView')->name('deal.view.admin');

        Route::get('/terms/policy', 'GeneralSettingController@viewTerms')->name('terms.policy');
        Route::post('/terms/policy', 'GeneralSettingController@updateTerms')->name('terms.policy.update');

        Route::get('/supports', 'TicketController@indexSupport')->name('support.admin.index');
        Route::get('/support/reply/{ticket}', 'TicketController@adminSupport')->name('ticket.admin.reply');
        Route::post('/reply/{ticket}', 'TicketController@adminReply')->name('store.admin.reply');
        Route::get('/pending/ticket', 'TicketController@pendingTicketAdmin')->name('pending.support.ticket');


        //Gateway
        Route::get('/gateway', 'GatewayController@show')->name('gateway');
        Route::post('/gateway', 'GatewayController@update')->name('update.gateway');

        //Deposit
        Route::get('/deposits', 'DepositController@index')->name('deposits');


        //Email Template
        Route::get('/template', 'EtemplateController@index')->name('email.template');
        Route::post('/template-update', 'EtemplateController@update')->name('template.update');
        //Sms Api
        Route::get('/sms-api', 'EtemplateController@smsApi')->name('sms.api');
        Route::post('/sms-update', 'EtemplateController@smsUpdate')->name('sms.update');


        // General Settings
        Route::get('/general-settings', 'GeneralSettingController@GenSetting')->name('admin.GenSetting');
        Route::post('/general-settings', 'GeneralSettingController@UpdateGenSetting')->name('admin.UpdateGenSetting');
        Route::get('/change-password', 'GeneralSettingController@changePassword')->name('admin.changePass');
        Route::post('/change-password', 'GeneralSettingController@updatePassword')->name('admin.changePass');
        Route::get('/profile', 'GeneralSettingController@profile')->name('admin.profile');
        Route::post('/profile', 'GeneralSettingController@updateProfile')->name('admin.profile');


        //User Management
        Route::get('users',  'GeneralSettingController@users')->name('users');
        Route::post('user-search','GeneralSettingController@userSearch')->name('search.users');
        Route::get('user/{user}', 'GeneralSettingController@singleUser')->name('user.single');
        Route::put('user/pass-change/{user}', 'GeneralSettingController@userPasschange')->name('user.passchange');
        Route::put('user/status/{user}', 'GeneralSettingController@statupdate')->name('user.status');
        Route::get('mail/{user}', 'GeneralSettingController@userEmail')->name('user.email');
        Route::post('/sendmail', 'GeneralSettingController@sendemail')->name('send.email');
        Route::get('/user-login-history/{id}', 'GeneralSettingController@loginLogsByUsers')->name('user.login.history');
        Route::get('/user-balance/{id}', 'GeneralSettingController@ManageBalanceByUsers')->name('user.balance');
        Route::post('/user-balance', 'GeneralSettingController@saveBalanceByUsers')->name('user.balance.update');
        Route::get('/user-banned', 'GeneralSettingController@banusers')->name('user.ban');
        Route::get('login-logs/{user?}', 'GeneralSettingController@loginLogs')->name('user.login-logs');

        Route::get('/user-transaction/{id}',  'GeneralSettingController@userTrans')->name('user.trans');
        Route::get('/user-deposit/{id}', 'GeneralSettingController@userDeposit')->name('user.deposit');

        Route::get('active/user', 'GeneralSettingController@activeUser')->name('users.active');
        Route::get('email/unverified/user', 'GeneralSettingController@emailVerfiedUser')->name('users.email.verified');
        Route::get('phone/unverified/user', 'GeneralSettingController@phoneVerfiedUser')->name('users.phone.verified');


        //Contact Setting
        Route::get('contact-setting', 'WebSettingController@getContact')->name('contact-setting');
        Route::put('contact-setting/{id}', 'WebSettingController@putContactSetting')->name('contact-setting-update');

        Route::get('manage-logo', 'WebSettingController@manageLogo')->name('manage-logo ');
        Route::post('manage-logo', 'WebSettingController@updateLogo')->name('manage-logo');

        Route::get('manage-footer', 'WebSettingController@manageFooter')->name('manage-footer');
        Route::put('manage-footer',  'WebSettingController@updateFooter')->name('manage-footer-update');


        Route::get('manage-social', 'WebSettingController@manageSocial')->name('manage-social');
        Route::post('manage-social', 'WebSettingController@storeSocial')->name('manage-social');
        Route::get('manage-social/{product_id?}', 'WebSettingController@editSocial')->name('social-edit');
        Route::put('manage-social/{product_id?}', 'WebSettingController@updateSocial')->name('social-edit');
        Route::delete('manage-social/{product_id?}', 'WebSettingController@deleteSocial')->name('social-delete');

        Route::get('menu-create', 'WebSettingController@createMenu')->name('menu-create');
        Route::post('menu-create',  'WebSettingController@storeMenu')->name('menu-create');
        Route::get('menu-control', 'WebSettingController@manageMenu')->name('menu-control');
        Route::get('menu-edit/{id}', 'WebSettingController@editMenu')->name('menu-edit');
        Route::post('menu-update/{id}',  'WebSettingController@updateMenu')->name('menu-update');
        Route::delete('menu-delete',  'WebSettingController@deleteMenu')->name('menu-delete');

        Route::get('slider-index','WebSettingController@manageSlider')->name('slider-index');
        Route::delete('slider-delete',  'WebSettingController@deleteSlider')->name('slider-delete');
        Route::put('slider-store', 'WebSettingController@storeSlider')->name('slider-store');

        Route::resource('crypto', 'CryptoController');

        Route::resource('currency', 'CurrencyController');


    Route::get('/logout', 'AdminController@logout')->name('admin.logout');
});


/*============== User Password Reset Route list ===========================*/
Route::get('user-password/reset', 'User\ForgotPasswordController@showLinkRequestForm')->name('user.password.request');
Route::post('user-password/email', 'User\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::get('reset/{token}', 'User\ResetPasswordController@showResetForm')->name('user.password.reset');
Route::post('/reset', 'User\ResetPasswordController@reset')->name('reset.passw');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
