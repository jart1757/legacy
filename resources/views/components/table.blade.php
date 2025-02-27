<div class="mb-3 d-flex justify-content-between">
    <div>
        <span>Mostrar</span>
        <select wire:model.live='cant'>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span>Entradas</span>
    </div>
    <div class="d-flex align-items-center justify-content-between">

        <div wire:loading wire:target='search' class="spinner-grow text-info mr-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        
        <input type="text" wire:model.live='search' class="form-control" placeholder="Buscar...">
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover text-center">
        <thead>
            <tr>
                {{$thead}}
            </tr>
        </thead>
        <tbody>
            {{$slot}}
        </tbody>
    </table>
</div>