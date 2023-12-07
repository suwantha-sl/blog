<template>
  <div>
    <h2 v-if="isNewArticle">Create New Blog Post</h2>
    <h2 v-else>Edit Blog Post</h2>
      <form @submit.prevent="submitForm">        
        
        <div class="mb-3">
          <label for="article_title" class="form-label">Blog Title:</label>
          <input class="form-control" type="text" id="article_title" v-model="article.article_title" required />
          <span class="text-danger"  v-if="errors?.article_title" >
            {{ errors.article_title[0] }}
          </span>
        </div>
        <div class="mb-3">          
          <label for="article_cover" class="form-label">Blog Cover Image:</label>
          <input class="form-control" type="file" id="article_cover" ref="article_cover" @change="validateCoverImage($event)"  />
          <span class="text-danger"  v-if="errors?.article_cover" >
            {{ errors.article_cover[0] }}
          </span>
          <img v-if="!isNewArticle" :src="getCoverUrl()" alt="Cover Image" style="max-width: 100%; margin-top: 10px;" />
        </div>
        <div class="mb-3">
            <label for="article_category" class="form-label">Blog Category:</label> 
                          
                <select class="form-control" v-model="article.article_category">                
                    <option value="Travel">Travel</option>
                    <option value="Life Style">Life Style</option>
                    <option value="Education">Education</option>                
                </select>
                <span class="text-danger"  v-if="errors?.article_category" >
                  {{ errors.article_category[0] }}
                </span>
        </div> 
        <div class="mb-3">
          <label for="article_content" class="form-label">Blog Content:</label>
          
          <textarea class="form-control" name="article_content" v-model="article.article_content"></textarea>
        </div>       
        
        <button type="submit" v-if="isNewArticle" class="btn btn-primary">Save</button>
        <button type="submit" v-else class="btn btn-primary">Update</button>

        <div class="alert alert-success" role="alert" v-if="success" >
          {{ success }}
        </div>
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
      errors: {},
      success: ''
    }
  },
  computed: {
    isNewArticle() {
      return !this.$route.path.includes('edit');
    }
  },
  async created() {
    this.errors = {};
    this.success = '';
    if (!this.isNewArticle) {
      const response = await axios.get(`/api/blogs/${this.$route.params.id}`,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }                    
      });
      this.article = response.data;
      console.log(  response.data);
    }
  },
  methods: {
    getCoverUrl(){
      return `/storage/${this.article.article_cover}`;
      console.log(this.article.article_cover);
    },
    validateCoverImage(event){
      this.success = '';
      // Reset errors object before validating
      this.errors = {};

      const coverImageInput = this.$refs.article_cover;
      const coverImage = coverImageInput.files[0];
      
      if(coverImage ){
        // validate file size in bytes
        const maxSize = 2 * 1024 * 1024; // 2MB

        if(coverImage.size > maxSize){
          this.errors.article_cover = ['File size must be less than 2MB.'];
          return;
        }

        // validate file types
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(coverImage.type)) {
          this.errors.article_cover = ['File type must be JPG, JPEG, or PNG.'];
          return;
        }

      }else{
        if(this.isNewArticle){
          this.errors.article_cover = ['Please select a cover image.'];
        }        
      }
    },
    async submitForm() {
      // Validate cover image before submitting the form
      this.validateCoverImage();

      // Check if there are any validation errors
      if (Object.keys(this.errors).length > 0) {
        // If there are errors, stop form submission
        return;
      }

      try {
        const blogData = new FormData(); // use FormData object to handle file uploads

        blogData.append('blog[article_title]',this.article.article_title);
        blogData.append('blog[article_cover]',this.$refs.article_cover.files[0]);
        blogData.append('blog[article_category]',this.article.article_category);
        blogData.append('blog[article_content]',this.article.article_content);

        let response; // Define a variable to store response
        console.log(this.article.article_title);
        if (this.isNewArticle) {          
          response = await axios.post('/api/blogs', blogData, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                        'content-type': 'multipart/form-data'
                    }
          });
          if (response.data && response.data.message) {
            this.success = response.data.message;
            console.log(this.success);
          }
        } else {  
          const blogDataEdit = {
              article_title: this.article.article_title,
              article_cover: this.$refs.article_cover.files[0],
              article_category: this.article.article_category,
              article_content: this.article.article_content,
          };

          response = await axios.put(`/api/blogs/${this.$route.params.id}`, blogDataEdit,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
						            'Content-Type': 'multipart/form-data'                        
                    } ,
                    transformRequest: [data => JSON.stringify(data)],                   
										
          });
        }
        if (response.data && response.data.status == 200) {
            this.success = response.data.message;
            console.log(this.success);
        }
        //this.$router.push('/');
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        }
        console.error(error);
      }
    }
  }
}
</script>