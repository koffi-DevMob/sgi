@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xs">
                <a href="{{route('todos.create')}}" class="btn btn-primary m-3" name ="" id="" role="button">Ajouter todo</a>
            </div>

            <div class="col-xs">
                @if (Route::currentRouteName() == 'todos.index')

                    <a href="{{route('todos.undone')}}" class="btn btn-warning m-3" name ="" id="" role="button">Todo ouverts</a>
            </div>
            <div class="col-xs">
                <a href="{{route('todos.done')}}" class="btn btn-success m-3" name ="" id="" role="button">Todo terminer</a>
                @elseif (Route::currentRouteName() == 'todos.done')

                    <a href="{{route('todos.index')}}" class="btn btn-dark m-3" name ="" id="" role="button">Toutes les todos</a>
            </div>
            <div class="col-xs">
                <a href="{{route('todos.undone')}}" class="btn btn-warning m-3" name ="" id="" role="button">Todo ouvertes</a>
            </div>
            @elseif (Route::currentRouteName() == 'todos.undone')
                <a href="{{route('todos.index')}}" class="btn btn-dark m-3" name ="" id="" role="button">Toutes les todos</a>
        </div>
        <div class="col-xs">
            <a href="{{route('todos.done')}}" class="btn btn-success m-3" name ="" id="" role="button">Todo terminer</a>
            @endif
        </div>
    </div>
    @foreach($datas as $data)
        <div class="alert alert-{{$data->done? 'success' : 'warning'}}" role ="alert">
            <div class="row">
                <div class="col-sm">
                    <p class="my-0">
                        <strong>
                            <span class="badge badge-dark">
                                #{{$data->id}}
                            </span>
                        </strong>
                        <small>
                            Créée {{ $data->created_at->from() }},
{{--                            {{ Auth::user()->id == $data->user->id ? 'moi' : $data->user->name }}--}}
                                @if($data->todoAffectedTo && $data->todoAffectedTo->id == Auth::user()->id)
                                affectée à moi
                                @elseif ($data->todoAffectedTo)
                            {{$data->todoAffectedTo ? 'Affectée a' .$data->AffectedTo->name : ''}}
                                @endif
                            {{--Display affected by someone or by user himself--}}
                            @if($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id == Auth::user()->id)
                            par moi même
                            @elseif($data->todoAffectedTo && $data->todoAffectedBy && $data->todoAffectedBy->id != Auth::user()->id)
                                par {{$data->todoAffectedBy->name}}
                            @endif
                        </small>
                        @if($data->done)
                            <small>
                    <p>
                        Terminée
                        {{$data->updated_at->from()}} - Terminée en
                        {{$data->updated_at->diffForHumans($data->updated_at, 1)}}
                    </p>
                            </small>

                    @endif

                    <details>
                        <summary>
                            <strong>{{$data -> name}} @if($data-> done)<span class="badge badge-success"> done </span>@endif</strong>
                        </summary>
                        <p>{{$data->description}}</p>
                    </details>
                </div>
                <div class="col-sm form-inline mx-2 justify-content-end my-1">
                    {{--button affecter--}}
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Affecté à
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($users as $user)
                                <a href="../public/todos/{{$data->id}}/affectedTo/{{$user->id}}" class="dropdown-item">{{$user->name}}</a>
                            @endforeach
                        </div>
                    </div>
                    {{--Bouton done/undone--}}
                    @if($data->done ==0)
                        <form action="{{route('todos.makedone',$data->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success mx-2 my-1" style="min-width:80px">Done</button>
                        </form>
                    @else
                        <form action="{{route('todos.makeundone',$data->id)}}" method="post">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning mx-2 my-1" style="min-width:80px">Undone</button>
                        </form>
                    @endif

                    {{-- bouton edit--}}
                    @can('edit',$data)
                    <a href="{{route('todos.edit', $data->id)}}" name="" id="" class="btn btn-info mx-2 my-1" role="button">Editer</a>
                    @elsecannot('edit',$data)
                        <a href="{{route('todos.edit', $data->id)}}" name="" id="" class="btn btn-info mx-2 my-1 disabled" role="button">Editer</a>
                    @endcan
                        {{--bouton delete--}}
                    @can('delete',$data)
                    <form action="{{route('todos.destroy',$data->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mx-2 my-1">Effacer</button>
                    </form>
                        @elsecannot('delete',$data)
                        <form action="{{route('todos.destroy',$data->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-2 my-1 " disabled>Effacer</button>
                        </form>
                    @endcan

                </div>
            </div>

        </div>
    @endforeach

    {{$datas -> links()}}
@endsection


