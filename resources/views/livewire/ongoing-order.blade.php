<div>
    <div>
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <form class="form-inline" wire:submit.prevent="searchString">
                                <input type="search" class="form-control mr-sm-3" id="search" wire:model="search">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </div>
                        <div class="mb-3">
                            @if (session('message'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <table class="table table-hover table-striped table-dark">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Status</th>
                                    <th>Place</th>
                                    <th>User Email</th>
                                    <th>Bill</th>
                                    <th>More</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>
                                            <span @class([
                                                'text-success' => $order->order_status === 'success',
                                                'text-primary' => $order->order_status === 'ongoing',
                                                'text-warning' => $order->order_status === 'pending',
                                            ])>
                                                <b>
                                                    {{ $order->order_status }}
                                                </b>
                                            </span>
                                        </td>
                                        <td>{{ $order->city }}</td>
                                        <td>
                                            {{ $order->user->email }}
                                        </td>
                                        <td>
                                            <b>
                                                {{ \Illuminate\Support\Number::currency($order->total_price, in: 'IDR', locale: 'id') }}
                                            </b>
                                        </td>
                                        <td>
                                            <a href="{{ route('order.show', ['order' => $order->id]) }}">
                                                <i class="fas fa-search"></i>
                                            </a>

                                            <button type="button" class="btn btn-primary btn-sm ml-sm-2 emitChild"
                                                data-toggle="modal" data-target="#modalTransaction"
                                                data-id="{{ $order->id }}">
                                                <i class="fas fa-plus fs-1x"></i>
                                                Transaction
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalTransaction" tabindex="-1" aria-labelledby="modalTransactionlLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTransactionlLabel">Create Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:staffTransaksi />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.querySelector('.emitChild').addEventListener('click', function(e) {
            let id = this.dataset.id;
            $wire.dispatch('getOrder', {
                id: id
            });
        })
        $wire.on('reloadPage', function() {
            window.location.reload();
        });
    </script>
@endscript
