<x-layout title="Dashboard" class="">
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Report List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active">Report</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="d-flex flex-column">
                        @if ($reports->count() > 0)
                            @foreach ($reports as $report)
                                <div class="border-top border-bottom">
                                    <h2>#{{ str_pad($report->id, 5, 0, STR_PAD_LEFT) }}</h2>
                                    @if (auth()->user()->role_id==1)
                                        <span class="text-muted">By: {{$report->schedule->staff->name}}</span>
                                    @endif
                                    <div class="d-flex" style="gap:2rem">
                                        <p class="text-justify">
                                            <img style="width:350px;max-height:450px;margin-inline:1rem;
                                            float:left;"
                                                src="{{ asset('storage/' . $report->photo) }}">
                                            <strong>Title Schedule: {{$report->schedule->title}}</strong><br/>
                                            <span class="text-muted">Location : {{$report->schedule->location}}</span><br/>
                                            <span class="text-muted">Date : {{$report->schedule->date}}</span><br/>
                                            <strong>Note:</strong> {{ \Illuminate\Support\Str::limit($report->note,200,'...') }}
                                        </p>
                                        <div class="my-auto ml-auto mr-3">
                                            <a href="{{route('report.show',['report'=>$report->id])}}" class="btn btn-success px-2">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            No Report Available
                        @endif
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </x-dashboard>
</x-layout>
