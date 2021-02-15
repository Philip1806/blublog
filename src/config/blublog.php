<?php

return [

    'version' => "2.0.1",
    'key' => "",
    /*
        Very important settings
    */

    'blog_prefix' => "blog",
    'panel_prefix' => "panel",
    // By default Blublog will searh for User model in \App\Models like is in Laravel 8
    'userModel' => "\\App\\Models\\User",
    /*
        What disk from app/filesystems.php blublog should use for file uploads
        By default you need to create new disk with name blublog in that file.
    */
    'files_disk' => "blublog",

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
        1 - Restricted. Only seen by author, mods and admin.
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
