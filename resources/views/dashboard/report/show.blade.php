<x-layout title="Dashboard" class="">
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Report Detail</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Report</a></li>
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
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h2>#{{ str_pad($report->id, 5, 0, STR_PAD_LEFT) }}</h2>
                        </div>
                        <div class="card-body">
                            <p class="text-justify">
                                <img style="width:350px;max-height:450px;margin-inline:1rem;
                                            float:left;"
                                    src="{{ asset('storage/' . $report->photo) }}">
                                <strong>Title Schedule: {{ $report->schedule->title }}</strong><br />
                                <span class="text-muted">Location : {{ $report->schedule->location }}</span><br />
                                <span class="text-muted">Date : {{ $report->schedule->date }}</span><br />
                                <strong>Note:</strong> {{$report->note}}
                            </p>
                        </div>
                    </div>
                    
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <h2>
                                Order Details
                            </h2>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Total Price</th>
                                        <th>Vendor Name</th>
                                        <th>Vendor Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Rp. {{number_format($report->schedule->orderDetail->total_price)}}</td>
                                        <td>{{$report->schedule->orderDetail->vendors->name}}</td>
                                        <td>
                                            <div class="flex-column d-flex">
                                                <div><strong>Status User : </strong> <span @class([
                                                    'badge',
                                                    'bg-secondary'=>$report->schedule->orderDetail->status=='active',
                                                    'bg-success'=>$report->schedule->orderDetail->status=='accepted',
                                                    'bg-danger'=>$report->schedule->orderDetail->status=='rejected'
                                                ])>
                                                    {{$report->schedule->orderDetail->status}}</span>
                                                </div>
                                                <div><strong>Date Order : </strong>
                                                    <span class="text-muted">
                                                        {{$report->schedule->orderDetail->created_at}}
                                                    </span>
                                                </div>
                                                <div><strong>Category : </strong>
                                                    <span class="text-muted">
                                                        {{$report->schedule->orderDetail->vendors->category}}
                                                    </span>
                                                </div>
                                                <div><strong>Phone Number : </strong>
                                                    <span class="text-muted">
                                                        {{$report->schedule->orderDetail->vendors->phone_number}}
                                                    </span>
                                                </div>
                                                <div><strong>Address : </strong>
                                                    <span class="text-muted">
                                                        {{$report->schedule->orderDetail->vendors->address}}
                                                    </span>
                                                </div>
                                                <div><strong>Note : </strong>
                                                    <span class="text-muted">
                                                        {{$report->schedule->orderDetail->note}}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </x-dashboard>
</x-layout>
