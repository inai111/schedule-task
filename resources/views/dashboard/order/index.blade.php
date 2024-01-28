<x-layout title="Order List">
    <x-dashboard>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Lists</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active">Order</li>
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
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Status</th>
                                <th>Total Installment</th>
                                <th>Plan Date</th>
                                <th>Total Price</th>
                                <th>More</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() > 0)
                                @foreach ($orders as $order)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ str_pad($order->id, 4, 0, STR_PAD_LEFT) }}</td>
                                        <td>
                                            <span
                                                @class([
                                                    'badge',
                                                    'badge-pill',
                                                    'badge-success' => $order->order_status == 'success',
                                                    'badge-primary' => $order->order_status == 'ongoing',
                                                    'badge-warning' => $order->order_status == 'pending',
                                                ])>{{ ucwords($order->order_status) }}</span>
                                        </td>
                                        <td>{{ $order->installments_total ?? 0 }}</td>
                                        <td>{{ date('D, d-m-Y', strtotime($order->plan_date)) }}</td>
                                        <td>Rp. {{ number_format($order->total_price) ?? 0 }}</td>
                                        <td>
                                            <a href="{{ route('order.show', ['order' => $order->id]) }}"
                                                class="text-muted">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body">
                                        <td colspan="6">
                                            @if ($order->orderDetails->count() > 0)
                                                <table class="table table-borderless">
                                                    @foreach ($order->orderDetails as $detail)
                                                        <tr>
                                                            <td>{{ $detail->vendor->name }}</td>
                                                            <td>{{ $detail->vendor->category->name }}</td>
                                                            <td>{{Illuminate\Support\Number::currency($detail->total,in:"IDR",locale:'id')}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="2">Total</th>
                                                            <th>{{Illuminate\Support\Number::currency($order->total_price,in:"IDR",locale:'id')}}</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            @else
                                                <p>
                                                    No Vendor Selected
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">
                                        <p class="text-muted">No Order Available</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
    </x-dashboard>
</x-layout>
