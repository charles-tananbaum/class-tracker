<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>REGISTER - HBS CLASS TRACKER</title>
    <link rel="stylesheet" href="{{ asset('css/retro-scifi.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Share+Tech+Mono&display=swap');
    </style>
</head>
<body>
    <div class="terminal-container">
        <div class="scanline"></div>
        <div class="noise"></div>
        
        <header class="system-header">
            <div class="header-top">
                <span class="system-id">HBS CLASS TRACKER v1.0</span>
                <span class="system-date">{{ date('Y-m-d H:i:s') }}</span>
            </div>
            <div class="header-title">
                <h1 class="glitch" data-text="CREATE NEW ACCOUNT">CREATE NEW ACCOUNT</h1>
                <p class="subtitle">INITIALIZE USER PROFILE</p>
            </div>
        </header>

        <main class="main-terminal">
            <div class="auth-container">
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">FULL NAME</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="auth-input" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               autocomplete="name">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">EMAIL ADDRESS</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="auth-input" 
                               value="{{ old('email') }}" 
                               required
                               autocomplete="email">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">PASSWORD</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="auth-input" 
                               required
                               autocomplete="new-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">CONFIRM PASSWORD</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="auth-input" 
                               required
                               autocomplete="new-password">
                    </div>

                    <button type="submit" class="auth-button">REGISTER</button>
                </form>

                <div class="auth-links">
                    <p>ALREADY HAVE AN ACCOUNT? <a href="{{ route('login') }}" class="auth-link">LOGIN HERE</a></p>
                </div>
            </div>
        </main>

        <footer class="system-footer">
            <div class="footer-line">
                <span>SYSTEM STATUS: AWAITING REGISTRATION</span>
            </div>
        </footer>
    </div>
</body>
</html>

