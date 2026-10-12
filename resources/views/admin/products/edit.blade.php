@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form')
    </form>
@endsection
