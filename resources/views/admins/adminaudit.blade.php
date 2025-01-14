<x-layout>

    <x-pageheader>Audit Trail</x-pageheader>

    <div class="flex flex-col gap-3">
        {{-- FILTER SECTION --}}
        <div class="flex flex-row w-full justify-between items-center">
            <form class="flex w-full gap-6 items-center" method="GET" action="{{ url()->current() }}">
                {{-- Filter by Name --}}
                <div class="flex flex-col">
                    <label class="text-sm" for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ request()->query('name') }}" 
                        class="w-full outline-none px-2 py-1 text-sm border border-gray-300 rounded-md" 
                        placeholder="Search by Name"
                        oninput="this.form.submit()"
                    >
                </div>

                {{-- Filter by Role --}}
                <div class="flex flex-col">
                    <label class="text-sm" for="role">Role</label>
                    <select 
                        name="role" 
                        id="role" 
                        class="w-full text-sm border border-gray-300 rounded-md px-2 py-1"
                        onchange="this.form.submit()"
                    >
                        <option value="">All Roles</option>
                        <option value="1" {{ request()->query('role') == '1' ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ request()->query('role') == '2' ? 'selected' : '' }}>Staff</option>
                        <option value="3" {{ request()->query('role') == '3' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                {{-- Filter by Date --}}
                <div class="flex flex-col">
                    <label class="text-sm" for="date">Date</label>
                    <input 
                        type="date" 
                        name="date" 
                        id="date" 
                        value="{{ request()->query('date') }}" 
                        class="w-full text-sm border border-gray-300 rounded-md px-2 py-1"
                        onchange="this.form.submit()"
                    >
                </div>

                {{-- Rows Per Page --}}
                <div class="flex flex-col">
                    <label class="text-sm" for="rows">Rows per page</label>
                    <input 
                        type="number" 
                        name="rows" 
                        id="rows" 
                        value="{{ request()->query('rows', 10) }}" 
                        class="w-full text-sm border border-gray-300 rounded-md px-2 py-1"
                        min="1"
                        onchange="this.form.submit()"
                    >
                </div>
            </form>
        </div>

        {{-- AUDIT LOG TABLE --}}
        <div class="overflow-x-auto mt-4">
            <table class="main-table w-full text-sm border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border border-gray-300">Name</th>
                        <th class="px-4 py-2 border border-gray-300">Role</th>
                        <th class="px-4 py-2 border border-gray-300">Date</th>
                        <th class="px-4 py-2 border border-gray-300">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($audits as $audit)
                        <tr>
                            {{-- Name --}}
                            <td class="px-4 py-2 border border-gray-300">
                                {{ $audit->user->first_name ?? 'System' }} {{ $audit->user->last_name ?? '' }}
                            </td>
                            {{-- Role --}}
                            <td class="px-4 py-2 border border-gray-300">
                                {{ $audit->user->role->name ?? 'N/A' }}
                            </td>
                            {{-- Date --}}
                            <td class="px-4 py-2 border border-gray-300">
                                {{ $audit->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            {{-- Description (Action) --}}
                            <td class="px-4 py-2 border border-gray-300">
                                {{ ucfirst($audit->action) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center border border-gray-300">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            <div class="flex justify-center">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</x-layout>
