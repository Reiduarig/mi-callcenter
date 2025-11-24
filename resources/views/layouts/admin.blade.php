<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Center Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-light">

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" style="min-width: 220px; height: 100vh;">
            <h4>Call Center</h4>
            <ul class="nav flex-column mt-4">
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('staff.employees.index') }}" class="nav-link text-white">Empleados</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('staff.shifts.index') }}" class="nav-link text-white">Turnos</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('staff.absences.index') }}" class="nav-link text-white">Ausencias</a>
                </li>
               
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="flex-fill p-4">
            <nav class="navbar navbar-light bg-white mb-4">
                <span class="navbar-brand mb-0 h1">Bienvenido, {{ auth()->user()->name }}</span>
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
                    </form>
                </div>
            </nav>

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>