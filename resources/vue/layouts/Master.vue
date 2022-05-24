<template>
<PPage full-width>
    <PLayout >

        <PTabs
            :tabs='[
                {"id":"home","content":"Home","to":"/home"},
                {"id":"products","content":"Products","to":"/product"},
                {"id":"customer","content":"Customer","to":"/customer"},
                {"id":"order","content":"Orders","to":"/order"}
            ]'
        >

        </PTabs>
    </PLayout>
    <router-view> </router-view>
</PPage>
</template>

<script>
    import createApp from '@shopify/app-bridge';
    import {Redirect} from '@shopify/app-bridge/actions';


    export default {
        name: "Master",

        data(){
            return{
                selected:{},
            }
        },
        async beforeCreate() {
            let queryStrings = this.$route.query;
            this.$root = createApp({
                apiKey: process.env.MIX_SHOPIFY_API_KEY,
                host:queryStrings.host
            });
            const redirect = Redirect.create(this.$root);
            console.log(this.$root);

            // redirect.dispatch(Redirect.Action.APP, '/home');

        },

    }
</script>

<style scoped>

</style>
