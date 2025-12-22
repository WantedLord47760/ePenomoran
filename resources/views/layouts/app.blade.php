<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penomoran Surat Digital')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Custom Styles -->
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
                    <span class="brand-icon">
                        <i class="bi bi-envelope-paper"></i>
                    </span>
                    <span>Penomoran Surat</span>
                </h4>
            </div>

            <!-- Navigation Menu -->
            <nav class="sidebar-nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a class="nav-link {{ request()->routeIs('surat.*') ? 'active' : '' }}" href="{{ route('surat.index') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Surat</span>
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'pemimpin']))
                    <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                        <i class="bi bi-bar-chart"></i>
                        <span>Laporan</span>
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Manajemen User</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('tipe-surat.*') ? 'active' : '' }}"
                        href="{{ route('tipe-surat.index') }}">
                        <i class="bi bi-folder"></i>
                        <span>Master Tipe Surat</span>
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
                        <p>{{ strtoupper(auth()->user()->role) }}</p>
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
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="userDropdown">
                            <div class="dropdown-header">
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
                <p>© {{ date('Y') }} Sistem Penomoran Surat Digital. All rights reserved.</p>
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
</body>

</html>