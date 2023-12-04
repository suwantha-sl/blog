<template>
  <div>
    <h2 v-if="isNewUser">Add User</h2>
    <h2 v-else>Edit User</h2>
      <form @submit.prevent="submitForm">
        <div class="mb-3">
          <label for="f_name" class="form-label">First Name:</label>
          <input class="form-control" type="text" id="f_name" v-model="user.f_name" required />
        </div>
        <div class="mb-3">
          <label for="l_name" class="form-label">Last Name:</label>
          <input class="form-control" type="text" id="l_name" v-model="user.l_name" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input class="form-control" type="email" id="email" v-model="user.email" :disabled="!isNewUser" required />
        </div>
        <div class="mb-3">
          <label for="user_type" class="form-label">User Type:</label>
            
                <select v-model="user.user_type">                
                    <option value="BA">Blog Admin</option>                
                </select>
        </div>        
        <div class="mb-3">
          <label for="password" class="form-label">Password:</label>
          <input class="form-control" type="text" id="password" v-model="user.password" :disabled="!isNewUser" required />
        </div>
        
        <button type="submit" v-if="isNewUser" class="btn btn-primary">Create User</button>
        <button type="submit" v-else class="btn btn-primary">Update User</button>
      </form>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      user: {
        f_name: '',
        l_name: '',
        email: '',
        password: '',
        user_type: '',
        login_attempt: 0
        
      }
    }
  },
  computed: {
    isNewUser() {
      return !this.$route.path.includes('edit');
    }
  },
  async created() {
    if (!this.isNewUser) {
      const response = await axios.get(`/api/users/${this.$route.params.id}`);
      this.user = response.data;
      console.log( response);
    }
  },
  methods: {
    async submitForm() {
      try {
        if (this.isNewUser) {
          await axios.post('/api/users', this.user);
        } else {
          await axios.put(`/api/users/${this.$route.params.id}`, this.user);
        }
        this.$router.push('/');
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>