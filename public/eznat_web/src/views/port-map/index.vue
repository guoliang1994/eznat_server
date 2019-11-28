<style lang='less'>
.structure {
  margin: 20px;
  > :nth-child(2) {
    margin-top: 20px;
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
      <el-table-column label="隧道">
        <template slot-scope="scope">{{ scope.row.channel }}</template>
      </el-table-column>
      <el-table-column label="操作" width="300" right>
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
         <el-form-item label="通道" :label-width="formLabelWidth">
          <el-input v-model="form.channel" autocomplete="off"></el-input>
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
import serviceManage from '@/api/server-manage'
import tableData from '@/mixins/tableData'
export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
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
    }
  },
  created() {
   
  },
  methods: {
    stop() {
      serviceManage.stop();
    },
    start() {
      serviceManage.start();
    },
    restart() {
      serviceManage.restart();
    },
    reload() {
      serviceManage.reload();
    },
    status() {
      serviceManage.status();
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
