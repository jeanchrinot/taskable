<template>
    <div>
        <div class="card">
            <div class="card-header">Login</div>

            <div class="card-body">
                <form @submit.prevent="login" novalidate>
                    <div class="form-group row">
                        <div v-if="errors.auth!=''" class="alert alert-danger centered" role="alert">
                          <strong>Error!</strong> {{ errors.auth }}
                          <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-mail address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" v-bind:class="[{'is-invalid':errors.email!=''}]" name="email" v-model="user.email" required autocomplete="email" autofocus>

                            
                                <span v-if="errors.email!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.email }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" v-bind:class="[{'is-invalid':errors.password!=''}]" name="password" v-model="user.password" required autocomplete="current-password">

                            
                                <span v-if="errors.password!=''" class="invalid-feedback" role="alert">
                                    <strong>{{ errors.password }}</strong>
                                </span>
                            
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" v-model="user.remember_me">

                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>

                            
                                <a class="btn btn-link" href="">
                                    Forgot Your Password?
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
                  email:'',
                  password:'',
                  remember_me:false
                },
                errors:{
                    email:'',
                    password:'',
                    auth:''
                }
            };
        },
 
        created() {
            
        },
        computed: {

        },
 
        methods: {
            login(){
                // alert(this.user.name+" "+this.user.password);
                var api_url = '/api/auth/login';
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
                        this.errors.email = errors.email ? errors.email : '';
                        this.errors.password = errors.password ? errors.password : '';
                        this.errors.auth = errors.auth ? errors.auth : '';
                    }
                    else{
                        if (response.access_token) {
                            localStorage.setItem('userToken', response.access_token);
                            localStorage.setItem('user',JSON.stringify(response.user));
                            localStorage.setItem('user_task_number',response.user_task_number);
                            localStorage.setItem('user_role',response.user_role);
                            //console.log(localStorage.getItem('userToken'));
                            window.location = '/app/dashboard';
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