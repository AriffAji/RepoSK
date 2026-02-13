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
            @forelse($rootFolder->children as $folder)
                <a href="{{ route('folders.show', $folder) }}"
                   class="bg-white border rounded-lg p-5 hover:shadow transition">
                    <div class="text-4xl mb-3">📁</div>
                    <div class="text-sm font-medium truncate">
                        {{ $folder->name }}
                    </div>
                </a>
            @empty
                <p class="text-gray-500 col-span-4">No folders</p>
            @endforelse
        </div>


        {{-- FILES --}}
        <h3 class="font-semibold mb-4 text-lg">Files</h3>

        <div class="grid grid-cols-4 gap-6">
            @forelse($rootFolder->files as $file)
                <div class="bg-white border rounded-lg p-5 hover:shadow transition">

                    {{-- ICON --}}
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

                    {{-- NAME --}}
                    <div class="text-sm font-semibold truncate">
                        {{ $file->filename }}
                    </div>

                    {{-- STATS --}}
                    <div class="text-xs text-gray-400 mt-1">
                        {{ $file->download_count }} downloads
                    </div>

                    {{-- STATUS --}}
                   <div class="mt-4 flex justify-between items-center text-sm">

                        <a href="{{ route('files.download', $file) }}"
                        class="text-blue-600 hover:underline">
                            Download
                        </a>

                        <div class="flex gap-3">

                            @if($file->is_public)
                                <a href="{{ route('files.public', $file) }}"
                                class="text-green-600 hover:underline">
                                    Public
                                </a>
                            @endif

                            <form action="{{ route('files.delete', $file) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this file?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500 hover:underline">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>


                    {{-- ACTION --}}
                    {{-- <div class="mt-4 flex justify-between text-sm">
                        <a href="{{ route('files.download', $file) }}"
                           class="text-blue-600 hover:underline">
                            Download
                        </a>

                        @if($file->is_public)
                            <a href="{{ route('files.public', $file) }}"
                               class="text-green-600 hover:underline">
                                Public Link
                            </a>
                        @endif
                    </div> --}}

                </div>
            @empty
                <p class="text-gray-500 col-span-4">No files</p>
            @endforelse
        </div>


        {{-- UPLOAD FILE --}}
        <form action="{{ route('files.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="mt-12">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <input type="hidden" name="folder_id" value="{{ $rootFolder->id }}">

            <div class="flex gap-3">
                <input type="file"
                       name="file"
                       class="border p-3 rounded w-full"
                       required>

                <button class="bg-green-600 hover:bg-green-700 text-white px-6 rounded">
                    Upload
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
