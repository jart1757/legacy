<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Detalles </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">
            {{-- <span class="mr-2">Total: <b>{{money($total)}}</b></span> --}}
            @livewire('sale.currency',['total'=>$total])
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <label for="fechaing">Fecha de Entrega:</label>
                <div class="input-group">
                    <input type="date" wire:model="fechaing" class="form-control" id="fechaing">
                </div>
            </div>

            <div class="col-6">
                <label for="delivery_id">Repartidor:</label>
                <div class="input-group">
                    <select wire:model="delivery_id" class="form-control">
                        <option value="">Sin repartidor</option>
                        @foreach (\App\Models\Delivery::all() as $delivery)
                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="tipo">Incentivo:</label>
                <div class="input-group">
                    <select wire:model="tipo" class="form-control" id="tipo">
                        <option value="">SELECCIONE INCENTIVO</option>
                        <option value="NUEVO">NUEVO</option>
                        <option value="RECONSUMO">RECONSUMO</option>
                        <option value="CAJA GRATIS">CAJA GRATIS</option>
                    </select>
                </div>
            </div>

            <div class="col-6 mt-2">
                <label for="descuento">Descuento:</label>
                <div class="input-group">
                    <input type="number" wire:model="descuento" class="form-control" id="descuento" placeholder="Ingrese descuento">
                </div>
            </div>
        </div>

        <div class="col-12 mt-2">
            <label for="file_path">Seleccione Archivo...</label>
            <div class="input-group">
                <input type="file" wire:model="file_path" class="form-control" id="archivo">
            </div>
        </div>
    </div>
        <!-- Mostrar total con descuento -->
        <div class="mt-2">
            <p>Total con descuento: <b>{{ money($total) }}</b></p>
        </div>
</div>
