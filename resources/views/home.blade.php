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
                <ul v-if="otherHunts.length" class="list-group list-group-flush">
                    <li v-for="hunt in otherHunts" class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <a :href="'/hunts/' + hunt.id">
                                @{{ hunt.name }}
                            </a>
                            <template v-if="hunt.is_closed">
                                - <em>closed</em>
                            </template>
                        </span>

                        <button
                            v-if="!hunt.includes_current_user && hunt.is_open"
                            title="Join Scavenger Hunt"
                            class="btn btn-secondary"
                            @click="joinHunt(hunt.id)">
                            Join <i class="fas fa-user-plus"></i>
                        </button>

                        <button
                            v-else-if="hunt.is_open"
                            title="Leave Scavenger Hunt"
                            class="btn btn-secondary"
                            @click="leaveHunt(hunt.id)">
                            Leave <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </li>
                </ul>
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
            otherHunts: @json($other_hunts),
            currentUserId: {{ auth()->id() }}
        },
        methods: {
            joinHunt(id) {
                axios.post('/hunts/' + id + '/users/' + this.currentUserId)
                    .then(function (response) {
                        console.log(response);
                    });
            },
            leaveHunt(id) {
                axios.delete('/hunts/' + id + '/users/' + this.currentUserId)
                    .then(function (response) {
                        console.log(response);
                    });
            }
        }
    });
</script>
@endsection