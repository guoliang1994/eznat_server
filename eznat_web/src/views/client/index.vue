<style lang='less'>
.structure {
  margin: 20px;
  > :nth-child(2) {
    margin-top: 20px;
  }
}
.el-input {
  margin-bottom: 10px;
  width: 300px;
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
    <!-- 搜索栏 -->
    <el-input
      v-model="search_account_or_username"
      placeholder="根据用户查询"
      prefix-icon="el-icon-search"
      @input="search()"
    />
    <el-button
      type="success"
      icon="el-icon-plus"
      @click="openCreateView(pureForm, 'create')"
    >新增客户端</el-button>
    <el-table
      ref="multipleTable"
      :data="tableData"
      :border="true"
      tooltip-effect="dark"
      style="width: 100%;"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55" />
      <el-table-column prop="id" label="ID" sortable width="100" />
      <el-table-column label="用户">
        <template slot-scope="scope">{{ scope.row.user_name }} / {{ scope.row.account }} </template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="设备类型">
        <template slot-scope="scope">{{ scope.row.type }}</template>
      </el-table-column>
      <el-table-column label="数据总线" width="300">
        <template slot-scope="scope">{{ scope.row.data_bus }}</template>
      </el-table-column>
      <el-table-column label="设备状态">
        <template slot-scope="scope"> <el-tag :type="isOnline(scope.row.is_online)">{{ scope.row.is_online ==1 ? '在线' : '离线' }} </el-tag></template>
      </el-table-column>
      <el-table-column label="备注">
        <template slot-scope="scope">
          {{ scope.row.description }}
        </template>
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
        </template>
      </el-table-column>
    </el-table>
    <!--编辑 && 新增弹窗-->
    <el-dialog width="40%" :title="title" :visible.sync="dialogFormVisible">
      <el-form label-position="left" :model="form">
        <el-row :gutter="20">
          <el-col :span="16">
            <el-form-item label="名称" :label-width="formLabelWidth">
              <el-input v-model="form.name" autocomplete="off" />
            </el-form-item>
            <el-form-item label="设备类型" :label-width="formLabelWidth">
              <el-select v-model="form.type" placeholder="请选择">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
            <el-form-item label="描述" :label-width="formLabelWidth">
              <el-input v-model="form.description" autocomplete="off" />
            </el-form-item>
            <el-form-item label="数据总线" :label-width="formLabelWidth">
              <el-input v-model="form.data_bus" autocomplete="off" readonly="" />
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
      :page-size="page.pagesize"
      :total="page.count"
      layout="total, sizes, prev, pager, next, jumper"
      style="margin-top:20px;"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
  </div>
</template>

<script>
import api from '@/api/client'
import tableData from '@/mixins/tableData'
export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      search_account_or_username: '',
      form: {
        id: '',
        name: '',
        type: 'windows',
        description: '无',
        data_bus: '系统自动生成',
        is_online: 0
      },
      options: [{
        value: 'windows',
        label: 'windows'
      }, {
        value: 'Linux',
        label: 'Linux'
      }, {
        value: '苹果系统',
        label: '苹果系统'
      }
      ]
    }
  },
  created() {

  },
  methods: {
    search() {
      const that = this
      that.loading = true
      this.query = { client_id: this.form.client_id, search_account_or_username: this.search_account_or_username }
    }
  }
}
</script>
