<template>
  <div>
    <!-- search area -->
    <div style="margin-bottom: 2px; margin-top: 2px">
      <el-input v-model="params.search" placeholder="Enter book's name or ISBN" style="width: 200px; margin-left: 2px"></el-input>
      <!-- <el-input v-model="params.isbn" placeholder="Enter ISBN" style="width: 200px; margin-left: 2px"></el-input> -->
      <el-button type="primary" style="margin-left: 2px; height: 40px" icon="el-icon-search" @click="load">Search</el-button>
      <el-button type="warning" style="margin-left: 2px; height: 40px" icon="el-icon-refresh-right" @click="reset">Reset</el-button>
    </div>
    <!-- table area -->
    <div>
      <el-table :data="tableData" style="width: 100%" stripe>
        <el-table-column prop="cover" label="Cover" width="100">
          <template v-slot="scope1">
            <el-image :src="scope1.row.cover" style="width: 50%; height: 50%"></el-image>
          </template>
        </el-table-column>
        <el-table-column prop="isbn" label="ISBN" width="160"></el-table-column>
        <el-table-column prop="title" label="Title" show-overflow-tooltip width="180"></el-table-column>
        <el-table-column prop="category.name" label="Category" width="130"></el-table-column>
        <el-table-column prop="author.name" label="Author" width="100"></el-table-column>
        <el-table-column prop="publisher.name" label="Publisher" show-overflow-tooltip width="100"></el-table-column>
        <el-table-column prop="year" label="Tahun Rilis" width="100"></el-table-column>
        <!-- <el-table-column prop="description" label="Description" show-overflow-tooltip width="300"></el-table-column> -->
        <!-- <el-table-column prop="created_at" label="Tanggal Input" width="110"></el-table-column> -->
        <el-table-column fixed="right" label="Operation" width="200">
          <template v-slot="scope">
            <el-button type="primary" @click="$router.push('/editBook?id=' + scope.row.id)">Edit</el-button>
            <el-popconfirm
                confirm-button-text='Yes'
                cancel-button-text='No'
                title="Are you sure you want to delete this row of data？"
                @confirm="del(scope.row.id)"
            >
              <el-button style="margin-left: 2px;" slot="reference" type="danger">Delete</el-button>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
      <!-- page -->
      <el-pagination
          style="margin-top: 5px;"
          background
          :current-page="params.pageNum"
          :page-size="params.pageSize"
          @current-change="changePageNum"
          layout="prev, pager, next"
          :total="total">
      </el-pagination>
    </div>
  </div>
</template>

<script>
import request from "@/utils/request";

export default {
  name: "List",

  data() {
    return {
      tableData: [],
      total: 0,
      params: {
        draw: 1,
        start: 0,
        length: 10,
        name: '',
      },
    }
  },

  created() {
    this.load()
  },

  methods: {
    load() {
      request.get('api/book', {
        params: this.params
      }).then(res => {
        if(res.success) {
          console.log(res.data.original.data);
          this.tableData = res.data.original.data
          this.total = res.data.original.recordsTotal
        }
      })
    },

    del(id) {
      request.delete('api/book/' + id).then(res => {
        if(res.code == '200') {
          this.$notify.success('Deleted')
          this.load()
        } else {
          this.$notify.error(res.msg)
        }
      })
    },

    reset() {
      this.params = {
        draw: 1,
        start: 0,
        length: 10,
        name: '',
      }
      this.value = ''
      this.load()
    },

    changePageNum(pageNum) {
      this.params.pageNum = pageNum
      this.load()
    }
  }
}
</script>

<style>
.el-tooltip__popper {
  max-width: 400px; /* modify the width of the popper */
}
</style>