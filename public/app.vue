<style lang="scss">
@import "./style/app";
// 旋转动画
@keyframes TurnAround {
  from {
    transform: rotate(1deg);
  }
  to {
    transform: rotate(360deg);
  }
}

// 设置滚动条样式
::-webkit-scrollbar {
  width: 2px;
  height: 2px;
}

::-webkit-scrollbar-thumb {
  background-color: #33BBBA !important;
}

::-webkit-scrollbar-track {
  background-color: transparent !important
}

#app {
  padding: 0;
  margin: 0;
}
</style>
<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/setting" tag="li" active-class="active">
        <a href="#">基础配置</a>
      </router-link>
      <router-link to="/loginmanage" tag="li" active-class="active">
        <a href="#">三方配置</a>
      </router-link>
      <router-link to="/navmenu" tag="li" active-class="active">
        <a href="#">导航配置</a>
      </router-link>
      <!--
      <router-link to="/authlist" tag="li" active-class="active">
        <a href="#">认证管理</a>
      </router-link>
      <router-link to="/report" tag="li" active-class="active">
        <a href="#">举报管理</a>
      </router-link>
      <router-link to="/news/add_news" tag="li" active-class="active">
        <a href="#">添加资讯</a>
      </router-link>
      <router-link to="/news/add_categories" tag="li" active-class="active">
        <a href="#">添加资讯分类</a>
      </router-link>
      <router-link to="/news/add_recommend" tag="li" active-class="active">
        <a href="#">添加推荐</a>
      </router-link>
      -->
    </ul>
    <router-view />
  </div>
</template>
<script>
export default {
    name: 'Pc'
}
</script>