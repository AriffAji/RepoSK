<x-app-layout>
    <div class="max-w-6xl mx-auto mt-10">

        {{-- BREADCRUMB --}}
       <nav class="flex md:flex justify-between mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2.5">

                <li>
                    <div class="flex items-center">
                        <a href="{{ route('drive.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-blue-600">
                            Drive
                        </a>
                    </div>
                </li>

                @foreach($breadcrumb as $crumb)
                    <li>
                        <div class="flex items-center space-x-2.5">
                            <svg class="w-3.5 h-3.5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m9 5 7 7-7 7"/>
                            </svg>

                            <a href="{{ route('drive.folder', $crumb) }}"
                            class="text-sm font-medium text-gray-600 hover:text-blue-600">
                                {{ $crumb->name }}
                            </a>
                        </div>
                    </li>
                @endforeach

            </ol>
        </nav>



        {{-- TITLE --}}
        <h2 class="text-2xl font-bold mb-8">
            📁 {{ $folder->name }}
        </h2>


        {{-- SUBFOLDERS --}}
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
                <p class="text-gray-500 col-span-4">No subfolders</p>
            @endforelse
        </div>


        {{-- FILES --}}
        <div class="grid grid-cols-4 gap-6">
            @forelse($files as $file)
                <div class="bg-white border rounded-lg p-5 hover:shadow transition">

                    <div class="text-4xl mb-3">📄</div>

                    <div class="text-sm font-semibold truncate">
                        {{ $file->filename }}
                    </div>

                    <div class="text-xs text-gray-400 mt-1">
                        {{ $file->download_count }} downloads
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('files.download', $file) }}"
                           class="text-blue-600 hover:underline text-sm">
                            Download
                        </a>
                    </div>

                </div>
            @empty
                <p class="text-gray-500 col-span-4">No files</p>
            @endforelse
        </div>

    </div>
</x-app-layout>
