<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'blublog_roles';
    protected $guarded = ['is_admin', 'is_mod'];
    public $timestamps = false;

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    public static function get_roles_in_array()
    {
        $roles = Role::get();
        $data = array();
        foreach($roles as $role){
            $data[$role->id] = $role->name;

        }
        return $data;
    }
    public static function add_new($request)
    {
        unset($request['role_id']);
        $keys = array_keys($request);
        for ($i= 0; $i < count($request); $i++){
            if($request[$keys[$i]] == 'on'){
                $request[$keys[$i]] = "1";
            }
        }
        dd($request);
        $role = new Role($request);
        $role->save();
        return true;
    }
    public static function edit($role,$request)
    {
        unset($request['role_id']);
        unset($request['_token']);

        $keys = array_keys($request);
        for ($i= 0; $i < count($request); $i++){
            if($request[$keys[$i]] == 'on'){
                $role->{$keys[$i]} = "1";
            } else {
                $role->{$keys[$i]} = "0";
            }
        }

        $role->save();
        return true;
    }

}
