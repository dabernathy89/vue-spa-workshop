@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create A Scavenger Hunt</div>

                <div class="card-body">
                    @include('partials.errors')
                    <form action="{{ route('hunt.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Scavenger Hunt Name</label>
                            <input class="form-control" type="text" name="name" placeholder="My Cool Scavenger Hunt">
                        </div>

                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
