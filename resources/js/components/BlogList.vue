<template>
    <div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Blog Title</th>
                <th scope="col">Blog Category</th>                
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="article in articles" :key="article.id">
                    <td>{{ article.id }}</td>
                    <td>{{ article.article_title }}</td>
                    <td>{{ article.article_category }}</td>                                        
                    <td>
                      <div class="row gap-3">
                        <router-link :to="`/blogs/${article.id}/view`" class="p-2 col border btn btn-success">Read</router-link>
                        <router-link :to="`/blogs/${article.id}/edit`" class="p-2 col border btn btn-success">Edit</router-link>
                        <button @click="deleteUser(article.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
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
      articles: []
    }
  },
  async created() {
    try {
      const response = await axios.get('/api/blogs',{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    },
                    withCredentials: true,
      });      
      this.articles = response.data;      
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async deleteUser(id) {
      try {
        await axios.delete(`/api/blogs/${id}`);
        this.articles = this.articles.filter(article => article.id !== id);
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>