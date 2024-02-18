@extends('layouts.app')

@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        <div class="sm:col-span-2 mt-4">
            {{-- タブ --}}
            @include('users.navtabs')
            <div class="mt-4">
@if (isset($favorites))
    <ul class="list-none">
        @foreach ($favorites as $favorite)
            <li class="flex items-center gap-x-2 mb-4">
                {{-- お気に入り投稿のユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                <div class="avatar">
                    <div class="w-12 rounded">
                        {{-- お気に入りの投稿を作成したユーザのemail belongsTo(Usre::clss)--}}
                        <img src="{{ Gravatar::get($favorite->user->email) }}" alt="" />
                    </div>
                </div>
                <div>
                    <div>
                        {{-- ユーザ詳細ページへのリンク --}}
                        <p><a class="link link-hover text-info" href="{{ route('users.show', $favorite->user->id) }}">{{ $favorite->user->name }}</a></p>
                    </div>
                    <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($favorite->content)) !!}</p>
                        </div>
                    <div>
                        {{-- お気に入り削除ボタンのフォーム --}}
                        <form method="POST" action="{{ route('favorites.unfavorite', $favorite->id) }}">
                                    @csrf
                                    @method('DELETE')
                        <button type="submit" class="btn btn-success btn-sm normal-case"
                                     onclick="return confirm('ユーザ = {{ $favorite->user->name }} のお気に入りを削除します。よろしいですか？')">Unfavorite</button>
                        </form>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $favorites->links() }}
@endif
            </div>
        </div>
    </div>
@endsection