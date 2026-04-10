@extends('admin.layouts.app')

@section('title', 'Gestion des instructeurs')

@section('content')

@include('admin.components.page-header', [
    'title'    => 'Gestion des instructeurs',
    'subtitle' => 'Gérez tous les instructeurs de la plateforme',
    'action'   => '<a href="javascript:void(0)" class="btn btn-primary">
                      <iconify-icon icon="solar:user-plus-line-duotone" class="me-1"></iconify-icon>
                      Ajouter un instructeur
                   </a>'
])

<div class="row">
  <div class="col-12">
    <div class="card shadow-none border">

      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">
          Liste des instructeurs
          <span class="badge bg-primary-subtle text-primary ms-2">{{ $instructeurs->count() }}</span>
        </h5>
        <input type="text" id="searchInput" class="form-control w-auto" placeholder="Rechercher...">
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="instructeursTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Instructeur</th>
              <th>Email</th>
              <th>Spécialité</th>
              <th>Cours créés</th>
              <th>Étudiants</th>
              <th>Inscrit le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($instructeurs as $index => $instructeur)
            <tr>
              <td class="text-muted">{{ $index + 1 }}</td>

              {{-- Avatar + Nom --}}
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                       style="width:38px; height:38px; flex-shrink:0;">
                    <span class="fw-bold text-primary" style="font-size:14px;">
                      {{ strtoupper(substr($instructeur->nom, 0, 1)) }}
                    </span>
                  </div>
                  <div>
                    <p class="mb-0 fw-semibold">{{ $instructeur->nom }}</p>
                    @if($instructeur->enseignant?->bio)
                      <small class="text-muted">{{ Str::limit($instructeur->enseignant->bio, 40) }}</small>
                    @endif
                  </div>
                </div>
              </td>

              <td class="text-muted">{{ $instructeur->email }}</td>

              <td>
                @if($instructeur->enseignant?->specialite)
                  <span class="badge bg-info-subtle text-info">{{ $instructeur->enseignant->specialite }}</span>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              {{-- Nombre de cours --}}
              <td>
                <span class="fw-semibold">{{ $instructeur->enseignant?->cours->count() ?? 0 }}</span>
                <small class="text-muted">cours</small>
              </td>

              {{-- Total étudiants inscrits dans ses cours --}}
              @php
                $totalEtudiants = $instructeur->enseignant?->cours->sum(fn($c) => $c->etudiants->count()) ?? 0;
              @endphp
              <td>
                <span class="fw-semibold">{{ $totalEtudiants }}</span>
                <small class="text-muted">étudiants</small>
              </td>

              <td class="text-muted">
                {{ $instructeur->date_inscription
                    ? $instructeur->date_inscription->format('d M Y')
                    : $instructeur->created_at->format('d M Y') }}
              </td>

              <td>
                <div class="d-flex gap-1">
                  <a href="javascript:void(0)" class="btn btn-sm btn-info"
                     data-bs-toggle="tooltip" title="Voir">
                    <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                  </a>
                  <a href="javascript:void(0)" class="btn btn-sm btn-warning"
                     data-bs-toggle="tooltip" title="Modifier">
                    <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                  </a>
                  <a href="javascript:void(0)" class="btn btn-sm btn-danger"
                     data-bs-toggle="tooltip" title="Supprimer">
                    <iconify-icon icon="solar:trash-bin-line-duotone"></iconify-icon>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <iconify-icon icon="solar:users-group-rounded-line-duotone"
                              style="font-size:48px; color:#ccc;"></iconify-icon>
                <p class="text-muted mt-2">Aucun instructeur trouvé</p>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary">
                  Ajouter le premier instructeur
                </a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Live search
  document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    document.querySelectorAll('#instructeursTable tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  });
</script>
@endpush
