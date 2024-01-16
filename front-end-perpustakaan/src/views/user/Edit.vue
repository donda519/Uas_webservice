<template>
  <div style="padding: 10px;">
    <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Edit User</div>
    <div style=" width: 60%">
      <!-- form area -->
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="Name: " style="margin-left: 2px">
          <el-input v-model="form.name" placeholder="Enter name"></el-input>
        </el-form-item>
        <el-form-item label="Email: " style="margin-left: 2px">
          <el-input v-model="form.email" placeholder="Enter email"></el-input>
        </el-form-item>
        <el-form-item label="User ID: " style="margin-left: 2px">
          <el-input v-model="form.id" placeholder="-- Cannot be changed --" :disabled="true"></el-input>
        </el-form-item>
        <el-form-item label="Phone: " style="margin-left: 2px">
          <el-input v-model="form.phone_number" placeholder="Enter phone number"></el-input>
        </el-form-item>
        <el-form-item label="Gender: " style="margin-left: 2px">
          <el-select v-model="form.gender" placeholder="Please select">
            <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Alamat: " style="margin-left: 2px">
          <el-input v-model="form.address" placeholder="Enter Alamat"></el-input>
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
  name: "Edit",
  data() {
    const checkGender = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter the gender'));
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
      if (!/^[1,2,3,4,5,6,7,8,9][0-9]{9}$/.test(value)) {
        callback(new Error('Illegal phone number'));
      }
      callback()
    }

    return {
      form: {},

      options: [{
        value: 'L',
        label: 'Laki-laki'
      }, {
        value: 'P',
        label: 'Perempuan'
      }
      ],

      // rules to check the input values
      rules: {
        // cannot be empty
        name: [{ required: true, message: 'Please enter name', trigger: 'blur' }],
        address: [{ required: true, message: 'Please enter the address', trigger: 'blur' }],
        // more restrictions
        gender:[{ required: true, validator: checkGender, trigger: 'blur' }],
        email:[{ required: true, validator: checkEmail, trigger: 'blur' }],
        phone_number:[{ required: true, validator: checkPhone, trigger: 'blur' }]
      }
    }
  },

  created() {
    const id = this.$route.query.id
    request.get("api/member/" + id).then(res => {
      this.form = res
    })
  },

  methods: {
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          request.put('api/member/' + this.$route.query.id, this.form).then(res => {
            if(res.code == '200') {
              this.$notify.success('Updated')
              this.$router.push("/userList")
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