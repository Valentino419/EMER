<!DOCTYPE html>
<html>
<head>
    <title>Redirigiendo a Mercado Pago</title>
</head>
<body onload="document.getElementById('paymentForm').submit();">
    <form id="paymentForm" action="{{ route('payment.initiate') }}" method="POST">
        @csrf
        @foreach ($parking_data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <p>Redirigiendo a Mercado Pago, por favor espera...</p>
    </form>
</body>
</html>