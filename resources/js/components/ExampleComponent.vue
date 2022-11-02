<template>
    <div class="container">
        <form @submit.prevent="create" v-if="form.id == undefined">
            Create
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="inputEmail4">Name</label>
                    <input type="text" class="form-control" v-model="form.name" id="inputEmail4" placeholder="name" />
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">sku</label>
                    <input type="text" class="form-control" v-model="form.sku" id="inputPassword4" placeholder="Sku" />
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">Image</label>
                    <input type="file" class="form-control" @change="AddFile" id="inputPassword4" placeholder="Sku" />
                </div>

                <div class="form-group col-md-3">
                    <label for="inputPassword6">price</label>
                    <input type="text" class="form-control" v-model="form.price" id="inputPassword6"
                        placeholder="Price" />
                </div>

                <div class="form-group col-md-3">
                    <label for="inputPassword7">Status</label>
                    <input type="checkbox" value="true" v-model="form.status" id="inputPassword7" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>

        <form @submit.prevent="update" v-if="form.id">
            Update
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="inputEmail4">Name</label>
                    <input type="text" class="form-control" v-model="form.name" id="inputEmail4" placeholder="name" />
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">sku</label>
                    <input type="text" class="form-control" v-model="form.sku" id="inputPassword4" placeholder="Sku" />
                </div>
                <div class="form-group col-md-3">
                    <label for="inputPassword4">Image</label>
                    <input type="file" class="form-control" @change="AddFile" id="inputPassword4" placeholder="Sku" />
                </div>

                <div class="form-group col-md-3">
                    <label for="inputPassword6">price</label>
                    <input type="text" class="form-control" v-model="form.price" id="inputPassword6"
                        placeholder="Price" />
                </div>

                <div class="form-group col-md-3">
                    <label for="inputPassword7">Status</label>
                    <input type="checkbox" value="true" v-model="form.status" id="inputPassword7" />
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>

        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Sku</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in items" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td><img width="100" :src="`/images/${item.image}`"></td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.sku }}</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.status == 'true' ? 'published' :'unpublished' }}</td>
                        <td>
                            <button class="btn btn-primary" type="button" @click="edit(item)">Edit</button>
                            <button class="btn btn-primary" type="button" @click="destroy(item)">delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],
                form: {
                    name: "",
                    image: "none for now",
                    sku: "",
                    price: "",
                    status: 'false',
                },
            };
        },
        created() {
            this.getProduct();
        },
        methods: {
            AddFile($event){

                this.form.image =  $event.target.files[0];
            },
            getProduct() {
                {
                    axios.get("/api/product").then((res) => {
                        this.items = res.data.data;
                    });
                }
            },
            create() {
                let formData =  new FormData();
                formData.append('image',this.form.image)
                formData.append('name',this.form.name)
                formData.append('sku',this.form.sku)
                formData.append('price',this.form.price)
                formData.append('status',this.form.status)
                axios
                    .post("/api/product", formData)
                    .then((res) => {
                        this.items.unshift(res.data.data);
                        this.form.name = '';
                        this.form.sku = '';
                        this.form.price = '';
                        this.form.status = '';
                    });
            },
            edit(item) {

                this.form = {...item};
            },
            update() {
                // file upload will be uploaded by the post method so skip for edit i have done in create product.
                axios
                    .put(`/api/product/${this.form.id}`, this.form)
                    .then((res) => {
                        let index =  this.items.findIndex(x => x.id == this.form.id);
                        this.items[index].name =  this.form.name;
                        this.items[index].sku =  this.form.sku;
                        this.items[index].price =  this.form.price;
                        this.items[index].status =  this.form.status;
                        // this.items.unshift();
                    });
            },
            destroy(item) {
                axios
                    .delete(`/api/product/${item.id}`)
                    .then((res) => {
                        let index =  this.items.findIndex(x => x.id == item.id);
                        this.items.splice(index,1);
                    });
            },
        },
    };

</script>
