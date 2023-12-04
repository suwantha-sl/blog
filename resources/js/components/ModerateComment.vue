<template>
 <div>    
      <h1>Moderate Comment</h1>
      
      
      <p></p>
        <div class="alert alert-success" role="alert" v-if="success" >
          {{ success }}
        </div>
      <form @submit.prevent="moderateComment">        
        <div class="mb-3">
          <label for="ori_comment" class="form-label">Original Comment:</label><br>          
          <textarea name="ori_comment" v-model="comment.ori_comment" class="form-control"></textarea>
          <span v-if="errors?.ori_comment">{{ errors.ori_comment[0] }}</span>
        </div> 
        <div class="mb-3">
          <label for="mod_comment" class="form-label">Moderate Comment:</label><br>          
          <textarea name="mod_comment" v-model="comment.mod_comment" class="form-control"></textarea>
          <span v-if="errors?.mod_comment">{{ errors.mod_comment[0] }}</span>
        </div>      
        
        <button type="submit" class="btn btn-primary" v-if="!success">Save & Post</button>
        
      </form>
  </div>
</template>

<script>
import axios from 'axios'
export default{
    data(){
        return{
            comment: {
                ori_comment: '',
                mod_comment: ''
            },
            errors: {},
            success: ''
        }
    },
    async created(){
        const response = await axios.get(`/api/comments/${this.$route.params.id}`,{
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }                    
        });
        response.data.mod_comment = response.data.ori_comment;
        this.comment = response.data;
    },
    methods: {
        async moderateComment(){
            try{
                await axios.put(`/api/comments/${this.$route.params.id}`, this.comment, {   
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem('token')}`                        
                        }
                });

                this.success = 'Comment successfully moderated !';   
            }catch(error){
                console.log(error);
            }
        }
    }
}
</script>