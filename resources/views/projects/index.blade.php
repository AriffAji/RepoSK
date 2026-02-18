{{-- <x-app-layout>
    <div class="max-w-4xl mx-auto mt-10">
        <div class="flex justify-between mb-6">
            <h2 class="text-xl font-bold">Projects</h2>
            <a href="{{ url('/projects/create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">
                + Create
            </a>
        </div>

        <div class="bg-white shadow rounded p-4">
            @forelse($projects as $project)
                <div class="border-b py-3 flex justify-between items-center">

                    <a href="{{ route('projects.show', $project) }}"
                       class="text-blue-600 hover:underline">
                        {{ $project->name }}
                    </a>

                    <form action="{{ route('projects.delete', $project) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this project and all contents?')">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-500 text-xs hover:underline">
                            Delete
                        </button>
                    </form>

                </div>
            @empty
                <p>No projects yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout> --}}


<x-app-layout>
    <div class="max-w-6xl mx-auto mt-10">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Projects
            </h2>

            <a href="{{ url('/projects/create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white
                      px-6 py-2 rounded-full shadow-sm
                      transition duration-200">
                + Create
            </a>
        </div>

        {{-- Projects Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            @forelse($projects as $project)

                <div class="bg-white rounded-2xl p-6 shadow-sm
                            hover:shadow-lg hover:-translate-y-1
                            transition-all duration-300">

                    <div class="flex items-start justify-between">

                        {{-- Left Side --}}
                        <a href="{{ route('projects.show', $project) }}" class="flex-1">

                            <div class="flex items-center gap-4">

                                {{-- Folder Icon --}}
                                <div class="text-yellow-500">
                                    <svg class="w-10 h-10"
                                         fill="currentColor"
                                         viewBox="0 0 30 24">
                                        <path d="M10 4H4a2 2 0 0 0-2 2v2h20V8a2 2 0 0 0-2-2h-8l-2-2z"/>
                                        <path d="M22 10H2v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8z"/>
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

                        {{-- Trash Icon --}}
                        <form action="{{ route('projects.delete', $project) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this project and all contents?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="text-gray-400 hover:text-red-600 transition">
                                {{-- <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M6 7h12M9 7V4h6v3m-7 0v13a1 1 0 001 1h4a1 1 0 001-1V7H9z"/>
                                </svg> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 24 24">
                                    <path d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z"></path>
                                </svg>
                            </button>
                        </form>

                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-500">
                    No projects yet.
                </div>
            @endforelse

        </div>

    </div>
</x-app-layout>
