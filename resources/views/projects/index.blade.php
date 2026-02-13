<x-app-layout>
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
                <div class="border-b py-2">
                    <a href="{{ route('projects.show', $project) }}"
                    class="text-blue-600 hover:underline">
                        {{ $project->name }}
                    </a>
                </div>
            @empty
                <p>No projects yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
