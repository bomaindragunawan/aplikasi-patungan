@extends('layout')

@section('content')
<div class="section-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="hero-heading fw-bold mb-2">Buat Group Patungan Baru</h1>
            <p class="text-muted mb-0">Isikan detail group untuk memulai patungan baru dengan cepat.</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card bg-soft">
            <div class="card-body">
                <h2 class="h4 mb-3">Detail Group</h2>
                <form action="{{ route('groups.store', [], false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Group</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Uang</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', 'IDR') }}">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('groups.index', [], false) }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
