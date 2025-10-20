
<div class="container">
    <h2>Forgot Password</h2>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Send reset link</button>
    </form>
</div>

