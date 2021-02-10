<?php
Route::get('/', function() {
    return redirect(route('admin.dashboard'));
});

Route::get('home', function() {
    return redirect(route('admin.dashboard'));
});

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
    Route::get('dashboard', 'DashboardController')->name('dashboard');
    Route::get('transaction', 'TransactionController@index')->name('transaction');
    Route::get('transaction-list', 'TransactionController@transactionList')->name('transaction-list');
    Route::get('transaction-store', 'TransactionController@transactionStore')->name('transaction-Store');
    Route::get('transaction-details', 'TransactionController@transactionDetails')->name('transaction-Details');
    Route::get('transaction-delete', 'TransactionController@transactionDelete')->name('transaction-Delete');
    Route::get('transaction-update', 'TransactionController@transactionUpdate')->name('transaction-Update');

    Route::get('users/roles', 'UserController@roles')->name('users.roles');
    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
});

Route::middleware('auth')->get('logout', function() {
    Auth::logout();
    return redirect(route('login'))->withInfo('You have successfully logged out!');
})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Get authenticated user
Route::get('users/auth', function() {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});
