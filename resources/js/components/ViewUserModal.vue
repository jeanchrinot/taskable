<template>
    <div class="modal fade" id="ViewModal" tabindex="-1" role="dialog" aria-labelledby="ViewModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ViewModalLabel">User details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <img src="/assets/images/avatar.jpg" style="width: 50%;margin:auto;">
              </div>
              <div class="col-md-12">
                 <div class="card">
                          <div class="card-header">
                            User Details
                          </div>
                          <div class="card-body">
                              <table class="table table-sm table-striped">
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
                                    <td>Banned</td>
                                    <td><span class="badge " :id="'user_ban_status'"></span></td>
                                  </tr>
                                  <tr>
                                    <td>Joined on</td>
                                    <td>{{ formatDate(user.created_at) }}</td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                        </div>
                <!-- <div><strong>Name: </strong> Jean</div>
                <div><strong>Email: </strong> jean@gmail.com</div>
                <div><strong>Status: </strong> Jean</div> -->
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
  export default{
    data(){
      return{
        user:{}
      }
    },
    created(){
      EventBus.$on('view-button-click', data => {
                  this.getUser(data.id);
                });
    },
    methods:{
      getUser(id){
        axios.get('/api/user/'+id)
        .then(response => {
            response = response.data;
            this.user = response.data;
            $("#user_status").attr('class', 'badge badge-'+this.userStatus(this.user.status,2));
            $("#user_status").html(this.userStatus(this.user.status,1));

            $("#user_ban_status").attr('class', 'badge badge-'+this.userBanStatus(this.user.banned,2));
            $("#user_ban_status").html(this.userBanStatus(this.user.banned,1));
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
      userBanStatus(status,index){
        var statusArr = [
              ['0','No','light'],
              ['1','Banned','danger']
              ];
              
        return statusArr[status][index];
      },
      formatDate(date){
        //let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        let theDate = new Date(date);
        return theDate.toLocaleDateString("en-US"); 
      },
    }
  }
</script>