# AGENTS.md: Laravel 13 "Manajemen Buku" (Pertemuan 7: Routing, Controller & Blade)

You are building a small Laravel 13 web app for the course *Pemrograman Berorientasi Objek (TI033015)*, Pertemuan 7. The lecturer's slides are the source of truth. Reproduce their examples as closely as possible: same names, same structure, same flow.

## 1. Core rules (read first)

1. **Follow the material.** Use the exact route URIs, route names, controller class/method names, view paths, variable names and directives shown below. Do not rename or "improve" them.
2. **Pure HTML only.**
   - No CSS: no `<style>`, no `style=""` attributes, no stylesheet links, no Tailwind, no Bootstrap, no Vite assets.
   - No JavaScript: no `<script>`, no `onclick`/`onsubmit`, no `confirm()` dialogs, no Alpine/Livewire.
   - Only semantic HTML: `h1`, `p`, `ul/li`, `a`, `form`, `label`, `input`, `textarea`, `button`, `table` if needed, `header/main/footer`. Browser default look is the intended result.
3. **No database.** Do not use migrations, Eloquent, models backed by tables, or SQLite/MySQL. The material says sample data lives in the Controller (Pertemuan 7), and Eloquent comes after the UTS.
4. **Responsibilities stay separate.** Route maps URL to action. Controller handles the request and prepares data. Blade only displays (`{{ }}`, `@if`, `@foreach`, `@forelse`, layout). No business logic in Blade, no heavy logic in `routes/web.php`.
5. **Escaped output only.** Use `{{ }}`. Never use `{!! !!}`.
6. **Allowed additions (single rule).** Anything not in the material may be added **only if it is necessary to complete the flow** (for example, making Create/Update/Delete actually work without a database). Every addition must be:
   - minimal,
   - plain PHP/Laravel with no styling or JS,
   - listed in the README under "Additions beyond the material", each with one line explaining which part of the flow it completes.
   If something is not needed to complete the flow, do not add it.

## 2. Project setup

- Use the existing Laravel 13 project from Pertemuan 6. If none exists, create a fresh Laravel 13 project and remove nothing from the default structure.
- Check syntax against the official docs: https://laravel.com/docs/13.x/routing, /controllers, /views, /blade.
- Create the folder `resources/views/buku/` and `resources/views/layouts/`.

## 3. Required routes (`routes/web.php`)

Import: `use App\Http\Controllers\BukuController;` and `use Illuminate\Support\Facades\Route;`

| Method | URI | Controller action | Route name | Purpose |
|---|---|---|---|---|
| GET | `/buku` | `index` | `buku.index` | list |
| GET | `/buku/create` | `create` | `buku.create` | add form |
| POST | `/buku` | `store` | `buku.store` | save new |
| GET | `/buku/{id}` | `show` | `buku.show` | detail |
| GET | `/buku/{id}/edit` | `edit` | `buku.edit` | edit form |
| PUT/PATCH | `/buku/{id}` | `update` | `buku.update` | save changes |
| DELETE | `/buku/{id}` | `destroy` | `buku.destroy` | delete |

Notes:
- Write them as explicit `Route::get(...)`, `Route::post(...)`, `Route::put(...)`, `Route::delete(...)` calls in the style `Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');`.
- Declare `/buku/create` **before** `/buku/{id}`, otherwise "create" is captured as an id.
- Also include the two simple closure routes from the slides: `GET /halo` returning `'Halo, mahasiswa PBO!'`, and `GET /tentang` returning `view('tentang')` (create `resources/views/tentang.blade.php`).
- Homework route with parameter, in the slide style: `GET /kategori/{nama?}` handled by the Controller (see section 7).
- Verify everything with `php artisan route:list --path=buku`.

## 4. Controller (`app/Http/Controllers/BukuController.php`)

Create with `php artisan make:controller BukuController`. Namespace `App\Http\Controllers`, class `BukuController extends Controller`.

- `index()`: prepare `$daftarBuku` (items shaped like `['id' => 1, 'judul' => 'Belajar PHP']`, plus `'penulis'` as shown in the slides) and return `view('buku.index', compact('daftarBuku'))`. Also pass `$judulHalaman = 'Daftar Buku'` as in the slides.
- `show(string $id)`: return `view('buku.show', ...)` with the book (at minimum the `id`, as in the slides, and the title and author).
- `create()`, `store(Request $request)`, `edit(string $id)`, `update(Request $request, string $id)`, `destroy(string $id)`.
- Sample seed data is exactly the slides' data: `['id'=>1,'judul'=>'Belajar PHP','penulis'=>'Andi']` and `['id'=>2,'judul'=>'Dasar Laravel','penulis'=>'Budi']`.
- `store`/`update`: validate with `$request->validate([...])` (judul and penulis required). After store/update/destroy, `redirect()->route('buku.index')->with('status', '...')`.
- Handle an unknown id with `abort(404)`.

## 5. Views (Blade, plain HTML)

Use dot notation (`buku.index` is `resources/views/buku/index.blade.php`).

- `layouts/app.blade.php`: follows the slide: `<html><body><header>Portal PBO</header><main>@yield('content')</main><footer>Universitas</footer></body></html>`. Add `<head><meta charset="UTF-8"><title>...</title></head>` only (needed for valid HTML). No styling.
- `buku/index.blade.php`: `@extends('layouts.app')` + `@section('content')`. Shows `<h1>{{ $judulHalaman }}</h1>`, success message with `@if (session('status'))`, a link to `route('buku.create')`, and the list with `@forelse`/`@foreach` as in the slides: each title is `<a href="{{ route('buku.show', $buku['id']) }}">{{ $buku['judul'] }}</a>` inside `<ul><li>`. Include `@if (count($daftarBuku) === 0)` / `@empty` handling ("Belum ada buku."). Next to each item put links/forms for edit and delete.
- `buku/show.blade.php`: `<h1>Detail Buku</h1>`, `<p>ID buku: {{ $id }}</p>`, title and author, `<a href="{{ route('buku.index') }}">Kembali ke daftar</a>`.
- `buku/create.blade.php`: form exactly like the slide: `<form method="POST" action="{{ route('buku.store') }}">`, `@csrf`, `<label>Judul Buku</label>`, `<input type="text" name="judul">`, plus an author field, `<button type="submit">Simpan</button>`. Show validation errors with `@if ($errors->any())` / `@foreach`.
- `buku/edit.blade.php`: form with `action="{{ route('buku.update', $id) }}"`, `@csrf`, `@method('PUT')`, prefilled inputs (`value="{{ old('judul', ...) }}"`), `<button type="submit">Perbarui</button>`.
- Delete: a form with `method="POST"`, `action="{{ route('buku.destroy', $buku['id']) }}"`, `@csrf`, `@method('DELETE')`, `<button type="submit">Hapus</button>`. No confirm dialog (that would need JavaScript).
- `tentang.blade.php`: a short plain page.

## 6. Additions the flow needs (allowed under rule 6)

The material has no persistence, yet Create/Update/Delete must actually work. Add only this:

1. **A tiny data layer in plain PHP** (for example `app/Repositories/BukuRepository.php`, an OOP class with methods like `all()`, `find($id)`, `create($data)`, `update($id, $data)`, `delete($id)`) that stores the book array **in the Laravel session**, seeded with the two sample books on first access. The Controller receives it via constructor injection. This keeps data out of Blade and respects "no database".
2. **`SESSION_DRIVER=file`** in `.env` (and `.env.example`), because Laravel's default session driver may be `database`, which would require a migration and violates the no-database rule.
3. **`@csrf`/`@method`, `$request->validate`, and a flash `status` message.** These are required by the slides' CRUD brief.

If you think something else is required, ask yourself: "Does the flow break without it?" If not, leave it out. Record every addition in the README.

## 7. Homework page (from "Tugas rumah individu")

- One additional page: `GET /kategori/{nama?}` to `BukuController@kategori` (or a small separate controller), with the optional-parameter behavior from the slides (`$nama ?? 'Semua kategori'`).
- Controller prepares the data (books grouped or filtered by a `kategori` field you add to the sample data, which is an allowed addition), view `buku/kategori.blade.php` uses `@if` and `@foreach`.

## 8. Testing checklist (run before finishing)

1. `php artisan route:list --path=buku` shows all 7 routes with the right names and actions.
2. `php artisan serve`, then manually verify:
   - `/halo` returns the text; `/tentang` renders.
   - `/buku` shows the list; clicking a title opens `/buku/{id}`; "Kembali ke daftar" returns.
   - `/buku/1` shows ID 1.
   - Create saves a new book and shows the status message; Edit changes it; Delete removes it.
   - `/kategori` and `/kategori/novel` both work.
3. Check there is **no** `<style>`, `<script>`, `style=`, `on*=` attribute, CDN link, or `@vite` anywhere in `resources/views`. Search with grep to confirm.
4. Check there is no `{!! !!}` anywhere.
5. Troubleshooting order if something fails (from the slides): 404 means check `route:list`; "Target class not found" means check the Controller import; "View not found" means check the folder and dot notation; "Undefined variable" means match the key names between Controller and Blade.

## 9. Deliverables

- Source code in the project/repo.
- `README.md` containing: how to run (`composer install`, `.env` setup, `php artisan serve`), a short note on the **Route -> Controller -> Blade** flow, the route table, and the **"Additions beyond the material"** section from rule 6.
- Do not add screenshots, those are taken manually from `/buku` and `/buku/{id}`.

## 10. Style of work

- Keep code simple and explainable. The student must be able to explain every line.
- Use Indonesian for UI text and variable names that appear in the material (`daftarBuku`, `judulHalaman`, `buku`); use English for code comments only if needed.
- Prefer small, readable methods. Do not introduce packages, build tools, or extra dependencies.