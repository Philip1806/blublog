<?php

return [

    'version' => "2.0.0",
    'key' => "",
    /*
    |--------------------------------------------------------------------------
    | Very Important Settings
    |--------------------------------------------------------------------------
    |
    | Cache and prefix settings
    | !---> By default Blublog will searh for User model in \App\Models like is in Laravel 8
    | 
    */
    'setting_cache' => 3800,
    'blog_prefix' => "blog",  // example.com/blog - Location of the blog 
    'panel_prefix' => "panel", // example.com/panel - Location of the blog panel 
    'userModel' => "\\App\\Models\\User",

    /*
    |--------------------------------------------------------------------------
    | BLUblog Disk
    |--------------------------------------------------------------------------
    |
    | What disk from app/filesystems.php blublog should use for file uploads?
    | By default you need to create new disk with name blublog in that file.
    | !---> Blublog gets image url from the disk, so you need to make sure it provides the right link. 
    |
    */
    'files_disk' => "blublog",

    /*
    |--------------------------------------------------------------------------
    | Post Status Settings
    |--------------------------------------------------------------------------
    |
    | All settings starting with post_status must have the same number of elements.
    | Here you can add and remove post's status codes.
    | !---> Status "waits" and publish are reserved and must exist.
    |
    */
    'post_status' => array(
        'publish', // Do not remove publish
        'private',
        'co-op',
        'waits', // Do not remove waits
    ),

    /*
    |--------------------------------------------------------------------------
    | Post Access Codes
    |--------------------------------------------------------------------------
    |
    | 0 - public. Post with that status code can be seen by all.
    | 1 - Restricted. Only seen by mods and admin.
    | 2 - Private. Only seen by the author.
    | 3 - Custom. Blublog will check if user have permission "view-{your-post-status}"
    |
    */
    'post_status_access' => array(
        0, // By default its for publish post status
        2,
        2,
        1,
    ),

    /*
    |--------------------------------------------------------------------------
    | Post Edit Codes
    |--------------------------------------------------------------------------
    |
    | 0 - Can be edited by all users. 
    | 1 - Restricted. Post author, mods and admin can edit post.
    | 2 - Custom. Blublog will check if user have permission "edit-{your-post-status}"
    |
    */
    'post_status_edit' => array(
        1,
        1,
        0,
        1,
    ),

    /*
    |--------------------------------------------------------------------------
    | Post Revision Setting For Post Status.
    |--------------------------------------------------------------------------
    |
    | Set number of max revisions per post status.
    | - true - blublog will keep ALL revisions of post with that status.
    | - INT - The number of revisions that should be keeped for that status.
    | - false/0 - blublog will NOT keep revisions of post with that status.
    |
    */
    'post_status_revisions' => array(
        5,
        false,
        true,
        false,
    ),
    'similar-posts' => 6, // Number or similar post.
    'posts-from-user' => 5, // Used in section "My post" in Posts.
    'posts-per-page-with-status' => 5, // Used in public blog index page with service function withStatus().
    'posts-per-page-from-search' => 5, // Used in search on dashboard and public side.

    /*
    |--------------------------------------------------------------------------
    | Post Image Size Settings
    |--------------------------------------------------------------------------
    |
    | post_image_size:
    | false - Use original image.
    | 0 - Use first size
    | 1 - Use second size...
    | 
    */
    'post_image_size' => false,
    'image_quality' => 80,

    /*
    |--------------------------------------------------------------------------
    | Image Sizes Settings
    |--------------------------------------------------------------------------
    |
    | You can add as many you want. More sizes, more database records and more disk usage.
    | !---> Must have at least one for thumbnail
    | 
    */
    'image_sizes' => [
        [
            // First size
            'w' => 600,
            'h' => 350,
            'crop' => false,
        ],
        // Blublog uses the last image size as image thumbnail
        [
            // Second/last size
            'w' => 300,
            'h' => 175,
            'crop' => true,
        ],
    ],



    /**
     * Tags Settings
     */
    // If they have that permission, user can edit and delete tags created within that period. It's in hours.
    'moderate-tags-within' => 1,
    'posts-form-tag-per-page-with-status' => 5, // Used in list of posts with a tag
    'tags-per-page-from-search' => 8,
    /**
     * Category Settings
     */
    'posts-per-page-from-category' => 9,

    /**
     * Comments Settings
     */
    'spam-question' => "What is 2 * 6?",
    'spam-question-answer' => 12,
    // Users or guest who have approved comments will not wait for approving 
    'auto-approve' => true,
    // If logged in, comment will be public
    'approve-if-logged-in' => true,
    // Only logged in users can comment
    'only-logged-in-can-comment' => false,

    //Max post in rss feed
    'rss-limit' => 100,

    /**
     * IGNORE
     */
    // Array of permissions used only for installing blublog (DB seeding)
    'default_permissions' => array(
        array('is-admin', 1, 1, 'User is admin'),
        array('is-mod', 1, 1, 'User is moderator'),
        array('no-html', 0, 1, 'Only plain text output.'),
        array('restrict-html', 0, 1, 'Restrict html output'),
        array('create-posts', 1, 2, 'User can create posts'),
        array('edit-posts', 1, 2, 'User can edit posts'),
        array('delete-posts', 1, 2, 'User can delete posts'),
        array('edit-own-posts', 1, 2, 'User can edit own posts'),
        array('delete-own-posts', 1, 2, 'User can delete own posts'),
        array('post-stats', 1, 2, 'User can view posts stats'),
        array('own-post-stats', 1, 2, 'User can view stats for own posts'),
        array('wait-for-approve', 0, 2, 'Users posts wait for approval'),
        array('create-comments', 1, 3, 'User can create comments'),
        array('edit-comments', 1, 3, 'User can edit comments'),
        array('delete-comments', 1, 3, 'User can delete comments'),
        array('moderate-comments-from-own-posts', 1, 3, 'User can edit and hide comments from own post.'),
        array('edit-own-comments', 1, 3, 'User can edit own comments'),
        array('delete-own-comments', 1, 3, 'User can delete own comments'),
        array('approve-comments', 1, 3, 'User can approve comments'),
        array('create-tags', 1, 4, 'User can create tags'),
        array('edit-tags', 1, 4, 'User can edit tags'),
        array('delete-tags', 1, 4, 'User can delete tags'),
        array('moderate-tags-within_set_time', 1, 4, 'User can edit and delete tags within set time'),
        array('create-categories', 1, 5, 'User can create categories'),
        array('edit-categories', 1, 5, 'User can edit categories'),
        array('delete-categories', 1, 5, 'User can delete categories'),
        array('create-users', 1, 6, 'User can create users'),
        array('edit-profile', 1, 6, 'User can edit own profile'),
        array('edit-users', 1, 6, 'User can edit all users'),
        array('delete-users', 1, 6, 'User can delete users'),
        array('upload-files', 1, 7, 'User can upload files'),
        array('delete-own-files', 1, 7, 'User can delete own files'),
        array('delete-files', 1, 7, 'User can delete files'),
    ),

];
