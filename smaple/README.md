1. /smaple 目录下是一个简单的业务逻辑，独立于Tiri框架。
2. 可以将smaple目录整体移动到任意web目录下.
3. 只要在业务入口文件中(index.php)中定义两个常量，用相对路径包含Tiri框架的入口文件,
   Tiri框架即在__APP_LIB__ 下根据配置寻找指定的Controller，并运行其中的默认的方法
   
   
2013-2-20 17:58:45 @beijing