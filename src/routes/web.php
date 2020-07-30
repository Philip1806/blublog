<?php


Route::group(
    ['middleware' => ['web'], 'namespace' => 'Blublog\Blublog\Controllers'],
    static function () {
        //Something like API
        Route::get('/blublog/listimg', 'BlublogAPIController@listimg')->name('blublog.posts.listimg')->middleware('auth');
        Route::post('/blublog/search', 'BlublogAPIController@search')->name('blublog.api.searchfile')->middleware('auth');
        Route::post('/blublog/set_rating', 'BlublogAPIController@set_rating')->name('blublog.api.set_rating')->middleware('throttle:30,1');;
        Route::get('/blublog/categories', 'BlublogAPIController@categories')->name('blublog.api.categories')->middleware('throttle:30,1');;
        Route::get('/blublog/post/{slug}', 'BlublogAPIController@post')->name('blublog.api.post')->middleware('throttle:30,1');;
        Route::get('/blublog/post/{slug}/similar-posts', 'BlublogAPIController@similar_posts')->name('blublog.api.similar_posts')->middleware('throttle:30,1');;
        Route::get('/blublog/category/{slug}', 'BlublogAPIController@category')->name('blublog.api.category')->middleware('throttle:30,1');;
        Route::get('/blublog/tag/{slug}', 'BlublogAPIController@tag')->name('blublog.api.tag')->middleware('throttle:30,1');;
        Route::get('/blublog/post/{slug}/comments', 'BlublogAPIController@comments')->name('blublog.api.comments')->middleware('throttle:30,1');;
        Route::any('/blublog/custom/', 'BlublogAPIController@api')->name('blublog.api.api')->middleware('throttle:10,1');;

        //Blog front end group
        Route::group(
            ['prefix' => config('blublog.blog_prefix', 'blog'),'middleware'=>'throttle:60,1'],
            static function () {
                Route::get('/page/{slug}', 'BlublogFrontController@page')->name('blublog.front.pages.show');
                Route::get('/', 'BlublogFrontController@index')->name('blublog.index');
                Route::get('/posts/{slug}', 'BlublogFrontController@post_show')->name('blublog.front.post_show');
                Route::post('/comment/store', 'BlublogFrontController@comment_store')->name('blublog.front.comment_store');
                Route::post('/comment/reply/store', 'BlublogFrontController@comment_store')->name('blublog.front.comment_reply_store');
                Route::get('/categories/{slug}', 'BlublogFrontController@category_show')->name('blublog.front.category_show');
                Route::get('/tags/{slug}', 'BlublogFrontController@tag_show')->name('blublog.front.tag_show');
                Route::post('/search', 'BlublogFrontController@search')->name('blublog.front.search');
                Route::get('/author/{name}', 'BlublogFrontController@author')->name('blublog.front.author');

            }
        );

        // Backend routes - CRUD for posts, categories, and approving/deleting submitted comments.
        Route::group(
            [
                'middleware' => ['BlublogPanel'],
                'prefix'     => config('blublog.panel_prefix', 'panel'),

            ],
            static function () {
            Route::get('/comments/approve/{id}', 'BlublogCommentsController@approve')->name('blublog.comments.approve');

            Route::get('/rating/duplicate/{id}', 'BlublogRatingController@duplicate')->name('blublog.posts.rating.duplicate');
            Route::delete('/rating/delete/{id}', 'BlublogRatingController@destroy')->name('blublog.posts.rating.destroy');
            Route::resource('/posts/rating', 'BlublogRatingController', [
                'as' => 'blublog'
            ])->only([
                'index', 'destroy'
            ]);
            Route::resource('/tags', 'BlublogTagController', [
                'as' => 'blublog'
            ])->only([
                'destroy'
            ]);
            Route::get('/comments/ban/{id}', 'BlublogCommentsController@ban')->name('blublog.comments.ban');
            Route::get('/ban', 'BlublogBanController@index')->name('blublog.ban.index');
            Route::delete('/ban/delete/{ban}', 'BlublogBanController@destroy')->name('blublog.ban.destroy');
            Route::post('/ban/add/store', 'BlublogBanController@ban')->name('blublog.ban.user');
            Route::get('/blublog/control/{setting}', 'BlublogSettingController@admin_control')->name('blublog.admin.control');

            Route::group(
                ['middleware'=>'BlublogUseMenu'],
                static function () {
                // Menu
                Route::get('/menu/set-main/{id}', 'BlublogMenuController@set_main_menu')->name('blublog.menu.set_main_menu');
                Route::get('/menu/menu_items/{id}', 'BlublogMenuController@menu_items')->name('blublog.menu.menu_items');
                Route::get('/menu/edit_item/{id}', 'BlublogMenuController@edit_item')->name('blublog.menu.edit_item');
                Route::put('/menu/edit_item', 'BlublogMenuController@edit_item_update')->name('blublog.menu.edit_item_update');
                Route::put('/menu/update', 'BlublogMenuController@edit_menu_update')->name('blublog.menu.edit_menu_update');
                Route::delete('/menu/items/{item}', 'BlublogMenuController@destroy_item')->name('blublog.menu.destroy_item');
                Route::delete('/menu/menus/{menu}', 'BlublogMenuController@destroy_menu')->name('blublog.menu.destroy_menu');
                Route::post('/menu/add_parent/store', 'BlublogMenuController@add_parent_store')->name('blublog.menu.add_parent_store');
                Route::post('/menu/add/store', 'BlublogMenuController@add_menu_store')->name('blublog.menu.add_menu_store');
                Route::post('/menu/add_child/store', 'BlublogMenuController@add_child_store')->name('blublog.menu.add_child_store');
                Route::get('/menu', 'BlublogMenuController@index')->name('blublog.menu.index');
                }
            );

            Route::resource('/pages', 'BlublogPagesController', [
                'as' => 'blublog'
            ])->only([
                'index','edit', 'create', 'store','update','destroy'
            ]);

            Route::resource('/categories', 'BlublogCategoryController', [
                'as' => 'blublog'
            ])->only([
                'index','edit', 'store','update','destroy'
            ]);

            Route::resource('/posts', 'BlublogPostsController', [
                'as' => 'blublog'
            ])->only([
                'index','edit','show', 'create', 'store','update','destroy'
            ]);

            Route::resource('/comments', 'BlublogCommentsController', [
                'as' => 'blublog'
            ])->only([
                'index', 'edit', 'update', 'destroy'
            ]);

            Route::resource('/tags', 'BlublogTagController', [
                'as' => 'blublog'
            ])->only([
                'index','edit', 'store','update'
            ]);

            // Files
            Route::get('/files/{id}/download', 'BlublogFileController@download')->name('blublog.files.download');
            Route::resource('/files', 'BlublogFileController', [
                'as' => 'blublog'
            ])->only([
                'index','create', 'store','destroy'
            ]);


            // Users
            Route::post('/users/add', 'BlublogUserController@add')->name('blublog.users.add');
            Route::get('/users/create', 'BlublogUserController@create')->name('blublog.users.create');
            Route::get('/users', 'BlublogUserController@index')->name('blublog.users.index');
            Route::resource('/users', 'BlublogUserController', [
                'as' => 'blublog'
            ])->only([
                'edit', 'update','destroy'
            ]);

            Route::get('/', 'BlublogController@panel')->name('blublog.panel');

            Route::group(
                ['middleware'=>'BlublogAdmin'],
                static function () {

                    // Admin Only

                Route::get('/settings', 'BlublogSettingController@general_settings')->name('blublog.settings.general');
                Route::resource('/settings', 'BlublogSettingController', [
                    'as' => 'blublog'
                ])->only([
                    'store','destroy'
                ]);
                Route::resource('/logs', 'BlublogLogController', [
                    'as' => 'blublog'
                ])->only([
                    'index','show','destroy'
                ]);
                Route::get('/updates', 'BlublogSettingController@update_blublog')->name('blublog.update');
                Route::post('/role/update', 'BlublogSettingController@role')->name('blublog.roles.update');
                Route::get('/roles', 'BlublogSettingController@roles')->name('blublog.roles');
                }
            );


            }
        );


    }
);
