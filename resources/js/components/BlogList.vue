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
                        <router-link :to="`/blogs/${article.id}/edit`" class="p-2 col border btn btn-success" v-if="isEditable(article)">Edit</router-link>
                        <button @click="deleteBlog(article.id)" type="button" class="p-2 col border btn btn-danger" v-if="isEditable(article)">Delete</button>
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
      articles: [],
      userID: localStorage.getItem('userId'), // get userId from localStorage
      uType: localStorage.getItem('userType') // get userType from localStorage
    }
  },
  computed:{
    isEditable() {
      return(article) => {
        // Check if the user is the author or has UserType SA
        console.log(article.author);
        return article.author === parseInt(this.userID) || this.uType === 'SA';
      };
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

      if(response.data.status == 200){
        this.articles = response.data.blogs;
      }else{
        // unauthorize access
      }     
    } catch (error) {
      console.error(error);
    }
  },  
  methods: {
    async deleteBlog(id) {
      try {
        await axios.delete(`/api/blogs/${id}`,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
        });
        this.articles = this.articles.filter(article => article.id !== id);
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>