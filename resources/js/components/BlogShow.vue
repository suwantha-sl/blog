<template>
  <div>    
      <h1>{{article.article_title}}</h1>
      <h5> Blog Category : {{article.article_category}}</h5>
      <img  :src="getCoverUrl()" alt="Cover Image" style="max-width: 100%; margin-top: 10px;" />
      <p>&nbsp;</p>
      <p>{{article.article_content}}</p>
      <p>&nbsp;</p>
      <form @submit.prevent="submitComment">
        <div class="alert alert-success" role="alert" v-if="success" >
          {{ success }}
        </div>
        <div class="mb-6">
          <label for="ori_comment" class="form-label">Post Comments:</label><br>
          
          <textarea name="ori_comment" v-model="comment.ori_comment" class="form-control"></textarea>
          <span class="text-danger" v-if="errors?.ori_comment">{{ errors.ori_comment[0] }}</span>
        </div>       
        
        <button type="submit" class="btn btn-primary" v-if="!success">Submit Comment</button>
        
      </form>
      <p>&nbsp;</p>
      <h6>Comments by users </h6>
      <table class="table">
        <tbody>
          <tr v-for="commentview in commentviews">
            <td>{{ commentview.commenttext }}<br>By : {{ commentview.commentuser }}&nbsp;&nbsp; on {{ commentview.commentdate }}</td>
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
      commentviews:{
        ori_comment: ''
      },
      errors:  {},
      success: ''
    }
  },  
  async created() {     
    try{   
            
        const response = await axios.get(`/api/blogs/${this.$route.params.id}`,{
                      headers: {
                          Authorization: `Bearer ${localStorage.getItem('token')}`
                      }                    
        });

        this.article = response.data;

        const commentsResponse = await axios.get(`/api/viewcomments/${this.$route.params.id}`,{
                      headers: {
                          Authorization: `Bearer ${localStorage.getItem('token')}`
                      }                    
        });

        if(commentsResponse.data.status == 200){
          this.commentviews = commentsResponse.data.comments;
        }else{
          // error
        }
        
                
      
    }catch(error){
      console.log('Error :',error);
    }    
  },
  methods: {
    getCoverUrl(){
      return `/storage/${this.article.article_cover}`;      
    },
    async submitComment() {
      try {
          // reset success and error variables
          this.errors = {};
          this.success = '';

          let response; // define response variable
          response = await axios.post('/api/comments', this.comment, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
          });
          if (response.data && (response.data.status == 201 || response.data.status == 200)) {
            this.success = response.data.message;            
          }else{
            this.errors = response.data.message;  
          }        
        
      } catch (error) {
        if (error.response && error.response.status != 500) {
          this.errors = error.response.data.errors;
        }
        //console.log(error);
      }
    }
  }
}
</script>