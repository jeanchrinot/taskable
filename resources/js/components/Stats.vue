<template>
		<div class="numbers flex">
            <div class="numbers__item">
                <div class="numbers__item__icon"><i class="fa fa-tasks"></i></div>
                <div class="numbers__item__value"><span class="item__number">{{ stats.count.value }}</span><br><span class="item__label">{{ stats.count.name }}</span></div>
            </div>
            <div class="numbers__item">
                <div class="numbers__item__icon bg-green"><i class="fa fa-check"></i></div>
                <div class="numbers__item__value"><span class="item__number">{{ stats.complete.value }}</span><br><span class="item__label">{{ stats.complete.name }}</span></div>
            </div>
            <div class="numbers__item">
                <div class="numbers__item__icon bg-yellow"><i class="fa fa-line-chart"></i></div>
                <div class="numbers__item__value"><span class="item__number">{{ stats.inprogress.value }}</span><br><span class="item__label">{{ stats.inprogress.name }}</span></div>
            </div>
            <div class="numbers__item">
                <div class="numbers__item__icon bg-red"><i class="fa fa-calendar-times-o"></i></div>
                <div class="numbers__item__value"><span class="item__number">{{ stats.expired.value }}</span><br><span class="item__label">{{ stats.expired.name }}</span></div>
            </div>
        </div>
</template>
<script>
    import { EventBus } from '../bus.js';
	export default{
		data(){
			return{
				stats:[]
			}
		},
		created(){

            this.getStats();

            EventBus.$on('task-updated', data => {
                  console.log(data)
                  this.getStats();
                });
            EventBus.$on('task-button-click', data => {
                  this.getStats(data.id);
                });
            EventBus.$on('task-added', data => {
                  this.getStats();
                });

		},
		computed:{

		},
		methods:{
			 getStats(id=null) {
                
                let api_url = '/api/stats';
                if (id!=null) {
                    api_url+='?id='+id;
                }
                axios.get(api_url)
                .then((response) => {
                  response = response.data;
                  //console.log(this.task_title);
                        this.stats = response.data;
                        //console.log(this.stats.count.name);
                    })
                    .catch(err => console.log(err));
            },
            updateLocalStorage(){
                let user_task_number = localStorage.getItem('user_task_number');
                user_task_number+=1;
                localStorage.setItem('user_task_number',user_task_number);
            }
		}
	}
</script>