<?php

return [
    // LOW LEVEL SETTING
    'version' => "0.9.2 ALPHA",
    'blog_prefix' => "blog",
    'panel_prefix' => "panel",
    'files_disk' => "blublog",

    // Int Settings
    'img_height' => 600,
    'img_height_type' => "int",
    'img_width' => 350,
    'img_width_type' => "int",
    'img_quality' => 60,
    'img_quality_type' => "int",
    'setting_cache' => 28800,
    'setting_cache_type' => "int",
    'index_posts_per_page' => "10",
    'index_posts_per_page_type' => "int",
    'category_posts_per_page' => "10",
    'category_posts_per_page_type' => "int",
    'tags_posts_per_page' => "10",
    'tags_posts_per_page_type' => "int",
    'number_main_tag_posts' => "4",
    'number_main_tag_posts_type' => "int",
    'number_of_similar_post' => "10",
    'number_of_similar_post_type' => "int",
    'max_unaproved_comments' => "10",
    'max_unaproved_comments_type' => "int",

    // String Settings
    'site_name' => "BLUblog",
    'site_name_type' => "string",
    'site_descr' => "This is simple blog with BLUblog!",
    'site_descr_type' => "string",
    'maintenance_massage' => "Site is under maintenance.",
    'maintenance_massage_type' => "string",
    'theme' => "blublog",
    'theme_type' => "string",
    'ignore_ip' => "",
    'ignore_ip_type' => "string",
    'date_format' => "d.m.Y",
    'date_format_type' => "string",

    'comment_spam_question' => "",
    'comment_spam_question_type' => "string",
    'comment_spam_question_answer' => "",
    'comment_spam_question_answer_type' => "string",

    // Text Settings
    'footer_html' => "Based on Bootstrap. Icons from Font Awesome. Web fonts from Google.<br>Blog made with <a href='https://blublog.info'>BLUblog</a>.",
    'footer_html_type' => "text",
    'head_html' => "<h1>Welcome to BLUblog!</h1><p>This is info panel for front page that can be edited or removed.<p>",
    'head_html_type' => "text",
    'sidebar_html' => "<div class='alert alert-primary' role='alert'>This is sidebar html area. Could be edited or removed.</div>",
    'sidebar_html_type' => "text",
    'global_header_html' => "",
    'global_header_html_type' => "text",
    'global_footer_html' => "",
    'global_footer_html_type' => "text",
    'post_header_html' => "",
    'post_header_html_type' => "text",
    'post_additional_html' => "",
    'post_additional_html_type' => "text",
    'author_message_html' => "",
    'author_message_html_type' => "text",
    'moderator_message_html' => "",
    'moderator_message_html_type' => "text",
    'menu_link_template' => '<li class="nav-item"><a class="nav-link" href="((LINK))">((LABEL))</a></li>',
    'menu_link_template_type' => "text",
    'menu_dropdown_template' => '<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="((LINK))" id="navbarDropdown"
    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">((LABEL))</a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    ((SUBLINKS))
    </div></li>',
    'menu_dropdown_template_type' => "text",
    'menu_dropdown_link_template' => '<a class="dropdown-item" href="((LINK))">((LABEL))</a>',
    'menu_dropdown_link_template_type' => "text",

    // Bool Settings
    'post_editor' => true,
    'post_editor_type' => "bool",
    'disable_comments_modul' => false,
    'disable_comments_modul_type' => "bool",
    'approve_comments_from_users_with_approved_comments' => true,
    'approve_comments_from_users_with_approved_comments_type' => "bool",
    'no_ratings' => false,
    'no_ratings_type' => "bool",
    'disable_search_modul' => false,
    'disable_search_modul_type' => "bool",
    'main_menu_name' => "Main",
    'main_menu_name_type' => "string",
    'front_page_posts_only' => false,
    'front_page_posts_only_type' => "bool",
    'add_front_page_posts' => false,
    'add_front_page_posts_type' => "bool",
    'under_attack' => false,
    'under_attack_type' => "bool",
    'comment_ask_question' => false,
    'comment_ask_question_type' => "bool",
];
