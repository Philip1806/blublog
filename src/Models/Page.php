<?php

namespace Blublog\Blublog\Models;

use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    protected $table = 'blublog_pages';

    public static function handle_request($page,$request)
    {
        //DO NOT like mass assignment...
        if(!$page){
            Session::flash('error', __('blublog.404'));
            Log::add($request, "error", __('blublog.404') );
            return redirect()->route('blublog.pages.index');
        }
        if($request->slug){
            $page->slug = $request->slug;
        } else {
            $page->slug = Post::makeslug($request->title);
        }
        $page->title = $request->title;
        $page->img = $request->img;
        $page->descr = $request->descr;
        $page->tags = $request->tags;
        $page->content = $request->content;
        if($request->public){
            $page->public = true;
        } else {
            $page->public =false;
        }
        if($request->sidebar){
            $page->sidebar = true;
        } else {
            $page->sidebar =false;
        }
        return $page;
    }
}
