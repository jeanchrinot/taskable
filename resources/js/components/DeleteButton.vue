<template>
    <div>
        <i class="fa fa-trash edit-item-icon color-red" @click="confirmDeleteTask()" data-toggle="modal" data-target="#deleteListModal"></i>
    </div>
</template>

<script>
    export default {
        
            props:['itemType','itemId'],
            
            mounted() {
                // console.log('Delete Component mounted.')
            },
        
            methods:{
                confirmDeleteTask(){

                    $('#confirm-delete').replaceWith($('#confirm-delete').clone());

                    document.querySelector("#confirm-delete").addEventListener("click",this.deleteItem, false);
                },

                deleteItem(e){

                    e.preventDefault();
                        
                       //alert('type='+this.itemType+', id='+this.itemId);

                        axios.delete('/api/'+this.itemType+'/' + this.itemId)
                        .then(data => {

                            $("#deleteListModal").modal('hide');

                            if (this.itemType=='task') {
                                this.$parent.getTasks('/api/tasks?page='+this.$parent.pagination.current_page);
                            }
                            else if (this.itemType=='todo') {
                                this.$parent.getTodos();
                                this.$parent.updateTaskStatus(this.$parent.current_task.id,this.$parent.current_task.status);
                                //alert(this.$parent.current_task.status);
                            }

                        })
                        .catch(err => console.log(err));
                }
            }
        }
</script>