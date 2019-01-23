@section('lastkills')
    <div class="card">
        <div class="card-header">Last 5 kills</div>

        <div class="card-body">
            <div class="container">
                <ul class="list-group list-group-flush">
                    @foreach($user->getLastKills(5) as $id => $kill)
                        <li class="list-group-item">
                            {{ $kill->monster->name }} ({{ $kill->monster->level }})
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('filter')
    <div class="card">
        <div class="card-header">Filter</div>

        <div class="card-body">
            <div class="container">

            </div>
        </div>
    </div>
@endsection