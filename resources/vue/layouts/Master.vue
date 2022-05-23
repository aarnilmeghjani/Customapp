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
        // async mounted() {
        //     this.$root.$toast = this.toastNotification;
        // },
        //
        // created() {
        //
        //     // this.toastNotification('product created');
        //     // this.button();
        //     // this.resource();
        //
        //
        //
        // },
        // watch: {
        //     $route(to, from) {
        //         const history = History.create(this.$root);
        //         history.dispatch(History.Action.PUSH, to.path);
        //
        //
        //     }
        // },
        // methods:{
        //     toastNotification(message, isError = false, duration = 5000) {
        //         const toastNotification = Toast.create(this.$root, {
        //             message,
        //             duration,
        //             isError,
        //         });
        //         toastNotification.dispatch(Toast.Action.SHOW);
        //     },
        //     button(){
        //         const myButton = Button.create(this.$root, {
        //             label: 'Save'
        //         });
        //         myButton.dispatch(Button.Action.CLICK);
        //         console.log(myButton);
        //     },
        //     resource(){
        //         this.productPicker = ResourcePicker.create(this.$root, {
        //             resourceType: ResourcePicker.ResourceType.Product,
        //         });
        //         this.productPicker.dispatch(ResourcePicker.Action.OPEN)
        //         console.log(this.productPicker);
        //         this.productPicker.subscribe(ResourcePicker.Action.SELECT,(selectPayload)=>{
        //             this.modal();
        //            this.selected = selectPayload.selection;
        //         });
        //
        //     },
        //     modal(){
        //         const okButton = Button.create(this.$root, {label: 'Order Product'});
        //         okButton.subscribe(Button.Action.CLICK, () => {
        //             this.createOrder();
        //
        //         });
        //         const cancelButton = Button.create(this.$root, {label: 'Cancel'});
        //         cancelButton.subscribe(Button.Action.CLICK, () => {
        //             this.myModal.dispatch(Modal.Action.CLOSE);
        //         });
        //
        //         const modalOptions = {
        //             title: 'Sure want to order',
        //             message: 'Please confirm your order',
        //             footer: {
        //                 buttons: {
        //                     primary: okButton,
        //                     secondary: [cancelButton],
        //                 },
        //             },
        //         };
        //         this.myModal = Modal.create(this.$root, modalOptions);
        //         this.myModal.dispatch(Modal.Action.OPEN);
        //
        //
        //     },
        //     createOrder() {
        //         this.myModal.dispatch(Modal.Action.CLOSE);
        //         try {
        //             this.axios.post('api/order ', this.selected).then(res => {
        //                 if (res.data === 'order created successfully') {
        //                     this.toastNotification(res.data);
        //                 }
        //             })
        //         } catch (e) {
        //             console.log(e);
        //         }
        //     }
        // }
    }
</script>

<style scoped>

</style>
