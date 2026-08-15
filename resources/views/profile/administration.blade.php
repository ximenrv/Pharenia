<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Pharenia</title>
    @vite(['resources/css/family-panel.css', 'resources/css/navbar.css', 'resources/css/footer.css'])
</head>
<body>
    <x-navbar/>

    <div class="family-panel-container">
        <header class="family-panel__header">
            <span class="family-panel__badge">Área Restringida</span>
            <h1>Panel de Administración</h1>
            <p>Bienvenido al centro de control global de Pharenia. Aquí podrás gestionar usuarios y visualizar métricas.</p>
            
        </header>

        <!-- Métricas Rápidas -->
        <div class="family-panel__grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
            
            <div class="pharenia-card" style="text-align: center; padding: 20px;">
                <h3>Total Usuarios</h3>
                <p style="font-size: 28px; font-weight: bold; color: #2f4f4f; margin-top: 10px;">{{ $totalUsers }}</p>
            </div>

            <div class="pharenia-card" style="text-align: center; padding: 20px;">
                <h3>Adultos TEA</h3>
                <p style="font-size: 28px; font-weight: bold; color: #2f4f4f; margin-top: 10px;">{{ $adultTeaCount }}</p>
            </div>

            <div class="pharenia-card" style="text-align: center; padding: 20px;">
                <h3>Tutores / Aliados</h3>
                <p style="font-size: 28px; font-weight: bold; color: #2f4f4f; margin-top: 10px;">{{ $allyCount }}</p>
            </div>

            <div class="pharenia-card" style="text-align: center; padding: 20px;">
                <h3>Jóvenes (Teens)</h3>
                <p style="font-size: 28px; font-weight: bold; color: #2f4f4f; margin-top: 10px;">{{ $teenCount }}</p>
            </div>

            <div class="pharenia-card" style="text-align: center; padding: 20px;">
                <h3>Menores</h3>
                <p style="font-size: 28px; font-weight: bold; color: #2f4f4f; margin-top: 10px;">{{ $minorCount }}</p>
            </div>

        </div>

        <!-- Próximos módulos (CRUD y Resultados) -->
        <div class="pharenia-card">
            <div class="pharenia-card__header">
                <h3>Próximas Secciones del Administrador</h3>
            </div>
            <p class="pharenia-card__desc">
                En este espacio construiremos muy pronto el <strong>CRUD completo de usuarios</strong> y la visualización de los <strong>resultados de los desafíos M-CHAT y del simulador</strong>.
            </p>
        </div>

    </div>

    <x-footer/>
</body>
</html>