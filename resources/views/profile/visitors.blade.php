<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Gestión de Visitantes') }}</title>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>

    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/js/navbar.js', 'resources/css/footer.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/dark-theme.css'])
</head>
<body>
    @include('components.loader')

    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Área Restringida') }}</span>
            <h1>{{ __('Gestión de Visitantes') }}</h1>
            <p>{{ __('Centro de control de los usuarios registrados como Visitante General (mayores de 12 años).') }}</p>
        </header>

        <!-- Métricas -->
        <div class="family-panel__grid pharenia-metrics">
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Total de Visitantes') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $visitorCount }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="pharenia-alert pharenia-alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="pharenia-alert pharenia-alert--error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabla de visitantes -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">{{ __('Listado de Visitantes') }}</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createVisitorModal')">{{ __('Crear Visitante +') }}</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Correo') }}</th>
                            <th>{{ __('Fecha Nac.') }}</th>
                            <th>{{ __('Edad') }}</th>
                            <th>{{ __('Rol') }}</th>
                            <th style="text-align: center;">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
@forelse($visitors as $visitor)
                            <tr>
                                <td data-label="{{ __('ID') }}" style="font-weight: bold; color: #718096; text-align: center;">{{ $visitor->id }}</td>
                                <td data-label="{{ __('Nombre') }}" style="font-weight: 600;">{{ $visitor->name }}</td>
                                <td data-label="{{ __('Correo') }}" style="color: #4a5568;">{{ $visitor->email }}</td>
                                <td data-label="{{ __('Fecha Nac.') }}">{{ $visitor->birthdate ? \Carbon\Carbon::parse($visitor->birthdate)->toDateString() : 'N/D' }}</td>
                                <td data-label="{{ __('Edad') }}">{{ $visitor->birthdate ? \Carbon\Carbon::parse($visitor->birthdate)->age : 'N/D' }} {{ __('años') }}</td>
                                <td data-label="{{ __('Rol') }}"><span class="pharenia-role-badge">{{ $visitor->role }}</span></td>
                                <td data-label="{{ __('Acciones') }}" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.visitors.show', $visitor->id) }}" class="pharenia-btn-secondary" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">{{ __('Ver') }}</a>
                                        <a href="{{ route('admin.visitors.edit', $visitor->id) }}" class="pharenia-btn-primary" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">{{ __('Editar') }}</a>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $visitor->id }}', '{{ $visitor->name }}')">{{ __('Eliminar') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="padding: 20px; text-align: center; color: #718096;">{{ __('No hay visitantes registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL: CREAR VISITANTE -->
    <div id="createVisitorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createVisitorModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Registrar Visitante General') }}</h3>
            <form action="{{ route('admin.visitors.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" value="{{ old('birthdate') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CONTRASEÑA TEMPORAL') }}</label><input type="password" name="password" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR VISITANTE') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div id="deleteModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content" style="max-width: 400px; text-align: center;">
            <h3 class="pharenia-modal-title" style="color: #e53e3e;">{{ __('Confirmar Eliminación') }}</h3>
            <p id="deleteMessage" style="color: #4a5568; margin-bottom: 25px; font-size: 14px;"></p>
            <form id="deleteForm" method="POST" action="{{ route('admin.visitors.destroy', 0) }}" style="display: flex; gap: 10px; justify-content: center;">
                @csrf @method('DELETE')
                <button type="button" class="pharenia-btn-secondary" onclick="closeModal('deleteModal')">{{ __('Cancelar') }}</button>
                <button type="submit" class="pharenia-btn-danger" style="padding: 10px 20px;">{{ __('Sí, Eliminar') }}</button>
            </form>
        </div>
    </div>
<x-settings-menu />
    <x-footer/>

    <script>
        function openModal(modalId) { document.getElementById(modalId).style.display = 'flex'; }
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

        function openDeleteModal(id, name) {
            const message = `{{ __('¿Estás seguro de que deseas eliminar a') }} "${name}"{{ __('? Esta acción no se puede deshacer.') }}`;
            document.getElementById('deleteMessage').textContent = message;
            document.getElementById('deleteForm').action = `/admin/visitors/${id}`;
            openModal('deleteModal');
        }

        window.onclick = function(event) {
            if (event.target.className === 'pharenia-modal-overlay') {
                document.querySelectorAll('.pharenia-modal-overlay').forEach(modal => modal.style.display = 'none');
            }
        };
    </script>
</body>
</html>