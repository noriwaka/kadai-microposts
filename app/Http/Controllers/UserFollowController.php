<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    //ユーザーをフォローするアクション↓
    
    public function store($id)
    {
        // ログインユーザが、idのユーザーをフォローする
        \Auth::user()->follow($id);
            // 前のURLへリダイレクトさせる
            return back();
        }
        
        // ユーザをアンフォローする
        public function destroy($id)
        {
            \Auth::user()->unfollow($id);
            return back();
        }
}
