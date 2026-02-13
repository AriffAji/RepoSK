<!DOCTYPE html>
<html>
<head>
    <title>Search Result</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10">

    <a href="{{ route('drive.index') }}"
       class="text-blue-600 hover:underline">
        ← Back to Drive
    </a>

    <h2 class="text-xl font-bold mt-4 mb-6">
        Search Result: "{{ $keyword }}"
    </h2>

    <div class="bg-white p-6 rounded shadow space-y-6">

        {{-- Folder Result --}}
        @if($folders->count())
            <div>
                <h3 class="font-semibold mb-3">Folders</h3>

                @foreach($folders as $folder)
                    <div class="py-2 border-b">
                        <a href="{{ route('drive.folder', $folder->id) }}"
                           class="hover:underline">
                            📁 {{ $folder->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif


        {{-- File Result --}}
        @if($files->count())
            <div>
                <h3 class="font-semibold mb-3">Files</h3>

                @foreach($files as $file)
                    <div class="flex justify-between py-2 border-b">
                        <span>📄 {{ $file->filename }}</span>

                        <a href="{{ route('files.public', $file) }}"
                           class="text-blue-600 hover:underline">
                            Download
                        </a>
                    </div>
                @endforeach
            </div>
        @endif


        {{-- No Result --}}
        @if(!$folders->count() && !$files->count())
            <p class="text-gray-500">No result found.</p>
        @endif

    </div>

</div>

</body>
</html>
