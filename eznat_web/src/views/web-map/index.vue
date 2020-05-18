<style lang='less'>
.structure {
  path: 1;
  margin: 20px;
  .search-box {
    path: 1-3;
    margin: 10px 0;
    .server-status {
      color: hotpink;
      margin-left: 15px;
      font-weight: bold;
    }
  }
}
.el-loading-spinner i {
  color: white;
  font-size: 1.2em;
}
.el-loading-text {
  color: white !important;
  font-size: 1.2em !important;
}
.el-input {
  width: 220px;
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
    <div class="structure">
      <el-button
        size="mini"
        type="success"
        icon="el-icon-plus"
        @click="openCreateView(pureForm, 'create')"
      >新增网站映射</el-button>
      <!-- 查询框 -->
      <div class="search-box">
        <div class="demo-input-suffix">
          <el-select v-model="form.client_id" clearable placeholder="请选择" @change="search()">
            <el-option
              v-for="item in client_list"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
          <el-input
            v-model="input2"
            placeholder="根据用户查询"
            prefix-icon="el-icon-search"
          />
        </div>
      </div>
      <!-- 数据表格 -->
      <el-table
        ref="multipleTable"
        :data="tableData"
        :border="true"
        tooltip-effect="dark"
        style="width: 100%"
        @selection-change="handleSelectionChange"
      >
        <el-table-column type="selection" width="55"/>
        <el-table-column prop="id" label="ID" sortable width="100"/>
        <el-table-column label="用户">
          <template slot-scope="scope">{{ scope.row.user_name }} / {{ scope.row.account }}</template>
        </el-table-column>
        <el-table-column label="客户端">
          <template slot-scope="scope">{{ scope.row.client_name }}</template>
        </el-table-column>
        <el-table-column label="映射域名">
          <template slot-scope="scope">{{ scope.row.domain }}</template>
        </el-table-column>
        <el-table-column label="类型">
          <template slot-scope="scope">
            <el-tag type="success">{{ scope.row.protocol }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="本地IP \ 域名也可">
          <template slot-scope="scope">{{ scope.row.local_ip }}</template>
        </el-table-column>
        <el-table-column label="本地端口">
          <template slot-scope="scope">{{ scope.row.local_port }}</template>
        </el-table-column>
        <el-table-column label="使用流量">
          <template slot-scope="scope">
            {{ scope.row.o + scope.row.i }}M
          </template>
        </el-table-column>
        <el-table-column label="操作" width="200" right>
          <template slot-scope="scope">
            <el-popover v-model="scope.row.visible" placement="right" width="auto">
              <p>警告：删除后将无法恢复！</p>
              <div style="text-align: right; margin: 0">
                <el-button type="warning" size="mini" @click="handleDelete(scope.row.id)">确定</el-button>
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
      <!-- 弹窗编辑 -->
      <el-dialog width="25%" :title="title" :visible.sync="dialogFormVisible">
        <el-form label-position="left" :model="form">
          <el-form-item label="协议" :label-width="formLabelWidth">
            <el-select v-model="form.protocol">
              <el-option label="https" value="https"/>
              <el-option label="http" value="http"/>
            </el-select>
          </el-form-item>
          <el-form-item label="域名" :label-width="formLabelWidth">
            <el-input v-model="form.domain" autocomplete="off"/>
          </el-form-item>
          <el-form-item label="本地IP\域名" :label-width="formLabelWidth">
            <el-input v-model="form.local_ip" autocomplete="off"/>
          </el-form-item>
          <el-form-item label="本地端口" :label-width="formLabelWidth">
            <el-input v-model="form.local_port" autocomplete="off"/>
          </el-form-item>
          <el-form-item label="客户端" :label-width="formLabelWidth">
            <el-select v-model="form.client_id" placeholder="请选择">
              <el-option
                v-for="item in client_list"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
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
  </div>
</template>

<script>
import api from '@/api/web-map'
import client from '@/api/client'
import tableData from '@/mixins/tableData'

export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      roles: [],
      form: {
        id: 0,
        domain: '',
        local_ip: '127.0.0.1',
        local_port: '',
        description: '无',
        client_id: null
      },
      loading: true,
      loading_data: true,
      client_list: []
    }
  },
  created() {
    const that = this
    client.my_client(this.query).then(res => {
      that.client_list = res.data
    })
  },
  methods: {
    search() {
      this.loading = true
      this.query = {
        client_id: this.form.client_id
      }
    }
  }
}
</script>
