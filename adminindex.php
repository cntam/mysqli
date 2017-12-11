<html>
<head>
    <title>供應商列表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript">
			function dodel(id){
				if(confirm("确定要删除吗？")){
					window.location="action.php?action=del&id="+id;
				}
			}
		
		</script>
	</head>
	<body>
		<center>
			<?php include("menu.php"); //导入导航栏 ?>
			<!-----搜索表单--------->
				<form action="adminindex.php" method="get">
					PID：<input type="text" name="pid" size="20" value="<?php echo !empty($_GET['pid'])?$_GET['pid']:""; ?>" />&nbsp;
					地名：<input type="text" name="place" size="8" value="<?php echo !empty($_GET['place'])?$_GET['place']:""; ?>" />&nbsp;
					商户：<input type="text" name="gps"  size="8"  value="<?php echo !empty($_GET['gps'])?$_GET['gps']:""; ?>"/>&nbsp;
					<input type="submit" value="搜索"/>
					<input type="button" value="全部信息" onclick="window.location='adminindex.php'"/>
				</form>
			<!-------------->

		<table style="width:100%;" border="1">
				<tr>
					<th>编号id</th><th>PID</th><th>地名</th><th>GPS</th><th>社区电话</th>
					<th>操作</th>
				</tr>
				<?php
					//=============================
					//封装搜索信息
					$wherelist = array();//定义一个封装搜索条件的数组变量
					$urllist = array(); //定义了一个封装搜索条件的url数组，语句放置到url后面做url参数使用
					//判断新闻标题是否有值，若有则封装此搜索条件
					if(!empty($_GET["pid"])){
						$wherelist[]="pid like '%{$_GET['pid']}%'";
						$urllist[]="pid={$_GET['pid']}";
					}
					//判断关键字是否有值，若有则封装此搜索条件
					if(!empty($_GET["place"])){
						$wherelist[]="place like '%{$_GET['place']}%'";
						$urllist[]="place={$_GET['place']}";
					}
					//判断作者是否有值，若有则封装此搜索条件
					if(!empty($_GET["gps"])){
						$wherelist[]="gps like '%{$_GET['gps']}%'";
						$urllist[]="gps={$_GET['gps']}";
					}
					//组装搜索条件
					
					if(count($wherelist)>0){
						$where = " where ".implode(" and ",$wherelist);
						$url = "&".implode("&",$urllist);
					}
					//echo $where;
					//echo $url;
					//=============================
					
					//1.导入配置文件
						require("dbconfig.php");
						
					//2.连接MySQL，选择数据库
						$link = @mysqli_connect(HOST,USER,PASS) or die("数据库连接失败！");
						mysqli_select_db($link,DBNAME);
						
					//2.1 插入分页处理代码
					//======================================
						//1. 定义一些分页变量
						$page=isset($_GET["page"])?$_GET['page']:1;		//当前页号
						$pageSize=15;	//页大小
						$maxRows;		//最大数据条
						$maxPages;		//最大页数
						
						//2. 获取最大数据条数
						@$sql = "select count(*) from placedb {$where}";
						//$sql = "select * from jmfulian order by addtime desc";; 
						$res = mysqli_query($link,$sql);

                // 错略的使用mysqli替换
               /* if (!function_exists('mysql_result')) {
                function mysql_result($result, $number, $field=0) {
                    mysqli_data_seek($result, $number);
                    $row = mysqli_fetch_array($result);
                    return $row[$field];
                }*/
				
	function mysql_result($res, $number, $field=0) {
        mysqli_data_seek($res, $number);
        $row = mysqli_fetch_array($res);
        return $row[$field];
    }

                //$maxRows = mysql_result($res,0,0); //定位从结果集中获取总数据条数这个值。
				//$maxRows = mysqli_query($link, "SELECT COUNT(*) FROM placedb");
				$maxRows = mysql_result($res, 0);
				echo $maxRows;
				
						//3. 计算出共计最大页数
						$maxPages = ceil($maxRows/$pageSize); //采用进一求整法算出最大页数 
						
						//4. 效验当前页数
						if($page>$maxPages){
							$page=$maxPages;
						}
						if($page<1){
							$page=1;
						}
						
						//5. 拼装分页sql语句片段
						$limit = " limit ".(($page-1)*$pageSize).",{$pageSize}";   //起始位置是当前页减一乘以页大小
					//======================================
					
					//3. 执行查询，并返回结果集
						//@$sql = "select * from news {$where} order by addtime asc {$limit}";
						@$sql = "select * from placedb {$where} order by pid asc {$limit}";
						@$result = mysqli_query($link,$sql);
						
					//4. 解析结果集,并遍历输出
					while($row = mysqli_fetch_assoc($result)){
							echo "<tr>";
							echo "<td style='word-break:break-all' width='3%'>{$row['id']}</td>";
							echo "<td style='word-break:break-all' width='6%'>{$row['pid']}</td>";
							echo "<td style='word-break:break-all' width='6%'>{$row['place']}</td>";
							echo "<td style='word-break:break-all' width='10%'>{$row['gps']}</td>";
							echo "<td style='word-break:break-all' width='10%'>{$row['phone']}</td>";
							echo "<td style='word-break:break-all' width='5%'>
								<a href='javascript:dodel({$row['id']})'>删除</a>
								<a href='edit.php?id={$row['id']}&page={$page}'>修改</a></td>";
							echo "</tr>";
						}
					//5. 释放结果集
						mysqli_free_result($result);
						mysqli_close($link);
				?>
			</table>
				<?php
					$url = !empty($url)?$url:"";
					//输出分页信息，显示上一页和下一页的连接
					echo "<br/><br/>";
					echo "当前{$page}/{$maxPages}页 共计{$maxRows}条";
					echo " <a href='adminindex.php?page=1{$url}'>首页</a> ";
					echo " <a href='adminindex.php?page=".($page-1)."{$url}'>上一页</a> ";
					echo " <a href='adminindex.php?page=".($page+1)."{$url}'>下一页</a> ";
					echo " <a href='adminindex.php?page={$maxPages}{$url}'>末页</a> ";
				?>

		</center>
	</body>
</html>