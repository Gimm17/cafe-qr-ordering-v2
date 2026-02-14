    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        // Brand tokens: Blue (logo) + Warm Bone
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        bone: '#F7F2E8',
                        ink: '#0B1020',
                        muted: '#5A647A',
                        line: 'rgba(4,3,96,.10)',
                        primary: {
                            50:  '#F0F5FF',
                            100: '#DCE7FF',
                            200: '#B9CFFF',
                            300: '#86ABFF',
                            400: '#5B84FF',
                            500: '#3B63FF',
                            600: '#234AE6',
                            700: '#1736B8',
                            800: '#0E238A',
                            900: '#040360',
                        }
                    },
                    boxShadow: {
                        soft: '0 10px 30px rgba(4,3,96,.10)',
                        soft2: '0 6px 18px rgba(4,3,96,.08)',
                    }
                }
            }
        }
    </script>

    <style>
        :root{
            --bone:#F7F2E8;
            --ink:#0B1020;
            --muted:#5A647A;
            --line:rgba(4,3,96,.10);
            --primary-600:#234AE6;
            --primary-900:#040360;
        }
        html, body{ font-family: 'Inter', sans-serif; }
        .ui-card{ background:#ffffff; border:1px solid var(--line); border-radius:18px; box-shadow:0 10px 30px rgba(4,3,96,.08); }
        .ui-card-flat{ background:#ffffff; border:1px solid var(--line); border-radius:18px; box-shadow:0 6px 18px rgba(4,3,96,.06); }
        .ui-chip{ border:1px solid var(--line); background:rgba(255,255,255,.70); border-radius:999px; }
        .ui-focus:focus{ outline:none; box-shadow:0 0 0 3px rgba(35,74,230,.20); border-color:rgba(35,74,230,.50); }
        .ui-btn{ border-radius:14px; }
        .ui-divider{ border-color: var(--line); }
        /* Make tap targets comfortable on mobile */
        .tap-44{ min-height:44px; }

        /* Tailwind CDN doesn't always include line-clamp plugin */
        .line-clamp-1, .line-clamp-2{ display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden; }
        .line-clamp-1{ -webkit-line-clamp:1; }
        .line-clamp-2{ -webkit-line-clamp:2; }
    </style>
