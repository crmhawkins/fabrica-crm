<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .header div {
            margin: 5px 0;
            font-size: 0.9rem;
            color: #555;
        }

        .row {
            clear: both;
            margin-top: 10px;
        }

        .row .label {
            float: left;
            width: 25%;
            font-weight: bold;
        }

        .row .value {
            float: left;
            width: 75%;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background-color: #3db1f3;
            color: #fff;
            text-align: center;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .totals {
            float: right;
            width: 40%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .totals th,
        .totals td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }

        .signature {
            margin-top: 50px;
            text-align: center;
        }

        .signature img {
            max-width: 200px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <table style="width: 100%;">
            <tr>
                <!-- Celda para el texto -->
                <td>
                    <div class="label"><b>EDUCACIÓN, OCIO Y TIEMPO LIBRE LA FABRICA S.L.</b></div>
                    <div class="value">B-11949658</div>
                    <div class="value">Avd. Alcalde Cantos Ropero, 51 Pol. Ind. "Jerez 2000" Nave 14</div>
                    <div class="value">11408 Jerez de la Frontera ( Cádiz)</div>
                    <div class="value">956 042 751 &nbsp; &nbsp; &nbsp; &nbsp; 673 811 838</div>
                </td>

                <!-- Celda para la imagen -->
                <td style="text-align: right;">
                    <img src="{{ public_path('assets/images/logo_factura.png') }}" alt="Firma del Cliente" style="height: 80px; vertical-align: middle;">
                </td>
            </tr>
        </table>

        <!-- Información de la Factura -->
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <div class="row">
                        <div class="label" style="width:auto;">Fecha de emisión: </div>
                        <div class="value">{{ substr($factura->fecha_emision, 0, 10) }}</div>
                    </div>
                    <div class="row">
                        <div class="label" style="width:auto;">Fecha de vencimiento: </div>
                        <div class="value" >{{ substr($factura->fecha_vencimiento, 0, 10) }}</div>
                    </div>
                </td>
                <td style="text-align: right; width: 50%;">
                    <h2>Factura {{$factura->numero_factura}}</h2>
                </td>
            </tr>
        </table>

        <!-- Datos del Cliente -->
        <table style="width: 100%; margin-top: 20px;">
            <tbody>
                <tr>
                    <td style="border-right-color: #fff !important;"><b>Nombre:</b> {{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                </tr>
                <tr>
                    @if($cliente->tipo_cliente)
                        <td><b>CIF:</b> {{ $cliente->nif }}</td>
                    @else
                        <td><b>DNI:</b> {{ $cliente->nif }}</td>
                    @endif
                </tr>
                <tr>
                    <td><b>Domicilio:</b> {{ $cliente->tipoCalle }} {{ $cliente->calle }}, {{ $cliente->numero }}, {{ $cliente->codigoPostal }}, {{ $cliente->ciudad }}, {{ $cliente->provincia }}</td>
                </tr>
                <tr>
                    <td><b>Teléfono:</b> {{ $cliente->tlf1 }}</td>
                </tr>
                <tr>
                    <td><b>Email:</b> {{ $cliente->email1 }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Detalles de los Servicios -->
        <table class="table">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Monitores</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listaPacks as $packIndex => $pack)
                    <tr>
                        <td>{{ $packs->where('id', $pack['id'])->first()->nombre }}</td>
                        <td>{{ array_sum($pack['numero_monitores']) }} monitores</td>
                        <td>{{ $pack['precioFinal'] }} €</td>
                    </tr>
                    @foreach ($packs->where('id', $pack['id'])->first()->servicios() as $keyPack => $servicioPack)
                        <tr>
                            <td>{{ $servicioPack->nombre }}</td>
                            <td>{{ $pack['numero_monitores'][$keyPack] }} monitores</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endforeach
                @foreach ($listaServicios as $servicioIndex => $servicio)
                    <tr>
                        <td>{{ $servicio['nombre'] }}</td>
                        <td>{{ $servicio['numero_monitores'] }} monitores</td>
                        <td>{{ $servicio['precioFinal'] }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <table class="totals">
            <tr>
                <th>Subtotal</th>
                <td>{{ $presupuesto->precioBase }} €</td>
            </tr>
            <tr>
                <th>Descuento</th>
                <td>{{ $presupuesto->descuento }} €</td>
            </tr>
            <tr>
                <th>Iva</th>
                <td>{{ $presupuesto->precioFinal * ($factura->tipo_iva / 100) }} €</td>
            </tr>
            <tr>
                <th>Precio Total</th>
                <td>{{ $presupuesto->precioFinal * (($factura->tipo_iva / 100) + 1) }} €</td>
            </tr>
        </table>

        <!-- Footer -->
        {{-- <div class="footer">
            <p>* En caso de que se suspendiera el evento antes de montar el servicio por causas ajenas a La Fábrica, el adelanto del pago se podrá disfrutar otro día en común acuerdo con el mismo importe total. ** Contrato no válido sin justificante bancario.</p>
        </div> --}}

        <!-- Firma del Cliente -->
        {{-- <div class="signature">
            <p>Firma del Cliente:</p>
            <img src="{{ public_path('storage/' . $parte->firma) }}" alt="Firma del Cliente">
        </div> --}}
    </div>
</body>

</html>
