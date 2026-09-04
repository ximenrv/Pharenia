<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Gestión de Menores (Niños)') }}</title>

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
        <a href="{{ route('admin.dashboard') }}" class="family-panel__back-btn" style="margin-bottom: 20px;">← {{ __('Regresar') }}</a>

        <header class="family-panel__header">
            <span class="family-panel__badge">{{ __('Área Restringida') }}</span>
            <h1>{{ __('Gestión de Menores (Niños)') }}</h1>
            <p>{{ __('Centro de control de los perfiles de Niños / Menores (menores de 13 años).') }}</p>
        </header>

        <!-- Métricas -->
        <div class="family-panel__grid pharenia-metrics">
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Total de Menores') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $minors->count() }}</p>
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

        <!-- Tabla de menores -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">{{ __('Listado de Menores') }}</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createMinorModal')">{{ __('Crear Menor +') }}</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Fecha Nac.') }}</th>
                            <th>{{ __('Edad') }}</th>
                            <th>{{ __('PIN Parental') }}</th>
                            <th>{{ __('Supervisor') }}</th>
                            <th style="text-align: center;">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
@forelse($minors as $minor)
                            <tr>
                                <td data-label="{{ __('ID') }}" style="font-weight: bold; color: #718096; text-align: center;">{{ $minor->id }}</td>
                                <td data-label="{{ __('Nombre') }}" style="font-weight: 600;">{{ $minor->name }}</td>
                                <td data-label="{{ __('Fecha Nac.') }}">{{ $minor->birthdate ? \Carbon\Carbon::parse($minor->birthdate)->toDateString() : 'N/D' }}</td>
                                <td data-label="{{ __('Edad') }}">{{ $minor->birthdate ? \Carbon\Carbon::parse($minor->birthdate)->age : 'N/D' }} {{ __('años') }}</td>
                                <td data-label="{{ __('PIN Parental') }}" style="font-family: monospace; font-weight: bold; color: #d69e2e;">{{ $minor->parent_pin }}</td>
                                <td data-label="{{ __('Supervisor') }}">
                                    @if($minor->user)
                                        <span style="font-weight: 600;">{{ $minor->user->name }}</span>
                                        <span style="font-family: monospace; color: #718096;">(#{{ $minor->user_id }})</span>
                                    @else
                                        <span style="color: #718096;">{{ __('N/D') }}</span>
                                    @endif
                                </td>
                                <td data-label="{{ __('Acciones') }}" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.minors.show', $minor->id) }}" class="pharenia-btn-secondary" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">{{ __('Ver') }}</a>
                                        <a href="{{ route('admin.minors.edit', $minor->id) }}" class="pharenia-btn-primary" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">{{ __('Editar') }}</a>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $minor->id }}', '{{ $minor->name }}')">{{ __('Eliminar') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="padding: 20px; text-align: center; color: #718096;">{{ __('No hay menores registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- MODAL: CREAR MENOR -->
    <div id="createMinorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createMinorModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Registrar Menor (<13 años)') }}</h3>
            <form action="{{ route('admin.minor.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>{{ __('NOMBRE DEL MENOR') }}</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" value="{{ old('birthdate') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('PIN PARENTAL (4 dígitos)') }}</label><input type="text" name="parent_pin" maxlength="4" value="{{ old('parent_pin') }}" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO DEL SUPERVISOR') }}</label><input type="email" name="tutor_email" value="{{ old('tutor_email') }}" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR MENOR') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div id="deleteModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content" style="max-width: 400px; text-align: center;">
            <h3 class="pharenia-modal-title" style="color: #e53e3e;">{{ __('Confirmar Eliminación') }}</h3>
            <p id="deleteMessage" style="color: #4a5568; margin-bottom: 25px; font-size: 14px;"></p>
            <form id="deleteForm" method="POST" action="{{ route('admin.minor.destroy', 0) }}" style="display: flex; gap: 10px; justify-content: center;">
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
            document.getElementById('deleteForm').action = `/admin/minor/${id}`;
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