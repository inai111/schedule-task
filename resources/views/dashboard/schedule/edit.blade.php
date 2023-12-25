@php
    // dd(request()->is('order/create'));
@endphp
<x-layout title="Dashboard" class="">
    <x-slot:head>
        {{-- @vite(['/resources/js/create_order.js']) --}}
    </x-slot>
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Schedule</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="/">Order</a></li>
                                <li class="breadcrumb-item active">Edit Schedule</li>
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
                        <div class="col-lg-8 mx-auto my-2   ">
                            <div class="card border">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Schedule</h3>
                                </div>
                                <div class="card-body table-responsive">

                                    <form id="userUpdate" method="post"
                                        action="{{ route('schedule.update', ['schedule' => $schedule->id]) }}">
                                        @csrf
                                        @method('PUT')

                                        @if($errors->first())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{$errors->first()}}
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            <input type="text" required name="title" @class(['form-control', 'is-invalid' => $errors->has('title')])
                                                id="title" placeholder="Title"
                                                value="{{ old('title', $schedule->title) }}">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="date">Date<span class="text-danger">*</span></label>
                                            <input type="date" required name="date" @class(['form-control', 'is-invalid' => $errors->has('date')])
                                                id="date" min="{{ \Carbon\Carbon::now()->toDateString() }}"
                                                value="{{ old('date', $schedule->date) }}">
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Location <span class="text-danger">*</span></label>
                                            <textarea @class(['form-control', 'is-invalid' => $errors->has('location')]) 
                                                name="location" placeholder="Location ..."
                                                rows="3">{{ old('location', $schedule->location) }}</textarea>
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea class="form-control" name="note" placeholder="Note ..."
                                            rows="3">{{ old('note', $schedule->note) }}</textarea>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary updateButtonStepper">Update</button>
                                    </form>
                                </div>
                            </div>
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
