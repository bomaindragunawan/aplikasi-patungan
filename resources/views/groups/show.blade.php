@extends('layout')

@section('content')
<div class="section-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <span class="badge badge-soft mb-2">Detail Grup</span>
            <h1 class="hero-heading fw-bold mb-2">{{ $group->name }}</h1>
            <p class="text-muted mb-0">{{ $group->description }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('groups.index', [], false) }}" class="btn btn-outline-secondary btn-sm me-2">Kembali</a>
            <a href="{{ route('groups.edit', $group, false) }}" class="btn btn-warning btn-sm me-2">Edit</a>
            <form action="{{ route('groups.destroy', $group, false) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus group ini? Semua data akan terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-soft border-accent">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="divider-icon">T</div>
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Total Transaksi</h6>
                        <p class="fs-4 fw-semibold mb-0">{{ number_format($group->transactions->sum('amount'), 0, ',', '.') }} {{ $group->currency }}</p>
                    </div>
                </div>
                <p class="text-muted mb-0">Jumlah semua transaksi yang tercatat.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-soft border-accent">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="divider-icon">M</div>
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Anggota</h6>
                        <p class="fs-4 fw-semibold mb-0">{{ $group->members->count() }}</p>
                    </div>
                </div>
                <p class="text-muted mb-0">Total anggota yang ikut patungan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-soft border-accent">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="divider-icon">B</div>
                    <div>
                        <h6 class="text-uppercase text-muted mb-1">Balance Group</h6>
                        <p class="fs-4 fw-semibold mb-0">{{ number_format($balances->sum(fn($item) => $item['balance']), 0, ',', '.') }} {{ $group->currency }}</p>
                    </div>
                </div>
                <p class="text-muted mb-0">Ringkasan saldo total anggota.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card bg-soft">
            <div class="card-body">
                <h4 class="fw-semibold mb-3">Saldo Anggota</h4>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Paid</th>
                                <th>Spent</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balances as $item)
                                <tr>
                                    <td>{{ $item['member']->name }}</td>
                                    <td>{{ number_format($item['paid'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($item['spent'], 0, ',', '.') }}</td>
                                    <td class="text-{{ $item['balance'] >= 0 ? 'success' : 'danger' }} fw-semibold">
                                        {{ number_format($item['balance'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card bg-soft">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Ringkasan Grup</h5>
                <p class="mb-2"><strong>Mata Uang:</strong> {{ $group->currency }}</p>
                <p class="mb-2"><strong>Total Anggota:</strong> {{ $group->members->count() }}</p>
                <p class="mb-0"><strong>Total Transaksi:</strong> {{ number_format($group->transactions->count(), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="h6">Tambah Anggota</h5>
                <form action="{{ route('groups.members.store', $group, false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="h6">Tambah Transaksi</h5>
                <form action="{{ route('groups.transactions.store', $group, false) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Payer</label>
                        <select name="payer_id" class="form-select" required>
                            @foreach($group->members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="description" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control">
                    </div>
                    <small class="text-muted">Biaya akan terbagi rata ke semua anggota.</small>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="h6">Daftar Anggota</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->members as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        <form action="{{ route('members.destroy', $member, false) }}" method="POST" onsubmit="return confirm('Hapus anggota ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="h6">Daftar Transaksi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Payer</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->payer->name }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td>{{ $transaction->category }}</td>
                                    <td>
                                        <form action="{{ route('transactions.destroy', $transaction, false) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="h6">Daftar Settlements</h5>
                <form action="{{ route('groups.settlements.store', $group, false) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Dari</label>
                            <select name="from_member_id" class="form-select" required>
                                @foreach($group->members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ke</label>
                            <select name="to_member_id" class="form-select" required>
                                @foreach($group->members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-3">Catat Settlement</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Dari</th>
                                <th>Ke</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group->settlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->fromMember->name }}</td>
                                    <td>{{ $settlement->toMember->name }}</td>
                                    <td>{{ number_format($settlement->amount, 0, ',', '.') }}</td>
                                    <td>{{ ucfirst($settlement->status) }}</td>
                                    <td>
                                        <form action="{{ route('settlements.destroy', $settlement, false) }}" method="POST" onsubmit="return confirm('Hapus settlement ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
