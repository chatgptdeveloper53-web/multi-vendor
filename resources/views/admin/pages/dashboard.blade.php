@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="row">
  <!-- Welcome Card -->
  <div class="col-lg-5">
    @include('admin.components.welcome-card')

    <!-- Stats Cards -->
    <div class="row mt-3">
      <div class="col-md-6">
        @include('admin.components.stat-card', [
          'title' => 'Utilisateurs totaux',
          'value' => \App\Models\User::count(),
          'label' => 'Actif',
          'bgColor' => 'bg-secondary-subtle'
        ])
      </div>
      <div class="col-md-6">
        @include('admin.components.stat-card', [
          'title' => 'Administrateurs',
          'value' => \App\Models\User::where('role', App\Enums\Role::Administrateur)->count(),
          'label' => 'Système',
          'bgColor' => 'bg-danger-subtle'
        ])
      </div>
    </div>
  </div>

  <!-- Right Column -->
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body pb-4">
        <div class="d-md-flex align-items-center justify-content-between mb-4">
          <div class="hstack align-items-center gap-3">
            <span class="d-flex align-items-center justify-content-center round-48 bg-primary-subtle rounded flex-shrink-0">
              <iconify-icon icon="solar:layers-linear" class="fs-7 text-primary"></iconify-icon>
            </span>
            <div>
              <h5 class="card-title">Statistiques du système</h5>
              <p class="card-subtitle mb-0">Aperçu de la plateforme</p>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td>
                  <h6 class="mb-0">Utilisateurs totaux</h6>
                </td>
                <td>
                  <h6 class="mb-0">{{ \App\Models\User::count() }}</h6>
                </td>
              </tr>
              <tr>
                <td>
                  <h6 class="mb-0">Administrateurs</h6>
                </td>
                <td>
                  <h6 class="mb-0">{{ \App\Models\User::where('role', App\Enums\Role::Administrateur)->count() }}</h6>
                </td>
              </tr>
              <tr>
                <td>
                  <h6 class="mb-0">Instructeurs</h6>
                </td>
                <td>
                  <h6 class="mb-0">{{ \App\Models\User::where('role', App\Enums\Role::Enseignant)->count() }}</h6>
                </td>
              </tr>
              <tr>
                <td>
                  <h6 class="mb-0">Étudiants</h6>
                </td>
                <td>
                  <h6 class="mb-0">{{ \App\Models\User::where('role', App\Enums\Role::Etudiant)->count() }}</h6>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
