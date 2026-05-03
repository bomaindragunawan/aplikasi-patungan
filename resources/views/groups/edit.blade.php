@extends('layout')

@section('content')
<div class="section-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1 class="hero-heading fw-bold mb-2">Edit Group Patungan</h1>
            <p class="text-muted mb-0">Perbarui nama, deskripsi, atau mata uang group.</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card bg-soft">
            <div class="card-body">
                <h2 class="h4 mb-3">Detail Group</h2>
                <form action="{{ route('groups.update', $group, false) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Group</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $group->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $group->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Uang</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', $group->currency) }}">
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('groups.show', $group, false) }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
