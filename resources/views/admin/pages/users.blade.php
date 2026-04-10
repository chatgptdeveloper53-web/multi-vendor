@extends('admin.layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Gestion des utilisateurs',
  'subtitle' => 'Gérez tous les utilisateurs du système',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Ajouter un nouvel utilisateur</a>'
])

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5>Liste des utilisateurs</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Date d'inscription</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>{{ $user->nom }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="badge {{ $user->role === App\Enums\Role::Administrateur ? 'bg-danger' : ($user->role === App\Enums\Role::Enseignant ? 'bg-warning' : 'bg-info') }}">
                  {{ ucfirst($user->role->value) }}
                </span>
              </td>
              <td>{{ $user->date_inscription->format('M d, Y') }}</td>
              <td>
                <a href="javascript:void(0)" class="btn btn-sm btn-info">
                  <iconify-icon icon="solar:eye-line-duotone"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-warning">
                  <iconify-icon icon="solar:pen-line-duotone"></iconify-icon>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-danger">
                  <iconify-icon icon="solar:trash-bin-line-duotone"></iconify-icon>
                </a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4">
                <p class="text-muted">Aucun utilisateur trouvé</p>
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
