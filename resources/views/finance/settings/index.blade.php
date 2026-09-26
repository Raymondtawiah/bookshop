@extends('layouts.finance')

@section('title', 'Settings')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Settings</h1>
                <p class="page-subtitle">Manage finance team members</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Finance Team Members</h2>
            </div>
            <div class="panel-body" style="padding: 0;">
                <div class="px-6 py-4 text-sm text-gray-600 border-b border-gray-100">
                    Finance team members are managed from the Admin Staff page. Below is a list of current finance team members.
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold">
                            <tr>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Name</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Email</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Role</th>
                                <th class="px-6 py-4 font-semibold whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($financeTeam as $member)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900 font-medium">{{ $member->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $member->email }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($member->email_verified_at)
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
