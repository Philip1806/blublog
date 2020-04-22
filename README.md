<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## About BLUBLOG
**BLUblog is close to BETA version.**
BLUblog is simple laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for basic functionality of a blog after installation.
URL address of the panel and blog can be changed in config file.

## Instaling
The package requires three other packages - intervention image, laravelcollective/html and laravel/ui.
You can install them with:
```
composer require laravel/ui
composer require laravelcollective/html
composer require intervention/image
```
In app.php from config folder add this in providers if it's not there:
```
Philip\Blublog\BlublogServiceProvider::class,
```
Log into your app without going to the blog. Run these one by one:
```
php artisan migrate
php artisan vendor:publish --provider="Philip\Blublog\BlublogServiceProvider"
php artisan blublog:setup
```
Cool. Everything except uploading/deleting files should work.
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
## Done
*With * are from last commit.*

1. Posts
- Have multible categories.
- Have multible tags.
- Open Graph.
- TinyMCE - WYSIWYG HTML Editor.
- Post image can be uploaded or choosen by pop-up modal from already uploaded images.
- Custom SEO post title and description (It's auto generated if not specified).
- Comments can be allowed or forbiden.
- Post can be public, private or draft. Private can be seen only from post author. Drafts are seen by all users with access to the panel.
- Have excerpt of content. Could be empty.
- Basic search for posts.*

2. Comments
- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.

3. Categories
- Can have background image.
- Can have custom color code (for now is auto generated).

4. Pages*
- TinyMCE - WYSIWYG HTML Editor.
- Page could have sidebar or not.
- Can have background image.
- Can be not public.

5. Users
- Use users from Laravel UI.
- They are three roles - Administrator, Moderator and Author.

6. File manager
- Upload files that are public and hiden.

7. Admin
- Very basic settings page for now.
- Very basic menu options. You can make link or dropdown.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.

## TO DO
*With (!) are very important.*
*With (-) are low priority and with (+) are high priority.*

1. FRONTEND
- Add author page.
- Add contact form page.(-)

2. COMMENTS
- Javascript search for comments in panel.
- Integrate with ban modul.
- Make comment rating modul.(-)
- Add support for facebook comments.(-)
- Add support for disqus comments.(-)

3. POSTS
- Add searchbar in panel.
- Add main tag.(+)
- Add password protection.(-)
- Add VIDEO type.(-)
- Make slider.(-)
- Javascript search for posts.
- make sitemap.

4. File manager 
- Javascript search for file.

5. TAGS
- Javascript search for tags in panel.

6. ADMIN THINGS
- Make a better menu.(-)
- (!) Cashing.(-)
- (!) Add BAN modul.
- Add maintenance mode.(-)
- A proper themes support.
- API.(-)
- Improve settings page.(-)
- (!) Write better code...


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
