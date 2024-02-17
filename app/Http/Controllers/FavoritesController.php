<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use App\Models\Micropost;

class FavoritesController extends Controller
{

    // 投稿をbookmarkに登録する
    
    public function store($micropostId)//miceoposts(Model)のidカラム
    {
        // ログインユーザが、idのポストをお気に入り登録する
        \Auth::user()->bookmark($micropostId);
            // 前のURLへリダイレクトさせる
            return back();
        }
        
    // 投稿をbookmarkから削除する
    public function destroy($micropostId)
    {
        \Auth::user()->unbookmark($micropostId);
        return back();
    }
}
