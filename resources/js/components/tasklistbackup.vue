<template>
  <div>
      <div class="row">
          <div class="col-md-6 no-padding">
              <div class="card">
                <div class="card-header">
                  <i class="fa fa-align-left"></i> {{ current_task.name }}
                </div>
                <div class="card-body">
                  <form @submit.prevent="addTodo">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Add item to list..." aria-label="Add item to list" aria-describedby="basic-addon2" v-model="todo.name">
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-plus"></i> Add</button>
                      </div>
                    </div>
                  </form>
                  <ul class="todo-list text-left">
                      <li v-for="todo in todos" v-bind:key="todo.id" role="button"><label :for="todo.id" class="list-label" role="button"> <input type="checkbox" v-model="todo.complete" :id="todo.id" @click="toggleComplete(todo.id)"> <span class="todo-list__text" v-bind:class="[{'todo-list__item-checked': todo.complete}]">{{ todo.name }}</span></label></li>
                  </ul>
                </div>
              </div>
          </div>
          <div class="col-md-6 no-padding">
              <div class="card">
                <div class="card-header">
                  My Tasks
                </div>
                <div class="card-body">
                    <ul class="todo-list text-left">
                      <li v-for="task in tasks" v-bind:id="'task_'+task.id" v-bind:key="task.id" role="button" @click="getTask(task.id)" v-bind:class="[{current:isCurrent(task.id)}]" class="edit-item-icon-parent"><span class="list-style" v-bind:class="'list-style--'+taskPriority(task.priority,2)"></span> <span class="todo-list__text" v-bind:id="'task_name_'+task.id">{{ task.name }}</span>
                        <i class="fa fa-pencil edit-item-icon" @click="editTaskName(task.id)"></i>
                        <span class="badge" v-bind:class="'badge-'+taskStatus(task.status,2)" style="float: right;margin-right: 25px;" v-bind:id="'status_'+task.id">{{ taskStatus(task.status,1) }}</span>
                      </li>
                  </ul>
                </div>
              </div>
               <nav>
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
</template>

<script>
    export default {
        data() {
            return {
                tasks: [],
                current_task:{},
                pagination: {},
                todos:[],
                todo: {
                    id: '',
                    name: '',
                    task_id:''
                },
                update: false,
                task_id: '',
                task_new_name:{
                  id:'',
                  name:''
                }
            };
        },
 
        created() {
            this.getTasks();
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
            paginator(meta, links) {
                this.pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
            },
            addTodo() {
                if (this.update === false) {
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
                            this.clearForm();
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
                    fetch('/api/task', {
                        method: 'put',
                        body: JSON.stringify(this.task),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.clearForm();
                            this.getTasks();
                        })
                        .catch(err => console.log(err));
                }
            },
             getTask(id) {
                fetch('/api/task/' + id)
                  .then(response => response.json())
                    .then(response => {
                        this.todos = response.data;
                        this.current_task = response.meta.current_task;
                    })
                    .catch(err => console.log(err));
            },
            deleteTask(id) {
                fetch('/api/task/' + id, {
                    method: 'delete'
                })
                    .then(response => response.json())
                    .then(data => {
                        this.getTasks();
                    })
                    .catch(err => console.log(err));
            },
            updateTask(task) {
                this.update = true;
                this.task.id = task.id;
                this.task.task_id = task.id;
                this.task.title = task.title;
                this.task.body = task.body;
            },
            clearForm() {
                this.update = false;
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
              $("#status_"+id).attr('class', 'badge badge-'+this.taskStatus(status,2));
              $("#status_"+id).html(this.taskStatus(status,1));
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
                      if(response.success){
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
            }
        }
    };
</script>