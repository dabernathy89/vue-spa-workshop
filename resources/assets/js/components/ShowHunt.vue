<template>
<component v-bind:is="huntShowType" :hunt="hunt"></component>
</template>

<script>
import ShowOwner from './ShowOwner.vue';
import ShowParticipant from './ShowParticipant.vue';

export default {
    data() {
        return {
            currentUserId: null,
            hunt: null,
        }
    },

    beforeRouteEnter (to, from, next) {
        let ownedHunts;
        let otherHunts;
        axios.get('/api/hunts/' + to.params.id).then(response => {
            next(vm => {
                vm.hunt = response.data;
            });
        });
    },

    mounted() {
        this.currentUserId = window.currentUserId;
    },

    computed: {
        huntShowType() {
            let owner_id = this.hunt ? this.hunt.owner_id : null;
            if (owner_id && owner_id === this.currentUserId) {
                return 'show-owner';
            }

            return 'show-participant';
        }
    }
}
</script>