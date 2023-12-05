<template>
<!-- Navigation menu section -->

        <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
            <li>
                <router-link to="/" v-if="isLoggedIn" class="nav-link text-white" style="text-decoration: none; color: white;"><svg class="bi d-block mx-auto mb-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4.5 21q-.625 0-1.063-.438T3 19.5v-1.9l4-3.55V21H4.5ZM8 21v-4h8v4H8Zm9 0v-8.2L12.725 9l3.025-2.675l4.75 4.225q.25.225.375.513t.125.612V19.5q0 .625-.438 1.063T19.5 21H17ZM3 16.25v-4.575q0-.325.125-.625t.375-.5L11 3.9q.2-.2.463-.287T12 3.525q.275 0 .537.088T13 3.9l2 1.775L3 16.25Z"/></svg>Home</router-link>
            </li>
            <li>
                <router-link to="/users/create" v-if="isSuperAdmin" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>&nbsp;Create User</router-link>
            </li>
            <li>
                <router-link to="/users/create" v-if="isSuperAdmin" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>&nbsp;View Users</router-link>
            </li>
            <li>
                <router-link to="/blogs/create" v-if="isLoggedIn" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="14 2 18 6 7 17 3 17 3 13 14 2"></polygon><line x1="3" y1="22" x2="21" y2="22"></line></svg>&nbsp;Create Blog Post</router-link>
            </li>
            <li>
                <router-link to="/blogs" v-if="isLoggedIn" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>&nbsp;Blogs</router-link>
            </li>
            <li>
                <router-link to="/comments" v-if="isLoggedIn" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>&nbsp;View Comments</router-link>
            </li>
            <li>
                <router-link to="/userlogin" v-if="!isLoggedIn" class="nav-link text-white" style="text-decoration: none; color: white;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>&nbsp;Login</router-link>
            </li>            
            <li v-if="isLoggedIn">
                <a id="logout-link" href="#" @click.prevent="logout" class="nav-link text-white"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>&nbsp;Logout</a>                
            </li>
        </ul>

</template>

<script>
import axios from 'axios';
import { mapState, mapActions } from 'vuex';

export default {  
  computed: {
    // Using Vuex helpers to map the 'isLoggedIn' state to a computed property
    ...mapState(['isLoggedIn'])   
  },  
  methods: {
    async logout(evt){
        if(confirm("Are you sure you want to logout?")){
            try{
                const logOutResponse = await axios.get(`/api/logout`,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });                
                               
            }catch(error){
                console.log(error);
            }
            
        }
    },
    ...mapActions(['logout'])  
        
  }
}
</script>