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
