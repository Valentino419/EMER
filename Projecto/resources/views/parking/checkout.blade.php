@extends('layouts.app')

@section('content')
    <style>
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            color: #1a3c6d;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .table th {
            background: #007bff;
            color: white;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table td {
            padding: 12px;
            color: #333;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8fafc;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
        }

        .btn-primary {
            background: #007bff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-danger:hover {
            background: #b02a37;
            transform: translateY(-2px);
        }

        .form-control {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            width: 100%;
            font-size: 15px;
        }

        #card-errors {
            color: #721c24;
            margin-bottom: 15px;
            min-height: 20px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
            background-repeat: no-repeat;
            background-position-x: 98%;
            background-position-y: 50%;
        }
    </style>

    <div class="container">
        @if (isset($session))
            <h2>Confirmar Pago para Estacionamiento</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Patente</th>
                        <td>{{ $session->license_plate }}</td>
                    </tr>
                    <tr>
                        <th>Duración</th>
                        <td>{{ number_format($session->duration / 60, 1) }} horas</td>
                    </tr>
                    <tr>
                        <th>Monto</th>
                        <td>ARS {{ number_format($session->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Inicio</th>
                        <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Estado del Pago</th>
                        <td><span class="badge bg-warning">Pendiente</span></td>
                    </tr>
                </tbody>
            </table>

            <form id="payment-form" action="{{ route('payment.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <input type="hidden" id="token" name="token">
                <input type="hidden" id="payment_method_id" name="payment_method_id">
                <input type="hidden" id="installments" name="installments" value="1">

                <div class="form-control" id="card-number-container"></div>
                <div class="form-control" id="expiration-date-container"></div>
                <div class="form-control" id="security-code-container"></div>
                <div class="form-control">
                    <input type="text" id="cardholder-name" name="cardholder_name" placeholder="Nombre del titular"
                        required>
                </div>
                <div class="form-control">
                    <select id="doc-type" name="identification_type" required>
                        <option value="">Tipo de documento</option>
                        <option value="DNI">DNI</option>
                        <option value="CUIT">CUIT</option>
                        <option value="LC">LC</option>
                        <option value="LE">LE</option>
                    </select>
                </div>
                <div class="form-control">
                    <input type="text" id="doc-number" name="identification_number" placeholder="Número de documento"
                        required>
                </div>
                <div class="form-control">
                    <select id="installments-select" name="installments" required>
                        <option value="1">1 cuota (sin interés)</option>
                        <!-- Populated dynamically -->
                    </select>
                </div>
                <div id="card-errors" role="alert"></div>
                <button type="submit" class="btn btn-primary mt-3">Pagar ARS
                    {{ number_format($session->amount, 2) }}</button>
            </form>

            <a href="{{ route('parking.create') }}" class="btn btn-danger mt-3">Cancelar</a>
        @else
            <div class="alert alert-danger">
                Error: No se pudo crear la sesión. Intenta de nuevo.
            </div>
            <a href="{{ route('parking.create') }}" class="btn btn-primary">Volver</a>
        @endif
        <div id="walletBrick_container"></div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const bricksBuilder = mp.bricks();
        bricksBuilder.create('wallet', 'walletBrick_container', {
            initialization: {
                preferenceId: '{{ $preferenceId }}', // Must be passed from backend
            },
        });
        const mp = new MercadoPago('{{ $publicKey }}');
        const cardNumberElement = mp.fields.create('cardNumber', {
            placeholder: 'Número de tarjeta'
        }).mount('#card-number-container');
        const expirationDateElement = mp.fields.create('expirationDate', {
            placeholder: 'MM/AA'
        }).mount('#expiration-date-container');
        const securityCodeElement = mp.fields.create('securityCode', {
            placeholder: 'Código de seguridad'
        }).mount('#security-code-container');

        // Dynamically fetch payment methods and installments
        async function updatePaymentMethodsAndInstallments() {
            const cardNumberInput = document.querySelector('#card-number-container input');
            if (cardNumberInput.value.replace(/\s/g, '').length >= 6) {
                try {
                    const bin = cardNumberInput.value.replace(/\s/g, '').substring(0, 6);
                    const paymentMethods = await mp.getPaymentMethods({
                        bin
                    });
                    if (paymentMethods.results && paymentMethods.results.length > 0) {
                        document.getElementById('payment_method_id').value = paymentMethods.results[0].id;

                        // Fetch installments
                        const installments = await mp.getInstallments({
                            amount: '{{ $session->amount }}',
                            paymentMethodId: paymentMethods.results[0].id,
                            bin: bin
                        });
                        const installmentsSelect = document.getElementById('installments-select');
                        installmentsSelect.innerHTML = '<option value="1">1 cuota (sin interés)</option>';
                        if (installments[0]?.payer_costs) {
                            installments[0].payer_costs.forEach(cost => {
                                if (cost.installments > 1) {
                                    const option = document.createElement('option');
                                    option.value = cost.installments;
                                    option.textContent =
                                        `${cost.installments} cuotas de ARS ${parseFloat(cost.installment_amount).toFixed(2)}`;
                                    installmentsSelect.appendChild(option);
                                }
                            });
                        }
                    }
                } catch (e) {
                    console.error('Error fetching payment methods/installments:', e);
                }
            }
        }

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            document.getElementById('card-errors').textContent = '';

            // Validate fields
            const cardholderName = document.getElementById('cardholder-name').value;
            const docType = document.getElementById('doc-type').value;
            const docNumber = document.getElementById('doc-number').value;
            if (!cardholderName || !docType || !docNumber) {
                document.getElementById('card-errors').textContent = 'Por favor, completa todos los campos.';
                return;
            }

            try {
                const cardData = await mp.fields.createCardToken({
                    cardholderName: cardholderName,
                    identificationType: docType,
                    identificationNumber: docNumber,
                });

                if (cardData.token) {
                    document.getElementById('token').value = cardData.token;
                    form.submit();
                } else {
                    document.getElementById('card-errors').textContent = cardData.cause[0]?.message ||
                        'Error al procesar la tarjeta';
                }
            } catch (e) {
                document.getElementById('card-errors').textContent = e.message ||
                'Error al procesar la tarjeta';
                console.error(e);
            }
        });

        // Update payment methods and installments on card number input
        document.querySelector('#card-number-container input').addEventListener('change',
            updatePaymentMethodsAndInstallments);
    </script>
@endsection
