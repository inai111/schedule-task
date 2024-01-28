<div>

    <form action="" wire:submit="makeTransaction">
        @foreach ($orderDetails as $detail)
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" checked id="check-{{ $detail['id'] }}"
                    wire:change.prevent="changeCheck({{ $detail['total'] }})" wire:model="checks.{{ $detail['id'] }}">
                <label class="form-check-label" for="check-{{ $detail['id'] }}">
                    <div style="mb-2">
                        <b>{{ $detail['vendor']['name'] }}</b>
                        <p class="text-muted">{{ $detail['note'] }}</p>
                        <b>
                            {{ \Illuminate\Support\Number::currency($detail['total'], in: 'IDR', locale: 'id') }}
                        </b>
                    </div>
                </label>
            </div>
        @endforeach
        <div class="d-flex justify-content-between align-items-center">
            <b
                style="font-size:1.8rem">{{ \Illuminate\Support\Number::currency($total, in: 'IDR', locale: 'id') }}</b>
            <button type="submit" class="btn btn-primary float-right">Checkout</button>
        </div>
    </form>
</div>
