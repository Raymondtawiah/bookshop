@extends('layouts.admin')

@section('title', 'Webinar Surveys - Visa with Nathaniel')

@section('content')
    <div class="content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Webinar Surveys</h1>
                <p class="page-subtitle">Where attendees heard about the webinar.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.webinars.index') }}" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors text-sm font-medium">
                    Back to Webinars
                </a>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header" style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <h2 class="panel-title">Survey Responses</h2>
                <a href="{{ route('admin.webinars.surveys.export', request()->query()) }}" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-medium whitespace-nowrap">
                    Export CSV
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Webinar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Source</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Additional Comments</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">IP Address</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Submitted At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($surveys as $survey)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $survey->webinar->title ?? 'Deleted Webinar' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 capitalize">{{ $survey->source }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $survey->additional_comments ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $survey->ip_address ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $survey->created_at->timezone('Africa/Accra')->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No survey responses found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection
