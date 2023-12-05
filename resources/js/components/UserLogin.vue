<template>
    <!-- Form for login -->
    <div class="form-container">
        <form @submit.prevent="login" class="login-form">
            <!-- Email and password inputs -->
            <span class="text-danger"  v-if="message" >
                {{ message }}
            </span>
            <input type="email" v-model="email" placeholder="Email" required />
            <span class="text-danger"  v-if="errors?.email" >
                {{ errors.email[0] }}
            </span>
            <input type="password" v-model="password" placeholder="Password" required />
            <span class="text-danger"  v-if="errors?.password" >
                {{ errors.password[0] }}
            </span>
            <!-- Submit button -->
            <button type="submit">Login</button>
        </form>
    </div>
</template>


<script>
    import axios from 'axios';
    export default {
    data() {
        return {        
            email: '',
            password: '',
            errors: {},
            message: ''
        }
    },
    methods: {
        async login() {
            console.log('Login function called');
            let response; // define variable to store response
            try {
                // Making POST request to "/login" endpoint with email and password as data
                response = await axios.post("/api/userlogin", {
                    email: this.email,
                    password: this.password
                });
                console.log('Axios response:', response); 
                // If a token is received, it's stored in local storage and the user is considered logged in
                if (response.data.token) {
                    localStorage.setItem('token', response.data.token);

                    if(response.data.user){
                        localStorage.setItem('userType', response.data.user.user_type); // get the user type SA - Super Amin, BA - Blog Admin 
                        localStorage.setItem('userId', response.data.user.id);   // get the user id and save to local storage                    
                    }


                    // Committing a mutation to update 'isLoggedIn' state
                    this.$store.commit('LOGIN');


                    // Redirecting to the home page
                    this.$router.push('/');
                }
            
            } catch (error) {
                // get the error message from router
                if(error.response && error.response.data.message){                    
                    this.errors = error.response.data.errors;
                }
                if (error.response && error.response.data.status === 401) {                  
                  this.message = 'Invalid username/password';                  
                }                             
            }
        }
    }
    }
</script>



<style scoped>
    .form-container {
    display: flex;
    justify-content: center;
    align-items: center;
    }


    .login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 300px;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }


    .login-form input, .login-form button {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    }


    .login-form button {
    background-color: #007BFF;
    color: white;
    cursor: pointer;
    }
</style>