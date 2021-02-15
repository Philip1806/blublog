## About BLUBLOG

**This is the 2.0 version dev branch. The package is been rewritten.**

BLUblog is simple laravel blog package with admin panel. Includes all views, controllers, routes and everything needed for basic functionality of a blog after installation.

## Require

- Tested on Laravel 8
- **Require Livewire**
- Require laravelcollective/html (probably will be removed in the future)
- Require intervention/image
- Require user model with auth.

## Features

In bold are new for verison 2.
**Not all features below are ready.** With + are done but may not be tested enough.

1. Posts (CRUD)

- Multible categories **with nesting**.+
- Multible tags.+
- Summernote - WYSIWYG HTML Editor.+
- Image sizes.+
- Custom SEO post title and description (It's auto generated if not specified).+
- Comments can be allowed or forbidden.+
- Post have status. **You can add or remove status. Post status tells who can view/edit post in panel (published, private...)**.
- Excerpt of content. Could be empty.+
- Basic search for posts.
- You can likes/dislike post.
- Similar posts.
- Views statistics.
- Auto generate sitemap (RSS).
- You can select posts to be recommended or in front page.+
- You can upload images for the post you create/edit.+
- "On this topic" - Select a tag to show other post from the same topic.

2. Comments (CRUD)

- Anti-spam modul.
- Support nesting (can have replies to replies).
- Author comments have "Author" title.
- Basic search for comments.

3. Categories (CRUD)+

- Background image.
- Description.

4. Users+

- **Exend and use your User with traid.**
- **Add blog roles to your user model.**
- You can create new roles and **new permissions**.

5. Image manager+

- Browse and upload images.

6. Admin

- BAN option. You can ban users from the blog or from commenting.
- Very basic settings page.
- Logs. They are Errors, Alerts, Info, Visits and Bots visits.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
