<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $app_settings['app_name'] ?? 'E-Num')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Custom Styles - Borderless Elegance -->
    <link rel="stylesheet" href="{{ asset('css/custom-styles.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-sky">
    @auth
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <!-- Brand -->
            <div class="sidebar-brand">
                <h4>
                    @if(!empty($app_settings['app_logo']))
                        <img src="{{ asset('storage/' . $app_settings['app_logo']) }}" alt="Logo" class="brand-logo">
                    @else
                        <span class="brand-icon">
                            <i class="bi bi-hash"></i>
                        </span>
                    @endif
                    <span>{{ $app_settings['app_name'] ?? 'E-Num' }}</span>
                </h4>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'admin_surat_keluar', 'pemimpin', 'operator', 'pegawai']))
                    <a class="nav-link {{ request()->routeIs('surat.*') ? 'active' : '' }}" href="{{ route('surat.index') }}">
                        <i class="bi bi-envelope"></i>
                        <span>Surat Keluar</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'admin_surat_masuk', 'pemimpin']))
                    <a class="nav-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}"
                        href="{{ route('surat-masuk.index') }}">
                        <i class="bi bi-envelope-open"></i>
                        <span>Surat Masuk</span>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['admin', 'admin_surat_keluar', 'pemimpin', 'operator']))
                    <a class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}"
                        href="{{ route('pegawai.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Manajemen Pegawai</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <div class="nav-divider"></div>
                    <div class="nav-section-title">Administrator</div>

                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-person-gear"></i>
                        <span>Master User</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('tipe-surat.*') ? 'active' : '' }}"
                        href="{{ route('tipe-surat.index') }}">
                        <i class="bi bi-folder2"></i>
                        <span>Tipe Surat</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                        href="{{ route('settings.index') }}">
                        <i class="bi bi-gear"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}"
                        href="{{ route('admin.permissions') }}">
                        <i class="bi bi-shield-lock"></i>
                        <span>Pengaturan Role</span>
                    </a>
                @endif
            </nav>

            <!-- User Profile Section -->
            <div class="sidebar-user">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-details">
                        <h6>{{ auth()->user()->name }}</h6>
                        <p>{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button class="hamburger-menu" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                        <h1 class="navbar-title">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--dark-text);">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2 d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <div class="dropdown-header px-3 py-2">
                                <strong>{{ auth()->user()->name }}</strong><br>
                                <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('password.change') }}">
                                <i class="bi bi-key me-2"></i>Ubah Password
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="p-4">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer">
                <p>{{ $app_settings['footer_text'] ?? '© ' . date('Y') . ' E-Num - Sistem Penomoran Surat Digital' }}</p>
            </footer>
        </div>
    @else
        <main class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    @endauth

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }
        });
    </script>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 on all searchable selects -->
    <script>
        $(document).ready(function () {
            // Initialize Select2 on all elements with .searchable-select class
            $('.searchable-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder') || '-- Pilih --';
                },
                allowClear: true,
                language: {
                    noResults: function () {
                        return "Tidak ditemukan";
                    },
                    searching: function () {
                        return "Mencari...";
                    },
                    inputTooShort: function () {
                        return "Ketik untuk mencari...";
                    }
                }
            });

            // Move search box inside results (inline search)
            $(document).on('select2:open', function () {
                // Get the search field
                let searchField = document.querySelector('.select2-container--open .select2-search--dropdown .select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            });
        });
    </script>

    <style>
        .nav-divider {
            height: 1px;
            background: var(--light-gray);
            margin: 1rem 1.25rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--medium-gray);
            padding: 0.5rem 1.25rem;
        }

        /* Select2 Custom Styling - Borderless Elegance */
        .select2-container--bootstrap-5 .select2-selection {
            border: none !important;
            border-bottom: 2px solid var(--light-gray) !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding: 0.5rem 0 !important;
            min-height: 42px !important;
        }

        .select2-container--bootstrap-5 .select2-selection:focus,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-bottom-color: var(--primary-blue) !important;
            box-shadow: none !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            color: var(--dark-text) !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            right: 0 !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12) !important;
            margin-top: 4px !important;
            overflow: hidden;
        }

        /* Search box styling - inline at top of results */
        .select2-container--bootstrap-5 .select2-search--dropdown {
            padding: 8px 12px !important;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border: 1px solid #e9ecef !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            background: white !important;
            font-size: 0.9rem;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary-blue) !important;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1) !important;
            outline: none;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field::placeholder {
            color: #adb5bd;
        }

        .select2-container--bootstrap-5 .select2-results {
            max-height: 280px !important;
        }

        .select2-container--bootstrap-5 .select2-results__options {
            max-height: 250px !important;
            padding: 4px !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            padding: 10px 14px !important;
            border-radius: 6px !important;
            margin: 2px 0 !important;
            font-size: 0.9rem;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background: var(--primary-blue-light) !important;
            color: var(--primary-blue) !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--selected {
            background: var(--primary-blue) !important;
            color: white !important;
        }

        .select2-container--bootstrap-5 .select2-selection__clear {
            color: var(--medium-gray) !important;
            margin-right: 5px;
        }

        .select2-container--bootstrap-5 .select2-selection__clear:hover {
            color: #dc3545 !important;
        }
    </style>
</body>

</html>