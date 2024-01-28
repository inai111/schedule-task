<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                Weeding Organizer, Inc.
                <small class="float-right">Date: {{ date('d/m/Y', strtotime($transaction->created_at)) }}</small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            From
            <address>
                <strong>Weeding Organizer, Inc.</strong><br>
                Email: apps.woschedule@gmail.com
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            To
            <address>
                <strong>{{ $user->name }}</strong><br>
                {{ $user->address, $user->address }}<br>
                Phone: {{ $user->phone_number }}<br>
                Email: {{ $user->email }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice : {{ str_pad($transaction->slug, 5, '0', STR_PAD_LEFT) }}</b><br>
            <br>
            <b>Order ID:</b> {{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
            <b>Payment Due:</b> {{ date('d/m/Y 00:00:00', strtotime($transaction->exp_date)) }}<br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->transactionDetails as $detail)
                        <tr>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->product }}</td>
                            <td>{{ $detail->description }}</td>
                            <td>
                                {{ Illuminate\Support\Number::currency($detail->price, in: 'IDR', locale: 'id') }}
                            </td>
                        </tr>
                    @endforeach
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right"><b>Total : </b></th>
                        <th>
                            {{ Illuminate\Support\Number::currency($transaction->total, in: 'IDR', locale: 'id') }}
                        </th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-12">
            @can('update', $transaction)
                <button type="button" class="btn btn-success float-right midtransPay"><i class="far fa-credit-card"></i>
                    Submit
                    Payment
                </button>
            @endcan
            @if ($transaction->status == 'success')
                {{-- <h3 class="text-right">
                        <strong class="text-success">PAID</strong>
                    </h3> --}}
                <a class="btn btn-primary float-right rounded-pill"
                    href="{{ route('order.show', ['order' => $order->id]) }}">Order Details</a>
            @endif
        </div>
    </div>
</div>
