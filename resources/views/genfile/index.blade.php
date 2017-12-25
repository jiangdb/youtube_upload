<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0027)http://192.168.1.10/genxml/ -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>XML Generator v1.5</title>

    <style type="text/css">
        .biaoti {
            background-color: #CBE7EB;
            width:1000px; height:200px;
            font-size: 30px;
            margin: 0 auto;
            margin-bottom:2px;
            font-weight: bold;
            padding-left:20px;
        }
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
            width:1000px; height:139px;
            text-align:center;
            margin: 0 auto;

        }
        .biaoti1 p {
            margin:0px;
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

        .neirong3 {
            background-color: #FC0;
            font-weight: bold;
        }
        .neirong4 {
            background-color: #368196;
            font-weight: bold;
        }
        .neirong4 td {
            font-weight: bold;
        }
        .body {
            margin-left: 0px;
            margin-top: 0px;
            margin-right: 0px;
            margin-bottom: 0px;
        }
        .beijin3 {
        }
        .title1 {
            width:640px;
        }
        .neirong01 {
            font-size: 25px;
            font-weight: bold;
        }
        .submit {
            background-color: #F33;
            color: #FFF;
            font-size: 25px;

        }
        .footer p{
            margin-left: 100px;
            font-size: 15px;
            color: gray;
        }
        .version p{
            margin-left: 100px;
            font-size: 20px;
        }
        .title11 {	font-size: 36px;
        }
        .biaoti2 {	font-size: 50px;
            font-weight: bold;
            background-image: none;
        }

        .divcss5{ border-style:solid; border-width:1px; border-left-color:#FFF}

    </style>
    <script language="javascript">


        function check(type)
        {
            document.getElementById("type").value = type;
            var temp = document.getElementById("Notification").value;
            var str="";
            if(temp!="" && temp.indexOf(",")>0)
            {
                var arremail=temp.split(",");
                for(var i=0;i <arremail.length;i++)
                {
                    if(arremail[i].replace(/\s+/g,"").search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)==-1)
                    {
                        str=str+"邮箱"+arremail[i]+"格式错误!\n";
                    }
                }
            }
            else
            {
                if(temp.replace(/\s+/g,"").search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)==-1)
                {
                    str="邮箱"+temp+"格式错误!\n";
                }
            }
            if(str!="")
            {
                alert(str);
            }
        }

        function csv2Submit()
        {
            var fields = [
                'VideoTitleCht',
                'textarea13',
                'textarea14',
                'textfield14',
                'textarea15',
                'textarea16',
            ];
            var name = [
                'title in traditional chinese',
                'description in traditional chinese',
                'keywords in traditional chinese',
                'title in english',
                'description in english',
                'keywords in english',
            ];
            for (var i=0; i<fields.length;i++) {
                var val = document.getElementById(fields[i]).value;
                if (val == '') {
                    alert(name[i] + ' is needed');
                    return;
                }
            }
            document.getElementById('titleEn').value = (document.getElementById('textfield14').value);
            document.getElementById('desEn').value = (document.getElementById('textarea15').value);
            document.getElementById('keywordsEn').value = (document.getElementById('textarea16').value);
            document.getElementById('titleCht').value = (document.getElementById('VideoTitleCht').value);
            document.getElementById('desCht').value = (document.getElementById('textarea13').value);
            document.getElementById('keywordsCht').value = (document.getElementById('textarea14').value);
            document.getElementById('csv2').submit();
        }
    </script>


</head>

<body >
<form method="post" action="{{ route('genfile.store') }}">
    {{ csrf_field() }}
    <div class="version">
        <p>v1.5</p>
    </div>
    <div class="biaoti1">
        <p>General</p>
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF; " ><span class="zhushi">*</span> Notification Email: </div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px; "><input name="Notification" type="text" id="Notification" style=" font-size: 20px" size="50" placeholder="[email address] seperated by &#39;,&#39;" >

        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF; " ><span class="zhushi">*</span> Channel:  </div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="Channel" type="text" id="Channel" style=" font-size: 20px" size="50" placeholder="channel id" required="" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px; border-right:5px solid #FFF;" ><span class="zhushi">*</span>  URL:  </div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="URL" type="text" id="URL" style=" font-size: 20px" size="50" placeholder="url" required="" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF;" ><span class="zhushi">*</span>  Video file:  </div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;"><input name="VideoFile" type="text" id="VideoFile" style=" font-size: 20px" size="50" placeholder="[filename]" required=""></div >
    </div>
    <div>
        <div class="biaoti">
            <div style="float:left; height:200px; line-height:200px; width:300px;border-right:5px solid #FFF; " ><span>Asset labels：</span></div>
            <div style="float:left; width:580px; margin-top:20px;padding-left:10px;">
                <textarea name="asset_labels" id="asset_labels" style=" font-size: 20px" cols="49" rows="7"></textarea>
            </div >
        </div>
        <div class="tianlan">
            <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF; " >Publish immediately: </div>
            <div style="float:left">
                <div style="float:left; width:300px;height:50px; line-height:50px;text-align: center;border-right:5px solid #FFF; ">
                    <td width="325" align="center" bgcolor="#CBE7EB"><span class="neirong01">YES</span></td>
                    <input type="radio" name="PublishImmediately" id="yes" value="yes" style="width:25px;height:25px"/></div >
                <div style="float:left; width:300px;height:50px; line-height:50px;text-align: center ;">
                    <td width="325" align="center" bgcolor="#CBE7EB"><span class="neirong01">NO</span></td>
                    <input  type="radio" name="PublishImmediately" id="no" value="no" checked="checked"style="width:25px;height:25px" />
                </div >
            </div>
        </div>
    </div>
    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px; border-right:5px solid #FFF;" ><span >Thumbnail:</span></div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="Thumbnail" type="text" id="Thumbnail" style=" font-size: 20px" size="50" placeholder="[filename ,empty indicates no]" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px; border-right:5px solid #FFF;" ><span class="zhushi">*</span><span> Playlist:</span></div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="Playlist" type="text" id="Playlist" style=" font-size: 20px" size="50" placeholder="[playlist id]" required="" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF;" ><span class="zhushi">* </span><span>Usage policy:</span></div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="UsagePolicy" type="text" id="UsagePolicy" style=" font-size: 20px" size="50" placeholder="[usage policy id]" required="" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF; " ><span>Match  policy:</span></div>
        <div style="float:left; width:580px; margin-top:8px;padding-left:10px;">
            <input name="MatchPolicy" type="text" id="MatchPolicy" style=" font-size: 20px" size="50" placeholder="[match policy id]" />
        </div >
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:300px;border-right:5px solid #FFF;" ><span>Notify subscribers:</span></div>
        <div style="float:left; width:300px;height:50px; line-height:50px;text-align: center;border-right:5px solid #FFF; ">
            <td width="325" align="center" bgcolor="#CBE7EB"><span class="neirong01">YES</span></td>
            <input type="radio" name="NotifySubscribers" id="NotifySubscribersYes" value="yes" checked="CHECKED"style="width:25px;height:25px"></div >

        <div style="float:left; width:300px;height:50px; line-height:50px;text-align: center; ">
            <td width="325" align="center" bgcolor="#CBE7EB"><span class="neirong01">NO</span></td>
            <input type="radio" name="NotifySubscribers" id="NotifySubscribersNo" value="no" style="width:25px;height:25px"/>
        </div>
    </div>
    <div class="biaoti">
        <div style="float:left; height:200px; line-height:200px; width:300px;border-right:5px solid #FFF; " ><span><span class="zhushi">* </span>Adbreak：</span></div>
        <div style="float:left; width:580px; margin-top:20px;padding-left:10px;">
            <textarea name="AdBreak" id="Adbreak" style=" font-size: 25px" cols="45" rows="5" placeholder="HH:MM:SS" required=""></textarea>
        </div >
    </div>

    <div class="neirong2 ">
        <div style="float:left; height:100px; line-height:100px; width:1000px; text-align: left; margin: 0 auto;margin-left:20px" >
            <span class="biaoti2">简体中文</span>
        </div>
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:480px; text-align: center;border-right:5px solid #FFF;" ><span class="zhushi">*</span><span class="neirong01">注意:</span></div>
        <div style="float:left; height:50px; line-height:50px; width:450px; text-align: center" ><span class="zhushi">*</span><span class="neirong01">keywords:</span></div>
    </div>

    <div class="biaoti">
        <div style="float:left; height:190px; line-height:190px; width:480px; text-align: center;margin-top:10px;border-right:5px solid #FFF;" >
            <ul id="Video notice4" style=" font-size: 18px; text-align: left; line-height: 28px; margin-top: 0px;">
                <li>建议至少20个keyword</li>
                <li>建议使用节目名称、明星名称、集数和日期</li>
                <li>前10个keyword比较关键</li>
                <li>基于上条，尽量确保keyword同时出现在title和description中</li>
                <li>可以考虑放其他语言的keyword</li>
            </ul>
        </div>
        <div style="float:left; height:190px; line-height:190px; width:400px; text-align: center;margin-top:10px;margin-left:40px" >
            <textarea name="VideoKeywordsChs" id="Video keyworkd4" style=" font-size: 18px" cols="35" rows="6" placeholder="keywords in simplified chinese" required=""></textarea>
        </div>
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:1000px;border-right:5px solid #FFF; " ><span class="zhushi">*</span><span class="title11">title:</span><span class="neirong">
      <input name="VideoTitleChs" type="text" id="VideoTitleChs" style=" font-size: 30px" size="50" placeholder="title in simplified chinese" required="" />
    </span></div>

    </div>

    <div class="biaoti">
        <div style="float:left; height:200px; line-height:200px; width:300px;border-right:5px solid #FFF; " ><span><span class="zhushi">* </span>description: </span></div>
        <div style="float:left; width:580px; margin-top:20px;padding-left:10px;" >
            <textarea name="VideoDesChs" id="Video description4" style=" font-size: 25px" cols="45" rows="5" placeholder="description in simplified chinese" required=""></textarea>
        </div>
    </div>

    <div class="neirong2 ">
        <div style="float:left; height:100px; line-height:100px; width:1280px; text-align: left; margin: 0 auto;margin-left:20px" ><span class="biaoti2">繁體中文</span></div>
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:1000px;border-right:5px solid #FFF; " ><span class="title11">title:
        <input name="VideoTitleCht" type="text" id="VideoTitleCht" style=" font-size: 30px" size="50" placeholder="title in traditional chinese" />
    </span ></div>

    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:480px; text-align: center;border-right:5px solid #FFF;" ><span class="neirong01">description:</span></div>
        <div style="float:left; height:50px; line-height:50px; width:400px; text-align: center" ><span class="neirong01">keywords:</span></div>
    </div>

    <div class="biaoti">
        <div style="float:left; height:190px; line-height:190px; width:480px; text-align: center;margin-top:10px;border-right:5px solid #FFF;" >
            <textarea name="VideoDesCht" id="textarea13" style=" font-size:18px" cols="35" rows="6" placeholder="description in traditional chinese" ></textarea>
        </div>
        <div style="float:left; height:190px; line-height:190px; width:400px; text-align: center;margin-top:10px;margin-left:40px" >
            <textarea name="VideoKeywordsCht" id="textarea14" style=" font-size:18px" cols="35" rows="6" placeholder="keywords in traditional chinese" ></textarea>
        </div>
    </div>

    <div class="neirong2 ">
        <div style="float:left; height:100px; line-height:100px; width:1280px; text-align: left; margin: 0 auto;margin-left:20px" ><span class="biaoti2">English</span></div>
    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:1000px; border-right:5px solid #FFF;" ><span class="title11">title:</span>
            <input name="VideoTitleEn" type="text" id="textfield14" style=" font-size: 30px" size="50" placeholder="title in english" />
        </div>

    </div>

    <div class="tianlan">
        <div style="float:left; height:50px; line-height:50px; width:480px; text-align: center;border-right:5px solid #FFF;" ><span class="neirong01">description:</span></div>
        <div style="float:left; height:50px; line-height:50px; width:400px; text-align: center" ><span class="neirong01">keywords:</span></div>
    </div>

    <div class="biaoti">
        <div style="float:left; height:190px; line-height:190px; width:480px; text-align: center;margin-top:10px;border-right:5px solid #FFF;" >
            <textarea name="VideoDesEn" id="textarea15" style=" font-size: 18px" cols="35" rows="6" placeholder="description in english" ></textarea>
        </div>
        <div style="float:left; height:190px; line-height:190px; width:400px; text-align: center;margin-top:10px;margin-left:40px" >
            <textarea name="VideoKeywordsEn" id="textarea16" style=" font-size: 18px" cols="35" rows="6" placeholder="keywords in english" ></textarea>
        </div>
    </div>
    <input id="type" name="type" value="" type="hidden">

    <div class="neirong2 ">
        <div style="float:left; height:100px; line-height:100px; width:1000px; text-align: center; margin: 0 auto;" >
            <input class="submit" type="submit" name="submit" value="生成XML" onclick="check('xml')" style=" width:200px; height:80px;">
            <input class="submit" type="submit" name="submit" value="生成CSV" onclick="check('csv1')" style=" width:200px; height:80px;">
            <input class="submit" type="button" name="button" value="生成多语言CSV" onclick="csv2Submit()" style=" width:200px; height:80px;">
            <a href="{{ route('genfile.translates') }}"><input class="submit" type="button" name="button" value="XML-->CSV" style=" width:200px; height:80px;"></a>
        </div>
    </div>

</form>

<form method="post" action="{{ route('genfile.store') }}" id="csv2">
    {{ csrf_field() }}
    <input name="VideoTitleEn" type="hidden" id="titleEn"/>
    <input name="VideoDesEn" type="hidden" id="desEn" />
    <input name="VideoKeywordsEn" type="hidden" id="keywordsEn" />
    <input name="VideoTitleCht" type="hidden" id="titleCht"/>
    <input name="VideoDesCht" type="hidden" id="desCht" />
    <input name="VideoKeywordsCht" type="hidden" id="keywordsCht" />
    <input name="type" value="csv2" type="hidden">
</form>
<p>&nbsp;</p>

<div class="footer">
    <p>@-Design by Bolei      @-Power by JDB&amp;WXY</p>
</div>
</body>
</html>