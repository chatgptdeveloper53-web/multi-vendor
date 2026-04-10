@extends('admin.layouts.app')

@section('title', 'Modifier — ' . $acheteur->nom)

@section('content')

    {{-- ── Breadcrumb ── --}}
    @include('admin.components.breadcrumb', [
        'items' => [
            'Acheteurs'        => route('admin.acheteurs.index'),
            $acheteur->nom     => route('admin.acheteurs.show', $acheteur->id),
            'Modifier'         => '#',
        ]
    ])

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card border-0 shadow-none">
                <div class="card-body p-4">

                    {{-- Card header --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="round-48 rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:52px;height:52px;font-size:1.4rem;font-weight:700;color:#fff;">
                            {{ strtoupper(substr($acheteur->nom, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Modifier le compte</h5>
                            <p class="mb-0 text-muted fs-2">ID #{{ $acheteur->id }} · Acheteur</p>
                        </div>
                    </div>

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit form --}}
                    <form method="POST" action="{{ route('admin.acheteurs.update', $acheteur->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label for="nom" class="form-label fw-semibold">
                                Nom complet <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <iconify-icon icon="solar:user-line-duotone"></iconify-icon>
                                </span>
                                <input type="text"
                                       id="nom"
                                       name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', $acheteur->nom) }}"
                                       required
                                       autofocus>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                Adresse email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent">
                                    <iconify-icon icon="solar:letter-line-duotone"></iconify-icon>
                                </span>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $acheteur->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Read-only info --}}
                        <div class="rounded-3 bg-light p-3 mb-4">
                            <p class="mb-1 fw-semibold fs-2 text-muted text-uppercase">Informations non modifiables</p>
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <span class="text-muted fs-2">Rôle</span>
                                    <p class="mb-0 fw-semibold">
                                        <span class="badge bg-primary-subtle text-primary">Acheteur</span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fs-2">Inscrit le</span>
                                    <p class="mb-0 fw-semibold">{{ $acheteur->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fs-2">Email vérifié</span>
                                    <p class="mb-0">
                                        @if($acheteur->email_verified_at)
                                            <span class="badge bg-success-subtle text-success">Oui</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Non</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fs-2">Commandes</span>
                                    <p class="mb-0 fw-semibold">{{ $acheteur->commandes()->count() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <iconify-icon icon="solar:check-circle-line-duotone" class="me-1"></iconify-icon>
                                Enregistrer les modifications
                            </button>
                            <a href="{{ route('admin.acheteurs.show', $acheteur->id) }}"
                               class="btn btn-outline-secondary px-4">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
