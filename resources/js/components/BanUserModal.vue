<template>
    <div class="modal modal--small fade" id="BanModal" tabindex="-1" role="dialog" aria-labelledby="BanModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="BanModalLabel">Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure to toggle ban for this user : {{ user_name }} ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger btn-sm" id="confirm-ban" @click="banUser(user_id)">Yes</button>
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
          user_name:'',
          user_id:'',
          user_ban_status:''
      }
    },
    created(){
      EventBus.$on('ban-button-click', data => {
                  this.user_id = data.id;
                  this.user_name = data.user_name;
                  this.user_ban_status = data.user_ban_status
                });
    },
    methods:{
        banUser(id){
          axios.put('/api/admin/user/ban/'+id)
          .then(response=>{
            response = response.data;
            if (response.success==true) {
              let data = {
                id:id
              }
                this.emitGlobalEvent('user-ban',data);
                $("#BanModal").modal('hide');
            }
            else{
              $('#error-toast').find('.toast-body').html('An error occured. Try again later.');
              $('#error-toast').toast('show');
            }
          })
          .catch(err=>{
            console.log(err);
          });
        },
        emitGlobalEvent(eventType,data){
          EventBus.$emit(eventType,data);
        }
    }
  }
</script>