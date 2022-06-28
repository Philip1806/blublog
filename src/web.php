<?php


Route::group(
    ['middleware' => ['web'], 'namespace' => 'Blublog\Blublog\Controllers'],
    static function () {

        Route::group(
            ['prefix' => config('blublog.blog_prefix', 'blog')],
            static function () {
                Route::get('/search', 'BlublogFrontController@search')->name('blublog.front.search');
                Route::get('/', 'BlublogFrontController@index')->name('blublog.index');
                Route::get('/{blogPostSlug}', 'BlublogFrontController@show')->name('blublog.front.single');
                Route::get('/category/{slug}', 'BlublogFrontController@category')->name('blublog.front.category');
                Route::get('/tag/{slug}', 'BlublogFrontController@tag')->name('blublog.front.tag');
                Route::group(['middleware' => 'throttle:7,1'], static function () {
                    Route::get('/like/{slug}', 'BlublogFrontController@like')->name('blublog.front.like');
                    Route::post('/comments/add', 'BlublogFrontController@comment_store')->name('blublog.front.comments.store');
                    Route::post('/comment/reply/add', 'BlublogFrontController@comment_store')->name('blublog.front.comments.reply.store');
                });
            }
        );

        Route::group(
            [
                'prefix'     => config('blublog.panel_prefix', 'panel'),
                'middleware' => 'BlublogPanel',
            ],
            static function () {
                Route::get('/', 'BlublogBackController@index')->name('blublog.panel.index');
                Route::get('/posts', 'BlublogPostsController@index')->name('blublog.panel.posts.index');
                Route::get('/posts/create', 'BlublogPostsController@create')->name('blublog.panel.posts.create');
                Route::post('/posts/create', 'BlublogPostsController@store')->name('blublog.panel.posts.store');
                Route::get('/posts/{id}/edit', 'BlublogPostsController@edit')->name('blublog.panel.posts.edit');
                Route::get('/posts/{id}', 'BlublogPostsController@show')->name('blublog.panel.posts.show');

                Route::put('/posts/{id}/update', 'BlublogPostsController@update')->name('blublog.panel.posts.update');
                Route::get('/panel/generate-rss',  'BlublogBackController@rss')->name('blublog.rss');


                Route::get('/images', 'BlublogBackController@images')->name('blublog.panel.images');


                Route::get('/comments', 'BlublogCommentController@index')->name('blublog.panel.comments.index');
                Route::put('/comments/{id}/edit', 'BlublogCommentController@update')->name('blublog.panel.comments.update');


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

                Route::get('/logs', 'BlublogBackController@logs')->name('blublog.panel.logs')->middleware('BlublogAdmin');
                Route::get('/logs/{id}', 'BlublogBackController@logsShow')->name('blublog.panel.logs.show')->middleware('BlublogAdmin');


                Route::get('/users/roles', 'BlublogRolesController@roles')->name('blublog.panel.users.roles')->middleware('BlublogAdmin');
                Route::get('/users/roles/{id}/edit', 'BlublogRolesController@rolesEdit')->name('blublog.panel.users.roles.edit')->middleware('BlublogAdmin');
                Route::put('/users/roles/{id}/edit', 'BlublogRolesController@rolesUpdate')->name('blublog.panel.users.roles.update')->middleware('BlublogAdmin');
                Route::delete('/users/roles/{id}/delete', 'BlublogRolesController@rolesDestroy')->name('blublog.panel.users.roles.destroy')->middleware('BlublogAdmin');
                Route::post('/users/roles/add', 'BlublogRolesController@rolesStore')->name('blublog.panel.users.roles.store')->middleware('BlublogAdmin');
            }
        );
    }
);
