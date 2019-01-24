@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @guest
                        Please log in!
                    @else
                        You are logged in!<br>
                        Your API Token: {{ Auth::user()->api_token }}

                        @if (!empty(Auth::user()->key))
                            <p>Your shareable link: <a href="{{ route('users.view', ['key' => Auth::user()->key]) }}">{{ route('users.view', ['key' => Auth::user()->key]) }}</a></p>
                        @endif
                        <form method="post" action="{{ route('generate.key') }}">
                            @csrf
                            <button class="btn btn-success">
                                <span>{{ __('Generate New Key') }}</span>
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
