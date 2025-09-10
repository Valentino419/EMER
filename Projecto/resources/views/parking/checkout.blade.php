<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pago - Estacionamiento</title>
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e4e8;
        }

        .table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            color: #333;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .table-hover tbody tr:hover {
            background-color: #e6f0fa;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 8px 18px;
            font-weight: 500;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
        }

        .alert-success, .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #e0e4e8;
            padding: 10px;
        }

        #card-element {
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e0e4e8;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        #card-errors {
            color: #721c24;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td {
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @if (isset($parking) && $parking->exists)
            <h2>Confirmar Pago para Estacionamiento</h2>

            @if (session('errors'))
                <div class="alert alert-danger">
                    @foreach (session('errors')->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Patente</th>
                        <td>{{ $parking->license_plate }}</td>
                    </tr>
                    <tr>
                        <th>Duración</th>
                        <td>{{ number_format($parking->end_time->diffInHours($parking->start_time), 1) }} horas</td>
                    </tr>
                    <tr>
                        <th>Monto</th>
                        <td>${{ number_format($parking->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Inicio</th>
                        <td>{{ $parking->start_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Fin</th>
                        <td>{{ $parking->end_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Estado del Pago</th>
                        <td>
                            <span class="badge {{ $parking->payment_status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                {{ $parking->payment_status === 'completed' ? 'Completado' : 'Pendiente' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <form id="payment-form" action="{{ route('parking.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="parking_id" value="{{ $parking->id }}">
                <div id="card-element" class="form-control"></div>
                <div id="card-errors" role="alert"></div>
                <button type="submit" class="btn btn-primary mt-3">Pagar ${{ number_format($parking->amount, 2) }}</button>
            </form>

            <a href="{{ route('parking.create') }}" class="btn btn-danger mt-3">Cancelar</a>
        @else
            <div class="alert alert-danger">
                Error: No se pudo crear el registro de estacionamiento. Intenta de nuevo.
            </div>
            <a href="{{ route('parking.create') }}" class="btn btn-primary">Volver</a>
        @endif
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        const elements = stripe.elements();
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#333',
                    '::placeholder': { color: '#6c757d' },
                },
            },
        });
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const cardErrors = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            cardErrors.textContent = '';

            const { error, paymentIntent } = await stripe.confirmCardPayment(
                '{{ $clientSecret }}',
                {
                    payment_method: {
                        card: card,
                        billing_details: { name: '{{ auth()->user()->name ?? "Usuario" }}' },
                    },
                }
            );

            if (error) {
                cardErrors.textContent = error.message;
            } else if (paymentIntent.status === 'succeeded') {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'payment_intent';
                hiddenInput.value = paymentIntent.id;
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    </script>
</body>
</html>