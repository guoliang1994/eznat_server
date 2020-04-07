<template>
  <el-card style="margin-bottom:20px;">
    <div slot="header" class="clearfix">
      <span>关于我</span>
    </div>

    <div class="user-profile">
      <div class="box-center">
        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false">
          <div>Hello</div>
          {{ user.role }}
        </pan-thumb>
      </div>
      <div class="box-center">
        <div class="user-name text-center">{{ user.name }}</div>
        <div class="user-role text-center text-muted">{{ user.role | uppercaseFirst }}</div>
      </div>
    </div>

    <div class="user-bio">
      <div class="user-education user-bio-section">
        <div class="user-bio-section-header"><svg-icon icon-class="education" /><span>个人简介</span></div>
        <div class="user-bio-section-body">
          <div class="text-muted">
            &nbsp;&nbsp;&nbsp;&nbsp;毕业于兴义民族师范学院数学系数学与应用数学2012级，什么都研究，什么都不懂。迷途在计算机世界的文艺青年...今年二十六，喜欢的姑娘叫李江艳，我还会一直追求她，直到她找到她喜欢的人，当然我希望那个人是我。
          </div>
        </div>
      </div>

      <div class="user-skills user-bio-section">
        <div class="user-bio-section-header"><svg-icon icon-class="skill" /><span>掌握技能</span></div>
        <div class="user-bio-section-body">
          <div class="progress-item">
            <span>PHP，以及相关PHP框架</span>
            <el-progress :percentage="90" />
          </div>
          <div class="progress-item">
            <span>Redis，Memcache，MongoDB</span>
            <el-progress :percentage="90" />
          </div>
          <div class="progress-item">
            <span>Vue，JavaScript</span>
            <el-progress :percentage="70" />
          </div>
          <div class="progress-item">
            <span>负载均衡</span>
            <el-progress :percentage="60" />
          </div>
          <div class="progress-item">
            <span>Css</span>
            <el-progress :percentage="75" />
          </div>
          <div class="progress-item">
            <span>MySQL</span>
            <el-progress :percentage="98" />
          </div>
          <div class="progress-item">
            <span>Docker</span>
            <el-progress :percentage="65" />
          </div>
          <div class="progress-item">
            <span>Git</span>
            <el-progress :percentage="90" />
          </div>
          <div class="progress-item">
            <span>Linux</span>
            <el-progress :percentage="70" status="success" />
          </div>
        </div>
      </div>
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb'

export default {
  components: { PanThumb },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          email: '',
          avatar: '',
          roles: ''
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
 .box-center {
   margin: 0 auto;
   display: table;
 }

 .text-muted {
   color: #777;
   line-height: 1.6em;
   font-size: 1.1em;
 }

 .user-profile {
   .user-name {
     font-weight: bold;
   }

   .box-center {
     padding-top: 10px;
   }

   .user-role {
     padding-top: 10px;
     font-weight: 400;
     font-size: 14px;
   }

   .box-social {
     padding-top: 30px;

     .el-table {
       border-top: 1px solid #dfe6ec;
     }
   }

   .user-follow {
     padding-top: 20px;
   }
 }

 .user-bio {
   margin-top: 20px;
   color: #606266;

   span {
     padding-left: 4px;
   }

   .user-bio-section {
     font-size: 14px;
     padding: 15px 0;

     .user-bio-section-header {
       border-bottom: 1px solid #dfe6ec;
       padding-bottom: 10px;
       margin-bottom: 10px;
       font-weight: bold;
     }
   }
 }
</style>
