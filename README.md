# Redisuper

Redis is super 基于PHP的redis web 管理工具

demo: [http://chenshuo.net/redisuper](http://chenshuo.net/redisuper)

![ui](http://chenshuo.net/other/ui.png)

### 理念
最小的依赖，简单直接的实现，人人可修改

### 建议
目前该项目处于开发状态，建议只用于开发环境

### 安装

```
git clone https://github.com/supkit/redisuper.git
```

### Nginx

如果您使用的是Nginx作为web服务器，需要配置Nginx支持PAHT_INFO

### 依赖
- php > 5.6 建议php7
- php redis 扩展
- composer [主要用于自动加载]

### 已完成

- 支持 string set list hash 查询、删除
- 支持PHP字符串反序列化显示
- 登陆 [可配置]

### 规划中
- 进一步规范代码，补充注释
- key 增加、编辑
- 多服务器支持
- 更好的页面兼容

### 更远的
- 完整的二次开发文档