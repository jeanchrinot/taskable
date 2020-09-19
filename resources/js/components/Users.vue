<template>
  <div v-if="user_role=='admin'">
       <div class="row">
          <div class="col-12 pd-0">
            <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="form-group col-sm-4 col-md-4 filter-form filter-form--select">
                      <label for="status">Status: </label>
                      <select class="form-control form-control-sm filter" name="status" id="status">
                        <option value="all">All</option>
                        <option value="complete">Complete</option>
                        <option value="in_progress">In Progress</option>
                        <option value="not_started">Not Started</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-4 col-md-4 filter-form filter-form--select">
                      <label for="priority">Priority: </label>
                      <select class="form-control form-control-sm filter" name="priority" id="priority">
                        <option value="all">All</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-4 col-md-4 filter-form filter-form--search">
                      <label for="search" class="search-label">Search:</label>
                      
                        <input type="text" class="form-control" id="search" placeholder="Seache here...">
                      
                    </div>
                  </div>
                </div>
                <div class="card-body">
              <table v-if="pagination.total" class="table table-sm table-responsive-sm white-bg">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Banned</th>
                    <th scope="col">Joined date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <tr v-for="(user,index) in users">
                    <th scope="row">{{ index+1 }}</th>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td><span class="badge " v-bind:class="'badge-'+userStatus(user.status,2)" :id="'user_status_'+user.id">{{ userStatus(user.status,1) }}</span></td>
                    <td><span class="badge " v-bind:class="'badge-'+userBanStatus(user.banned,2)" :id="'user_ban_status_'+user.id">{{ userBanStatus(user.banned,1) }}</span></td>
                    <td>{{ formatDate(user.created_at) }}</td>
                    <td>
                      <user-list-action :action-type="'View'" :icon="'eye'" :user-id="user.id" :user-name="user.name" :color="'yellow'"></user-list-action>
                      <user-list-action :action-type="'Task'" :icon="'tasks'" :user-id="user.id" :user-name="user.name" :color="'green'"></user-list-action>
                      <!-- <user-list-action :action-type="'Ban'" :icon="'ban'" :user-id="user.id" :user-name="user.name" :color="'red'"></user-list-action> -->
                    </td>
                  </tr>
                  <tr v-if="pagination.total==0">
                    <td colspan="7">No user found.</td>
                  </tr>
                  
                </tbody>
                <tfoot>
                  <tr>
                      <td colspan="7">
                          <nav v-if="pagination.total" style="margin-top: 10px;">
                              <ul class="pagination justify-content-center">
                                  <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                                      <a class="page-link" href="#" @click="getTasks(pagination.prev_page_url)">Previous</a>
                                  </li>
                                  <li class="page-item disabled">
                                      <a class="page-link" href="#">{{ pagination.current_page }} of {{ pagination.last_page }}</a>
                                  </li>
                                  <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                                      <a class="page-link" href="#" @click="getTasks(pagination.next_page_url)">Next</a>
                                  </li>
                              </ul>
                          </nav>
                      </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          </div>
      </div>
      <view-user-modal></view-user-modal>
      <task-modal></task-modal>
      <ban-user-modal></ban-user-modal>
  </div>
</template>

<script>

  import { EventBus } from '../bus.js';

    export default {
        data() {
            return {
                users: [],
                user:{},
                pagination: {},
                url_request:'',
                search_url:'',
                result: [],
                delay: 100,
                clicks: 0,
                timer: null,
                filter:{
                  priority:'all',
                  status:'all',
                  keyword:''
                },
                userToken:localStorage.getItem('userToken') ? localStorage.getItem('userToken') : null,
                user_role:localStorage.getItem('user_role')
            };
        },
 
        created() {
          EventBus.$on('user-ban',data=>{
            this.updateUserBanStatus(data.id);
          })
            this.getUsers();
        },
        computed: {
          nonNullUsers: function() {
            return this.users.filter(function(item) {
              return item !== null;
            });
          }

        },
 
        methods: {
            getUsers(api_url) {
                let vm = this;

                //console.log(vm);

                api_url = api_url || '/api/admin/users';
                axios.get(api_url)
                .then((response) => {
                  response = response.data;
                  //console.log(this.task_title);
                        this.users = response.data;
                        // this.current_task = this.tasks[0];
                        // this.todos = response.meta.todos;
                        vm.paginator(response.meta, response.links);
                    })
                    .catch(err => console.log(err));
            },
            paginator(meta, links) {
                this.pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: (links.next) ? links.next + this.url_request : links.next,
                    prev_page_url: (links.prev) ? links.prev + this.url_request : links.prev,
                    total: meta.total
                };
            },
            filterUsers(filterType){
              
              var api_url = "/api/admin/users?f=true";

              this.url_request = '';

              if (this.filter.status!="all"){
                api_url += "&status="+this.filter.status;
                this.url_request += "&status="+this.filter.status;
              }

              if (this.filter.priority!="all") {
                api_url += "&priority="+this.filter.priority;
                this.url_request += "&priority="+this.filter.priority;
              }

              if ((this.filter.keyword).length) {
                api_url += "&search="+this.filter.keyword;
                this.url_request += "&search="+this.filter.keyword; 
              }

              this.getUsers(api_url);
            },
            formatDate(date){
              //let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
              let theDate = new Date(date);
              return theDate.toLocaleDateString("en-US"); 
            },
            formatDateTimeLocal(date) {
              let theDate = new Date(date);
              let months = theDate.getMonth()+1;
              let days = theDate.getDate();
              let hours = theDate.getHours();
              let minutes = theDate.getMinutes();
              // var ampm = hours >= 12 ? 'pm' : 'am';
              // hours = hours % 12;
              // hours = hours ? hours : 12; // the hour '0' should be '12'
              months = months < 10 ? '0'+months : months;
              days = days < 10 ? '0'+days : days;
              hours = hours < 10 ? '0'+hours : hours;
              minutes = minutes < 10 ? '0'+minutes : minutes;
              let strTime = theDate.getFullYear() +"-"+ months +"-"+ days + "T"+hours + ':' + minutes;
              return strTime;
            },
            removeParam(key, sourceURL) {
                var rtn = "",
                    param,
                    params_arr = [],
                    queryString = sourceURL;
                if (queryString !== "") {
                    params_arr = queryString.split("&");
                    for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                        param = params_arr[i].split("=")[0];
                        if (param === key) {
                            params_arr.splice(i, 1);
                        }
                    }
                    rtn = params_arr.join("&");
                }
                return rtn;
            },
            userStatus(status,index){
               var statusArr = [
                    ['0','Not activated','warning'],
                    ['1','Active','info']
                    ];
                    
              return statusArr[status][index];
            },
            updateUserStatus(id){
              $("#user_status_"+id).attr('class', 'badge badge-'+this.userStatus(2,2));
              $("#user_status_"+id).html(this.userStatus(2,1));
            },
            updateUserBanStatus(id){
              let status = $("#user_ban_status_"+id).html();
              status = status=='Banned' ? 0 : 1;
              $("#user_ban_status_"+id).attr('class', 'badge badge-'+this.userBanStatus(status,2));
              $("#user_ban_status_"+id).html(this.userBanStatus(status,1));
            },
            userBanStatus(status,index){
              var statusArr = [
                    ['0','No','light'],
                    ['1','Banned','danger']
                    ];
                    
              return statusArr[status][index];
            },
            emitGlobalEvent(eventType,data) {
              EventBus.$emit(eventType,data);
            } 
        }
    };
</script>