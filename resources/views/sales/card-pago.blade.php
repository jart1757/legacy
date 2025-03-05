<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Detalles </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">

            {{--<span class="mr-2">Total: <b>{{money($total)}}</b></span>--}}

           @livewire('sale.currency',['total'=>$total])
            
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <label for="fecha_entrega">Fecha de Entrega:</label>
                <div class="input-group">
                    <input type="date" wire:model="fecha_entrega" class="form-control" id="fecha_entrega">
                </div>
            </div>
            <div class="col-6">
                <label for="repartidor">Repartidor:</label>
                <div class="input-group">
                    <select wire:model="repartidor" class="form-control" id="repartidor">
                        <option value="">Seleccione un repartidor</option>
                        <option value="FLORENCIA">FLORENCIA</option>
                        <option value="GERMAN">GERMAN</option>
                        <option value="CELINA">CELINA</option>
                        <option value="JULIA">JULIA</option>
                        <option value="ANDREA">ANDREA</option>
                        <option value="CELIO JUSTINA">CELIO JUSTINA</option>
                        <option value="LURDES">LURDES</option>
                        <option value="DANIELA">DANIELA</option>
                        <option value="NANCY">NANCY</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
</div>