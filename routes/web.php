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

use morningtrain\Crud\Facades\Crud;
use App\Models\Post;

use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'test'], function () {

    Route::get('morph', function () {
        dd(\App\Models\Role::first()->permissions, Role::first()->permissions);
    });

    Route::get('add-post', function () {
        $post = new Post();
        $post->title = 'Some title';
        $post->content = 'Some content';
        $post->save();
    });

    Route::get('login', function () {
        Auth::login(User::first());
    });

    Route::get('gate', function () {
        dd(Gate::allows('test.company'));
    });

    Route::get('permission', function () {
        $user = User::first();

        //dd($user->name, $user->roles()->first()->permissions->pluck('slug'));

        dd($user->allowed('test.Something'));
    });

    Route::get('add-company', function () {
        $company = new Company();
        $company->name = 'My company';
        $company->save();

        $role = Role::where('slug', 'company-admin')->first();

        $company->roles()->attach($role->id);

        $user = User::first();
        $user->companies()->attach($company->id);

    });

});

Route::group(['theme' => 'Base'], function () {


});