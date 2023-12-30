<x-layout title="Dashboard" class="">
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            @if ($user->role_id == 4)
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Active Order</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>Order Id</th>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>More</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($orders->count() > 0)
                                                    @foreach ($orders as $order)
                                                        <tr>
                                                            <td>{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                            <td>
                                                                <img src="{{ asset('storage/ico.png') }}"
                                                                    alt="Product 1" class="img-circle img-size-32 mr-2">
                                                                Weeding Organizer
                                                            </td>
                                                            <td>Rp. {{ number_format($order->total_price) }}</td>
                                                            <td>
                                                                @switch($order->order_status)
                                                                    @case('success')
                                                                        <span
                                                                            class="badge badge-pill
                                                                        badge-success">Success</span>
                                                                    @break

                                                                    @case('ongoing')
                                                                        <span
                                                                            class="badge badge-pill
                                                                        badge-primary">On-Going</span>
                                                                    @break

                                                                    @default
                                                                        <span
                                                                            class="badge badge-pill
                                                                        badge-warning">Pending</span>
                                                                @endswitch
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('order.show', ['order' => $order->id]) }}"
                                                                    class="text-muted">
                                                                    <i class="fas fa-search"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">No Active
                                                            Order</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <a href="{{ route('order.create') }}"
                                                            class="btn btn-sm btn-primary">
                                                            Order Service Now
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Active Payment</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>Order Id</th>
                                                    <th>Installment</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                    <th>Pay Before</th>
                                                    <th>More</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($orders->count() > 0 &&
                                                $orders->first()->installments->count() > 0)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ asset('storage/ico.png') }}" alt="Product 1"
                                                                class="img-circle img-size-32 mr-2">
                                                            Weeding Organizer
                                                        </td>
                                                        <td>$13 USD</td>
                                                        <td>
                                                            <small class="text-success mr-1">
                                                                <i class="fas fa-arrow-up"></i>
                                                                12%
                                                            </small>
                                                            12,000 Sold
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-muted">
                                                                <i class="fas fa-search"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No Active
                                                            Installment</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if ($user->role_id == 2)
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Unreported Schedule</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Pelanggan</th>
                                                    <th>Meet Date</th>
                                                    <th>Location</th>
                                                    <th>Title</th>
                                                    <th>More</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($reports->count() > 0)
                                                    @foreach ($reports as $schedule)
                                                        <tr>
                                                            <td>
                                                                <img src="{{ asset('storage/ico.png') }}"
                                                                    alt="Product 1" class="img-circle img-size-32 mr-2">
                                                            </td>
                                                            <td>
                                                                {{ $schedule->order->user->name }}<br>
                                                                <span class="text-muted">
                                                                    {{ $schedule->order->user->phone_number }}
                                                                </span>

                                                            </td>
                                                            <td>{{ $schedule->date }}</td>
                                                            <td>
                                                                <span @class(['text-muted' => empty($schedule->location)])>
                                                                    {{ $schedule->location ?? 'No Location' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span @class(['text-muted' => empty($schedule->title)])>
                                                                    {{ $schedule->title ?? 'No Title' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('schedule.report.create', [
                                                                    'schedule' => $schedule->id,
                                                                ]) }}"
                                                                    class="text-primary">
                                                                    <i class="fas fa-pen"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6">
                                                            <p class="text-muted text-center">All Schedule Reported</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            @if ($scheduleList->count() > 0)
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            {{ $scheduleList->links() }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Schedule</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <h1>{{ date('D, d M Y') }}</h1>
                                        @if ($schedule)
                                            <div class="d-flex">
                                                <p class="d-flex flex-column">
                                                    <span class="text-bold text-lg text-success">Upcomming</span>
                                                    <span>{{ $schedule->title }}</span>
                                                </p>
                                                <p class="ml-auto d-flex flex-column text-right">
                                                    <span class="">
                                                        Order Id : <a
                                                            href="{{ route('order.show', ['order' => $schedule->order_id]) }}">
                                                            #{{ str_pad($schedule->order_id, 5, 0, STR_PAD_LEFT) }}
                                                        </a>
                                                    </span>
                                                    <span class="">
                                                        <i class="fas fa-calendar"></i>
                                                        {{ date('D, d M Y', strtotime($schedule->date)) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <!-- /.d-flex -->
                                        @else
                                            <div class="row">
                                                <div class="col">
                                                    <p class="text-muted">No Schedule Available</p>
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($schedules) && $schedules->count() > 0)
                                            <table class="table table-striped table-valign-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Meet Date</th>
                                                        <th>Title</th>
                                                        <th>Nama Pelanggan</th>
                                                        <th>Location</th>
                                                        <th>Order Id</th>
                                                        <th>More</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($scheduleList->count() > 0)
                                                        @foreach ($scheduleList as $schedule)
                                                            <tr>
                                                                <td>{{ $schedule->date }}</td>
                                                                <td>
                                                                    <span @class(['text-muted' => empty($schedule->title)])>
                                                                        {{ $schedule->title ?? 'No Title' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    {{ $schedule->order->user->name }}<br>
                                                                    <span class="text-muted">
                                                                        {{ $schedule->order->user->phone_number }}
                                                                    </span>

                                                                </td>
                                                                <td>
                                                                    <span @class(['text-muted' =>
                                                                    empty($schedule->location)])>
                                                                        {{ $schedule->location ?? 'No Location' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="{{route('order.show',
                                                                    ['order'=>$schedule->order_id])}}">
                                                                        #{{str_pad($schedule->order_id,5,0,
                                                                        STR_PAD_LEFT)}}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('schedule.edit', [
                                                                        'schedule' => $schedule->id,
                                                                    ]) }}"
                                                                        class="text-primary">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">No
                                                                Schedules</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            {{ $schedules->links() }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->

                            @if ($user->role_id == 1)
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">Report</h3>
                                    </div>
                                    <div class="card-body">
                                        @if ($report)
                                            <div
                                                class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                                <p class="text-success text-xl">
                                                    <i class="ion ion-ios-refresh-empty"></i>
                                                </p>
                                                <p class="d-flex flex-column text-right">
                                                    <span class="font-weight-bold">
                                                        <i class="ion ion-android-arrow-up text-success"></i> 12%
                                                    </span>
                                                    <span class="text-muted">CONVERSION RATE</span>
                                                </p>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col">
                                                    <p class="text-muted">No Report Available</p>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- /.d-flex -->
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.col-md-6 -->
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
