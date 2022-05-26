<template>
    <PPage title="Customer" full-width
           :primaryAction="PrimaryAction"
           :secondaryActions="[
        {
          content: 'Import',
          onAction: importcustomerdata,
          icon: 'ImportMinor'
        },
        {
          content: 'Export',
          onAction: exportcustomerdata,
          icon: 'ExportMinor'
        }
        ]"
    >


        <PCard sectioned>
            <PDataTable
                :resourceName="{singular: 'Customer', plural: 'Customers'}"
                :headings="[
            {
                content: 'Customer',
                value: 'name',
                type: 'text',
                width: '30%'
            },
            {
                content: 'Email',
                value: 'email',
                type: 'email',


            },
            {
                content: 'Phone Number',
                value: 'phone',
                type: 'text',

            },
            {
                content: 'Address',
                value: 'address',
                type: 'text',

            },
            {
                content: 'Status',
                value: 'status',
                type: 'text',
            },
            {
                content: 'Actions',
                value: 'actions',
                type: 'text',
                sortable: false,

            },
        ]"
                :rows="customers"
                @input-filter-changed="searchFilter"


            >
                <template v-slot:item="{item}">
                    <PDataTableRow>
                        <PDataTableCol firstColumn>
                            {{item.name}}
                        </PDataTableCol>
                        <PDataTableCol>{{item.email}}</PDataTableCol>
                        <PDataTableCol>
                            {{item.phone}}
                        </PDataTableCol>
                        <PDataTableCol>{{item.address}}</PDataTableCol>
                        <PDataTableCol>
                            <PBadge status="success" progress="complete" size="medium"
                                    v-if="item.status === 0">
                                Active
                            </PBadge>
                            <PBadge status="info" progress="partiallyComplete" color="red" size="medium" v-else>
                                Inactive
                            </PBadge>

                        </PDataTableCol>
                        <PDataTableCol>
                            <PStack>
                                <PStackItem>
                                    <PIcon source="EditMinor" @click="editProduct(item)"/>
                                </PStackItem>
                                <PStackItem>
                                    <PIcon source="DeleteMinor" @click="deleteCustomerId(item.id)" color="critical"/>
                                </PStackItem>
                            </PStack>
                        </PDataTableCol>
                    </PDataTableRow>
                </template>
                <PButtonGroup slot="filter" segmented>


                    <PPopover
                        id="popover_1"
                        :active="active"
                        preferred-alignment="right"
                        fullWidth
                    >
                        <PButton
                            @click="filterbutton"
                            slot="activator"
                        :disclosure="active ? 'up' : 'down'">
                        Filter Options
                        </PButton>
                        <POptionList
                            slot="content"
                            :selected="selected"
                            @change="filterChange($event)"
                            :options="[
                        {label: 'Lasted Customer', value: 'asc'},
                        {label: 'Old Customer ', value: 'DESC'},

                    ]"
                        />
                    </PPopover>

                    <PPopover
                        id="popover_2"
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
                    <PDatePicker
                        button
                        id="pDatePicker"
                        :singleDatePicker="false"
                        placeholder="Select Date"
                        :value="null"
                        opens="left"
                        @change="datefilter($event)"

                    />

                </PButtonGroup>
            </PDataTable>
        </PCard>


        <PModal
            :open="customermodal"
            sectioned
            @close="customerModal"
            :primaryAction='{"content":"Save Customer",onAction:createCustomer}'
            :secondaryActions='[{"content":"Cancel",onAction:customerModal}]'
            title="Enter Customer Details"
        >
            <PFormLayout>
                <PTextField label="Full name" v-model="form.name"
                            :error="(errors.name) ? errors.name: null"/>
                <PTextField label="Email" type="email" v-model="form.email"
                            :error="(errors.email) ? errors.email: null"/>
                <PTextField label="Phone number" v-model="form.phone"
                            :error="(errors.phone) ? errors.phone: null"/>
                <PTextField label="Address" v-model="form.address"
                            :error="(errors.address) ? errors.address: null"/>
            </PFormLayout>
        </PModal>

        <PModal
            :open="deleteModal"
            sectioned
            :secondaryActions="deleteModalData"
            title="Alert!"
            @close="changeDeleteModal"
        >
            <PHeading element="h1">Are you sure you want to delete customer?</PHeading>
        </PModal>

        <PModal
            :open="editModal"
            sectioned
            :secondaryActions="editModalData"
            title="Edit Product"
            @close="changeEditModal"
        >
            <PFormLayout>
                <PTextField v-model="form.name" label="Customer name "/>
                <PTextField v-model="form.email" label="Customer email "/>
                <PTextField v-model="form.phone" label="Customer phone "/>
                <PTextField v-model="form.address" label="Customer address "/>
            </PFormLayout>
        </PModal>


        <PModal
            sectioned
            title="Import Repair Centers"
            :primaryAction="{content: 'Save', onAction: doImport}"
            :secondaryActions="[{content:'Close', onAction: () => {openImportModel = false}}]"
            :open="openImportModel" @close="openImportModel = !openImportModel"
        >
            <PFormLayout>
                <PStack vertical>
                    <PStackItem>
                        <input type="file" name="import_file" accept=".csv" @change="onSelectFile($event)">
                    </PStackItem>

                    <PStackItem>
                        <PTextField type="email" id="email" label="Email" placeholder="Email"
                                    v-model="importForm.email">

                            <PIcon source="EmailMajor" slot="prefix"/>
                        </PTextField>
                        <PTextContainer>
                            We will send you email notification on the above email once import process will complete.
                        </PTextContainer>
                    </PStackItem>

                </PStack>
            </PFormLayout>
        </PModal>


        <PModal
            sectioned
            title="Export Repair Centers"
            :primaryAction="{content: 'Save', onAction: doExport}"
            :secondaryActions="[{content:'Close', onAction: () => {openExportModel = false}}]"
            :open="openExportModel" @close="openExportModel = !openExportModel"
        >
            <PFormLayout>
                <PTextField type="email" id="export_email" label="Email" placeholder="Email" v-model="export_email">
                    <PIcon source="EmailMajor" slot="prefix"/>
                </PTextField>
                <PTextContainer>
                    We will send you email notification on the above email once export process will complete.
                </PTextContainer>
            </PFormLayout>
        </PModal>


    </PPage>
</template>
<script>
    export default {
        name: "Customer",
        data() {
            return {
                filterOptions: [
                    {label: 'Active', value: 0},
                    {label: 'Draft', value: 1},
                ],
                PrimaryAction: {
                    content: "Add customer",
                    onAction: this.customerModal
                },
                openFilter: false,
                active: false,
                openExportModel: false,
                customermodal: false,
                deleteModal: false,
                form: {
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                },
                editId: null,
                editModal: false,
                customers: [],
                errors: {},
                deleteId: null,
                deleteModalData: [
                    {
                        content: "Cancel",
                        onAction: this.changeDeleteModal
                    }, {
                        content: "Delete Customer",
                        destructive: true,
                        onAction: this.deleteCustomer
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
                openImportModel: false,
                importForm: {},
                export_email:'',
                statusSelected: [],
                selected:[],



            }
        },
        created() {
            this.getdata();
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
                    this.axios.post('/api/customer/filter', status).then(response => {
                        this.customers = response.data;
                    })
                } else {
                    return this.getdata();
                }
            },
            filter() {
                this.openFilter = !this.openFilter
            },
            filterChange($event){

                if ($event.length > 0) {
                    console.log('fdsfgds');
                    let order = {'order': $event}
                    this.axios.post('/api/customer/statusFilter',order).then(res=>{
                        console.log(res.data);
                        console.log(res.data);
                        this.customers = res.data;
                    })
                } else {
                    return this.getdata();
                }

                console.log($event)
            },
            filterbutton(){
              this.active=!this.active;
            },
            changeDeleteModal() {
                this.deleteModal = !this.deleteModal
            },
            getdata() {
                this.axios.get('/api/customer').then(res => {

                    this.customers = res.data;
                }).catch(error => console.log(error))
            },
            customerModal() {
                this.form = {};
                this.customermodal = !this.customermodal
            },
            changeEditModal() {
                this.editModal = !this.editModal
            },
            async doImport() {
                let formData = new FormData();
                formData.append('file', this.importForm.file);
                formData.append('email', this.importForm.email);
              await  this.axios.post('/api/customer/import', formData).then(res => {
                    this.openImportModel = false;
                    console.log(res.data);
                })

            },
            async doExport() {
              await this.axios.post('/api/customer/export',{'email':this.export_email}).then(res=>{
                  this.openExportModel=false;
                  console.log(res.data);
              })

            },
            async createCustomer() {
                try {
                    let response = await this.axios.post('/api/customer', this.form)

                    this.customers.push(response.data);
                    this.customerModal();
                    this.$pLoading.finish();
                    this.$pToast.open({
                        message: 'customer add successfully',
                    });


                } catch (error) {

                    if (error.response.status === 422) {
                        console.log(error.response)
                        this.errors = {};
                        if (error.response.data.name) {
                            this.errors.name = error.response.data.name.join();
                        }
                        if (error.response.data.email) {
                            this.errors.email = error.response.data.email.join();
                        }
                        if (error.response.data.phone) {
                            this.errors.phone = error.response.data.phone.join();
                        }
                        if (error.response.data.address) {
                            this.errors.address = error.response.data.address.join();
                        }

                    }
                }
            },
            deleteCustomerId(id) {
                this.deleteModal = true;
                this.deleteId = id
            },
            deleteCustomer() {

                this.axios.delete('/api/customer/' + this.deleteId).then(res => {
                    console.log(res);
                    this.deleteModal = false;
                    if (res.data === 'success') {
                        this.$pToast.open({
                            message: 'customer delete successfully',
                        });
                    }
                    this.getdata();
                })
            },
            editProduct(item) {
                this.form.name = item.name;
                this.form.email = item.email;
                this.form.phone = item.phone;
                this.form.address = item.address;
                this.editId = item.id;
                this.editModal = true;
            },
            updateProduct() {
                this.form.id = this.editId;
                this.axios.put('/api/customer/' + this.editId, this.form).then(res => {
                    console.log(res.data);
                    this.getdata();
                })
            },
            searchFilter(value) {

                if (value) {
                    let data = {'text': value}
                    this.axios.post('/api/customer/filter', data).then(response => {
                        console.log(response);
                        this.customers = response.data;
                    })
                } else {

                    this.getdata();
                }
            },
            importcustomerdata() {
                this.openImportModel = true;

            },
            exportcustomerdata() {
                this.openExportModel= true;

            },
            onSelectFile: function (e) {
                const file = e.target.files[0];
                if (!e.target.files.length)
                    return;
                this.importForm.file = file;
                console.log(this.importForm.file);
            },
            datefilter($event){
                let data =$event
                this.axios.post('/api/customer/datefilter',data).then(res=>{
                    console.log(res);
                })
            },



        }
    }
</script>
<style>


</style>

