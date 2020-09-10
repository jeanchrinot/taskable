<template>
  <div>
      <div class="row">
          <div class="col-md-12 no-padding">
              <div class="card">
                <div class="card-header">
                  <div class="row">
                    <div class="form-group col-md-4 filter-form">
                      <label for="status">Filter by status: </label>
                      <select class="form-control form-control-sm filter" name="status" id="status" @change="filterTask" v-model="filter.status">
                        <option value="all">All</option>
                        <option value="complete">Complete</option>
                        <option value="in_progress">In Progress</option>
                        <option value="not_started">Not Started</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 filter-form">
                      <label for="priority">Filter by Priority: </label>
                      <select class="form-control form-control-sm filter" name="priority" id="priority" @change="filterTask" v-model="filter.priority">
                        <option value="all">All</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                      </select>
                    </div>
                    <div class="form-group col-md-4 filter-form">
                      <label for="search" class="search-label">Search :</label>
                      
                      <input type="text" class="form-control" id="search" v-model="filter.keyword" placeholder="Seache here..." @change="filterTask">
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row" v-if="pagination.total">
                    <div class="col-md-6 col-sm-12">
                      <div class="task-name">
                        <span v-if="current_task"><i class="fa fa-align-left"></i> {{ current_task.name }} </span> <span class="task-date" v-if="current_task"><i class="fa fa-calendar"></i>  {{ formatDate(current_task.start_date) }} - {{ formatDate(current_task.end_date) }}</span>
                      </div>
                      <div class="todo-list">
                        <form @submit.prevent="addTodo">
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Add item to list..." aria-label="Add item to list" aria-describedby="basic-addon2" v-model="todo.name">
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-plus"></i> Add</button>
                            </div>
                          </div>
                        </form>
                        <ul class="todo-list text-left">
                            <li v-for="todo in nonNullTodos" v-bind:key="todo.id" :id="'todo_'+todo.id" class="edit-item-icon-parent">
                              <div class="list-item">
                                <label :for="todo.id" class="list-label" role="button"> <input type="checkbox" v-model="todo.complete" :id="todo.id" @click="toggleComplete(todo.id)"> <span class="todo-list__text" v-bind:class="[{'todo-list__item-checked': todo.complete}]" :id="'todo_name_'+todo.id">{{ todo.name }}</span></label>
                              </div>
                              <div class="list-action">
                                <i class="fa fa-pencil edit-item-icon" @click="editTodo(todo.id)"></i>
                                <delete-button :item-id="todo.id" :item-type="'todo'"></delete-button>
                              </div>
                            </li>
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="task-title" id="task-title">
                        <div class="pull-left"><span><i class="fa fa-tasks"></i>  {{ task_title }}</span></div>
                        <div class="pull-right">
                          <button type="button" v-if="!add_new_task" class="btn btn-primary btn-sm add-task-btn" @click="addTaskForm()"><i class="fa fa-plus"></i> Add new task</button>
                        </div>
                      </div>
                      <div v-if="add_new_task || update_task" class="task-list">
                          <form @submit.prevent="addTask(task.id)">
                            <div class="row">
                              <div class="form-group col-md-8">
                                <input type="text" v-model="task.name" placeholder="Type task list name..." name="name" id="new_task_name" class="form-control form-control-sm"/>
                              </div>
                              <div class="form-group col-md-4">
                                <select name="priority" class="form-control form-control-sm" id="new_task_priority" v-model="task.priority"> 
                                  <option value="">Priority</option>
                                  <option value="1">Low</option>
                                  <option value="2">Medium</option>
                                  <option value="3">High</option>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="input-group input-group-sm col-md-6 mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon1">Start Date</span>
                                </div>
                                <input type="datetime-local" :value="formatDateTimeLocal(task.start_date)" class="form-control datetime" placeholder="Start Date" aria-label="Start Date" aria-describedby="basic-addon1" id="new_task_start_date" @change="bindTaskDate('start_date')">
                              </div>
                              <div class="input-group input-group-sm col-md-6 mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon2">End Date</span>
                                </div>
                                <input type="datetime-local" :value="formatDateTimeLocal(task.end_date)" class="form-control datetime" placeholder="End Date" aria-label="End Date" aria-describedby="basic-addon2" id="new_task_end_date" @change="bindTaskDate('end_date')">
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-sm" style="margin-right:20px;">Save</button>
                                <button type="button" class="btn btn-secondary btn-sm" @click="cancelNewTask">Cancel</button>
                              </div>
                            </div>
                          </form>
                      </div>
                      <div v-else class="task-list" id="task-list">
                        <ul class="todo-list text-left">
                            <li v-for="task in nonNullTasks" v-bind:id="'task_'+task.id" v-bind:key="task.id" v-bind:class="[{current:isCurrent(task.id)}]" class="edit-item-icon-parent">
                              <div class="list-item" role="button" @click="oneClick($event,task.id)">
                              <span class="list-style" v-bind:class="'list-style--'+taskPriority(task.priority,2)"></span> <span class="todo-list__text" v-bind:id="'task_name_'+task.id">{{ task.name }}</span>
                              <span class="badge" v-bind:class="'badge-'+taskStatus(task.status,2)" style="float: right;margin-right: 8px;margin-top: 3px;" v-bind:id="'status_'+task.id">{{ taskStatus(task.status,1) }} {{ task.complete+'/'+task.count}}</span>
                              </div>
                              <div class="list-action">
                                <i class="fa fa-pencil edit-item-icon color-green" @click="addTaskForm(task.id)"></i>
                                <delete-button :item-id="task.id" :item-type="'task'"></delete-button>
                              </div>
                            </li>
                        </ul>
                      </div>
                      <div v-if="!add_new_task && !update_task" style="width: 100%;float: left;padding: 10px;" id="pagination">
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
                  </div>
                  <div v-if="pagination.total == 0">
                    <span>No task found.</span>
                  </div>
                </div>
              </div>
          </div>
      </div>
      <!-- Modal -->
        <div class="modal modal--small fade" id="deleteListModal" tabindex="-1" role="dialog" aria-labelledby="deleteListModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteListModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Are you sure to delete this list?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger btn-sm" id="confirm-delete">Delete</button>
              </div>
            </div>
          </div>
        </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
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
                update_task: false,
                update_todo:false,
                task_id: '',
                task_new_name:{
                  id:'',
                  name:''
                },
                todo_new_name:{
                  id:'',
                  name:''
                },
                task_title:'My Tasks',
                task:{
                  id:'',
                  name:'',
                  priority:'',
                  start_date:'',
                  end_date:'',
                  complete:'',
                  count:''
                },
                taskClickCounter:0,
                result: [],
                delay: 100,
                clicks: 0,
                timer: null,
                filter:{
                  priority:'all',
                  status:'all',
                  keyword:''
                }
            };
        },
 
        created() {
            this.getTasks();
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
                fetch(api_url)
                    .then(response => response.json())
                    .then(response => {
                        this.tasks = response.data;
                        this.current_task = this.tasks[0];
                        this.todos = response.meta.todos;
                        vm.paginator(response.meta, response.links);
                    })
                    .catch(err => console.log(err));
            },
             getTask(id) {
                let api_url = '/api/task/'+id;
                fetch(api_url)
                    .then(response => response.json())
                    .then(response => {
                        this.task = response.data;
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
            addTodo() {
                if (this.update_todo === false) {
                    fetch('/api/todo/'+this.current_task.id, {
                        method: 'post',
                        body: JSON.stringify(this.todo),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(response => {
                          if(response.data){
                            this.clearTodoForm();
                            this.todos = response.data;
                            this.current_task = response.meta.current_task;
                            this.updateTaskStatus(this.current_task.id,this.current_task.status);
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
                } else {
                    fetch('/api/todo', {
                        method: 'put',
                        body: JSON.stringify(this.task),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.clearTodoForm();
                            this.getTasks();
                        })
                        .catch(err => console.log(err));
                }
            },
             getTodos(id=null) {
               id = (id==null) ? this.current_task.id : id;
                fetch('/api/todos/' + id)
                  .then(response => response.json())
                    .then(response => {
                        this.todos = response.data;
                        this.current_task = response.meta.current_task;
                    })
                    .catch(err => console.log(err));
            },
            updateTask(task) {
                this.update_task = true;
                this.task.id = task.id;
                this.task.name = task.name;
                this.task.priority = task.priority;
                this.task.start_date = task.start_date;
                this.task.end_date = task.end_date;
            },
            clearTodoForm() {
                this.todo.id = null;
                this.todo.task_id = null;
                this.todo.name = '';
            },
            toggleComplete(id){
              fetch('/api/todo/toggle/' + id, {
                    method: 'post'
                })
                    .then(response => response.json())
                    .then(response => {
                       // this.getTasks('/api/tasks?page='+this.pagination.current_page);
                       this.current_task = response.data; 
                       this.updateTaskStatus(this.current_task.id,this.current_task.status);
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
            updateTaskStatus(id,status){
              const complete = this.current_task.complete;
              const count = this.current_task.count;
              const status_name = this.taskStatus(status,1);

              $("#status_"+id).attr('class', 'badge badge-'+this.taskStatus(status,2));
              $("#status_"+id).html(status_name+' '+complete+'/'+count);
            },
            editTaskName(id){
              var task_name = $('#task_name_'+id); 
              var name = $('#task_name_'+id).text();
              task_name.html('');
              $('<input></input>')
                  .attr({
                      'type': 'text',
                      'name': 'name',
                      'id': 'task_name_input_'+id,
                      'size': '30',
                      'value': name
                  })
                  .appendTo('#task_name_'+id);
              $('#task_name_input_'+id).focus();

              $(document).on('blur','#task_name_input_'+id, ()=>{
                  var name = $('#task_name_input_'+id).val();
                  // console.log(name);
                  this.task_new_name.id = id;
                  this.task_new_name.name = name;

                  fetch('/api/task/'+id, {
                        method: 'put',
                        body: JSON.stringify(this.task_new_name),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                      if(response.data){
                        $('#task_name_'+id).text(name);
                        this.current_task.name = name;
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
                });
            },
            editTodo(id){
              var todo_name = $('#todo_name_'+id); 
              var name = $('#todo_name_'+id).text();
              todo_name.html('');
              $('<input></input>')
                  .attr({
                      'type': 'text',
                      'name': 'name',
                      'id': 'todo_name_input_'+id,
                      'size': '30',
                      'value': name
                  })
                  .appendTo('#todo_name_'+id);
              $('#todo_name_input_'+id).focus();

              $(document).on('blur','#todo_name_input_'+id, ()=>{
                  var name = $('#todo_name_input_'+id).val();
                  // console.log(name);
                  this.todo_new_name.id = id;
                  this.todo_new_name.name = name;

                  fetch('/api/todo/'+id, {
                        method: 'put',
                        body: JSON.stringify(this.todo_new_name),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(response => {
                      if(response.data){
                        $('#todo_name_'+id).text(name);
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
                });
            },
            filterTask(filterType){
              
              var api_url = "/api/tasks?f=true";

              this.url_request = '';

              if (this.filter.status!="all"){
                api_url += "&status="+this.filter.status;
                this.url_request += "&status="+this.filter.status;
              }
              // else{
              //   this.url_request = this.removeParam("status",this.url_request);
              // }

              if (this.filter.priority!="all") {
                api_url += "&priority="+this.filter.priority;
                this.url_request += "&priority="+this.filter.priority;
              }
              // else{
              //   this.url_request = this.removeParam("priority",this.url_request);
              // }

              if ((this.filter.keyword).length) {
                api_url += "&search="+this.filter.keyword;
                this.url_request += "&search="+this.filter.keyword; 
              }
              // else{
              //   this.url_request = this.removeParam("search",this.url_request);
              // }

              // alert(api_url);

              // if (status!="all" && priority!="all") {
              //   api_url = api_url + "?priority="+priority+"&status="+status;
              //   this.url_request = this.url_request+"&priority="+priority+"&status="+status;
              // }
              // else if (status!="all" && priority=="all") {
              //   api_url = api_url + "?status="+status;
              //   this.url_request = this.url_request+"&status="+status;
              // }
              // else if (priority!="all" && status=="all") {
              //   api_url = api_url + "?priority="+priority;
              //   this.url_request = "&priority="+priority;
              // }
              // else if (status=="all" && priority=="all") {
              //   this.url_request = '';
              // }

              this.getTasks(api_url);
            },
            addTaskForm(id=null){
              if (id!=null) {
                this.getTask(id);
                this.update_task = true;
                this.task_title = 'Edit task';
                $('#new_task_name').focus();
              }
              else{
                this.add_new_task = true;
                this.task_title = 'New task';
                $('#new_task_name').focus();
              }
              
            },
            addTask(id=null){
              var api_url = '/api/task';
              var method = 'post';
              if (this.update_task==true && id!=null) {
                api_url = api_url + '/'+id;
                method = 'put';
              }
                fetch(api_url, {
                    method: method,
                    body: JSON.stringify(this.task),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(response => {
                  if(response.data){
                    this.clearTaskForm();
                    this.getTasks();
                    this.add_new_task = false;
                    this.update_task = false;
                    //this.current_task = response.data;
                    // this.current_task = response.meta.current_task;
                    // this.updateTaskStatus(this.current_task.id,this.current_task.status);
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
            cancelNewTask(){
              this.add_new_task = false;
              this.update_task = false;
              this.task_title = 'My tasks';

              var checkExist = setInterval(()=> {
                                 if ($("#status_"+this.current_task.id).length) {
                                    this.updateTaskStatus(this.current_task.id,this.current_task.status);
                                    clearInterval(checkExist);
                                 }
                              }, 100); // check every 100ms
            },
            clearTaskForm(){
              this.task.name = '';
              this.task.priority = '',
              this.task.start_date = null;
              this.task.end_date = null;
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
            bindTaskDate(name){
              if (name=='start_date') {
                this.task.start_date = $('#new_task_start_date').val();
              }
              else if (name=='end_date') {
                this.task.end_date = $('#new_task_end_date').val();
              }
            },
            oneClick: function(event,id){
              this.clicks++ 
              if(this.clicks === 1) {
                var self = this
                this.timer = setTimeout(()=> {
                  // self.result.push(event.type);
                  this.getTodos(id);
                  self.clicks = 0
                }, this.delay);
              } else{
                 clearTimeout(this.timer);  
                 // this.result.push('dblclick');
                 this.editTaskName(id);
                 this.clicks = 0;
              }         
            },
            searchTask(){
            
            let api_url = '/api/tasks?search='+this.filter.keyword;
            this.search_url = '&search='+this.filter.keyword;
            this.url_request = this.url_request + this.search_url;
            this.getTasks(api_url);

              // let vm = this;

              // fetch('/api/tasks/search', {
              //           method: 'post',
              //           body: JSON.stringify(this.filter.keyword),
              //           headers: {
              //               'content-type': 'application/json'
              //           }
              //       })
              //       .then(response => response.json())
              //       .then(response => {
                      
              //           this.tasks = response.data;
              //           this.current_task = this.tasks[0];
              //           this.todos = response.meta.todos;
              //           vm.paginator(response.meta, response.links);
                        
              //       })
              //       .catch(err => console.log(err));

              // var checkExist = setInterval(()=> {
              //    if ($("#status_"+this.current_task.id).length) {
              //       this.updateTaskStatus(this.current_task.id,this.current_task.status);
              //       clearInterval(checkExist);
              //    }
              // }, 100); // check every 100ms
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
            } 
        }
    };
</script>