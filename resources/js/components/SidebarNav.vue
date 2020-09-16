<template>
    <div>
        <nav class="sidebar__nav">
            <ul class="sidebar__menu">
                <li class="title--small">
                    MAIN NAVIGATION
                </li>
                <li class="active">
                    <a :href="links.dashboard" class="menu-toggle">
                        <i class="fa fa-bar-chart"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a :href="links.profile">
                        <i class="fa fa-user"></i>
                        <span class="nav-label">Profile</span>
                    </a>
                </li>
                <li v-if="user_role=='admin'">
                    <a :href="links.users">
                        <i class="fa fa-users"></i>
                        <span class="nav-label">Users</span>
                    </a>
                </li>
                
            </ul>
        </nav>
    </div>
</template>

<script>
    export default{
        props:['routes'],
        data(){
            return{
                user_role:localStorage.getItem('user_role'),
                links:{}
            }
        },
        created(){
            this.links = JSON.parse(this.routes);
        },
        methods:{

        }
    }
</script>