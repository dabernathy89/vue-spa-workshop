@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        @{{ hunt.name }}
                        <template v-if="hunt.is_closed">
                            <em>- closed</em>
                        </template>
                    </h1>
                    <small>Owned by: <em>@{{ hunt.owner.name }}</em></small>

                    <div v-if="hunt.winner_id" class="alert alert-success mt-3">
                        <h4 class="mb-0">Winner: @{{ hunt.winner.name }}</h4>
                    </div>

                    <button
                        v-if="!hunt.includes_current_user && hunt.is_open"
                        @click="joinHunt(hunt.id)"
                        title="Join Scavenger Hunt"
                        class="btn btn-secondary">
                        Join <i class="fas fa-user-plus"></i>
                    </button>
                </div>

                <div v-if="errors.length" class="card-body">
                    @include('partials.errors')
                </div>

                <div class="card-body">
                    <h3>Goals</h3>

                    <ul v-if="!hunt.goals.length" class="list-group">
                        <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
                    </ul>

                    <ul v-else-if="hunt.includes_current_user" class="list-group">
                        <li v-for="(goal, index) in hunt.goals" class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                @{{ goal.title }}<hr>

                                <button v-if="hunt.is_open" class="border-0 bg-transparent" type="button" @click="showSolutionForm(goal.id)">
                                    <i :class="'fas ' + (userSolution(goal) ? 'fa-edit' : 'fa-plus-circle')" style="cursor: pointer;"></i>
                                </button>
                            </div>

                            <div v-if="userSolution(goal)" class="alert alert-secondary mt-2 d-flex justify-content-between align-items-start">
                                <span>Your solution: </span><img style="height: auto; width: 50%;" :src="userSolution(goal).imageSrc">
                            </div>

                            {{-- The form for creating or updating a solution --}}
                            <div v-if="hunt.is_open" v-show="currentSolutionForm === goal.id" class="mt-3" :id="'goal-solution-form' + goal.id">
                                <form @submit.prevent="addOrEditSolution(goal, index)" method="POST" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="col-12 col-sm-8">
                                            <input class="form-control-file" type="file" :id="'solution-image-' + goal.id" name="image">
                                        </div>

                                        <div class="col-12 col-sm-4">
                                            <input v-if="userSolution(goal)" class="form-control btn btn-primary" type="submit" value="Update Solution">
                                            <input v-else class="form-control btn btn-primary" type="submit" value="Submit A Solution">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                    </ul>

                    <ul v-else class="list-group">
                        <li v-for="goal in hunt.goals" class="list-group-item">
                            @{{ goal.title }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    new Vue({
        el: '#app',
        data: {
            successMessage: '',
            errors: [],
            hunt: @json($hunt),
            currentUserId: {{ auth()->id() }},
            currentSolutionForm: null,
        },
        methods: {
            userSolution(goal) {
                var vm = this;
                return goal.solutions.find(function(solution) {
                    return solution.user_id === vm.currentUserId;
                }) || false;
            },
            showSolutionForm(goalId) {
                this.currentSolutionForm = (this.currentSolutionForm === goalId) ? null : goalId;
            },
            joinHunt(id, index) {
                var vm = this;
                axios.post('/hunts/' + id + '/users/' + this.currentUserId)
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.hunt.includes_current_user = true;
                        window.scrollTo({top: 0});
                    });
            },
            addOrEditSolution: function (goal, index) {
                var vm = this;
                var data = new FormData();
                var input = document.getElementById('solution-image-' + goal.id);
                if (input.files.length) {
                    data.append('image', input.files[0]);
                }
                if (this.userSolution(goal)) {
                    data.append('_method', 'PATCH');
                    axios.post('/goals/' + goal.id + '/solutions/' + this.userSolution(goal).id, data)
                        .then(function (response) {
                            var solution_index = vm.hunt.goals[index].solutions.findIndex(function (solution) {
                                return solution.id === response.data.solution.id;
                            });
                            vm.$set(vm.hunt.goals[index].solutions, solution_index, response.data.solution);
                        });
                } else {
                    axios.post('/goals/' + goal.id + '/solutions', data)
                        .then(function (response) {
                            vm.hunt.goals[index].solutions.push(response.data.solution);
                        });
                }
            }
        }
    });
</script>
@endsection