<template>
    <div class="modal fade" id="TaskModal" tabindex="-1" role="dialog" aria-labelledby="TaskModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="TaskModalLabel">Showing task list for : {{ user_name }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
               <div  class="row">
                  <div class="col-md-12 no-padding">
                      <div class="card">
                        <div class="card-header">
                          <div class="row">
                            <div class="form-group col-sm-4 col-md-4 filter-form filter-form--select">
                              <label for="status">Status: </label>
                              <select class="form-control form-control-sm filter" name="status" id="userTaskStatus" @change="filterTask" v-model="filter.status">
                                <option value="all">All</option>
                                <option value="complete">Complete</option>
                                <option value="in_progress">In Progress</option>
                                <option value="not_started">Not Started</option>
                              </select>
                            </div>
                            <div class="form-group col-sm-4 col-md-4 filter-form filter-form--select">
                              <label for="priority">Priority: </label>
                              <select class="form-control form-control-sm filter" name="priority" id="userTaskPriority" @change="filterTask" v-model="filter.priority">
                                <option value="all">All</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                              </select>
                            </div>
                            <div class="form-group col-sm-4 col-md-4 filter-form filter-form--search">
                              <label for="search" class="search-label">Search:</label>
                              
                              <input type="text" class="form-control" id="userTaskSearch" v-model="filter.keyword" placeholder="Seache here..." @change="filterTask">
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="row" v-if="pagination.total">
                            <div class="col-md-6 col-sm-12 pd-0">
                              <div class="task-title" id="task-title">
                                <div class="pull-left"><span><i class="fa fa-tasks"></i>  {{ task_title }}</span></div>
                              </div>
                              <div class="task-list" id="task-list">
                                <ul class="todo-list text-left">
                                    <li v-for="task in nonNullTasks" v-bind:id="'task_'+task.id" v-bind:key="task.id" v-bind:class="[{current:isCurrent(task.id)}]" class="edit-item-icon-parent">
                                      <div class="list-item" role="button" @click="getTodos(task.id)">
                                      <span class="list-style" v-bind:class="'list-style--'+taskPriority(task.priority,2)"></span> <span class="todo-list__text" v-bind:id="'task_name_'+task.id">{{ task.name }}</span>
                                      <span class="badge" v-bind:class="'badge-'+taskStatus(task.status,2)" style="float: right;margin-right: 8px;margin-top: 3px;" v-bind:id="'status_'+task.id">{{ taskStatus(task.status,1) }} {{ task.complete+'/'+task.count}}</span>
                                      </div>
                                    </li>
                                </ul>
                              </div>
                              <!-- <div v-if="pagination.total==0">
                                <span>This user doesn't have any task.</span>
                              </div>
 -->                              <div style="width: 100%;float: left;padding: 10px;" id="pagination">
                              <nav v-if="pagination.total">
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
                            </div>
                            </div>
                             <div class="col-md-6 col-sm-12 pd-0">
                              <div class="task-name">
                                <span v-if="current_task"><i class="fa fa-align-left"></i> {{ current_task.name }} </span> <span class="task-date" v-if="current_task"><i class="fa fa-calendar"></i>  {{ formatDate(current_task.start_date) }} - {{ formatDate(current_task.end_date) }}</span>
                              </div>
                              <div class="todo-list">
                                <ul class="todo-list text-left">
                                    <li v-for="todo in nonNullTodos" v-bind:key="todo.id" :id="'todo_'+todo.id" class="edit-item-icon-parent">
                                      <div class="list-item">
                                        <label :for="todo.id" class="list-label" role="button"> <input type="checkbox" v-model="todo.complete" :id="todo.id" onclick="return false;"> <span class="todo-list__text" v-bind:class="[{'todo-list__item-checked': todo.complete}]" :id="'todo_name_'+todo.id">{{ todo.name }}</span></label>
                                      </div>
                                    </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div v-if="pagination.total == 0">
                            <span>No task found.</span>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>

    import { EventBus } from '../bus.js';

    export default {
        data() {
            return {
                user_id:'',
                add_new_task:false,
                tasks: [],
                current_task:{},
                pagination: {},
                url_request:'',
                search_url:'',
                todos:[],
                todo: {
                    id: '',
                    name: '',
                    task_id:''
                },
                task_id: '',
                task_title:'My Tasks',
                result: [],
                delay: 100,
                timer: null,
                filter:{
                  priority:'all',
                  status:'all',
                  keyword:''
                },
                userToken:localStorage.getItem('userToken') ? localStorage.getItem('userToken') : null,
                user_name:''
            };
        },
 
        created() {
            EventBus.$on('task-button-click', data => {
                  this.resetFilterForm();
                  this.url_request = '&id='+data.id;
                  this.user_id = data.id;
                  this.getTasks('/api/tasks?id='+data.id);
                  this.user_name = data.user_name;
                });
        },
        computed: {
          nonNullTasks: function() {
            return this.tasks.filter(function(item) {
              return item !== null;
            });
          },
          nonNullTodos: function() {
            return this.todos.filter(function(item) {
              return item !== null;
            });
          }

        },
 
        methods: {
            getTasks(api_url) {
                let vm = this;



                api_url = api_url || '/api/tasks';
                axios.get(api_url)
                .then((response) => {
                  response = response.data;
                  //console.log(this.task_title);
                        this.tasks = response.data;
                        this.current_task = this.tasks[0];
                        this.todos = response.meta.todos;
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
             getTodos(id=null) {
               id = (id==null) ? this.current_task.id : id;
                axios.get('/api/todos/' + id)
                    .then(response => {
                        response = response.data;
                        this.todos = response.data;
                        this.current_task = response.meta.current_task;
                    })
                    .catch(err => console.log(err));
            },
            taskStatus(status,index){
              var statusArr = [
              ['0','Not started','warning'],
              ['1','In progress','info'],
              ['2','Complete','success'],
              ['3','Canceled','danger']
              ];
              
              return statusArr[status][index];
            },
            taskPriority(priority,index){
              var priorityArr = [
              ['1','Low','light'],
              ['2','Medium','warning'],
              ['3','High','danger']
              ];
              
              // console.log(priorityArr[priority-1][index]);
              return priorityArr[priority-1][index];
            },
            isCurrent(id){
              if(this.current_task.id==id){
                return true;
              }
              return false;
            },
            filterTask(filterType){
              
              var api_url = "/api/tasks?id="+this.user_id;

              this.url_request = '&id='+this.user_id;

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

              this.getTasks(api_url);
            },
            formatDate(date){
              //let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
              let theDate = new Date(date);
              return theDate.toLocaleDateString("en-US"); 
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
            emitGlobalEvent(eventType,data) {
              EventBus.$emit(eventType,data);
            },
            resetFilterForm(){
              this.filter.priority='all';
              this.filter.status = 'all';
              this.filter.keyword = '';
            }
        }
    };
</script>