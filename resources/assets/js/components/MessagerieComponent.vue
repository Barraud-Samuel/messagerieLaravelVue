<template>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <!--utilisation d'une balise template pour ne pas cassser l'html-->
                <a  v-for="conversation in conversations" class="list-group-item d-flex justify-content-between align-items-center">
                    {{conversation.name}}
                    <span class="badge badge-pill badge-primary" v-if="conversation.unread">{{conversation.unread}}</span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        name: "messagerie-component",
        props: {
            user: Number
        },
        computed: {
            ...mapGetters(['conversations'])
        },
        mounted () {
            this.$store.dispatch('loadConversations')
        }
    }
</script>

<style scoped>

</style>