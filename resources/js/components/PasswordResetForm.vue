<template>
    <!-- Form for login -->
    <div class="form-container">
        <form @submit.prevent="changePasswordForm" class="login-form">
            <!-- Email and password inputs -->
            <span class="text-success"  v-if="success" >
                {{ success }}
            </span>
            <span class="text-danger"  v-if="message" >
                {{ message }}
            </span>            
            <input type="password" v-model="password" placeholder="New Password" required />
            <span class="text-danger"  v-if="errors?.password" >
                {{ errors.password[0] }}
            </span>
            <input type="password" v-model="confirmpassword" placeholder="Confirm password" required />
            <span class="text-danger"  v-if="errors?.confirmpassword" >
                {{ errors.confirmpassword[0] }}
            </span>

            <!-- Submit button -->
            <button type="submit">Reset Password</button>
            <router-link to="/userlogin">Login</router-link>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
    data() {
        return {        
            password: '', 
            confirmpassword: '',
            errors: {},
            message: '',
            success: ''
        }
    },
    computed: {
        getResetToken() {
            return this.$route.params.token;
        }
    },
    methods: {
        async changePasswordForm() {            
            // Validate if password and confirm password match
            if (this.password !== this.confirmpassword) {
                this.message = '';
                this.errors = {};
                this.message = 'Password and Confirm Password do not match.';
                return; // Stop form submission
            }

            let response; // define variable to store response
            try {
                // Making POST request to "/resetpassword" endpoint with email and password as data
                response = await axios.post("/api/resetpassword", {
                    password: this.password,
                    confirmpassword: this.confirmpassword,
                    token: this.getResetToken
                });
                this.message = '';
                this.errors = {};
                this.success = "Password changed successfully! Click login link to signin.";
            } catch (error) {
                // get the error message from router
                if(error.response && error.response.data.message){                    
                    this.errors = error.response.data.errors;
                }
                if (error.response && error.response.data.status != 200) {                  
                  this.message = error.response.data.message;                  
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