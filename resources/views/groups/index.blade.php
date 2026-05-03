@extends('layout')

@section('content')
<div class="section-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <span class="badge badge-soft mb-2">Dashboard</span>
            <h1 class="display-6 fw-bold">Manajemen Grup Patungan</h1>
            <p class="mb-0 text-muted">Kelola grup, anggota, dan pembagian biaya dengan tampilan modern dan bersih.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('groups.create', [], false) }}" class="btn btn-success btn-lg">Buat Grup Baru</a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card bg-soft">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-3">Total Grup</h6>
                <h2 class="fw-bold mb-1">{{ $groups->count() }}</h2>
                <p class="text-muted mb-0">Jumlah grup patungan yang tersimpan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-soft">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-3">Total Anggota</h6>
                <h2 class="fw-bold mb-1">{{ $groups->sum('members_count') }}</h2>
                <p class="text-muted mb-0">Total anggota aktif pada semua grup.</p>
            </div>
        </div>
    </div>
</div>

<div class="card bg-soft">
    <div class="card-body p-0 overflow-hidden">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Group</th>
                    <th>Deskripsi</th>
                    <th>Member</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $group)
                    <tr>
                        <td class="fw-semibold">{{ $group->name }}</td>
                        <td class="text-muted">{{ $group->description }}</td>
                        <td>{{ $group->members_count }}</td>
                        <td>
                            <a href="{{ route('groups.show', $group, false) }}" class="btn btn-sm btn-primary">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">Belum ada group. Buat group baru untuk memulai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
