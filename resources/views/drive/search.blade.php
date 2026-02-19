<!DOCTYPE html>
<html>

<head>
    <title>Search Result : {{ $keyword }}</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/Politeknik_Pertanian_Negeri_Payakumbuh.png') }}" type="image/x-icon">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

    <div class="max-w-6xl mx-auto py-12 px-4">
        @php
            function highlight($text, $keyword)
            {
                return str_ireplace(
                    $keyword,
                    '<span class="bg-yellow-200 px-1 rounded">' . $keyword . '</span>',
                    $text,
                );
            }
        @endphp

        <a href="{{ route('drive.index') }}" class="flex items-center gap-1 hover:text-blue-600 transition">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75M4.5 10.5V20h15v-9.5" />
            </svg>

            Home
        </a>

        {{-- Title --}}
        <h2 class="text-2xl font-bold mt-4 mb-10 text-gray-800">
            Search Result: "{{ $keyword }}"
        </h2>

        {{-- Counting --}}
        @php
            $totalResults = $folders->count() + $files->count();
        @endphp

        <div class="mb-8 text-gray-600">
            {{ $totalResults }} results found for
            <span class="font-semibold text-gray-800">"{{ $keyword }}"</span>
        </div>

        {{-- Folder Results --}}
        @if ($folders->count())
            <div class="mb-12">
                <h3 class="text-lg font-semibold mb-6 text-gray-700">
                    Folders
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    @foreach ($folders as $folder)
                        <a href="{{ route('drive.folder', $folder->id) }}"
                            class="bg-white rounded-2xl p-6 shadow-sm
                              hover:shadow-lg hover:-translate-y-1
                              transition-all duration-300">

                            <div class="text-yellow-500 mb-4">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z" />
                                    <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z" />
                                </svg>
                            </div>

                            <div class="font-semibold text-gray-800 truncate">
                                {{ $folder->name }}
                            </div>

                            <div class="text-sm text-gray-500 mt-2">
                                Open folder →
                            </div>

                        </a>
                    @endforeach

                </div>
            </div>
        @endif


        {{-- File Results --}}
        @if ($files->count())
            <div>
                <h3 class="text-lg font-semibold mb-6 text-gray-700">
                    Files
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                    @foreach ($files as $file)
                        <div
                            class="bg-white rounded-2xl p-6 shadow-sm
                                hover:shadow-lg hover:-translate-y-1
                                transition-all duration-300">

                            {{-- File Icon --}}
                            <div class="text-gray-400 mb-4">
                                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <path d="M14 2v6h6" />
                                </svg>
                            </div>

                            {{-- Filename --}}
                            <div class="font-semibold text-gray-800 truncate">
                                {{ $file->filename }}
                            </div>

                            {{-- Download Count --}}
                            <div class="text-sm text-gray-500 mt-2">
                                {{ $file->download_count }} downloads
                            </div>

                            {{-- Download Button --}}
                            <a href="{{ route('files.public', $file) }}"
                                class="mt-4 inline-block bg-blue-600 hover:bg-blue-700
                                  text-white text-sm px-4 py-2 rounded-lg
                                  transition duration-200">
                                Download
                            </a>

                        </div>
                    @endforeach

                </div>
            </div>
        @endif


        {{-- No Result --}}
        @if (!$folders->count() && !$files->count())
            <div class="text-center text-gray-500 mt-16">
                No result found.
            </div>
        @endif

    </div>

</body>

</html>
