@extends('layouts.app')

@section('content')
    @if (isset($favorites))
        <ul class="list-none">
            @foreach ($favorites as $favorite)
                <li class="flex items-center gap-x-2 mb-4">
                    {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="{{ Gravatar::get($user->email) }}" alt="" />
                        </div>
                    </div>
                    <div>
                        <div>
                            {{ $user->name }}
                        </div>
                        <div>
                            {{-- ユーザ詳細ページへのリンク --}}
                            <p><a class="link link-hover text-info" href="{{ route('users.show', $user->id) }}">View profile</a></p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $favorites->links() }}
    @endif
@endsection