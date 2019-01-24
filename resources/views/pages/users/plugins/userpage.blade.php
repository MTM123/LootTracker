@section('lastkills')
    <div class="card">
        <div class="card-header">Last 5 kills</div>



                <ul class="list-group list-group-flush">
                    @foreach($user->getLastKills(5) as $id => $kill)
                        <li class="list-group-item">
                            <div>{{ $kill->monster->name }} ({{ $kill->monster->level }})</div>
                            @foreach($kill->items as $item)
                                @include('pages.users.plugins.item', $item)
                            @endforeach
                        </li>
                    @endforeach
                </ul>


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
