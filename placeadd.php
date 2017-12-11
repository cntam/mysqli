<html>
	<head>
		<title>地名增加</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<center>
			<?php include("menu.php"); //导入导航栏 ?>
			
			<h3>地名增加</h3>
			<form action="action.php?action=add" method="post">
				<table width="640" border="0">
					<!--<tr>
						<td align="right">*增加时间:</td>
						<td><input type="text" name="uptime" id="uptime"/></td>
					</tr>
					<tr>
						<td align="right">*到期时间:</td>
						<td><input type="text" name="duetime" id="duetime"/></td>
					</tr>-->
					<tr>
						<td align="right">*PID:</td>
						<td><input type="text" name="pid"/></td>
					</tr>
					<tr>
						<td align="right">*地名:</td>
						<td><input style="width:500px" type="text" name="place"/></td>
					</tr>
					<tr>
						<td align="right">GPS:</td>
						<td><input  type="text" name="gps"/></td>
					</tr>
					
					<!--<tr>
						<td align="right">状态:</td>
						<td><input type="text" name="status" value="已付款"/></td>
					</tr>
					<tr>
						<td align="right" valign="top" value="正常">位置:</td>
						<td><input type="text" name="linkout"/></td>
					</tr>-->
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="添加"/>&nbsp;&nbsp;
							<input type="reset" value="重置"/>
						</td>
					</tr>
				</table>
			</form>
			<br/><hr/>
		

		</center>
	</body>
</html>