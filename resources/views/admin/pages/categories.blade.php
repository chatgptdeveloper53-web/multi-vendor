@extends('admin.layouts.app')

@section('title', 'Gestion des catégories')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Gestion des catégories',
  'subtitle' => 'Organisez vos cours par catégories hiérarchiques',
  'action' => '<a href="javascript:void(0)" class="btn btn-primary">Ajouter une catégorie</a>'
])

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5>Catégories disponibles</h5>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Nom de la catégorie</th>
              <th>Description</th>
              <th>Parent</th>
              <th>Nombres de cours</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5">
                <p class="text-muted">Aucune catégorie trouvée</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
