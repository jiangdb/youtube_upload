<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0027)http://192.168.1.10/genxml/ -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>XML Generator v1.5</title>

<style type="text/css">
.zhushi {
	color: #F00;
	font-size: 36px;
}
.zhushi2 {
	color: #F00;
	font-size: 3px;
}
.neirong {
	background-color: #CBE7EB;
	font-weight: bold;
}
.neirong2 {
    background-color: #CBE7EB;
	width:1020px; height:100px;
	font-size: 50px;
	margin: 0 auto;
	margin-bottom:2px;
}

.biaoti1 {
	font-size: 100px;
	font-weight: bold;
	font-family: "Comic Sans MS", cursive;
	width:1000px; height:125px;
	text-align:center;
	margin: 0 auto;

}

.tianlan {
	background-color: #CBE7EB;
	width:1000px; height:50px;
	font-size: 20px;
	margin: 0 auto;
	margin-bottom:2px;
	font-weight: bold;
	padding-left:20px;
	}
.submit {
    background-color: #F33;
    color: #FFF;
    font-size: 25px;
    margin-top: 10px;
}
</style>
<script language="javascript">

</script>
</head>

<body>
<div class="biaoti1">
<p>XML-->CSV</p>
</div>

<form method="post" action="{{ route('genfile.translates.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	 <div class="tianlan">
	    <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF; " ><span class="zhushi">*</span> XML File: </div>
	    <div style="float:left; width:580px; margin-top:15px;padding-left:10px; "><input name="xmlFile" type="file" id="xmlFile" accept=".xml" style=" font-size: 15px" required >
	    </div >
	</div>

	<div class="neirong2 ">
      	<div style="float:left; height:100px; line-height:100px; width:1000px; text-align: center; margin: 0 auto;" >
	   		<input class="submit" type="submit" name="submit" value="生成CSV" style=" width:200px; height:80px;">
		</div>
	</div>
</form>
</body>
</html>