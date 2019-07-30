### 1 安装docker
#### 1、1 卸载旧版本
```apt-get remove docker docker-engine docker.io containerd runc```
#### 1、2 更新系统源
```apt-get update```
#### 1、3 安装允许apt使用基于https的仓库安装软件
```
sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg-agent \
    software-properties-common
```
#### 1、4 添加GPG密钥
```curl -fsSL http://mirrors.aliyun.com/docker-ce/linux/ubuntu/gpg | sudo apt-key add -```
#### 1、5 验证密钥是否添加成功
```apt-key fingerprint 0EBFCD88```
#### 1、6 写入docker stable版本的阿里云镜像软件源
```
sudo add-apt-repository "deb [arch=amd64] http://mirrors.aliyun.com/docker-ce/linux/ubuntu $(lsb_release -cs) stable"
```
#### 1、7 更新软件源
```apt-get update```
#### 1、8 安装最新版的docker ce
```sudo apt-get install docker-ce docker-ce-cli containerd.io```
#### 1、9 通过运行hello-world验证docker ce安装成功
```sudo docker run hello-world```
#### 1、10 使用阿里云docker镜像加速器
登陆阿里云管理后台
然后访问网址`https://cr.console.aliyun.com/cn-hangzhou/instances/mirrors`
```
sudo mkdir -p /etc/docker
sudo vim /etc/docker/daemon.json 
    {
        "registry-mirrors": ["你的阿里云镜像加速器地址"]
    }
sudo systemctl daemon-reload
sudo systemctl restart docker
```
#### 1、11 启动docker
```systemctl start docker```
#### 2 安装docker compose
#### 2.1 使用DaoCloud安装
```curl -L https://get.daocloud.io/docker/compose/releases/download/1.24.1/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose`
`chmod +x /usr/local/bin/docker-compose```