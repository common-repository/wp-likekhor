<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>آمار لایک‌خور</h2>
	<?php
		$images_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/images';
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		
		$options = get_likekhor_options();
		$general = $options['likekhor_general'];
		$top_posts = $options['likekhor_top_posts'];
		$latest_posts = $options['likekhor_latest_posts'];
		$related_sites = $options['likekhor_related_sites'];
		
		$number_of_likes = $general['number_of_likes'];
		$number_of_subscribers = $general['number_of_subscribers'];
	?>
	<div >
		<style>
			.highlight {
				color: #FF6600;
				background-color: transparent;
			}
			.tabs-hide {
				display: none;
			}
			
			#tabnav li {
				color: #FFFFFF;
				direction: rtl;
				display: inline;
				height: 57px;
				list-style: none outside none;
				padding-left:10px;
			}
			.tabs-container {
				-moz-border-radius: 6px 0 6px 6px;
				border-radius: 6px 0 6px 6px;
				border: 1px solid silver;
				margin-top: 3px;
				padding: 5px;
				background-color: white;
			}
			
			#tabnav li a {
				text-decoration: none;
				color: black;
			}
			#tabnav li a:hover {
				color: green;
			}
			.tabs-selected a {
				font-weight:bold;
				text-decoration:none;
			}
			.tabs-selected {
				-moz-border-radius: 6px 6px 0 0;
				border-radius: 6px 6px 0 0;
				border: 1px solid silver;
				border-bottom: 1px solid white;
				padding: 5px;
				background-color: white;
			}
		</style>
		<h2>
		<?php
			echo $general['title'];
			$fullstars = floor(floatval($general['stars']));
			for($i = 0; $i < $fullstars; $i++) {
				?>
				<img width="20" style="margin: 0pt -5px;" valign="middle" src="<?php echo $images_url;?>/fullstar.png" />
				<?php 
			}
			if(floatval($general['stars']) - $fullstars != 0) {
				?>
				<img width="20" style="margin: 0pt -5px;" valign="middle" src="<?php echo $images_url;?>/halfstar.png" />
				<?php 
			}
		?>
		</h2>
		<script src="<?php echo $plugin_url;?>/jquery.fading-tab.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(function() {jQuery('#tabwrap').tabs({ fxFade: true, fxSpeed: 'slow' }); });
		</script>
		<div id="slide">
			<div id="tabwrap">
			<ul id="tabnav">
				<li id="tab_1"><a href="#content_tab1">اطلاعات کلی</a></li>
				<li id="tab_2"><a href="#content_tab2">پست‌های پرلایک</a></li>
				<li id="tab_3"><a href="#content_tab3">پست‌های اخیر</a></li>
				<li id="tab_4"><a href="#content_tab4">نمودارها</a></li>
				<li id="tab_5"><a href="#content_tab5">سایت‌های مشابه</a></li>
			</ul>
			<div id="content_tab1">
				<p>آخرین زمان شمارش: <span class="highlight"><?php echo str_replace('%and%amp;' ,'&', $general['last_counted_fa']);?></span></p>
				<p>زمان ثبت در لایک‌خور: <span class="highlight"><?php echo str_replace('%and%amp;' ,'&', $general['date_added_fa']);?></span></p>
				<div>		
					<table cellspacing="10" >
					  <tbody>
					  <tr>
						<td valign="top" width="50%">
						تعداد لایک: <font class="highlight"><?php echo number_format_i18n($number_of_likes);?> </font><br>
						تعداد خواننده: <font class="highlight"><?php echo number_format_i18n($number_of_subscribers);?></font>
						</td>
						<td valign="top">
						 اندیسِ H [<a target="_blank" href="http://goder.hopto.org/hot/?help=1#hindex">؟</a>]: <font class="highlight"><?php echo $general['h_index'];?></font> <br />
						میانگین کلمات هر پست: <font class="highlight"><?php echo $general['word_count_mean'];?></font> <br>
						</td>
					  </tr>
					 </tbody>
					</table>
					<h4>رتبه در جداول لایک‌خور</h4>
					<table cellspacing="0" cellpadding="0" style="margin: 10px 0px; width: 100%;">
					  <tbody>
					  <tr>
						<td><a href="http://goder.hopto.org/ahoo"><img border="0" valign="middle" src="<?php echo $images_url;?>/fawn-small-color.png"> 
						  </a>(کل لایک): <span class="highlight"><?php echo $general['rank_ahoo'];?></span></td>
						<td><a href="http://goder.hopto.org/ghoo"><img border="0" valign="middle" src="<?php echo $images_url;?>/swan-small-color.png"> 
						  </a>(لایک/پست): <span class="highlight"><?php echo $general['rank_ghoo'];?></span></td>
						<td><a href="http://goder.hopto.org/dolphin"><img border="0" valign="middle" src="<?php echo $images_url;?>/dolphin-small-color.png"> 
						  </a>(خواننده): <span class="highlight"><?php echo $general['rank_dolphin'];?></span></td>
						<td><a href="http://goder.hopto.org/simorgh"><img border="0" valign="middle" src="<?php echo $images_url;?>/phoenix-small-color.png"> 
						  </a>(همه معیارها): <span class="highlight"><?php echo $general['rank_simorgh'];?></span></td>
					  </tr>
					  </tbody>
					</table>
					<h4>رتبه در طبقه‌بندی دسته‌بندی این وبلاگ</h4>
					<table cellspacing="0" cellpadding="0" style="margin: 10px 0px; width: 100%;">
					  <tbody>
					  <tr>
						<td><a href="http://goder.hopto.org/ahoo/?type=non-minimal"><img border="0" valign="middle" src="<?php echo $images_url;?>/fawn-small-color.png"> 
						  </a>(کل لایک): <span class="highlight"><?php echo $general['rank_ahoo_category'];?></span></td>
						<td><a href="http://goder.hopto.org/ghoo/?type=non-minimal"><img border="0" valign="middle" src="<?php echo $images_url;?>/swan-small-color.png"> 
						  </a>(لایک/پست): <span class="highlight"><?php echo $general['rank_ghoo_category'];?></span></td>
						<td><a href="http://goder.hopto.org/dolphin/?type=non-minimal"><img border="0" valign="middle" src="<?php echo $images_url;?>/dolphin-small-color.png"> </a>(خواننده): <span class="highlight"><?php echo $general['rank_dolphin_category'];?></span></td>
						<td><a href="http://goder.hopto.org/simorgh?type=non-minimal"><img border="0" valign="middle" src="<?php echo $images_url;?>/phoenix-small-color.png"> </a>(همه معیارها): <span class="highlight"><?php echo $general['rank_simorgh_category'];?></span></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="content_tab2">
				<h4>پست‌های پرلایک (حداقل ۲۰ لایک) به ترتیب تعداد لایک: </h4>
				<ul>
				<?php
					if($top_posts)
					foreach($top_posts as $post)
						echo "<li><a style='text-decoration:none;' href='{$post[link]}'>".str_replace('%and%', '&', $post['title'])."</a> ({$post[likes]})</li>";
				?>
				</ul>
			</div>
			<div id="content_tab3">
				<h4>پست‌هایی که اخیرا در لایک‌خور لیست شده‌اند: </h4>
				<ul>
				<?php
					if($latest_posts)
					foreach($latest_posts as $post)
						echo "<li><a style='text-decoration:none;' href='{$post[link]}'>".str_replace('%and%', '&', $post['title'])."</a> ({$post[likes]})</li>";
				?>
				</ul>
			</div>
			<div id="content_tab4">
				<p>نمودار تعداد لایک های گرفته شده در روز:
				</p><div align="center" style="margin: 20px;">
				<p>
				<script src="<?php echo $plugin_url;?>/swfobject.js" type="text/javascript"></script>
				</p><div id="flashcontent"></div>
				<script type="text/javascript">
				var so = new SWFObject("<?php echo $plugin_url;?>/open-flash-chart.swf", "chart", "450", "200", "9", "#FFFFFF");
				so.addVariable("data", encodeURI("<?php echo $plugin_url;?>/likekhor-ofc.php?type=likes_chart"));
				so.addParam("allowScriptAccess", "sameDomain");
				so.addParam("wmode", "opaque");
				so.write("flashcontent");
				</script>
				</div>
				<p>نمودار مشترکین:
				</p><div align="center" style="margin: 20px;">
				<p>
				</p><div id="flashcontent_2"><embed height="200" width="450" flashvars="data=http%3A%2F%2Fgoder.hopto.org%2Ffunctions%2Fsubscribers_chart.php%3Fsid%3D44" wmode="opaque" allowscriptaccess="sameDomain" quality="high" bgcolor="#FFFFFF" name="chart_2" id="chart_2" style="" src="/ofc-library/open-flash-chart.swf" type="application/x-shockwave-flash"></div>
				<script type="text/javascript">
				var so = new SWFObject("<?php echo $plugin_url;?>/open-flash-chart.swf", "chart_2", "450", "200", "9", "#FFFFFF");
				so.addVariable("data", encodeURI("<?php echo $plugin_url;?>/likekhor-ofc.php?type=subscribers_chart"));
				so.addParam("allowScriptAccess", "sameDomain");
				so.addParam("wmode", "opaque");
				so.write("flashcontent_2");
				</script>
				</div>
				<p>نمودار میانگین لایک به پست:
				</p><div align="center" style="margin: 20px;">
				<p>
				</p><div id="flashcontent_3"><embed height="200" width="450" flashvars="data=http%3A%2F%2Fgoder.hopto.org%2Ffunctions%2Flike_to_post_chart.php%3Fsid%3D44" wmode="opaque" allowscriptaccess="sameDomain" quality="high" bgcolor="#FFFFFF" name="chart_3" id="chart_3" style="" src="/ofc-library/open-flash-chart.swf" type="application/x-shockwave-flash"></div>
				<script type="text/javascript">
				var so = new SWFObject("<?php echo $plugin_url;?>/open-flash-chart.swf", "chart_3", "450", "200", "9", "#FFFFFF");
				so.addVariable("data", encodeURI("<?php echo $plugin_url;?>/likekhor-ofc.php?type=likes_to_post_chart"));
				so.addParam("allowScriptAccess", "sameDomain");
				so.addParam("wmode", "opaque");
				so.write("flashcontent_3");
				</script>
				</div>
				<p>نمودار کمیتِ لایک&zwnj;خور:
				</p><div align="center" style="margin: 20px;">
				<p>
				</p><div id="flashcontent_4"><embed height="200" width="450" flashvars="data=http%3A%2F%2Fgoder.hopto.org%2Ffunctions%2Flikekhor_chart.php%3Fsid%3D44" wmode="opaque" allowscriptaccess="sameDomain" quality="high" bgcolor="#FFFFFF" name="chart_4" id="chart_4" style="" src="/ofc-library/open-flash-chart.swf" type="application/x-shockwave-flash"></div>
				<script type="text/javascript">
				var so = new SWFObject("<?php echo $plugin_url;?>/open-flash-chart.swf", "chart_4", "450", "200", "9", "#FFFFFF");
				so.addVariable("data", encodeURI("<?php echo $plugin_url;?>/likekhor-ofc.php?type=likekhor_chart"));
				so.addParam("allowScriptAccess", "sameDomain");
				so.addParam("wmode", "opaque");
				so.write("flashcontent_4");
				</script>
				</div>
			</div>
			<div id="content_tab5">
				<h4>سایت‌ها و وبلاگ‌های مشابه بر اساس آمار گوگل ریدر</h4>
				<ul>
				<?php
					if($related_sites)
						foreach($related_sites as $site)
							echo "<li><a style='text-decoration:none;' href='{$site[link]}'>{$site[title]}</a></li>";
				?>
				</ul>
			</div>
			
			</div>
		</div>

		<div style="padding: 10px; background: none repeat scroll 0% 0% rgb(238, 238, 238);">لایک&zwnj;خور را 
		دوست دارید؟ ایده اش را می پسندید؟ پس چرا با <a target="_blank" href="http://likekhor.com/tools/#logos" class="dotted">یک لینک کوچک</a> در وبلاگ و یا 
		فیس&zwnj;بوکتان حمایتش نکنید؟ 
		</div>
	</div>

</div>
