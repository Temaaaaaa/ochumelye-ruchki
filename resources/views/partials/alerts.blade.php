@if (session('success') || session('error') || session('info') || $errors->any())
    <div class="row">
        <div class="row--small flash-stack">
            @if (session('success'))
                <div class="flash flash--success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash flash--error">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="flash flash--info">{{ session('info') }}</div>
            @endif
            @if ($errors->any())
                <div class="flash flash--error">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endif
