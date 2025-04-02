
<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Detalles </h3>
        <div class="card-tools d-flex justify-content-center align-self-center">
            <a href="{{ route('deliveries') }}" class="btn bg-purple btn-sm">
                Crear Usuario
            </a>                     
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <label for="fechaing">Fecha de Pago:</label>
                <div class="input-group">
                    <input type="date" wire:model="fechaing" class="form-control" id="fechaing">
                </div>
            </div>
            <div class="col-6">
                <label for="delivery_id">Usuario:</label>
                <div class="input-group">
                    <select wire:model="delivery_id" class="form-control" id="delivery_id">
                        <option value="">Seleccionar Usuario</option>
                        @foreach (\App\Models\Delivery::all() as $delivery)
                            <option value="{{ $delivery->id }}">{{ $delivery->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
  
            <div class="col-12">
                <label for="descuento">Descuento:</label>
                <div class="input-group">
                    <select class="form-control" id="descuento_select" onchange="setDescuento()">
                        <option value="0">Seleccionar descuento</option>
                        <option value="0.03">Bonificado (0.03)</option>
                        <option value="0.10">Mayorista (0.10)</option>
                        <option value="0.02">Bonificado Alc (280.04)</option>
                    </select>
                    <input type="number" wire:model="descuento" class="form-control" id="descuento" placeholder="Ingrese descuento">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <label for="pedido_path">Subir Pedido:</label>
                <div class="input-group">
                    <input type="file" wire:model="pedido_path" class="form-control" id="pedido_path">
                </div>
            </div>
            <div class="col-6">
                <label for="boleta_path">Subir Boleta:</label>
                <div class="input-group">
                    <input type="file" wire:model="boleta_path" class="form-control" id="boleta_path">
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-6">
                <label for="departamento">Departamento:</label>
                <div class="input-group">
                    <select wire:model="departamento" class="form-control" id="departamento">
                        <option value="" selected>Seleccione Departamento</option>
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
        <div class="row mt-3">
            <div class="col-12">
                <label for="pago_bonificacion">PAGO CON BONIFICACIÓN:</label>
                <div class="input-group">
                    <input type="text" wire:model="tipo" class="form-control" id="pago_bonificacion" placeholder="Ingrese el codigo">
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

<script>
    function setDescuento() {
        let descuentoSelect = document.getElementById("descuento_select");
        let descuentoInput = document.getElementById("descuento");

        // Asignar el valor seleccionado al campo de descuento
        descuentoInput.value = descuentoSelect.value;

        // Notificar a Livewire del cambio
        @this.set('descuento', descuentoSelect.value);
    }
</script>
<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('resetDescuentoSelect', () => {
            document.getElementById('descuento_select').value = "";
        });
    });
</script>
