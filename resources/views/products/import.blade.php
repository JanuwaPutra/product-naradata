@extends('layouts.app')

@section('title', 'Import Barang')

@section('header-buttons')
    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2">
            <h5 class="card-title mb-0 fw-semibold">
                <i class="fas fa-file-import me-1 text-primary"></i> Import Data Barang
            </h5>
        </div>
        <div class="card-body p-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i> Terjadi kesalahan pada import:
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border bg-light bg-opacity-50 mb-3">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-3">Petunjuk Import</h6>
                            
                            <ol class="ps-3 mb-0">
                                <li>Download template Excel yang telah disediakan.</li>
                                <li>Isi data sesuai dengan format yang ada pada template.</li>
                                <li>Pastikan kolom dengan tanda <span class="text-danger">*</span> wajib diisi.</li>
                                <li>Simpan file Excel yang telah diisi.</li>
                                <li>Upload file Excel tersebut melalui form di samping.</li>
                                <li>Klik tombol "Import Data" untuk memulai proses import.</li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('products.template') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body p-3">
                            <h6 class="fw-semibold mb-3">Upload File Excel</h6>
                            
                            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="file" class="form-label fw-medium small">File Excel <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx, .xls" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" style="font-size: 0.75rem;">Format file: .xlsx atau .xls</small>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i> Import Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 