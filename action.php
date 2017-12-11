<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
//这是一个信息增、删和改操作的处理页

//（1）、 导入配置文件
	require("dbconfig.php");
	
//（2）、连接MySQL、并选择数据库
	$alinksql = @mysql_connect(HOST,USER,PASS) or die("数据库连接失败！");
	mysql_select_db(DBNAME,$alinksql);
	
//（3）、根据需要action值，来判断所属操作，执行对应的代码
	switch($_GET["action"]){
		case "add": //执行添加操作
			//1. 获取要添加的信息，并补充其他信息
				$pid = $_POST["pid"];
				$place = $_POST["place"];
				$gps = $_POST["gps"];

				//2. 做信息过滤（省略）
			//3. 拼装添加SQL语句，并执行添加操作
				$sql = "insert into placedb values(null,'{$pid}','{$place}','{$gps}')";
				//echo $sql;
				mysql_query($sql,$alinksql);
				
			//4. 判断是否成功
				$id = mysql_insert_id($alinksql);//获取刚刚添加信息的自增id号值
				if($id>0){
					echo "<h3>信息添加成功！</h3>";
				}else{
					echo "<h3>信息添加失败！</h3>";
				}
				echo "<a href='javascript:window.history.back();'>返回</a>&nbsp;&nbsp;";
				echo "<a href='adminindex.php'>管理界面</a>";
				
				break;
		
		case "del": //执行删除操作
				//1. 获取要删除的id号
				$id=$_GET['id'];
				
				//2. 拼装删除sql语句，并执行删除操作
				$sql = "delete from placedb where id={$id}";
				mysql_query($sql,$alinksql);
				
				//3. 自动跳转到浏览新闻界面
				header("Location:adminindex.php");
			break;
			
		case "update": //执行修改操作
				//1. 获取要修改的信息
				$page=isset($_GET["page"])? intval($_GET['page']) : 1;
				$pid = $_POST["pid"];
				$place = $_POST["place"];
				$gps = $_POST["gps"];
				$phone = $_POST["phone"];
				$id = $_POST['id'];
				
				//2. 过滤要修改的信息（省略）
				//3. 拼装修改sql语句，并执行修改操作
				$sql = "update placedb set pid='{$pid}',place='{$place}',gps='{$gps}',phone='{$phone}' where id={$id}";
				//echo $sql;
				mysql_query($sql,$alinksql);
			
				//4. 跳转回浏览界面
				$headerurl = "Location:adminindex.php?page=".$page;
				
				header($headerurl);
				
			break;
	
	}
	
//（4）、关闭数据连接
	mysql_close($alinksql);
