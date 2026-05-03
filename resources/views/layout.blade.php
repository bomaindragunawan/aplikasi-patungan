<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Patungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-qH3+m0NYwILhAQ9YfZyGPGVd5g+qtw6f30vNDDFG1lCwUvU2hLDmPrQ6ka5IoXlE" crossorigin="anonymous">
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.18), transparent 28%),
                        linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
        }
        .container {
            max-width: 1100px;
        }
        .navbar {
            background: rgba(15, 23, 42, 0.92);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(12px);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.08em;
        }
        .nav-link {
            color: rgba(241, 245, 249, 0.9) !important;
        }
        .nav-link:hover {
            color: #ffffff !important;
        }
        .card {
            border: none;
            border-radius: 1.4rem;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }
        .card.bg-soft {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
        }
        .card-body {
            padding: 1.75rem;
        }
        .section-header {
            padding: 1.75rem 1.75rem 1.5rem;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1.4rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        }
        .hero-heading {
            font-size: clamp(2rem, 2.8vw, 2.75rem);
            line-height: 1.05;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #2563eb);
            border: none;
            box-shadow: 0 18px 40px rgba(99, 102, 241, 0.2);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #1d4ed8);
        }
        .btn-success {
            background: linear-gradient(135deg, #22c55e, #14b8a6);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a, #0f766e);
        }
        .btn-outline-secondary {
            border-radius: 999px;
            border-color: rgba(148, 163, 184, 0.35);
        }
        .table {
            border-radius: 1rem;
            background: #ffffff;
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background: #f8fafc;
        }
        .table thead th {
            border-bottom: none;
            background: #f8fafc;
        }
        .table td,
        .table th {
            vertical-align: middle;
            border-color: #e5e7eb;
        }
        .form-control,
        .form-select {
            border-radius: 1rem;
            border: 1px solid #d1d5db;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.06);
            min-height: 48px;
        }
        .form-label {
            font-weight: 600;
        }
        .text-muted {
            color: #64748b !important;
        }
        .badge-soft {
            background: rgba(59, 130, 246, 0.12);
            color: #1d4ed8;
            font-weight: 600;
            border-radius: 0.75rem;
            padding: 0.45em 0.9em;
        }
        .border-accent {
            border-left: 0.45rem solid #6366f1;
        }
        .divider-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 0.9rem;
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
            font-weight: 700;
            margin-right: 0.9rem;
        }
    </style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('groups.index', [], false) }}">Aplikasi Patungan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('groups.index', [], false) }}">Groups</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('groups.create', [], false) }}">Buat Group</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-LoP5H0qXgfHnJcE+N9YybfKzS5OADH7f9Tl3TPhXdg6gnShjhDwYzG7BHXCkoU24" crossorigin="anonymous"></script>
</body>
</html>
