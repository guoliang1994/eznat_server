<template>
  <div class="user-activity">
    <div class="post">
      <div class="user-block">
        <img class="img-circle" :src="'https://wpimg.wallstcn.com/57ed425a-c71e-4201-9428-68760c0537c4.jpg'+avatarPrefix">
        <span class="username text-muted">九九一十八</span>
        <span class="description">Shared publicly - 7:30 PM today</span>
      </div>
      <p>
        在windows端，采用docker为服务容器，配合内网穿透和xdebug，有多爽？<br />
        1.代码修改无需上传，保存即可，因为docker和宿主机采用目录映射。<br />
        2.通过内网穿透，可以断点调试所有的第三方回调，不再需要file_put_contents存储第三方回调参数，不用猜测第三方是否回调。<br />
        3.通过内网穿透，使用本机作为服务器提供服务到外网。<br />
        4.内网穿透能让你的计算机就像在公网上一样，任何人都能访问。<br />
        5.远程桌面穿透<br />
        6.数据库穿透<br />
        7.网站穿透<br />
        8.你想穿透的一切...<br />
      </p>
      <ul class="list-inline">
        <li>
          <span class="link-black text-sm">
            <i class="el-icon-share" />
            Share
          </span>
        </li>
        <li>
          <span class="link-black text-sm">
            <svg-icon icon-class="like" />
            Like
          </span>
        </li>
      </ul>
    </div>
    <div class="post">
      <div class="user-block">
        <img class="img-circle" :src="'https://wpimg.wallstcn.com/9e2a5d0a-bd5b-457f-ac8e-86554616c87b.jpg'+avatarPrefix">
        <span class="username text-muted">九九一十八</span>
        <span class="description">Sent you a message - yesterday</span>
      </div>
      <p>
        Cli模式下如何使用Xdebug调试? <br />
        <a style="color:blue;" href="https://www.jetbrains.com/help/phpstorm/2019.2/debugging-a-php-cli-script.html?utm_campaign=PS&utm_content=2019.2&utm_medium=link&utm_source=product">Jetbrain Xdebug参考</a>
      </p>
      <ul class="list-inline">
        <li>
          <span class="link-black text-sm">
            <i class="el-icon-share" />
            Share
          </span>
        </li>
        <li>
          <span class="link-black text-sm">
            <svg-icon icon-class="like" />
            Like
          </span>
        </li>
      </ul>
    </div>
    <div class="post">
      <div class="user-block">
        <img class="img-circle" :src="'https://wpimg.wallstcn.com/fb57f689-e1ab-443c-af12-8d4066e202e2.jpg'+avatarPrefix">
        <span class="username">九九一十八</span>
        <span class="description">Posted 4 photos - 2 days ago</span>
      </div>
      <div class="user-images">
        <el-carousel :interval="6000" type="card">
          <el-carousel-item v-for="item in carouselImages" :key="item">
            <img :src="item+carouselPrefix" class="image">
          </el-carousel-item>
        </el-carousel>
      </div>
      <ul class="list-inline">
        <li><span class="link-black text-sm"><i class="el-icon-share" /> Share</span></li>
        <li>
          <span class="link-black text-sm">
            <svg-icon icon-class="like" /> Like</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
const avatarPrefix = '?imageView2/1/w/80/h/80'
const carouselPrefix = '?imageView2/2/h/440'
const webUrl = 'http://www.eznat.com:8000'
export default {
  data() {
    return {
      carouselImages: [
        webUrl + '/uploads/jy1.jpg',
        webUrl + '/uploads/jy2.jpg',
        webUrl + '/uploads/jy3.jpg',
        webUrl + '/uploads/jy4.jpg',
        webUrl + '/uploads/jy5.jpg',
        webUrl + '/uploads/jy6.jpg'
      ],
      avatarPrefix,
      carouselPrefix
    }
  }
}
</script>

<style lang="scss" scoped>
.user-activity {
  .user-block {

    .username,
    .description {
      display: block;
      margin-left: 50px;
      padding: 2px 0;
    }

    .username{
      font-size: 16px;
      color: #000;
    }

    :after {
      clear: both;
    }

    .img-circle {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      float: left;
    }

    span {
      font-weight: 500;
      font-size: 12px;
    }
  }

  .post {
    font-size: 14px;
    border-bottom: 1px solid #d2d6de;
    margin-bottom: 15px;
    padding-bottom: 15px;
    color: #666;

    .image {
      width: 300px;
    }

    .user-images {
      padding-top: 20px;
    }
  }

  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;

    li {
      display: inline-block;
      padding-right: 5px;
      padding-left: 5px;
      font-size: 13px;
    }

    .link-black {

      &:hover,
      &:focus {
        color: #999;
      }
    }
  }

}

.box-center {
  margin: 0 auto;
  display: table;
}

.text-muted {
  color: #777;
}
</style>
