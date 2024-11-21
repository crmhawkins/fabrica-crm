@extends('layouts.app')

@section('title', 'IVA')
@section('content-principal')

<div>
    @livewire('iva.edit-component', ['identificador'=>$id])
</div>

@endsection

