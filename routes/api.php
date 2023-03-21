<?php

namespace App;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/email_opp/{opp}', function (Opportunity $opp) {

//     return MailHelper::sendOpportunityCreatedMail($opp);
// });

// ------------------------------------------------------------------------------
//  Dashboard
// ------------------------------------------------------------------------------
Route::group(['prefix' => 'dashboard'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/get', 'DashboardController@get')->name('dashboard.get');
        Route::get('/demos', 'DashboardController@getDemos')->name('dashboard.getDemos');
        Route::get('/to_be_delivered', 'DashboardController@getToBeDelivered')->name('dashboard.getToBeDelivered');
    });
});


// ------------------------------------------------------------------------------
//  Opportunities
// ------------------------------------------------------------------------------
Route::group(['prefix' => 'opportunities'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/download_report', 'OpportunitiesController@downloadReport')->name('opportunity.downloadReport');
        Route::post('/', 'OpportunitiesController@create')->name('opportunity.create');
        Route::post('/upload', 'OpportunitiesController@upload')->name('opportunity.upload');
        Route::post('/notify_uploaded_files/{opp}', 'OpportunitiesController@notifyUploadedFiles')->name('opportunity.notifyUploadedFiles');
        Route::post('/publish/{opp}', 'OpportunitiesController@publish')->name('opportunity.publish');
        Route::put('manage/{opp}', 'OpportunitiesController@manage')->name('opportunity.manage');
        Route::put('/{opp}', 'OpportunitiesController@update')->name('opportunity.update');
        Route::get('/download_file/{file}', 'OpportunitiesController@download_file')->name('opportunity.download_file');
        Route::delete('/delete_file/{file}', 'OpportunitiesController@delete_file')->name('opportunity.delete_file');
        Route::get('/list', 'OpportunitiesController@list')->name('opportunity.list');
        Route::get('/{opp}', 'OpportunitiesController@get')->name('opportunity.get');
        Route::delete('/{opp}', 'OpportunitiesController@delete')->name('opportunity.delete');
    });
});

// ------------------------------------------------------------------------------
//  Travel
// ------------------------------------------------------------------------------
Route::group(['prefix' => 'travel'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/list', 'TravelController@list')->name('travel.list');
        Route::get('/{travel}', 'TravelController@get')->name('travel.get');
        Route::post('/', 'TravelController@create')->name('travel.create');
        Route::put('/{travel}', 'TravelController@update')->name('travel.update');
        Route::delete('/{travel}', 'TravelController@delete')->name('travel.delete');
    });
});

// ------------------------------------------------------------------------------
//  Tasks
// ------------------------------------------------------------------------------
Route::group(['prefix' => 'tasks'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/list', 'TasksController@list')->name('task.list');
        Route::get('/{task}', 'TasksController@get')->name('task.get');
        Route::post('/', 'TasksController@create')->name('task.create');
        Route::put('/', 'TasksController@update')->name('task.update');
        Route::get('/testEmail/{task}', 'TasksController@testEmail')->name('task.testEmail');
        Route::post('/upload', 'TasksController@upload')->name('task.upload');
        Route::delete('/{task}', 'TasksController@delete')->name('task.delete');
    });
});

Route::group(['prefix' => 'branches'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/list', 'BranchesController@list')->name('branch.list');
        Route::get('/{branch}', 'BranchesController@get')->name('branch.get');
        Route::post('/', 'BranchesController@create')->name('branch.create');
        Route::put('/{branch}', 'BranchesController@update')->name('branch.update');
        Route::delete('/{branch}', 'BranchesController@delete')->name('branch.delete');
    });
});

Route::group(['prefix' => 'products'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::post('/', 'ProductController@create')->name('product.create');
        Route::get('/list', 'ProductController@list')->name('product.list');
        Route::get('/{product}', 'ProductController@get')->name('product.get');
        Route::post('/', 'ProductController@create')->name('product.create');
        Route::put('/{product}', 'ProductController@update')->name('product.update');
        Route::delete('/{product}', 'ProductController@delete')->name('product.delete');
    });
});

Route::group(['prefix' => 'clients'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/list', 'ClientsController@list')->name('clients.list');
        Route::get('/{client}', 'ClientsController@get')->name('clients.get');
        Route::post('/', 'ClientsController@create')->name('clients.create');
        Route::put('/{client}', 'ClientsController@update')->name('clients.update');
        Route::delete('/{client}', 'ClientsController@delete')->name('clients.delete');
    });
});

Route::group(['prefix' => 'users'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::post('/reset', 'UsersController@reset')->name('user.reset');
        Route::get('/info', 'UsersController@getUser')->name('user.getUser');
        Route::get('/', 'UsersController@filter')->name('user.filter');
        // Route::get('/info', 'UsersController@getUser')->name('user.getUser');
        Route::get('/{user}', 'UsersController@get')->name('user.get');
        Route::get('/{user}', 'UsersController@getUserById')->name('user.getUserById');
        Route::post('/', 'UsersController@create')->name('user.create');
        Route::put('/update_password/{user}', 'UsersController@updateUserPassword')->name('user.updateUserPassword');
        Route::put('/{user}', 'UsersController@update')->name('user.update');
        Route::delete('/{user}', 'UsersController@delete')->name('user.delete');
    });
});

Route::group(['prefix' => 'roles'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::get('/', 'RolesController@getAll')->name('role.all');
        Route::get('/{role}', 'UsersController@get')->name('role.get');
    });
});


// ------------------------------------------------------------------------------
//  Reports
// ------------------------------------------------------------------------------
Route::group(['prefix' => 'reports'], function () {
    Route::group(['middleware' => ['cors', 'json.response']], function () {
        //---------------
        // @ Opportunities
        //---------------
        Route::get('/rbc', 'ReportsController@requestsByCountry')->name('reports.requestsByCountry');
        Route::get('/rdbc', 'ReportsController@requestsDistributionByCountry')->name('reports.requestsDistributionByCountry');
        // http://localhost:8000/api/reports/rpmc?from=2017-01-31&to=2023-05-11&branch_id=1009
        Route::get('/rpmc', 'ReportsController@requestsPerMonthByCountry')->name('reports.requestsPerMonthByCountry');
        Route::get('/rsbs', 'ReportsController@requestsBySolution')->name('reports.requestsBySolution');
        Route::get('/rprm', 'ReportsController@RequestsPerMonth')->name('reports.RequestsPerMonth');
        
        //---------------
        // @ Resources
        //---------------
        Route::get('/rsbr', 'ReportsController@requestsStatusByResources')->name('reports.requestsStatusByResources');
        Route::get('/crbr', 'ReportsController@countriesRequestByResources')->name('reports.countriesRequestByResources');
        // http://localhost:8000/api/reports/rpmr?from=2017-01-31&to=2023-05-11&user_id=476
        Route::get('/rpmr', 'ReportsController@requestsPerMonthByResources')->name('reports.requestsPerMonthByResources');

        //---------------
        // @ Financial
        //---------------
        Route::get('/prbc', 'ReportsController@profitByCountries')->name('reports.profitByCountries');
        Route::get('/prbs', 'ReportsController@profitBySolution')->name('reports.profitBySolution');
        Route::get('/ttpf', 'ReportsController@totalProfit')->name('reports.totalProfit');
    });
});


// Route::group(['middleware' => ['cors', 'json.response', 'auth:api', 'role:writer']], function () {

//     Route::get('/tests', function (Request $request) {
//         return "Hello world";
//     });


//     Route::get('/create-role', function (Request $request) {
//         $role = Role::create(['name' => 'writer']);
//         return $role;
//     });


//     Route::get('/assign-role', function (Request $request) {

//         $user = $request->user();
        // $writerRole = Role::findByName('writer');
//         // $user->assignRole($writerRole);
//         $res =  $user->hasRole('writer');
//         return $user;
//     });
// });
