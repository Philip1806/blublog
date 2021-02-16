<?php

return [

    'version' => "2.0.1",
    'key' => "",
    /*
        Very important settings
    */

    'blog_prefix' => "blog",
    'panel_prefix' => "panel",
    // >>>>> By default Blublog will searh for User model in \App\Models like is in Laravel 8
    'userModel' => "\\App\\Models\\User",
    /*
        What disk from app/filesystems.php blublog should use for file uploads
        By default you need to create new disk with name blublog in that file.
    */
    'files_disk' => "blublog",



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
        array('ban-from-comments', 1, 3, 'User can ban others from making comments'),
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

    /*
        Post Settings
    */
    // !!!! All settings starting with post_status must have the same number of elements.
    'post_status' => array(
        'publish',
        'private',
        'co-op',
        'waits',
    ),
    /* 
        Access codes:
        0 - public. Post with that status code can be seen by all.
        1 - Restricted. Only seen by mods and admin.
        2 - Private. Only seen by the author.
        3 - Custom. Blublog will check if user have permission "view-{your-post-status}"

    */
    'post_status_access' => array(
        0, // By default its for publish post status
        2,
        2,
        1,
    ),
    /* 
        Edit codes:
        0 - Can be edited by all users. 
        1 - Restricted. Post author, mods and admin can edit post.
        2 - Custom. Blublog will check if user have permission "edit-{your-post-status}"
    */
    'post_status_edit' => array(
        1,
        1,
        0,
        1,
    ),
    'post_status_revisions' => array(
        true,
        false,
        true,
        false,
    ),





    /*
        Image Settings
    */

    // 0 - Use original image. 1 - Use first size (Medium) 2 - Use second size (thumbnail)...
    'post_image_size' => 0,

    'image_quality' => 80,

    // Image sizes. Names does not matter.
    'image_sizes' => [
        'Medium' => [

            'w' => 600,
            'h' => 350,
            'crop' => false,
        ],
        // Blublog uses the last image size as image thumbnail
        'thumbnail' => [

            'w' => 300,
            'h' => 175,
            'crop' => true,
        ],
    ],

];
