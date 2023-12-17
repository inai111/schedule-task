<x-layout title="Transaction">
    <x-slot:head>
        @if ($transaction->status == 'waiting')
            <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
                data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
            @vite('/resources/js/transaction-show.js')
        @endif
    </x-slot>
    <x-dashboard>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Transaction Detail</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('order.show', ['order' => $order->id]) }}">Order</a>
                                </li>
                                <li class="breadcrumb-item active">Transaction Detail</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <x-invoice :transaction=$transaction :order=$order :user=$user></x-invoice>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </x-dashboard>
</x-layout>
