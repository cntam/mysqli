<html>
	<head>
		<title>地名管理系统</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<center>
			<?php 
				include("menu.php"); //导入导航栏 
				
				//1. 导入配置文件
					require("dbconfig.php");
					
				//2. 连接MySQL、选择数据库
					$alink = @mysql_connect(HOST,USER,PASS) or die("数据库连接失败！");
					mysql_select_db(DBNAME,$alink);
					
				//3. 获取要修改信息的id号，并拼装查看sql语句，执行查询，获取要修改的信息
					$sql = "select * from placedb where id={$_GET['id']}";
					$result = mysql_query($sql,$alink);
					
				//4. 判断是否获取到了要修改的信息
					if($result && mysql_num_rows($result)>0){
						$news = mysql_fetch_assoc($result);
					}else{
						die("没有找到要修改的信息！");
					}
			?>
			
			<h3>编辑</h3>
			<?php $page=isset($_GET['page'])? intval($_GET['page']) : 1; ?>
			
			<form action="action.php?action=update&page=<?php echo $page;  ?>" method="post">
			
				<input type="hidden" name="id" value="<?php echo $news['id']; ?>"/>
				<table width="640" border="0">
					<tr>
						<td align="right">*PID:</td>
						<td><input type="text" name="pid" value="<?php echo $news['pid']; ?>"/></td>
					</tr>
					<tr>
						<td align="right">*地名:</td>
						<td><input type="text" name="place" value="<?php echo $news['place']; ?>"/></td>
					</tr>
					<tr>
						<td align="right">*GPS:</td>
						<td><input type="text" name="gps" value="<?php echo $news['gps']; ?>"/></td>
					</tr>
					<tr>
						<td align="right">社区电话：</td>
						<td><input type="text" name="phone" value="<?php echo $news['phone']; ?>"/></td>
					</tr>
					
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="提交"/>&nbsp;&nbsp;
							<input type="reset" value="重置"/>
						</td>
					</tr>
				</table>
			</form>
		</center>
	</body>
</html>