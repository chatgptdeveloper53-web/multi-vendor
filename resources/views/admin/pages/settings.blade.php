@extends('admin.layouts.app')

@section('title', 'Paramètres')

@section('content')
@include('admin.components.page-header', [
  'title' => 'Paramètres du système',
  'subtitle' => 'Configurez les paramètres de la plateforme'
])

<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5>Paramètres généraux</h5>
      </div>
      <div class="card-body">
        <form>
          @csrf
          <div class="mb-3">
            <label for="siteName" class="form-label">Nom du site</label>
            <input type="text" class="form-control" id="siteName" placeholder="Enter site name" value="EduRéussite">
          </div>

          <div class="mb-3">
            <label for="siteEmail" class="form-label">Email du site</label>
            <input type="email" class="form-control" id="siteEmail" placeholder="Enter site email">
          </div>

          <div class="mb-3">
            <label for="siteUrl" class="form-label">URL du site</label>
            <input type="url" class="form-control" id="siteUrl" placeholder="Enter site URL">
          </div>

          <div class="mb-3">
            <label for="timezone" class="form-label">Fuseau horaire</label>
            <select class="form-select" id="timezone">
              <option selected>Africa/Casablanca</option>
              <option>Europe/Paris</option>
              <option>America/New_York</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5>Actions rapides</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <button class="btn btn-outline-primary" type="button">
            <iconify-icon icon="solar:refresh-line-duotone" class="me-2"></iconify-icon>
            Effacer le cache
          </button>
          <button class="btn btn-outline-warning" type="button">
            <iconify-icon icon="solar:database-line-duotone" class="me-2"></iconify-icon>
            Sauvegarde de la base de données
          </button>
          <button class="btn btn-outline-danger" type="button">
            <iconify-icon icon="solar:trash-bin-line-duotone" class="me-2"></iconify-icon>
            Effacer les journaux
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
