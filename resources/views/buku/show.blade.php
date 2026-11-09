@extends('layouts.app')

@section('content')
    <h1>Detail Buku</h1>
    <p>ID buku: {{ $id }}</p>
    <p>Judul: {{ $buku['judul'] }}</p>
    <p>Penulis: {{ $buku['penulis'] }}</p>
    <a href="{{ route('buku.index') }}">Kembali ke daftar</a>
@endsection
