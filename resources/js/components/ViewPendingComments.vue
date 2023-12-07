<template>
    <div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Blog Title</th>
                <th scope="col">Original Comment</th> 
                <th scope="col">Moderated Comment</th>               
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="comment in comments" :key="comment.id">
                    <td>{{ comment.id }}</td>
                    <td>{{ comment.article_title }}</td> 
                    <td>{{ comment.ori_comment }}</td>
                    <td>{{ comment.mod_comment }}</td>                                                           
                    <td>
                      <div class="row gap-3">                        
                        <router-link :to="`/comments/${comment.id}/edit`" class="p-2 col border btn btn-success">Moderate</router-link>
                        <button @click="deleteUser(comment.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
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
      comments: []
    }
  },
  async created() {
    try {
      const response = await axios.get('/api/comments',{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    },
                    withCredentials: true,
      });
		if(response.data.status == 200){
		  this.comments = response.data.reccomments;  
		}else{
			// error
		}
          
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async deleteUser(id) {
      try {
        await axios.delete(`/api/comments/${id}`,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
        });
        this.comments = this.comments.filter(comment => comment.id !== id);
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>