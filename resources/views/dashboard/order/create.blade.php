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
                            <h1 class="m-0">Create New Order</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="/">Order</a></li>
                                <li class="breadcrumb-item active">Create</li>
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
                        <div class="col-lg-8 mx-auto">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">Order</h3>
                                </div>
                                <div class="card-body table-responsive p-0">

                                    <div class="bs-stepper">
                                        <div class="bs-stepper-header" role="tablist">
                                            <!-- your steps here -->
                                            <div class="step" data-target="#user-information">
                                                <button type="button" class="step-trigger" role="tab"
                                                    aria-controls="user-information" id="user-information-trigger">
                                                    <span class="bs-stepper-circle">1</span>
                                                    <span class="bs-stepper-label">User Information</span>
                                                </button>
                                            </div>
                                            <div class="line"></div>
                                            <div class="step" data-target="#order-information">
                                                <button type="button" class="step-trigger" role="tab"
                                                    aria-controls="order-information" id="order-information-trigger">
                                                    <span class="bs-stepper-circle">2</span>
                                                    <span class="bs-stepper-label">Order Information</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="bs-stepper-content">
                                            <!-- your steps content here -->
                                            <div id="user-information" class="content" role="tabpanel"
                                                aria-labelledby="user-information-trigger">
                                                <form id="userUpdate" method="post"
                                                    action="{{ route('user.update', ['user' => auth()->user()->username]) }}">
                                                    {{-- @csrf --}}
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Email
                                                            address</label>
                                                        <input type="email" readonly value="{{auth()->user()->email}}"
                                                        class="form-control" id="emailUser" placeholder="Enter email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="name">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="name"
                                                            class="form-control" id="name" placeholder="Name"
                                                            value="{{ old('name', auth()->user()->name) }}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone_number">Phone Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="phone_number"
                                                            class="form-control" id="phone_number"
                                                            placeholder="0802223311"
                                                            value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" required name="address" rows="3" placeholder="Address ...">
                                                            {{ old('address', auth()->user()->address) }}
                                                        </textarea>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary updateButtonStepper">Next</button>
                                                </form>
                                            </div>
                                            <div id="order-information" class="content" role="tabpanel"
                                                aria-labelledby="order-information-trigger">
                                                <form id="orderCreate" action="{{ route('order.store') }}">
                                                    <div class="callout callout-info">
                                                        <h5><i class="fas fa-info"></i> Note:</h5>
                                                        <p class="text-muted">
                                                            Untuk memulai perencanaan, kami membutuhkan setidaknya
                                                            3 bulan. Konfirmasikan terlebih dahulu kepada kami apakah
                                                            tanggal yang anda ingin kan dapat kami tindak lanjuti atau
                                                            tidak.
                                                        </p>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="plan_date">Plan Date</label>
                                                        <input type="date" required name="plan_date"
                                                            class="form-control" id="plan_date"
                                                            placeholder="Plan Date"
                                                            value="{{ old('plan_date', date('Y-m-d', strtotime('+1 week'))) }}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-primary prevButtonStepper">Previous</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
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
