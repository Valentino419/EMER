@extends('layouts.app')

@section('content')
    <style>
        /* Your existing styles for container, table, etc. */
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
                        <td>${{ number_format($session->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Inicio</th>
                        <td>{{ $session->start_time->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>

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
                <div id="card-element" class="form-control"></div>
                <div id="card-errors" role="alert"></div>
                <button type="submit" class="btn btn-primary mt-3">Pagar ${{ number_format($session->amount, 2) }}</button>
            </form>

            <a href="{{ route('parking.create') }}" class="btn btn-danger mt-3">Cancelar</a>
        @else
            <div class="alert alert-danger">
                Error: No se pudo crear la sesión. Intenta de nuevo.
            </div>
            <a href="{{ route('parking.create') }}" class="btn btn-primary">Volver</a>
        @endif
    </div>

  <script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago('{{ $publicKey }}');
    
    // Create card fields (adapt IDs to your form)
    const cardNumberElement = mp.fields.create('cardNumber', { placeholder: 'Número de tarjeta' }).mount('card-number-container');
    const expirationDateElement = mp.fields.create('expirationDate', { placeholder: 'MM/AA' }).mount('expiration-date-container');
    const securityCodeElement = mp.fields.create('securityCode', { placeholder: 'Código de seguridad' }).mount('security-code-container');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        try {
            const { token, error } = await mp.createCardToken({
                cardNumber: document.getElementById('card-number').value, // Or use field values
                cardholderName: document.getElementById('cardholder-name').value,
                cardExpirationMonth: ..., // Extract from expiration
                cardExpirationYear: ...,
                securityCode: document.getElementById('security-code').value,
                identificationType: document.getElementById('doc-type').value,
                identificationNumber: document.getElementById('doc-number').value,
            });
            if (error) {
                // Show error
                document.getElementById('card-errors').textContent = error.message;
            } else {
                // Add token to form and submit
                document.getElementById('token').value = token.id;
                form.submit();
            }
        } catch (e) {
            console.error(e);
        }
    });
</script>

<!-- Form example -->
<form id="payment-form" action="{{ route('payment.confirm') }}" method="POST">
    @csrf
    <input type="hidden" name="session_id" value="{{ $session->id }}">
    <input type="hidden" id="token" name="token">
    <!-- Card fields containers -->
    <div id="card-number-container"></div>
    <div id="expiration-date-container"></div>
    <div id="security-code-container"></div>
    <!-- Other fields: cardholder name, doc type/number, etc. -->
    <div id="card-errors"></div>
    <button type="submit">Pagar</button>
</form>