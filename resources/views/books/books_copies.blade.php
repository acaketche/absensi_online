@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Salinan Buku: {{ $book->title }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4" id="book-{{ $book->id }}">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $book->title }}</strong><br>
                <small>{{ $book->author }} | {{ $book->publisher }} | {{ $book->year_published }}</small>
            </div>
            <form action="{{ route('books.copies.store', $book->id) }}" method="POST" class="d-flex">
                @csrf
                <input type="number" name="quantity" class="form-control form-control-sm me-2" style="width: 100px;" placeholder="Jumlah" required min="1">
                <button type="submit" class="btn btn-sm btn-primary">Tambah Kode</button>
            </form>
        </div>

        <div class="card-body p-2">
            @if($book->copies->count() > 0)
                <div class="row row-cols-2 row-cols-md-5 g-2">
                    @foreach($book->copies as $copy)
                        <div class="col">
                            <div class="border rounded p-2 small text-center">{{ $copy->copy_code }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">Belum ada kode salinan.</p>
            @endif
        </div>
    </div>

    <a href="{{ route('books.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Buku</a>
</div>
@endsection
