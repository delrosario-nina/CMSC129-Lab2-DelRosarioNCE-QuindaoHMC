<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nina\'s Recipe Diary')</title>

    {{-- Tailwind CDN (replace with Vite build in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts: Press Start 2P (pixel/diary display) + Courier Prime (body) + Kiwisoda --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Kiwisoda&display=swap" rel="stylesheet">

    {{-- Material Symbols (single font import) --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <style>
        /* ─── Design Tokens ─────────────────────────────── */
        :root {
            --paper:   #f7f5f0;
            --ink:     #1a1a1a;
            --muted:   #6b6860;
            --border:  #c8c4bc;
            --accent:  #4caf50;   /* the green used for buttons */
            --accent-h:#388e3c;
        }

        /* ─── Base ──────────────────────────────────────── */
        html, body {
            color: var(--ink);
            min-height: 100vh;
        }

        /* bg.png covers everything below the navbar */
        .page-bg {
            background-size: cover;
            background-position: center top;
            background-attachment: fixed;
            min-height: calc(100vh - 73px);
        }

        /* ─── Typography helpers ─────────────────────────── */
        .diary-title  { font-family: 'Kiwisoda', sans-serif; font-weight: 700; }
        .diary-body   { font-family: 'Courier Prime', 'Courier New', monospace; }
        .diary-display{ font-family: 'Kiwisoda', sans-serif; }

        /* ─── Tag pill ───────────────────────────────────── */
        .diary-tag {
            font-family: 'Courier Prime', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.03em;
        }

        /* ─── Recipe index row hover ─────────────────────── */
        .recipe-row { padding: 0.6rem 0; border-bottom: 1px solid transparent; }
        .recipe-row:hover { border-bottom-color: var(--border); }

        /* ─── Let's Cook button ──────────────────────────── */
        .btn-cook {
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            background: var(--accent);
            color: #fff;
            border-radius: 9999px;
            padding: 0.55rem 1.6rem;
            display: inline-block;
        }
        .btn-cook:hover { background: var(--accent-h); }

        /* ─── Page-turning navigation ────────────────────── */
        .page-turn-left, .page-turn-right {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(4px);
            border: 2px solid rgba(0, 0, 0, 0.1);
            opacity: 0.95;
        }
        .page-turn-left span, .page-turn-right span {
            font-size: 2.25rem;
            line-height: 1;
        }
        .page-turn-left:hover, .page-turn-right:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.22);
            opacity: 1;
        }
        .page-turn-left:active, .page-turn-right:active {
            transform: translateY(-50%) scale(0.9);
        }

        /* ─── Section box header (Ingredients / Process) ─── */
        .section-box {
            border: 2px solid var(--ink);
            border-radius: 0.5rem;
        }
        .section-box-header {
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-bottom: 2px solid var(--ink);
        }
        .section-box-body { padding: 1rem; }

        /* ─── Layout width (page width ~80%) ─────────────── */
        .content-shell {
            width: 80vw;
            max-width: 1200px;
            min-width: 900px;
        }

        /* ─── Nav ────────────────────────────────────────── */
        .nav-logo-box {
            border: 2px solid var(--ink);
            border-radius: 2rem;
            padding: 0.5rem 0.75rem;
            display: inline-block;
            transition: padding-right 0.2s ease;
        }

        /* ─── Sidebar (removed and replaced by header icons) ────────────────────────────────────── */
        .main-content {
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* ─── Drawer ─────────────────────────────────────── */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

    {{-- ── Navigation Header ────────────────────────────── --}}
    <nav class="bg-white border-b-2 border-gray-900" style="height: 73px;">
        <div class="content-shell mx-auto px-6 py-4 flex items-center justify-between h-full">

            {{-- Delete icon (upper left) --}}
            <a href="{{ route('recipes.trash') }}" class="material-symbols-outlined text-2xl text-gray-900 hover:text-red-500" title="Trash">delete_history</a>

            {{-- Centered Logo --}}
            <div class="relative inline-block group">
                <a href="{{ route('recipes.index') }}" class="nav-logo-box" id="logo-link">
                    <span class="diary-display text-4xl text-gray-900 leading-none" id="diary-title">Nina's Recipe Diary</span>
                    {{-- Edit icon inside the border, expands border on hover --}}
                    <span id="edit-title-btn"
                        class="material-symbols-outlined inline-block align-top ml-0 w-0 overflow-hidden opacity-0 group-hover:w-7 group-hover:ml-2 group-hover:opacity-100 text-2xl text-gray-500 hover:text-gray-800 transition-all duration-200 cursor-pointer"
                        title="Edit">
                        edit
                    </span>
                </a>
            </div>

            {{-- Edit Logo Modal --}}
            <div id="edit-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 shadow-lg w-80">
                    <h2 class="diary-body font-bold mb-3 text-gray-800">Edit Diary Name</h2>
                    <input id="diary-name-input" type="text" value="Nina's Recipe Diary"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 diary-body focus:outline-none focus:ring-2 focus:ring-amber-400 mb-4">
                    <div class="flex gap-2">
                        <button id="save-title-btn"
                                class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition diary-body font-bold flex-1">
                            Save
                        </button>
                        <button id="cancel-title-btn"
                                class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition diary-body flex-1">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Add icon (upper right) --}}
            <a href="{{ route('recipes.create') }}" class="material-symbols-outlined text-2xl text-gray-900 hover:text-amber-700" title="Add Recipe">add</a>
        </div>
    </nav>

    {{-- ── Main Content Area ─────────────────────────────── --}}
    <div class="main-content page-bg">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="max-w-5xl mx-auto px-6 pt-4">
                <x-flash-message type="success" :message="session('success')" :modal="true" />
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-5xl mx-auto px-6 pt-4">
                <x-flash-message type="error" :message="session('error')" :modal="true" />
            </div>
        @endif

        {{-- Main Content --}}
        <main class="content-shell mx-auto px-6 py-10">
            @yield('content')
        </main>

    </div>

    {{-- Page-turning JavaScript --}}
    <script>
        // Keyboard navigation for recipe pages
        document.addEventListener('keydown', function(e) {
            // Left arrow key - previous recipe
            if (e.key === 'ArrowLeft') {
                const prevLink = document.querySelector('.page-turn-left');
                if (prevLink) {
                    prevLink.click();
                }
            }
            // Right arrow key - next recipe
            else if (e.key === 'ArrowRight') {
                const nextLink = document.querySelector('.page-turn-right');
                if (nextLink) {
                    nextLink.click();
                }
            }
        });

        // Add subtle page entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const main = document.querySelector('main');
            if (main) {
                // Removed animation to improve performance
            }
        });

        // Title editing
        const editBtn = document.getElementById('edit-title-btn');
        const editModal = document.getElementById('edit-modal');
        const saveBtn = document.getElementById('save-title-btn');
        const cancelBtn = document.getElementById('cancel-title-btn');
        const diaryTitle = document.getElementById('diary-title');
        const diaryInput = document.getElementById('diary-name-input');

        editBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            diaryInput.value = diaryTitle.textContent;
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            diaryInput.focus();
        });

        saveBtn.addEventListener('click', function() {
            const newName = diaryInput.value.trim();
            if (newName) {
                diaryTitle.textContent = newName;
                localStorage.setItem('diaryTitle', newName);
            }
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        });

        cancelBtn.addEventListener('click', function() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        });

        // Load saved title on page load
        const savedTitle = localStorage.getItem('diaryTitle');
        if (savedTitle) {
            diaryTitle.textContent = savedTitle;
        }
    </script>

</body>
</html>
