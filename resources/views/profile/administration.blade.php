<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharenia - {{ __('Panel de Administración') }} </title>

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
            <h1>{{ __('Panel de Administración') }}</h1>
            <p>{{ __('Centro de control global de Pharenia. Gestión segmentada de usuarios y auditoría de cuentas.') }}</p>
        </header>

        <!-- Métricas Rápidas -->
        <div class="family-panel__grid pharenia-metrics">            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Total General') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $totalUsers }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Adultos TEA') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $adultTeaCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Tutores / Aliados') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $allyCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Jóvenes (Teens)') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $teenCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>{{ __('Menores') }}</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $minorCount }}</p>
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

        <!-- ================= 1. TABLA ADULTOS ================= -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">{{ __('1. Directorio de Adultos (TEA y Aliados)') }}</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createAdultModal')">{{ __('Crear Adulto +') }}</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Correo') }}</th>
                            <th>{{ __('Fecha Nac.') }}</th>
                            <th>{{ __('Rol') }}</th>
                            <th style="text-align: center;">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adults as $user)
                            <tr>
                                <td data-label="{{ __('ID') }}" style="font-weight: bold; color: #718096; text-align: center;">{{ $user->id }}</td>
                                <td data-label="{{ __('Nombre') }}" style="font-weight: 600;">{{ $user->name }}</td>
                                <td data-label="{{ __('Correo') }}" style="color: #4a5568;">{{ $user->email }}</td>
                                <td data-label="{{ __('Fecha Nac.') }}">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->toDateString() : 'N/D' }}</td>
                                <td data-label="{{ __('Rol') }}"><span class="pharenia-role-badge">{{ $user->role }}</span></td>
                                <td data-label="{{ __('Acciones') }}" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditAdultModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                                style="padding: 6px 12px; font-size: 12px;">{{ __('Editar') }}</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}', 'user')">{{ __('Eliminar') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">{{ __('No hay adultos registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 2. TABLA JÓVENES (Sin rol) ================= -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">{{ __('2. Directorio de Jóvenes (Teens 13-17)') }}</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createTeenModal')">{{ __('Crear Joven +') }}</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Correo') }}</th>
                            <th>{{ __('Fecha Nac.') }}</th>
                            <th>{{ __('Supervisor ID') }}</th>
                            <th style="text-align: center;">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teens as $user)
                            <tr>
                                <td data-label="{{ __('ID') }}" style="font-weight: bold; color: #718096; text-align: center;">{{ $user->id }}</td>
                                <td data-label="{{ __('Nombre') }}" style="font-weight: 600;">{{ $user->name }}</td>
                                <td data-label="{{ __('Correo') }}" style="color: #4a5568;">{{ $user->email }}</td>
                                <td data-label="{{ __('Fecha Nac.') }}">{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->toDateString() : 'N/D' }}</td>
                                <td data-label="{{ __('Supervisor ID') }}" style="font-family: monospace;">{{ $user->supervisor_id ?? '---' }}</td>
                                <td data-label="{{ __('Acciones') }}" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditTeenModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->supervisor_id }}')"
                                                style="padding: 6px 12px; font-size: 12px;">{{ __('Editar') }}</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}', 'user')">{{ __('Eliminar') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">{{ __('No hay jóvenes registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 3. TABLA MENORES ================= -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">{{ __('3. Directorio de Menores (<13 años)') }}</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createMinorModal')">{{ __('Crear Menor +') }}</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Nombre') }}</th>
                            <th>{{ __('Fecha Nac.') }}</th>
                            <th>{{ __('PIN Parental') }}</th>
                            <th>{{ __('Supervisor ID') }}</th>
                            <th style="text-align: center;">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($minors as $minor)
                            <tr>
                                <td data-label="{{ __('ID') }}" style="font-weight: bold; color: #718096; text-align: center;">{{ $minor->id }}</td>
                                <td data-label="{{ __('Nombre') }}" style="font-weight: 600;">{{ $minor->name }}</td>
                                <td data-label="{{ __('Fecha Nac.') }}">{{ $minor->birthdate ? \Carbon\Carbon::parse($minor->birthdate)->toDateString() : 'N/D' }}</td>
                                <td data-label="{{ __('PIN Parental') }}" style="font-family: monospace; font-weight: bold; color: #d69e2e;">{{ $minor->parent_pin }}</td>
                                <td data-label="{{ __('Supervisor ID') }}">{{ $minor->user_id }}</td>
                                <td data-label="{{ __('Acciones') }}" style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditMinorModal('{{ $minor->id }}', '{{ $minor->name }}', '{{ $minor->parent_pin }}', '{{ $minor->user_id }}')"
                                                style="padding: 6px 12px; font-size: 12px;">{{ __('Editar') }}</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $minor->id }}', '{{ $minor->name }}', 'minor')">{{ __('Eliminar') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">{{ __('No hay menores registrados.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL: CREAR ADULTO -->
    <div id="createAdultModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createAdultModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Registrar Adulto') }}</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" name="email" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group">
                    <label>{{ __('ROL ESPECÍFICO') }}</label>
                    <select name="role" required>
                        <option value="adult_tea">{{ __('Adulto Autogestor (TEA)') }}</option>
                        <option value="ally_no_tea">{{ __('Tutor / Aliado (No TEA)') }}</option>
                    </select>
                </div>
                <div class="pharenia-form-group"><label>{{ __('CONTRASEÑA TEMPORAL') }}</label><input type="password" name="password" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR ADULTO') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL: CREAR JOVEN -->
    <div id="createTeenModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createTeenModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Registrar Joven (Teen)') }}</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="teen">
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" name="email" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group"><label>{{ __('CONTRASEÑA TEMPORAL') }}</label><input type="password" name="password" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR JOVEN') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL: CREAR MENOR -->
    <div id="createMinorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createMinorModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Registrar Menor (<13 años)') }}</h3>
            <form action="{{ route('admin.minor.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>{{ __('NOMBRE DEL MENOR') }}</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('FECHA DE NACIMIENTO') }}</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group"><label>{{ __('PIN PARENTAL (4 dígitos)') }}</label><input type="text" name="parent_pin" maxlength="4" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO DEL SUPERVISOR') }}</label><input type="email" name="tutor_email" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR MENOR') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: ADULTO -->
    <div id="editAdultModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editAdultModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Editar Adulto') }}</h3>
            <form id="editAdultForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" id="editAdultName" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" id="editAdultEmail" name="email" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR CAMBIOS') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: JOVEN -->
    <div id="editTeenModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editTeenModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Editar Joven (Teen)') }}</h3>
            <form id="editTeenForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>{{ __('NOMBRE COMPLETO') }}</label><input type="text" id="editTeenName" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('CORREO ELECTRÓNICO') }}</label><input type="email" id="editTeenEmail" name="email" required></div>
                <div class="pharenia-form-group"><label>{{ __('SUPERVISOR ID') }}</label><input type="number" id="editTeenSupervisorId" name="supervisor_id" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR CAMBIOS') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: MENOR -->
    <div id="editMinorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editMinorModal')">&times;</button>
            <h3 class="pharenia-modal-title">{{ __('Editar Menor') }}</h3>
            <form id="editMinorForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>{{ __('NOMBRE DEL MENOR') }}</label><input type="text" id="editMinorName" name="name" required></div>
                <div class="pharenia-form-group"><label>{{ __('PIN PARENTAL (4 dígitos)') }}</label><input type="text" id="editMinorPin" name="parent_pin" maxlength="4" required></div>
                <div class="pharenia-form-group"><label>{{ __('SUPERVISOR ID') }}</label><input type="number" id="editMinorSupervisorId" name="supervisor_id" required></div>
                <button type="submit" class="pharenia-modal-submit">{{ __('GUARDAR CAMBIOS') }}</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div id="deleteModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content" style="max-width: 400px; text-align: center;">
            <h3 class="pharenia-modal-title" style="color: #e53e3e;">{{ __('Confirmar Eliminación') }}</h3>
            <p id="deleteMessage" style="color: #4a5568; margin-bottom: 25px; font-size: 14px;"></p>
            <form id="deleteForm" method="POST" style="display: flex; gap: 10px; justify-content: center;">
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

        function openEditAdultModal(id, name, email) {
            document.getElementById('editAdultName').value = name;
            document.getElementById('editAdultEmail').value = email;
            document.getElementById('editAdultForm').action = `/admin/users/${id}`;
            openModal('editAdultModal');
        }

        function openEditTeenModal(id, name, email, supervisorId) {
            document.getElementById('editTeenName').value = name;
            document.getElementById('editTeenEmail').value = email;
            document.getElementById('editTeenSupervisorId').value = supervisorId;
            document.getElementById('editTeenForm').action = `/admin/users/${id}`;
            openModal('editTeenModal');
        }

        function openEditMinorModal(id, name, pin, supervisorId) {
            document.getElementById('editMinorName').value = name;
            document.getElementById('editMinorPin').value = pin;
            document.getElementById('editMinorSupervisorId').value = supervisorId;
            document.getElementById('editMinorForm').action = `/admin/minor/${id}`;
            openModal('editMinorModal');
        }

        function openDeleteModal(id, name, type) {
            const message = `{{ __('¿Estás seguro de que deseas eliminar a') }} "${name}"{{ __('? Esta acción no se puede deshacer.') }}`;
            document.getElementById('deleteMessage').textContent = message;
            document.getElementById('deleteForm').action = (type === 'minor') ? `/admin/minor/${id}` : `/admin/users/${id}`;
            openModal('deleteModal');
        }

        window.onclick = function(event) {
            if (event.target.className === 'pharenia-modal-overlay') {
                document.querySelectorAll('.pharenia-modal-overlay').forEach(modal => modal.style.display = 'none');
            }
        }
    </script>

</body>
</html>