/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import store from './store'; // Vuex store
import { createRouter, createWebHistory } from 'vue-router';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

//const app = createApp({});

import App from './components/App.vue';
import UserList from './components/UserList.vue';
import UserForm from './components/UserForm.vue';
import UserLogin from './components/UserLogin.vue';
import BlogForm from './components/BlogForm.vue';
import BlogList from './components/BlogList.vue';
import BlogShow from './components/BlogShow.vue';
import ViewPendingComments from './components/ViewPendingComments.vue';
import ModerateComment from './components/ModerateComment.vue';
//app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: UserList },
        { path: '/users/create', component: UserForm, meta:{public: false} },            
        { path: '/users/:id/edit', component: UserForm, meta:{public: false} },
        { path: '/userlogin', component: UserLogin, meta:{public: true} },
        { path: '/blogs/create', component: BlogForm, meta:{public: false} },
        { path: '/blogs/:id/edit', component: BlogForm, meta:{public: false} },
        { path: '/blogs/:id/view', component: BlogShow, meta:{public: false} },
        { path: '/blogs', component: BlogList, meta:{public: false} },
        { path: '/comments', component: ViewPendingComments, meta:{public: false} },
        { path: '/comments/:id/edit', component: ModerateComment, meta:{public: false} },
    ]
});

router.beforeEach((to, from, next) => {
    // Check if the user is authenticated (e.g., check if a token is stored)
    const isAuthenticated = !!localStorage.getItem('token');
    
    
    if (!to.meta.public && !isAuthenticated) {
        // If the route is not public and the user is not authenticated, redirect to login
        next({ path: '/userlogin' });
    } else {
        // Otherwise, allow navigation
        next();
    }
});

const app = createApp(App);

app.use(store);
app.use(router);


app.mount('#app');

export default router;
