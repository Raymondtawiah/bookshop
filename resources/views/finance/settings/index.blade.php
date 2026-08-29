@extends('layouts.finance')

@section('title', 'Settings')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
        <p class="text-gray-600">Manage finance team members</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Finance Team Members</h2>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">Finance team members are managed from the Admin Staff page. Below is a list of current finance team members.</p>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($financeTeam as $member)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $member->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $member->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $member->role }}</td>
                                <td class="px-4 py-3">
                                    @if($member->email_verified_at)
                                        <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
