@section('lastkills')
    <div class="card last-5kills">
        <div class="card-header">Last 5 kills</div>
                <ul class="list-group list-group-flush">
                    @foreach($user->getLastKills(5) as $id => $kill)
                        <li class="list-group-item">
                            <div class="monster-name">{{ $kill->monster->name }} ({{ $kill->monster->level }})</div>
                            <div class="monster-drops">
                            @foreach($kill->items as $item)
                                @include('pages.users.plugins.item', $item)
                            @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
    </div>
@endsection

@section('filter')
    <div class="card filter-get-drops">
        <div class="card-header">Filter <button type="button" style="display: none;" class="btn-clear-filter btn btn-sm btn-outline-danger float-right"><i class="fa fa-times" aria-hidden="true"></i></button></div>

        <div class="card-body">
            <ul class="filter-selected-list list-group list-group-flush">
                Select monster!
            </ul>
            <button type="button" data-url="{{ route('users.drops', ['key' => $user->key]) }}" class="btn-go-to-drops btn btn-outline-primary btn-block">Get drops</button>
        </div>
    </div>
@endsection
