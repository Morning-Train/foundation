<?php

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

Route::get('/', [ 'uses' => function () {

    \morningtrain\Stub\Facades\Stub::create('migration', database_path('migrations/my_awesome_migration.php'), [
        'class'     => 'MyAwesomeMigration',
        'imports'   => [
            \App\User::class => 'MyUser',
            \Illuminate\Database\Migrations\Migration::class,
            \Illuminate\Database\Schema\Blueprint::class,
            \Illuminate\Support\Facades\Schema::class
        ],
        'extends'   => \Illuminate\Database\Migrations\Migration::class,
        'table' => 'awesome'
    ]);

    return view('welcome');
}]);


Route::group([ 'prefix' => 'dev' ], function() {
    Route::get('test', [ 'as' => 'dev.test', 'uses' => function() {
        dd(request()->route()->getName());
    }]);
});