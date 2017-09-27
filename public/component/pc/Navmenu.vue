<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  padding-top: 100px;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
.breadcrumbNotActvie {
  color: #3097D1;
  cursor: pointer;
}
.navTab{
  margin-bottom: 10px;
}
</style>

<template>

  <div class="container-fluid" :class="$style.container">
  <ul class="nav nav-tabs" :class="$style.navTab">
    <router-link to="/navmenu" tag="li" active-class="active">
      <a href="#">头部导航</a>
    </router-link>
    <router-link to="/footnav" tag="li" active-class="active">
      <a href="#">底部导航</a>
    </router-link>
    <router-link to="/navmanage" tag="li" active-class="active">
      <a href="#">增加导航</a>
    </router-link>
  </ul>

    <!-- 加载动画 -->
    <div v-show="loadding" :class="$style.loadding">
      <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </div>
    <!-- 整体盒子 -->
    <div v-show="!loadding" class="">

      <!-- 列表表格 -->
      <table class="table table-striped">
        <thead>
          <tr>
            <th>导航名称</th>
            <th>英文名称</th>
            <th>链接地址</th>
            <th>打开方式</th>
            <th>状态</th>
            <th>导航位置</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="nav in list">
              <td align="left">
                <div v-if="nav.parent_id" class="input-group" style="margin-left: 10px;"> ┗  {{ nav.name }}</div>
                <div v-else class="input-group">{{ nav.name }}</div>
              </td>
              <td align="center"><div class="input-group">{{ nav.app_name }}</div></td>
              <td align="left"><div class="input-group">{{ nav.url }}</div></td>
              <td align="center"><div class="input-group">{{ targets[nav.target] }}</div></td>
              <td align="center"><div class="input-group">{{ state[nav.status] }}</div></td>
              <td align="center"><div class="input-group">{{ types[nav.position] }}</div></td>
              <td align="left">
                <router-link type="button" class="btn btn-primary btn-sm" :to="'/navmanage/0/'+nav.id" v-if="!nav.parent_id">添加子导航</router-link>
                <router-link type="button" class="btn btn-primary btn-sm" :to="'/navmanage/'+nav.id+'/0'">编辑</router-link>
                <button type="button" class="btn btn-danger btn-sm" @click.prevent="deletenav(nav.id)">删除</button>
              </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const navComponent = {
  data: () => ({
    current: 0,
    loadding: true,
    list: {},
    targets: {
      _blank: '新窗口',
      _self: '当前窗口',
      _parent: '父窗口'
    },
    types: {
      0: '头部',
      1: '底部'
    },
    state: {
      0: '关闭',
      1: '开启'
    }
  }),
  methods: {
    selectCurrent (id) {

    },
    deletenav (id) {
      if (window.confirm('确认删除?')) {
          request.delete(
            createRequestURI('nav/del/'+id),
            { validateStatus: status => status === 200 }
          ).then(({ message = '删除成功' }) => {
            const index = this.list.indexOf({id: id});
            this.loadding = false;
            this.list.splice(index, 1);
          }).catch(({ response: { message = '删除失败' } = {} }) => {
            this.loadding = false;
            this.message = message;
          });
      }
    }
    },
    created () {
        this.loadding = true;
        request.get(
          createRequestURI('nav/list/0'),
          { validateStatus: status => status === 200 }
        ).then(({ data: { data = {} } }) => {
          this.loadding = false;
          this.list = data;
        }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
          this.loadding = false;
          this.message = message;
        });
    }
};

export default navComponent;
</script>
