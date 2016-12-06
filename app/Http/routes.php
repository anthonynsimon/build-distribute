<?php

// Web routes
Route::group(['middleware' => ['web', 'force.ssl']], function () {
    // Entry
    Route::get('/', ['middleware' => 'auth', 'uses' => 'HomeController@index']);
    
    // Auth Routes
    Route::get('/login', 'Auth\AuthController@getLogin');
    Route::get('/logout', 'Auth\AuthController@logout');
    Route::post('/login', 'Auth\AuthController@postLogin');
    Route::get('/register', 'Auth\AuthController@getRegister');
    Route::post('/register', 'Auth\AuthController@postRegister');

    Route::get('/oauth2/redirect/{provider}', ['as' => 'social.redirect',   'uses' => 'Auth\OAuth2Controller@redirectToProvider']);
    Route::get('/oauth2/handle/{provider}', ['as' => 'social.handle',     'uses' => 'Auth\OAuth2Controller@handleProviderCallback']);
    
    // Password reset
    Route::get('password/email', 'Auth\PasswordController@getEmail');
    Route::post('password/email', 'Auth\PasswordController@postEmail');
    Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
    Route::post('password/reset', 'Auth\PasswordController@postReset');
});

// Web routes that require Auth
Route::group(['middleware' => ['web', 'auth', 'force.ssl']], function () {
    Route::resource('/projects', 'ProjectController', ['only' => ['index', 'show', 'store', 'edit', 'update', 'create']]);
    Route::get('/builds/{buildId}', 'BuildController@show');
    Route::get('/projects/{projectId}/builds', 'ProjectController@show'); // TODO: don't show in breadcrumbs. Then remove this
    Route::get('/projects/{projectId}/builds/head', 'ProjectController@headBuildsShow');
    Route::get('/projects/{projectId}/builds/{buildId}', 'BuildController@nestedShow');
    Route::patch('/projects/{projectId}/builds/{buildId}/note', 'BuildController@patchBuildNote');
    Route::get('/downloads/builds/{buildId}', 'InstallLinkController@getAwsBuild');

    Route::post('/projects/{projectId}/builds/{buildId}/tag', 'BuildController@tag');
    Route::delete('/projects/{projectId}/builds/{buildId}/untag/{tagName}', 'BuildController@untag');
});

// iOS doesn't hold session cookies for retrieving the plist
Route::group(['middleware' => ['force.ssl']], function () {
    // Route cannot contain query string, token must be a nested route
    Route::get('/downloads/plist/{token}', 'InstallLinkController@getAwsPlist');
});
 
// Admin only web routes
Route::group(['middleware' => ['web', 'auth', 'force.ssl']], function () {
    Route::get('/admin', 'UserAdminController@index');
    Route::get('/admin/users', 'UserAdminController@indexUsers');
    Route::get('/admin/users/{userId}', 'UserAdminController@showUser');
    Route::post('/admin/users/{userId}/role', 'UserAdminController@updateUserRole');
    Route::delete('/admin/users/{userId}', 'UserAdminController@destroyUser');
    Route::post('/admin/permissions/revoke', 'ProjectPermissionController@revokeAccess');
    Route::post('/admin/permissions/grant', 'ProjectPermissionController@grantAccess');
});

// API
// Access routes
Route::group(['prefix' => '/auth', 'middleware' => 'api.authorize'], function () {
    Route::post('/authenticate', 'API\APIAuthController@authenticate');
    Route::get('/me', 'API\APIAuthController@getAuthenticatedUser');
});

// Resource routes
Route::group(['prefix' => '/api/v1', 'middleware' => ['api']], function () {
    // Resources
    Route::resource('/builds', 'API\BuildController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('/projects', 'API\ProjectController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    Route::resource('/projects.builds', 'API\ProjectBuildController', ['only' => ['index', 'show']]);
    Route::resource('/users', 'API\UserController', ['only' => ['index', 'show']]);
    
    // Additional relationships
    Route::get('/projects/{projectId}/head', 'API\ProjectBuildController@indexHead');
    Route::get('/builds/{buildId}/project', 'API\BuildController@getProject');
});
