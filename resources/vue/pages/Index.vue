<template>

    <PPage full-width>

        <PStack>
            <PStackItem fill="">
                <PHeading element="h1">Products</PHeading>
            </PStackItem>
            <PStackItem>
                <PButton primary @click="changeCreateModal">Add product</PButton>
            </PStackItem>
        </PStack>

        <PCard sectioned>
            <PDataTable
                :resourceName="{singular: 'Product', plural: 'Products'}"
                :headings="[
            {
                content: 'Product',
                value: 'title',
                type: 'text',
                width: '30%'
            },
            {
                content: 'Description',
                value: 'description',
                type: 'text',
                width: '30%'
            },
            {
                content: 'Status',
                value: 'status',
                type: 'text',
            },{
                content: 'Vendor',
                value: 'vendor',
                type: 'text',
            },
             {
                content: 'Actions',
                value: 'actions',
                type: 'text',
                sortable: false,
            },

        ]"
                :rows="this.products"
                @input-filter-changed="searchFilter"
                :hasPagination="true"
                :pagination="{
            hasPrevious: this.pagination['hasPreviousPage'],
            hasNext: this.pagination['hasNextPage'],
            onNext: ()=>{
                this.paginate('next');
            },
            onPrevious:()=>{
              this.paginate('previous')
            },
        }"
            >
                <template v-slot:item="{item}">
                    <PDataTableRow>
                        <PDataTableCol firstColumn>{{item.node.title}}</PDataTableCol>
                        <PDataTableCol firstColumn>
                            <PScrollable vertical horizontal shadow focusable>
                                {{item.node.description}}
                            </PScrollable>
                        </PDataTableCol>
                        <PDataTableCol>
                            <PBadge status="success" progress="complete" size="medium"
                                    v-if="item.node.status == 'ACTIVE'">
                                {{item.node.status}}
                            </PBadge>
                            <PBadge status="info" progress="partiallyComplete" color="red" size="medium" v-else>
                                {{item.node.status}}
                            </PBadge>

                        </PDataTableCol>
                        <PDataTableCol>{{item.node.vendor}}</PDataTableCol>
                        <PDataTableCol>
                            <PStack>
                                <PStackItem>
                                    <PButton>
                                        <PIcon @click="editProduct(item.node)" source="EditMinor"/>
                                    </PButton>
                                </PStackItem>
                                <PStackItem>
                                    <PButton>
                                        <PIcon @click="deleteProduct(item.node.legacyResourceId)" source="DeleteMinor"
                                               color="critical"/>
                                    </PButton>

                                </PStackItem>
                            </PStack>
                        </PDataTableCol>
                    </PDataTableRow>
                </template>
                <PButtonGroup slot="filter" segmented>

                    <PPopover
                        id="popover_1"
                        :active="openFilter"
                        preferred-alignment="right"
                        fullWidth
                    >
                        <PButton
                            @click="filter"
                            slot="activator"
                            :disclosure="openFilter ? 'up' : 'down'">
                            Status
                        </PButton>
                        <POptionList
                            slot="content"
                            allowMultiple
                            :selected="statusSelected"
                            @change="onActivate($event)"
                            :options="filterOptions"

                        />
                    </PPopover>
                </PButtonGroup>
            </PDataTable>
        </PCard>
        <PStack>
            <PStackItem>
                <PModal
                    :open="deleteModal"
                    sectioned
                    :secondaryActions="deleteModalData"
                    title="Alert!"
                    @close="changeDeleteModal"
                >
                    <PHeading element="h1">Are you sure you want to delete product?</PHeading>
                </PModal>
            </PStackItem>
        </PStack>
        <PStack>
            <PStackItem>
                <PModal
                    :open="editModal"
                    sectioned
                    :secondaryActions="editModalData"
                    title="Edit Product"
                    @close="changeEditModal"
                >
                    <PFormLayout>
                        <PTextField v-model="title" label="Product Title"/>
                        <PTextField v-model="description" label="Product Description"/>
                    </PFormLayout>
                </PModal>
            </PStackItem>
        </PStack>
        <PStack>
            <PStackItem>
                <PModal
                    :open="createModal"
                    sectioned
                    :secondaryActions="createModalData"
                    title="Add Product"
                    @close="changeCreateModal"
                >
                    <PFormLayout>
                        <PTextField v-model="form.title" label="Product Title"
                                    :error="(errors.title) ? errors.title: null"/>
                        <PTextField v-model="form.description" label="Product Description"
                                    :error="(errors.description) ? errors.description: null"/>
                        <PTextField v-model="form.vendor" label="Vendor" :error="(errors.vendor) ? errors.vendor: null"/>
                        <PTextField v-model="form.price" label="Price" :error="(errors.price) ? errors.price: null" type="number"/>
                    </PFormLayout>
                </PModal>
            </PStackItem>
        </PStack>
    </PPage>
</template>



<script>
    export default {
        data() {
            return {
                params: {
                    value: '',
                },
                statusSelected: [],
                openFilter: false,
                form: {
                    title: '',
                    description: '',
                    vendor: '',
                },
                errors: {},
                title: null,
                description: null,
                vendor: null,
                productId: null,
                deleteId: null,
                deleteModal: false,
                editModal: false,
                createModal: false,
                editId: null,

                products: [],
                pagination: [],
                filterOptions: [
                    {label: 'Active', value: 'ACTIVE'},
                    {label: 'Draft', value: 'DRAFT'},
                ],

                deleteModalData: [
                    {
                        content: "Cancel",
                        onAction: this.changeDeleteModal
                    }, {
                        content: "Delete Product",
                        destructive: true,
                        onAction: this.deleteItem
                    }
                ],
                editModalData: [
                    {
                        content: "Cancel",
                        onAction: this.changeEditModal
                    }, {
                        content: "Update Product",
                        destructive: true,
                        onAction: this.updateProduct
                    }
                ],
                createModalData: [
                    {
                        content: "Cancel",
                        onAction: this.changeCreateModal
                    }, {
                        content: "Create Product",
                        destructive: true,
                        onAction: this.createProduct
                    }
                ],
            }
        },

        methods: {
            onActivate($event) {

                if (this.statusSelected.indexOf($event[0]) === -1) {
                    this.statusSelected.push($event[0]);
                } else {
                    this.statusSelected = [];
                    this.statusSelected.push($event[0]);
                }
                if ($event.length === 0) {
                    this.statusSelected = [];
                }
                if (this.statusSelected.length > 0) {
                    let status = {'status': this.statusSelected}
                    this.axios.post('/api/products/filter', status).then(response => {
                        return this.products = response.data.edges
                    })
                } else {
                    return this.getData();
                }
            },
            filter() {
                this.openFilter = !this.openFilter
            },
            searchFilter(value) {

                if (value) {
                    let data = {'text': value}
                    this.axios.post('/api/products/filter', data).then(response => {
                        return this.products = response.data.edges
                    })
                } else {
                    this.getData();
                }
            },
            getData() {
                this.$pLoading.start();
                this.axios.get('/api/products')
                    .then(response => {
                        this.products = response.data.edges
                        this.pagination = response.data.pageInfo
                        this.$pLoading.finish();
                    })
                    .catch(error => console.log(error))

            },
            changeDeleteModal() {
                this.deleteModal = !this.deleteModal
            },
            changeEditModal() {
                this.editModal = !this.editModal
            },
            changeCreateModal() {
                this.createModal = !this.createModal
            },
            editProduct(item) {
                this.title = item.title;
                this.description = item.description;
                this.productId = item.legacyResourceId;
                this.editModal = true;
            },
            async createProduct() {
                this.$pLoading.start();
                try {
                    let response = await this.axios.post('/api/product/create', this.form)
                    this.getData();


                    if(response.data==='success')
                    {
                        this.changeCreateModal();
                        this.$pLoading.finish();
                        this.$pToast.open({
                            message: 'Product add successfully',

                        });
                        this.getData();


                    }
                    else{
                        this.errors = {};
                        if(response.data.title){
                            this.errors.title = response.data.title.join();
                        }
                        if(response.data.description){
                            this.errors.description = response.data.description.join();
                        }
                        if(response.data.vendor){
                            this.errors.vendor = response.data.vendor.join();
                        }
                        if(response.data.price){
                            this.errors.price = response.data.price.join();
                        }

                        console.log(response.data);


                    }



                } catch (error) {

                    if (error.response.status === 422) {
                        if(error.response.data.errors.title){
                            this.errors.title = error.response.data.errors.title.join();
                        }
                        if(error.response.data.errors.description){
                            this.errors.description = error.response.data.errors.description.join();
                        }
                        if(error.response.data.errors.vendor){
                            this.errors.vendor = error.response.data.errors.vendor.join();
                        }
                        if(error.response.data.errors.price){
                            this.errors.price = error.response.data.errors.price.join();
                        }
                        // this.errors.title = error.response.data.title
                        // this.errors = error.response.data.errors;
                    }
                }
            },
            updateProduct() {
                this.$pLoading.start();

                let data = {
                    'title': this.title,
                    'description': this.description,
                    'id': this.productId,
                };
                this.axios.post('/api/product/update', data).then(response => {
                    this.getData();
                    this.changeEditModal();
                    this.$pLoading.finish();
                })

            },
            deleteProduct(id) {
                this.deleteModal = true;
                this.deleteId = id
            },
            deleteItem() {
                this.axios.delete('/api/product/delete/' + this.deleteId).then(response => {
                    this.getData();
                    this.changeDeleteModal();
                })
            },
            paginate(value) {
                if (value === 'next') {
                    var paginate = {'next': this.pagination['endCursor']}
                } else {
                    var paginate = {'previous': this.pagination['startCursor']}
                }
                this.axios.post('/api/products', paginate).then(response => {
                    this.products = response.data.edges;
                    this.pagination = response.data.pageInfo;

                })
            },
        },
        created() {
            this.getData();
        }
    }
</script>

<style scoped>

</style>
