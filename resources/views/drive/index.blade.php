<!DOCTYPE html>
<html>

<head>
    <title>Public Drive</title>

    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/Politeknik_Pertanian_Negeri_Payakumbuh.png') }}" type="image/x-icon">

    {{-- AOS CSS --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">



</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">


    {{-- ================= TOAST ================= --}}
    @if (session('success'))
        <div id="toast" class="fixed top-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                document.getElementById('toast')?.remove();
            }, 3000);
        </script>
    @endif


    <div class="max-w-6xl mx-auto py-12 px-4">

        {{-- HEADER --}}
        <div class="mb-10" data-aos="fade-up">
            <h1 class="text-3xl font-bold text-gray-800">
                Public Drive
            </h1>
            <p class="text-gray-500 mt-2">
                Share files easily • Clean & Fast
            </p>
        </div>

        {{-- SEARCH --}}
        <form action="{{ route('drive.search') }}" method="GET" class="max-w-xl mb-12" data-aos="fade-up">

            <div class="relative">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search public file..."
                    class="w-full pl-12 pr-28 py-3 rounded-xl border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                    required />

                {{-- Icon --}}
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>

                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2
                            bg-blue-600 hover:bg-blue-700
                            text-white text-sm px-5 py-2 rounded-lg
                            transition duration-200">
                    Search
                </button>
            </div>
        </form>

        {{-- FOLDER SECTION --}}
        <h2 class="text-xl font-semibold mb-6 text-gray-700 " data-aos="fade-up">
            Available Folders
        </h2>

        {{-- Loading Skeleton --}}
        <div id="skeleton" class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-6"
            data-aos="fade-up">

            @for ($i = 0; $i < 4; $i++)
                <div class="bg-white p-6 rounded-xl shadow animate-pulse">
                    <div class="h-10 w-10 bg-gray-200 rounded mb-4"></div>
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            @endfor
        </div>

        {{-- REAL CONTENT --}}
        <div id="content" class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 hidden"
            data-aos="fade-up">

            @foreach ($projects as $project)
                @php
                    $root = $project->folders->whereNull('parent_id')->first();
                    $fileCount = $root ? $root->files->where('is_public', true)->count() : 0;
                    $downloadTotal = $root ? $root->files->sum('download_count') : 0;
                @endphp

                @if ($root)
                    <a href="{{ route('drive.folder', $root->id) }}" data-aos="fade-up"
                        data-aos-delay="{{ $loop->index * 100 }}"
                        class="group bg-white rounded-2xl p-6 shadow-sm
                                hover:shadow-xl hover:-translate-y-2
                                transition-all duration-300 ease-out">

                        {{-- Folder Icon --}}
                        <div
                            class="text-yellow-500 mb-4
                                        group-hover:scale-110
                                        transition duration-300">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z" />
                                <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z" />
                            </svg>
                        </div>

                        {{-- Folder Name --}}
                        <div class="font-semibold text-gray-800 truncate">
                            {{ $project->name }}
                        </div>

                        {{-- Folder Info --}}
                        <div class="text-sm text-gray-500 mt-2">
                            {{ $fileCount }} files • {{ $downloadTotal }} downloads
                        </div>

                    </a>
                @endif
            @endforeach

        </div>

    </div>


    {{-- ================= JS SECTION ================= --}}

    {{-- AOS JS --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Hide skeleton after load
        window.addEventListener('load', function() {
            document.getElementById('skeleton')?.remove();
            document.getElementById('content')?.classList.remove('hidden');
        });
    </script>

</body>

</html>
