<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Micropost extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function favorite_users()    //以下お気に入り機能追記
    
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
    }

    
     // micropostIdで指定されたマイクロポストがお気に入りに未登録なら、登録する。
    public function bookmark($micropostId)
    {
        if ($this->exists_in_favorites($micropostId)) {
            return false;
        } else {
            $this->favorite_users()->attach($micropostId);
            return true;
        }
    }
    
    // 指定されたmicropostをこのユーザがbookmark中であるか調べる。bookmark中ならtrueを返す。
    
    public function exists_in_favorites($micropostId)
    {
        return $this->favorite_users()->where('micropost_id', $micropostId)->exists();
    }
    
    public function feed_favorites()
    {
        // このユーザがbookmark中のmicropostのidを取得して配列にする
        $$micropostIds = $this->favorites()->pluck('micropost.id')->toArray();
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('micropost_id', $$micropostIds);
    }
}