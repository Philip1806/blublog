<p align="center"><img src="https://demo.blublog.info/blublog-uploads/posts/4-admin-panel-index.jpg"></p>

## About BLUBLOG

**BLUblog is close to BETA version.**
BLUblog is simple laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for basic functionality of a blog after installation. It's made simple with mostly blade and a little basic javascript.
URL address of the panel and blog can be changed in config file.

Front end demo and documentation at http://demo.blublog.info/blog

## Requirements And Important Notice

1. Your app must have a laravel/ui or the same authentication with User model in App folder that have property "name".
   BLUblog imports users from that User model to BlublogUser model and gives them other properties independent of your application. If logged in user is not imported to BlublogUser, they will not have access to the panel.
   For some cases that could be a good thing or a bad thing.

2. It's **recommended** for trusted users, because posts outputs HTML. But there is option author not to be able to make his posts public until someone approve specified number of posts.

3. BLUblog is made as independent blog. You can extend your laravel application with it.
   Or if you just want a blog on Laravel, you will need to make a front page (or copy blog front page) and then you will have a complete Laravel App. BLUblog does not assume you have build anything more than authentication (with laravel/ui). It provides all the basic things you will need.

## Instaling

The package requires three other packages - intervention image, laravelcollective/html and laravel/ui.
You can install them with:

```
composer require laravel/ui
composer require laravelcollective/html
composer require intervention/image
```

1. Add package to laravel with:

```
composer require blublog/blublog
```

- Make sure in app.php from config folder, under providers this is there:

```
Blublog\Blublog\BlublogServiceProvider::class,
```

3. login into your app without going to the blog and run this:

```
php artisan blublog:install
```

You will be ask if you want express install. Unless you get errors, you should use express install.

4. Cool. Everything except uploading/deleting files should work.
   You need to add this in filesystems.php from config folder:

```
'blublog' => [
    'driver' => 'local',
    'root' => public_path('/blublog-uploads'),
    'url' => env('APP_URL').'/blublog-uploads/',
    'visibility' => 'public',
],
```

You can set up where all files from the package go.

By default you can access the blog from /blog and panel from /panel.

**If you get 404 error, make sure you're logged in. If you get 403 error, make sure you have run blublog:setup or blublog:install**;

**If post images don't show, make sure you have configured blublog file driver. If you use the one above, make sure your APP_URL in .env file is correct.**

## Features

1. Posts (CRUD)

- Multible categories.
- Multible tags.
- Open Graph.
- CKeditor - WYSIWYG HTML Editor.
- Post image can be uploaded or choosen by pop-up modal from already uploaded images. You can define the image dimensions and quality.
- Custom SEO post title and description (It's auto generated if not specified).
- Comments can be allowed or forbidden.
- Post can be public, private or draft. Private posts are seen only from post author. Drafts are seen by all users with access to the panel.
- Excerpt of content. Could be empty.
- Basic search for posts.
- Rating system with five stars. You can use it as likes/dislikes.
- Similar posts.
- Views statistics.
- Auto generate sitemap (RSS).
- Custom html in header, footer and posts comments.
- Post types: Post, Video and custom.
- You can select posts to be recommended and for a slider. Default theme do not take advantage of this for now.
- You can upload images for the post you create/edit. All of them will be shown when you view the post in the panel.
- "On this topic" - Select a tag to show other post from the same topic.

2. Comments (CRUD)

- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.
- Basic search for comments by username, part of the comment or IP address.

3. Categories (CRUD)

- Background image.
- Description.
- Color code.

4. Pages (CRUD)

- CKeditor - WYSIWYG HTML Editor.
- With sidebar or not.
- Background image.
- Public or not.

5. Users

- Import users from Laravel UI.
- They are three built in roles - Administrator, Moderator and Author.
- You can control roles permissions and create new roles.

6. File manager

- Upload files that are public and hiden.

7. Admin

- BAN option. You can ban users from the blog or from commenting.
- Very basic settings page for now.
- Very basic menu options. You can make links and dropdowns. There are html templates for the links and dropdowns, so that you can change it if your theme do not use bootstrap.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.
- Maintenance mode.

8. API
   Simple API for getting posts, categories, comments, tags and others. It's a public API, so for actions requiring authorization, you will need to use the panel. You can write your own custom api calls for your theme.

## Design Customization

Creating your own desing/theme for BLUblog is easy. Look at the documentation. (still not made)
Basically, you put your theme files (blade.php) in here: \resources\views\vendor\blublog\your_theme_name and from settings page change theme field from "blublog" to "your_theme_name".
Default theme is in \resources\views\vendor\blublog\blublog. You can edit it.

## TODO

1. General

- Improve API.
- Make the code better - more compact, reduce repetition...
- Add comments for most of the code.
- Testing, testing, testing...
- Write documentation (50% done).

2. Planed extension/packages

- Statistics - Learn whats most seen posts in different time period or/and category. Monitor Authors and Moderators actions. And many others.
- Gallery - For all posts you can make multiple galleries.
- Calendar - Add events and posts to calendar.

## ROADMAP

- September 2020 - Fist beta of BLUblog.
- October 2020 - First BLUblog theme/design (package).
- Q1 of 2021 - First extension (Gallery or Statistics package for BLUblog).

## Package Support

I'm using it for my projects, so it's gonna be updated (probably not very often). If you decide to use it and find problems/bugs or you want some new functionality, add new issue on github.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
