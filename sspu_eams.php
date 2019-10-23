<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/normalize.css">
<style>
table{
	text-align: left;
	width:100%;
}
</style>

</head>
<body>
<?php
error_reporting(E_ALL);
require 'simple_html_dom.php';
error_reporting(E_ALL);
function mkuserdir($path)  
{  
	if(!is_readable($path))  
	{  
		is_file($path) or mkdir($path,0777);  
	}  
}  
function login_post($url, $cookie, $post) { 
	$curl = curl_init();//初始化curl模块 
	curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址 
	curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//是否自动显示返回的信息 
	curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中 
	curl_setopt($curl, CURLOPT_POST, 1);//post方式提交 
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息 
	$result = curl_exec($curl);//执行cURL 
	curl_close($curl);//关闭cURL资源，并且释放系统资源 
	return ($result === '');
} 
function post_content($url, $cookie, $post) { 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie 
	curl_setopt($ch, CURLOPT_POST, 1);//post方式提交 
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息 
	$rs = curl_exec($ch); //执行cURL抓取页面内容 
	curl_close($ch); 
	return $rs; 
} 
function get_content($url, $cookie) { 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie 
	$rs = curl_exec($ch); //执行cURL抓取页面内容 
	curl_close($ch); 
	return $rs; 
}
if (isset($_GET['opac'])) {
	$opac_url = "http://opac.sspu.edu.cn/opac/";
				
	$content = get_content($opac_url, null); 
	
	$opac_html = new simple_html_dom();
	$opac_html = str_get_html($content);
	
	$opac_html->find('div.hot', 0)->outertext = "";
	$opac_html->find('div.search_more', 0)->outertext = "";
	$opac_html->find('div.booktypes', 0)->outertext = "";
	$opac_html->find('div#searchDivHidden', 0)->outertext = "";
	$opac_html->find('div#libraries', 0)->outertext = "";
	$opac_html->find('div#searchTips', 0)->outertext = "";
	
	$object = $opac_html->find('*[src]');
	
	foreach ($object as $item) {
		$item->src = "http://opac.sspu.edu.cn" . $item->src;
		//echo $item->src;
	}
	
	$opac_html->find('form#searchForm', 0)->action = "http://opac.sspu.edu.cn/opac/search";
	
	$opac_content = $opac_html->find('div#searchDiv', 0);
	echo '<link href="http://cdn.bootcss.com/bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet">';
	echo '<style>
	body{
		background-color: #faf7f7
	}
	#searchForm>div {
		width:100%;
		text-align:left;
	}
	select[name=searchWay]{
		margin-top:25px;
		width:50%;
		height:30px;
		margin-left:3%
	}
	option{
		width:100%
	}
	.search_input{
		margin-top:25px;
		width:100%;
		height:35px;
		text-align:center
	}
	.search_input input{
		width:95%;
		height:35px;
	}
	input[type="button"]{
		width:45%;
		height:40px;
		margin-left:3%;
		margin-top:20px;
		background-color: #4a77d4;
		color:#ddd;
		background-image: -moz-linear-gradient(top, #6eb6de, #4a77d4);
		background-image: -ms-linear-gradient(top, #6eb6de, #4a77d4);
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#6eb6de), to(#4a77d4));
		background-image: -webkit-linear-gradient(top, #6eb6de, #4a77d4);
		background-image: -o-linear-gradient(top, #6eb6de, #4a77d4);
		background-image: linear-gradient(top, #6eb6de, #4a77d4);
		background-repeat: repeat-x;
		filter: progid:dximagetransform.microsoft.gradient(startColorstr=#6eb6de, endColorstr=#4a77d4, GradientType=0);
		border: 1px solid #3762bc;
		box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5);
		font-size: 15px;
		line-height: normal;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}
	</style>';
	echo $opac_content;
	
	echo '<script src="http://opac.sspu.edu.cn/opac/media/js/jquery/jquery-1.6.2.min.js"></script>';
	
	$opac_script = $opac_html->find('script', 11);
	echo $opac_script;
	echo '<script>function gotoAdvancedSearch() {
				document.location.href = "http://opac.sspu.edu.cn/opac/index/advance";
			}</script>';
	exit(0);
}
if (isset($_POST['username'])) {
		
	if (!isset($_POST["action"])) {
		$action = "course";
	}else {
		$action = $_POST["action"];
	}
			
    $userid = $_POST['username'];
    $password = $_POST['password'];
    $post = array(
        'username'=>$userid,
        'password'=>$password,
    );
    $url = "http://jx.sspu.edu.cn/eams/login.action";  
    if (!is_dir( __DIR__ .'/session/'.$userid))
        mkuserdir( __DIR__ .'/session/'.$userid);
    $cookie = dirname(__FILE__) . '/session/'.$userid.'/cookie.txt'; 
  
    if (@login_post($url, $cookie, $post)) {
        $url2 = "http://jx.sspu.edu.cn/eams/home!index.action";
        $content = get_content($url2, $cookie);
        $rule  = '%查看登录记录">(.*?)\((.*?)\)</a>%';
        preg_match_all($rule,$content,$result);
        $username = $result[1][0];
		
		echo '欢迎你，' . $username . '！';
		
		switch ($action) {
			case "grade":
				$grade_url ='http://jx.sspu.edu.cn/eams/teach/grade/course/person!historyCourseGrade.action?projectType=MAJOR';
		// 'http://jx.sspu.edu.cn/eams/teach/grade/course/person!search.action?semesterId=381&projectType=';
				$content = get_content($grade_url, $cookie); 
				//echo $content;
				$grade_html = new simple_html_dom();
				$grade_html = str_get_html($content);
				
				$jidians 	= $grade_html->find('table.gridtable', 0)->children(1);
				
				$grade_index = count($jidians->find('tr')) - 2;
				
				$container = $grade_html->find('div.grid tr');
				
				$jidian_score = $jidians->find('tr', $grade_index)->find('th', 3)->plaintext;
				echo '<br>';
				echo "你的平均绩点是：";
				echo($jidian_score);
				echo '<br>';
				if ($jidian_score<2.0)
					echo "请努力学习，将绩点提升到2.0以上";
				elseif ($jidian_score>3.0) {
					echo "加油！继续努力！";
				}

				echo '<br><br>
							<table>
								<thead>
									<tr>
										<th width="40%" >课程名称</th>
										<th width="20%" >学分</th>
										<th width="20%" >最终</th>
										<th width="20%" >绩点</th>
									</tr>
								</thead>
								<tbody>';
				
				//grid
				//echo count($container);
				//echo count($jidian);
				
				foreach($container as $item) {
					echo '<tr>';
						echo $item->find('td', 3);
						echo $item->find('td', 5);
						echo $item->find('td', 9);
						echo $item->find('td', 10);
					echo '</tr>';
				}
				echo '</tbody></table>';
				break;
				//end grade case---------------------------
			case "course":
				$course_url = "http://jx.sspu.edu.cn/eams/studentDetail.action"; 
		
				$content = get_content($course_url, $cookie); 
				
				$detail_html = new simple_html_dom();
				$detail_html = str_get_html($content);
				
				$studentId = $detail_html->find('input[name=studentId]', 0)->value;
				
				//echo $studentId;
		
				$grade_url = 'http://jx.sspu.edu.cn/eams/courseTableForStd!courseTable.action';
				
				$post_data = array(
					'ignoreHead'=>1,
					'setting.kind'=>'std',
					'startWeek'=>1,
					'semester.id'=>501,
					'ids'=>$studentId,
				);
				$content = post_content($grade_url, $cookie, $post_data); 

				//echo $content;
				
				$course_html = new simple_html_dom();
				$course_html = str_get_html($content);
				
				$course_table = $course_html->find('table', 0);
				
				$course_table->find('th[width=80px]', 0)->width = null;//remove not important style
				
				$course_js_action = $course_html->find('script', 14);//don't ask me how to get this number.
				$course_js_data = $course_html->find('script', 15);
				
				echo $course_table;
				echo '<script>setTimeout(function(){console.log("0")},1000);</script>';

				echo '<script src="http://jx.sspu.edu.cn/eams/struts/js/base/jquery-1.5.2.js"></script>'; 
				echo '<script src="http://jx.sspu.edu.cn/eams/static/scripts/course/TaskActivity.js"></script>'; 
				echo $course_js_action;
				echo $course_js_data;

				break;
				//end course case---------------------------
				
			
		}
    }else {
        echo '用户名或密码错，请重试';
  }
}
else {
	echo '<style type="text/css">
.btn { display: inline-block; *display: inline; *zoom: 1; padding: 4px 10px 4px; margin-bottom: 0; font-size: 13px; line-height: 18px; color: #333333; text-align: center;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75); vertical-align: middle; background-color: #f5f5f5; background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6); background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6)); background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6); background-image: -o-linear-gradient(top, #ffffff, #e6e6e6); background-image: linear-gradient(top, #ffffff, #e6e6e6); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#ffffff, endColorstr=#e6e6e6, GradientType=0); border-color: #e6e6e6 #e6e6e6 #e6e6e6; border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25); border: 1px solid #e6e6e6; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05); cursor: pointer; *margin-left: .3em; }
.btn:hover, .btn:active, .btn.active, .btn.disabled, .btn[disabled] { background-color: #e6e6e6; }
.btn-large { padding: 9px 14px; font-size: 15px; line-height: normal; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
.btn:hover { color: #333333; text-decoration: none; background-color: #e6e6e6; background-position: 0 -15px; -webkit-transition: background-position 0.1s linear; -moz-transition: background-position 0.1s linear; -ms-transition: background-position 0.1s linear; -o-transition: background-position 0.1s linear; transition: background-position 0.1s linear; }
.btn-primary, .btn-primary:hover { text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); color: #ffffff; }
.btn-primary.active { color: rgba(255, 255, 255, 0.75); }
.btn-primary { background-color: #4a77d4; background-image: -moz-linear-gradient(top, #6eb6de, #4a77d4); background-image: -ms-linear-gradient(top, #6eb6de, #4a77d4); background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#6eb6de), to(#4a77d4)); background-image: -webkit-linear-gradient(top, #6eb6de, #4a77d4); background-image: -o-linear-gradient(top, #6eb6de, #4a77d4); background-image: linear-gradient(top, #6eb6de, #4a77d4); background-repeat: repeat-x; filter: progid:dximagetransform.microsoft.gradient(startColorstr=#6eb6de, endColorstr=#4a77d4, GradientType=0);  border: 1px solid #3762bc; text-shadow: 1px 1px 1px rgba(0,0,0,0.4); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.5); }
.btn-primary:hover, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] { filter: none; background-color: #4a77d4; }
.btn-block { width: 100%; display:block; }

* { -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; -o-box-sizing:border-box; box-sizing:border-box; }

html { width: 100%; height:100%; overflow:hidden; }

body { 
	width: 100%;
	height:100%;
	font-family: "Open Sans", sans-serif;
	background: #092756;
	background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%),-moz-linear-gradient(top,  rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg,  #670d10 0%, #092756 100%);
	background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -o-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -ms-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
	background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), linear-gradient(to bottom,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), linear-gradient(135deg,  #670d10 0%,#092756 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#3E1D6D", endColorstr="#092756",GradientType=1 );
}
.login { 
	position: absolute;
	top: 50%;
	left: 50%;
	margin: -150px 0 0 -150px;
	width:300px;
	height:300px;
}
.login h3 { color: #fff; text-shadow: 0 0 10px rgba(0,0,0,0.3); letter-spacing:1px; text-align:center; }
label {
	color:#fff;
}
input { 
	width: 100%; 
	margin-bottom: 10px; 
	background: rgba(0,0,0,0.3);
	border: none;
	outline: none;
	padding: 10px;
	font-size: 13px;
	color: #fff;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.3);
	border: 1px solid rgba(0,0,0,0.3);
	border-radius: 4px;
	box-shadow: inset 0 -5px 45px rgba(100,100,100,0.2), 0 1px 1px rgba(255,255,255,0.2);
	-webkit-transition: box-shadow .5s ease;
	-moz-transition: box-shadow .5s ease;
	-o-transition: box-shadow .5s ease;
	-ms-transition: box-shadow .5s ease;
	transition: box-shadow .5s ease;
}
input:focus { box-shadow: inset 0 -5px 45px rgba(100,100,100,0.4), 0 1px 1px rgba(255,255,255,0.2); }

</style>';
	echo '
	<div class="login">
	<h3>使用学校学号/工号登录</h3>
    <form class="form-horizontal" action="./webservice.php" method="POST" enctype="multipart/form-data">
      <br>
      <div class="form-group">
        <label for="username" class="col-sm-3 control-label">用户名：</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="username" id="username" placeholder="学号/工号">
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-3 control-label">密　码：</label>
        <div class="col-sm-9">
          <input type="password" class="form-control" name="password" id="password" placeholder="密码">
        </div>
      </div>';
	  if (isset($_GET['admin']))
		  echo '
	  <div class="form-group">
        <label for="action" class="col-sm-3 control-label">功能：</label>
        <div class="col-sm-9">
          <input type="action" class="form-control" name="action" id="action" placeholder="功能">
        </div>
      </div>';
	  echo '
      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
          <button class="btn btn-primary btn-block btn-large" type="submit" class="btn btn-success">登录</button>
        </div>
      </div>
    </form>	
	</div>
	';
}
    ?>
</body>
</html>





