<x-app-layout>
    <div class="max-w-6xl mx-auto mt-10">

        <h2 class="text-2xl font-bold mb-8">
            {{ $project->name }}
        </h2>

        {{-- ERROR MESSAGE --}}
        @if ($errors->has('file'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded">
                {{ $errors->first('file') }}
            </div>
        @endif


        {{-- FOLDERS --}}
        <h3 class="font-semibold mb-4 text-lg">Folders</h3>

        <div class="grid grid-cols-4 gap-6 mb-10">
            @forelse($subFolders as $sub)
                <a href="{{ route('folders.show', $sub) }}"
                    class="bg-white border rounded-lg p-5 hover:shadow transition">
                    <div class="text-4xl mb-3">📁</div>
                    <div class="text-sm font-medium truncate">
                        {{ $sub->name }}
                    </div>
                </a>
            @empty
                <p class="text-gray-500 col-span-4">No folders</p>
            @endforelse
        </div>


        {{-- FILES --}}
        <h3 class="font-semibold mb-4 text-lg">Files</h3>

        <div class="grid grid-cols-4 gap-6">

            @forelse($files as $file)
                <div class="bg-white border rounded-lg p-5 hover:shadow transition">

                    {{-- FILE ICON --}}
                    <div class="text-4xl mb-3">
                        @switch($file->extension)
                            @case('pdf')
                                📕
                            @break

                            @case('doc')
                            @case('docx')
                                📘
                            @break

                            @case('xls')
                            @case('xlsx')
                                📗
                            @break

                            @case('png')
                            @case('jpg')

                            @case('jpeg')
                                🖼️
                            @break

                            @default
                                📄
                        @endswitch
                    </div>

                    {{-- FILE NAME --}}
                    <div class="text-sm font-semibold truncate">
                        {{ $file->display_name }}
                        {{-- .{{ $file->extension }} --}}
                        <span class="text-xs text-gray-400">
                            (v{{ $file->version }})
                        </span>
                    </div>

                    {{-- DOWNLOAD COUNT --}}
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $file->download_count }} downloads
                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 flex justify-between items-center text-sm">

                        <a href="{{ route('files.download', $file) }}" class="text-blue-600 hover:underline">
                            Download
                        </a>

                        <div class="flex items-center gap-4">

                            {{-- TOGGLE PUBLIC --}}
                            <form action="{{ route('files.togglePublic', $file) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" onchange="this.form.submit()" class="sr-only peer"
                                        {{ $file->is_public ? 'checked' : '' }}>

                                    <div
                                        class="relative w-9 h-5 bg-gray-300 rounded-full
                                                peer peer-checked:bg-green-500
                                                after:content-['']
                                                after:absolute after:top-[2px] after:left-[2px]
                                                after:bg-white after:rounded-full
                                                after:h-4 after:w-4
                                                after:transition-all
                                                peer-checked:after:translate-x-4">
                                    </div>
                                </label>
                            </form>

                            {{-- DELETE --}}
                            <form action="{{ route('files.delete', $file) }}" method="POST"
                                onsubmit="return confirm('Delete this file?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500 hover:underline text-xs">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>


                    {{-- VERSION LIST (old versions) --}}
                    @if (isset($allVersions[$file->base_name]) && count($allVersions[$file->base_name]) > 1)
                        <div class="mt-4 border-t pt-3 text-xs text-gray-500 space-y-1">

                            @foreach ($allVersions[$file->base_name] as $version)
                                @if (!$version->is_latest)
                                    <div class="flex justify-between">

                                        <span>
                                            v{{ $version->version }}
                                        </span>

                                        <a href="{{ route('files.download', $version) }}"
                                            class="text-blue-500 hover:underline">
                                            Download
                                        </a>

                                    </div>
                                @endif
                            @endforeach

                        </div>
                    @endif

                </div>

                @empty
                    <p class="text-gray-500 col-span-4">No files</p>
                @endforelse

            </div>


            {{-- UPLOAD --}}
            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" class="mt-12">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="folder_id" value="{{ $folder->id }}">

                <div class="flex gap-3">
                    <input type="file" name="file" class="border p-3 rounded w-full" required>

                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 rounded">
                        Upload
                    </button>
                </div>
            </form>

        </div>
    </x-app-layout>
