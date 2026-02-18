<!DOCTYPE html>
<html>

<head>
    <title>{{ $folder->name }}</title>

    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/Politeknik_Pertanian_Negeri_Payakumbuh.png') }}" type="image/x-icon">

    {{-- AOS --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">



            <div class="max-w-6xl mx-auto py-12 px-4">

                {{-- ================= BREADCRUMB ================= --}}
                <div class="mb-8" data-aos="fade-up">

                    {{-- Folder Title --}}
                    <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z"/>
                            <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z"/>
                        </svg>
                        {{ $folder->name }}
                    </h1>

                    {{-- Breadcrumb --}}
                    <nav class="flex items-center text-sm text-gray-500 mt-3 space-x-2" data-aos="fade-up"> 

                        {{-- Home --}}
                        <a href="{{ route('drive.index') }}"
                        class="flex items-center gap-1 hover:text-blue-600 transition" >

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 9.75L12 4l9 5.75M4.5 10.5V20h15v-9.5"/>
                            </svg>

                            Home
                        </a>

                        @foreach ($breadcrumb as $crumb)
                            <span class="text-gray-400">/</span>

                            @if ($loop->last)
                                <span class="text-gray-700 font-medium">
                                    {{ $crumb->name }}
                                </span>
                            @else
                                <a href="{{ route('drive.folder', $crumb->id) }}"
                                class="hover:text-blue-600 transition">
                                    {{ $crumb->name }}
                                </a>
                            @endif
                        @endforeach

                    </nav>

                </div>


                {{-- ================= CONTENT GRID ================= --}}
                <div class="grid gap-6
                            grid-cols-1
                            sm:grid-cols-2
                            md:grid-cols-3
                            lg:grid-cols-4" data-aos="fade-up">

                    {{-- ===== SUBFOLDERS ===== --}}
                    @foreach ($folders as $sub)
                        <a href="{{ route('drive.folder', $sub->id) }}"
                        data-aos="fade-up"
                        data-aos-delay="{{ $loop->index * 100 }}"
                        class="group bg-white rounded-2xl p-6 shadow-sm
                                hover:shadow-xl hover:-translate-y-2
                                transition-all duration-300">

                            <div class="text-yellow-500 mb-4
                                        group-hover:scale-110
                                        transition duration-300">
                                <svg class="w-12 h-12"
                                    fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z"/>
                                    <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z"/>
                                </svg>
                            </div>

                            <div class="font-semibold text-gray-800 truncate">
                                {{ $sub->name }}
                            </div>

                            <div class="text-sm text-gray-500 mt-2">
                                View folder →
                            </div>

                        </a>
                    @endforeach


                    {{-- ===== PUBLIC FILES ===== --}}
                    @foreach ($files as $file)

                        <div data-aos="fade-up"
                            data-aos-delay="{{ ($loop->index + count($folders)) * 100 }}"
                            class="group bg-white rounded-2xl p-6 shadow-sm
                                    hover:shadow-xl hover:-translate-y-2
                                    transition-all duration-300">

                            {{-- File Icon --}}
                            <div class="text-red-500 mb-4
                                        group-hover:scale-110
                                        transition duration-300">
                                📄
                            </div>

                            {{-- File Name --}}
                            <div class="font-semibold text-gray-800 truncate">
                                {{ $file->filename }}
                            </div>

                            {{-- Downloads --}}
                            <div class="text-sm text-gray-500 mt-2">
                                {{ $file->download_count }} downloads
                            </div>

                            {{-- Download Button --}}
                            @if ($file->is_public)
                                <a href="{{ route('files.public', $file) }}"
                                class="mt-4 inline-block bg-blue-600 hover:bg-blue-700
                                        text-white text-sm px-4 py-2 rounded-lg
                                        transition duration-200">
                                    Download
                                </a>
                            @endif
                        </div>

                    @endforeach

                </div>

                {{-- EMPTY STATE --}}
                @if(count($folders) === 0 && count($files) === 0)
                    <div class="text-center mt-16 text-gray-500" data-aos="fade-up">
                        No files or folders found.
                    </div>
                @endif

            </div>


{{-- ================= AOS JS ================= --}}
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>

</body>
</html>



{{-- <!DOCTYPE html>
<html>

<head>
    <title>{{ $folder->name }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100">

    <div class="max-w-6xl mx-auto mt-10">

        <a href="{{ route('drive.index') }}" class="text-blue-600 hover:underline">
            ← Home
        </a>

        <h1 class="text-2xl font-bold mb-6">
            📁 {{ $folder->name }}
        </h1>

        <div class="mb-4 text-sm text-gray-600">
            <a href="{{ route('drive.index') }}" class="hover:underline">
                Drive
            </a>

            @foreach ($breadcrumb as $crumb)
                &gt;
                <a href="{{ route('drive.folder', $crumb->id) }}" class="hover:underline">
                    {{ $crumb->name }}
                </a>
            @endforeach
        </div>

        <div class="bg-white p-6 rounded shadow">

            
            @foreach ($folders as $sub)
                <div class="py-2 border-b">
                    <a href="{{ route('drive.folder', $sub) }}" class="hover:underline">
                        📁 {{ $sub->name }}
                    </a>
                </div>
            @endforeach

       
            @foreach ($files as $file)
                <div class="py-2 border-b flex justify-between items-center">
                    <div>
                        📄 {{ $file->filename }}

                        <span class="text-xs text-gray-400 ml-2">
                            ({{ $file->download_count }} downloads)
                        </span>
                    </div>

                    @if ($file->is_public && $file->public_token)
                        <a href="{{ route('files.public.token', $file->public_token) }}"
                            class="text-blue-600 hover:underline" target="_blank">
                            Download
                        </a>
                    @endif
                </div>
            @endforeach


        </div>

    </div>

</body>

</html> --}}
