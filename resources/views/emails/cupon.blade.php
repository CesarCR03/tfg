<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; text-align: center; }
        .titulo { color: #333; }
        .codigo-box {
            background: #f0fdf4;
            color: #166534;
            border: 2px dashed #166534;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            display: inline-block;
        }
        .btn {
            background: #000;
            color: #fff;
            padding: 15px 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="titulo">¡Hola! Tenemos un regalo para ti</h1>
    <p>Queremos agradecerte tu confianza con un descuento especial para tu próxima compra.</p>

    <p>Usa este código al finalizar tu pedido:</p>

    <div class="codigo-box">
        {{ $cupon->codigo }}
    </div>

    <p>
        Este cupón te descuenta
        <strong>
            {{ $cupon->valor }}{{ $cupon->tipo == 'porcentaje' ? '%' : '€' }}
        </strong>
        @if($cupon->fecha_caducidad)
            y es válido hasta el {{ date('d/m/Y', strtotime($cupon->fecha_caducidad)) }}.
        @else
            y no tiene fecha de caducidad.
        @endif
    </p>

    <a href="{{ route('home') }}" class="btn">IR A LA TIENDA</a>
</div>
</body>
</html>
