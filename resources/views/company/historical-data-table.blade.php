<div class="card-body">
    @if ($prices && sizeOf($prices))
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Open</th>
                    <th scope="col">High</th>
                    <th scope="col">Low</th>
                    <th scope="col">Close</th>
                    <th scope="col">Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prices as $price)
                    <tr>
                        <td>{{ date('Y-m-d', $price['date']) }}</td>
                        <td>{{ number_format($price['open'], 4) }}</td>
                        <td>{{ number_format($price['high'], 4) }}</td>
                        <td>{{ number_format($price['low'], 4) }}</td>
                        <td>{{ number_format($price['close'], 4) }}</td>
                        <td>{{ $price['volume'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning" role="alert">
            No Historical Data Found !!!
        </div>
    @endif
</div>





