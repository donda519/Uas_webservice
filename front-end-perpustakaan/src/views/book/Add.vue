<template>
  <div style="padding: 10px;">
    <div style="font-size: 40px; font-family: Arial; margin-bottom: 5px">Add New Book</div>
    <div style="width: 60%">
      <!-- form area -->
      <el-form :inline="true" :model="form" :rules="rules" ref="ruleForm">
        <el-form-item label="ISBN: " style="margin-left: 2px" prop="isbn">
          <el-input v-model="form.isbn" placeholder="Enter isbn"></el-input>
        </el-form-item>
        <!-- <el-form-item label="Description: " style="margin-left: 2px;" prop="description">
          <el-input style="width: 500px" type="textarea" v-model="form.description" placeholder="Enter description"></el-input>
        </el-form-item> -->
        <el-form-item label="Title: " style="margin-left: 2px" prop="title">
          <el-input v-model="form.title" placeholder="Enter title"></el-input>
        </el-form-item>
        <el-form-item label="Category: " style="margin-left: 2px">
          <el-cascader
              :props="{value: 'id', label: 'name'}"
              v-model="form.category_id"
              :options="categories"></el-cascader>
        </el-form-item>
        <el-form-item label="Author: " style="margin-left: 2px" prop="author">
          <el-cascader
              :props="{value: 'id', label: 'name'}"
              v-model="form.author_id"
              :options="authors"></el-cascader>
        </el-form-item>
        <el-form-item label="Publisher: " style="margin-left: 2px" prop="publisher">
          <el-cascader
              :props="{value: 'id', label: 'name'}"
              v-model="form.publisher_id"
              :options="publishers"></el-cascader>
        </el-form-item>
        <el-form-item label="Tahun Rilis: " style="margin-left: 2px" prop="year">
          <!-- <el-date-picker
              v-model="form.year"
              type="year"
              format="yyyy"
              value-format="yyyy"
              placeholder="Select a date">
          </el-date-picker> -->
          <el-input type="number" class="form-control" id="year" placeholder="year" v-model="form.year" required autocomplete="new-year"></el-input>
        </el-form-item>
        <el-form-item id="cover" label="Cover: " style="margin-left: 2px;" prop="cover">
          <!-- <el-input v-model="form.cover" placeholder="Enter cover url"></el-input> -->
          <el-upload
            class="upload-demo"
            action=""
            :auto-upload="false"
            :on-change="handleChange"
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :file-list="fileList"
            list-type="picture">
            <el-button size="small" type="primary">Click to upload</el-button>
            <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
          </el-upload>
        </el-form-item>
        <el-form-item label="Jumlah : " style="margin-left: 2px" prop="qty">
          <el-input type="number" class="form-control" id="qty" placeholder="Jumlah buku" v-model="form.qty" required ></el-input>
        </el-form-item>
        <el-form-item label="Harga : " style="margin-left: 2px" prop="price">
          <el-input type="number" class="form-control" id="qty" placeholder="Harga buku" v-model="form.price" required ></el-input>
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
    const checkNumeric = (rule, value, callback) => {
      if(!value) {
        callback(new Error('This blank cannot be empty'));
      }
      if(!/^[0-9]*$/.test(value)) {
        callback(new Error('Please enter a numerical value'))
      }
      if(parseInt(value) < 0) {
        callback(new Error('Please enter a number larger than 0'))
      }
      callback()
    };

    const checkISBN = (rule, value, callback) => {
      if(!value) {
        callback(new Error('Please enter the book\'s ISBN'))
      }
      callback()
    }

    return {
      fileList: [],
      file: null,
      form: {},
      categories: [],
      authors: [],
      publishers: [],
      rules: {
        // cannot be empty
        qty: [{ required: true, message: "Please enter quantity book", trigger: 'blur' }],
        price: [{ required: true, message: "Please enter price book", trigger: 'blur' }],
        title: [{ required: true, message: "Please enter the book's name", trigger: 'blur' }],
        category_id: [{ required: true, message: "Please enter the book's category", trigger: 'blur' }],
        author_id: [{ required: true, message: "Please enter the book's author", trigger: 'blur' }],
        publisher_id: [{ required: true, message: "Please enter the book's publisher", trigger: 'blur' }],
        year: [{ required: true, message: "Please select a date", trigger: 'blur' }],
        // more restrictions
        isbn: [{ required: true,  validator: checkISBN, trigger: 'blur' }],
      }
    }
  },

  created() {
    request.get('api/category').then(res => {
      this.categories = res ? res.data.original.data : [];
    })

    request.get('api/author').then(res => {
      this.authors = res ? res.data.original.data : [];
    })

    request.get('api/publisher').then(res => {
      this.publishers = res ? res.data.original.data : [];
    })
  },

  methods: {
    handleRemove(file, fileList) {
        console.log(file, fileList);
        this.fileList.splice(0,1,{name: 'cover', url: this.form.cover});
        // this.fileList.push({name: 'cover', url: this.form.cover})
        
    },
    handlePreview(file) {
      console.log(file);
    },
    handleChange(val){
      console.log(val);
      this.fileList.splice(0,1,val)
    },
    save() {
      this.$refs['ruleForm'].validate((valid) => {
        if(valid) {
          
          if(this.fileList[0].url != this.form.cover) this.form.cover = this.fileList[0].raw;
          console.log(this.form);

          if(typeof this.form.cover === 'string' || this.form.cover instanceof String){
            var index = this.form.cover.indexOf("books");
  
            // Jika "books" ditemukan, hapus bagian sebelumnya
            if (index !== -1) {
                this.form.cover = this.form.cover.substring(index);
            }

          }

          const formData = new FormData();
          formData.append('isbn', this.form.isbn);
          formData.append('title', this.form.title);
          formData.append('year', this.form.year);
          formData.append('publisher_id', this.form.publisher_id);
          formData.append('author_id', this.form.author_id);
          formData.append('category_id', this.form.category_id);
          formData.append('qty', this.form.qty);
          formData.append('price', this.form.price);
          formData.append('cover', this.form.cover);
          request.post('api/book', formData).then(res => {
                  if(res.code == '200') {
                    this.$notify.success('Updated')
                    this.$router.push("/BookList")
                  } else {
                    this.$notify.error(res.msg)
                  }
                })
          }
        })
    },
    // save() {
    //   this.$refs['ruleForm'].validate((valid) => {
    //     if(valid) {
    //       request.post('book/save', this.form).then(res => {
    //         if(res.code === '200') {
    //           this.$notify.success('Submitted')
    //           this.$refs['ruleForm'].resetFields()
    //         } else {
    //           this.$notify.error(res.msg)
    //         }
    //       })
    //     }
    //   })
    // },
  }
}
</script>

<style scoped>

</style>