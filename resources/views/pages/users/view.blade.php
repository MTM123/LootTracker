@extends('layouts.app')
@include('pages.users.plugins.userpage')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Monsters</div>

                <div class="card-body">
                        <div class="card-columns">
                    @php
                        $lastLetter = "";
                        $new = false;
                        $list = $user->sortedKills();
                    @endphp

                    @foreach($list as $id => $kill)
                        @php
                            $currentLetter = substr($kill->name, 0, 1)
                        @endphp

                        @if($lastLetter != $currentLetter)
                            @php
                                $lastLetter = $currentLetter;
                                $new = true;
                            @endphp
                        @endif

                        @if($new == true)
                            <div class="card user-monster-kill">
                                <div class="card-header text-center" >
                                    <strong>{{ $lastLetter }}</strong>
                                </div>
                                <ul class="list-group list-group-flush">
                        @endif
                                    <li class="list-group-item" data-monsterid="{{ $kill->monster->id }}">
                                        <div class="fav-btn">
                                            <i class="favme fa fa-star" data-mid="{{ $kill->id }}" aria-hidden="true"></i>
                                        </div>
                                        <span class="monster-name">{{ $kill->name }} ({{ $kill->level }})</span> <span class="badge badge-primary float-right">{{ $kill->count }}</span>
                                    </li>
                        @if(@substr($list[$id+1]->name, 0, 1) != $currentLetter)
                                </ul>
                            </div>

                        @endif

                        @php
                            $new = false;
                        @endphp
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 monster-side-menu">
            @yield('filter')
            @yield('lastkills')
        </div>

    </div>
</div>
@endsection