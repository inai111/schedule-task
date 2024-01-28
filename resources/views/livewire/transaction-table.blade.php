<div>
    <div class="row justify-content-center">
        <div class="col-10">
            <div class="card p-2">
                <div class="card-body">
                    <div class="mb-3">
                        <form class="form-inline" wire:submit.prevent="searchString">
                            <div class="input-group mr-sm-2">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">From</div>
                                </div>
                                <input type="date" class="form-control"
                                id="dateStart" wire:model="start">
                              </div>
                            <div class="input-group mr-sm-2">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">To</div>
                                </div>
                                <input type="date" class="form-control"
                                id="dateEnd" wire:model="end">
                              </div>
                              <select class="form-control mr-sm-3" wire:model="status">
                                <option value="">All</option>
                                <option value="waiting">Waiting</option>
                                <option value="success">Success</option>
                              </select>
                            
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>                          
                        </form>
                    </div>
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order</th>
                                <th>Tanggal Transaksi</th>
                                <th>Transaction Status</th>
                                <th>Total</th>
                                <th>More</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr wire:click="transactionDetail({{ $transaction->id }})">
                                    <td>#{{ $transaction->id }}</td>
                                    <td>
                                        <a href="{{ route('order.show', ['order' => $transaction->order_id]) }}">
                                            #{{ $transaction->order_id }}
                                        </a>
                                    </td>
                                    <td>{{ $transaction->updated_at }}</td>
                                    <td>
                                        <span @class([
                                            'text-success' => $transaction->status === 'success',
                                            'text-warning' => $transaction->status === 'waiting',
                                        ])>
                                            <b>
                                                {{ $transaction->status }}
                                            </b>
                                        </span>
                                    </td>
                                    <td>
                                        <b>
                                            {{ \Illuminate\Support\Number::currency($transaction->total, in: 'IDR', locale: 'id') }}
                                        </b>
                                    </td>
                                    <td>
                                        <a href="{{ route('transaction.show', ['transaction' => $transaction->id]) }}">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
