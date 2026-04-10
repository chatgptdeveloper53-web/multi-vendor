@extends('admin.layouts.app')

@section('title', 'Gestion du contenu')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Gestion du contenu',
  'subtitle' => 'Organisez et gérez sections et contenus des cours',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Ajouter du contenu</a>'
])

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5>Contenu des cours</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Titre</th>
              <th>Cours</th>
              <th>Type</th>
              <th>Durée</th>
              <th>Vues</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="7">
                <p class="text-muted">Aucun contenu trouvé</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
