@extends('layouts.admin')

@section('title', 'Add Brand')

@section('content')
    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.brands._form')
    </form>
@endsection
