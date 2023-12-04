<template>
  <div>    
      <h1>{{article.article_title}}</h1>
      <h5> Blog Category : {{article.article_category}}</h5>
      <p>{{article.article_content}}</p>
      <p></p>
      <form @submit.prevent="submitComment">
        <span v-if="success">{{ success }}</span>
        <div class="mb-6">
          <label for="ori_comment" class="form-label">Comment:</label><br>
          
          <textarea name="ori_comment" v-model="comment.ori_comment"></textarea>
          <span v-if="errors?.ori_comment">{{ errors.ori_comment[0] }}</span>
        </div>       
        
        <button type="submit" class="btn btn-primary">Submit Comment</button>
        
      </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      article: {
        article_title: '',
        article_cover: '',
        article_category: '',
        article_content: ''
      },
      comment:{
        ori_comment: '',
        blog_id: this.$route.params.id
      },
      errors:  {},
      success: ''
    }
  },
  computed: {
    isNewArticle() {
      return !this.$route.path.includes('view');
    }
  },
  async created() {     
    try{   
      if (!this.isNewArticle) {        
        const response = await axios.get(`/api/blogs/${this.$route.params.id}`,{
                      headers: {
                          Authorization: `Bearer ${localStorage.getItem('token')}`
                      }                    
        });
        this.article = response.data;        
      }
    }catch(error){
      console.log('Error :',error);
    }    
  },
  methods: {
    async submitComment() {
      try {
        if (!this.isNewArticle) {
          await axios.post('/api/comments', this.comment, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
          });
          if (response.data && response.data.message) {
            this.success = response.data.message;
            console.log(this.success);
          }
        } else {
          await axios.put(`/api/blogs/${this.$route.params.id}`, this.article,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }                   
          });
        }
        
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        }
        //console.log(error);
      }
    }
  }
}
</script>