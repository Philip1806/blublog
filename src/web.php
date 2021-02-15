<?php


Route::group(
    ['middleware' => ['web'], 'namespace' => 'Blublog\Blublog\Controllers'],
    static function () {

        Route::group(
            ['prefix' => config('blublog.blog_prefix'), 'middleware' => 'throttle:60,1'],
            static function () {
                Route::get('/', 'BlublogFrontController@index')->name('blublog.index');
            }
        );

        Route::group(
            ['prefix' => config('blublog.panel_prefix'), 'middleware' => 'BlublogPanel'],
            static function () {
                Route::get('/', 'BlublogBackController@index')->name('blublog.panel.index');
                Route::get('/posts', 'BlublogPostsController@index')->name('blublog.panel.posts.index');
                Route::get('/posts/create', 'BlublogPostsController@create')->name('blublog.panel.posts.create');
                Route::post('/posts/create', 'BlublogPostsController@store')->name('blublog.panel.posts.store');
                Route::get('/posts/{id}/edit', 'BlublogPostsController@edit')->name('blublog.panel.posts.edit');

                Route::put('/posts/{id}/update', 'BlublogPostsController@update')->name('blublog.panel.posts.update');


                Route::get('/images', 'BlublogBackController@images')->name('blublog.panel.images');



                Route::get('/tags', 'BlublogBackController@tags')->name('blublog.panel.tags');
                Route::put('/tags/{id}/edit', 'BlublogBackController@tagsUpdate')->name('blublog.panel.tags.update');


                Route::get('/categories', 'BlublogCategoriesController@index')->name('blublog.panel.categories.index');
                Route::post('/categories/add', 'BlublogCategoriesController@store')->name('blublog.panel.categories.store');
                Route::put('/categories/{id}/add', 'BlublogCategoriesController@update')->name('blublog.panel.categories.update');
                Route::delete('/categories/{id}/delete', 'BlublogCategoriesController@destroy')->name('blublog.panel.categories.destroy');


                Route::get('/users', 'BlublogUserController@index')->name('blublog.panel.users.index');
                Route::post('/users/add', 'BlublogUserController@store')->name('blublog.panel.users.store');
                Route::put('/users/{id}/edit', 'BlublogUserController@update')->name('blublog.panel.users.update');
                Route::delete('/users/{id}/delete', 'BlublogUserController@destroy')->name('blublog.panel.users.destroy');



                Route::get('/users/roles', 'BlublogRolesController@roles')->name('blublog.panel.users.roles')->middleware('BlublogAdmin');
                Route::get('/users/roles/{id}/edit', 'BlublogRolesController@rolesEdit')->name('blublog.panel.users.roles.edit')->middleware('BlublogAdmin');
                Route::put('/users/roles/{id}/edit', 'BlublogRolesController@rolesUpdate')->name('blublog.panel.users.roles.update')->middleware('BlublogAdmin');
                Route::delete('/users/roles/{id}/delete', 'BlublogRolesController@rolesDestroy')->name('blublog.panel.users.roles.destroy')->middleware('BlublogAdmin');
                Route::post('/users/roles/add', 'BlublogRolesController@rolesStore')->name('blublog.panel.users.roles.store')->middleware('BlublogAdmin');
            }
        );
    }
);