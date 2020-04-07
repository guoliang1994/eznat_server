/*
  此混入类用于后台表格展示页面。
  1.自动检测page和pagesize的变化，然后请求后台数据
  2.自动拷贝出form的内容到pureForm
  3.通过this.query.xxx新增查询条件，条件变化之后自动请求
  4.多选删除支持 multipleSelection
*/

import { indexOf } from 'codemirror/src/util/misc'
export default {
  data() {
    return {
      pureForm: [], // 保留纯净的表单结构，新增的时候需要使用
      dialogFormVisible: false,
      formLabelWidth: '85px',
      title: '新增', // 弹窗标题
      submit: 'create', // 用一个弹窗区分提交类型
      tableData: [], // 表格数据
      multipleSelection: [], // 多选
      chooseRow: [], // 选择哪一行
      query: { }, // 筛选条件
      loading: true,
      page: { // 分页相关
        pagesize: 10,
        count: 1,
        currentPage: '',
        choosePagesize: [2, 5, 10, 20, 30, 40, 50] // 2 for test
      }
    }
  },
  computed: {
    isOnline() {
      return function(status) {
        if (status == 1) {
          return 'success'
        } else {
          return 'danger'
        }
      }
    },
    isFrozen() {
      return function(status) {
        if (status == 0) {
          return 'success'
        } else {
          return 'danger'
        }
      }
    }
  },
  created() {
    this.pureForm = Object.assign({}, this.form) // 将表单结构clone到pureForm
    this.page.currentPage = 1 // 触发监听器
  },
  watch: {
    'page.pagesize'() {
      this.query = Object.assign(this.query, { limit: this.page.pagesize })
      this.retrieve(this.query)
    },
    'page.currentPage'() {
      this.query = Object.assign(this.query, { limit: this.page.pagesize, page: this.page.currentPage })
      this.retrieve(this.query)
    },
    'query'() {
      this.retrieve(this.query) // 查询条件发生变化时就获取数据
    }
  },
  methods: {
    retrieve() {
      this.api.retrieve(this.query).then(res => {
        this.tableData = res.data.data
        this.page.count = res.data.total
        this.loading = false
      })
    },
    popForm(rowData, type) {
      console.log('子类去实现这个接口,弹窗编辑或新增')
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row)
        })
      } else {
        this.$refs.multipleTable.clearSelection()
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = val
    },
    handleSizeChange(pagesize) {
      this.page.pagesize = pagesize
    },
    handleCurrentChange(currentPage) {
      this.page.currentPage = currentPage
    },
    handleCancle() {
      this.dialogFormVisible = false
    },
    openCreateView(row, type) {
      this.dialogFormVisible = true
      this.form = Object.assign({}, row)
      switch (type) {
        case 'create':
          this.title = '新增'
          this.submit = type
          break
        case 'update':
          this.title = '修改'
          this.submit = type
          break
      }
    },
    handleCreate() {
      const that = this
      console.log(this.submit)
      this.api.update_or_create(this.form).then(res => {
        this.retrieve(this.query)
        that.dialogFormVisible = false
      })
    },
    handleDelete(id) {
      if (indexOf(id, ',') === -1) {
        this.api.delete(id).then(res => {
          this.retrieve()
        })
      } else if (this.multipleSelection.length > 0) {
        console.log(this.multipleSelection)
        this.$confirm('您正在执行批量删除操作', '警告', {
          confirmButtonText: '确定',
          confirmButtonClass: 'el-button--danger',
          cancelButtonClass: 'el-button--primary',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          let ids = ''
          this.multipleSelection.forEach(row => {
            ids += row.id + ','
          })
          this.api.delete(ids).then(res => {
            this.retrieve()
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消删除'
          })
        })
      }
    }
  }
}
