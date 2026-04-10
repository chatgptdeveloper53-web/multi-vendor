@extends('admin.layouts.app')

@section('title', 'Gestion des étudiants')

@section('content')

@include('admin.components.page-header', [
    'title'    => 'Gestion des étudiants',
    'subtitle' => 'Suivi et gestion des étudiants inscrits sur la plateforme',
    'action'   => '<a href="javascript:void(0)" class="btn btn-primary">
                      <iconify-icon icon="solar:user-plus-line-duotone" class="me-1"></iconify-icon>
                      Ajouter un étudiant
                   </a>'
])

<div class="row">
  <div class="col-12">
    <div class="card shadow-none border">

      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">
          Liste des étudiants
          <span class="badge bg-primary-subtle text-primary ms-2">{{ $etudiants->count() }}</span>
        </h5>
        <input type="text" id="searchInput" class="form-control w-auto" placeholder="Rechercher...">
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="etudiantsTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Étudiant</th>
              <th>Email</th>
              <th>Cours inscrits</th>
              <th>Progression globale</th>
              <th>Date d'inscription</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($etudiants as $index => $etudiant)
            <tr>
              <td class="text-muted">{{ $index + 1 }}</td>

              {{-- Avatar + Nom --}}
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center"
                       style="width:38px; height:38px; flex-shrink:0;">
                    <span class="fw-bold text-success" style="font-size:14px;">
                      {{ $etudiant->nom ? strtoupper(substr($etudiant->nom, 0, 1)) : '?' }}
                    </span>
                  </div>
                  <p class="mb-0 fw-semibold">{{ $etudiant->nom ?? 'Nom non défini' }}</p>
                </div>
              </td>

              <td class="text-muted">{{ $etudiant->email }}</td>

              {{-- Cours inscrits --}}
              <td>
                <span class="fw-semibold">{{ $etudiant->etudiant?->cours->count() ?? 0 }}</span>
                <small class="text-muted">cours</small>
              </td>

              {{-- Progression globale --}}
              <td style="min-width: 140px;">
                @php $prog = round($etudiant->etudiant?->progression_globale ?? 0); @endphp
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height:6px;">
                    <div class="progress-bar
                      {{ $prog >= 75 ? 'bg-success' : ($prog >= 40 ? 'bg-warning' : 'bg-danger') }}"
                         role="progressbar" style="width: {{ $prog }}%">
                    </div>
                  </div>
                  <small class="text-muted fw-semibold">{{ $prog }}%</small>
                </div>
              </td>

              <td class="text-muted">
                {{ $etudiant->date_inscription
                    ? $etudiant->date_inscription->format('d M Y')
                    : $etudiant->created_at->format('d M Y') }}
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
              <td colspan="7" class="text-center py-5">
                <iconify-icon icon="solar:users-group-rounded-line-duotone"
                              style="font-size:48px; color:#ccc;"></iconify-icon>
                <p class="text-muted mt-2">Aucun étudiant trouvé</p>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary">
                  Ajouter le premier étudiant
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
    document.querySelectorAll('#etudiantsTable tbody tr').forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
    });
  });
</script>
@endpush
