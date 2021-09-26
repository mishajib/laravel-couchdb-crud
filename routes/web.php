<?php

use App\Http\Controllers\CouchDbController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('couch-db', [CouchDbController::class, 'index'])->name('couch-db.index');
Route::get('couch-db/get-uuids', [CouchDbController::class, 'getUUIDs'])->name('couch-db.get-uuids');
Route::get('couch-db/create-document', [CouchDbController::class, 'createDocument'])->name('couch-db.create-document');
Route::get('couch-db/get-document', [CouchDbController::class, 'getDocument'])->name('couch-db.get-document');
Route::get('couch-db/get-all-documents', [CouchDbController::class, 'getAllDocuments'])->name('couch-db.get-all-documents');
Route::get('couch-db/update-document', [CouchDbController::class, 'updateDocument'])->name('couch-db.update-document');
Route::get('couch-db/attach-file', [CouchDbController::class, 'attachFile'])->name('couch-db.attach-file');
Route::get('couch-db/delete-document', [CouchDbController::class, 'deleteDocument'])->name('couch-db.delete-document');
Route::get('couch-db/find-document', [CouchDbController::class, 'findDocument'])->name('couch-db.find-document');

Route::get('couch-db/test', [CouchDbController::class, 'test'])->name('couch-db.test');
