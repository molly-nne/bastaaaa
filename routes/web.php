
<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
 
//Route::get('/', function () {
//    return view('welcome');
//});
 
Route::get('/', [ServiceController::class, 'index']);
Route::post('/store', [ServiceController::class, 'store'])->name('store');
Route::get('/fetchall', [ServiceController::class, 'fetchAll'])->name('fetchAll');
Route::delete('/delete', [ServiceController::class, 'delete'])->name('delete');
Route::get('/edit', [ServiceController::class, 'edit'])->name('edit');
Route::post('/update', [ServiceController::class, 'update'])->name('update');

Route::get('/export', [ServiceController::class, 'export'])->name('export');