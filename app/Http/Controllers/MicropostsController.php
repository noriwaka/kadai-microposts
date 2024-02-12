<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Modeles\Microposts;

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザとフォロー中ユーザの投稿の一覧を作成日時の降順で取得
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
 
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|max:255',
        ]);
        
        //送信されたデータをデータベースに作成
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);
        
        return back();
    }
    
    public function destroy($id)
    {
        $micropost = \App\Models\Micropost::findOrFail($id);
        
        //認証済みユーザ（閲覧者）であれば削除出来る
        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            return back()
                ->with('success','Delete Successful');
        }
        
        return back()
            ->with('Delete Failed');
    }
}