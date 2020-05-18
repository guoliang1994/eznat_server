<style lang='less'>
.structure {
  path: 1;
  margin: 20px;
  .search-box {
    path: 1-3;
    margin: 10px 0;
  }
}
.el-loading-spinner i{
  color: white;
  font-size: 1.2em;
}
.el-loading-text {
  color: white !important;
  font-size: 1.2em !important;
}
.el-button--mini {
    padding: 5px 6px;
    font-size: 12px;
    border-radius: 3px;
    margin-top: 5px;
}
.el-button + .el-button {
    margin-left: 0px;
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
    <div>
      <el-button
        size="mini"
        type="primary"
        icon="el-icon-plus"
        @click="openCreateView(pureForm, 'create')"
      >新增映射</el-button>
      <el-button size="mini" type="success" @click="patch('','restart', true)">全部启动</el-button>
      <el-button size="mini" type="warning" @click="patch('', 'stop', true)">全部停止</el-button>
      <el-button size="mini" type="warning" @click="patch('', 'stop', true)">重启通道服务</el-button>
    </div>
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
          v-model="search_account_or_username"
          placeholder="根据用户查询"
          prefix-icon="el-icon-search"
          @input="search()"
        />
      </div>
    </div>
    <!-- 表格数据 -->
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
      <el-table-column label="用户">
        <template slot-scope="scope">
          {{ scope.row.user_name }} /  {{ scope.row.account }}
        </template>
      </el-table-column>
      <el-table-column label="客户端">
        <template slot-scope="scope">
          {{ scope.row.client_name }}
        </template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">
          {{ scope.row.name }}
        </template>
      </el-table-column>
      <el-table-column label="客户端&&服务端状态">
        <template slot-scope="scope">
          <el-tag :type="isOnline(scope.row.client_is_online)">
            {{ scope.row.client_is_online == 1 ? '在线': '离线' }}
          </el-tag>
          <el-tag :type="isOnline(scope.row.is_online)">
            {{ scope.row.is_online == 1 ? '在线': '离线' }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="端口映射">
        <template slot-scope="scope">
          {{ scope.row.remote_port }} <i class="el-icon-refresh" />
          {{ scope.row.local_ip }}:{{ scope.row.local_port }}
        </template>
      </el-table-column>
      <el-table-column label="使用流量">
        <template slot-scope="scope">
          {{ scope.row.o + scope.row.i }}M
        </template>
      </el-table-column>
      <el-table-column label="操作" width="150">
        <template slot-scope="scope">
          <el-popover v-model="scope.row.visible" placement="right" width="auto">
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
            type="success"
            icon="el-icon-caret-right"
            @click="patch(scope.row, 'restart', true)"
          >启动</el-button>
          <el-button
            size="mini"
            type="warning"
            icon="el-icon-remove"
            @click="patch(scope.row, 'stop', true)"
          >停止</el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 新增 && 编辑弹窗 -->
    <el-dialog width="25%" :title="title" :visible.sync="dialogFormVisible">
      <el-form label-position="left" :model="form">
        <el-form-item label="名称" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off" />
        </el-form-item>
        <el-form-item label="远程端口" :label-width="formLabelWidth">
          <el-input v-model="form.remote_port" autocomplete="off" />
        </el-form-item>
        <el-form-item label="本地IP" :label-width="formLabelWidth">
          <el-input v-model="form.local_ip" autocomplete="off" />
        </el-form-item>
        <el-form-item label="本地端口" :label-width="formLabelWidth">
          <el-input v-model="form.local_port" autocomplete="off" />
        </el-form-item>
        <el-form-item label="描述" :label-width="formLabelWidth">
          <el-input v-model="form.description" autocomplete="off" />
        </el-form-item>
        <el-form-item label="客户端" :label-width="formLabelWidth">
          <el-select v-model="form.client_id" clearable placeholder="请选择">
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
      <el-button size="small" type="danger" icon="el-icon-delete" @click="handleDelete('0,0')">删除勾选</el-button>
      <el-button size="small" type="primary" @click="toggleSelection()">取消选择</el-button>
      <el-button size="small" type="success" icon="el-icon-caret-right" @click="patch('','restart')">启动勾选</el-button>
      <el-button size="small" type="warning" icon="el-icon-remove" @click="patch('','stop')">停止勾选</el-button>
    </div>
    <!-- 分页 -->
    <el-pagination
      :current-page="page.currentPage"
      :page-sizes="page.choosePagesize"
      :page-size="page.pagesize"
      :total="page.count"
      style="margin-top:20px;"
      layout="total, sizes, prev, pager, next, jumper"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
  </div>
</template>

<script>
import api from '@/api/port-map'
import client from '@/api/client'
import serviceManage from '@/api/server-manage'
import tableData from '@/mixins/tableData'

export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      form: {
        id: '',
        name: '',
        remote_port: '',
        local_ip: '127.0.0.1',
        local_port: '',
        description: '无',
        client_id: '',
        is_online: 0
      },
      client_list: [],
      search_account_or_username: ''
    }
  },
  created() {
    const that = this
    client.my_client().then(res => {
      that.client_list = res.data
    })
  },
  methods: {
    handlePatch(script_list, option) {
      this.query.script_list = script_list
      switch (option) {
        case 'stop':
          serviceManage.stop(this.query).then(res => {
            this.loading = false
            this.retrieve()
          })
          break
        case 'restart':
          serviceManage.restart(this.query).then(res => {
            this.loading = false
            this.retrieve()
          })
          break
        default:
          this.loading = false
          break
      }
    },
    patch(row, option, single = false) {
      if (this.multipleSelection.length > 0) {
        let msg
        if (option === 'stop') {
          msg = '停止'
        } else {
          msg = '启动'
        }
        console.log(this.multipleSelection)
        this.$confirm('确定要批量' + msg + '服务吗？', '警告', {
          confirmButtonText: '确定',
          confirmButtonClass: 'el-button--danger',
          cancelButtonClass: 'el-button--primary',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          const that = this
          that.loading = true
          let scriptList = ''
          this.multipleSelection.forEach(row => {
            scriptList += row.remote_port + '_' + row.local_port + '.php,'
          })
          this.handlePatch(scriptList, option)
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '取消操作'
          })
        })
      }
      if (single === true) {
        if (row !== '') {
          const scriptList = row.remote_port + '_' + row.local_port + '.php,'
          this.handlePatch(scriptList, option)
        } else {
          this.handlePatch('', option)
        }
      }
    },
    search() {
      this.loading = true
      this.query = {
        client_id: this.form.client_id,
        search_account_or_username: this.search_account_or_username
      }
    }
  }
}
</script>
