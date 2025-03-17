<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - EDUCACIÓN, OCIO Y TIEMPO LIBRE LA FÁBRICA S.L.</title>
</head>
<body style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f9f9f9;">
    <!-- Contenedor Principal -->
    <div style="max-width: 600px; margin: 20px auto; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <!-- Encabezado -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{asset('assets/images/logo_factura.png') }}" alt="Logo de la Empresa" style="width: 200px; margin-bottom: 10px;">
            <p style="margin: 5px 0; font-size: 0.9rem; color: #555;">Gracias por confiar en nosotros</p>
        </div>

        <!-- Mensaje Principal -->
        <p style="margin-bottom: 20px;">Estimado/a <b>{{ $cliente->nombre }} {{ $cliente->apellido }}</b>,</p>
        <p style="margin-bottom: 20px;">Adjuntamos su factura correspondiente al número <b>{{$factura->numero_factura}}</b>. A continuación, encontrará un resumen de los detalles:</p>

        <!-- Resumen de la Factura -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><b>Fecha de emisión:</b></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ substr($factura->fecha_emision, 0, 10) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><b>Fecha de vencimiento:</b></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ substr($factura->fecha_vencimiento, 0, 10) }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><b>Precio Total:</b></td>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">{{ $presupuesto->precioFinal }} €</td>
            </tr>
        </table>

        <!-- Nota Adicional -->
        <p style="margin-bottom: 20px;">Si tiene alguna pregunta o necesita asistencia, no dude en contactarnos a través de:</p>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Teléfono: <b>956 042 751 / 673 811 838</b></li>
            <li>Email: <b>eventos@fabricandoeventos.com</b></li>
        </ul>

        {{-- <!-- Botón de Descarga (Opcional) -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ $url_descarga_factura }}" style="display: inline-block; padding: 10px 20px; background-color: #3db1f3; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold;">Descargar Factura</a>
        </div> --}}

        <!-- Pie de Página -->
        <div style="margin-top: 30px; text-align: center; font-size: 0.9rem; color: #777;">
            <p>EDUCACIÓN, OCIO Y TIEMPO LIBRE LA FÁBRICA S.L.<br>
            B-11949658 | Avd. Alcalde Cantos Ropero, 51 Pol. Ind. "Jerez 2000" Nave 14<br>
            11408 Jerez de la Frontera (Cádiz)</p>
        </div>
    </div>
</body>
</html>
