<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{



    public function index()
    {

        return 'Opa';
    }

    public function create()
    {
        return 'Criou';
    }

    public function route($id, $asd)
    {

        return $id . ' ' . $asd;
    }

    public function name($id, $asd){

        return $id . ' name ' . $asd;
        
    }

    public function guardar()
    {

        Task::create(request()->only(['title', 'description']));
        return redirect('tasks');
    }

    public function protegido(){
        return 'rota protegida';
    }

    public function redirect(){
        return redirect($status = 404);
    }


    //Controllers - Getting user input
    public function store(){
        //request() helper to represent the HTTP request
        //only() method to pull just the title and description fields the user submitted
        //request()->only() takes an associative array of input names and returns them
        //And Task::create() takes an associative array and creates a new task from it. Combining them together creates a task with just the user-provided “title” and “description” fields.
        //We’re then passing that data into the create() method of our Task model, which creates a new instance of the Task with title set to the passed-in title and description set to the passed-in description. Finally, we redirect back to the page that shows all tasks.
        Task::create(request()->only(['title', 'description']));
        return redirect('tasks');
    }

    public function storeAlt(\Illuminate\Http\Request $request)
    {
        Task::create($request->only(['title', 'description']));
        return redirect('tasks');
    }

}
