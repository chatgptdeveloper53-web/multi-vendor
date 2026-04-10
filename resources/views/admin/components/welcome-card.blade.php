<div class="card text-bg-primary">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-7">
        <div class="d-flex flex-column h-100">
          <div class="hstack gap-3">
            <span class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
              <iconify-icon icon="solar:course-up-outline" class="fs-7 text-muted"></iconify-icon>
            </span>
            <h5 class="text-white fs-6 mb-0 text-nowrap">Bienvenue
              <br>{{ auth()->user()->nom }}
            </h5>
          </div>
          <div class="mt-4 mt-sm-auto">
            <p class="text-white opacity-75">Panneau Administrateur</p>
          </div>
        </div>
      </div>
      <div class="col-sm-5 text-center text-md-end">
        <img src="/adminPanel/assets/images/backgrounds/welcome-bg.png" alt="welcome" class="img-fluid mb-n7 mt-2" width="180">
      </div>
    </div>
  </div>
</div>
