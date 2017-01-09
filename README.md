﻿# Dependency Injection Component

[![Build Status](https://travis-ci.org/slince/di.svg?branch=master)](https://travis-ci.org/slince/di)
[![Latest Stable Version](https://poser.pugx.org/slince/di/v/stable)](https://packagist.org/packages/slince/di)
[![Total Downloads](https://poser.pugx.org/slince/di/downloads)](https://packagist.org/packages/slince/di)
[![Latest Unstable Version](https://poser.pugx.org/slince/di/v/unstable)](https://packagist.org/packages/slince/di)
[![License](https://poser.pugx.org/slince/di/license)](https://packagist.org/packages/slince/di)

这是一个依赖注入组件，通过简单的实现即可让类的实例化过程变得简单；

## 安装

在composer.json中添加
```
{
    "require": {
        "slince/di": "*"
    }
}
```
## 用法

为了适应不同的场景，组件采用多种方法帮助获取实例，以下是三种方式案例

### 1、通过实例绑定
```
class Reader
{
    public function read()
    {}
}

$di = new Slince\Di\Container();
$di->set('Reader', function () {
    return new Reader(); 
});
$instance = $di->get('Reader');
//结果 bool(true)
var_dump($instance instanceof Reader);
```
如果需要定义一个共享的实例,则使用share取代set方法

### 2、通过自动获取
```
class Reader 
{
    private $reader;

    public function __construct(PdfReader $reader)
    {
        $this->reader = $reader;
    } 
}
class PdfReader
{
}

$di = new Slince\Di\Container();
$instance = $di->get('Reader');
//结果 bool(true)
var_dump($instance instanceof Reader);
```
自动获取可以解决简单的实例依赖关系，但如果依赖是一个标量并且不是可选的，则无法完成自动注入。


### 3、描述类
```
class Reader 
{
    private $reader;
    private $file;
    private $mode;
    public function __construct(PdfReader $reader, $file)
    {
        $this->reader = $reader;
        $this->file = $file;
    }
    public function setMode($mode)
    {
        $this->mode = $mode;
    }
}
class PdfReader
{}

$di = new Slince\Di\Container();
//描述
$di->setDefinition('Reader', new Definition('Reader')->setArgument(1, 'C:/a.pdf')->setMethodCall('setMod', [1]);
//获取实例
$instance = $di->get('Reader');
//结果 bool(true)
var_dump($instance instanceof Reader);

```
对于一个依赖较多的类，只需要指出它的标量依赖即可，当然指出全部依赖亦可；如果需要共享则提供第二个参数true；以下是常用的指出依赖的方法:
```
1. setArgument($index, $value) //指出构造依赖. 
2. setArguments(array $arguments) //批量指出所有依赖，会覆盖已有的定义
3. setMethodCall($method, array $arguments) //指出setter依赖
4. setMethodCalls(array $methodCalls) //批量指出所有setter依赖，同样会覆盖已有的定义
```
推荐使用类名做绑定标记，但如果类名过长，则可以给该类名设置别名，这样通过别名和类名都可以获取该类的实例
```
//Reader.php
namespace I\Like\Read\Book;

class Reader 
{
    public function read()
    {
        echo 'reading';
    }
}

//client.php
include 'Reader.php';
$di = new Slince\Di\Container();
$di->alias('reader', 'I\\Like\\Read\\Book\\Reader');
$instance = $di->get('reader');
//结果 bool(true)
var_dump($instance instanceof I\Like\Read\Book\Reader); 
```

### 新增api

#### 服务定义
- 定义一个详细实例化指令
```
Container::define 
```
- 定义实例化方法或者函数，例如绑定一个类的工厂方法或者自定义的闭包
```
Container::delegate
```
- 直接绑定接口
```
Container::instance
```

#### 绑定一个接口到它的实现，如果有类在实例化的时候依赖到接口将会使用实现类替换
```
Container::bind
```

#### 设置单例模式
```
Container:;share
```
