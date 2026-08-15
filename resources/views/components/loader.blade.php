<style>
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: #061325; /* Azul marino muy oscuro */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .loader-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
    
    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 25px;
    }
    
    .loader-logo {
        width: 65px; 
        height: auto;
        animation: logoHeartbeat 0.5s ease-in-out infinite alternate;
    }
    
    @keyframes logoHeartbeat {
        0% {
            transform: scale(0.95);
        }
        100% {
            transform: scale(1.05);
        }
    }
    
    .progress-bar-container {
        width: 180px;
        height: 3px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background-color: #ffffff;
        animation: loadingProgress 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    @keyframes loadingProgress {
        0% { width: 0%; }
        100% { width: 100%; }
    }
</style>

<div id="loader" class="loader-wrapper">
    <div class="loader-content">
        <img src="{{ asset('img/logo.png') }}" alt="Logo Pharenia" class="loader-logo">
        <div class="progress-bar-container">
            <div class="progress-bar-fill"></div>
        </div>
    </div>
</div>

<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("loader");
        
        setTimeout(function() {
            loader.classList.add("loader-hidden");
        }, 680);
    });
</script>