<template>
  <div>
    <!-- search area -->
    <div style="margin-bottom: 2px; margin-top: 2px">
      <el-input v-model="params.username" placeholder="Enter username" style="width: 200px; margin-left: 2px"></el-input>
      <el-input v-model="params.name" placeholder="Enter book's name" style="width: 200px; margin-left: 2px"></el-input>
      <el-input v-model="params.isbn" placeholder="Enter book's isbn" style="width: 200px; margin-left: 2px"></el-input>
      <el-button type="primary" style="margin-left: 2px; height: 40px" icon="el-icon-search" @click="load">Search</el-button>
      <el-button type="warning" style="margin-left: 2px; height: 40px" icon="el-icon-refresh-right" @click="reset">Reset</el-button>
    </div>
    <!-- table area -->
    <div>
      <el-table :data="tableData" style="width: 100%" stripe>
        <el-table-column prop="id" label="Id Peminjaman" show-overflow-tooltip width="150"></el-table-column>
        <el-table-column prop="member.name" label="Nama" show-overflow-tooltip width="100"></el-table-column>
        <el-table-column prop="member.phone_number" label="Phone" width="100"></el-table-column>
        <el-table-column prop="book_name" label="Book Name" show-overflow-tooltip width="200"></el-table-column>
        <el-table-column prop="isbn" label="ISBN" width="100"></el-table-column>
        <el-table-column prop="status" label="Status" width="160"></el-table-column>
        <el-table-column prop="date_start" label="Borrow Date" width="110" :formatter="createDateFormat"></el-table-column>
        <el-table-column prop="date_end" label="Due Date" width="110" :formatter="dueDateFormat"></el-table-column>
        <el-table-column fixed="right" label="Operations">
          <template v-slot="scope">
            <el-popconfirm
                confirm-button-text='Yes'
                cancel-button-text='No'
                title="Are you sure you want to delete this row of data？"
                @confirm="del(scope.row)"
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
import moment from "moment/moment";

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
        status: 'true',
      },
    }
  },

  created() {
    this.load()
  },

  methods: {
    load() {
      request.get('api/transaction', {
        params: this.params
      }).then(res => {
        if(res.success) {
          // console.log(res.data.original.data);
          this.tableData = res.data.original.data
          if(this.tableData.length > 0) {
            this.tableData.forEach(el => {
              console.log(el);
              el.book_name = el.transaction_detail[0].book.title
              el.isbn = el.transaction_detail[0].book.isbn
            })
          }
          this.total = res.data.original.recordsTotal
        }
      })
    },

    del(row) {
      const id = row.id

      request.delete('api/transaction/' + id).then(res => {
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
        pageNum: 1,
        pageSize: 10,
        email: '',
        isbn: '',
      }
      this.value = ''
      this.load()
    },

    changePageNum(pageNum) {
      this.params.pageNum = pageNum
      this.load()
    },

    createDateFormat(row) {
      return moment(row.cdate).format("YYYY-MM-DD") // a useful tool to format datetime
    },
    dueDateFormat(row) {
      return moment(row.rdate).format("YYYY-MM-DD")
    },
    returnDateFormat(row) {
      return moment(row.adate).format("YYYY-MM-DD")
    },
  }
}
</script>

<style scoped>

</style>