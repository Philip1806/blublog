<?php

namespace Blublog\Blublog\Traits;

use Blublog\Blublog\Models\File;
use Blublog\Blublog\Models\Post;
use Blublog\Blublog\Models\Role;

trait ManageBlublog
{

    public function blublogPosts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    public function blublogImages()
    {
        return $this->hasMany(File::class, 'user_id');
    }
    public function blublogRoles()
    {
        return $this->belongsToMany(Role::class, 'blublog_roles_users', 'user_id', 'role_id');
    }
}
