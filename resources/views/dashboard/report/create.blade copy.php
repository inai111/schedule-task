<x-layout title="Dashboard" class="">
    <x-slot:head>
        @vite(['/resources/js/create_report.js'])
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
                                                <input type="file" accept="image/jpg,image/png" 
                                                    name="photo" @class(['form-control', 'is-invalid' => $errors->has('photo')]) id="photo"
                                                    value="{{ old('photo') }}">
                                                @error('photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Report Note : <span class="text-danger">*</span></label>
                                            <textarea @class(['form-control', 'is-invalid' => $errors->has('note')])
                                                name="note" rows="3">{{ old('note') }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <strong>Vendors</strong><br />
                                        </div>
                                        <div class="mb-3">
                                        @if (!empty(old('vendors')))
                                            @foreach (old('vendors') as $vendor)
                                                <div class="mx-3 px-3 py-1 border">
                                                    <div class="form-group">
                                                        <label for="phone_number">Phone Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="vendors[]['phone_number']"
                                                            @class(["form-control"
                                                            "is-invalid"=>$errors->has("vendors[{$loop->index}]['phone_number']")
                                                            ]) id="phone_number"
                                                            value="{{ $vendor->name }}">
                                                            @error()
                                                                
                                                            @enderror
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
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mx-3 px-3 py-1 border">
                                                <div class="form-group">
                                                    <label for="vendors[]['id']">Vendor<span
                                                            class="text-danger">*</span></label>
                                                            <select name="vendors[]['id']" class="custom-select">
                                                                <option selected value="">Select Vendor</option>
                                                                @foreach ($vendorList as $item )
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                                @endforeach
                                                              </select>
                                                </div>
                                                <div class="pl-4">
                                                <div class="form-group">
                                                    <label for="vendors[]['name']">Vendor Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" required name="vendors[]['name']"
                                                        class="form-control" id="vendors[]['name']" placeholder="Name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['phone_number']">Vendor Phone Number<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" required name="vendors[]['phone_number']"
                                                        class="form-control" id="vendors[]['phone_number']"
                                                        placeholder="08871xxx">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['category']">Vendor Category<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" required name="vendors[]['category']"
                                                        class="form-control" id="vendors[]['category']"
                                                        placeholder="Katering">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['bank_name']">Vendor Bank Name<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" required name="vendors[]['bank_name']"
                                                        class="form-control" id="vendors[]['bank_name']"
                                                        placeholder="Bank Name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['bank_account_name']">
                                                        Vendor Bank Account Name<span class="text-danger">
                                                        *</span></label>
                                                    <input type="text" required name="vendors[]['bank_account_name']"
                                                        class="form-control" id="vendors[]['bank_account_name']"
                                                        placeholder="Bank Account Name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['bank_account_number']">
                                                        Vendor Bank Account Number<span class="text-danger">
                                                        *</span></label>
                                                    <input type="text" required name="vendors[]['bank_account_number']"
                                                        class="form-control" id="vendors[]['bank_account_number']"
                                                        placeholder="Bank Account Number">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['address']">Vendor Address
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control" name="vendors[]['address']"
                                                    placeholder="Address"rows="3"></textarea>
                                                </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['total_price']">
                                                        Order Total Price<span class="text-danger">
                                                        *</span></label>
                                                    <input type="text" required name="vendors[]['total_price']"
                                                        class="form-control" id="vendors[]['total_price']"
                                                        placeholder="Total Price">
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendors[]['note']">Order Note</label>
                                                    <textarea class="form-control" name="vendors[]['note']"
                                                    placeholder="Note ..."rows="3"></textarea>
                                                </div>
                                            </div>
                                        @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary updateButtonStepper">Next</button>
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
    </x-dashboard>
</x-layout>
