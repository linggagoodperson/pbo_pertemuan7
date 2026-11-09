<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class BukuController extends Controller
{
    public function index(): View
    {
        $daftarBuku = $this->daftarBuku();
        $judulHalaman = 'Daftar Buku';

        return view('buku.index', compact('daftarBuku', 'judulHalaman'));
    }

    public function show(string $id): View
    {
        foreach ($this->daftarBuku() as $buku) {
            if ((string) $buku['id'] === $id) {
                return view('buku.show', compact('id', 'buku'));
            }
        }

        abort(404);
    }

    /**
     * @return list<array{id: int, judul: string, penulis: string}>
     */
    private function daftarBuku(): array
    {
        return [
            ['id' => 1, 'judul' => 'Belajar PHP', 'penulis' => 'Andi'],
            ['id' => 2, 'judul' => 'Dasar Laravel', 'penulis' => 'Budi'],
        ];
    }
}
