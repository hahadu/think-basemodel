# think-basemodel
thinkphp/thinkORM的数据库基础模型扩展，实现增删改查，无限级分类


安装 composer require hahadu/think-basemodel

无限级分类依赖：

<a href='https://github.com/hahadu/data-handle'> hahadu/data-handle</a>


使用： think-orm

在需要创建的model文件中直接继承basemodel文件
示例：

<code>

use Hahadu\ThinkBaseModel\BaseModel;

class User model extends BaseModel{



}

</code>