<p align="center"><img src="http://demo.blublog.info/uploads/posts/1-panel-index.jpg"></p>

## About BLUBLOG
**BLUblog is close to BETA version.**
BLUblog is simple laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for basic functionality of a blog after installation.
URL address of the panel and blog can be changed in config file.

Front end demo and documentation at http://demo.blublog.info/blog

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
2. In app.php from config folder add this in providers if it's not there:
```
Blublog\Blublog\BlublogServiceProvider::class,
```
3. login into your app without going to the blog. Run these one by one:
```
php artisan migrate
php artisan vendor:publish --provider="Blublog\Blublog\BlublogServiceProvider"
php artisan blublog:setup
```
4. Cool. Everything except uploading/deleting files should work.
You need to add this in filesystems.php from config folder:
```
'blublog' => [
    'driver' => 'local',
    'root' => public_path('/uploads'),
    'url' => env('APP_URL').'/public',
    'visibility' => 'public',
],
```
You can set up where all files from the package go. It's not stable for now, so don't change this above.
By default you can access the blog from /blog and panel from /panel.
## Features

1. Posts (CRUD)
- Multible categories.
- Multible tags.
- Open Graph.
- TinyMCE - WYSIWYG HTML Editor.
- Post image can be uploaded or choosen by pop-up modal from already uploaded images.
- Custom SEO post title and description (It's auto generated if not specified).
- Comments can be allowed or forbiden.
- Post can be public, private or draft. Private posts are seen only from post author. Drafts are seen by all users with access to the panel.
- Excerpt of content. Could be empty.
- Basic search for posts.
- Rating system with five stars.
- Similar posts.
- Views statistics.
- Auto generate sitemap (RSS).
- Custom html in header, footer and posts comments.


2. Comments (CRUD)
- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.

3. Categories (CRUD)
- Background image.
- Description.
- Color code.

4. Pages (CRUD)
- TinyMCE - WYSIWYG HTML Editor.
- Page could have sidebar or not.
- Background image.
- Can be not public.

5. Users
- Import users from Laravel UI.
- They are three roles - Administrator, Moderator and Author.
- Authors can edit and delete only their own posts. Can create tags and upload files.
- Moderator can edit and delete all posts, tags, comments and categories.
- Administrator have full access with control over users, menu, site settings, logs and others.

6. File manager
- Upload files that are public and hiden.

7. Admin
- BAN option. You can ban users from the blog or from commenting.
- Very basic settings page for now.
- Very basic menu options. You can make links and dropdowns.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.
- Maintenance mode.

## Design Customization
Creating your own desing/theme for BLUblog is easy. Look at the documentation. (still not made)
Basically, you put your theme files (blade.php) in here: \resources\views\vendor\blublog\your_theme_name and from setting page change theme field from "blublog" to "your_theme_name".
Default theme is in \resources\views\vendor\blublog\blublog. You can edit it.

## Package Support
I'm using it for my projects, so it's gonna be updated (probably not very often). If you decide to use it and find problems/bugs or you want some new functionality, add new issue on github.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
