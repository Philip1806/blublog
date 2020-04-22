<?php


Route::group(
    ['middleware' => ['web'], 'namespace' => 'Philip\Blublog\Controllers'],
    static function () {

        Route::post('/blublog/uploadimg', 'BlublogPostsController@uploadimg')->name('blublog.posts.uploadimg')->middleware('auth');
        Route::get('/blublog/listimg', 'BlublogPostsController@listimg')->name('blublog.posts.listimg')->middleware('auth');
        Route::post('/blublog/searchfile', 'BlublogPostsController@searchfile')->name('blublog.posts.searchfile')->middleware('auth');


        //Blog front end group
        Route::group(
            ['prefix' => config('blublog.blog_prefix', 'blog')],
            static function () {
                Route::get('/page/{slug}', 'BlublogFrontController@page')->name('blublog.front.pages.show');
                Route::get('/', 'BlublogFrontController@index')->name('blublog.index');
                Route::get('/posts/{slug}', 'BlublogFrontController@post_show')->name('blublog.front.post_show');
                Route::post('/comment/store', 'BlublogFrontController@comment_store')->name('blublog.front.comment_store');
                Route::post('/comment/reply/store', 'BlublogFrontController@comment_store')->name('blublog.front.comment_reply_store');
                Route::get('/categories/{slug}', 'BlublogFrontController@category_show')->name('blublog.front.category_show');
                Route::get('/tags/{slug}', 'BlublogFrontController@tag_show')->name('blublog.front.tag_show');

            }
        );

        // Admin backend routes - CRUD for posts, categories, and approving/deleting submitted comments.
        Route::group(
            [
                'middleware' => ['auth'],
                'prefix'     => config('blublog.panel_prefix', 'panel'),

            ],
            static function () {

                Route::get('/comments/approve/{id}', 'BlublogCommentsController@approve')->name('comments.approve');

                Route::get('/menu/menu_items/{id}', 'BlublogMenuController@menu_items')->name('menu.menu_items');

                Route::get('/menu/edit_item/{id}', 'BlublogMenuController@edit_item')->name('menu.edit_item');
                Route::put('/menu/edit_item', 'BlublogMenuController@edit_item_update')->name('menu.edit_item_update');

                Route::put('/menu/update', 'BlublogMenuController@edit_menu_update')->name('menu.edit_menu_update');
                Route::delete('/menu/items/{item}', 'BlublogMenuController@destroy_item')->name('menu.destroy_item');
                Route::delete('/menu/menus/{menu}', 'BlublogMenuController@destroy_menu')->name('menu.destroy_menu');

                Route::post('/menu/add_parent/store', 'BlublogMenuController@add_parent_store')->name('menu.add_parent_store');
                Route::post('/menu/add/store', 'BlublogMenuController@add_menu_store')->name('menu.add_menu_store');
                Route::post('/menu/add_child/store', 'BlublogMenuController@add_child_store')->name('menu.add_child_store');


                Route::get('/menu', 'BlublogMenuController@index')->name('menu.index');


                Route::resource('/logs', 'BlublogLogController', [
                    'as' => 'blublog'
                ])->only([
                    'index','show','destroy'
                ])->middleware('auth')->middleware('BlublogAdmin');
                Route::post('/users/add', 'BlublogUserController@add')->name('blublog.users.add')->middleware('BlublogAdmin');
                Route::resource('/pages', 'BlublogPagesController', [
                    'as' => 'blublog'
                ])->only([
                    'index','edit', 'create', 'store','update','destroy'
                ])->middleware('BlublogAdmin');
                Route::resource('/categories', 'BlublogCategoryController', [
                    'as' => 'blublog'
                ])->only([
                    'index','edit', 'store','update','destroy'
                ])->middleware('BlublogAdmin');

                Route::get('/', 'BlublogController@panel')->name('blublog.panel');
                Route::resource('/posts/rating', 'BlublogRatingController', [
                    'as' => 'blublog'
                ])->only([
                    'index', 'destroy'
                ])->middleware('auth');
                Route::resource('/posts', 'BlublogPostsController', [
                    'as' => 'blublog'
                ])->only([
                    'index','edit','show', 'create', 'store','update','destroy'
                ])->middleware('auth');
                Route::resource('/tags', 'BlublogTagController', [
                    'as' => 'blublog'
                ])->only([
                    'index','edit', 'store','update','destroy'
                ])->middleware('auth');
                Route::resource('/comments', 'BlublogCommentsController', [
                    'as' => 'blublog'
                ])->only([
                    'index', 'edit', 'update','destroy'
                ])->middleware('auth');
                Route::get('/files/{id}/download', 'BlublogFileController@download')->name('blublog.files.download');

                Route::resource('/files', 'BlublogFileController', [
                    'as' => 'blublog'
                ])->only([
                    'index','create', 'store','destroy'
                ]);
                //users
                Route::get('/users/create', 'BlublogUserController@create')->name('blublog.users.create')->middleware('BlublogAdmin');
                Route::get('/users', 'BlublogUserController@index')->name('blublog.users.index')->middleware('BlublogAdmin');
                Route::resource('/users', 'BlublogUserController', [
                    'as' => 'blublog'
                ])->only([
                    'edit', 'update','destroy'
                ])->middleware('BlublogAdmin');
                Route::get('/settings', 'BlublogSettingController@general_settings')->name('blublog.settings.general')->middleware('BlublogAdmin');
                Route::resource('/settings', 'BlublogSettingController', [
                    'as' => 'blublog'
                ])->only([
                    'store','destroy'
                ])->middleware('BlublogAdmin');

            }
        );


    }
);
