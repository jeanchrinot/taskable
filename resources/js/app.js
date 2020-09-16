/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

if (localStorage.getItem('userToken')!=null) {

	axios.defaults.headers.common["Authorization"] = 'Bearer ' + localStorage.getItem('userToken');
}

axios.defaults.headers.post['content-type'] = 'application/json'; // for POST requests
axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest'; // for POST requests

axios.defaults.headers.put['content-type'] = 'application/json'; // for POST requests
axios.defaults.headers.put['X-Requested-With'] = 'XMLHttpRequest'; // for POST requests

// window.helpers = {show:'task'}; // Show task by default

// const axiosPost = axios.create({
//   baseURL: '/api/',
//   method: 'post',
//   timeout: 1000,
//   headers: {
//   	 'content-type': 'application/json',
//      'X-Requested-With': 'XMLHttpRequest'
//     }
// });


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('auth-navbar', require('./components/AuthNavbar.vue').default);
Vue.component('sidebar-nav', require('./components/SidebarNav.vue').default);
Vue.component('login-form', require('./components/LoginForm.vue').default);

Vue.component('stats', require('./components/Stats.vue').default);
Vue.component('delete-button', require('./components/DeleteButton.vue').default);
Vue.component('task-list', require('./components/TaskList.vue').default);

Vue.component('user-list-action', require('./components/UserListAction.vue').default);
Vue.component('delete-modal', require('./components/DeleteModal.vue').default);
Vue.component('view-user-modal', require('./components/ViewUserModal.vue').default);
Vue.component('ban-user-modal', require('./components/BanUserModal.vue').default);
Vue.component('task-modal', require('./components/TaskModal.vue').default);
Vue.component('users', require('./components/Users.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// // Task Filter 
// $("#priority").change(function(){
//   $value = this.value.toLowerCase();
//   var url = window.location.href;
//   $urlBase = url.substring(0, url.lastIndexOf("?"));
//   //console.log($urlBase);
//   window.location.replace($urlBase+"?priority="+$value);
// });

// $("#status").change(function(){
//   $value = this.value.toLowerCase();
//   var url = window.location.href;
//   $urlBase = url.substring(0, url.lastIndexOf("?"));
//   //console.log($urlBase);
//   window.location.replace($urlBase+"?status="+$value);
// });

$('.toast').toast();

// var timeoutId = null;

// $( "#dblclicktest" ).click(function() {
  
//         if(!timeoutId)
//         {
//             timeoutId = setTimeout(() => {
//                 // simple click
//                 alert("simple click");
//                 clearTimeout(timeoutId);
//             }, 20);//tolerance in ms

//         }else{
//             clearTimeout(timeoutId);
//             timeoutId = null;
//             // double click
//             alert("double click");
//         }

// });