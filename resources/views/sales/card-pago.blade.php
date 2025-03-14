<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Detalles </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">
            @livewire('sale.currency',['total'=>$total])
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Fecha de Entrega -->
            <div class="col-6">
                <label for="fechaing">Fecha de Entrega:</label>
                <div class="input-group">
                    <input type="date" wire:model="fechaing" class="form-control" id="fechaing">
                </div>
            </div>

            <!-- Repartidor -->
            <div class="col-6">
                <label for="delivery_id">Repartidor:</label>
                <div class="input-group">
                    <select wire:model="delivery_id" class="form-control" id="delivery_id">
                        <option value="">Sin repartidor</option>
                        @foreach (\App\Models\Delivery::all() as $delivery)
                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Incentivo -->
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

            <!-- Descuento -->
            <div class="col-6">
                <label for="descuento">Descuento:</label>
                <div class="input-group">
                    <input type="number" wire:model="descuento" class="form-control" id="descuento" placeholder="Ingrese descuento">
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Departamento y Provincia -->
            <div class="col-6">
                <label for="departamento">Departamento:</label>
                <div class="input-group">
                    <select wire:model="departamento" class="form-control" id="departamento">
                        <option value="LA PAZ">LA PAZ</option>
                        <option value="COCHABAMBA">COCHABAMBA</option>
                        <option value="SANTA CRUZ">SANTA CRUZ</option>
                        <option value="TARIJA">TARIJA</option>
                        <option value="CHUQUISACA">CHUQUISACA</option>
                        <option value="ORURO">ORURO</option>
                        <option value="POTOSÍ">POTOSÍ</option>
                        <option value="BENI">BENI</option>
                        <option value="PANDO">PANDO</option>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <label for="provincia">Provincia:</label>
                <div class="input-group">
                    <select wire:model="provincia" class="form-control" id="provincia">
                        <!-- Provincias serán llenadas dinámicamente -->
                    </select>
                </div>
            </div>
        </div>

        <!-- Archivos Pedido y Boleta -->
        <div class="row">
            <div class="col-12 mt-12">
                <label for="pedido_path">Subir Pedido:</label>
                <div class="input-group">
                    <input type="file" wire:model="pedido_path" class="form-control" id="pedido">
                    <button class="btn btn-primary" onclick="document.getElementById('pedido').click();">Seleccionar Archivo</button>
                </div>
                <div id="drop-area-pedido" class="mt-2 p-3 border border-dashed text-center">
                    <p>Arrastra y suelta un archivo aquí</p>
                </div>
            </div>

            <div class="col-12 mt-12">
                <label for="boleta_path">Subir Boleta:</label>
                <div class="input-group">
                    <input type="file" wire:model="boleta_path" class="form-control" id="boleta">
                    <button class="btn btn-primary" onclick="document.getElementById('boleta').click();">Seleccionar Archivo</button>
                </div>
                <div id="drop-area-boleta" class="mt-2 p-3 border border-dashed text-center">
                    <p>Arrastra y suelta un archivo aquí</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos de ejemplo para departamentos y provincias
    const departamentos = {
        "LA PAZ": [
            "MURILLO", "LA RECAJA", "YUNGAS", "LOS ANDES", "SUD YUNGAS", "NOR YUNGAS", 
            "INGAVI", "GUALBERTO VILLARROEL", "PALIAGUA", "ARAPA", "ORURO", "JULI", 
            "CAMILO CORDOBA"
        ], // La Paz
        "COCHABAMBA": [
            "CERCADO", "QUILLACOLLO", "COLCAPIRHUA", "SIPE SIPE", "VINTO", "SACABA", 
            "TIQUIPAYA", "VILLA TUNARI", "AYOPAYA", "MIZQUE", "TURCUPARA", "ESTEVANICO"
        ], // Cochabamba
        "SANTA CRUZ": [
            "ANDRES IBÁÑEZ", "ICHILO", "SARA", "VALLEGRANDE", "CORDILLERA", "CHIQUITOS", 
            "GUARAYOS", "ÑUFLO DE CHÁVEZ", "MOCOVÍ", "VELASCO", "SANTA CRUZ DE LA SIERRA"
        ], // Santa Cruz
        "TARIJA": [
            "TARIJA", "AVILÉS", "CHAGUAYA", "EL PUENTE", "SAN ANDRÉS", "MEXICO", "BERMEJO", 
            "YACUIBA", "VILLA MONTES", "PADILLA", "SANTA TERESA", "ALICIA"
        ], // Tarija
        "CHUQUISACA": [
            "SUCRE", "TOMINA", "ZUDÁÑEZ", "YOTALA", "PRESTO", "HUACARETA", "VILLA SERRANO", 
            "SAN LUCAS", "CAMARGO", "MONTEAGUDO", "LA VILLA", "NUEVA ESPERANZA"
        ], // Chuquisaca
        "ORURO": [
            "ORURO", "CARACOLLO", "SABAYA", "HUANUNI", "POOPÓ", "PUNA", "SORACACHI", 
            "MACHACAMARCA", "SANTIAGO DE HUARINA", "VILLA VICTORIA", "COCHABAMBA"
        ], // Oruro
        "POTOSÍ": [
            "POTOSÍ", "UYUNI", "VILLAZÓN", "COTAGAITA", "CHAYANTA", "PUNA", "LLICA", "TINGUIPAYA", 
            "TUPIZA", "YONDO", "EL CHORO", "TARAPAYA"
        ], // Potosí
        "BENI": [
            "TRINIDAD", "RIBERALTA", "GUAYARAMERÍN", "BAURES", "SANTA ANA DEL YACUMA", 
            "YACUMA", "EXALTACIÓN", "SAN JAVIER", "SAN IGNACIO DE VELASCO", "SERRANIA", 
            "MISIONES", "SANTA ROSA"
        ], // Beni
        "PANDO": [
            "COBIJA", "PORVENIR", "FILADELFIA", "SAN PEDRO", "BOLÍVAR", "SANTA ROSA", "SAN ANTONIO", 
            "SAN JUAN", "EXALTACIÓN"
        ]  // Pando
    };

    // Inicializar Select2
    $(document).ready(function() {
        // Inicializamos Select2 para los departamentos y provincias
        $('#departamento').select2({
            placeholder: 'Selecciona un departamento',
            allowClear: true
        });

        $('#provincia').select2({
            placeholder: 'Selecciona una provincia',
            allowClear: true
        });

        // Cambiar provincias cuando se selecciona un departamento
        $('#departamento').change(function() {
            const departamentoId = $(this).val();
            const provincias = departamentos[departamentoId] || [];

            // Limpiar provincias actuales
            $('#provincia').empty(); 

            // Agregar las nuevas opciones de provincias
            provincias.forEach(provincia => {
                $('#provincia').append(new Option(provincia, provincia));
            });

            // Actualizar Select2
            $('#provincia').trigger('change');
        });
    });
</script>
