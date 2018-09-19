<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create A Scavenger Hunt</div>

                <div class="card-body">
                    <div v-if="errors.length" class="alert alert-danger">
                        <ul class="mb-0">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
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
</template>

<script>
    export default {
        data() {
            return {
                huntName: '',
                errors: [],
            }
        },
        methods: {
            createHunt() {
                this.errors = [];
                axios.post('/hunts', {name: this.huntName})
                    .then(response => {
                        this.$emit('success', response.data.successMessage);
                        this.huntName = '';
                    }, error => {
                        var errors = error.response.data.errors;
                        for (let field in errors) {
                            this.errors = this.errors.concat(errors[field]);
                        }
                    });
            }
        }
    }
</script>
