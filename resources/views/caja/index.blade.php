@extends('layouts.app')

@section('title', 'Ver Diario de caja')

@section('head')
    @vite(['resources/sass/productos.scss'])
    @vite(['resources/sass/alumnos.scss'])
@endsection

@section('content-principal')
<div>
    @livewire('caja.index-component')
</div>
@endsection
