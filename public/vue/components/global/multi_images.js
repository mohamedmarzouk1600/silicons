Vue.component('multi-images', {
    template:`
        <div class="card-component col-sm-12">
        <div class="container-component">
            <div class="one-component col-sm-12" v-for="(row, key) in local_images">

                <input type="hidden" name="image_ids[]" :value="row.id" />

                <div class="form-group col-sm-4" v-if="!row.id">
                    <div class="controls">
                        <label :for="'image'+row.id">{{ isAr() ? 'الصورة' : 'Image' }}</label>
                        <input type="file" name="images[]" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-4" v-if="row.id">
                    <img :src="row.image" style="width: 200px;" alt="image-uploaded" />
                    <input type="hidden" name="images[]" :value="row.file_name">
                </div>

                <div class="form-group col-sm-4">
                    <div class="controls">
                        <label :for="'type'+row.id">{{ isAr() ? 'النوع' : 'Type' }}</label>
                        <select name="image_types[]" type="text" :id="'type'+row.id" class="form-control" v-model="row.type">
                            <option value="image" selected>Image</option>
                            <option value="main_image">Main image</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4 btn-delete-component pt-0">
                    <div class="controls">
                        <button class="btn btn-danger" v-on:click.prevent="removeRow(key)" :style="row.id != null ? 'margin-top: 31px' : 'margin-top: 40px'">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div class="form-group col-sm-12" id="btn-add-component">
                <div class="controls">
                    <button class="btn btn-success " v-on:click.prevent="addRow">
                        <i class="fa fa-plus"></i>
                        {{ isAr() ? 'اضافة صورة جديدة' : 'Add An Image' }}
                    </button>
                </div>
            </div>
        </div>
        </div>
    `,
    props: {
        images: [],
    },
    data: function() {
        return {
            local_images: [],
        }
    },
    created() {
    },
    mounted() {
        if(this.images) {
            this.images.forEach(image => {
                this.local_images.push({id: image.id, image: image.original_url, file_name: image.file_name, type: image.custom_properties.image_type});
            });
        }
    },
    methods : {
        addRow: function () {
            this.local_images.push({id : '', image : '', type : 'image', file_name: ''});
        },
        removeRow: function(key){
            this.local_images.splice(key, 1);
        },
        isAr: function () {
            return this.$root.$el.lang === 'ar';
        },
    }
});
