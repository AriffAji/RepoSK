<x-app-layout>
    <div class="max-w-xl mx-auto mt-10">
        <h2 class="text-xl font-bold mb-4">Create Project</h2>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Project Name</label>
                <input type="text" name="name"
                    class="w-full border rounded p-2"
                    required>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Create
            </button>
        </form>
    </div>
</x-app-layout>
