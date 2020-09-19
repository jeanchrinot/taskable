<template>
    <div>
        <div class="card">
            <div class="card-header">Login</div>

            <div class="card-body">
                <form @submit.prevent="signup" novalidate>
                    <div class="form-group row">
                        <div v-if="errors.auth!=''" class="alert alert-danger centered" role="alert">
                          <strong>Error!</strong> {{ errors.auth }}
                          <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button> -->
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Full name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" v-bind:class="[{'is-invalid':errors.name!=''}]" name="name" v-model="user.name" required autofocus>

                            
                                <span v-if="errors.name!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.name }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-mail address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" v-bind:class="[{'is-invalid':errors.email!=''}]" name="email" v-model="user.email" required>

                            
                                <span v-if="errors.email!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.email }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" v-bind:class="[{'is-invalid':errors.password!=''}]" name="password" v-model="user.password" required>

                            
                                <span v-if="errors.password!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.password }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control" v-bind:class="[{'is-invalid':errors.password_confirmation!=''}]" name="password_confirmation" v-model="user.password_confirmation" required>

                            
                                <span v-if="errors.password_confirmation!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.password_confirmation }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Sign up
                            </button>

                            
                                <a class="btn btn-link" href="/auth/login">
                                    Already have an account ?
                                </a>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user:{
                  name:'',
                  email:'',
                  password:'',
                  password_confirmation:''
                },
                errors:{
                    name:'',
                    email:'',
                    password:'',
                    password_confirmation:'',
                    auth:''
                }
            };
        },
 
        created() {
            
        },
        computed: {

        },
 
        methods: {
            signup(){
                // alert(this.user.name+" "+this.user.password);
                var api_url = '/api/auth/signup';
                fetch(api_url, {
                    method: 'post',
                    body: JSON.stringify(this.user),
                    headers: {
                        'content-type': 'application/json',
                        'X-Requested-With' :'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(response => {

                    if(response.errors){
                        let errors = response.errors;
                        this.errors.name = errors.name ? errors.name : '';
                        this.errors.email = errors.email ? errors.email : '';
                        this.errors.password = errors.password ? errors.password : '';
                        this.errors.password_confirmation = errors.password_confirmation ? errors.password_confirmation : '';
                        this.errors.auth = errors.auth ? errors.auth : '';
                    }
                    else{
                        if (response.success==true) {
                            // Redirect to Login Page
                            window.location = '/auth/login?signedup=true';
                        }
                        else{
                            this.errors.auth = "Unexpected error occured. Please try again later.";
                        }
                    }
                    
                })
                .catch(err => console.log(err));
            }
        }
    };
</script>