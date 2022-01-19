@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header justify-content-center">
            <h3 class="card-title">Modification de todo <span class="badge badge-dark">#{{$todo->id}}</span></h3>
        </div>
        <div class="card-body">
            <form action="{{route('todos.update', $todo->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Titre</label>
                    <input type="text"  name="name" class="form-control" required="" id="name" aria-describedby="name"
                           value="{{old('name', $todo->name)}}">
                    <small id="nameHelp" class="form-text text-muted">Veuillez entrer le titre du todo.</small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name= "description" class="form-control" id="description" required=""
                    value="{{old('description', $todo->description)}}">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="done" name="done" {{$todo->done? 'checked': ''}} value=1>
                    <label class="form-check-label" for="done">Done</label>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
@endsection
