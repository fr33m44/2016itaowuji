<?php require('wxch_check.php'); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>砸金蛋</title>
	<meta name="viewport" content="initial-scale=1, minimum-scale=1, maximum-scale=1">
	<style>

	</style>
    <link href="eg.css" rel="stylesheet">
</head>
<body>

	<div class="grid">
		<div id="hammer"><img src="images/img-6.png" height="87" width="74" alt=""></div>
		<div id="f"><img src="images/img-4.png" /></div>
		<div id="banner">
			  <dl>
			    <dt>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			      <a href="javascript:;"><img src="images/egg_1.png" ></a>
			    </dt>
			    <dd></dd>
			  </dl>
		</div>
		<div class="block">
			<div class="title">每次扣除积分</div>
			<p ><span class="num1"><?php echo $prize['point']; ?></span></p>
		</div>
		<div class="block">
			<div class="title">目前还有积分</div>
			<p ><span class="num_point"><?php echo $user_point; ?></span></p>
		</div>
		<div class="block">
			<div class="title">剩余次数</div>
			<p >你还可抽奖的次数：<span class="num"><?php echo $prize['num']; ?></span></p>
		</div>
		<div class="block">
			<div class="title">活动规则</div>
				<p><?php echo $wxch_lang['prize_egg']; ?></p>
		</div>
		<div class="block">
			<div class="title">奖项设置</div>
            <?php foreach($wxchdata as $v) { ?>
				<p><?php echo $v['level'] ?>---<?php echo $v['prize_name'] ?> <?php if($v['state'] == 'yes'){ echo '---奖品数量：'.$v['prize_value']; }  ?></p>
            <?php } ?>
		</div>
		<div class="block">
			<div class="title">中奖记录</div>
				<?php foreach($prize_users as $v) { ?>
                    <p><?php echo $v['nickname'] ?>----<?php echo $v['prize_name'] ?></p>
                <?php } ?>
		</div>
	</div>
	<div id="mask"></div>
	<div id="dialog" class="yes">
		<div id="content"></div>
		<a href="<?php echo $go_contact; ?>">去看看</a>
		<button id="close">关闭</button>
	</div>
	
</body>
</html>
<script src="jq.js"></script>
<script>
    $(function() {
        var timer,forceStop;
        var wxch_Marquee = function(id){
            try{document.execCommand("BackgroundImageCache", false, true);}catch(e){};
            var container = document.getElementById(id),
                original = container.getElementsByTagName("dt")[0],
                clone = container.getElementsByTagName("dd")[0],
                speed = arguments[1] || 10;
            clone.innerHTML=original.innerHTML;
            var rolling = function(){
                if(container.scrollLeft == clone.offsetLeft){
                    container.scrollLeft = 0;
                }else{
                    container.scrollLeft++;
                }
            }
            this.stop = function() {
                clearInterval(timer);
            }
            timer = setInterval(rolling,speed);//设置定时器
            container.onmouseover=function() {clearInterval(timer)}//鼠标移到marquee上时，清除定时器，停止滚动
            container.onmouseout=function() {
                if (forceStop) return;
                timer=setInterval(rolling,speed);
            }//鼠标移开时重设定时器
        };

        var wxch_stop = function() {
            clearInterval(timer);
            forceStop = true;
        };
        var wxch_start = function() {
            forceStop = false;
            wxch_Marquee("banner",20);
        };

        wxch_Marquee("banner",20);

        var $egg;

        $("#banner a").on('click',function() {
            wxch_stop();
            $egg = $(this);
            var offset = $(this).position(),
                $hammer = $("#hammer");
            $hammer.animate({left:(offset.left+30)}, 1000,function(){
                $(this).addClass('hit');
                $("#f").css('left',offset.left).show();
                $egg.find('img').attr('src','images/egg_2.png');
                setTimeout(function() {
                    wxch_result.call(window);
                }, 500);
            });
        });

        $("#mask").on('click',function() {
            $(this).hide();
            $("#dialog").hide();
            $egg.find('img').attr('src','images/egg_1.png');
            $("#f").hide();
            $("#hammer").css('left','-74px').removeClass('hit');
            wxch_start();
        });

        $("#close").click(function() {
            $("#mask").trigger('click');
        });

        function wxch_result () {

            $.getJSON("<?php echo $base_url; ?>wechat/prize_data.php?pid=<?php echo $pid.'&pzfun='.$prize['pzfun'].'&wxid='.$wxid; ?>",function(data)
            {
                $("#mask").show();
                if (data.msg === 1) {
                    $("#content").html(data.prize);
                    $.post("<?php echo $base_url; ?>wechat/api.php?act=egg",{pid:data.pid,nickname:'<?php echo $prize[nickname] ?>',yn:data.yn,pzfun:data.pzfun,paid:data.paid,prize_value:data.prize_value,prize:data.prize,wxid:'<?php echo $wxid ?>'});
                    $("#dialog").attr("class",'yes').show();
                    setTimeout(function() {
                        //window.location.href = '<?php echo $go_contact; ?>';
                    },3000);
                }else if(data.msg === 0){
                    $("#content").html(data.prize);
                    $.post("<?php echo $base_url; ?>wechat/api.php?act=egg",{pid:data.pid,nickname:'<?php echo $prize[nickname] ?>',yn:data.yn,pzfun:data.pzfun,paid:data.paid,prize_value:data.prize_value,prize:data.prize,wxid:'<?php echo $wxid ?>'});
                    $("#dialog").attr("class",'no').show();
                }else if(data.msg == 2){
                    $("#content").html(data.prize);
                    $("#dialog").attr("class",'no').show();
                }else if(data.msg == 4){
                    $("#content").html(data.prize);
                    $("#dialog").attr("class",'no').show();
                }
				if(data.num >= 1)
                {
                    $(".num").text(data.num-1)
                }
                else
                {
                    $(".num").text(data.num);
                }
                   
					
					
					$(".num_point").text(data.point);
            });



        }
    });

</script>