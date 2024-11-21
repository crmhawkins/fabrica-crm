<div class="container-fluid">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">CREAR TIPO DE IVA</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Impuestos</a></li>
                    <li class="breadcrumb-item active">Crear Tipo de IVA</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-md-9">
            <div class="card m-b-30">
                <div class="card-body">
                    <form wire:submit.prevent="submit">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="name" class="col-form-label">Nombre del tipo de IVA</label>
                                <input type="text" class="form-control" wire:model="name" name="name" id="name"
                                    placeholder="Nombre del tipo de IVA...">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="iva" class="col-form-label">IVA (%)</label>
                                <input type="text" class="form-control" wire:model="iva" name="iva" id="iva"
                                    placeholder="IVA...">
                                @error('iva')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <h5>Acciones</h5>
                    <div class="row">
                        <div class="col-12">
                            <button class="w-100 btn btn-info mb-2" wire:click.prevent="alertaGuardar">Guardar Tipo de IVA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')

@endsection
