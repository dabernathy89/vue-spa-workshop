<template>
<div v-if="hunt" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        Solutions for "{{ hunt.name }}"
                    </h1>

                    <div v-if="hunt.winner_id" class="alert alert-success mt-3">
                        <h4 class="mb-0">Winner: {{ hunt.winner.name }}</h4>
                    </div>

                    <div class="mt-3">
                        <a :href="'/hunts/' + hunt.id" class="btn btn-primary"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back To Goals</a>
                    </div>
                </div>

                <div class="card-body">
                    <h3>Submitted Solutions</h3>
                    <div v-for="participant in hunt.participants" class="card mb-3">
                        <div :class="'card-header d-flex justify-content-between align-items-center ' + (hunt.winner_id === participant.id ? 'text-success' : '')">
                            <h4 class="mb-0">{{ participant.name }}</h4>
                            <h5 v-if="hunt.winner_id === participant.id" class="mb-0"><em>Winner</em></h5>

                            <button v-if="!hunt.winner_id && hunt.is_open" @click="chooseWinner(participant.id)" title="Choose Winner" class="border-0 bg-transparent"><i class="fas fa-trophy"></i></button>
                        </div>

                        <div class="card-body">
                            <div class="card-columns mt-3">
                                <div v-for="solution in participant.solutions" v-if="solution.goal.hunt_id === hunt.id" class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">{{ solution.goal.title }}</h5>
                                    </div>
                                    <img class="card-img-bottom" :src="solution.imageSrc">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!hunt.participants.length" class="card">
                        <div class="card-body">
                            <p class="card-text">This Scavenger Hunt does not have any participants yet.</p>
                        </div>
                    </div>
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
            hunt: null,
        }
    },

    mounted() {
        this.hunt = window.currentHunt;
    },

    methods: {
        chooseWinner(participantId) {
            axios.patch('/hunts/' + this.hunt.id, {winner_id: participantId})
                .then(response => {
                    this.successMessage = response.data.successMessage;
                    this.hunt.winner = this.hunt.participants.find(function (participant) {
                        return participant.id === participantId;
                    });
                    this.hunt.winner_id = participantId;
                    this.hunt.status = 'closed';
                    window.scrollTo({top: 0});
                });
        },
    }
}
</script>
