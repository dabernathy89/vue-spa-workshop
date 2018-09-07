@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @guest
                <div class="jumbotron text-center">
                    <h1 class="display-5">Welcome to Scavenger Hunt!</h1>
                    <p class="lead">Sign up or log in to get started.</p>
                </div>
            @endguest

            @auth
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    My Scavenger Hunts
                    @if (!$owned_hunts->isEmpty())
                        <a class="btn btn-primary" href="{{ route('hunt.create') }}">
                            Create <i class="fas fa-plus-square"></i>
                        </a>
                    @endif
                </div>
                @if (!$owned_hunts->isEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($owned_hunts as $hunt)
                            <li class="list-group-item">
                                <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}">
                                    {{ $hunt->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="card-body">
                        <p>It looks like you don't currently own any scavenger hunts. Create one now:</p>
                        <a class="btn btn-primary" href="{{ route('hunt.create') }}">Create A Scavenger Hunt</a>
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="card-header">Scavenger Hunts I've Joined</div>
                @if (!$participating_hunts->isEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach($participating_hunts as $hunt)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}">
                                    {{ $hunt->name }}
                                </a>

                                <form action="{{ route('hunt.remove_user', ['hunt' => $hunt->id, 'user' => auth()->id()]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Leave Scavenger Hunt" class="btn btn-secondary" type="submit">
                                        Leave <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="card-body">
                        <p>It looks like you haven't joined any scavenger hunts. Join one now:</p>
                        <a class="btn btn-primary" href="{{ route('hunt.index') }}">Join A Scavenger Hunt</a>
                    </div>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
