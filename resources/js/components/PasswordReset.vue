<template>
    <!-- Form for login -->
    <div class="form-container">
        <form @submit.prevent="passwordResetForm" class="login-form">
            <!-- Email and password inputs -->
            <span class="text-success"  v-if="success" >
                {{ success }}
            </span>
            <span class="text-danger"  v-if="message" >
                {{ message }}
            </span>
            <input type="email" v-model="email" placeholder="Email" required />
            <span class="text-danger"  v-if="errors?.email" >
                {{ errors.email[0] }}
            </span>
            
            <!-- Submit button -->
            <button type="submit">Send Password Reset Link</button>            
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
    data() {
        return {        
            email: '',            
            errors: {},
            message: '',
            success: ''
        }
    },
    methods: {
        async passwordResetForm() { 
            this.success = '';
            this.errors = {};
            this.message = '';           
            let response; // define variable to store response
            try {
                // Making POST request to "/forget-password" endpoint with email and password as data
                response = await axios.post("/api/forget-password", {
                    email: this.email                    
                });
                console.log('Axios response:', response); 
                this.success = 'Please check your email inbox/spam in few minutes.';              
            
            } catch (error) {
                // get the error message from router
                if(error.response && error.response.data.message){                    
                    this.errors = error.response.data.errors;
                }
                if (error.response && error.response.data.status != 200) {                  
                  this.message = 'Error! ';                  
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