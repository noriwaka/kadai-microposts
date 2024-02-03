<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        // ユーザ一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users,
        ]);
    }
    
    public function show($id)
    {
        //idの値でユーザ検索
        $user = User::findOrFail($id);
        
        
        //ユーザに関連するモデルの件数をロードする
        $user->loadRelationshipCounts();
        
        
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);
        
        
        //ユーザ詳細ビューで表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }
}