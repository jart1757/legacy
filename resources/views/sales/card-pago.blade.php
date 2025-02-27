<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Pago </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">

            <span class="mr-2">Total: <b>{{money($total)}}</b></span>

           @livewire('sale.currency',['total'=>$total])
            
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <label for="pago">Pago:</label>
                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>

                    <input type="number" wire:model.live="pago" class="form-control" id="pago" min="{{$total}}">

                </div>
                <p>{{numeroLetras($pago)}}</p>
            </div>
            <div class="col-6">
                <label for="pago">Devuelve:</label>
                <div class="input-group">

                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                    </div>
                    <input type="number" wire:model="devuelve" class="form-control" min="0" readonly>


                </div>
                <p>{{numeroLetras($devuelve)}}</p>
            </div>
        </div>
    </div>
</div>