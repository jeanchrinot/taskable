/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

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
Vue.component('delete-button', require('./components/DeleteButton.vue').default);
Vue.component('task-list', require('./components/TaskList.vue').default);

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