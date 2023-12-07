<template>
    <div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.f_name }}</td>
                    <td>{{ user.l_name }}</td>
                    <td>{{ user.email }}</td>
                    <td v-if="user.user_type == 'BA'">Blog Admin</td>
                    <td v-else-if="user.user_type == 'SA'">Super Admin</td>
                    <td v-else>Unknown</td>
                    <td>
                      <div class="row gap-3">
                       
                        <router-link :to="`/users/${user.id}/edit`" class="p-2 col border btn btn-success">Edit</router-link>
                        <button @click="deleteUser(user.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      users: []
    }
  },
  async created() {
    try {
      const response = await axios.get('/api/users',{
					headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`                        
                    }
	  });
      this.users = response.data;
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async deleteUser(id) {
      try {
        await axios.delete(`/api/users/${id}`,{
					headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`                        
                    }
		});
        this.users = this.users.filter(user => user.id !== id);
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>