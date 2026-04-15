<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AcheteurController;
use App\Http\Controllers\Admin\VendeurController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Vendeur\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FRONT OFFICE — Public routes (Broccoli template)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/boutique',       fn() => view('front.home'))->name('shop.index');
Route::get('/boutique/{id}',  fn($id) => abort(404))->name('shop.show');
Route::get('/panier',         fn() => abort(404))->name('cart.index');
Route::get('/commander',      fn() => abort(404))->name('checkout.index');
Route::get('/a-propos',       fn() => abort(404))->name('about');
Route::get('/contact',        fn() => abort(404))->name('contact');

/*
|--------------------------------------------------------------------------
| FRONT OFFICE — Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| VENDEUR — Onboarding wizard + pages statuses
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('vendeur')
    ->name('vendeur.')
    ->group(function () {

        /*── Onboarding wizard ────────────────────────────────────*/
        Route::prefix('onboarding')->name('onboarding.')->group(function () {
            Route::get('/{etape}',  [OnboardingController::class, 'show'])->name('etape');
            Route::post('/1',       [OnboardingController::class, 'saveEtape1'])->name('save1');
            Route::post('/2',       [OnboardingController::class, 'saveEtape2'])->name('save2');
            Route::post('/3',       [OnboardingController::class, 'saveEtape3'])->name('save3');
            Route::post('/4',       [OnboardingController::class, 'saveEtape4'])->name('save4');
            Route::post('/5',       [OnboardingController::class, 'saveEtape5'])->name('save5');
            Route::delete('/document/{id}', [OnboardingController::class, 'deleteDocument'])->name('document.delete');
        });

        /*── API VIES (AJAX) ──────────────────────────────────────*/
        Route::get('/vies-check', [OnboardingController::class, 'checkVies'])->name('vies.check');

        /*── Pages statut ─────────────────────────────────────────*/
        Route::get('/pending',  [OnboardingController::class, 'pending'])->name('pending');
        Route::get('/rejected', [OnboardingController::class, 'rejected'])->name('rejected');
    });

/*
|--------------------------------------------------------------------------
| ADMIN PANEL — Protected routes  [auth + admin middleware]
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*── Dashboard ───────────────────────────────────────────*/
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*── Utilisateurs ────────────────────────────────────────*/
        Route::prefix('utilisateurs')->name('users.')->group(function () {
            Route::get('/',          fn() => abort(404))->name('index');
            Route::get('/{id}',      fn($id) => abort(404))->name('show');
            Route::delete('/{id}',   fn($id) => abort(404))->name('destroy');
        });

        Route::prefix('acheteurs')->name('acheteurs.')->group(function () {
            Route::get('/',           [AcheteurController::class, 'index'])->name('index');
            Route::get('/{id}',       [AcheteurController::class, 'show'])->name('show');
            Route::get('/{id}/edit',  [AcheteurController::class, 'edit'])->name('edit');
            Route::put('/{id}',       [AcheteurController::class, 'update'])->name('update');
            Route::delete('/{id}',    [AcheteurController::class, 'destroy'])->name('destroy');
        });

        /*── Vendeurs & Onboarding ───────────────────────────────*/
        Route::prefix('vendeurs')->name('vendeurs.')->group(function () {
            Route::get('/',                [VendeurController::class, 'index'])->name('index');
            Route::get('/pending',         fn() => redirect()->route('admin.vendeurs.index', ['statut' => 'EN_ATTENTE']))->name('pending');
            Route::get('/{id}',            [VendeurController::class, 'show'])->name('show');
            Route::get('/{id}/edit',       [VendeurController::class, 'edit'])->name('edit');
            Route::put('/{id}',            [VendeurController::class, 'update'])->name('update');
            Route::post('/{id}/valider',   [VendeurController::class, 'valider'])->name('valider');
            Route::post('/{id}/rejeter',   [VendeurController::class, 'rejeter'])->name('rejeter');
            Route::delete('/{id}',         [VendeurController::class, 'destroy'])->name('destroy');
        });

        /*── Documents ───────────────────────────────────────────*/
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/',          fn() => abort(404))->name('index');
            Route::get('/{id}',      fn($id) => abort(404))->name('show');
            Route::post('/{id}/valider', fn($id) => abort(404))->name('valider');
        });

        /*── Produits ────────────────────────────────────────────*/
        Route::prefix('produits')->name('produits.')->group(function () {
            Route::get('/',            [ProduitController::class, 'index'])->name('index');
            Route::get('/create',      [ProduitController::class, 'create'])->name('create');
            Route::post('/',           [ProduitController::class, 'store'])->name('store');
            Route::get('/{id}',        [ProduitController::class, 'show'])->name('show');
            Route::get('/{id}/edit',   [ProduitController::class, 'edit'])->name('edit');
            Route::put('/{id}',        [ProduitController::class, 'update'])->name('update');
            Route::post('/{id}/toggle',[ProduitController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}',     [ProduitController::class, 'destroy'])->name('destroy');
        });

        /*── Catalogues ──────────────────────────────────────────*/
        Route::prefix('catalogues')->name('catalogues.')->group(function () {
            Route::get('/',            [CatalogueController::class, 'index'])->name('index');
            Route::get('/{id}',        [CatalogueController::class, 'show'])->name('show');
            Route::post('/{id}/toggle',[CatalogueController::class, 'toggle'])->name('toggle');
        });

        /*── Photos ──────────────────────────────────────────────*/
        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/',          fn() => redirect()->route('admin.produits.index'))->name('index');
            Route::delete('/{id}',   [ProduitController::class, 'destroyPhoto'])->name('destroy');
        });

        /*── Commandes ───────────────────────────────────────────*/
        Route::prefix('commandes')->name('commandes.')->group(function () {
            Route::get('/',          fn() => abort(404))->name('index');
            Route::get('/en-cours',  fn() => abort(404))->name('en-cours');
            Route::get('/{id}',      fn($id) => abort(404))->name('show');
            Route::post('/{id}/statut', fn($id) => abort(404))->name('statut');
        });

        /*── Logistique ──────────────────────────────────────────*/
        Route::prefix('logistiques')->name('logistiques.')->group(function () {
            Route::get('/',          fn() => abort(404))->name('index');
            Route::get('/{id}',      fn($id) => abort(404))->name('show');
        });

        /*── Notifications ───────────────────────────────────────*/
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/',          fn() => abort(404))->name('index');
            Route::delete('/{id}',   fn($id) => abort(404))->name('destroy');
        });

        /*── Paramètres ──────────────────────────────────────────*/
        Route::get('/parametres', fn() => abort(404))->name('settings');
    });

/*
|--------------------------------------------------------------------------
| AUTH — Breeze generated routes (login, register, reset…)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
