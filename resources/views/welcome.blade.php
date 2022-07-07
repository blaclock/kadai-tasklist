@extends('layouts.app')

@section('content')
    <div class="center jumbotron">
        <div class="text-center">
            @if (Auth::check())
                {{ Auth::user()->name }}
                {!! link_to_route('logout.get', 'Log out now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
                @include('tasks.index')
            @else
                <h1>Welcome to the Tasklists</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
                {{-- ログインページへのリンク --}}
                {!! link_to_route('login', 'Log in now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            @endif
        </div>
    </div>
@endsection