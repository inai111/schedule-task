@php
    // dd(request()->is('order/create'));
@endphp
<x-layout title="Dashboard" class="">
    <x-slot:head>
        @vite(['resources/js/show_order.js'])
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
                                                <th>Vendor Name</th>
                                                <th>Category</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderDetails as $detail)
                                                <tr>
                                                    <td>{{ $detail->vendor->name }}</td>
                                                    <td>{{ $detail->vendor->category->name }}</td>
                                                    <td>{{ Illuminate\Support\Number::currency($detail->total,in:"IDR",locale:'id') }}</td>
                                                </tr>
                                            @endforeach
                                            @if($order->orderDetails->count()==0)
                                                <tr>
                                                    <td colspan="3" class="text-muted text-center">No Vendor Selected</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @if ($order->orderDetails)
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th>Rp.
                                                        {{ Illuminate\Support\Number::currency($order->total_price,in:"IDR",locale:'id') }}
                                                    </th>
                                                @else
                                                    <th colspan="2" class="text-right">Total</th>
                                                    <th>Rp. 0</th>
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
                                    @if (auth()->user()->role_id == 2)
                                        <div class="mb-3">
                                            <a href="{{ route('order.schedule.create', ['order' => $order->id]) }}"
                                                class="btn btn-success btn-sm px-3">Create New Schedule</a>
                                        </div>
                                    @endif
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
                                                <div class="tab-content">
                                                    @foreach ($order->schedules as $schedule)
                                                        <div @class([
                                                            'tab-pane text-left fade',
                                                            'show active' => $loop->index == 0,
                                                        ])
                                                            id="vert-tabs-{{ $loop->index }}" role="tabpanel"
                                                            aria-labelledby="vert-tabs-{{ $loop->index }}">
                                                            <div>
                                                                <h3>{{ $schedule->title }}</h3>
                                                                @if (\Carbon\Carbon::parse($schedule->date)->isFuture() && 
                                                                auth()->user()->role_id != 4)
                                                                    <div class="mb-3 text-right">
                                                                        <div class="btn-group">
                                                                            @can('update', $schedule)
                                                                                <a href="{{ route('schedule.edit', ['schedule' => $schedule->id]) }}"
                                                                                    class="btn btn-sm px-3 btn-primary">
                                                                                    <i class="far fa-edit"></i>
                                                                                </a>
                                                                            @endcan
                                                                            @can('delete', $schedule)
                                                                                <button type="button"
                                                                                    data-href="{{ route('schedule.delete', ['schedule' => $schedule->id]) }}"
                                                                                    class="btn btn-sm px-3 btn-danger triggerDelete">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            @endcan
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="d-flex mb-3 justify-content-between">
                                                                    <div class="d-flex flex-column">
                                                                        <strong>Meet Our Staff :</strong>
                                                                        <div>
                                                                            {{ $schedule->staff->name }}
                                                                            (<span class="text-muted">
                                                                                {{ $schedule->staff->role->description }}
                                                                            </span>)
                                                                            <br />
                                                                            <div class="text-muted"
                                                                                style="font-size:13px">
                                                                                {{ $schedule->staff->email ?? 'No Email' }}<br />
                                                                                {{ $schedule->staff->number_phone ?? 'No Phone Number' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Location --}}
                                                                    <div class="d-flex flex-column">
                                                                        <strong>Date and Location :</strong>
                                                                        <div class="text-muted">
                                                                            {{ date('D, d F Y', strtotime($schedule->date)) }}<br />
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
                                                                            <p class="text-muted">{{ $schedule->note }}
                                                                            </p>
                                                                        </div>
                                                                    @endif
                                                                    <div class="border shadows p-2"
                                                                        style="min-height: 120px">
                                                                        @foreach ($schedule->orderDetail as $od)
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <p>{{ $od->vendor->name }}</p>
                                                                            </div>
                                                                            <div class="col-8">
                                                                                <p><span class="text-muted"><b>Note : </b></span> {{ $od->note }}</p>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
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

        <div class="modal" id="modalConfirmation" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus data ini</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-dashboard>
</x-layout>
