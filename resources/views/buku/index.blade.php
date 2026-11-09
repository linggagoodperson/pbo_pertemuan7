@extends('layouts.app')

@section('content')
    <h1>{{ $judulHalaman }}</h1>

    <ul>
        @forelse ($daftarBuku as $buku)
            <li>
                <a href="{{ route('buku.show', $buku['id']) }}">{{ $buku['judul'] }}</a>
                — {{ $buku['penulis'] }}
            </li>
        @empty
            <li>Belum ada buku.</li>
        @endforelse
    </ul>
@endsection
