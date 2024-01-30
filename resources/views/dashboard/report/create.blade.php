<x-layout title="Dashboard" class="">
    <x-slot:head>
        @vite(['resources/js/create_report.js'])
    </x-slot>
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Create Report</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item"><a href="/">Order</a></li>
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
                    <div class="mx-auto">
                        <div class="card border">
                            <div class="card-body table-responsive">
                                <div class="mb-3 pb-3 border-bottom">
                                    <strong>{{ $schedule->title }}</strong><br />
                                    <span class="text-muted">{{ $schedule->location }}</span><br>
                                    <span class="text-muted">{{ date('D, d F Y', strtotime($schedule->date)) }}</span>
                                </div>
                                <div>
                                    <form id="createReport" method="post" enctype="multipart/form-data"
                                        action="{{ route('schedule.report.store', ['schedule' => $schedule->id]) }}">
                                        @csrf

                                        <div class="mb-3 pb-3 border-bottom">
                                            <strong>Vendors</strong><br />
                                        </div>
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-success mb-2" data-toggle="modal"
                                                data-target="#createVendor">Create New
                                                Vendor</button>
                                            <div class="form-inline">
                                                <select class="custom-select mb-2 mr-sm-2 select-vendor">
                                                    <option selected value="">Select Vendor</option>
                                                    @foreach ($vendorList as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                            <span class="text-muted">
                                                                ({{ $item->category->name }})
                                                            </span>
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" id="addVendor"
                                                    class="btn btn-primary
                                                mb-2">Add
                                                    Vendor</button>
                                                <div class="invalid-feedback" id="vendor-feedback"></div>
                                            </div>

                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Vendor Name</th>
                                                        <th>Vendor Category</th>
                                                        <th>Sub Price</th>
                                                        <th>Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="vendor-table"></tbody>
                                                <tbody>
                                                    <tr>
                                                        <th colspan="3">Total Price</th>
                                                        <th colspan="2"><span id="vendorTotal">0</span></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <strong>Report</strong><br />
                                        </div>
                                        <div class="row gap-3 align-items-center" style="gap:2rem">
                                            <div class="form-group shadow col-auto p-2 rounded text-center"
                                                style="min-width:200px;min-height:200px;border:4px #6c757d dashed;
                                            background-color:#e3e3e3">
                                                <img src="" class="border-0" width="200" height="200"
                                                    alt="Photo" style="object-fit:contain;object-position:center"
                                                    id="photoPreview">
                                            </div>
                                            <div class="form-group col-7">
                                                <label for="photo">Photo <span class="text-danger">*</span></label>
                                                <input type="file" accept="image/jpg,image/png" name="photo"
                                                    class="form-control" id="photo">
                                                <div class="invalid-feedback "></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Report Note : <span class="text-danger">*</span></label>
                                            <textarea v-model="form" class="form-control note-input" name="note" rows="3"></textarea>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="d-flex align-items-center" style="gap:1rem">
                                            <button type="submit"
                                                class="btn btn-primary updateButtonStepper">Save</button>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch1"
                                                    name="next_schedule">
                                                <label class="custom-control-label" for="customSwitch1">Create Next
                                                    Schedule</label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <div class="modal" id="createVendor" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Vendor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="vendorForm" action="{{ route('vendor.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Vendor Name<span class="text-danger">*</span></label>
                                <input type="text" required name="name" class="form-control vendors-input"
                                    id="name" placeholder="Name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Vendor Phone Number<span
                                        class="text-danger">*</span></label>
                                <input type="text" required name="phone_number" class="form-control vendors-input"
                                    id="phone_number" placeholder="08871xxx">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Vendor Category<span class="text-danger">*</span></label>
                                <select name="category_id" class="custom-select vendors-input">
                                    <option selected value="">Vendor Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="bank_name">Vendor Bank Name<span class="text-danger">*</span></label>
                                <input type="text" required name="bank_name" class="form-control vendors-input"
                                    id="bank_name" placeholder="Bank Name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="bank_account_name">
                                    Vendor Bank Account Name<span class="text-danger">
                                        *</span></label>
                                <input type="text" required name="bank_account_name"
                                    class="form-control vendors-input" id="bank_account_name"
                                    placeholder="Bank Account Name">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="bank_account_number">
                                    Vendor Bank Account Number<span class="text-danger">
                                        *</span></label>
                                <input type="text" required name="bank_account_number"
                                    class="form-control vendors-input" id="bank_account_number"
                                    placeholder="Bank Account Number">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="address">Vendor Address
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea v-model="report.vendors" class="form-control vendors-input" name="address" placeholder="Address"
                                    rows="3"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <button class="btn px-3 rounded-pill btn-primary float-right">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </x-dashboard>
</x-layout>
