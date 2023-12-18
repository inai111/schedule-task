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
                                <li class="breadcrumb-item"><a href="/">Order</a></li>
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
                                        Order Detail
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
                                                    <td>{{ date('d/m/Y 00:00:00',strtotime($trans->exp_date)) }}</td>
                                                    <td><span @class([
                                                        'badge',
                                                        'badge-pill',
                                                        'badge-success' => $trans->status == 'success',
                                                        'badge-warning' => $trans->status == 'waiting',
                                                    ])>{{ ucwords($trans->status) }}</span></td>
                                                    <td>
                                                        <a href="{{route('transaction.show',['transaction'=>$trans->id])}}"
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
                                                <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill"
                                                    href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home"
                                                    aria-selected="true">Home</a>
                                                <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill"
                                                    href="#vert-tabs-profile" role="tab"
                                                    aria-controls="vert-tabs-profile" aria-selected="false">Profile</a>
                                                <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill"
                                                    href="#vert-tabs-messages" role="tab"
                                                    aria-controls="vert-tabs-messages"
                                                    aria-selected="false">Messages</a>
                                                <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill"
                                                    href="#vert-tabs-settings" role="tab"
                                                    aria-controls="vert-tabs-settings"
                                                    aria-selected="false">Settings</a>
                                            </div>
                                        </div>
                                        <div class="col-7 col-sm-9">
                                            <div class="tab-content" id="vert-tabs-tabContent">
                                                <div class="tab-pane text-left fade show active" id="vert-tabs-home"
                                                    role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin
                                                    malesuada lacus ullamcorper dui molestie, sit amet congue quam
                                                    finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio
                                                    magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin
                                                    pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id.
                                                    Pellentesque habitant morbi tristique senectus et netus et malesuada
                                                    fames ac turpis egestas. Proin id orci eu lectus blandit suscipit.
                                                    Phasellus porta, ante et varius ornare, sem enim sollicitudin eros,
                                                    at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor
                                                    porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus
                                                    pulvinar non consequat neque. Mauris lacus elit, condimentum ac
                                                    condimentum at, semper vitae lectus. Cras lacinia erat eget sapien
                                                    porta consectetur.
                                                </div>
                                                <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel"
                                                    aria-labelledby="vert-tabs-profile-tab">
                                                    Mauris tincidunt mi at erat gravida, eget tristique urna bibendum.
                                                    Mauris pharetra purus ut ligula tempor, et vulputate metus
                                                    facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
                                                    posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus
                                                    interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                                                    lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere
                                                    nec nunc. Nunc euismod pellentesque diam.
                                                </div>
                                                <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel"
                                                    aria-labelledby="vert-tabs-messages-tab">
                                                    Morbi turpis dolor, vulputate vitae felis non, tincidunt congue
                                                    mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus
                                                    faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac
                                                    tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum.
                                                    Suspendisse ut velit condimentum, mattis urna a, malesuada nunc.
                                                    Curabitur eleifend facilisis velit finibus tristique. Nam vulputate,
                                                    eros non luctus efficitur, ipsum odio volutpat massa, sit amet
                                                    sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida
                                                    fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel
                                                    metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare
                                                    magna.
                                                </div>
                                                <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel"
                                                    aria-labelledby="vert-tabs-settings-tab">
                                                    Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque
                                                    magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget
                                                    blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod
                                                    molestie tristique. Vestibulum consectetur dolor a vestibulum
                                                    pharetra. Donec interdum placerat urna nec pharetra. Etiam eget
                                                    dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et
                                                    felis ut nisl commodo dignissim. In hac habitasse platea dictumst.
                                                    Praesent imperdiet accumsan ex sit amet facilisis.
                                                </div>
                                            </div>
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
