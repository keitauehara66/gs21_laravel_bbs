@extends('layouts.app')

@section('content')

<div class="card-header">Board</div>

<!-- 検索バー -->
<form class="form-inline d-flex justify-content-center md-form form-sm" action="{{ route('posts.search') }}" method="get">
    {{ csrf_field() }}
    <input class="form-control form-control-sm mt-2 w-75" type="text" placeholder="Search" aria-label="Search" name="search">
    <button class="btn btn-default mt-2" type="submit">
        <i class="fas fa-search" aria-hidden="true"></i>
    </button>
</form>

@isset($search_result)
<h6 class="form-inline d-flex justify-content-center md-form form-sm">{{ $search_result }}</h6>
@endisset

<div class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @foreach($posts as $post)
        <div class="card">
            <div class="card-body">
                <p class="card-title">{{ $post->title }}</p>
                <p class="card-title">
                    カテゴリー：
                    <a href="{{ route('categories.show', $post->category_id) }}">
                        {{ $post->category->category_name }}
                    </a>
                </p>
                <p class="card-title">
                    投稿者：
                    <a href="{{ route('users.show', $post->user_id) }}">
                        {{ $post->user->name }}
                    </a>                    
                </p>
                <p class="card-text">{{ $post->content }}</p>
                <a href="{{ route('posts.show', $post->id) }}" class="fas btn btn-primary btn-sm">詳細</a>
                @if($post->bookmarks()->where('user_id', Auth::id())->exists())
                    <form action="{{ route('unbookmarks', $post) }}" method="POST">
                        @csrf
                        <input type="submit" value="&#xf005;お気に入り解除：{{ $post->bookmarks()->count() }}"  class="fas btn btn-light btn-sm">
                    </form>
                @else
                    <form action="{{ route('bookmarks', $post) }}" method="POST">
                        @csrf
                        <input type="submit" value="&#xf005;お気に入り登録：{{ $post->bookmarks()->count() }}" class="fas btn btn-warning btn-sm">
                    </form>
                @endif
            </div>
        </div>
    @endforeach

    @if(isset($search_query))
        {{ $posts->appends(['search' => $search_query ])->links() }}
    @else
        {{ $posts->links() }}
    @endif
    
</div>

@endsection
