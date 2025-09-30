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

    <script src="https://js.stripe.com/v3/"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.publishable_key') }}');  // Use publishable key!
    const elements = stripe.elements();
    const card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#333',
                '::placeholder': {
                    color: '#6c757d'
                }
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
            '{{ $clientSecret }}', {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: '{{ auth()->user()->name ?? 'Usuario' }}'
                    }
                }
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
@endsection
