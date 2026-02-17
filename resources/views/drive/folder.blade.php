<!DOCTYPE html>
<html>

<head>
    <title>{{ $folder->name }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100">

    <div class="max-w-6xl mx-auto mt-10">

        {{-- <a href="{{ url()->previous() }}"
       class="text-blue-600 hover:underline mb-4 inline-block">
        ← Back
    </a> --}}

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

            {{-- Subfolders --}}
            @foreach ($folders as $sub)
                <div class="py-2 border-b">
                    <a href="{{ route('drive.folder', $sub) }}" class="hover:underline">
                        📁 {{ $sub->name }}
                    </a>
                </div>
            @endforeach

            {{-- Public Files --}}
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

</html>
