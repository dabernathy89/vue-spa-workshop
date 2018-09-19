@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create A Scavenger Hunt</div>

                <div class="card-body">
                    @include('partials.errors')
                    <form @submit.prevent="createHunt">
                        <div class="form-group">
                            <label for="name">Scavenger Hunt Name</label>
                            <input v-model="huntName" class="form-control" type="text" placeholder="My Cool Scavenger Hunt">
                        </div>

                        <input class="btn btn-primary" type="submit" value="Submit">
                    </form>
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
            huntName: '',
            errors: [],
        },
        methods: {
            createHunt: function () {
                var vm = this;
                this.errors = [];
                axios.post('/hunts', {name: this.huntName})
                    .then(function (response) {
                        vm.successMessage = response.data.successMessage;
                        vm.huntName = '';
                    }, function (error) {
                        var errors = error.response.data.errors;
                        for (field in errors) {
                            vm.errors = vm.errors.concat(errors[field]);
                        }
                    });
            }
        }
    });
</script>
@endsection