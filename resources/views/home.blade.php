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
                    <a v-if="ownedHunts.length" class="btn btn-primary" href="/hunts/create/">
                        Create <i class="fas fa-plus-square"></i>
                    </a>
                </div>

                <ul v-if="ownedHunts.length" class="list-group list-group-flush">
                    <li v-for="hunt in ownedHunts" class="list-group-item">
                        <a :href="'hunts/' + hunt.id">
                            @{{ hunt.name }}
                        </a>
                    </li>
                </ul>
                <div v-else class="card-body">
                    <p>It looks like you don't currently own any scavenger hunts. Create one now:</p>
                    <a class="btn btn-primary" href="/hunts/create">Create A Scavenger Hunt</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Other Scavenger Hunts</div>
                @if (!$other_hunts->isEmpty())
                    <ul class="list-group list-group-flush">
                    @foreach($other_hunts as $hunt)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <a href="{{ route('hunt.show', ['hunt' => $hunt->id]) }}">
                                    {{ $hunt->name }}
                                </a>
                                @if ($hunt->isClosed)
                                    - <em>closed</em>
                                @endif
                            </span>

                            @if (!$hunt->includesUser(auth()->user()) && $hunt->isOpen)
                                @include('hunt.partials.join-hunt-button')
                            @elseif ($hunt->isOpen)
                                @include('hunt.partials.leave-hunt-button')
                            @endif
                        </li>
                    @endforeach
                    </ul>
                @endif
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#app',
        data: {
            ownedHunts: @json($owned_hunts),
        }
    });
</script>
@endsection