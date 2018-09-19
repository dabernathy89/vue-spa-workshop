<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div v-if="!currentUserId" class="jumbotron text-center">
                <h1 class="display-5">Welcome to Scavenger Hunt!</h1>
                <p class="lead">Sign up or log in to get started.</p>
            </div>

            <div v-if="currentUserId" class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    My Scavenger Hunts
                    <router-link v-if="ownedHunts.length" class="btn btn-primary" :to="{ path: '/hunts/create'}">
                        Create <i class="fas fa-plus-square"></i>
                    </router-link>
                </div>

                <ul v-if="ownedHunts.length" class="list-group list-group-flush">
                    <li v-for="hunt in ownedHunts" class="list-group-item">
                        <a :href="'hunts/' + hunt.id">
                            {{ hunt.name }}
                        </a>
                    </li>
                </ul>
                <div v-else class="card-body">
                    <p>It looks like you don't currently own any scavenger hunts. Create one now:</p>
                    <a class="btn btn-primary" href="/hunts/create">Create A Scavenger Hunt</a>
                </div>
            </div>

            <div v-if="currentUserId" class="card">
                <div class="card-header">Other Scavenger Hunts</div>
                <ul v-if="otherHunts.length" class="list-group list-group-flush">
                    <li v-for="(hunt, index) in otherHunts" class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <a :href="'/hunts/' + hunt.id">
                                {{ hunt.name }}
                            </a>
                            <template v-if="hunt.is_closed">
                                - <em>closed</em>
                            </template>
                        </span>

                        <button
                            v-if="!hunt.includes_current_user && hunt.is_open"
                            title="Join Scavenger Hunt"
                            class="btn btn-secondary"
                            @click="joinHunt(hunt.id, index)">
                            Join <i class="fas fa-user-plus"></i>
                        </button>

                        <button
                            v-else-if="hunt.is_open"
                            title="Leave Scavenger Hunt"
                            class="btn btn-secondary"
                            @click="leaveHunt(hunt.id, index)">
                            Leave <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                ownedHunts: [],
                otherHunts: [],
                currentUserId: null,
            }
        },

        mounted() {
            this.ownedHunts = window.ownedHunts;
            this.otherHunts = window.otherHunts;
            this.currentUserId = window.currentUserId;
        },

        methods: {
            joinHunt(id, index) {
                axios.post('/hunts/' + id + '/users/' + this.currentUserId)
                    .then(response => {
                        this.$emit('success', response.data.successMessage);
                        this.otherHunts[index].includes_current_user = true;
                        window.scrollTo({top: 0});
                    });
            },

            leaveHunt(id, index) {
                axios.delete('/hunts/' + id + '/users/' + this.currentUserId)
                    .then(response => {
                        this.$emit('success', response.data.successMessage);
                        this.otherHunts[index].includes_current_user = false;
                        window.scrollTo({top: 0});
                    });
            }
        }
    }
</script>
