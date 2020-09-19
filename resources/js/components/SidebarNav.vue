<template>
    <nav class="sidebar__nav sidebar__nav--expanded">
            <ul class="sidebar__menu sidebar__menu--visible">
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