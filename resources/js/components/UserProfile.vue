<template>
	<div>
		<div class="card card--sh">
            <div class="card-header sm-pd">
              <div class="pull-left"><h2 class="card-header__title">My Profile</h2></div>
              <div class="pull-right">
                <button type="button" v-if="!edit_profile" class="btn btn-primary btn-sm" @click="addForm()"><i class="fa fa-pencil"></i> Edit Profile</button>
              </div>
            </div>
            <div class="card-body">
              <img :src="profile.image" style="width: 50%;margin:auto;" id="profile_image">
            </div>
            <div v-if="edit_profile">
                 <form @submit.prevent="updateProfile" enctype="multipart/form-data">
                      <div class="row">
                        <div class="form-group col-md-12">
                          <input type="file" @change="onFileChange" name="image" id="image_file" class="form-control form-control-sm"/>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary btn-sm" style="margin-right:20px;">Save</button>
                          <button type="button" class="btn btn-secondary btn-sm" @click="cancelEdit">Cancel</button>
                        </div>
                      </div>
                  </form>
              </div>
          </div>
	</div>
</template>

<script>
  import { EventBus } from '../bus.js';
  export default{
    data(){
      return{
        edit_profile:false,
        profile:{},
        image:null,
        current_image:'',
        test:{
          image:'fdghdfg'
        }
      }
    },
    created(){
      this.getProfile();
    },
    methods:{
      getProfile(){
        axios.get('/api/user/profile/show')
        .then(response => {
            response = response.data;
            this.profile = response.data;
            this.current_image = this.profile.image;
        })
        .catch(err => console.log(err));
      },
      addForm(){
        this.edit_profile = true;
      },
      cancelEdit(){
        this.edit_profile = false;
        this.removeImage();
        $('#profile_image').attr('src', this.current_image);
      },
      updateProfile(){

        // console.log(this.image);

        const data = new FormData();
        data.append('image', this.image);

        console.log(data);

        axios.put('/api/user/profile/update',data,{
          headers: {
            'Content-Type': 'multipart/form-data',
            'X-Requested-With' : 'XMLHttpRequest'
          }
        })
        .then(response=>{

          console.log(response);

          // if (response.data) {
          //     response = response.data;

          //     if (response.success==true) {
                  
          //         alert('OK');
          //     }
          //     else{
          //       console.log('Error');
          //     }
          // }
          // else{
          //   const errors = Object.values(response)
          //   for (var i = errors.length - 1; i >= 0; i--) {
          //     // console.log(errors[i][0]);
          //     $('#error-toast').find('.toast-body').html(errors[i][0]);
          //     $('#error-toast').toast('show');
          //   }
          // }
          
        })
        .catch(err=>{
          console.log(err);
        })
      },
      onFileChange(e) {
        this.image = e.target.files[0];

        var files = e.target.files || e.dataTransfer.files;
        if (!files.length)
          return;
        this.createImage(files[0]);
      },
      createImage(file) {
        var image = new Image();
        var reader = new FileReader();
        var vm = this;

        reader.onload = (e) => {
          // vm.image = e.target.result;
          $('#profile_image').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
      },
      removeImage: function (e) {
        this.image = '';
      }
    }
  }
</script>