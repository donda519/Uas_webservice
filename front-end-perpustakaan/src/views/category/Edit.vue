<template>
  <div style="padding: 10px;">
    <div>
      <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Edit Category</div>
    </div>

    <div style="width: 60%; margin-top: 2px">
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="Category Name: " style="margin-left: 2px" prop="name">
          <el-input v-model="form.name" placeholder="Enter name"></el-input>
        </el-form-item>
      </el-form>

      <div style="text-align: center">
        <el-button type="primary" style="margin-left: 2px; height: 40px; min-width: 100px" @click="save">Submit</el-button>
      </div>
    </div>
  </div>
</template>

<script>
import request from "@/utils/request";

export default {
  name: "Edit",

  data() {
    return {
      form: {},
      rules: {
        name: [{ required: true, message: "Category name must not be empty", trigger: 'blur' }],
      }
    }
  },

  created() {
    const id = this.$route.query.id
    request.get('api/category/' + id).then(res => {
      this.form = res
    })
  },

  methods: {
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          request.put('api/category/' + this.$route.query.id, this.form).then(res => {
            if(res.code == '200') {
              this.$notify.success('Updated')
              this.$router.push("/categoryList")
            } else {
              this.$notify.error(res.msg)
            }
          })
        }
      })
    },
  }
}
</script>

<style scoped>

</style>