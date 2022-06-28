<p align="center"><img src="https://demo.blublog.info/blublog-uploads/photos/2022/6/fmowV0GkHUXwZTjk6cGToq3tQ2s8uGbxAKNuGpZr.jpg"></p>

## About BLUBLOG

** The package is currently being rewritten for version 3.0. Last commits WILL break old versions. **

BLUblog is laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for a blog.

**Demo at [demo.blublog.info](https://demo.blublog.info/)**.

## Require

- Laravel 7 or newer. It's build on Laravel 8.
- **Livewire**.
- laravelcollective/html
- intervention/image
- user model with auth.

You can install all you need with:

```
composer require livewire/livewire
composer require laravelcollective/html
composer require intervention/image
```

Check below if you don't have authentication.

## Instaling

0. Make sure you have the requirements above. Download BLUblog with:

```
composer require blublog/blublog
```

1. In your User model you need to add this trait:

```
...
use Blublog\Blublog\Traits\ManageBlublog;
...
class User extends Authenticatable
{
    use ManageBlublog;
    ...
```

**If your User model is not in App\Models, edit blublog config file** in src/Config/blublog.php.

2. You need to add this in filesystems.php from config folder:

```
'blublog' => [
    'driver' => 'local',
    'root' => public_path('/blublog-uploads'),
    'url' => env('APP_URL').'/blublog-uploads/',
    'visibility' => 'public',
],
```

3. Make sure you have at least one user and run this:

```
php artisan blublog:install
```

Or you can use blublog:setup if you already run migrations and don't wan't to publish files.

Blublog will check for common errors and do all it needs. **Only the first user** of your user model will have access to blog panel and they will be admin. You can give access to others and set up blog roles and permisions from the panel.

Cool. Everything should work now. By default you can visit your blog panel in example.com/panel and your blog in example.com/blog.

## Upgrading

If somebody is using it, here is what you need to do to upgrade to 3.0.

- **See** the new migration.
- Replace blublog config file with the new one. In the array "image_sizes" follow the instructions in the comments for backwards compatibility.
- Add the new column (file_id) in blublog_posts table. You will need to make a php script that finds the id of a File model given the content of the old column (img).

## Features

1. Posts (CRUD)

- Multible categories **with nesting** and multible tags.
- Summernote - WYSIWYG HTML Editor.
- **Easy adding and removing custom image sizes and settings.**
- Custom SEO post title and description (It's auto generated if not specified).
- Search.
- Comments can be allowed or forbidden.
- Posts have status. **You can add or remove status. Post status tells who can view/edit post in panel (published, private...)**.
- Post type. By default all posts are with type "post". You can add dropdown with other types in the view file.
- **Permissions for displaying HTML**. You can set for a user role 3 options for this - no HTML, restricted and no filter. Restricted option will use mewebstudio/Purifier if installed, if not a custom filter.
- Posts can be liked.
- Similar posts.
- Views statistics for views and likes.
- **Post revisions**. You can select post with what status to have revisions and how many.
- **Create post with other users**. Author can change post status to co-op and give edit link to other user and edit together.
- Generate sitemap (RSS).\
- User can change the date that a post is created.
- User can change author if have permission.
- You can select posts to be recommended or in front page.
- You can upload images for the post you create/edit.
- "On this topic" - Select a tag to show other post from the same topic.

2. Comments (CRUD)

- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.

3. Categories (CRUD)

- Background image.
- Description.
- **Infinite category nesting.** This means that one category can have multiple sub categories.

4. Users (CRUD)

- **Exend and use your User with traid.**
- **Add blog roles to your user model.**
- You can create new roles and **new permissions**.

5. Image manager

- Browse and upload images.
- All images have different sizes. On upload Blublog will create the sizes set in config file.

6. Panel

- You can ban users from the blog or from commenting.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.

And more...

## If you don't have authentication

You can use laravel ui. You can install it like this for Laravel 8:

```
composer require laravel/ui
php artisan ui bootstrap --auth
```

And like this for Laravel 7:

```
composer require laravel/ui "2.0"
php artisan ui bootstrap --auth
```

## Support

I'm using it on my projects and I will be using it on new ones. I do changes like bug fixes first on my projects when needed and later (sometimes months) I add them here. If I see interest in this repository I will support it better and faster.

If you find any bugs or you want something changed or added, create an issue.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
