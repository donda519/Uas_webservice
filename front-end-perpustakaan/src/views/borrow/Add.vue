<template>
  <div style="padding: 10px;">
    <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Add New Borrow Record</div>
    <div style=" width: 80%">
      <!-- form area -->
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="ISBN: " style="margin-left: 2px" prop="isbn">
          <el-select v-model="form.books" clearable filterable placeholder="Please select Buku" @change="selectBook">
            <el-option
                v-for="item in books"
                :key="item.id"
                :label="item.title + ` (${item.isbn})`"
                :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Book Title: " style="margin-left: 2px"  prop="title">
          <el-input v-model="form.title" placeholder="Enter book's name" disabled></el-input>
        </el-form-item>
        <el-form-item label="User ID: " style="margin-left: 2px" prop="anggota">
          <el-select v-model="form.anggota" clearable filterable placeholder="Please select an user ID" @change="selectUser">
            <el-option
                v-for="item in users"
                :key="item.id"
                :label="`(${item.id})` + ` ${item.name}`"
                :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Nama : " style="margin-left: 2px" prop="name">
          <el-input v-model="form.name" placeholder="Enter username" disabled></el-input>
        </el-form-item>
        <el-form-item label="Phone: " style="margin-left: 2px" prop="phone">
          <el-input v-model="form.phone_number" placeholder="Enter phone number" disabled></el-input>
        </el-form-item>
        
        <el-form-item label="Email: " style="margin-left: 2px" prop="email">
          <el-input v-model="form.email" placeholder="Enter email" disabled></el-input>
        </el-form-item>
        <el-form-item label="Durasi Peminjaman (hari): " style="margin-left: 2px" prop="duration">
          <template>
            <el-input-number v-model="form.duration" @change="handleChange" :min="1" :max="10"></el-input-number>
          </template>
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
import moment from "moment/moment";

export default {
  name: "Add",

  data() {
    const checkISBN = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter the book\'s ISBN'))
      }
      callback()
    }

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
      form: {
        duration: 1,
        date_start : moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
        date_end : moment(new Date()).add('days', 1).format('YYYY-MM-DD HH:mm:ss')
      },
      books: [],
      users: [],
      unitCredit: 0,
      // rules to check the input values
      rules: {
        title: [{ required: true, message: "Please enter the book's name", trigger: 'blur' }],
        anggota: [{ required: true, message: "Please enter anggota", trigger: 'blur' }],
        // more restrictions
        // isbn: [{ required: true,  validator: checkISBN, trigger: 'blur' }],
      }
    }
  },

  created() {
    // get book list
    request.get('api/book').then(res => {
      this.books = res.data.original.data
    })
    // get user list
    request.get('api/member').then(res => {
      this.users = res.data.original.data
    })
  },

  methods: {
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          this.form.books = [this.form.books];
          request.post('api/transaction', this.form).then(res => {
            if(res.code == '200') {
              this.$notify.success('Submitted')
              this.$refs['ruleForm'].resetFields()
              location.reload()
            } else {
              this.$notify.error(res.msg)
            }
          })
        }
      })
    },
    // get data from book
    selectBook() {
      // console.log(this.form.isbn)
      const book = this.form.books
      request.get("api/book/" + book).then( res => {
        this.form.title = res.title
        this.$forceUpdate()
      })
    },
    // get data from user
    selectUser() {
      const user = this.form.anggota
      request.get("api/member/" + user).then(res => {
        this.form.phone_number = res.phone_number
        this.form.name = res.name
        this.form.email = res.email
        this.$forceUpdate()
      })
    },

    handleChange() {
      this.form.date_start =  moment(new Date()).format('YYYY-MM-DD HH:mm:ss');
      this.form.date_end =  moment(new Date()).add('days', this.form.duration).format('YYYY-MM-DD HH:mm:ss');
    }
  }
}
</script>

<style scoped>

</style>