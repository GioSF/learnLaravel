<?php
use App\Http\Controllers\OpaController;

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

Route::get('tasks/create', 'TasksController@create');

#Passando parâmetro pela uri
Route::get('tasks/{id}/route', function($id){
    return $id;
});

#Routes, regex e closures

Route::get('/regex/{id}', function($id){
    return $id;
})->where('id', '[0-9]+');

Route::get('regex/{id}', function($id){
    return $id . ' maiúsculas';
})->where('id', '[A-Z]+');

#Route, regex e vários parâmetros para controller
Route::get('regex/{id}/{asd}','TasksController@route')->where(['id' => '[a-z]+', 'asd' => '[0-9]+']);

#Naming routes
Route::get('name/{id}/{asd}',[
    'as' => 'route.name',
    'uses' => 'TasksController@name'
]);

#Naming routes e usando closures
Route::get('regex/{id}/{asd}/ei', [
    'as' => 'regex.edit',
    function ($id, $asd){
        return $id . ' regex.edit '. $asd;
    }
]);

#Restricting a group of routes to logged-in users only

Route::middleware('auth')->group(function(){
    Route::get('index', function(){
        return 'está autorizado';
    });
    Route::get('indice', function(){
        return 'também está autorizado';
    });
});

Route::get('id/{id?}', 'OpaController@index');

Route::match(['GET', 'POST'], '/opa', 'OpaController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

#Fallback Routes
#Usar fallback(): you could define a “fallback route” (which you need to define at the end of your routes file) to catch all unmatched paths:
Route::fallback(function(){
    return "vai rolar não";
});

#Prefixing a route with route groups

Route::group(['prefix' => 'api'], function(){
    Route::get('/',function(){
        return 'chegou em /api';
    });
    Route::get('users', function(){
        return 'chegou em /api/users';
    });
});

#VIEWS
#Dois formatos out of the box: Blade (renderizado com Blade engine) e PHP (renderizado com php).
# view() == View::make()
#Após importar uma view é possível retorná-la. Isso funciona bem se a view não se apoia em variáveis do controller

# busca uma view em resources/views/welcome.blade ou resources/views/welcome.php, lê seu conteúdo, parse qualquer estrutura de controle ou php até que se tenha o output view:

Route::get('/', function () {
    return view('welcome');
});

Route::get('sample', 'MySampleController@home');

#Passando variavel 'tasks' para uma view():

Route::get('/variavel', function(){
    return view('id')->with('tasks', Task::all());
});
#PASSANDO VARIAVEIS PARA UM VIEW ATRAVES DE UM ARRAY:
Route::get('/variavelb', function(){
    return view('id', ['tasks' => Task::all()]);
});

# Share variables with templates:
# share(): adda a piece of shared data to the environment (key, value)
view()->share('variableName', 'variabelValue');


#Signing a route
# Duas formas:
    #1) Separando Route::get e URL::route:
    #In order to build a signed URL to access a given route, the route must have a name:

    #Gerar um link normal:
    Route::get('convites/{convite}/{resposta}', function(){

        return URL::route('convites', ['convite' => 123123, 'resposta' => 'sim']);

    })->name('convites');

    #Gerar um signed link:

    Route::get('signed', function(){

        return URL::signedRoute('signed', ['convite' => 123123, 'senha' => 234234]);

    })->name('signed');

    #Gerar um link expirável
    #now() create a new Carbon instance for the current time:

    Route::get('expiravel', function(){
        
        return URL::temporarySignedRoute('exp', now()->addMinute());
        
    })->name('exp');

Route::get('redirect', 'TasksController@redirect');

Route::get('signingroute', function(){
    return URL::signedRoute('invitations', ['invitation' => 12345, 'answer' => 'yes']);
})->name('invitations');

#Modifying Routes to Allow Signed Links
# Para proteger uma rota de qualquer acesso não autorizado

#gerar signed link

Route::get('gerarSignedLink', function(){
    return URL::temporarySignedRoute('protegido', now()->addMinute());
});

#O signed link acima gerado será uma link válido para a rota abaixo definida
Route::get('paginaProtegida','TasksController@protegido')->name('protegido')->middleware('signed');

//Controllers - Getting user input
Route::get('tasks/create', 'TasksController@create');
Route::post('tasks', 'TasksController@store');


#URL::temporarySignedRoute('protegido', now()->addMinute());