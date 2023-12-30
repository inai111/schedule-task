@php
    // dd(request()->is('order/create'));
@endphp
<x-layout title="Dashboard" class="">
    <x-slot:head>
        @vite(['/resources/js/create_order.js'])
    </x-slot>
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Detail</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-6 table-responsive">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        Order Details
                                        <span @class([
                                            'badge',
                                            'badge-pill',
                                            'badge-success' => $order->order_status == 'success',
                                            'badge-primary' => $order->order_status == 'ongoing',
                                            'badge-warning' => $order->order_status == 'pending',
                                        ])>{{ ucwords($order->order_status) }}</span>
                                    </h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Description</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Down Payment Weeding Organizer</td>
                                                <td></td>
                                                <td>Rp. {{ number_format(5000000) }}</td>
                                            </tr>
                                            @foreach ($order->orderDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail->qty }}</td>
                                                    <td>{{ $detail->product }}</td>
                                                    <td>{{ $detail->description }}</td>
                                                    <td>Rp. {{ number_format($detail->sub_total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @if ($order->order_details)
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th>Rp.
                                                        {{ number_format($order->order_details->sum(fn($dtl) => $dtl->total_price)) }}
                                                    </th>
                                                @else
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th>Rp. {{ number_format(5000000) }}</th>
                                                @endif
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 table-responsive">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Transaction</h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Transaction Id</th>
                                                <th>Expired Date</th>
                                                <th>Status</th>
                                                <th>More</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transactions as $trans)
                                                <tr>
                                                    <td>{{ str_pad($trans->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                    <td>{{ date('d/m/Y 00:00:00', strtotime($trans->exp_date)) }}</td>
                                                    <td><span
                                                            @class([
                                                                'badge',
                                                                'badge-pill',
                                                                'badge-success' => $trans->status == 'success',
                                                                'badge-warning' => $trans->status == 'waiting',
                                                            ])>{{ ucwords($trans->status) }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('transaction.show', ['transaction' => $trans->id]) }}"
                                                            class="text-muted">
                                                            <i class="fas fa-search"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- @foreach ($transaction->transaction_details as $detail)
                                        <tr>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ $detail->product }}</td>
                                            <td>{{ $detail->description }}</td>
                                            <td>Rp. {{ number_format($detail->sub_total) }}</td>
                                        </tr>
                                    @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 table-responsive">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Schedule</h5>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5 col-sm-3">
                                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab"
                                                role="tablist" aria-orientation="vertical">
                                                @if ($order->schedules->count() > 0)
                                                    @foreach ($order->schedules as $schedule)
                                                        <a @class(['nav-link', 'active' => $loop->index == 0]) id="tab-{{ $loop->index }}"
                                                            data-toggle="pill"
                                                            href="#vert-tabs-{{ $loop->index }}"role="tab"
                                                            aria-controls="vert-tabs-home" aria-selected="true">
                                                            {{ date('d F Y', strtotime($schedule->date)) }}
                                                            @if (\Carbon\Carbon::parse($schedule->date)->isFuture())
                                                                <span class="float-right badge bg-success">
                                                                    Upcoming
                                                                </span>
                                                            @endif
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-7 col-sm-9">
                                            @if ($order->schedules->count() > 0)
                                                @foreach ($order->schedules as $schedule)
                                                    <div @class([
                                                        'tab-pane text-left fade',
                                                        'show active' => $loop->index == 0,
                                                    ])
                                                        id="vert-tabs-{{ $loop->index }}" role="tabpanel"
                                                        aria-labelledby="vert-tabs-{{ $loop->index }}">
                                                        <div>
                                                            <h3>{{ $schedule->title }}</h3>

                                                            <div class="d-flex mb-3 justify-content-between">
                                                                <div class="d-flex flex-column">
                                                                    <strong>Meet Our Staff :</strong>
                                                                    <div>
                                                                        {{ $schedule->staff->name }}
                                                                        (<span class="text-muted">
                                                                            {{ $schedule->staff->role->description }}
                                                                        </span>)
                                                                        <br />
                                                                        <div class="text-muted" style="font-size:13px">
                                                                            {{ $schedule->staff->email ?? 'No Email' }}<br />
                                                                            {{ $schedule->staff->number_phone ?? 'No Phone Number' }}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Location --}}
                                                                <div class="d-flex flex-column">
                                                                    <strong>Date and Location :</strong>
                                                                    <div class="text-muted">
                                                                        {{ date('D, d F Y',strtotime($schedule->date)) }}<br />
                                                                        @if ($schedule->location)
                                                                            {{ $schedule->location }}<br />
                                                                        @else
                                                                            <span class="badge bg-warning">
                                                                                Waiting Our Staff
                                                                            </span><br />
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                @if ($schedule->note)
                                                                    <div class="callout callout-info mb-3">
                                                                        <h5>Note:</h5>
                                                                        <p class="text-muted">{{ $schedule->note }}</p>
                                                                    </div>
                                                                @endif
                                                                <div class="border shadows p-2" style="min-height: 120px">
                                                                    @if ($schedule->orderDetail)
                                                                    <p>{{ $schedule->orderDetails->note }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- ./card-body -->
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </x-dashboard>
</x-layout>
