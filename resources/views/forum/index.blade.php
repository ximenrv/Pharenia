<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lumenia - Pharenia</title>
    @vite(['resources/css/navbar.css', 'resources/css/settings-menu.css', 'resources/js/settings-menu.js', 'resources/css/footer.css', 'resources/css/dark-theme.css'])
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = savedTheme;
        })();
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Segoe UI", Tahoma, Verdana, sans-serif;
            color: #2c525a;
            min-height: 100vh;
            background: url('{{ asset("img/background-2.png") }}') center/cover no-repeat fixed;
            background-color: #dce8eb;
        }

        .forum-page {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            padding: 120px 2rem 4rem;
        }

        /* --- Header --- */
        .forum-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .forum-header__deco {
            position: absolute;
            opacity: 0.09;
            pointer-events: none;
        }

        .forum-header__deco--left {
            left: 0;
            top: -20px;
            width: 90px;
        }

        .forum-header__deco--right {
            right: 0;
            top: -10px;
            width: 70px;
            transform: scaleX(-1);
        }

        .forum-header__subtitle {
            display: inline-block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #7c4dff;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .forum-header__title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c525a;
            margin-bottom: 0.5rem;
        }

        .forum-header__intro {
            font-size: 1rem;
            color: #5a7a82;
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* --- Tabs --- */
        .forum-tabs {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2.5rem;
        }

        .forum-tab {
            padding: 0.7rem 2rem;
            border: 2px solid rgba(44, 82, 90, 0.15);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            color: #2c525a;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .forum-tab:hover {
            border-color: #7c4dff;
            color: #7c4dff;
            background: rgba(255, 255, 255, 0.85);
        }

        .forum-tab--active {
            background: #2c525a;
            color: #fff;
            border-color: #2c525a;
        }

        .forum-tab--active:hover {
            background: #1e3a40;
            color: #fff;
            border-color: #1e3a40;
        }

        .forum-panel { display: none; }
        .forum-panel--active { display: block; }

        /* ============================================
           LUMENIA HERO — Lumen grande + galería (modo claro por defecto)
           ============================================ */
        .lg-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 2rem;
        }

        .lg-scene {
            position: sticky;
            top: 90px;
            overflow: hidden;
            border-radius: 26px;
            min-height: 560px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.6rem;
            perspective: 1000px;
            background:
                radial-gradient(120% 80% at 50% 18%, rgba(124,77,255,.16), transparent 60%),
                linear-gradient(180deg, #ffffff, #e9f1f3);
            border: 1px solid rgba(255,255,255,.8);
            box-shadow: 0 20px 50px rgba(44,82,90,.14);
        }

        .lg-orbs { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
        .lg-orb {
            position: absolute; bottom: -20px; width: 10px; height: 10px; border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, rgba(255,255,255,.7), rgba(191,161,43,.35));
            opacity: .5; animation: lgRise 9s linear infinite; animation-delay: var(--d);
        }
        @keyframes lgRise {
            0% { transform: translateY(40px) scale(.8); opacity: 0; }
            20% { opacity: .6; }
            100% { transform: translateY(-460px) scale(1.1); opacity: 0; }
        }

        .lg-stage { position: relative; z-index: 2; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; }
        .lg-zone {
            position: relative; width: 100%; height: 400px;
            display: flex; align-items: flex-end; justify-content: center;
            transform-style: preserve-3d; transition: transform .2s ease-out;
        }
        .lg-glow {
            position: absolute; bottom: 44px; width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(191,161,43,.30), rgba(124,77,255,.16) 55%, transparent 72%);
            filter: blur(9px); animation: lgAura 2.8s ease-in-out infinite alternate;
        }
        @keyframes lgAura { 0% { transform: scale(.9); opacity: .75; } 100% { transform: scale(1.08); opacity: 1; } }

        .lg-lumen {
            position: relative; width: 360px; height: 400px;
            animation: lgFloat 3.2s ease-in-out infinite; transform-origin: center bottom;
        }
        .lg-lumen .lg-pose {
            position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
            width: 350px; height: auto;
            filter: drop-shadow(0 14px 22px rgba(44,82,90,.3)) drop-shadow(0 0 22px rgba(191,161,43,.35));
            transition: opacity .6s ease;
        }
        .lg-lumen .lg-pose--b { opacity: 0; }
        .lg-lumen.lg-gesture .lg-pose--a { opacity: 0; }
        .lg-lumen.lg-gesture .lg-pose--b { opacity: 1; }
        .lg-lumen.lg-blink { animation: lgFloat 3.2s ease-in-out infinite, lgBlink .16s ease; }
        @keyframes lgFloat { 0%,100% { transform: translateY(0) rotate(-1.4deg); } 50% { transform: translateY(-16px) rotate(1.4deg); } }
        @keyframes lgBlink { 0%,100% { transform: scaleY(1); } 50% { transform: scaleY(.9); } }

        .lg-bubble {
            position: absolute; top: 10px; left: 50%; margin-left: -105px; width: 210px; text-align: center;
            background: #fff; color: #2c525a; font-weight: 600; font-size: .95rem;
            padding: .6rem 1rem; border-radius: 18px; box-shadow: 0 8px 24px rgba(0,0,0,.18);
            transform: scale(0); transform-origin: bottom center;
            transition: transform .35s cubic-bezier(.2,1.3,.5,1); z-index: 6;
        }
        .lg-bubble.show { transform: scale(1); }
        .lg-bubble::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 9px solid transparent; border-top-color: #fff; }

        .lg-cta { margin-top: 1.3rem; z-index: 5; }
        .lg-cta button {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .95rem 2.3rem; border: none; border-radius: 50px;
            background: linear-gradient(135deg, #2c525a, #3c6f79); color: #fff;
            font-size: 1.05rem; font-weight: 700; cursor: pointer; font-family: inherit;
            box-shadow: 0 10px 30px rgba(124,77,255,.32);
            animation: lgCtaPulse 2.2s ease-in-out infinite; transition: transform .2s;
        }
        .lg-cta button:hover { transform: translateY(-3px) scale(1.03); }
        .lg-cta button svg { width: 20px; height: 20px; }
        @keyframes lgCtaPulse { 0%,100% { box-shadow: 0 10px 26px rgba(124,77,255,.28); } 50% { box-shadow: 0 12px 40px rgba(124,77,255,.45); } }

        /* Columna de galería */
        .lg-gallery { display: flex; flex-direction: column; }
        .lg-gallery__title { font-size: 1.2rem; margin-bottom: 1rem; color: #2c525a; }
        .lg-gallery .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); }

        /* Fotos estilo polaroid */
        .lg-gallery .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1.6rem 1.1rem; }
        .lg-gallery .photo-card {
            position: relative;
            background: linear-gradient(#ffffff, #fbfaf5);
            border-radius: 4px;
            overflow: visible;
            padding: .55rem .55rem .35rem;
            border: 1px solid rgba(44,82,90,.06);
            box-shadow: 0 12px 26px rgba(44,82,90,.18), 0 2px 5px rgba(44,82,90,.1);
            transition: transform .28s ease, box-shadow .28s ease;
        }
        .lg-gallery .photo-card:nth-child(odd) { transform: rotate(-1.8deg); }
        .lg-gallery .photo-card:nth-child(even) { transform: rotate(1.6deg); }
        .lg-gallery .photo-card:nth-child(3n) { transform: rotate(.7deg); }
        .lg-gallery .photo-card:hover { transform: rotate(0) translateY(-6px) scale(1.03); box-shadow: 0 22px 42px rgba(44,82,90,.26); z-index: 4; }
        /* cinta decorativa arriba */
        .lg-gallery .photo-card::before {
            content: ''; position: absolute; top: -8px; left: 50%;
            transform: translateX(-50%) rotate(-3deg);
            width: 56px; height: 18px;
            background: rgba(191,161,43,.32); border: 1px dashed rgba(191,161,43,.55);
            border-radius: 2px; z-index: 3;
        }
        .lg-gallery .photo-card__img-wrap { border-radius: 2px; box-shadow: inset 0 0 0 1px rgba(0,0,0,.06); }
        .lg-gallery .photo-card__img { border-radius: 2px; aspect-ratio: 1 / 1; }
        .lg-gallery .photo-card__body { padding: .6rem .35rem .2rem; }
        /* fila usuario: el nombre largo NO empuja el botón fuera ni rompe la polaroid */
        .lg-gallery .photo-card__user { flex-wrap: nowrap; }
        .lg-gallery .photo-card__name { flex: 1 1 auto; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lg-gallery .photo-card__delete { flex-shrink: 0; margin-left: .25rem; }
        /* caption estilo escrito a mano */
        .lg-gallery .photo-card__caption {
            font-family: "Segoe Script", "Bradley Hand", "Comic Sans MS", cursive;
            font-size: .98rem; color: #4a6b73; text-align: center;
            margin: .15rem 0 .35rem; overflow-wrap: anywhere;
        }
        .lg-gallery .photo-card__date { display: block; text-align: center; }

        /* Barra superior del panel de cámara: botón volver alineado con la cámara */
        .lg-cam-topbar { max-width: 750px; margin: 0 auto 1.2rem; display: flex; }
        .lg-back {
            display: inline-flex; align-items: center; gap: .4rem;
            background: rgba(255,255,255,.85); border: 1px solid rgba(44,82,90,.15);
            color: #2c525a; border-radius: 50px; padding: .55rem 1.2rem; font-weight: 600;
            font-family: inherit; cursor: pointer; box-shadow: 0 4px 14px rgba(44,82,90,.08);
            transition: all .2s ease;
        }
        .lg-back:hover { background: #fff; transform: translateX(-3px); box-shadow: 0 6px 18px rgba(44,82,90,.14); }

        @media (max-width: 900px) {
            .lg-hero { grid-template-columns: 1fr; }
            .lg-scene { position: relative; top: 0; }
        }

        /* ============================================
           GALERÍA
           ============================================ */
        .gallery-empty {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.6);
        }

        .gallery-empty__icon { margin-bottom: 1rem; }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .photo-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(44, 82, 90, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .photo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(44, 82, 90, 0.14);
        }

        .photo-card__img-wrap {
            cursor: pointer;
            overflow: hidden;
        }

        .photo-card__img {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .photo-card__img-wrap:hover .photo-card__img {
            transform: scale(1.04);
        }

        .photo-card__body {
            padding: 1rem 1.2rem;
        }

        .photo-card__user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .photo-card__avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e9d5ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: #7c4dff;
            flex-shrink: 0;
        }

        .photo-card__name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c525a;
        }

        .photo-card__caption {
            font-size: 0.85rem;
            color: #5a7a82;
            line-height: 1.4;
            margin-bottom: 0.4rem;
        }

        .photo-card__date {
            font-size: 0.75rem;
            color: #a0aec0;
        }

        .photo-card__delete {
            margin-left: auto;
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .photo-card__delete:hover {
            color: #e53e3e;
            background: rgba(229, 62, 62, 0.08);
        }

        /* ============================================
           LIGHTBOX
           ============================================ */
        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 500;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .lightbox--active { display: flex; }

        .lightbox__overlay {
            position: absolute;
            inset: 0;
            background: rgba(10, 20, 30, 0.82);
            backdrop-filter: blur(8px);
            cursor: pointer;
        }

        .lightbox__content {
            position: relative;
            z-index: 1;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .lightbox__img {
            max-width: 100%;
            max-height: 75vh;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            object-fit: contain;
        }

        .lightbox__info {
            text-align: center;
            color: #fff;
        }

        .lightbox__user {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .lightbox__caption {
            font-size: 0.9rem;
            opacity: 0.8;
            max-width: 500px;
        }

        .lightbox__close {
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .lightbox__close:hover { background: rgba(255,255,255,0.3); }

        /* ============================================
           CÁMARA
           ============================================ */
        .camera-section {
            max-width: 750px;
            margin: 0 auto;
        }

        .camera-login-msg {
            text-align: center;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.6);
        }

        .camera-login-msg p {
            color: #5a7a82;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .camera-login-msg a {
            display: inline-block;
            padding: 0.6rem 1.5rem;
            background: #2c525a;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .camera-login-msg a:hover { background: #bfa12b; }

        /* --- Botón encender / apagar --- */
        .cam-power-area {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .cam-power-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.8rem;
            border: 2px solid #2c525a;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            color: #2c525a;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cam-power-btn:hover {
            background: #2c525a;
            color: #fff;
        }

        .cam-power-btn--on {
            background: #2c525a;
            color: #fff;
        }

        .cam-power-btn--on:hover {
            background: #1e3a40;
        }

        .cam-power-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #a0aec0;
            transition: all 0.3s;
        }

        .cam-power-btn--on .cam-power-dot {
            background: #48bb78;
            box-shadow: 0 0 8px rgba(72, 187, 120, 0.6);
        }

        .cam-content { display: none; }
        .cam-content--active { display: block; }

        /* --- Barra de herramientas (Lumen + iconos stickers/filtros) --- */
        .cam-toolbar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.2rem;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.6);
            margin-bottom: 1rem;
            flex-wrap: nowrap;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(44, 82, 90, 0.06);
            overflow-x: auto;
        }

        .cam-toolbar__divider {
            width: 1px;
            height: 32px;
            background: rgba(44, 82, 90, 0.12);
            margin: 0 0.15rem;
            flex-shrink: 0;
        }

        /* Lumen thumbnails */
        .cam-lumen-opt {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            border: 2px solid rgba(44, 82, 90, 0.1);
            background: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            padding: 3px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cam-lumen-opt:hover { border-color: #7c4dff; transform: scale(1.1); }
        .cam-lumen-opt--active { border-color: #2c525a; box-shadow: 0 0 0 2px rgba(44, 82, 90, 0.12); background: #fff; }
        .cam-lumen-opt img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* Navegación de Lumen (6 visibles + flechas) */
        .cam-lumen-nav { display: flex; align-items: center; gap: 0.35rem; flex-shrink: 0; }
        .cam-lumen-viewport { overflow: hidden; }
        .cam-lumen-track { display: flex; gap: 0.4rem; }
        .cam-lumen-arrow {
            width: 30px;
            height: 44px;
            border-radius: 10px;
            flex-shrink: 0;
            border: 2px solid rgba(44, 82, 90, 0.12);
            background: rgba(255, 255, 255, 0.7);
            color: #2c525a;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .cam-lumen-arrow:hover:not(:disabled) { border-color: #7c4dff; color: #7c4dff; background: #fff; }
        .cam-lumen-arrow:disabled { opacity: 0.3; cursor: default; }

        /* Botones de herramienta (stickers / filtros) */
        .cam-tool-btn {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.8rem;
            border-radius: 10px;
            border: 2px solid rgba(44, 82, 90, 0.1);
            background: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-size: 1rem;
            font-family: inherit;
            color: #2c525a;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .cam-tool-btn:hover { border-color: #bfa12b; transform: scale(1.05); background: #fff; }
        .cam-tool-btn--active { border-color: #2c525a; background: #2c525a; color: #fff; }
        .cam-tool-btn--active:hover { border-color: #1e3a40; background: #1e3a40; }

        .cam-tool-btn__label {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: inherit;
            white-space: nowrap;
        }

        /* --- Dropdown expandible (stickers / filtros) --- */
        .cam-dropdown {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.35s ease, opacity 0.25s ease, margin 0.35s ease, padding 0.35s ease;
            margin-bottom: 0;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow: 0 2px 12px rgba(44, 82, 90, 0.06);
            padding: 0 0.8rem;
        }

        .cam-dropdown--open {
            max-height: 300px;
            opacity: 1;
            margin-bottom: 1rem;
            padding: 0.7rem 0.8rem;
        }

        .cam-dropdown__title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #7c4dff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .cam-dropdown__grid {
            display: flex;
            gap: 0.4rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Sticker items */
        .cam-sticker-opt {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            border: 2px solid rgba(44, 82, 90, 0.08);
            background: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            padding: 5px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cam-sticker-opt:hover {
            border-color: #bfa12b;
            transform: scale(1.1);
            background: #fff;
        }

        .cam-sticker-opt:active { transform: scale(0.95); }
        .cam-sticker-opt svg { width: 100%; height: 100%; }

        /* Filter items */
        .cam-filter-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            cursor: pointer;
            padding: 5px 8px;
            border-radius: 10px;
            border: 2px solid transparent;
            transition: all 0.25s ease;
        }

        .cam-filter-item:hover { border-color: rgba(44, 82, 90, 0.15); background: rgba(255,255,255,0.6); }
        .cam-filter-item--active { border-color: #2c525a; background: rgba(255,255,255,0.8); }

        .cam-filter-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(44, 82, 90, 0.12);
        }

        .cam-filter-circle__inner {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #7c4dff, #2c525a, #bfa12b);
        }

        .cam-filter-label {
            font-size: 0.6rem;
            color: #5a7a82;
            font-weight: 600;
        }

        .cam-filter-item--active .cam-filter-label { color: #2c525a; }

        /* --- Cámara --- */
        .cam-box-wrap {
            position: relative;
            margin-bottom: 0.8rem;
        }

        /* Decorativo: Lumen mini en la esquina del marco */
        .cam-box-wrap::after {
            content: '';
            position: absolute;
            bottom: -12px;
            right: -12px;
            width: 60px;
            height: 60px;
            background: url('{{ asset("img/lumen.png") }}') center/contain no-repeat;
            opacity: 0.15;
            pointer-events: none;
            z-index: 2;
        }

        .cam-box {
            position: relative;
            width: 100%;
            background: #1a1a2e;
            border-radius: 16px;
            overflow: hidden;
            border: 3px solid rgba(124, 77, 255, 0.3);
            box-shadow:
                0 8px 32px rgba(44, 82, 90, 0.18),
                0 0 0 1px rgba(44, 82, 90, 0.08),
                inset 0 0 60px rgba(0, 0, 0, 0.15);
            animation: camBorderGlow 3s ease-in-out infinite alternate;
        }

        @keyframes camBorderGlow {
            0%   { border-color: rgba(124, 77, 255, 0.3); box-shadow: 0 8px 32px rgba(44, 82, 90, 0.18), 0 0 15px rgba(124, 77, 255, 0.08); }
            50%  { border-color: rgba(191, 161, 43, 0.4); box-shadow: 0 8px 32px rgba(44, 82, 90, 0.18), 0 0 15px rgba(191, 161, 43, 0.1); }
            100% { border-color: rgba(44, 82, 90, 0.4); box-shadow: 0 8px 32px rgba(44, 82, 90, 0.18), 0 0 15px rgba(44, 82, 90, 0.1); }
        }

        /* Indicador de grabando */
        .cam-rec {
            position: absolute;
            top: 12px;
            left: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 20;
            pointer-events: none;
        }

        .cam-rec__dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #e53e3e;
            animation: recPulse 1.2s ease-in-out infinite;
        }

        @keyframes recPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .cam-rec__text {
            font-size: 0.65rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 1px 4px rgba(0,0,0,0.6);
            letter-spacing: 1px;
        }

        .cam-box video {
            width: 100%;
            display: block;
            transform: scaleX(-1);
        }

        .cam-box canvas { display: none; }

        /* Lumen arrastrable */
        .cam-lumen {
            position: absolute;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
            z-index: 10;
            /* Aura: brillo dorado/púrpura pulsante */
            filter:
                drop-shadow(0 2px 8px rgba(0,0,0,0.35))
                drop-shadow(0 0 12px rgba(191, 161, 43, 0.4))
                drop-shadow(0 0 25px rgba(124, 77, 255, 0.2));
            animation: lumenAura 2.5s ease-in-out infinite alternate, lumenFloat 3s ease-in-out infinite;
        }

        @keyframes lumenAura {
            0%   { filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)) drop-shadow(0 0 12px rgba(191,161,43,0.45)) drop-shadow(0 0 25px rgba(124,77,255,0.2)); }
            100% { filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)) drop-shadow(0 0 20px rgba(124,77,255,0.4)) drop-shadow(0 0 35px rgba(191,161,43,0.15)); }
        }

        .cam-lumen:active { cursor: grabbing; }
        .cam-lumen img {
            width: 100%;
            height: auto;
            pointer-events: none;
        }

        @keyframes lumenFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        /* Bounce cuando cambia de Lumen */
        .cam-lumen--bounce img {
            animation: lumenBounce 0.5s ease !important;
        }

        @keyframes lumenBounce {
            0%   { transform: scale(0.5) rotate(-10deg); opacity: 0.3; }
            50%  { transform: scale(1.15) rotate(3deg); }
            70%  { transform: scale(0.95) rotate(-1deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        /* Celebración al capturar — sacude todo el contenedor */
        .cam-lumen--celebrate {
            animation: lumenCelebrateWrap 0.8s ease !important;
        }

        .cam-lumen--celebrate img {
            animation: lumenCelebrateImg 0.8s ease !important;
        }

        @keyframes lumenCelebrateWrap {
            0%   { filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)) drop-shadow(0 0 15px rgba(191,161,43,0.5)); }
            50%  { filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)) drop-shadow(0 0 40px rgba(191,161,43,0.8)) drop-shadow(0 0 60px rgba(124,77,255,0.5)); }
            100% { filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)) drop-shadow(0 0 15px rgba(191,161,43,0.4)); }
        }

        @keyframes lumenCelebrateImg {
            0%   { transform: scale(1) rotate(0); }
            15%  { transform: scale(1.25) rotate(-10deg); }
            30%  { transform: scale(1.2) rotate(10deg); }
            45%  { transform: scale(1.15) rotate(-6deg); }
            60%  { transform: scale(1.1) rotate(6deg); }
            80%  { transform: scale(1.05) rotate(-2deg); }
            100% { transform: scale(1) rotate(0); }
        }

        /* Globo de diálogo de Lumen */
        .cam-lumen-bubble {
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) scale(0);
            background: #fff;
            color: #2c525a;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 0.7rem;
            border-radius: 12px;
            white-space: nowrap;
            box-shadow: 0 3px 15px rgba(0,0,0,0.25);
            pointer-events: none;
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
            z-index: 20;
            border: 2px solid rgba(191, 161, 43, 0.2);
        }

        .cam-lumen-bubble::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #fff;
        }

        .cam-lumen-bubble--visible {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }

        /* Partículas — van en el wrapper (fuera del overflow:hidden del cam-box) */
        .cam-particles {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 40;
        }

        .cam-particle {
            position: absolute;
            font-size: 1.4rem;
            animation: particleFly 1.2s ease-out forwards;
            opacity: 0;
        }

        @keyframes particleFly {
            0%   { opacity: 1; transform: translate(0, 0) scale(0.3); }
            40%  { opacity: 1; }
            100% { opacity: 0; transform: translate(var(--px), var(--py)) scale(1.3) rotate(200deg); }
        }

        /* Estela de Lumen al arrastrarlo */
        .lumen-trail {
            position: absolute;
            pointer-events: none;
            z-index: 9;
            animation: trailFade 0.8s ease-out forwards;
        }

        @keyframes trailFade {
            0%   { opacity: 1; transform: scale(1) rotate(0deg); }
            100% { opacity: 0; transform: scale(0) rotate(180deg) translateY(10px); }
        }

        .cam-lumen__controls {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .cam-lumen:hover .cam-lumen__controls { opacity: 1; }

        .cam-size-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: #2c525a;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cam-size-btn:hover { background: #bfa12b; }

        /* Stickers arrastrables */
        .cam-sticker {
            position: absolute;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
            z-index: 11;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.25));
        }

        .cam-sticker:active { cursor: grabbing; }
        .cam-sticker img, .cam-sticker svg { width: 100%; height: auto; pointer-events: none; }

        .cam-sticker__controls {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .cam-sticker:hover .cam-sticker__controls { opacity: 1; }

        .stk-btn {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #fff;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stk-btn--size { background: #2c525a; }
        .stk-btn--del { background: #c53030; }
        .stk-btn:hover { transform: scale(1.15); }

        /* --- Botones de acción --- */
        .cam-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            align-items: center;
            padding: 0.5rem 0;
        }

        .cam-btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 50px;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Botón capturar estilo cámara */
        .cam-btn--capture {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            padding: 0;
            background: transparent;
            border: 4px solid #2c525a;
            position: relative;
            box-shadow: 0 4px 20px rgba(44, 82, 90, 0.2);
            font-size: 0;
            color: transparent;
        }

        .cam-btn--capture::after {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 50%;
            background: #2c525a;
            transition: all 0.2s ease;
        }

        .cam-btn--capture:hover {
            border-color: #bfa12b;
            transform: scale(1.08);
        }

        .cam-btn--capture:hover::after {
            background: #bfa12b;
        }

        .cam-btn--capture:active {
            transform: scale(0.95);
        }

        .cam-btn--capture:active::after {
            inset: 6px;
        }

        .cam-btn--secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #2c525a;
            border: 1px solid rgba(44, 82, 90, 0.15);
            backdrop-filter: blur(4px);
            font-size: 0.82rem;
        }

        .cam-btn--secondary:hover { background: #fff; transform: scale(1.03); }

        /* Badge contador de stickers */
        .cam-sticker-badge {
            display: none;
            min-width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #bfa12b;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            line-height: 16px;
            text-align: center;
            margin-left: 2px;
        }

        .cam-sticker-badge--visible { display: inline-block; }

        /* Flash */
        .cam-flash { position: absolute; inset: 0; background: #fff; z-index: 50; opacity: 0; pointer-events: none; }
        .cam-flash--active { animation: camFlash 0.3s ease-out; }
        @keyframes camFlash { 0% { opacity: 0.9; } 100% { opacity: 0; } }

        /* Preview */
        .cam-preview { display: none; flex-direction: column; align-items: center; gap: 1rem; width: 100%; }
        .cam-preview--active { display: flex; }

        .cam-preview__img {
            width: 100%;
            border-radius: 16px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(44, 82, 90, 0.15);
        }

        .cam-preview__form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(44, 82, 90, 0.15);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            color: #2c525a;
            font-family: inherit;
            font-size: 0.95rem;
            resize: none;
            height: 70px;
            outline: none;
            transition: border-color 0.3s;
        }

        .cam-preview__form textarea:focus { border-color: #7c4dff; }

        .cam-preview__actions { display: flex; gap: 0.75rem; justify-content: center; margin-top: 0.5rem; }

        .cam-btn--save { background: #2c525a; color: #fff; box-shadow: 0 4px 15px rgba(44, 82, 90, 0.25); }
        .cam-btn--save:hover { background: #bfa12b; }

        .cam-btn--retake { background: rgba(255,255,255,0.85); color: #2c525a; border: 1px solid rgba(44, 82, 90, 0.15); }
        .cam-btn--retake:hover { background: #fff; }

        /* Toast */
        .cam-toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #2c525a;
            color: #fff;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            z-index: 600;
            transition: transform 0.4s ease;
            box-shadow: 0 5px 20px rgba(44, 82, 90, 0.3);
        }

        .cam-toast--active { transform: translateX(-50%) translateY(0); }

        /* ============================================
           PAGINACIÓN
           ============================================ */
        .gallery-pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .gallery-pagination nav { width: 100%; }

        .gallery-pagination .flex { display: none; }

        .gallery-pagination svg { width: 16px; height: 16px; }

        .gallery-pagination span[aria-current="page"] > span,
        .gallery-pagination a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.5rem;
            margin: 0 3px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .gallery-pagination a {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            color: #2c525a;
            border: 1px solid rgba(44, 82, 90, 0.12);
        }

        .gallery-pagination a:hover {
            background: #2c525a;
            color: #fff;
            border-color: #2c525a;
        }

        .gallery-pagination span[aria-current="page"] > span {
            background: #2c525a;
            color: #fff;
            border: 1px solid #2c525a;
        }

        .gallery-pagination p { font-size: 0.85rem; color: #5a7a82; text-align: center; }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .forum-page { padding: 100px 1rem 3rem; }
            .forum-header__title { font-size: 1.6rem; }
            .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
            .cam-lumen__controls, .cam-sticker__controls { opacity: 1; }
            .cam-lumen-opt { width: 38px; height: 38px; }
            .cam-tool-btn { width: 38px; height: 38px; font-size: 1.05rem; }
            .cam-sticker-opt { width: 44px; height: 44px; }
            .lightbox__content { max-width: 95vw; }
            .lightbox__close { top: 0.5rem; right: 0.5rem; }
            .forum-bg__lumen { width: 200px; }
        }

        @media (max-width: 480px) {
            .gallery-grid { grid-template-columns: 1fr; }
            .cam-toolbar { gap: 0.35rem; padding: 0.5rem; }
            .cam-lumen-opt { width: 34px; height: 34px; }
        }
    </style>
</head>
<body class="lumenia-body">

    @include('components.loader')
    @include('components.navbar')

    <div class="forum-page">
        <header class="forum-header">
            <img class="forum-header__deco forum-header__deco--left" src="{{ asset('img/coral.png') }}" alt="">
            <img class="forum-header__deco forum-header__deco--right" src="{{ asset('img/coral-2.png') }}" alt="">
            <span class="forum-header__subtitle">{{ __('Comunidad Pharenia') }}</span>
            <h1 class="forum-header__title">Lumenia</h1>
            <p class="forum-header__intro">{{ __('Comparte tus mejores momentos con Lumen. Toma una foto, agrega stickers y comparte con la comunidad.') }}</p>
        </header>

        {{-- ============ PANEL: GALERÍA (Hero Lumen + fotos) ============ --}}
        <div class="forum-panel forum-panel--active" id="panel-gallery">
          <div class="lg-hero">

            {{-- Lumen protagonista --}}
            <div class="lg-scene">
                <div class="lg-orbs" aria-hidden="true">
                    <span class="lg-orb" style="left:16%;--d:0s"></span>
                    <span class="lg-orb" style="left:30%;--d:2.5s"></span>
                    <span class="lg-orb" style="left:9%;--d:5s"></span>
                    <span class="lg-orb" style="left:42%;--d:7s"></span>
                </div>
                <div class="lg-stage">
                    <div class="lg-zone" id="lgZone">
                        <div class="lg-glow"></div>
                        <div class="lg-lumen" id="lgLumen">
                            <div class="lg-bubble" id="lgBubble">{{ __('Hola, soy Lumen') }}</div>
                            <img class="lg-pose lg-pose--a" src="{{ asset('img/lumen/lumen-01.png') }}" alt="Lumen saludando">
                            <img class="lg-pose lg-pose--b" src="{{ asset('img/lumen/lumen-11.png') }}" alt="Lumen">
                        </div>
                    </div>
                    <div class="lg-cta">
                        <button type="button" onclick="goToPanel('camera')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                            {{ __('Entrar a la cámara') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Galería de fotos --}}
            <div class="lg-gallery">
                <h3 class="lg-gallery__title">{{ __('Fotos de la comunidad') }}</h3>
                @if($photos->isEmpty())
                    <div class="gallery-empty">
                        <div class="gallery-empty__icon">
                            <img src="{{ asset('img/lumen.png') }}" alt="Lumen" style="width: 80px; opacity: 0.4;">
                        </div>
                        <p style="font-size: 1.1rem; color: #5a7a82;">{{ __('Aun no hay fotos en Lumenia.') }}</p>
                        <p style="font-size: 0.9rem; color: #a0aec0;">{{ __('Se el primero en compartir un momento en Lumenia.') }}</p>
                    </div>
                @else
                    <div class="gallery-grid">
                        @foreach($photos as $photo)
                        <div class="photo-card" id="photo-{{ $photo->id }}">
                            <div class="photo-card__img-wrap" data-lb-src="{{ asset('storage/' . $photo->image_path) }}" data-lb-user="{{ $photo->username }}" data-lb-caption="{{ $photo->caption ?? '' }}" onclick="openLightbox(this.dataset.lbSrc, this.dataset.lbUser, this.dataset.lbCaption)">
                                <img class="photo-card__img" src="{{ asset('storage/' . $photo->image_path) }}" alt="Foto de {{ $photo->username }}" loading="lazy">
                            </div>
                            <div class="photo-card__body">
                                <div class="photo-card__user">
                                    <div class="photo-card__avatar">{{ strtoupper(substr($photo->username, 0, 1)) }}</div>
                                    <span class="photo-card__name">{{ $photo->username }}</span>

                                    @auth
                                        @if(auth()->user()->id === $photo->user_id || auth()->user()->role === 'admin')
                                            <button class="photo-card__delete" onclick="deletePhoto({{ $photo->id }})" title="{{ __('Eliminar') }}">{{ __('Eliminar') }}</button>
                                        @endif
                                    @endauth
                                </div>
                                @if($photo->caption)
                                    <p class="photo-card__caption">{{ $photo->caption }}</p>
                                @endif
                                <span class="photo-card__date">{{ $photo->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($photos->hasPages())
                    <div class="gallery-pagination">
                        {{ $photos->links() }}
                    </div>
                @endif
            @endif
            </div>{{-- .lg-gallery --}}
          </div>{{-- .lg-hero --}}
        </div>

        {{-- ============ PANEL: CÁMARA ============ --}}
        <div class="forum-panel" id="panel-camera">
            <div class="lg-cam-topbar">
                <button type="button" class="lg-back" onclick="goToPanel('gallery')">‹ {{ __('Volver a la galería') }}</button>
            </div>
            @guest
                <div class="camera-section">
                    <div class="camera-login-msg">
                        <p>{{ __('Inicia sesion para tomar fotos con Lumen y compartirlas en Lumenia.') }}</p>
                        <a href="{{ route('login') }}">{{ __('Iniciar sesion') }}</a>
                    </div>
                </div>
            @else
                <div class="camera-section">

                    {{-- Botón encender / apagar --}}
                    <div class="cam-power-area">
                        <button class="cam-power-btn" id="camPowerBtn" onclick="toggleCamera()">
                            <span class="cam-power-dot"></span>
                            <span id="camPowerLabel">{{ __('Encender camara') }}</span>
                        </button>
                    </div>

                    {{-- Contenido (oculto hasta encender) --}}
                    <div class="cam-content" id="camContent">

                        {{-- Barra de herramientas: Lumens + iconos stickers/filtros --}}
                        <div class="cam-toolbar">
                            <button class="cam-tool-btn" id="btnFilters" onclick="toggleDropdown('filters')" title="{{ __('Filtros') }}">
                                📸
                                <span class="cam-tool-btn__label">{{ __('Filtros') }}</span>
                            </button>

                            <div class="cam-toolbar__divider"></div>

                            @if($lumenImages->isNotEmpty())
                                <div class="cam-lumen-nav">
                                    <button type="button" class="cam-lumen-arrow" id="lumenPrev" onclick="pageLumen(-1)" title="Anteriores" aria-label="Lumen anteriores">‹</button>
                                    <div class="cam-lumen-viewport">
                                        <div class="cam-lumen-track" id="lumenTrack">
                                            @foreach($lumenImages as $index => $img)
                                                <div class="cam-lumen-opt {{ $index === 0 ? 'cam-lumen-opt--active' : '' }}"
                                                     onclick="changeLumen('{{ $img }}', this)">
                                                    <img src="{{ asset('img/lumen/' . $img) }}" alt="Lumen {{ $index + 1 }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="button" class="cam-lumen-arrow" id="lumenNext" onclick="pageLumen(1)" title="Siguientes" aria-label="Lumen siguientes">›</button>
                                </div>
                            @endif

                            <div class="cam-toolbar__divider"></div>

                            <button class="cam-tool-btn" id="btnStickers" onclick="toggleDropdown('stickers')" title="{{ __('Stickers') }}">
                                ⭐
                                <span class="cam-tool-btn__label">{{ __('Stickers') }}</span>
                                <span class="cam-sticker-badge" id="stickerBadge">0</span>
                            </button>
                        </div>

                        {{-- Dropdown de filtros --}}
                        <div class="cam-dropdown" id="dropFilters">
                            <div class="cam-dropdown__title">{{ __('Filtros') }}</div>
                            <div class="cam-dropdown__grid">
                                <div class="cam-filter-item cam-filter-item--active" onclick="applyFilter('none', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner"></div></div>
                                    <span class="cam-filter-label">{{ __('Normal') }}</span>
                                </div>
                                <div class="cam-filter-item" onclick="applyFilter('warm', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner" style="filter: saturate(1.4) sepia(0.3) brightness(1.1);"></div></div>
                                    <span class="cam-filter-label">{{ __('Calido') }}</span>
                                </div>
                                <div class="cam-filter-item" onclick="applyFilter('cool', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner" style="filter: saturate(1.2) hue-rotate(20deg) brightness(1.05);"></div></div>
                                    <span class="cam-filter-label">{{ __('Frio') }}</span>
                                </div>
                                <div class="cam-filter-item" onclick="applyFilter('vintage', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner" style="filter: sepia(0.6) contrast(1.1) brightness(0.95);"></div></div>
                                    <span class="cam-filter-label">{{ __('Vintage') }}</span>
                                </div>
                                <div class="cam-filter-item" onclick="applyFilter('bw', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner" style="filter: grayscale(1) contrast(1.1);"></div></div>
                                    <span class="cam-filter-label">{{ __('B/N') }}</span>
                                </div>
                                <div class="cam-filter-item" onclick="applyFilter('dreamy', this)">
                                    <div class="cam-filter-circle"><div class="cam-filter-circle__inner" style="filter: brightness(1.15) contrast(0.9) saturate(1.3);"></div></div>
                                    <span class="cam-filter-label">{{ __('Suave') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Dropdown de stickers --}}
                        <div class="cam-dropdown" id="dropStickers">
                            <div class="cam-dropdown__title">{{ __('Stickers — toca para agregar') }}</div>
                            <div class="cam-dropdown__grid">
                                <div class="cam-sticker-opt" onclick="addSticker('crown')" title="{{ __('Corona') }}">
                                    <svg viewBox="0 0 120 80"><polygon points="10,70 20,25 35,50 60,10 85,50 100,25 110,70" fill="#bfa12b" stroke="#8a7a1d" stroke-width="2"/><rect x="10" y="65" width="100" height="12" rx="3" fill="#bfa12b" stroke="#8a7a1d" stroke-width="2"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('glasses')" title="{{ __('Lentes') }}">
                                    <svg viewBox="0 0 120 50"><rect x="5" y="10" width="45" height="30" rx="8" fill="#2c525a" stroke="#bfa12b" stroke-width="3"/><rect x="70" y="10" width="45" height="30" rx="8" fill="#2c525a" stroke="#bfa12b" stroke-width="3"/><path d="M50 25 L70 25" stroke="#bfa12b" stroke-width="3" fill="none"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('heart-glasses')" title="{{ __('Lentes corazon') }}">
                                    <svg viewBox="0 0 120 55"><path d="M5 22 C5 10,28 5,28 20 C28 5,50 10,50 22 C50 35,28 45,28 45 C28 45,5 35,5 22Z" fill="#7c4dff" opacity="0.9"/><path d="M70 22 C70 10,93 5,93 20 C93 5,115 10,115 22 C115 35,93 45,93 45 C93 45,70 35,70 22Z" fill="#7c4dff" opacity="0.9"/><path d="M50 25 L70 25" stroke="#7c4dff" stroke-width="3" fill="none"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('party-hat')" title="{{ __('Gorro') }}">
                                    <svg viewBox="0 0 100 110"><polygon points="50,5 15,95 85,95" fill="#7c4dff" stroke="#5b38c9" stroke-width="2"/><circle cx="50" cy="5" r="8" fill="#bfa12b"/><ellipse cx="50" cy="95" rx="40" ry="8" fill="#5b38c9"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('mustache')" title="{{ __('Bigote') }}">
                                    <svg viewBox="0 0 140 60"><path d="M70 45 C55 45,40 55,20 50 C5 46,0 30,15 25 C30 20,45 35,70 30 C95 35,110 20,125 25 C140 30,135 46,120 50 C100 55,85 45,70 45Z" fill="#2c525a"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('cowboy')" title="{{ __('Sombrero vaquero') }}">
                                    <svg viewBox="0 0 140 90"><ellipse cx="70" cy="75" rx="68" ry="12" fill="#8B4513" stroke="#654321" stroke-width="2"/><path d="M30,75 C30,30 50,15 70,15 C90,15 110,30 110,75" fill="#A0522D" stroke="#654321" stroke-width="2"/><rect x="30" y="65" width="80" height="8" rx="2" fill="#bfa12b"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('cat-ears')" title="{{ __('Orejas de gato') }}">
                                    <svg viewBox="0 0 140 80"><polygon points="15,75 5,10 50,55" fill="#e9d5ff" stroke="#7c4dff" stroke-width="2"/><polygon points="125,75 135,10 90,55" fill="#e9d5ff" stroke="#7c4dff" stroke-width="2"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('halo')" title="{{ __('Aureola') }}">
                                    <svg viewBox="0 0 120 50"><ellipse cx="60" cy="30" rx="50" ry="15" fill="none" stroke="#bfa12b" stroke-width="5" opacity="0.9"/></svg>
                                </div>
                                <div class="cam-sticker-opt" onclick="addSticker('stars')" title="{{ __('Estrellas') }}">
                                    <svg viewBox="0 0 100 100"><polygon points="50,5 61,35 95,35 68,55 78,90 50,70 22,90 32,55 5,35 39,35" fill="#bfa12b" opacity="0.9"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Cámara --}}
                        <div class="cam-box-wrap">
                            <div class="cam-box" id="camBox">
                                <div class="cam-rec">
                                    <div class="cam-rec__dot"></div>
                                    <span class="cam-rec__text">LIVE</span>
                                </div>
                                <video id="video" autoplay playsinline></video>
                                <canvas id="canvas"></canvas>
                                <div class="cam-lumen" id="camLumen" style="bottom: 20px; right: 20px; width: 150px;">
                                    <div class="cam-lumen-bubble" id="lumenBubble"></div>
                                    <div class="cam-lumen__controls">
                                        <button class="cam-size-btn" onclick="resizeLumen(-1)">−</button>
                                        <button class="cam-size-btn" onclick="resizeLumen(1)">+</button>
                                    </div>
                                    <img id="lumenImg" src="{{ $lumenImages->isNotEmpty() ? asset('img/lumen/' . $lumenImages[0]) : '' }}" alt="Lumen">
                                </div>
                                <div class="cam-flash" id="camFlash"></div>
                            </div>
                            <div class="cam-particles" id="camParticles"></div>
                        </div>

                        {{-- Botones --}}
                        <div class="cam-actions" id="camActions">
                            <button class="cam-btn cam-btn--secondary" onclick="flipLumen()">↔ {{ __('Voltear') }}</button>
                            <button class="cam-btn cam-btn--capture" onclick="capturePhoto()" title="{{ __('Capturar foto') }}"></button>
                            <button class="cam-btn cam-btn--secondary" onclick="switchCamera()">🔄 {{ __('Camara') }}</button>
                        </div>

                        {{-- Preview --}}
                        <div class="cam-preview" id="camPreview">
                            <img class="cam-preview__img" id="previewImg" src="" alt="Vista previa">
                            <div class="cam-preview__form">
                                <textarea id="captionInput" placeholder="{{ __('Agrega una descripcion... (opcional)') }}" maxlength="200"></textarea>
                            </div>
                            <div class="cam-preview__actions">
                                <button class="cam-btn cam-btn--retake" onclick="retakePhoto()">{{ __('Otra vez') }}</button>
                                <button class="cam-btn cam-btn--save" onclick="savePhoto()">{{ __('Publicar en Lumenia') }}</button>
                            </div>
                        </div>

                    </div>
                </div>
            @endguest
        </div>
    </div>

    {{-- ============ LIGHTBOX ============ --}}
    <div class="lightbox" id="lightbox">
        <div class="lightbox__overlay" onclick="closeLightbox()"></div>
        <div class="lightbox__content">
            <button class="lightbox__close" onclick="closeLightbox()">✕</button>
            <img class="lightbox__img" id="lightboxImg" src="" alt="Foto ampliada">
            <div class="lightbox__info">
                <div class="lightbox__user" id="lightboxUser"></div>
                <div class="lightbox__caption" id="lightboxCaption"></div>
            </div>
        </div>
    </div>

    <div class="cam-toast" id="camToast">{{ __('Foto publicada en Lumenia') }}</div>

    <script>
        // ==========================================
        // NAVEGACIÓN ENTRE PANELES (galería <-> cámara)
        // ==========================================
        function goToPanel(name) {
            // Seguridad: al salir de la cámara, apagarla automáticamente
            // aunque no se haya presionado "Apagar".
            if (name !== 'camera' && typeof cameraIsOn !== 'undefined' && cameraIsOn && typeof stopCamera === 'function') {
                stopCamera();
            }
            document.querySelectorAll('.forum-panel').forEach(function(p) { p.classList.remove('forum-panel--active'); });
            var panel = document.getElementById('panel-' + name);
            if (panel) panel.classList.add('forum-panel--active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ==========================================
        // VIDA DEL LUMEN DEL HERO (galería)
        // ==========================================
        (function() {
            var lumen = document.getElementById('lgLumen');
            var bubble = document.getElementById('lgBubble');
            if (!lumen) return;
            var frases = [@json(__('Hola, soy Lumen')), @json(__('¿Te tomas una foto conmigo?')), @json(__('Ven, entra a la cámara')), @json(__('Capturemos el momento juntos'))];
            var fi = 0;
            function cycle() {
                if (bubble) {
                    bubble.textContent = frases[fi];
                    bubble.classList.add('show');
                    setTimeout(function() { bubble.classList.remove('show'); }, 2900);
                }
                fi = (fi + 1) % frases.length;
            }
            setTimeout(cycle, 600);
            setInterval(cycle, 4200);
            // parpadeo (simulado) + gestos (alterna poses)
            setInterval(function() { lumen.classList.add('lg-blink'); setTimeout(function() { lumen.classList.remove('lg-blink'); }, 170); }, 3800);
            setInterval(function() { lumen.classList.toggle('lg-gesture'); }, 3400);
            // parallax pseudo-3D con el mouse
            var zone = document.getElementById('lgZone');
            var scene = lumen.closest('.lg-scene');
            if (scene && zone) {
                scene.addEventListener('mousemove', function(e) {
                    var r = scene.getBoundingClientRect();
                    var px = (e.clientX - r.left) / r.width - 0.5;
                    var py = (e.clientY - r.top) / r.height - 0.5;
                    zone.style.transform = 'rotateY(' + (px * 14) + 'deg) rotateX(' + (-py * 10) + 'deg)';
                });
                scene.addEventListener('mouseleave', function() { zone.style.transform = 'rotateY(0) rotateX(0)'; });
            }
        })();

        // ==========================================
        // LIGHTBOX
        // ==========================================
        function openLightbox(src, user, caption) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightboxUser').textContent = user;
            document.getElementById('lightboxCaption').textContent = caption || '';
            document.getElementById('lightbox').classList.add('lightbox--active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('lightbox--active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // ==========================================
        // ELIMINAR FOTO
        // ==========================================
        function deletePhoto(id) {
            if (!confirm(@json(__('¿Eliminar esta foto?')))) return;
            fetch('/lumenia/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success) {
                    var card = document.getElementById('photo-' + id);
                    if (card) card.remove();
                }
            }).catch(function() {
                alert(@json(__('Error al eliminar. Intenta de nuevo.')));
            });
        }

        // ==========================================
        // CÁMARA
        // ==========================================
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var ctx = canvas ? canvas.getContext('2d') : null;
        var camBox = document.getElementById('camBox');
        var camLumen = document.getElementById('camLumen');
        var lumenImg = document.getElementById('lumenImg');
        var currentStream = null;
        var cameraIsOn = false;
        var facingMode = 'user';
        var isFlipped = false;
        var capturedImageData = null;
        var currentFilter = 'none';
        var currentLumenWidth = 150;
        var activeStickers = [];
        var stickerIdCounter = 0;

        var STICKER_SVGS = {
            'glasses': '<svg viewBox="0 0 120 50" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="10" width="45" height="30" rx="8" fill="#2c525a" stroke="#bfa12b" stroke-width="3"/><rect x="70" y="10" width="45" height="30" rx="8" fill="#2c525a" stroke="#bfa12b" stroke-width="3"/><path d="M50 25 L70 25" stroke="#bfa12b" stroke-width="3" fill="none"/></svg>',
            'heart-glasses': '<svg viewBox="0 0 120 55" xmlns="http://www.w3.org/2000/svg"><path d="M5 22 C5 10,28 5,28 20 C28 5,50 10,50 22 C50 35,28 45,28 45 C28 45,5 35,5 22Z" fill="#7c4dff" opacity="0.9"/><path d="M70 22 C70 10,93 5,93 20 C93 5,115 10,115 22 C115 35,93 45,93 45 C93 45,70 35,70 22Z" fill="#7c4dff" opacity="0.9"/><path d="M50 25 L70 25" stroke="#7c4dff" stroke-width="3" fill="none"/></svg>',
            'crown': '<svg viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><polygon points="10,70 20,25 35,50 60,10 85,50 100,25 110,70" fill="#bfa12b" stroke="#8a7a1d" stroke-width="2"/><rect x="10" y="65" width="100" height="12" rx="3" fill="#bfa12b" stroke="#8a7a1d" stroke-width="2"/></svg>',
            'party-hat': '<svg viewBox="0 0 100 110" xmlns="http://www.w3.org/2000/svg"><polygon points="50,5 15,95 85,95" fill="#7c4dff" stroke="#5b38c9" stroke-width="2"/><circle cx="50" cy="5" r="8" fill="#bfa12b"/><ellipse cx="50" cy="95" rx="40" ry="8" fill="#5b38c9"/></svg>',
            'mustache': '<svg viewBox="0 0 140 60" xmlns="http://www.w3.org/2000/svg"><path d="M70 45 C55 45,40 55,20 50 C5 46,0 30,15 25 C30 20,45 35,70 30 C95 35,110 20,125 25 C140 30,135 46,120 50 C100 55,85 45,70 45Z" fill="#2c525a"/></svg>',
            'cowboy': '<svg viewBox="0 0 140 90" xmlns="http://www.w3.org/2000/svg"><ellipse cx="70" cy="75" rx="68" ry="12" fill="#8B4513" stroke="#654321" stroke-width="2"/><path d="M30,75 C30,30 50,15 70,15 C90,15 110,30 110,75" fill="#A0522D" stroke="#654321" stroke-width="2"/><rect x="30" y="65" width="80" height="8" rx="2" fill="#bfa12b"/></svg>',
            'cat-ears': '<svg viewBox="0 0 140 80" xmlns="http://www.w3.org/2000/svg"><polygon points="15,75 5,10 50,55" fill="#e9d5ff" stroke="#7c4dff" stroke-width="2"/><polygon points="125,75 135,10 90,55" fill="#e9d5ff" stroke="#7c4dff" stroke-width="2"/></svg>',
            'halo': '<svg viewBox="0 0 120 50" xmlns="http://www.w3.org/2000/svg"><ellipse cx="60" cy="30" rx="50" ry="15" fill="none" stroke="#bfa12b" stroke-width="5" opacity="0.9"/></svg>',
            'stars': '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><polygon points="50,5 61,35 95,35 68,55 78,90 50,70 22,90 32,55 5,35 39,35" fill="#bfa12b" opacity="0.9"/></svg>',
        };

        var STICKER_SIZES = {
            'glasses': 160, 'heart-glasses': 160, 'crown': 130, 'party-hat': 100,
            'mustache': 140, 'cowboy': 150, 'cat-ears': 140, 'halo': 140, 'stars': 90,
        };

        var FILTERS = {
            none: 'none',
            warm: 'saturate(1.4) sepia(0.3) brightness(1.1)',
            cool: 'saturate(1.2) hue-rotate(20deg) brightness(1.05)',
            vintage: 'sepia(0.6) contrast(1.1) brightness(0.95)',
            bw: 'grayscale(1) contrast(1.1)',
            dreamy: 'brightness(1.15) contrast(0.9) saturate(1.3)',
        };

        // ==========================================
        // ENCENDER / APAGAR
        // ==========================================
        function toggleCamera() {
            if (cameraIsOn) { stopCamera(); } else { startCamera(); }
        }

        function startCamera() {
            if (!video) return;
            var content = document.getElementById('camContent');
            var btn = document.getElementById('camPowerBtn');
            var label = document.getElementById('camPowerLabel');

            // Resetear estado de preview por si quedó abierto
            document.getElementById('camPreview').classList.remove('cam-preview--active');
            document.getElementById('camActions').style.display = 'flex';
            document.querySelector('.cam-box-wrap').style.display = '';
            document.querySelector('.cam-toolbar').style.display = '';
            document.querySelectorAll('.cam-dropdown').forEach(function(d) { d.style.display = ''; });
            capturedImageData = null;
            var captionEl = document.getElementById('captionInput');
            if (captionEl) captionEl.value = '';

            if (currentStream) currentStream.getTracks().forEach(function(t) { t.stop(); });
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
            }).then(function(stream) {
                currentStream = stream;
                video.srcObject = stream;
                video.style.display = 'block';
                content.classList.add('cam-content--active');
                btn.classList.add('cam-power-btn--on');
                label.textContent = @json(__('Apagar camara'));
                cameraIsOn = true;
                startIdlePhrases();
                // Ajustar tamaño de Lumen ahora que la cámara es visible
                video.addEventListener('loadedmetadata', function() { setLumenSize(); }, { once: true });
                setTimeout(setLumenSize, 500); // respaldo
            }).catch(function() {
                camBox.innerHTML = '<div class="cam-no-camera" style="padding:3rem;text-align:center;color:#5a7a82;aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;"><p>No se pudo acceder a la camara.</p><p style="font-size:0.85rem;">Asegurate de dar permiso al navegador.</p></div>';
                content.classList.add('cam-content--active');
                btn.classList.add('cam-power-btn--on');
                label.textContent = @json(__('Apagar camara'));
                cameraIsOn = true;
            });
        }

        function stopCamera() {
            if (currentStream) {
                currentStream.getTracks().forEach(function(t) { t.stop(); });
                currentStream = null;
            }
            if (video) { video.srcObject = null; }

            var content = document.getElementById('camContent');
            var btn = document.getElementById('camPowerBtn');
            var label = document.getElementById('camPowerLabel');

            content.classList.remove('cam-content--active');
            btn.classList.remove('cam-power-btn--on');
            label.textContent = @json(__('Encender camara'));
            cameraIsOn = false;
            stopIdlePhrases();

            // Cerrar dropdowns
            document.querySelectorAll('.cam-dropdown').forEach(function(d) { d.classList.remove('cam-dropdown--open'); });
            document.querySelectorAll('.cam-tool-btn').forEach(function(b) { b.classList.remove('cam-tool-btn--active'); });

            // Limpiar stickers
            activeStickers.forEach(function(s) { if (s.element && s.element.parentNode) s.element.remove(); });
            activeStickers = [];
        }

        function switchCamera() {
            facingMode = facingMode === 'user' ? 'environment' : 'user';
            if (video) video.style.transform = facingMode === 'user' ? 'scaleX(-1)' : 'scaleX(1)';
            if (cameraIsOn) {
                if (currentStream) currentStream.getTracks().forEach(function(t) { t.stop(); });
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: facingMode, width: { ideal: 1280 }, height: { ideal: 720 } }
                }).then(function(stream) {
                    currentStream = stream;
                    video.srcObject = stream;
                }).catch(function() {});
            }
        }

        // ==========================================
        // DROPDOWNS (stickers / filtros)
        // ==========================================
        function toggleDropdown(type) {
            var dropId = type === 'stickers' ? 'dropStickers' : 'dropFilters';
            var btnId = type === 'stickers' ? 'btnStickers' : 'btnFilters';
            var otherDrop = type === 'stickers' ? 'dropFilters' : 'dropStickers';
            var otherBtn = type === 'stickers' ? 'btnFilters' : 'btnStickers';

            var drop = document.getElementById(dropId);
            var btn = document.getElementById(btnId);
            var isOpen = drop.classList.contains('cam-dropdown--open');

            // Cerrar el otro
            document.getElementById(otherDrop).classList.remove('cam-dropdown--open');
            document.getElementById(otherBtn).classList.remove('cam-tool-btn--active');

            if (isOpen) {
                drop.classList.remove('cam-dropdown--open');
                btn.classList.remove('cam-tool-btn--active');
            } else {
                drop.classList.add('cam-dropdown--open');
                btn.classList.add('cam-tool-btn--active');
            }
        }

        // ==========================================
        // FILTROS
        // ==========================================
        function applyFilter(name, el) {
            if (!video) return;
            currentFilter = name;
            video.style.filter = FILTERS[name] === 'none' ? '' : FILTERS[name];
            document.querySelectorAll('.cam-filter-item').forEach(function(o) { o.classList.remove('cam-filter-item--active'); });
            el.classList.add('cam-filter-item--active');
            if (name !== 'none') lumenSay('filter');
        }

        // ==========================================
        // LUMEN
        // ==========================================
        var isDragging = false;
        var dragOffX, dragOffY;

        if (camLumen) {
            camLumen.addEventListener('mousedown', startDrag);
            camLumen.addEventListener('touchstart', startDrag, { passive: false });
        }

        function startDrag(e) {
            if (e.target.classList.contains('cam-size-btn')) return;
            e.preventDefault();
            isDragging = true;
            var rect = camLumen.getBoundingClientRect();
            var cx = e.touches ? e.touches[0].clientX : e.clientX;
            var cy = e.touches ? e.touches[0].clientY : e.clientY;
            dragOffX = cx - rect.left;
            dragOffY = cy - rect.top;
        }

        document.addEventListener('mousemove', onDrag);
        document.addEventListener('touchmove', onDrag, { passive: false });

        var trailEmojis = ['✨', '⭐', '🌟', '💫', '·'];
        var trailCounter = 0;

        function onDrag(e) {
            if (!isDragging || !camBox) return;
            e.preventDefault();
            var cr = camBox.getBoundingClientRect();
            var cx = e.touches ? e.touches[0].clientX : e.clientX;
            var cy = e.touches ? e.touches[0].clientY : e.clientY;
            var nl = Math.max(0, Math.min(cx - cr.left - dragOffX, cr.width - camLumen.offsetWidth));
            var nt = Math.max(0, Math.min(cy - cr.top - dragOffY, cr.height - (camLumen.offsetHeight || lumenImg.offsetHeight)));
            camLumen.style.left = nl + 'px';
            camLumen.style.top = nt + 'px';
            camLumen.style.right = 'auto';
            camLumen.style.bottom = 'auto';

            // Estela de estrellas cada 3 movimientos
            trailCounter++;
            if (trailCounter % 3 === 0) {
                var t = document.createElement('div');
                t.className = 'lumen-trail';
                t.textContent = trailEmojis[Math.floor(Math.random() * trailEmojis.length)];
                var lumenW = camLumen.offsetWidth || 100;
                var lumenH = camLumen.offsetHeight || 100;
                t.style.left = (nl + lumenW / 2 - 6 + (Math.random() - 0.5) * 20) + 'px';
                t.style.top = (nt + lumenH / 2 - 6 + (Math.random() - 0.5) * 20) + 'px';
                t.style.fontSize = (0.6 + Math.random() * 0.7) + 'rem';
                camBox.appendChild(t);
                setTimeout(function() { if (t.parentNode) t.remove(); }, 800);
            }
        }

        document.addEventListener('mouseup', function() { isDragging = false; });
        document.addEventListener('touchend', function() { isDragging = false; });

        function resizeLumen(dir) {
            if (!camBox) return;
            currentLumenWidth += dir * 25;
            var maxW = camBox.getBoundingClientRect().width * 0.8;
            currentLumenWidth = Math.max(50, Math.min(currentLumenWidth, maxW));
            camLumen.style.width = currentLumenWidth + 'px';
        }

        if (camLumen) {
            camLumen.addEventListener('wheel', function(e) { e.preventDefault(); resizeLumen(e.deltaY < 0 ? 1 : -1); }, { passive: false });
        }

        function flipLumen() {
            if (!lumenImg) return;
            isFlipped = !isFlipped;
            lumenImg.style.transform = isFlipped ? 'scaleX(-1)' : 'scaleX(1)';
        }

        function changeLumen(filename, el) {
            if (!lumenImg) return;
            lumenImg.src = '{{ asset("img/lumen") }}/' + filename;
            document.querySelectorAll('.cam-lumen-opt').forEach(function(o) { o.classList.remove('cam-lumen-opt--active'); });
            el.classList.add('cam-lumen-opt--active');

            // Bounce al cambiar
            if (camLumen) {
                camLumen.classList.remove('cam-lumen--bounce');
                void camLumen.offsetWidth;
                camLumen.classList.add('cam-lumen--bounce');
                setTimeout(function() { camLumen.classList.remove('cam-lumen--bounce'); }, 600);
            }
            lumenSay('changeLumen');
            // Reajustar tamaño al cargar la nueva imagen
            lumenImg.addEventListener('load', function() { setLumenSize(); }, { once: true });
        }

        // ==========================================
        // PAGINADO DE LUMEN — 6 visibles con flechas
        // ==========================================
        var LUMEN_PER_PAGE = 6;
        var lumenPage = 0;
        function renderLumenPage() {
            var track = document.getElementById('lumenTrack');
            if (!track) return;
            var opts = track.querySelectorAll('.cam-lumen-opt');
            var pages = Math.max(1, Math.ceil(opts.length / LUMEN_PER_PAGE));
            if (lumenPage > pages - 1) lumenPage = pages - 1;
            if (lumenPage < 0) lumenPage = 0;
            opts.forEach(function(o, i) {
                o.style.display = (Math.floor(i / LUMEN_PER_PAGE) === lumenPage) ? '' : 'none';
            });
            var prev = document.getElementById('lumenPrev');
            var next = document.getElementById('lumenNext');
            var multi = opts.length > LUMEN_PER_PAGE;
            if (prev) { prev.style.display = multi ? '' : 'none'; prev.disabled = (lumenPage === 0); }
            if (next) { next.style.display = multi ? '' : 'none'; next.disabled = (lumenPage >= pages - 1); }
        }
        function pageLumen(dir) {
            lumenPage += dir;
            renderLumenPage();
        }
        renderLumenPage();

        // ==========================================
        // LUMEN HABLA — Globos de diálogo
        // ==========================================
        var LUMEN_PHRASES = {
            greeting: [
                @json(__('¡Hola! 📸')),
                @json(__('¡Sonrie!')),
                @json(__('¿Listos?')),
                @json(__('¡Vamos!')),
                @json(__('¡Que bien te ves!'))
            ],
            changeLumen: [
                @json(__('¡Nuevo look!')),
                @json(__('¡Me gusta este!')),
                @json(__('¡Mira, soy yo!')),
                @json(__('¡Genial!')),
                @json(__('¡Cambio de estilo!'))
            ],
            capture: [
                @json(__('¡Perfecta! ⭐')),
                @json(__('¡Quedo genial!')),
                @json(__('¡Hermosa foto!')),
                @json(__('¡Increible!')),
                @json(__('¡Me encanta!'))
            ],
            sticker: [
                @json(__('¡Que bonito!')),
                @json(__('¡Mas stickers!')),
                @json(__('¡Me queda bien!')),
                @json(__('¡Eso, eso!'))
            ],
            filter: [
                @json(__('¡Ese filtro queda bien!')),
                @json(__('¡Wow, que cambio!')),
                @json(__('¡Me gusta como se ve!')),
                @json(__('¡Queda diferente!')),
                @json(__('¡Buena eleccion!'))
            ],
            idle: [
                @json(__('¡Toma una foto!')),
                @json(__('Arrastra los stickers')),
                @json(__('¡Prueba un filtro!')),
                @json(__('¿Y si cambias de Lumen?')),
                @json(__('¡Comparte con todos!')),
                @json(__('¡Mueve los stickers!')),
                @json(__('¡Hazme mas grande! +'))
            ]
        };

        var bubbleTimeout = null;
        var idleInterval = null;

        function lumenSay(type) {
            var bubble = document.getElementById('lumenBubble');
            if (!bubble) return;
            var phrases = LUMEN_PHRASES[type] || LUMEN_PHRASES.idle;
            var phrase = phrases[Math.floor(Math.random() * phrases.length)];
            bubble.textContent = phrase;
            bubble.classList.add('cam-lumen-bubble--visible');
            clearTimeout(bubbleTimeout);
            bubbleTimeout = setTimeout(function() {
                bubble.classList.remove('cam-lumen-bubble--visible');
            }, 2200);
        }

        function startIdlePhrases() {
            clearInterval(idleInterval);
            // Primera frase después de 2s
            setTimeout(function() { lumenSay('greeting'); }, 2000);
            // Frase idle cada 12-18s
            idleInterval = setInterval(function() {
                if (!cameraIsOn) return;
                lumenSay('idle');
            }, 14000);
        }

        function stopIdlePhrases() {
            clearInterval(idleInterval);
        }

        // Partículas al capturar
        function spawnParticles() {
            var container = document.getElementById('camParticles');
            if (!container) return;
            var emojis = ['⭐', '✨', '🌟', '💫', '🎉'];
            for (var i = 0; i < 8; i++) {
                var p = document.createElement('div');
                p.className = 'cam-particle';
                p.textContent = emojis[Math.floor(Math.random() * emojis.length)];
                var angle = (Math.random() * 360) * (Math.PI / 180);
                var dist = 60 + Math.random() * 120;
                p.style.setProperty('--px', Math.cos(angle) * dist + 'px');
                p.style.setProperty('--py', Math.sin(angle) * dist + 'px');
                p.style.left = '50%';
                p.style.top = '50%';
                p.style.animationDelay = (Math.random() * 0.2) + 's';
                container.appendChild(p);
            }
            setTimeout(function() { container.innerHTML = ''; }, 1200);
        }

        // ==========================================
        // STICKERS
        // ==========================================
        var draggingSticker = null;
        var stkOffX, stkOffY;

        function addSticker(type) {
            if (!camBox) return;
            var id = 'stk_' + (stickerIdCounter++);
            var cr = camBox.getBoundingClientRect();
            var dw = STICKER_SIZES[type] || 120;
            var rx = Math.random() * (cr.width - dw);
            var ry = Math.random() * (cr.height * 0.5);
            var el = document.createElement('div');
            el.className = 'cam-sticker';
            el.id = id;
            el.dataset.type = type;
            el.dataset.width = dw;
            el.style.width = dw + 'px';
            el.style.left = rx + 'px';
            el.style.top = ry + 'px';
            el.innerHTML = '<div class="cam-sticker__controls"><button class="stk-btn stk-btn--size" onclick="resizeStk(\'' + id + '\',-1)">−</button><button class="stk-btn stk-btn--size" onclick="resizeStk(\'' + id + '\',1)">+</button><button class="stk-btn stk-btn--del" onclick="removeStk(\'' + id + '\')">✕</button></div>' + STICKER_SVGS[type];
            el.addEventListener('mousedown', function(e) { startStkDrag(e, id); });
            el.addEventListener('touchstart', function(e) { startStkDrag(e, id); }, { passive: false });
            el.addEventListener('wheel', function(e) { e.preventDefault(); resizeStk(id, e.deltaY < 0 ? 1 : -1); }, { passive: false });
            var flash = document.getElementById('camFlash');
            camBox.insertBefore(el, flash);
            activeStickers.push({ id: id, type: type, element: el });
            updateStickerBadge();
            lumenSay('sticker');
        }

        function removeStk(id) {
            var el = document.getElementById(id);
            if (el) el.remove();
            activeStickers = activeStickers.filter(function(s) { return s.id !== id; });
            updateStickerBadge();
        }

        function updateStickerBadge() {
            var badge = document.getElementById('stickerBadge');
            if (!badge) return;
            var count = activeStickers.length;
            badge.textContent = count;
            if (count > 0) { badge.classList.add('cam-sticker-badge--visible'); }
            else { badge.classList.remove('cam-sticker-badge--visible'); }
        }

        function resizeStk(id, dir) {
            var el = document.getElementById(id);
            if (!el) return;
            var w = parseFloat(el.dataset.width) || 120;
            w += dir * 20;
            w = Math.max(30, Math.min(w, camBox.getBoundingClientRect().width * 0.8));
            el.dataset.width = w;
            el.style.width = w + 'px';
        }

        function startStkDrag(e, id) {
            if (e.target.classList.contains('stk-btn')) return;
            e.preventDefault();
            draggingSticker = document.getElementById(id);
            var r = draggingSticker.getBoundingClientRect();
            stkOffX = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
            stkOffY = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
        }

        document.addEventListener('mousemove', function(e) {
            if (!draggingSticker || !camBox) return;
            e.preventDefault();
            var cr = camBox.getBoundingClientRect();
            var cx = e.clientX;
            var cy = e.clientY;
            var nl = Math.max(0, Math.min(cx - cr.left - stkOffX, cr.width - draggingSticker.offsetWidth));
            var nt = Math.max(0, Math.min(cy - cr.top - stkOffY, cr.height - draggingSticker.offsetHeight));
            draggingSticker.style.left = nl + 'px';
            draggingSticker.style.top = nt + 'px';
        });
        document.addEventListener('touchmove', function(e) {
            if (!draggingSticker || !camBox) return;
            e.preventDefault();
            var cr = camBox.getBoundingClientRect();
            var cx = e.touches[0].clientX;
            var cy = e.touches[0].clientY;
            var nl = Math.max(0, Math.min(cx - cr.left - stkOffX, cr.width - draggingSticker.offsetWidth));
            var nt = Math.max(0, Math.min(cy - cr.top - stkOffY, cr.height - draggingSticker.offsetHeight));
            draggingSticker.style.left = nl + 'px';
            draggingSticker.style.top = nt + 'px';
        }, { passive: false });
        document.addEventListener('mouseup', function() { draggingSticker = null; });
        document.addEventListener('touchend', function() { draggingSticker = null; });

        // ==========================================
        // CAPTURAR
        // ==========================================
        function capturePhoto() {
            if (!video || !canvas || !ctx || !camBox || !cameraIsOn) return;
            var cr = camBox.getBoundingClientRect();
            var vw = video.videoWidth;
            var vh = video.videoHeight;
            canvas.width = vw;
            canvas.height = vh;
            var sx = vw / cr.width;
            var sy = vh / cr.height;
            ctx.filter = (FILTERS[currentFilter] && FILTERS[currentFilter] !== 'none') ? FILTERS[currentFilter] : 'none';
            if (facingMode === 'user') { ctx.save(); ctx.scale(-1, 1); ctx.drawImage(video, -vw, 0, vw, vh); ctx.restore(); }
            else { ctx.drawImage(video, 0, 0, vw, vh); }
            ctx.filter = 'none';

            var flash = document.getElementById('camFlash');
            flash.classList.add('cam-flash--active');
            setTimeout(function() { flash.classList.remove('cam-flash--active'); }, 300);

            // Lumen celebra
            lumenSay('capture');
            spawnParticles();
            if (camLumen) {
                camLumen.classList.add('cam-lumen--celebrate');
                setTimeout(function() { camLumen.classList.remove('cam-lumen--celebrate'); }, 900);
            }

            var lr = camLumen.getBoundingClientRect();
            var lx = (lr.left - cr.left) * sx;
            var ly = (lr.top - cr.top) * sy;
            var lw = lr.width * sx;
            var lh = lr.height * sy;

            var stkDraw = activeStickers.map(function(s) {
                var r = s.element.getBoundingClientRect();
                return { svg: STICKER_SVGS[s.type], x: (r.left - cr.left) * sx, y: (r.top - cr.top) * sy, w: r.width * sx, h: r.height * sy };
            });

            var lImg = new Image();
            lImg.crossOrigin = 'anonymous';
            lImg.onload = function() {
                ctx.save();
                if (isFlipped) { ctx.translate(lx + lw, ly); ctx.scale(-1, 1); ctx.drawImage(lImg, 0, 0, lw, lh); }
                else { ctx.drawImage(lImg, lx, ly, lw, lh); }
                ctx.restore();
                drawStks(stkDraw, 0);
            };
            lImg.src = lumenImg.src;
        }

        function drawStks(stks, i) {
            if (i >= stks.length) {
                capturedImageData = canvas.toDataURL('image/jpeg', 0.7);
                // Esperar a que se vea la celebración antes de mostrar preview
                setTimeout(showPreview, 1200);
                return;
            }
            var s = stks[i];
            var blob = new Blob([s.svg], { type: 'image/svg+xml;charset=utf-8' });
            var url = URL.createObjectURL(blob);
            var img = new Image();
            img.onload = function() { ctx.drawImage(img, s.x, s.y, s.w, s.h); URL.revokeObjectURL(url); drawStks(stks, i + 1); };
            img.onerror = function() { URL.revokeObjectURL(url); drawStks(stks, i + 1); };
            img.src = url;
        }

        function showPreview() {
            document.getElementById('previewImg').src = capturedImageData;
            document.getElementById('camPreview').classList.add('cam-preview--active');
            document.getElementById('camActions').style.display = 'none';
            document.querySelector('.cam-box-wrap').style.display = 'none';
            document.querySelector('.cam-toolbar').style.display = 'none';
            document.querySelectorAll('.cam-dropdown').forEach(function(d) { d.style.display = 'none'; });
        }

        function retakePhoto() {
            document.getElementById('camPreview').classList.remove('cam-preview--active');
            document.getElementById('camActions').style.display = 'flex';
            document.querySelector('.cam-box-wrap').style.display = 'block';
            document.querySelector('.cam-toolbar').style.display = 'flex';
            document.querySelectorAll('.cam-dropdown').forEach(function(d) { d.style.display = ''; });
            capturedImageData = null;
        }

        function savePhoto() {
            if (!capturedImageData) return;
            fetch('{{ route("forum.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    image: capturedImageData,
                    caption: document.getElementById('captionInput').value.trim(),
                }),
            }).then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); }).then(function(res) {
                if (res.ok && res.data.success) {
                    stopCamera();
                    var toast = document.getElementById('camToast');
                    toast.classList.add('cam-toast--active');
                    setTimeout(function() {
                        toast.classList.remove('cam-toast--active');
                        window.location.href = '{{ route("forum.index") }}';
                    }, 1500);
                } else {
                    alert(res.data.message || @json(__('Error al guardar.')));
                }
            }).catch(function() {
                alert(@json(__('Error de conexion. Intenta de nuevo.')));
            });
        }

        // Lumen initial size — solo ajustar cuando la cámara esté visible
        function setLumenSize() {
            if (!camBox || !lumenImg || !lumenImg.naturalWidth) return;
            var cr = camBox.getBoundingClientRect();
            // No ejecutar si la cámara está oculta (dimensiones 0)
            if (cr.width < 10 || cr.height < 10) return;
            var nw = lumenImg.naturalWidth;
            var nh = lumenImg.naturalHeight;
            var maxW = cr.width * 0.4;
            var maxH = cr.height * 0.6;
            var dw = nw;
            var ratio = nh / nw;
            if (dw > maxW) dw = maxW;
            if (dw * ratio > maxH) dw = maxH / ratio;
            dw = Math.max(dw, 80); // mínimo 80px para que siempre sea visible
            currentLumenWidth = dw;
            camLumen.style.width = dw + 'px';
        }
    </script>

    @include('components.footer')

    <x-settings-menu />
</body>
</html>
