@extends('index')

@section('content')
    <style>
        /* SMOOTH TRANSITIONS */
        .transition-smooth { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* PRINT STYLES */
        @media print {
            button, .no-print { display: none !important; }
            .print-visible { display: block !important; }
            body { background: white !important; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .card { break-inside: avoid; border: 1px solid #cbd5e1; box-shadow: none !important; }
            .shadow-soft { box-shadow: none !important; }
        }

        /* CUSTOM CARD SHADOWS */
        .shadow-soft { box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05); }
    </style>

    <div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4 no-print">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reports</h1>
                </div>
                <p class="text-slate-500 font-medium ml-1">Performance overview for <span class="text-indigo-600 font-bold">{{ date('F Y') }}</span></p>
            </div>
            
            <div class="flex gap-3">
                <div class="relative">
                    <button id="dateDropdownBtn" onclick="toggleDropdown()" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:border-indigo-500 hover:text-indigo-600 transition-smooth shadow-sm group">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span id="selectedDateRange">This Year</span>
                        <svg class="w-3 h-3 ml-1 text-slate-300 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div id="dateDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden ring-1 ring-black ring-opacity-5">
                        <a href="#" onclick="selectRange('Last 7 Days')" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors">Last 7 Days</a>
                        <a href="#" onclick="selectRange('Last 30 Days')" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors">Last 30 Days</a>
                        <a href="#" onclick="selectRange('This Month')" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors">This Month</a>
                        <a href="#" onclick="selectRange('This Year')" class="block px-4 py-2.5 text-sm text-indigo-700 bg-indigo-50 font-bold transition-colors">This Year</a>
                    </div>
                </div>

                <button onclick="window.print()" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-slate-200 transition-smooth active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"></path></svg>
                    Print PDF
                </button>
            </div>
        </div>

        <div class="hidden print-visible mb-8 border-b border-slate-200 pb-4">
            <h1 class="text-3xl font-bold text-slate-900">Tejada Dent Admin Reports</h1>
            <p class="text-slate-500">{{ date('F j, Y') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-l-emerald-500 border-y border-r border-slate-100 card transition-smooth hover:shadow-md hover:scale-[1.01]">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Appointments</p>
                        <h2 class="text-4xl font-black text-slate-800 mt-1" id="total-appointments-display">0</h2>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="inline-flex items-center gap-2 py-1.5 px-3 bg-emerald-50 rounded-full border border-emerald-100">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span class="text-xs font-bold text-emerald-700">Tracking Well</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-l-blue-500 border-y border-r border-slate-100 card transition-smooth hover:shadow-md hover:scale-[1.01]">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Top Service</p>
                    <div class="p-2 bg-blue-50 text-blue-500 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mt-2 leading-tight min-h-[3rem] line-clamp-2" id="top-service-display">Loading...</h2>
                <p class="text-xs text-slate-500 font-medium mt-2">Most booked by patients</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-soft border-l-4 border-l-violet-500 border-y border-r border-slate-100 card transition-smooth hover:shadow-md hover:scale-[1.01]">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Success Rate</p>
                        <h2 class="text-4xl font-black text-slate-800 mt-1" id="completion-rate-display">0%</h2>
                    </div>
                    <div id="completion-badge-container">
                        </div>
                </div>
                <p class="text-xs text-slate-500">Completed appointments ratio</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-soft border border-slate-100 card">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Appointment Traffic</h3>
                        <p class="text-sm text-slate-500 font-medium">Daily patient volume</p>
                    </div>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-soft border border-slate-100 card flex flex-col">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Outcome Overview</h3>
                    <p class="text-sm text-slate-500 font-medium">Status breakdown</p>
                </div>
                
                <div class="relative h-48 flex justify-center items-center my-auto">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-black text-slate-800" id="center-total">0</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                    </div>
                </div>

                <div class="space-y-3 mt-6" id="status-legend-container">
                    </div>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-3xl shadow-soft border border-slate-100 card mb-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">Service Popularity</h3>
                <p class="text-sm text-slate-500 font-medium">Which treatments are in demand?</p>
            </div>
            <div class="relative h-64">
                <canvas id="servicesChart"></canvas>
            </div>
        </div>
    </div>

    <div id="chart-data"
        data-dates="{{ json_encode($dates) }}"
        data-totals="{{ json_encode($totals) }}"
        data-status-labels="{{ json_encode($statusLabels) }}"
        data-status-counts="{{ json_encode($statusCounts) }}"
        data-service-names="{{ json_encode($serviceNames) }}"
        data-service-counts="{{ json_encode($serviceCounts) }}"
        class="hidden">
    </div>

    <script src="{{ asset('js/chart.umd.js') }}"></script>
    
    <script>
        // --- 1. DROPDOWN LOGIC ---
        function toggleDropdown() { document.getElementById('dateDropdownMenu').classList.toggle('hidden'); }
        function selectRange(range) {
            document.getElementById('selectedDateRange').innerText = range;
            document.getElementById('dateDropdownMenu').classList.add('hidden');
        }
        window.addEventListener('click', function(e) {
            const btn = document.getElementById('dateDropdownBtn');
            const menu = document.getElementById('dateDropdownMenu');
            if (!btn.contains(e.target) && !menu.contains(e.target)) { menu.classList.add('hidden'); }
        });

        // --- 2. DATA PARSING ---
        const dataElement = document.getElementById('chart-data');
        const dailyDates = JSON.parse(dataElement.dataset.dates);
        const dailyTotals = JSON.parse(dataElement.dataset.totals);
        const statusLabels = JSON.parse(dataElement.dataset.statusLabels);
        const statusCounts = JSON.parse(dataElement.dataset.statusCounts);
        const serviceNames = JSON.parse(dataElement.dataset.serviceNames);
        const serviceCounts = JSON.parse(dataElement.dataset.serviceCounts);

        // --- 3. FRIENDLY KPI LOGIC ---
        
        // A. Total
        const totalAppts = dailyTotals.reduce((a, b) => a + b, 0);
        document.getElementById('total-appointments-display').innerText = totalAppts;
        document.getElementById('center-total').innerText = totalAppts;

        // B. Top Service
        if (serviceNames.length > 0) {
            const maxCount = Math.max(...serviceCounts);
            const maxIndex = serviceCounts.indexOf(maxCount);
            document.getElementById('top-service-display').innerText = serviceNames[maxIndex];
        } else {
            document.getElementById('top-service-display').innerText = "No bookings yet";
        }

        // C. Success Rate Badge
        const completedIndex = statusLabels.indexOf('Completed');
        if (completedIndex !== -1 && totalAppts > 0) {
            const rate = Math.round((statusCounts[completedIndex] / totalAppts) * 100);
            document.getElementById('completion-rate-display').innerText = rate + "%";
            
            const badgeContainer = document.getElementById('completion-badge-container');
            let badgeClass = "";
            let badgeText = "";
            
            if(rate >= 80) {
                badgeClass = "bg-violet-100 text-violet-700 border-violet-200";
                badgeText = "Excellent 🌟";
            } else if (rate >= 50) {
                badgeClass = "bg-blue-100 text-blue-700 border-blue-200";
                badgeText = "Good 👍";
            } else {
                badgeClass = "bg-amber-100 text-amber-700 border-amber-200";
                badgeText = "Needs Focus ⚠️";
            }
            
            badgeContainer.innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-bold border ${badgeClass}">${badgeText}</span>`;
        } else {
            document.getElementById('completion-badge-container').innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">No Data</span>`;
        }

        // --- 4. CHART CONFIGURATION ---
        Chart.defaults.font.family = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.color = '#64748b'; 
        
        // Bar Chart (Traffic)
        new Chart(document.getElementById('dailyChart').getContext('2d'), {
            type: 'bar',
            data: { 
                labels: dailyDates, 
                datasets: [{ 
                    label: 'Appointments', 
                    data: dailyTotals, 
                    backgroundColor: '#10b981', // Emerald
                    hoverBackgroundColor: '#059669',
                    borderRadius: 6, 
                    barThickness: 'flex',
                    maxBarThickness: 40
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    y: { beginAtZero: true, border: { display: false }, grid: { borderDash: [4, 4], drawBorder: false, color: '#f1f5f9' }, ticks: { padding: 10, font: { weight: '600' } } }, 
                    x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: '600', size: 11 } } } 
                } 
            }
        });

        // Donut Chart (Status)
        const statusColors = ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6']; 
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: { 
                labels: statusLabels, 
                datasets: [{ 
                    data: statusCounts, 
                    backgroundColor: statusColors, 
                    borderWidth: 2, 
                    borderColor: '#ffffff',
                    hoverOffset: 12, 
                    cutout: '80%' 
                }] 
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                animation: { animateScale: true } 
            }
        });

        // Legend Generator
        const legendContainer = document.getElementById('status-legend-container');
        statusLabels.forEach((label, index) => {
            const color = statusColors[index % statusColors.length];
            const count = statusCounts[index];
            const percentage = totalAppts > 0 ? Math.round((count / totalAppts) * 100) : 0;
            
            legendContainer.innerHTML += `
                <div class="flex items-center justify-between group cursor-default">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full ring-2 ring-slate-50" style="background-color: ${color}"></span>
                        <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">${label}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-sm font-bold text-slate-800">${count}</span>
                        <span class="block text-[10px] text-slate-400 font-medium">${percentage}%</span>
                    </div>
                </div>`;
        });

        // Horizontal Bar Chart (Services)
        new Chart(document.getElementById('servicesChart').getContext('2d'), {
            type: 'bar',
            data: { 
                labels: serviceNames, 
                datasets: [{ 
                    label: 'Bookings', 
                    data: serviceCounts, 
                    backgroundColor: '#6366f1', // Indigo
                    hoverBackgroundColor: '#4f46e5',
                    borderRadius: 6, 
                    barPercentage: 0.6, 
                }] 
            },
            options: { 
                indexAxis: 'y', 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    x: { beginAtZero: true, grid: { borderDash: [4, 4], drawBorder: false, color: '#f1f5f9' }, border: { display: false } }, 
                    y: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: '600' }, color: '#475569' } } 
                } 
            }
        });
    </script>
@endsection