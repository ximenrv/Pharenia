<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Detalle del Visitante') }}</title>

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
            <h1>{{ __('Detalle del Visitante') }}</h1>
            <p>{{ __('INFORMACIÓN GENERAL') }}</p>
        </header>

        <div class="pharenia-card" style="margin-bottom: 25px;">
            <h3 style="margin: 0 0 15px 0; color: #2f4f4f;">{{ __('INFORMACIÓN GENERAL') }}</h3>
            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 220px;">{{ __('Campo') }}</th>
                            <th>{{ __('Valor') }}</th>
                        </tr>
                    </thead>
                    <tbody>
<tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('ID') }}</td>
                            <td data-label="{{ __('Valor') }}" style="font-weight: bold; color: #718096;">{{ $visitor->id }}</td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Nombre') }}</td>
                            <td data-label="{{ __('Valor') }}" style="font-weight: 600;">{{ $visitor->name }}</td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Correo') }}</td>
                            <td data-label="{{ __('Valor') }}" style="color: #4a5568;">{{ $visitor->email }}</td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Fecha Nac.') }}</td>
                            <td data-label="{{ __('Valor') }}">{{ $visitor->birthdate ? \Carbon\Carbon::parse($visitor->birthdate)->toDateString() : 'N/D' }}</td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Edad') }}</td>
                            <td data-label="{{ __('Valor') }}">{{ $visitor->birthdate ? \Carbon\Carbon::parse($visitor->birthdate)->age : 'N/D' }} {{ __('años') }}</td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Rol') }}</td>
                            <td data-label="{{ __('Valor') }}"><span class="pharenia-role-badge">{{ $visitor->role }}</span></td>
                        </tr>
                        <tr>
                            <td data-label="{{ __('Campo') }}" style="font-weight: 600;">{{ __('Registrado el') }}</td>
                            <td data-label="{{ __('Valor') }}">{{ $visitor->created_at ? $visitor->created_at->format('d/m/Y H:i') : 'N/D' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 30px;">
            <a href="{{ route('admin.visitors.edit', $visitor->id) }}" class="pharenia-btn-primary" style="padding: 10px 18px; text-decoration: none;">{{ __('Editar') }}</a>
            <button type="button" class="pharenia-btn-danger" style="padding: 10px 18px;" onclick="openDeleteModal('{{ $visitor->id }}', '{{ $visitor->name }}')">{{ __('Eliminar') }}</button>
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