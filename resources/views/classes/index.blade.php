<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HBS CLASS TRACKER - SYSTEM 1977</title>
    <link rel="stylesheet" href="{{ asset('css/retro-scifi.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                <div class="user-info">
                    <span>USER: {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">LOGOUT</button>
                    </form>
                </div>
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

            <!-- Summary Table -->
            <div class="summary-section">
                <div class="section-label">CLASS SUMMARY REPORT</div>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>CLASS CODE</th>
                            <th>CLASS NAME</th>
                            <th>PARTICIPATION DAYS</th>
                            <th>MIDTERM</th>
                            <th>HOMEWORK</th>
                            <th>FINAL</th>
                            <th>AVERAGE GRADE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $class)
                            @php
                                $participationCount = $class->participations->count();
                                $midterm = $class->grade->midterm ?? null;
                                $homework = $class->grade->homework ?? null;
                                $final = $class->grade->final ?? null;
                                $grades = array_filter([$midterm, $homework, $final], function($g) { return $g !== null; });
                                $average = count($grades) > 0 ? round(array_sum($grades) / count($grades), 2) : null;
                            @endphp
                            <tr data-class-id="{{ $class->id }}">
                                <td class="class-code-cell">{{ $class->code }}</td>
                                <td>{{ $class->name }}</td>
                                <td class="participation-cell" id="summary-participation-{{ $class->id }}">{{ $participationCount }}</td>
                                <td class="grade-cell" id="summary-midterm-{{ $class->id }}">{{ $midterm !== null ? number_format($midterm, 2) : '--' }}</td>
                                <td class="grade-cell" id="summary-homework-{{ $class->id }}">{{ $homework !== null ? number_format($homework, 2) : '--' }}</td>
                                <td class="grade-cell" id="summary-final-{{ $class->id }}">{{ $final !== null ? number_format($final, 2) : '--' }}</td>
                                <td class="average-cell" id="summary-average-{{ $class->id }}">{{ $average !== null ? number_format($average, 2) : '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="summary-total">
                            <td colspan="2">TOTAL</td>
                            <td id="total-participation">{{ $classes->sum(fn($c) => $c->participations->count()) }}</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
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

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // CSRF token setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Initialize Flatpickr for all date inputs
        document.querySelectorAll('.date-input').forEach(input => {
            flatpickr(input, {
                dateFormat: "Y-m-d",
                theme: "dark",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    const classId = input.dataset.classId;
                    checkParticipationForDate(classId, dateStr);
                }
            });
        });
        
        // Check participation status for a specific date
        function checkParticipationForDate(classId, date) {
            const btn = document.querySelector(`.toggle-btn[data-class-id="${classId}"]`);
            
            // Fetch participation status for this date
            fetch(`/classes/${classId}/participation?date=${date}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.has_participation) {
                    btn.dataset.participated = '1';
                    btn.querySelector('.btn-text').textContent = 'ACTIVE';
                    btn.classList.add('active');
                } else {
                    btn.dataset.participated = '0';
                    btn.querySelector('.btn-text').textContent = 'INACTIVE';
                    btn.classList.remove('active');
                }
            })
            .catch(() => {
                // Fallback: reset to inactive
                btn.dataset.participated = '0';
                btn.querySelector('.btn-text').textContent = 'INACTIVE';
                btn.classList.remove('active');
            });
        }
        
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
                        updateSummaryTable(classId, midterm, homework, final);
                    }
                });
            });
        });

        // Update participation count
        function updateParticipationCount(classId) {
            const countElement = document.querySelector(`[data-class-id="${classId}"] .count-value`);
            const summaryCount = document.getElementById(`summary-participation-${classId}`);
            const currentCount = parseInt(countElement.textContent);
            const btn = document.querySelector(`.toggle-btn[data-class-id="${classId}"]`);
            
            let newCount;
            if (btn.dataset.participated === '1') {
                newCount = currentCount + 1;
            } else {
                newCount = Math.max(0, currentCount - 1);
            }
            
            countElement.textContent = newCount;
            if (summaryCount) {
                summaryCount.textContent = newCount;
            }
            
            // Update total participation
            updateTotalParticipation();
        }
        
        // Update summary table with new grades
        function updateSummaryTable(classId, midterm, homework, final) {
            const midtermCell = document.getElementById(`summary-midterm-${classId}`);
            const homeworkCell = document.getElementById(`summary-homework-${classId}`);
            const finalCell = document.getElementById(`summary-final-${classId}`);
            const averageCell = document.getElementById(`summary-average-${classId}`);
            
            if (midtermCell) midtermCell.textContent = midterm ? parseFloat(midterm).toFixed(2) : '--';
            if (homeworkCell) homeworkCell.textContent = homework ? parseFloat(homework).toFixed(2) : '--';
            if (finalCell) finalCell.textContent = final ? parseFloat(final).toFixed(2) : '--';
            
            // Calculate average
            const grades = [midterm, homework, final].filter(g => g !== null && g !== '');
            if (grades.length > 0 && averageCell) {
                const avg = grades.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / grades.length;
                averageCell.textContent = avg.toFixed(2);
            } else if (averageCell) {
                averageCell.textContent = '--';
            }
        }
        
        // Update total participation count
        function updateTotalParticipation() {
            const participationCells = document.querySelectorAll('.participation-cell');
            let total = 0;
            participationCells.forEach(cell => {
                total += parseInt(cell.textContent) || 0;
            });
            const totalCell = document.getElementById('total-participation');
            if (totalCell) {
                totalCell.textContent = total;
            }
        }

    </script>
</body>
</html>

