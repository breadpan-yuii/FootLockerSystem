@extends('layouts.storefront')

@section('title', 'My Account')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-6">My Account</h1>

        <div class="space-y-6">
            <div class="bg-white border-2 border-gray-900 rounded-lg shadow-[3px_3px_0_#000] p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white border-2 border-gray-900 rounded-lg shadow-[3px_3px_0_#000] p-6 sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white border-2 border-gray-900 rounded-lg shadow-[3px_3px_0_#000] p-6 sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
