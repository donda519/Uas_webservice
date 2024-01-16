<template>
  <div style="padding: 10px;">
    <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Add New Member</div>
    <div style=" width: 60%">
      <!-- form area -->
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="Name: " style="margin-left: 2px" prop="name">
          <el-input v-model="form.name" placeholder="Enter name"></el-input>
        </el-form-item>
        <el-form-item label="Email: " style="margin-left: 2px" prop="email">
          <el-input v-model="form.email" placeholder="Enter email"></el-input>
        </el-form-item>
        <el-form-item label="User ID: " style="margin-left: 2px">
          <el-input placeholder="--Generate after submission--" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="Phone: " style="margin-left: 2px" prop="phone_number">
          <el-input v-model="form.phone_number" placeholder="Enter phone number"></el-input>
        </el-form-item>
        <el-form-item label="Gender: " style="margin-left: 2px" prop="gender">
          <el-select v-model="form.gender" placeholder="Please select a gender">
            <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Alamat: " style="margin-left: 2px" prop="address">
          <el-input v-model="form.address" placeholder="Enter address"></el-input>
        </el-form-item>
      </el-form>
      <!-- button area -->
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
    const checkGender = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter gender'));
      }
      callback()
    };

    const checkEmail = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter the email address'))
      }
      if(!/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test(value)) {
        callback(new Error('Illegal email address'))
      }
      callback()
    };

    const checkPhone = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter the phone number'))
      }
      // if (!/^[1,2,3,4,5,6,7,8,9][0-9]{9}$/.test(value)) {
      //   callback(new Error('Illegal phone number'));
      // }
      callback()
    }

    return {
      form: {
        gender: '',
      },

      options: [{
        value: 'L',
        label: 'Laki-laki'
      }, {
        value: 'P',
        label: 'Perempuan'
      }],

      // rules to check the input values
      rules: {
        // cannot be empty
        name: [{ required: true, message: 'Please enter the name', trigger: 'blur' }],
        address: [{ required: true, message: 'Please enter the address', trigger: 'blur' }],
        // more restrictions
        gender:[{ required: true, validator: checkGender, trigger: 'blur' }],
        email:[{ required: true, validator: checkEmail, trigger: 'blur' }],
        phone_number:[{ required: true, validator: checkPhone, trigger: 'blur' }]
      }
    }
  },

  methods: {
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          request.post('api/member', this.form).then(res => {
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