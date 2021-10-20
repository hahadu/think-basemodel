# think-basemodel
thinkphp/thinkORM的数据库基础模型扩展，实现增删改查，无限级分类


安装 composer require hahadu/think-basemodel

无限级分类依赖：

[hahadu/data-handle](https://github.com/hahadu/data-handle)

使用： think-orm

在需要创建的model文件中直接继承basemodel文件
示例：

```

use Hahadu\ThinkBaseModel\BaseModel;

 //如果需要用到软删除功能需要引入SoftDeletemo模型

 //use think\model\concern\SoftDelete;

class User model extends BaseModel{

    //    use SoftDelete;
    
    //     protected $deleteTime = 'delete_time';

    repair you function
    
}

```

然后在控制器中直接调用方法
```

//查询user表

$User = new User();

//查询所有数据

$User -> select();

//无限级分类 $type  默认为tree获取树形结构 level获取层级结构
&User->getTreeData($type ); 


//查询
```