<template>
    <div>
            <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Parameters -->
                <!-- <li class="nav-item" v-if="loggedIn">
                    <a class="nav-link nav-link--icon color-green" href="#"><i class="fa fa-bell"></i></a>
                </li>
                <li class="nav-item" v-if="loggedIn">
                    <a class="nav-link nav-link--icon color-green" href="#"><i class="fa fa-cog"></i></a>
                </li> -->

            <!-- Authentication Links -->
            
                <li class="nav-item" v-if="!loggedIn">
                    <a class="nav-link" href="/auth/login">Login</a>
                </li>
                <li class="nav-item" v-if="!loggedIn">
                    <a class="nav-link" href="/auth/signup">Register</a>
                </li>
                
                <li class="nav-item dropdown" v-if="loggedIn">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div style="float: left;margin-top: -6px;">
                            <img :src="image" style="width: 35px;border-radius: 50%;">
                        </div>
                        <div style="float: left;padding-left: 10px;">
                            {{ user.name }} <span class="caret"></span>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/auth/logout"
                           @click.prevent="logout">
                            Logout
                        </a>
                    </div>
                </li>
        </ul>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                loggedIn:localStorage.getItem('userToken') ? true : false,
                user:JSON.parse(localStorage.getItem('user')),
                image:''
            }
        },
        created(){
            this.getImage();
        },
        computed:{

        },
        methods:{
            logout(){
                axios.get('/api/auth/logout')
                .then(response=>{
                    if (response.data.success==true) {
                        localStorage.removeItem('userToken');
                        this.loggedIn = false;
                        window.location.replace('/auth/login');
                    }
                    else{
                        $('#error-toast').find('.toast-body').html('Logout failed. An error occured.'); 
                        $('#error-toast').toast('show');
                    }
                })
                .catch(err=>{
                    $('#error-toast').find('.toast-body').html('Logout failed. An error occured.'); 
                    $('#error-toast').toast('show');
                    console.log(err);
                });
            },
            getImage(){
                axios.get('/api/user/profile/show')
                .then(response => {
                    response = response.data;
                    this.image = response.data.image;
                })
                .catch(err => console.log(err));
              }
        }
    }
</script>