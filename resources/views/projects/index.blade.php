<x-app-layout>

    <div x-data="{ open: false, actionUrl: '', projectName: '' }" class="max-w-6xl mx-auto mt-10">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Projects
            </h2>

            <a href="{{ route('projects.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white
                   px-6 py-2 rounded-full shadow-sm
                   transition duration-200">
                + Create
            </a>
        </div>

        {{-- Projects Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            @forelse($projects as $project)
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm
                       hover:shadow-lg hover:-translate-y-1
                       transition-all duration-300">

                    <div class="flex items-start justify-between">

                        {{-- Left Side --}}
                        <a href="{{ route('projects.show', $project) }}" class="flex-1">

                            <div class="flex items-center gap-4">

                                {{-- Folder Icon --}}
                                <div class="text-yellow-500">
                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 30 24">
                                        <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z" />
                                        <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z" />
                                    </svg>
                                </div>

                                <div>
                                    <div class="font-semibold text-gray-800">
                                        {{ $project->name }}
                                    </div>

                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $project->file_count ?? 0 }} files •
                                        {{ $project->total_download ?? 0 }} downloads
                                    </div>
                                </div>

                            </div>

                        </a>

                        {{-- Trash Button --}}
                        <button
                            @click="
                            open = true; 
                            actionUrl = '{{ route('projects.delete', $project) }}';
                            projectName = '{{ $project->name }}'
                        "
                            class="text-gray-400 hover:text-red-600 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M10 2L9 3H4v2h1v15a2 2 0 002 2h10a2 2 0 002-2V5h1V3h-5l-1-1h-4zM7 5h10v15H7V5zm2 2v11h2V7H9zm4 0v11h2V7h-2z" />
                            </svg>
                        </button>

                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-500">
                    No projects yet.
                </div>
            @endforelse

        </div>


        {{-- DELETE MODAL --}}
        <div x-show="open" x-transition class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                <div class="flex items-center gap-3 mb-4 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M5.93 19h12.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L4.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>
                    <h3 class="text-lg font-semibold">
                        Confirm Delete
                    </h3>
                </div>

                <p class="text-gray-600 mb-6">
                    Delete project
                    <span class="font-semibold text-gray-800" x-text="projectName"></span>
                    and all contents?
                </p>

                <div class="flex justify-end gap-3">

                    <button @click="open = false" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">
                        Cancel
                    </button>

                    <form :action="actionUrl" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>

</x-app-layout>
