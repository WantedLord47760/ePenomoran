@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Live Search Bar -->
        <div class="row mb-4">
            <div class="col-lg-6 mx-auto">
                <div class="search-container position-relative">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="liveSearch" class="form-control border-start-0"
                            placeholder="Cari nomor surat, perihal, nama pembuat..." autocomplete="off">
                    </div>
                    <div id="searchResults" class="search-results-dropdown"></div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-purple">
                    <div class="stat-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5>Total Surat</h5>
                    <h2>{{ $totalSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-amber">
                    <div class="stat-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5>Pending</h5>
                    <h2>{{ $pendingSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-green">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h5>Approved</h5>
                    <h2>{{ $approvedSurats }}</h2>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card stat-red">
                    <div class="stat-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h5>Rejected</h5>
                    <h2>{{ $rejectedSurats }}</h2>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Main Chart: All Letters -->
            <div class="col-lg-6">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-graph-up me-2"></i>
                            Total Semua Surat per Bulan ({{ $currentYear }})
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <canvas id="mainChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Surat Keluar Chart -->
            <div class="col-lg-6">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-envelope-arrow-up me-2"></i>
                            Surat Keluar per Bulan ({{ $currentYear }})
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <canvas id="keluarChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-3">
                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>SPT per Bulan ({{ $currentYear }})
                        </h6>
                    </div>
                    <div class="card-body p-3 pt-0">
                        <canvas id="sptChart" height="180"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-glass">
                    <div class="card-header bg-transparent border-0 p-3">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-check me-2"></i>Surat Cuti per Bulan ({{ $currentYear }})
                        </h6>
                    </div>
                    <div class="card-body p-3 pt-0">
                        <canvas id="cutiChart" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Letters Table -->
        <div class="card-glass">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-file-text me-2"></i>
                    Surat Terbaru
                </h5>
            </div>
            <div class="card-body p-4 pt-0">
                @if($recentSurats->count() > 0)
                    <div class="table-responsive">
                        <table class="table card-table">
                            <thead>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <th>Tipe Surat</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan</th>
                                    <th>Pembuat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSurats as $surat)
                                    <tr>
                                        <td>
                                            @if($surat->hasNumber())
                                                <strong>{{ $surat->nomor_surat_full }}</strong>
                                            @else
                                                <span class="badge bg-secondary">Draft/Menunggu</span>
                                            @endif
                                        </td>
                                        <td>{{ $surat->tipeSurat->jenis_surat }}</td>
                                        <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($surat->tujuan, 30) }}</td>
                                        <td>{{ $surat->user->name }}</td>
                                        <td>
                                            <span class="badge {{ $surat->getStatusBadgeClass() }}">
                                                {{ $surat->getStatusLabel() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada surat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .search-container {
            position: relative;
        }

        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 0 0 12px 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
        }

        .search-results-dropdown.show {
            display: block;
        }

        .search-result-item {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-result-item:hover {
            background-color: rgba(14, 165, 233, 0.1);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .bidang-suggestion {
            background-color: rgba(14, 165, 233, 0.05);
            color: var(--primary-gradient-start);
        }

        .input-group-text {
            background: white;
        }

        #liveSearch {
            background: white;
            border-radius: 12px;
        }

        .input-group {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
    </style>

    <script>
        // Sky Blue color palette
        const skyBlueLight = 'rgba(56, 189, 248, 0.2)';
        const skyBlueMedium = 'rgba(14, 165, 233, 0.5)';
        const skyBlueSolid = 'rgb(14, 165, 233)';
        const skyBlueDark = 'rgb(2, 132, 199)';

        // Chart labels
        const chartLabels = @json($chartLabels);

        // Main Chart - Line/Bar
        const mainCtx = document.getElementById('mainChart').getContext('2d');
        new Chart(mainCtx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Semua Surat',
                    data: @json($chartDataAll),
                    backgroundColor: skyBlueLight,
                    borderColor: skyBlueSolid,
                    borderWidth: 2,
                    borderRadius: 8,
                    barThickness: 30,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Surat Keluar Chart - Line (same as SPT and Cuti)
        const keluarCtx = document.getElementById('keluarChart').getContext('2d');
        new Chart(keluarCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Surat Keluar',
                    data: @json($chartDataKeluar),
                    borderColor: 'rgb(2, 132, 199)',
                    backgroundColor: 'rgba(56, 189, 248, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgb(2, 132, 199)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });


        // SPT Chart - Line
        const sptCtx = document.getElementById('sptChart').getContext('2d');
        new Chart(sptCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'SPT',
                    data: @json($chartDataSPT),
                    borderColor: skyBlueSolid,
                    backgroundColor: skyBlueLight,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: skyBlueSolid,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, display: false },
                    x: { display: false }
                }
            }
        });

        // Cuti Chart - Line
        const cutiCtx = document.getElementById('cutiChart').getContext('2d');
        new Chart(cutiCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Cuti',
                    data: @json($chartDataCuti),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, display: false },
                    x: { display: false }
                }
            }
        });

        // Live Search with AJAX
        let searchTimeout;
        const searchInput = document.getElementById('liveSearch');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchResults.classList.remove('show');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';

                        // Bidang suggestions
                        if (data.bidang_suggestions && data.bidang_suggestions.length > 0) {
                            data.bidang_suggestions.forEach(s => {
                                html += `<div class="search-result-item bidang-suggestion">
                                                <i class="bi bi-building me-2"></i>
                                                <strong>${s.abbr}</strong> → ${s.text}
                                            </div>`;
                            });
                        }

                        // Surat results
                        if (data.surats && data.surats.length > 0) {
                            data.surats.forEach(s => {
                                html += `<a href="${s.url}" class="search-result-item d-block text-decoration-none text-dark">
                                                <div class="d-flex justify-content-between">
                                                    <strong>${s.nomor}</strong>
                                                    <small class="text-muted">${s.bidang}</small>
                                                </div>
                                                <div class="text-muted small">${s.perihal}</div>
                                                <div class="text-muted small"><i class="bi bi-person me-1"></i>${s.pembuat}</div>
                                            </a>`;
                            });
                        }

                        if (html === '') {
                            html = '<div class="search-result-item text-muted">Tidak ada hasil ditemukan</div>';
                        }

                        searchResults.innerHTML = html;
                        searchResults.classList.add('show');
                    });
            }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('show');
            }
        });
    </script>
@endsection