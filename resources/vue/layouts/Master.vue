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
    import {History, Toast, ResourcePicker, Redirect, Button,Modal} from '@shopify/app-bridge/actions';


    export default {
        name: "Master",

        async beforeCreate() {
            let queryStrings = this.$route.query;
            this.app = createApp({
                apiKey: process.env.MIX_SHOPIFY_API_KEY,
                host:queryStrings.host
            });
            const redirect = Redirect.create(app);
            console.log(this.app);

            // redirect.dispatch(Redirect.Action.APP, '/home');

        },
        async mounted() {
            this.app.$toast = this.toastNotification;
        },

        created() {

            this.toastNotification('product created');
            this.button();
            this.resource();





        },
        watch: {
            $route(to, from) {
                const history = History.create(app);
                history.dispatch(History.Action.PUSH, to.path);


            }
        },
        methods:{
            toastNotification(message, isError = false, duration = 5000) {
                const toastNotification = Toast.create(this.app, {
                    message,
                    duration,
                    isError,
                });
                toastNotification.dispatch(Toast.Action.SHOW);
            },
            button(){
                const myButton = Button.create(this.app, {
                    label: 'Save'
                });
                myButton.dispatch(Button.Action.CLICK);
                console.log(myButton);
            },
            resource(){
                const productPicker = ResourcePicker.create(this.app, {
                    resourceType: ResourcePicker.ResourceType.Product,
                });
                productPicker.dispatch(ResourcePicker.Action.OPEN)
                console.log(productPicker);
                productPicker.subscribe(ResourcePicker.Action.SELECT,(selectPayload)=>{
                    this.modal();
                   let selection = selectPayload.selection;
                    console.log(selection);
                });
                // picker.subscribe(ResourcePicker.Action.CANCEL, () => {
                //     // Picker was cancelled
                // });
                // picker.subscribe(ResourcePicker.Action.SELECT, (selectPayload) => {
                //     const selection = selectPayload.selection;
                //     // Do something with `selection`
                // });
            },
            modal(){
                const okButton = Button.create(this.app, {label: 'Order Product'});
                okButton.subscribe(Button.Action.CLICK, () => {
                    // Do something with the click action
                });
                const cancelButton = Button.create(this.app, {label: 'Cancel'});
                cancelButton.subscribe(Button.Action.CLICK, () => {
                    // Do something with the click action
                });

                const modalOptions = {
                    title: 'Sure want to order',
                    message: 'Hello world!',
                    footer: {
                        buttons: {
                            primary: okButton,
                            secondary: [cancelButton],
                        },
                    },
                };
                let myModal = Modal.create(this.app, modalOptions);
                myModal.dispatch(Modal.Action.OPEN);


            }
        }
    }
</script>

<style scoped>

</style>
