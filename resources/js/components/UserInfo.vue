<template>
	<div>
		<div class="card card--sh">
            <div class="card-header sm-pd">
	            <div class="pull-left"><h2 class="card-header__title">My Information</h2></div>
	            <div class="pull-right">
	              <button type="button" v-if="!edit_info" class="btn btn-primary btn-sm" @click="addInfoForm()"><i class="fa fa-pencil"></i> Edit Info</button>
	            </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <img :src="profile.image" style="width: 100%;margin:auto;" id="profile_image">
                </div>
                <div class="col-md-9">
                  <table v-if="!edit_info" class="table table-sm table-striped">
                      <!-- <thead>
                        <tr>
                          <th scope="col">Properties</th>
                          <th scope="col">Value</th>
                        </tr>
                      </thead> -->
                      <tbody>
                        <tr>
                          <td>Name</td>
                          <td>{{ user.name }}</td>
                        </tr>
                        <tr>
                          <td>Email</td>
                          <td>{{ user.email }}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td><span class="badge " :id="'user_status'"></span></td>
                        </tr>
                        <tr>
                          <td>Joined on</td>
                          <td>{{ formatDate(user.created_at) }}</td>
                        </tr>
                      </tbody>
                    </table>
                    <div v-if="edit_info">
                       <form @submit.prevent="editInfo">
                          <div class="row">
                            <div class="form-group col-md-12">
                              <input type="text" v-model="info.name" placeholder="Type your full name" name="name" id="info_name" class="form-control form-control-sm"/>
                            </div>
                            <div class="form-group col-md-12">
                              <input type="text" v-model="info.email" placeholder="Type your email address" name="email" id="info_name" class="form-control form-control-sm"/>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12">
                              <button type="submit" class="btn btn-primary btn-sm" style="margin-right:20px;">Save</button>
                              <button type="button" class="btn btn-secondary btn-sm" @click="cancelEditInfo">Cancel</button>
                            </div>
                          </div>
                      </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</template>

<script>
  import { EventBus } from '../bus.js';
  export default{
    data(){
      return{
        user:{},
        edit_info:false,
        info:{
        	name:'',
        	email:''
        },
        edit_image:false,
        profile:{},
        image:null,
        current_image:'',
      }
    },
    created(){
      this.getUser();
      this.getImage();
    },
    methods:{
      getUser(){
        axios.get('/api/user/info/show')
        .then(response => {
            response = response.data;
            this.user = response.data;
            this.info.name = this.user.name;
            this.info.email = this.user.email;
            this.updateUserStatus();
        })
        .catch(err => console.log(err));
      },
      userStatus(status,index){
         var statusArr = [
              ['0','Not activated','warning'],
              ['1','Active','info']
              ];
              
        return statusArr[status][index];
      },
      updateUserStatus(){
      	$("#user_status").attr('class', 'badge badge-'+this.userStatus(this.user.status,2));
        $("#user_status").html(this.userStatus(this.user.status,1));
      },
      formatDate(date){
        //let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        let theDate = new Date(date);
        return theDate.toLocaleDateString("en-US"); 
      },
      addInfoForm(){
      	this.edit_info = true;
      },
      editInfo(){
      	axios.put('/api/user/info/update',{
      		body:this.info
      	})
      	.then(response => {
          response = response.data;
          // console.log(response);
          if(response.data){
            // this.clearForm();
            this.user = response.data;
            this.edit_info = false;
            var checkExist = setInterval(()=> {
				   if ($('#user_status').length) {
				      this.updateUserStatus();
				      clearInterval(checkExist);
				   }
				}, 100); // check every 100ms
          }
          else{
            const errors = Object.values(response)
            for (var i = errors.length - 1; i >= 0; i--) {
              // console.log(errors[i][0]);
              $('#error-toast').find('.toast-body').html(errors[i][0]);
              $('#error-toast').toast('show');
            }
          }
            
        })
        .catch(err => console.log(err));
      },
      cancelEditInfo(){
      	this.edit_info = false;

      	var checkExist = setInterval(()=> {
    		   if ($('#user_status').length) {
    		      this.updateUserStatus();
    		      clearInterval(checkExist);
    		   }
    		}, 100); // check every 100ms
      },
      getImage(){
        axios.get('/api/user/profile/show')
        .then(response => {
            response = response.data;
            this.profile = response.data;
            this.current_image = this.profile.image;
        })
        .catch(err => console.log(err));
      },
      addImageForm(){
        this.edit_profile = true;
      },
      cancelEditImage(){
        this.edit_profile = false;
        this.removeImage();
        $('#profile_image').attr('src', this.current_image);
      },
      updateImage(){

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