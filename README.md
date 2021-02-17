<p align="center"><img src="https://demo.blublog.info/blublog-uploads/files/40blogcreate.jpg"></p>

## About BLUBLOG

**This is the 2.0 version dev branch. The package is been rewritten. It's still in alpha.**

BLUblog is simple laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for basic functionality of a blog after installation.

## Require

- **Require Livewire**
- Require laravelcollective/html (probably will be removed in the future)
- Require intervention/image
- Require user model with auth.

## Instaling

1. Make sure you have the requirements above.

In your User model you need to add this trait:

```
...
use Blublog\Blublog\Traits\ManageBlublog;
...
class User extends Authenticatable
{
    use ManageBlublog;
    ...
```

If your User model is not in App\Models, edit blublog config file.

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

Cool. Everything should work now.

## Features

**Not all features below are ready.** With + are done but may not be tested enough.

In bold are new for verison 2.

1. Posts (CRUD)

- Multible categories **with nesting**.+
- Multible tags.+
- Summernote - WYSIWYG HTML Editor.+
- **Easy adding and removing custom image sizes and settings.**+
- Custom SEO post title and description (It's auto generated if not specified).+
- Search.+
- Comments can be allowed or forbidden.+
- Post have status. **You can add or remove status. Post status tells who can view/edit post in panel (published, private...)**+.
- Excerpt of content. Could be empty.+
- You can likes/dislike post.+
- Similar posts.
- Views statistics.+
- **Post revisions.**
- Auto generate sitemap (RSS).
- You can select posts to be recommended or in front page.+
- You can upload images for the post you create/edit.+
- "On this topic" - Select a tag to show other post from the same topic.

2. Comments (CRUD)

- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.

3. Categories (CRUD)+

- Background image.
- Description.
- **Infinite category nesting.** This means that one category can have multiple sub categories.

4. Users+

- **Exend and use your User with traid.**
- **Add blog roles to your user model.**
- You can create new roles and **new permissions**.

5. Image manager+

- Browse and upload images.
- All images have different sizes. On upload Blublog will create the sizes set in config file.

6. Admin+

- You can ban users from the blog or from commenting.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.

## Big New things in version 2

1.  Post status

In config file you can set up what status types you gonna use.
Every status have access code, edit code and revision setting.

Access codes:

- 0 - public. Post with that status code can be seen by all.
- 1 - Restricted. Only seen by mods and admin.
- 2 - Private. Only seen by the author.
- 3 - Custom. Blublog will check if user have permission "view-{your-post-status}"

Edit codes:

- 0 - Can be edited by all users.
- 1 - Restricted. Post author, mods and admin can edit post.
- 2 - Custom. Blublog will check if user have permission "edit-{your-post-status}"

Revision setting:

- With true or false for every status you set if you want blublog to keep revisions for post with that status.

2. More appropriate for users you don't trust.

With roles and permissions you can make sure that posts from some users WILL wait to be approved. Also you **can restrict html output of some roles or totaly disable it**.

3. Extending your user model.

You can check for blog permission of your users like this:

```
$user->blublogRoles->first()->havePermission('delete-posts')
```

You also have access to user's images and posts with blublogImages and blublogPosts. More to be added.

4. Blublog drops support for pages, menu and star rating.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
