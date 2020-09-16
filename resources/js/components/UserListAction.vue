<template>
		<span role="button" data-toggle="modal" :data-target="'#'+button_action+'Modal'" style="margin-right: 5px;"><i class="fa" v-bind:class="'fa-'+button_icon+' color-'+button_color" role="button" data-toggle="tooltip" data-placement="top" :title="button_title" @click="performAction(button_action)"></i></span>
</template>

<script>

	import { EventBus } from '../bus.js';

	export default{
		props:['actionType','icon','userId','color','userName'],
		data(){
			return{
				button_action : this.actionType,
				button_icon : this.icon,
				user_id : this.userId,
				button_title : this.actionType,
				button_color:this.color,
				user_name:this.userName
			}
		},
		created(){

		},
		methods:{
				performAction(action){
					let data = {
						id:this.user_id,
						user_name:this.user_name
					}
					if (action=='Task') {
						this.emitGlobalEvent('task-button-click',data);
					}
					else if (action=='View') {
						this.emitGlobalEvent('view-button-click',data);
					}
					else if (action=='Delete') {
						this.emitGlobalEvent('delete-button-click',data);
					}
					else if (action=='Ban') {
						this.emitGlobalEvent('ban-button-click',data);
					}
				},
				emitGlobalEvent(eventType,data){
					EventBus.$emit(eventType,data);
				}
		}
	}
</script>