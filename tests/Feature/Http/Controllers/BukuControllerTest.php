<?php

describe('book pages', function () {
    test('shows the sample books on the index page', function () {
        $response = $this->get('/buku');

        $response->assertOk()
            ->assertSee('Daftar Buku')
            ->assertSee('Belajar PHP')
            ->assertSee('Andi')
            ->assertSee('Dasar Laravel')
            ->assertSee('Budi');
    });

    test('shows a book detail and returns 404 for an unknown book', function () {
        $this->get('/buku/1')
            ->assertOk()
            ->assertSee('Detail Buku')
            ->assertSee('ID buku: 1')
            ->assertSee('Belajar PHP')
            ->assertSee('Andi');

        $this->get('/buku/999')->assertNotFound();
    });

    test('does not expose create, edit, or delete pages and actions', function () {
        $this->get('/buku/create')->assertNotFound();
        $this->get('/buku/1/edit')->assertNotFound();
        $this->post('/buku', [])->assertMethodNotAllowed();
        $this->put('/buku/1', [])->assertMethodNotAllowed();
        $this->delete('/buku/1')->assertMethodNotAllowed();
    });
});
