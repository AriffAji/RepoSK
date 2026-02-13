<!DOCTYPE html>
<html>
<head>
    <title>Mirror Drive</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Public Drive</h1>

    <form action="{{ route('drive.search') }}" method="GET" class="max-w-md mx-auto mb-8">
        <label for="search" class="block mb-2.5 text-sm font-medium sr-only">
            Search
        </label>

        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-width="2"
                        d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                </svg>
            </div>

            <input
                type="search"
                id="search"
                name="q"
                class="block w-full p-3 ps-9 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                placeholder="Search public file..."
                value="{{ request('q') }}"
                required
            />

            <button
                type="submit"
                class="absolute end-1.5 bottom-1.5 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded text-xs px-3 py-1.5 focus:outline-none">
                Search
            </button>
        </div>
    </form>


    <div class="grid grid-cols-3 gap-4">
        @foreach($projects as $project)
            @php
                $root = $project->folders()->whereNull('parent_id')->first();
            @endphp

            @if($root)
                <a href="{{ route('drive.folder', $root->id) }}"
                class="bg-white p-6 rounded shadow hover:bg-gray-50">
                    📁 {{ $project->name }}
                </a>
            @endif
        @endforeach
    </div>

    

</div>

</body>
</html>
