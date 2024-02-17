<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Micropost;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // このユーザが所有する投稿。　(Micropostモデルとの関係を定義)
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    // このユーザに関するモデルの件数をロードする。
    
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts', 'followings', 'followers', 'favorites']);
    }
    
    
    // このユーザーがフォロー中のユーザ。（Userモデルとの関係を定義）
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    
    // このユーザーをフォロー中のユーザ。　(Userモデルとの関係を定義)
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
    // userIdで指定されたユーザをフォローする
    // すでにフォローしているか,対象が自分自身かどうかの確認。
    public function follow($userId)
    {
        $exist = $this->is_following($userId);//モデルから取得したデータ
        $its_me = $this->id == $userId; //ログインユーザidと同じか（自分自身かどうか）
        
        if ($exist || $its_me) { //どちらかに該当したら
            return false;
        } else { //どちらでもなければフォローできる
            $this->followings()->attach($userId);//レコードへ挿入（保存）
        }
    }
    
    // $userIdで指定されたユーザをアンフォローする。
    public function unfollow($userId)
    {
        $exist = $this->is_following($userId);
        $its_me = $this->id == $userId;
        
        if ($exist && !$its_me) {
            $this->followings()->detach($userId);
            return true;
        } else {
            return false;
        }
    }
    
    // 指定された$userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    // ユーザとフォロー中ユーザの投稿に絞り込む
    
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    
    //～お気に入り機能　以下追記
    // このユーザーがお気に入りに入れている内容(マイクロポスト）。（Micropostモデルとの関係を定義）
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id');
    }
    
     // micropostIdで指定されたマイクロポストがお気に入りに未登録なら、登録する。
    public function bookmark($micropostId)
    {
        if ($this->exists_in_favorites($micropostId)) {
            return false;
        } else {
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    // 指定されたmicropostをこのユーザがbookmark中であるか調べる。bookmark中ならtrueを返す。
    
    public function exists_in_favorites($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
    
    public function feed_favorites()
    {
        // このユーザがbookmark中のmicropostのidを取得して配列にする
        $micropostIds = $this->favorites()->pluck('id')->toArray();
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('id', $$micropostIds);
    }
    
    // microostIdで指定されたマイクロポストをお気に入りから削除する。
    
    public function unbookmark($micropostId)
    {
        //すでにブックマークされているか確認する
        if ($this->exists_in_favorites($micropostId)) {
            return $this->favorites()->detach($micropostId);
        } else {
            return false;
        }
    }
    /*ChatGpt案
    public function remove_from_bookmark($micropostId)
    {
    // すでにブックマークされているか確認する
    if ($this->exists_in_favorites($micropostId)) {
        // 実際に削除を試み、結果にかかわらず true を返す（削除が試みられたことを意味する）
        $this->favorites()->detach($micropostId);
        return true;
    } else {
        return false;
    }
    }*/
}
