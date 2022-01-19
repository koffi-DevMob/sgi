@extends('layouts.app')

@section('content')

    <div class="card m-4">
        <div class="card-header justify-content-center">
            <h3>Creation de todo</h3>
        </div>
        <div class="card-body">
            <form action="{{route('todos.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Titre</label>
                    <input type="text"  name="name" class="form-control" required="" id="name" aria-describedby="name">
                    <small id="nameHelp" class="form-text text-muted">Veuillez entrer le titre du todo.</small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name= "description" class="form-control" id="description" required="">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

@endsection
