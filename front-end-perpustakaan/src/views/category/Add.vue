<template>
  <div style="padding: 10px;">
    <div>
      <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Add Category</div>
    </div>

    <div style="width: 60%; margin-top: 2px">
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="Category Name: " style="margin-left: 2px" prop="name">
          <el-input v-model="form.name" placeholder="Enter name"></el-input>
        </el-form-item>
        <!-- <el-form-item label="Remark: " style="margin-left: 2px" prop="remark">
          <el-input v-model="form.remark" placeholder="Enter remark"></el-input>
        </el-form-item> -->
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
  name: "Add",

  data() {
    return {
      form: {},
      rules: {
        name: [{ required: true, message: "Category name must not be empty", trigger: 'blur' }],
        remark: [{ required: true, message: "Remark must not be empty", trigger: 'blur' }],
      }
    }
  },

  methods: {
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          request.post('api/category', this.form).then(res => {
            if(res.code == '200') {
              this.$notify.success('Submitted')
              this.$refs['ruleForm'].resetFields()
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