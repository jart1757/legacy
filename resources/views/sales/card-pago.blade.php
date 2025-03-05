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
                <label for="fechaing">Fecha de Entrega:</label>
                <div class="input-group">
                    <input type="date" wire:model="fechaing" class="form-control" id="fechaing">
                </div>
            </div>
            <div class="col-6">
                <label for="delivery_id">Repartidor:</label>
                <div class="input-group">
                    <select wire:model="delivery_id" class="form-control" id="repartidor">
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
        <div class="col-6">
            <label for="tipo">Incentivo:</label>
            <div class="input-group">
                <select wire:model="tipo" class="form-control" id="tipo">
                    <option value="">SELECCIONE INCENTIVO</option>
                    <option value="RECONSUMO">RECONSUMO</option>
                    <option value="CAJA GRATIS">CAJA GRATIS</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <label for="file_path">Seleccione Archivo...</label>
            <div class="input-group">
                <input type="file" wire:model="file_path" class="form-control" id="archivo">
            </div>
        </div>
      
        
    </div>
    
</div>