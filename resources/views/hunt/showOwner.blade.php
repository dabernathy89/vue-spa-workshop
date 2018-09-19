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

                    <div class="mt-3">
                        <button v-if="hunt.is_open" @click="closeHunt" title="Close Scavenger Hunt" class="btn btn-secondary">
                            Close &nbsp;&nbsp;<i class="fas fa-door-closed"></i>
                        </button>

                        <button v-if="hunt.is_closed" @click="reopenHunt" title="Reopen Scavenger Hunt" class="btn btn-secondary">
                            Reopen &nbsp;&nbsp;<i class="fas fa-door-open"></i>
                        </button>

                        <button @click="deleteHunt" title="Delete Scavenger Hunt" class="btn btn-danger" type="submit">
                            Delete &nbsp;&nbsp;<i class="fas fa-trash"></i>
                        </button>

                        <a :href="'/hunts/' + hunt.id + '/solutions'" class="btn btn-primary">View Submitted Solutions</a>
                    </div>
                </div>

                <div v-if="errors.length" class="card-body">
                    @include('partials.errors')
                </div>

                <div class="card-body">
                    <h3>Goals</h3>

                    <form v-if="hunt.is_open" class="mb-3 mt-3" @submit.prevent="createGoal">
                        <div class="form-row">
                            <div class="col-12 col-sm-8">
                                <input class="form-control" v-model="goalTitle" type="text" placeholder="Insert Goal Title...">
                            </div>

                            <div class="col-12 col-sm-4">
                                <input class="form-control btn btn-primary" type="submit" value="Create A Goal">
                            </div>
                        </div>
                    </form>

                    <ul v-if="hunt.goals.length" class="list-group">
                        <li v-for="(goal, index) in hunt.goals" class="list-group-item">
                            <div v-if="hunt.is_open" class="d-flex justify-content-between align-items-center">
                                <strong>@{{ goal.title }}</strong>
                                <button @click="deleteGoal(goal.id, index)" title="Delete Goal" class="border-0 bg-transparent" type="submit"><i class="fas fa-trash"></i></button>
                            </div>
                            <strong v-else>@{{ goal.title }}</strong>
                        </li>
                    </ul>

                    <ul v-else class="list-group">
                        <li class="list-group-item"><em>This Scavenger Hunt does not have any goals yet.</em></li>
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
            hunt: @json($hunt),
            errors: [],
            goalTitle: '',
        },
        methods: {
            closeHunt: function () {
                var vm = this;
                axios.patch('/hunts/' + this.hunt.id, {status: 'closed'})
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.hunt.status = 'closed';
                        vm.hunt.is_closed = true;
                        vm.hunt.is_open = false;
                        window.scrollTo({top: 0});
                    });
            },
            reopenHunt: function () {
                var vm = this;
                axios.patch('/hunts/' + this.hunt.id, {status: 'open'})
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.hunt.status = 'open';
                        vm.hunt.is_closed = false;
                        vm.hunt.is_open = true;
                        window.scrollTo({top: 0});
                    });
            },
            deleteHunt: function () {
                var vm = this;
                axios.delete('/hunts/' + this.hunt.id)
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        window.scrollTo({top: 0});
                    });
            },
            createGoal: function () {
                var vm = this;
                this.errors = [];
                axios.post('/hunts/' + this.hunt.id + '/goals', {title: this.goalTitle})
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.goalTitle = '';
                        vm.hunt.goals.push(response.data.goal);
                    }, function (error) {
                        var errors = error.response.data.errors;
                        for (field in errors) {
                            vm.errors = vm.errors.concat(errors[field]);
                        }
                    });
            },
            deleteGoal: function (goalId, index) {
                var vm = this;
                axios.delete('/hunts/' + this.hunt.id + '/goals/' + goalId)
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.hunt.goals.splice(index, 1);
                        window.scrollTo({top: 0});
                    });
            }
        }
    });
</script>
@endsection