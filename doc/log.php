需求：
1.没有page类，
2.model 226行有一个bug，pager方法有问题
3.分页类有bug a全部页面都列出来， b 如果首页这样没有? 的，会直接显示这样的url  /taobao/&page=198
4.分页类需要重写

5.requert的 param 方法带一个默认值参数，很多从get里给出来的参数，都要有值的
6.model 233 行有一个bug，条件写反了，orderby参数给不进去。

7.提供cli的工具
        提供一个php的简单的 可简易扩展的php server,研究明白php的垃圾回收机制。
        方便的shell工具调用，目录操作封装，分割文件封装
        并发任务执行封装。
        

8.新增了Widget_Pager ，但和外部的per_page 沟通，如何让外部知道？cur_page,目前内部采用一个变量来表示，外部如何知道这个变量，需要在考虑一下。

实现：
1.2012年12月20日18:52:41
修改了pager，基本完美了
修改了request生成query的函数，基本比较好。

