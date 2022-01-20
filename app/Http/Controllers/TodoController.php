<?php

namespace App\Http\Controllers;

use App\Notifications\TodoAffected;
use App\Todo;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

    //store all users

    public $users;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**s
    * Assign a todo to an user
     *
     *  @param App\Todo $todo
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function affectedTo(Todo $todo, User $user)
    {
        $todo->affectedTo_id = $user->id;
        $todo->affectedBy_id = Auth::user()->id;
        $todo->update();

        $user -> notify(new todoAffected($todo));

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $datas = Todo::where(['affectedTo_id' => $userId])-> orderBy('id', 'desc')->paginate(8);
        /*$datas  = Todo::all() ->reject(function ($todo){
            return $todo -> done == 0;
        });*/
         $users = User::where('id','!=',Auth::user()->id)->get();
         //sdd($users);
        return view('todos.index', compact('datas','users'));

    }

    /**
     * Fonction pour lister les todos terminés
     * */
    Public function done()
    {
        $datas = Todo::where('done',1) ->orderBy('id','desc')-> paginate(8);
        $users = User::where('id','!=',Auth::user()->id)->get();

        return view('todos.index', compact('datas','users'));
    }

    /**
     * Fonction pour lister les todos non terminés
     * */
    Public function undone()
    {
        $datas = Todo::where('done',0)->orderBy('id','desc')-> paginate(8);
        $users = User::where('id','!=',Auth::user()->id)->get();
        return view('todos.index', compact('datas','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $todo = new Todo();
        $todo->creator_id = Auth::user()->id;
        $todo->affectedTo_id = Auth::user()->id;
        $todo->name = $request->name;
        $todo->description = $request->description;
        $todo->save();

        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> vient d'etre créée.");

        return redirect() -> route('todos.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo $todo
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo $todo
     * @return RedirectResponse
     */
    public function update(Request $request, Todo $todo)
    {
        if (!isset($request->done))
        {
            $request ['done'] = 0;
        }
        $todo->update($request->all());
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> a bien été mise a jour.");
        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        notify()->error("La todo <span class='badge badge-dark'>#$todo->id</span> a bien été supprimée.");
        return back();
    }

    /**
     *changement d'etat
     * @param Todo $todo
     * @return RedirectResponse
     */
    public function makedone(Todo $todo)
    {
        $todo->done = 1;
        $todo->update();
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> est terminée.");
        return back();

    }
    /**
     *changement d'etat
     * @param Todo $todo
     * @return RedirectResponse
     */
    public function makeundone(Todo $todo)
    {
        $todo->done = 0;
        $todo->update();
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id</span> est ouverte.");
        return back();

    }

}
