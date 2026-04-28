<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Database Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 20px; }
        h1 { font-size: 18px; text-align: center; color: #420d65; }
        h2 { font-size: 14px; margin: 20px 0 10px; background: #f0f0f0; padding: 6px; border-left: 4px solid #420d65; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 6px 4px; text-align: left; vertical-align: top; }
        th { background: #420d65; color: white; }
        footer { text-align: center; font-size: 8px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
<h1>Full Database Export</h1>
<p style="text-align:center">Generated: {{ now()->format('Y-m-d H:i:s') }}</p>

@foreach(['Users' => $users, 'Products' => $products, 'Orders' => $orders, 'Order Items' => $orderGoods, 'Families' => $families, 'Comments' => $comments, 'Auctions' => $auctions, 'Bids' => $bids] as $title => $collection)
    @if($collection->count())
        <div>
            <h2>{{ $title }} ({{ $collection->count() }} records)</h2>
            <table>
                <thead>
                <tr>
                    @foreach(array_keys($collection->first()->toArray()) as $col)
                        <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($collection as $row)
                    <tr>
                        @foreach($row->toArray() as $value)
                            <td>@if(is_array($value) || is_object($value)) {{ json_encode($value) }} @else {{ $value ?? '-' }} @endif</td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endforeach
<footer>Exported from Admin Panel | {{ now() }}</footer>
</body>
</html>
