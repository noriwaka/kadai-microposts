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
    
    // ユーザのフォロー一覧ページを表示する
    
    public function followings($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);
        
        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
            ]);
    }
    
    // ユーザのフォロワー一覧ページを表示する
    
    public function followers($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        
        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);
        
        // フォロワー一覧をビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
            ]);
    }
    
    
    // ～お気に入り機能～
    
    
    // お気に入りを一覧表示するページ
    public function favorites($id)
    {
        $user = User::findOrFail($id);
        
        $user->loadRelationshipCounts();
        
        $favorites = $user->favorites()->paginate(10);
        
        return view('users.favorites', [
            'favorites' => $favorites,
            ]);
    }
}