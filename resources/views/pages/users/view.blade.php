@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="container">
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
                                <div class="card-body" >
                                    <span class="text-center">{{ $lastLetter }}</span>
                                </div>
                                <ul class="list-group list-group-flush">
                        @endif
                                    <li class="list-group-item">{{ $kill->name }} ({{ $kill->level }}) - {{ $kill->count }}</li>
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
        </div>
    </div>
</div>
@endsection