<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LOGIN - HBS CLASS TRACKER</title>
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
                <h1 class="glitch" data-text="AUTHORIZATION REQUIRED">AUTHORIZATION REQUIRED</h1>
                <p class="subtitle">PLEASE AUTHENTICATE TO ACCESS SYSTEM</p>
            </div>
        </header>

        <main class="main-terminal">
            <div class="auth-container">
                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">EMAIL ADDRESS</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="auth-input" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
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
                               autocomplete="current-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" class="checkbox-input">
                            <span>REMEMBER ME</span>
                        </label>
                    </div>

                    <button type="submit" class="auth-button">LOGIN</button>
                </form>

                <div class="auth-links">
                    <p>NEW USER? <a href="{{ route('register') }}" class="auth-link">REGISTER HERE</a></p>
                </div>
            </div>
        </main>

        <footer class="system-footer">
            <div class="footer-line">
                <span>SYSTEM STATUS: AWAITING AUTHENTICATION</span>
            </div>
        </footer>
    </div>
</body>
</html>

