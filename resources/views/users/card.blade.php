<div class="card border border-base-300">
    <div class="card-body bg-base-200 text-4x1">
        <h2 class="card-title">{{ $user->name }}</h2>
    </div>
    <figure>
        {{-- ユーザのメールをもとにGravatarを取得して表示 --}}
        <img src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
    </figure>
</div>