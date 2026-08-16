<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Pharenia</title>
    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
    <style>
        /* Corrección de capas para garantizar clics en botones */
        .pharenia-data-table { position: relative; z-index: 5; }
        .pharenia-data-table td, .pharenia-data-table th { position: relative; z-index: 6; }
        .pharenia-btn-primary, .pharenia-btn-danger, .pharenia-btn-secondary {
            position: relative;
            z-index: 10;
            cursor: pointer !important;
        }
    </style>
</head>
<body>
    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">Área Restringida</span>
            <h1>Panel de Administración</h1>
            <p>Centro de control global de Pharenia. Gestión segmentada de usuarios y auditoría de cuentas.</p>
        </header>

        <!-- Métricas Rápidas -->
        <div class="family-panel__grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; margin-bottom: 30px;">
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>Total General</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $totalUsers }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>Adultos TEA</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $adultTeaCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>Tutores / Aliados</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $allyCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>Jóvenes (Teens)</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2f4f4f; margin-top: 5px;">{{ $teenCount }}</p>
            </div>
            <div class="pharenia-card" style="text-align: center; padding: 15px;">
                <h3>Menores</h3>
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
                <h3 style="margin: 0; color: #2f4f4f;">1. Directorio de Adultos (TEA y Aliados)</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createAdultModal')">Crear Adulto +</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Fecha Nac.</th>
                            <th>Rol</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adults as $user)
                            <tr>
                                <td style="font-weight: bold; color: #718096; text-align: center;">{{ $user->id }}</td>
                                <td style="font-weight: 600;">{{ $user->name }}</td>
                                <td style="color: #4a5568;">{{ $user->email }}</td>
                                <td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->toDateString() : 'N/D' }}</td>
                                <td><span class="pharenia-role-badge">{{ $user->role }}</span></td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditAdultModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                                style="padding: 6px 12px; font-size: 12px;">Editar</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}', 'user')">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">No hay adultos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 2. TABLA JÓVENES (Sin rol) ================= -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">2. Directorio de Jóvenes (Teens 13-17)</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createTeenModal')">Crear Joven +</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Fecha Nac.</th>
                            <th>Supervisor ID</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teens as $user)
                            <tr>
                                <td style="font-weight: bold; color: #718096; text-align: center;">{{ $user->id }}</td>
                                <td style="font-weight: 600;">{{ $user->name }}</td>
                                <td style="color: #4a5568;">{{ $user->email }}</td>
                                <td>{{ $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->toDateString() : 'N/D' }}</td>
                                <td style="font-family: monospace;">{{ $user->supervisor_id ?? '---' }}</td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditTeenModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->supervisor_id }}')"
                                                style="padding: 6px 12px; font-size: 12px;">Editar</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $user->id }}', '{{ $user->name }}', 'user')">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">No hay jóvenes registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= 3. TABLA MENORES ================= -->
        <div class="pharenia-card" style="margin-bottom: 25px;">
            <div class="pharenia-table-action-header">
                <h3 style="margin: 0; color: #2f4f4f;">3. Directorio de Menores (&lt;13 años)</h3>
                <button type="button" class="pharenia-btn-primary" onclick="openModal('createMinorModal')">Crear Menor +</button>
            </div>

            <div style="overflow-x: auto;">
                <table class="pharenia-data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Nombre</th>
                            <th>Fecha Nac.</th>
                            <th>PIN Parental</th>
                            <th>Supervisor ID</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($minors as $minor)
                            <tr>
                                <td style="font-weight: bold; color: #718096; text-align: center;">{{ $minor->id }}</td>
                                <td style="font-weight: 600;">{{ $minor->name }}</td>
                                <td>{{ $minor->birthdate ? \Carbon\Carbon::parse($minor->birthdate)->toDateString() : 'N/D' }}</td>
                                <td style="font-family: monospace; font-weight: bold; color: #d69e2e;">{{ $minor->parent_pin }}</td>
                                <td>{{ $minor->user_id }}</td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="button" class="pharenia-btn-primary" 
                                                onclick="openEditMinorModal('{{ $minor->id }}', '{{ $minor->name }}', '{{ $minor->parent_pin }}', '{{ $minor->user_id }}')"
                                                style="padding: 6px 12px; font-size: 12px;">Editar</button>
                                        <button type="button" class="pharenia-btn-danger" onclick="openDeleteModal('{{ $minor->id }}', '{{ $minor->name }}', 'minor')">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="padding: 20px; text-align: center; color: #718096;">No hay menores registrados.</td></tr>
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
            <h3 class="pharenia-modal-title">Registrar Adulto</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>NOMBRE COMPLETO</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>CORREO ELECTRÓNICO</label><input type="email" name="email" required></div>
                <div class="pharenia-form-group"><label>FECHA DE NACIMIENTO</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group">
                    <label>ROL ESPECÍFICO</label>
                    <select name="role" required>
                        <option value="adult_tea">Adulto Autogestor (TEA)</option>
                        <option value="ally_no_tea">Tutor / Aliado (No TEA)</option>
                    </select>
                </div>
                <div class="pharenia-form-group"><label>CONTRASEÑA TEMPORAL</label><input type="password" name="password" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR ADULTO</button>
            </form>
        </div>
    </div>

    <!-- MODAL: CREAR JOVEN -->
    <div id="createTeenModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createTeenModal')">&times;</button>
            <h3 class="pharenia-modal-title">Registrar Joven (Teen)</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="teen">
                <div class="pharenia-form-group"><label>NOMBRE COMPLETO</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>CORREO ELECTRÓNICO</label><input type="email" name="email" required></div>
                <div class="pharenia-form-group"><label>FECHA DE NACIMIENTO</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group"><label>CONTRASEÑA TEMPORAL</label><input type="password" name="password" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR JOVEN</button>
            </form>
        </div>
    </div>

    <!-- MODAL: CREAR MENOR -->
    <div id="createMinorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('createMinorModal')">&times;</button>
            <h3 class="pharenia-modal-title">Registrar Menor (&lt;13 años)</h3>
            <form action="{{ route('admin.minor.store') }}" method="POST">
                @csrf
                <div class="pharenia-form-group"><label>NOMBRE DEL MENOR</label><input type="text" name="name" required></div>
                <div class="pharenia-form-group"><label>FECHA DE NACIMIENTO</label><input type="date" name="birthdate" required></div>
                <div class="pharenia-form-group"><label>PIN PARENTAL (4 dígitos)</label><input type="text" name="parent_pin" maxlength="4" required></div>
                <div class="pharenia-form-group"><label>CORREO DEL SUPERVISOR</label><input type="email" name="tutor_email" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR MENOR</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: ADULTO -->
    <div id="editAdultModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editAdultModal')">&times;</button>
            <h3 class="pharenia-modal-title">Editar Adulto</h3>
            <form id="editAdultForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>NOMBRE COMPLETO</label><input type="text" id="editAdultName" name="name" required></div>
                <div class="pharenia-form-group"><label>CORREO ELECTRÓNICO</label><input type="email" id="editAdultEmail" name="email" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR CAMBIOS</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: JOVEN -->
    <div id="editTeenModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editTeenModal')">&times;</button>
            <h3 class="pharenia-modal-title">Editar Joven (Teen)</h3>
            <form id="editTeenForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>NOMBRE COMPLETO</label><input type="text" id="editTeenName" name="name" required></div>
                <div class="pharenia-form-group"><label>CORREO ELECTRÓNICO</label><input type="email" id="editTeenEmail" name="email" required></div>
                <div class="pharenia-form-group"><label>SUPERVISOR ID</label><input type="number" id="editTeenSupervisorId" name="supervisor_id" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR CAMBIOS</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN: MENOR -->
    <div id="editMinorModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content">
            <button type="button" class="pharenia-modal-close" onclick="closeModal('editMinorModal')">&times;</button>
            <h3 class="pharenia-modal-title">Editar Menor</h3>
            <form id="editMinorForm" method="POST">
                @csrf @method('PUT')
                <div class="pharenia-form-group"><label>NOMBRE DEL MENOR</label><input type="text" id="editMinorName" name="name" required></div>
                <div class="pharenia-form-group"><label>PIN PARENTAL (4 dígitos)</label><input type="text" id="editMinorPin" name="parent_pin" maxlength="4" required></div>
                <div class="pharenia-form-group"><label>SUPERVISOR ID</label><input type="number" id="editMinorSupervisorId" name="supervisor_id" required></div>
                <button type="submit" class="pharenia-modal-submit">GUARDAR CAMBIOS</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE ELIMINACIÓN -->
    <div id="deleteModal" class="pharenia-modal-overlay">
        <div class="pharenia-modal-content" style="max-width: 400px; text-align: center;">
            <h3 class="pharenia-modal-title" style="color: #e53e3e;">Confirmar Eliminación</h3>
            <p id="deleteMessage" style="color: #4a5568; margin-bottom: 25px; font-size: 14px;"></p>
            <form id="deleteForm" method="POST" style="display: flex; gap: 10px; justify-content: center;">
                @csrf @method('DELETE')
                <button type="button" class="pharenia-btn-secondary" onclick="closeModal('deleteModal')">Cancelar</button>
                <button type="submit" class="pharenia-btn-danger" style="padding: 10px 20px;">Sí, Eliminar</button>
            </form>
        </div>
    </div>

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
            const message = `¿Estás seguro de que deseas eliminar a "${name}"? Esta acción no se puede deshacer.`;
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

    <x-footer/>
</body>
</html>