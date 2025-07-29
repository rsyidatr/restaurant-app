@extends('layouts.app')

@section('title', 'Waiter Dashboard')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Waiter Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Active Orders</h2>
            <!-- Active orders will be added here -->
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Table Status</h2>
            <!-- Table status will be added here -->
        </div>
    </div>
</div>
@endsection
