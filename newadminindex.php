<html>
<head>
    <title>友情链接管理系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript">
			function dodel(id){
				if(confirm("确定要删除吗？")){
					window.location="action2.php?action=del&id="+id;
				}
			}
		
		</script>
	</head>
	<body>
		<center>
			<?php include("menu.php"); //导入导航栏 ?>
			<!-----搜索表单--------->
				<form action="newadminindex.php" method="get">
					标题：<input type="text" name="keyword" size="20" value="<?php echo !empty($_GET['keyword'])?$_GET['keyword']:""; ?>" />&nbsp;
					分类：<input type="text" name="keywords" size="8" value="<?php echo !empty($_GET['keywords'])?$_GET['keywords']:""; ?>" />&nbsp;
					商户：<input type="text" name="bus"  size="8"  value="<?php echo !empty($_GET['bus'])?$_GET['bus']:""; ?>"/>&nbsp;
					<input type="submit" value="搜索"/>
					<input type="button" value="全部信息" onclick="window.location='newadminindex.php'"/>
				</form>
			<!-------------->

		<table style="width:100%;" border="1">
				<tr>
					<th>编号id</th><th>增加时间</th><th>到期时间</th><th>关键词</th><th>链接</th><th>商户</th>
					<th>对话</th><th>客服</th><th>交易状态</th><th>查看</th><th>位置</th>
					<th>操作</th>
				</tr>
				<?php
					//=============================
					//封装搜索信息
					$wherelist = array();//定义一个封装搜索条件的数组变量
					$urllist = array(); //定义了一个封装搜索条件的url数组，语句放置到url后面做url参数使用
					//判断新闻标题是否有值，若有则封装此搜索条件
					if(!empty($_GET["keyword"])){
						$wherelist[]="keyword like '%{$_GET['keyword']}%'";
						$urllist[]="keyword={$_GET['keyword']}";
					}
					//判断关键字是否有值，若有则封装此搜索条件
					if(!empty($_GET["keywords"])){
						$wherelist[]="keywords like '%{$_GET['keywords']}%'";
						$urllist[]="keywords={$_GET['keywords']}";
					}
					//判断作者是否有值，若有则封装此搜索条件
					if(!empty($_GET["bus"])){
						$wherelist[]="bus like '%{$_GET['bus']}%'";
						$urllist[]="bus={$_GET['bus']}";
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
						$link = @mysql_connect(HOST,USER,PASS) or die("数据库连接失败！");
						mysql_select_db(DBNAME,$link);
						
					//2.1 插入分页处理代码
					//======================================
						//1. 定义一些分页变量
						$page=isset($_GET["page"])?$_GET['page']:1;		//当前页号
						$pageSize=15;	//页大小
						$maxRows;		//最大数据条
						$maxPages;		//最大页数
						
						//2. 获取最大数据条数
						@$sql = "select count(*) from linkdb {$where}";
						//$sql = "select * from jmfulian order by addtime desc";; 
						$res = mysql_query($sql,$link);
						$maxRows = mysql_result($res,0,0); //定位从结果集中获取总数据条数这个值。
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
						@$sql = "select * from linkdb {$where} order by duetime2 asc {$limit}";
						@$result = mysql_query($sql,$link);
						
					//4. 解析结果集,并遍历输出
					while($row = mysql_fetch_assoc($result)){  
							echo "<tr>";
							echo "<td style='word-break:break-all' width='3%'>{$row['id']}</td>";
							echo "<td style='word-break:break-all' width='6%'>".date("Y-m-d",$row['uptime2'])."</td>";
							echo "<td style='word-break:break-all' width='6%'>".date("Y-m-d",$row['duetime2'])."</td>";
							echo "<td style='word-break:break-all' width='10%'>{$row['keyword']}</td>";
							echo "<td style='word-break:break-all' width='20%'>{$row['link']}</td>";
							echo "<td style='word-break:break-all' width='4%'>{$row['bus']}</td>";
							echo "<td style='word-break:break-all' width='5%'>{$row['dialog']}</td>";
							echo "<td style='word-break:break-all' width='5%'>{$row['serivce']}</td>";
							echo "<td style='word-break:break-all' width='10%'>{$row['status']}</td>";
							echo "<td style='word-break:break-all' width='5%'><a href='http://web.jmmama.com.cn/jmwebpage/link/out/answerpage6.php?swid=".$row['id']." ' target='_blank'>查看</a></td>";
							echo "<td style='word-break:break-all' width='5%'>{$row['linkout']}</td>";
							echo "<td style='word-break:break-all' width='5%'>
								<a href='javascript:dodel({$row['id']})'>删除</a>
								<a href='edit2.php?id={$row['id']}'>修改</a></td>";
							echo "</tr>";
						}
					//5. 释放结果集
						mysql_free_result($result);
						mysql_close($link);
				?>
			</table>
				<?php
					$url = !empty($url)?$url:"";
					//输出分页信息，显示上一页和下一页的连接
					echo "<br/><br/>";
					echo "当前{$page}/{$maxPages}页 共计{$maxRows}条";
					echo " <a href='newadminindex.php?page=1{$url}'>首页</a> ";
					echo " <a href='newadminindex.php?page=".($page-1)."{$url}'>上一页</a> ";
					echo " <a href='newadminindex.php?page=".($page+1)."{$url}'>下一页</a> ";
					echo " <a href='newadminindex.php?page={$maxPages}{$url}'>末页</a> ";
				?>

		</center>
	</body>
</html>