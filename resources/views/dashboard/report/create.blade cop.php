@php
    // if(old('vendor')){
    //     dd(old('vendor'));
    // }
@endphp
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
                                                <input type="file" accept="image/jpg,image/png" name="photo"
                                                    @class(['form-control', 'is-invalid' => $errors->has('photo')]) id="photo"
                                                    value="{{ old('photo') }}">
                                                @error('photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Report Note : <span class="text-danger">*</span></label>
                                            <textarea @class(['form-control', 'is-invalid' => $errors->has('note')]) name="note" rows="3">{{ old('note') }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <strong>Vendor</strong><br />
                                        </div>
                                        <div class="mb-3">
                                            <div class="mx-3 px-3 py-1 border">
                                                <div class="form-group">
                                                    <label for="vendor[id]">Vendor</label>
                                                    <select name="vendor[id]" @class(['custom-select select-vendor',
                                                    'is-invalid' => $errors->has('vendor.id')])>
                                                        <option selected value="">New Vendor</option>
                                                        @foreach ($vendorList as $item)
                                                            <option value="{{ $item->id }}"
                                                                @selected(old('vendor.id') == $item->id)
                                                                >{{ $item->name }} <span class="text-muted">
                                                                    ({{$item->category->name}})
                                                                </span>
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('vendor.phone_number')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="pl-4">
                                                    <div class="form-group">
                                                        <label for="vendor.name">Vendor Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="vendor[name]"
                                                            @class(['form-control', 'is-invalid' => $errors->has('vendor.name')]) id="vendor.name"
                                                            placeholder="Name" value="{{ old('vendor.name') }}">
                                                        @error('vendor.name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.phone_number">Vendor Phone Number<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="vendor[phone_number]"
                                                            @class([
                                                                'form-control',
                                                                'is-invalid' => $errors->has('vendor.phone_number'),
                                                            ]) id="vendor.phone_number"
                                                            placeholder="08871xxx"
                                                            value="{{ old('vendor.phone_number') }}">
                                                        @error('vendor.phone_number')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.category">Vendor Category<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="vendor[category]"
                                                            @class([
                                                                'form-control',
                                                                'is-invalid' => $errors->has('vendor.category'),
                                                            ]) id="vendor.category"
                                                            placeholder="Katering"
                                                            value="{{ old('vendor.category') }}">
                                                        @error('vendor.category')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.bank_name">Vendor Bank Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" required name="vendor[bank_name]"
                                                            @class([
                                                                'form-control',
                                                                'is-invalid' => $errors->has('vendor.bank_name'),
                                                            ]) id="vendor.bank_name"
                                                            placeholder="Bank Name"
                                                            value="{{ old('vendor.bank_name') }}">
                                                        @error('vendor.bank_name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.bank_account_name">
                                                            Vendor Bank Account Name<span class="text-danger">
                                                                *</span></label>
                                                        <input type="text" required
                                                            name="vendor[bank_account_name]"
                                                            @class([
                                                                'form-control',
                                                                'is-invalid' => $errors->has('vendor.bank_account_name'),
                                                            ]) id="vendor.bank_account_name"
                                                            placeholder="Bank Account Name"
                                                            value="{{ old('vendor.bank_account_name') }}">
                                                        @error('vendor.bank_account_name')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.bank_account_number">
                                                            Vendor Bank Account Number<span class="text-danger">
                                                                *</span></label>
                                                        <input type="text" required
                                                            name="vendor[bank_account_number]"
                                                            @class([
                                                                'form-control',
                                                                'is-invalid' => $errors->has('vendor.bank_account_number'),
                                                            ])
                                                            id="vendor.bank_account_number"
                                                            placeholder="Bank Account Number"
                                                            value="{{ old('vendor.bank_account_number') }}">

                                                        @error('vendor.bank_account_number')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="vendor.address">Vendor Address
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea @class([
                                                            'form-control',
                                                            'is-invalid' => $errors->has('vendor.address'),
                                                        ]) name="vendor[address]" placeholder="Address" rows="3">{{ old('vendor.address') }}</textarea>
                                                        @error('vendor.address')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendor.total_price">
                                                        Order Total Price<span class="text-danger">
                                                            *</span></label>
                                                    <input type="text" required name="vendor[total_price]"
                                                        @class([
                                                            'form-control',
                                                            'is-invalid' => $errors->has('vendor.total_price'),
                                                        ]) id="vendor.total_price"
                                                        placeholder="Total Price"
                                                        value="{{ old('vendor.total_price') }}">
                                                    @error('vendor.total_price')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="vendor.note">Order Note</label>
                                                    <textarea @class(['form-control', 'is-invalid' => $errors->has('vendor.note')]) name="vendor[note]" placeholder="Note ..."rows="3">{{ old('vendor.note') }}</textarea>
                                                    @error('vendor.note')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center" style="gap:1rem">
                                                    <button type="submit"
                                                        class="btn btn-primary updateButtonStepper">Next</button>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="customSwitch1" name="next_schedule">
                                                        <label class="custom-control-label" for="customSwitch1">Create Next Schedule</label>
                                                    </div>
                                                </div>
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
    </x-dashboard>
</x-layout>
