<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HBS CLASS TRACKER - SYSTEM 1977</title>
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
                <span class="system-date" id="currentDate">{{ date('Y-m-d H:i:s') }}</span>
            </div>
            <div class="header-title">
                <h1 class="glitch" data-text="HARVARD BUSINESS SCHOOL">HARVARD BUSINESS SCHOOL</h1>
                <p class="subtitle">CLASS PARTICIPATION & GRADE MONITORING SYSTEM</p>
            </div>
        </header>

        <main class="main-terminal">
            <div class="classes-grid">
                @foreach($classes as $class)
                    <div class="class-panel" data-class-id="{{ $class->id }}">
                        <div class="panel-header">
                            <span class="class-code">{{ $class->code }}</span>
                            <span class="status-indicator" id="status-{{ $class->id }}">●</span>
                        </div>
                        <div class="panel-body">
                            <div class="class-name">{{ $class->name }}</div>
                            
                            <div class="participation-section">
                                <div class="section-label">PARTICIPATION LOG</div>
                                <div class="date-selector">
                                    <input type="date" 
                                           class="date-input" 
                                           id="date-{{ $class->id }}" 
                                           value="{{ date('Y-m-d') }}"
                                           data-class-id="{{ $class->id }}">
                                    @php
                                        $today = \Carbon\Carbon::today()->toDateString();
                                        $hasParticipation = $class->participations->contains(function($participation) use ($today) {
                                            return $participation->date->toDateString() === $today;
                                        });
                                    @endphp
                                    <button class="toggle-btn {{ $hasParticipation ? 'active' : '' }}" 
                                            data-class-id="{{ $class->id }}"
                                            data-participated="{{ $hasParticipation ? '1' : '0' }}">
                                        <span class="btn-text">{{ $hasParticipation ? 'ACTIVE' : 'INACTIVE' }}</span>
                                    </button>
                                </div>
                                <div class="participation-count">
                                    TOTAL: <span class="count-value">{{ $class->participations->count() }}</span> DAYS
                                </div>
                            </div>

                            <div class="grades-section">
                                <div class="section-label">GRADE MODULE</div>
                                <div class="grades-grid">
                                    <div class="grade-input-group">
                                        <label>MIDTERM</label>
                                        <input type="number" 
                                               class="grade-input" 
                                               id="midterm-{{ $class->id }}"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $class->grade->midterm ?? '' }}"
                                               placeholder="--">
                                    </div>
                                    <div class="grade-input-group">
                                        <label>HOMEWORK</label>
                                        <input type="number" 
                                               class="grade-input" 
                                               id="homework-{{ $class->id }}"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $class->grade->homework ?? '' }}"
                                               placeholder="--">
                                    </div>
                                    <div class="grade-input-group">
                                        <label>FINAL</label>
                                        <input type="number" 
                                               class="grade-input" 
                                               id="final-{{ $class->id }}"
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               value="{{ $class->grade->final ?? '' }}"
                                               placeholder="--">
                                    </div>
                                </div>
                                <button class="save-grades-btn" data-class-id="{{ $class->id }}">SAVE GRADES</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>

        <footer class="system-footer">
            <div class="footer-line">
                <span>SYSTEM STATUS: OPERATIONAL</span>
                <span>|</span>
                <span>CLASSES MONITORED: {{ $classes->count() }}</span>
                <span>|</span>
                <span>LAST UPDATE: <span id="lastUpdate">{{ date('H:i:s') }}</span></span>
            </div>
        </footer>
    </div>

    <script>
        // CSRF token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Update date display
        function updateDate() {
            const now = new Date();
            document.getElementById('currentDate').textContent = now.toISOString().slice(0, 19).replace('T', ' ');
            document.getElementById('lastUpdate').textContent = now.toTimeString().slice(0, 8);
        }
        setInterval(updateDate, 1000);

        // Toggle participation
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const classId = this.dataset.classId;
                const dateInput = document.getElementById('date-' + classId);
                const date = dateInput.value;
                
                fetch(`/classes/${classId}/participation`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ date: date })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        this.dataset.participated = '1';
                        this.querySelector('.btn-text').textContent = 'ACTIVE';
                        this.classList.add('active');
                        updateParticipationCount(classId);
                    } else {
                        this.dataset.participated = '0';
                        this.querySelector('.btn-text').textContent = 'INACTIVE';
                        this.classList.remove('active');
                        updateParticipationCount(classId);
                    }
                });
            });
        });

        // Save grades
        document.querySelectorAll('.save-grades-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const classId = this.dataset.classId;
                const midterm = document.getElementById('midterm-' + classId).value;
                const homework = document.getElementById('homework-' + classId).value;
                const final = document.getElementById('final-' + classId).value;
                
                fetch(`/classes/${classId}/grade`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        midterm: midterm || null,
                        homework: homework || null,
                        final: final || null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.textContent = 'SAVED ✓';
                        setTimeout(() => {
                            this.textContent = 'SAVE GRADES';
                        }, 2000);
                    }
                });
            });
        });

        // Update participation count (would need to fetch from server)
        function updateParticipationCount(classId) {
            const countElement = document.querySelector(`[data-class-id="${classId}"] .count-value`);
            // In a real app, you'd fetch the updated count from the server
            // For now, just increment/decrement locally
            const currentCount = parseInt(countElement.textContent);
            const btn = document.querySelector(`.toggle-btn[data-class-id="${classId}"]`);
            if (btn.dataset.participated === '1') {
                countElement.textContent = currentCount + 1;
            } else {
                countElement.textContent = Math.max(0, currentCount - 1);
            }
        }

        // Date input change handler
        document.querySelectorAll('.date-input').forEach(input => {
            input.addEventListener('change', function() {
                const classId = this.dataset.classId;
                // Check if this date has participation
                // In a real app, you'd fetch this from the server
                // For now, we'll just reset the button state
            });
        });
    </script>
</body>
</html>

