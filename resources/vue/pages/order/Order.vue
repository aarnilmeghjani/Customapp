<template>
<p-page title="Order page">


    <PButton @click="resource" >Create Order</PButton>

</p-page>
</template>

<script>
    import {Button, Modal, ResourcePicker, Toast} from "@shopify/app-bridge/actions";

    export default {
        name: "Order",
        data(){
            return{

            }
        },
        created() {


        },

        methods:{
            toastNotification(message, isError = false, duration = 5000) {
                const toastNotification = Toast.create(this.$root, {
                    message,
                    duration,
                    isError,
                });
                toastNotification.dispatch(Toast.Action.SHOW);
            },
            button(){

                const myButton = Button.create(this.$root, {
                    label: 'Save'
                });
                myButton.dispatch(Button.Action.CLICK);
                console.log(myButton);
            },
            resource(){
                this.productPicker = ResourcePicker.create(this.$root, {
                    resourceType: ResourcePicker.ResourceType.Product,
                });
                this.productPicker.dispatch(ResourcePicker.Action.OPEN)
                console.log(this.productPicker);
                this.productPicker.subscribe(ResourcePicker.Action.SELECT,(selectPayload)=>{
                    this.modal();
                    this.selected = selectPayload.selection;
                });

            },
            modal(){
                const okButton = Button.create(this.$root, {label: 'Order Product'});
                okButton.subscribe(Button.Action.CLICK, () => {
                    this.createOrder();

                });
                const cancelButton = Button.create(this.$root, {label: 'Cancel'});
                cancelButton.subscribe(Button.Action.CLICK, () => {
                    this.myModal.dispatch(Modal.Action.CLOSE);
                });

                const modalOptions = {
                    title: 'Sure want to order',
                    message: 'Please confirm your order',
                    footer: {
                        buttons: {
                            primary: okButton,
                            secondary: [cancelButton],
                        },
                    },
                };
                this.myModal = Modal.create(this.$root, modalOptions);
                this.myModal.dispatch(Modal.Action.OPEN);


            },
            createOrder() {
                this.myModal.dispatch(Modal.Action.CLOSE);
                try {
                    this.axios.post('api/order ', this.selected).then(res => {
                        if (res.data === 'order created successfully') {
                            this.toastNotification(res.data);
                        }
                    })
                } catch (e) {
                    console.log(e);
                }
            }
        }
    }
</script>

<style scoped>

</style>
