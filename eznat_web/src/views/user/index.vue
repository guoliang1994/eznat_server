<style lang='less'>
.structure {
  margin: 20px;
  > :nth-child(2) {
    margin-top: 20px;
  }
}
</style>

<template>
  <div
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.4)"
    class="structure"
  >
    <el-button
      size="mini"
      type="success"
      icon="el-icon-plus"
      @click="openCreateView(pureForm, 'create')"
    >新增用户</el-button>
    <el-table
      ref="multipleTable"
      :data="tableData"
      :border="true"
      tooltip-effect="dark"
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" />
      <el-table-column prop="id" label="ID" sortable width="100" />
      <el-table-column label="姓名">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="账号">
        <template slot-scope="scope">{{ scope.row.account }}</template>
      </el-table-column>
      <el-table-column label="状态">
        <template slot-scope="scope">
          <el-tag :type="isFrozen(scope.row.frozen)"> {{ scope.row.frozen == 0 ? '正常' : '冻结' }} </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="手机">
        <template slot-scope="scope">{{ scope.row.mobile }}</template>
      </el-table-column>
      <el-table-column label="操作" width="300" right>
        <template slot-scope="scope">
          <el-popover v-model="scope.row.visible" placement="right" width="auto" trigger="click">
            <p>警告：删除后将无法恢复！</p>
            <div style="text-align: right; margin: 0">
              <el-button
                type="warning"
                size="mini"
                @click="handleDelete(scope.row.id)"
              >确定</el-button>
              <el-button size="mini" type="primary" @click="scope.row.visible = false">取消</el-button>
            </div>
            <el-button slot="reference" size="mini" type="danger" icon="el-icon-delete">删除</el-button>
          </el-popover>
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-edit-outline"
            @click="openCreateView(scope.row, 'update')"
          >编辑</el-button>
          <el-button
            size="mini"
            :type="isFrozen(scope.row.frozen)"
            icon="el-icon-edit-outline"
            @click="frozen(scope.row)"
          >{{ scope.row.frozen == 0 ? '冻结' : '解冻' }}</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 编辑 && 新增弹窗 -->
    <el-dialog width="40%" :title="title" :visible.sync="dialogFormVisible">
      <el-form label-position="left" :model="form">
        <el-row :gutter="20">
          <el-col :span="16">
            <el-form-item label="姓名" :label-width="formLabelWidth">
              <el-input v-model="form.name" autocomplete="off" />
            </el-form-item>
            <el-form-item label="账号" :label-width="formLabelWidth">
              <el-input v-model="form.account" autocomplete="off" />
            </el-form-item>
            <el-form-item label="密码" :label-width="formLabelWidth">
              <el-input v-model="form.password" show-password autocomplete="off">******</el-input>
            </el-form-item>
            <el-form-item label="手机" :label-width="formLabelWidth">
              <el-input v-model="form.mobile" autocomplete="off" />
            </el-form-item>
          </el-col>
          <el-col :span="6" />
        </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="handleCreate">确 定</el-button>
      </div>
    </el-dialog>
    <!-- 批量删除选择 -->
    <div style="margin-top: 20px">
      <el-button type="danger" @click="handleDelete('0,0')">批量删除</el-button>
      <el-button type="primary" @click="toggleSelection()">取消选择</el-button>
    </div>
    <!-- 分页 -->
    <el-pagination
      :current-page="page.currentPage"
      :page-sizes="page.choosePagesize"
      :total="page.count"
      :page-size="page.pagesize"
      style="margin-top:20px;"
      layout="total, sizes, prev, pager, next, jumper"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
  </div>
</template>

<script>
import api from '@/api/users'
import tableData from '@/mixins/tableData'
export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      form: {
        id: '',
        name: '九九一十八',
        account: 'jiujiu18',
        mobile: '15870399165',
        password: 123456,
        avatar: '',
        frozen: 0
      }
    }
  },
  created() {
  },
  methods: {
    frozen(row) {
      this.api.frozen(row).then(res => {
        this.api.retrieve(this.query)
        row.frozen = 1 - row.frozen
      })
    }
  }
}
</script>
