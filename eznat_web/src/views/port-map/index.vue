<style lang='less'>
.structure {
  path: 1;
  margin: 20px;
  .server-status{
    path: 1-2;
    font-weight: bold;
    font-size: 1.2em;
    background: #fff;
    margin: 20px 0;
    color: red;
  }
}
</style>

<template>
  <div class="structure">
    <div>
      <el-button
      size="mini"
      type="success"
      icon="el-icon-plus"
      @click="openCreateView(pureForm, 'create')"
      >新增映射</el-button>
      <el-button size="mini" @click="start()" type="primary">启动服务</el-button>
      <el-button size="mini" @click="restart()" type="success">重启服务</el-button>
      <el-button size="mini" @click="stop()" type="danger">停止服务</el-button>
      <!-- <el-button size="mini" @click="reload()" type="warning">重载服务</el-button> -->
      <el-button size="mini" @click="status()">查看状态</el-button>
    </div>

    <div
      class="server-status"
      v-loading="loading"
      element-loading-text="正在加载服务端运行状态..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(255, 255, 255, 0.7)">
      服务器状态：{{ serverStatus}}
    </div>

    <el-table
      ref="multipleTable"
      :data="tableData"
      :border="true"
      tooltip-effect="dark"
      style="width: 100%"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column prop="id" label="ID" sortable width="100"></el-table-column>
      <el-table-column label="客户端">
        <template slot-scope="scope">{{ scope.row.client }}</template>
      </el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="远程端口">
        <template slot-scope="scope">{{ scope.row.remote_port }}</template>
      </el-table-column>
      <el-table-column label="本地IP">
        <template slot-scope="scope">{{ scope.row.local_ip }}</template>
      </el-table-column>
      <el-table-column label="本地端口">
        <template slot-scope="scope">{{ scope.row.local_port }}</template>
      </el-table-column>
      <el-table-column label="操作" width="200" right>
        <template slot-scope="scope">
          <el-popover placement="right" width="auto" trigger="click" v-model="scope.row.visible">
            <p>警告：删除后将无法恢复！</p>
            <div style="text-align: right; margin: 0">
              <el-button
                type="warning"
                size="mini"
                @click="handleDelete(scope.row.id)"
              >确定</el-button>
              <el-button size="mini" type="primary" @click="scope.row.visible = false">取消</el-button>
            </div>
            <el-button size="mini" type="danger" icon="el-icon-delete" slot="reference">删除</el-button>
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
            icon="el-icon-video-play"
            v-if="is_on==1"
            @click="openCreateView(scope.row, 'update')"
          >启动</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog width="25%" :title="title" :visible.sync="dialogFormVisible">
      <el-form label-position="left" :model="form">
        <el-form-item label="名称" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="远程端口" :label-width="formLabelWidth">
          <el-input v-model="form.remote_port" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="本地IP" :label-width="formLabelWidth">
          <el-input v-model="form.local_ip" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="本地端口" :label-width="formLabelWidth">
          <el-input v-model="form.local_port" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="描述" :label-width="formLabelWidth">
          <el-input v-model="form.description" autocomplete="off"></el-input>
        </el-form-item>
         <el-form-item label="客户端" :label-width="formLabelWidth">
          <el-select v-model="form.channel" placeholder="请选择">
            <el-option
              v-for="item in client_list"
              :key="item.channel"
              :label="item.name"
              :value="item.channel">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="handleCreate">确 定</el-button>
      </div>
    </el-dialog>

    <!-- <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="page.currentPage"
      :page-sizes="page.choosePagesize"
      :page-size="page.pagesize"
      style="margin-top:20px;"
      layout="total, sizes, prev, pager, next, jumper"
      :total="page.count">
    </el-pagination> -->
  </div>
</template>

<script>
import api from '@/api/port-map'
import client from '@/api/client'
import serviceManage from '@/api/server-manage'
import tableData from '@/mixins/tableData'
import { setInterval } from 'timers';
export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      is_on: 0,
      roles: [],
      form: {
        id: '',
        name: '',
        remote_port: '',
        local_ip: '127.0.0.1',
        local_port: '',
        description: '无',
        channel: ''
      },
      serverStatus: "正在获取中....",
      loading: true,
      client_list: []
    }
  },
  created() {
    this.status()
    let that = this
    client.retrieve().then(res => {
      that.client_list = res.data
    })
  },
  methods: {
    stop() {
      let that = this
      serviceManage.stop().then(res => {
        that.status()
      })
    },
    start() {
      let that = this
      serviceManage.start().then(res => {
        that.status()
      })
    },
    restart() {
      let that = this
      serviceManage.restart().then(res => {
         that.status()
      })
    },
    reload() {
      let that = this
      serviceManage.reload()
      that.status()
    },
    status() {
      let that = this
      this.loading = true
      serviceManage.status().then(res => {
        that.serverStatus = res.out.length > 2 ? "运行中" : "已经停止";
        that.loading = false
      })
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
      let that = this
      console.log(this.submit)
      switch (this.submit) {
        case 'create':
          api.create(this.form).then(res => {
            this.retrieve()
            that.dialogFormVisible = false;
          })
          break
        case 'update':
          api.update(this.form).then(res => {
            this.retrieve()
            that.dialogFormVisible = false
          })
          break
      }
    }
  }
}
</script>
