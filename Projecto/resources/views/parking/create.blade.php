@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .custom-card {
        max-width: 900px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #1a3c6d;
        font-weight: 700;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    select, input[type="number"], input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    select:focus, input:focus {
        border-color: #007bff;
        outline: none;
    }

    .btn-blue {
        background-color: #007bff;
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-blue:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .form-header h2 {
        margin: 0;
    }

    .form-section {
        border-radius: 8px;
        overflow: hidden;
    }

    .form-section .form-title {
        background-color: #007bff;
        color: #fff;
        padding: 12px;
        font-weight: 600;
        font-size: 16px;
    }

    .form-section .form-body {
        padding: 20px;
    }
    .back-arrow {
    display: inline-block;
    font-size: 32px; /* más grande */
    font-weight: bold;
    color: #1a3c6d;
    text-decoration: none;
    margin-bottom: 15px;
    background: #fff;
    border-radius: 50%;
    padding: 8px 14px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.back-arrow:hover {
    background: #007bff;
    color: #fff;
    transform: scale(1.1);
}
</style>

<div class="custom-card">
    <div class="form-header">
        <h2>Registrar Estacionamiento</h2>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('dashboard') }}" class="back-arrow" title="Volver al inicio">
        &#8592;
    </a>

    <form action="{{ route('parking.store') }}" method="POST">
        @csrf

        <div class="form-section">
            <div class="form-title">Datos del Estacionamiento</div>
            <div class="form-body">
                
                <!-- Patente -->
                <div class="mb-4">
                    <label for="car_id">Vehículo (Patente)</label>
                    <input type="text" name="car_id" id="car_id" placeholder="Ingrese la patente sin espacios ni puntos">
                </div>

                <!-- Zona -->
                <div class="mb-4">
                    <label for="zone_id">Zona</label>
                    <select id="zona" class="form-control">
                        <option value="">Selecciona una zona</option>
                        <option value="zona1">Zona 1</option>
                        <option value="zona2">Zona 2</option>
                        <option value="zona3">Zona 3</option>
                    </select>
                    <div style="width: 70%; margin: 10px auto; text-align: center;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12612.156494877916!2d-58.511!3d-33.0079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bb5f9f9f9f9f9f%3A0x123456789!2zR3VhbGVndWF5Y2jDuw!5e0!3m2!1ses!2sar!4v1694000000000"
                            width="150%"
                            height="250"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Tiempo -->
                <div class="mb-4">
                    <label for="estimated_minutes">Tiempo estimado (minutos)</label>
                    <input type="time" name="estimated_minutes" id="estimated_minutes" min="15" step="15">
                </div>
                <br>
               
                <button id="iniciar-estacionamiento" class="btn btn-primary">Iniciar Estacionamiento</button>

                <!-- Modal de Pago -->
<div id="payment-modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 5px; width: 300px; text-align: center;">
        <h3>Pago de Estacionamiento</h3>
        <p>Seleccione un método de pago:</p>
        <button class="btn btn-secondary" style="margin: 5px;" onclick="selectPayment('mercadopago')">Mercado Pago</button>
        <div id="payment-details" style="display: none; margin-top: 10px;">
            <input type="text" id="mp-email" placeholder="Email de Mercado Pago" class="form-control">
            <input type="text" id="mp-amount" placeholder="Monto (ej. 100)" class="form-control" value="100">
            <div id="mercadopago-button-container"></div>
        </div>
        <button class="btn btn-danger" style="margin-top: 10px;" onclick="closeModal()">Cancelar</button>
    </div>
</div>

<!-- Incluir el SDK de Mercado Pago desde CDN -->
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    const mp = new MercadoPago('APP_USR-68914d21-8a4b-40f5-86ea-e699dbec6b69', {
        locale: 'es-AR'
    });

    document.getElementById('iniciar-estacionamiento').addEventListener('click', function() {
        document.getElementById('payment-modal').style.display = 'block';
    });

    function closeModal() {
        document.getElementById('payment-modal').style.display = 'none';
        document.getElementById('payment-details').style.display = 'none';
    }

    function selectPayment(method) {
        if (method === 'mercadopago') {
            document.getElementById('payment-details').style.display = 'block';
            createMercadoPagoCheckout();
        }
    }

    function createMercadoPagoCheckout() {
        const email = document.getElementById('mp-email').value;
        const amount = parseFloat(document.getElementById('mp-amount').value);

        const checkout = mp.checkout({
            preference: {
                items: [
                    {
                        title: 'Estacionamiento en Gualeguaychú',
                        unit_price: amount,
                        quantity: 1,
                    }
                ],
                payer: {
                    email: email
                },
                back_urls: {
                    success: window.location.href,
                    failure: window.location.href,
                    pending: window.location.href
                },
                auto_return: 'approved',
            },
            render: {
                container: '#mercadopago-button-container',
                label: 'Pagar con Mercado Pago'
            }
        });
    }
</script>

<style>
    .btn {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn-secondary {
        background-color: #6c757d;
    }
    .btn-danger {
        background-color: #dc3545;
    }
    .form-control {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

            </div>
        </div>
    </form>
</div>

@endsection
