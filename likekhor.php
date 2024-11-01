<?php

	/*
	Plugin Name: Likekhor
	Plugin URI: http://www.moallemi.ir/blog/1389/04/30/%D9%85%D8%B9%D8%B1%D9%81%DB%8C-%D8%A7%D9%81%D8%B2%D9%88%D9%86%D9%87-%D9%84%D8%A7%DB%8C%DA%A9-%D8%AE%D9%88%D8%B1-%D9%88%D8%B1%D8%AF%D9%BE%D8%B1%D8%B3/
	Description: This plugin export the feed stats of each wordpress blog in the Google Reader with use of likekhor.com service.
	Version: 0.8
	Author: Reza Moallemi
	Author URI: http://www.moallemi.ir/blog
	*/
	
	add_action('admin_menu', 'likekhor_menu');

	function likekhor_menu() 
	{
		add_options_page('تنظیمات لایک‌خور', 'لایک‌خور', 10, 'likekhor', 'likekhor_options');
		add_submenu_page('index.php', 'آمار لایک‌خور', 'آمار لایک‌خور', 10, 'wp-likekhor/likekhor-stats.php');
	}

	function get_likekhor_options()
	{
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		$likekhor_options = array('site_address' => '',
								'last_update' => 'N/A',
								'interval' => '8',
								'plugin_status' => 'UNCOMPLETED_OPTIONS',
								'likekhor_general' => '',
								'likekhor_top_posts' => '',
								'likekhor_latest_posts' => '',
								'likekhor_related_sites' => '',
								'likekhor_graphs' => '',
								'show_author_url' => 'true',
								'show_plugin_url' => 'true', 
								'plugin_version' => '0.7');
		$likekhor_save_options = get_option('likekhor_options');
		if (!empty($likekhor_save_options))
		{
			foreach ($likekhor_save_options as $key => $option)
			$likekhor_options[$key] = $option;
		}
		update_option('likekhor_options', $likekhor_options);
		return $likekhor_options;
	}
	
	
	function Likekhor() 
	{
		add_filter('wp_footer', 'likekhor_check_for_update');
		//delete_option('likekhor_options');
		$options = get_likekhor_options();
		
		if (is_admin() and $options['plugin_status'] == 'BAD_FEED' and empty($_POST['site_address'])) 
		{
			$currbasename = (isset($_GET['page'])) ? $_GET['page'] : ''; 
			if ($currbasename == 'likekhor' or $currbasename == 'wp-likekhor/likekhor-stats.php') 
			{
				$msg = '<div class="error"><p>متاسفانه آدرسی که وارد کرده‌اید صحیح نمی‌باشد. <a href="admin.php?page=likekhor">لطفا تنظیمات صحیح را وارد کنید</a></p></div>';
				add_action('admin_notices', create_function( '', "echo '$msg';" ));
			}
		}
		
		if (is_admin() and $options['plugin_status'] == 'UNCOMPLETED_OPTIONS' ) 
		{
			$currbasename = (isset($_GET['page'])) ? $_GET['page'] : ''; 
			if ($currbasename == 'wp-likekhor/likekhor-stats.php') 
			{
				$msg = '<div class="error"><p>آدرس سایت شما باید در قسمت تنظیمات وارد شود. لطفا از <a href="'.get_option('siteurl').'/wp-admin/options-general.php?page=likekhor">اینجا</a> برای تکمیل تنظیمات اقدام کنید.</p></div>';
				add_action('admin_notices', create_function( '', "echo '$msg';" ));
			}
		}
		
	}
	
	
	function likekhor_check_for_update()
	{
		//print '<pre dir="ltr">';
		$options = get_likekhor_options();
		//print_r($options);
		if(is_admin() and $options['plugin_status'] == 'UNCOMPLETED_OPTIONS')
		{
			//print "UNCOMPLETED_OPTIONS";
		}
		else if($options['plugin_status'] != 'UNCOMPLETED_OPTIONS')
		{
			$current_date = date('Y-m-d H:i:s');
			$last_update = $options['last_update'];
			$interval = $options['interval'];
			//print "upd: $last_update \n";
			//print "now: $current_date \n";
			//print "int: $interval \n";
			//print date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s", strtotime($last_update)) . " +$interval hours"))."\n";
			if(strtotime($current_date) >= strtotime(date("Y-m-d H:i:s", strtotime($last_update)) . " +$interval hours")) 
			{
				likekhor_fetch_feed();
			}
			//else
			//{
			//	print 'not now';
			//}
					
			//print '</pre>';
		}
	}
	
	
	function likekhor_options()
	{
		$likekhor_options = get_likekhor_options();
		$plugin_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__));
		if (isset($_POST['update_likekhor_settings']))
		{
			$likekhor_options['site_address'] = isset($_POST['site_address']) ? $_POST['site_address'] : '';
			$likekhor_options['show_plugin_url'] = isset($_POST['show_plugin_url']) ? $_POST['show_plugin_url'] : 'false';
			$likekhor_options['show_author_url'] = isset($_POST['show_author_url']) ? $_POST['show_author_url'] : 'false';
			if($likekhor_options['plugin_status'] == 'UNCOMPLETED_OPTIONS')
				$likekhor_options['plugin_status'] = 'READY_FOR_CHECK';
			
			update_option('likekhor_options', $likekhor_options);
			likekhor_check_for_update();
			?>
			<div class="updated">
				<p><strong>تنظیمات ذخیره شد.</strong></p>
			</div>
			<?php
		}
		$likekhor_options = get_likekhor_options();
		$last_update = $likekhor_options['last_update'];
		$interval = $likekhor_options['interval'];
		?>
		<div class=wrap>
		<?php if(function_exists('screen_icon')) screen_icon(); ?>
			<h2>تنظیمات لایک‌خور</h2>
			<form id="likekhor_mainform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" >
				<h3>وضعیت افزونه</h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">زمان فعلی </th>
						<td style="direction:ltr;text-align:right;"><?php echo likekhor_date('Y-m-d H:i:s'); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">آخرین به‌روزرسانی </th>
						<td style="direction:ltr;text-align:right;"><?php echo likekhor_date('Y-m-d H:i:s', $likekhor_options['last_update']); ?></td>
					</tr>
					<tr valign="top">
						<th scope="row">به‌روزرسانی بعدی </th>
						<td style="direction:ltr;text-align:right;">
							<?php if ($likekhor_options['last_update'] != 'N/A') echo likekhor_date('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($last_update)) . " +$interval hours"); ?>
						</td>
					</tr>
				</table>
				<h3>تنظیمات آدرس سایت</h3>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">آدرس ثبت شده در لایک‌خور  </th>
						<td>
							<input style="direction:ltr;width:300px;" id="site_address" name="site_address" type="text" value="<?php echo $likekhor_options['site_address']; ?>" /> 
						</td>
					</tr>
				</table>
				<h3>به گسترش افزونه کمک کنید:</h3>
				<p><input name="show_plugin_url" value="true" type="checkbox" <?php if ($likekhor_options['show_plugin_url'] == 'true' ) echo ' checked="checked" '; ?> /> نمایش لینک به لایک‌خور</p>
				<p><input name="show_author_url" value="true" type="checkbox" <?php if ($likekhor_options['show_author_url'] == 'true' ) echo ' checked="checked" '; ?> /> نمایش لینک به کاوشگر</p>
				<div class="submit">
					<input class="button-primary" type="submit" name="update_likekhor_settings" value="ذخیره تغییرات" />
				</div>
				<hr />
				<div>
					<h4>دیگر افزونه‌های کاوشگر برای وردپرس</h4>
					<ul>
						<li><font color="red"><b> - آماره‏ی گوگل ریدر</b></font>
							(<a href="http://wordpress.org/extend/plugins/google-reader-stats/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1389/04/12/%d9%86%d8%b3%d8%ae%d9%87-%d8%ac%d8%af%db%8c%d8%af-%d8%a2%d9%85%d8%a7%d8%b1-%da%af%d9%88%da%af%d9%84-%d8%b1%db%8c%d8%af%d8%b1-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3-3-%d9%84%d8%a7%db%8c%da%a9/">اطلاعات بیشتر</a>)
						</li>
						<li><font color="blue"><b>- نظرات در فید</b></font>
							(<a href="http://wordpress.org/extend/plugins/comments-on-feed/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1389/04/07/%d9%86%d8%b8%d8%b1%d8%a7%d8%aa-%d8%af%d8%b1-%d9%81%db%8c%d8%af-%d8%a8%d8%b1%d8%a7%db%8c-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3-%db%b3-%da%a9%d8%a7%d9%88%d8%b4%da%af%d8%b1-wordpress-3/">اطلاعات بیشتر</a>)
						</li>
						<li><b>- نویسه‌گردان گوگل</b>
							(<a href="http://wordpress.org/extend/plugins/google-transliteration/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1388/07/19/%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%db%8c-%d9%86%d9%88%db%8c%d8%b3%d9%87-%da%af%d8%b1%d8%af%d8%a7%d9%86-%da%af%d9%88%da%af%d9%84-%d8%a8%d8%b1%d8%a7%db%8c-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3/">اطلاعات بیشتر</a>)
						</li>						
						<li><b>- نمایش‌دهنده اطلاعات نظردهندگان</b>
							(<a href="http://wordpress.org/extend/plugins/advanced-user-agent-displayer/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1388/07/24/%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%db%8c-%d9%86%d9%85%d8%a7%db%8c%d8%b4-%d8%af%d9%87%d9%86%d8%af%d9%87-%db%8c-%d8%a7%d8%b7%d9%84%d8%a7%d8%b9%d8%a7%d8%aa-%d9%86%d8%b8%d8%b1-%d8%af%d9%87%d9%86%d8%af/">اطلاعات بیشتر</a>)
						</li>						
						<li><b>- نویسه‌گردان بهنویس</b> 
							(<a href="http://wordpress.org/extend/plugins/behnevis-transliteration/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1388/07/25/%D8%A7%D9%81%D8%B2%D9%88%D9%86%D9%87-%D9%86%D9%88%DB%8C%D8%B3%D9%87-%DA%AF%D8%B1%D8%AF%D8%A7%D9%86-%D8%A8%D9%87%D9%86%D9%88%DB%8C%D8%B3-%D8%A8%D8%B1%D8%A7%DB%8C-%D9%88%D8%B1%D8%AF%D9%BE%D8%B1%D8%B3/">اطلاعات بیشتر</a> )
						</li>
						<li><b>- تاخیر در فید</b> 
							(<a href="http://wordpress.org/extend/plugins/feed-delay/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1388/12/07/%d8%a7%d9%81%d8%b2%d9%88%d9%86%d9%87-%d8%a7%d9%86%d8%aa%d8%b4%d8%a7%d8%b1-%d9%85%d8%b7%d8%a7%d9%84%d8%a8-%d9%81%db%8c%d8%af-%d8%aa%d8%a7%d8%ae%db%8c%d8%b1-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3/">اطلاعات بیشتر</a>)
						</li>
						<li><b>- تماس با نظردهندگان</b> 
							(<a href="http://wordpress.org/extend/plugins/contact-commenter/">دریافت</a> | 
							<a href="http://www.moallemi.ir/blog/1388/12/27/%d9%87%d8%af%db%8c%d9%87-%da%a9%d8%a7%d9%88%d8%b4%da%af%d8%b1-%d9%85%d9%86%d8%a7%d8%b3%d8%a8%d8%aa-%d8%b3%d8%a7%d9%84-%d9%86%d9%88-%d9%88%d8%b1%d8%af%d9%be%d8%b1%d8%b3/">اطلاعات بیشتر</a>)
						</li>
					</ul>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="<?php echo $plugin_url ?>/jquery.validate.min.js"></script>
		<script type="text/javascript">
			/* <![CDATA[*/
			jQuery(document).ready(function() {
			  jQuery("#likekhor_mainform").validate({
				rules: {
				  site_address: {
					required: true,
					url: true
				  }
				},
				messages: {
				  site_address: 'لطفا آدرس صحیح را وارد کنید'
				}
			  });
			});
			/* ]]> */
		</script>
		<?php
	}
			
	function likekhor_date($format, $date = 'now')
	{
		if(function_exists('jdate'))
			return jdate($format, strtotime($date));
		else
			return date($format, strtotime($date));
	}
	
	function likekhor_dashboard_widget_function()
	{
		$images_url = get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/images';
		$options = get_likekhor_options();
		$general = $options['likekhor_general'];
		
		if($options['plugin_status'] == 'UNCOMPLETED_OPTIONS')
		{
			echo '<font color="red">آدرس سایت شما باید در قسمت تنظیمات وارد شود. لطفا از <a href="'.get_option('siteurl').'/wp-admin/options-general.php?page=likekhor">اینجا</a> برای تکمیل تنظیمات اقدام کنید.</font>';
		}
		elseif($options['plugin_status'] == 'BAD_FEED')
		{
			echo '<font color="red">متاسفانه آدرسی که وارد کرده‌اید صحیح نمی‌باشد. لطفا <a href="'.get_option('siteurl').'/wp-admin/options-general.php?page=likekhor">تنظیمات صحیح</a> را وارد کنید.</font>';
		}
		else
		{
		$number_of_likes = $general['number_of_likes'];
		$number_of_subscribers = $general['number_of_subscribers'];
		?>
		<div>
			<div>
				<p>آخرین زمان شمارش: <?php echo str_replace('%and%amp;' ,'&', $general['last_counted_fa']);?></p>
				<table style="width:100%;">
				  <tr>
					<td style="width:50%;">
					  <span style="font-size: 18px;color:#1c6280;"><?php echo number_format_i18n($number_of_likes);?></span> لایک
					</td>
					<td>
					  <span style="font-size: 18px;color:#1c6280;"><?php echo number_format_i18n($number_of_subscribers);?></span> خواننده گودری
					</td>
				  </tr>
				</table>
			</div>
			<div>
			<p style="color: #777777;font-size: 13px;">رتبه‌ی شما در لایک‌خور
				<?php  
				$fullstars = floor(floatval($general['stars']));
				for($i = 0; $i < $fullstars; $i++) {
					?>
					<img width="19" style="margin: 0pt -4px;" valign="middle" src="<?php echo $images_url;?>/fullstar.png" />
					<?php 
				}
				if(floatval($general['stars']) - $fullstars != 0) {
					?>
					<img width="19" style="margin: 0pt -4px;" valign="middle" src="<?php echo $images_url;?>/halfstar.png" />
					<?php 
				} 
				?>
			  </p>
			  <table style="width:100%;border-top-width: 1px;border-top-style: solid; border-top-color: #ececec;">
				  <tr>
					<td>
					  <span style="font-size: 18px;color:#1c6280;"><?php echo $general['rank_ahoo'];?></span> <img title="مجموع کل لایک‌ها" src="<?php echo $images_url;?>/fawn-small-color.png" />
					</td>
					<td >
					  <span style="font-size: 18px;color:#1c6280;"><?php echo $general['rank_ghoo'];?></span> <img title="میانگین لایک در هر پست" src="<?php echo $images_url;?>/swan-small-color.png" />
					</td>
				  </tr>
				  <tr>
					<td>
					  <span style="font-size: 18px;color:#1c6280;"><?php echo $general['rank_dolphin'];?></span> <img title="تعداد دنبال کنندگان" src="<?php echo $images_url;?>/dolphin-small-color.png" />
					</td>
					<td>
					  <span style="font-size: 18px;color:#1c6280;"><?php echo $general['rank_simorgh'];?></span> <img title="همه‌ی معیارها" src="<?php echo $images_url;?>/phoenix-small-color.png" />
					</td>
				  </tr>
			  </table>
			</div>
		</div>
		<?php
		}
	} 

	function likekhor_add_dashboard_widgets()
	{
		wp_add_dashboard_widget('likekhor_dashboard_widget', 'آمار لایک‌خور', 'likekhor_dashboard_widget_function');
	} 

	add_action('wp_dashboard_setup', 'likekhor_add_dashboard_widgets' );

	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'likekhor_links' );
	
	function likekhor_links($links)
	{ 
		$settings_link = '<a href="options-general.php?page=likekhor">'.__('Settings', 'likekhor').'</a>';
		array_unshift($links, $settings_link); 
		return $links; 
	}
	
	function likekhor_fetch_feed()
	{
		$options = get_likekhor_options();
		//print_r($options);die;
		
		$url = $options['site_address'];
		$general = @file_get_contents("http://likekhor.com/tools/api/?site=$url&section=general");
		$top_posts = @file_get_contents("http://likekhor.com/tools/api/?site=$url&section=top_posts");
		$latest_posts = @file_get_contents("http://likekhor.com/tools/api/?site=$url&section=latest_posts");
		$related_sites = @file_get_contents("http://likekhor.com/tools/api/?site=$url&section=related_sites");
		$graph = @file_get_contents("http://likekhor.com/tools/api/?site=$url&section=graphs");
		
		$feedpos = strpos($general, "<rss");
		if(!$feedpos) 
		{
			$options['plugin_status'] = 'BAD_FEED';
			update_option('likekhor_options', $options);
			if(is_admin())
			{
				//print_r($options);
				?>
				<div class="error">
					<p>
						متاسفانه آدرسی که وارد کرده‌اید صحیح نمی‌باشد. <a href="admin.php?page=likekhor">لطفا تنظیمات صحیح را وارد کنید</a>
					</p>
				</div>
				<?php 
			}
		}
		else
		{
			$options['plugin_status'] = 'OK';
			$options['last_update'] = date('Y-m-d H:i:s');
			
			require_once 'likekhor-xml.php';
		
			$xmltoarray = new XMLParser(); 
			$xmltoarray->SetOption(XML_OPTION_SKIP_WHITE, 1); 
			$xmltoarray->SetOption(XML_OPTION_CASE_FOLDING, 0);
			$xmltoarray->FixIntoStruct($general);
			$array = $xmltoarray->CreateArray();
			$general = $array['rss']['channel'][0]['item'][0];
			
			$xmltoarray = new XMLParser(); 
			$xmltoarray->SetOption(XML_OPTION_SKIP_WHITE, 1); 
			$xmltoarray->SetOption(XML_OPTION_CASE_FOLDING, 0);
			$xmltoarray->FixIntoStruct($top_posts);
			$array = $xmltoarray->CreateArray();
			$top_posts = $array['rss']['channel'][0]['item'];
			
			$xmltoarray = new XMLParser(); 
			$xmltoarray->SetOption(XML_OPTION_SKIP_WHITE, 1); 
			$xmltoarray->SetOption(XML_OPTION_CASE_FOLDING, 0);
			$xmltoarray->FixIntoStruct($latest_posts);
			$array = $xmltoarray->CreateArray();
			$latest_posts = $array['rss']['channel'][0]['item'];
			
			$xmltoarray = new XMLParser(); 
			$xmltoarray->SetOption(XML_OPTION_SKIP_WHITE, 1); 
			$xmltoarray->SetOption(XML_OPTION_CASE_FOLDING, 0);
			$xmltoarray->FixIntoStruct($related_sites);
			$array = $xmltoarray->CreateArray();
			$related_sites = $array['rss']['channel'][0]['item'];
			
			$xmltoarray = new XMLParser(); 
			$xmltoarray->SetOption(XML_OPTION_SKIP_WHITE, 1); 
			$xmltoarray->SetOption(XML_OPTION_CASE_FOLDING, 0);
			$xmltoarray->FixIntoStruct($graph);
			$array = $xmltoarray->CreateArray();
			$graph = $array['rss']['channel'][0]['item'];
			
			$options['likekhor_general'] = $general;
			$options['likekhor_top_posts'] = $top_posts;
			$options['likekhor_latest_posts'] = $latest_posts;
			$options['likekhor_related_sites'] = $related_sites;
			$options['likekhor_graphs'] = $graph;
											
			update_option('likekhor_options', $options);
		}
		
	}
	
	
	class WP_Widget_Likekhor extends WP_Widget
	{
		
		function WP_Widget_Likekhor() 
		{
			$widget_ops = array('description' => 'ابزارک نمایش دهنده آمار لایک‌خور', 'likekhor');
			$this->WP_Widget('likekhor', 'لایک‌خور (نوشته‌ها)', $widget_ops);
		}

		
		function widget($args, $instance) 
		{
			extract($args);
			$title = empty($instance['title']) ? 'آمار لایک‌خور' : apply_filters('widget_title', $instance['title']);
			$type = esc_attr($instance['type']);
			$limit = intval($instance['limit']);
			$chars = intval($instance['chars']);
			echo $before_widget.$before_title.$title.$after_title;
			echo '<ul>'."\n";
			switch($type) {
				case 'most_liked':
					likekhor_get_most_liked($limit, $chars);
					break;
				case 'most_viewed':
					likekhor_get_most_viewed($limit, $chars);
					break;
			}
			echo '</ul>'."\n";
			echo $after_widget;
		}

		function update($new_instance, $old_instance) 
		{
			if (!isset($new_instance['submit'])) {
				return false;
			}
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['type'] = strip_tags($new_instance['type']);
			$instance['limit'] = intval($new_instance['limit']);
			$instance['chars'] = intval($new_instance['chars']);
			return $instance;
		}

		function form($instance) 
		{
			global $wpdb;
			$instance = wp_parse_args((array) $instance, array('title' => 'آمار لایک‌خور', 'type' => 'most_viewed', 'limit' => 10, 'chars' => 200));
			$title = esc_attr($instance['title']);
			$type = esc_attr($instance['type']);
			$limit = intval($instance['limit']);
			$chars = intval($instance['chars']);
	?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">عنوان: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('type'); ?>">نوع آمار:
					<select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>" class="widefat">
						<option value="most_liked"<?php selected('most_liked', $type); ?>>نوشته‌های پر لایک در گوگل ریدر</option>
						<option value="most_viewed"<?php selected('most_viewed', $type); ?>>نوشته‌های لیست شده در لایک‌خور</option>
					</select>
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>">تعداد نوشته‌ها برای نمایش: <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label>
			</p>
			
			<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php
	
		}
	}
	
	class WP_Widget_Likekhor_Mini extends WP_Widget
	{
		
		function WP_Widget_Likekhor_Mini() 
		{
			$widget_ops = array('description' => 'ابزارک نمایش دهنده آمار کوتاه لایک‌خور', 'likekhor_mini');
			$this->WP_Widget('likekhor_mini', 'آمار لایک‌خور', $widget_ops);
		}

		
		function widget($args, $instance) 
		{
			extract($args);
			$title = empty($instance['title']) ? 'آمار گوگل ریدر' : apply_filters('widget_title', $instance['title']);
			$text = $instance['text'];
			echo $before_widget.$before_title.$title.$after_title;
			echo '<ul>'."\n";
			likekhor_mini_show_stats($text);
			echo '</ul>'."\n";
			echo $after_widget;
		}

		function update($new_instance, $old_instance) 
		{
			if (!isset($new_instance['submit'])) {
				return false;
			}
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['text'] = $new_instance['text'];
			return $instance;
		}

		function form($instance) 
		{
			global $wpdb;
			$instance = wp_parse_args((array) $instance, array('title' => 'آمار گوگل ریدر', 'text' => 'شما در مجموع [subscribers] خواننده گودری دارید. تعداد لایک‌ها بر روی نوشته‌های شما [likes] می‌باشد.'));
			$title = esc_attr($instance['title']);
			$text = $instance['text'];
	?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">عنوان: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('text'); ?>">متن: <textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" ><?php echo $text; ?></textarea></label>
				<small><b>[likes]</b> تعداد لایک‌ها</small><br />
				<small><b>[subscribers]</b> تعداد مشترکین</small><br />
				<small>استفاده از کدهای HTML‌ مجاز است.</small>
			</p>
			
			<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php
	
		}
	}


	
	add_action('widgets_init', 'widget_likekhor_init');
	function widget_likekhor_init() {
		register_widget('WP_Widget_Likekhor');
		register_widget('WP_Widget_Likekhor_Mini');
	}
	
	Likekhor();
		
	function likekhor_get_most_liked($limit = 10, $chars = 0, $display = true) 
	{
		$options = get_option('likekhor_options');
		$output = '';
		$most_liked = $options['likekhor_top_posts'];
		
		$count = 0;
		if($most_liked != '') 
		{
			$output .= '<ul>';
			foreach ($most_liked as $post) 
			{
				if($count == $limit)
					break;
				if($chars > 0) 
					$post_title = likekhor_snippet_text($post['title'], $chars);
				$output .= "<li><a href='$post[link]'>".str_replace('%and%', '&', $post[title])."</a> - $post[likes] لایک</li>";
				$count++;
			}
			$output .= '</ul>';
			if($options['show_plugin_url'] == 'true' or $options['show_author_url'] == 'true')
				$output .= '<p style="border-top:1px solid #EEEEEE" />';
			if($options['show_plugin_url'] == 'true')
					$output .= '<span style="font-size:7pt;">قدرت گرفته از <a style="font-size:7pt;" href="http://likekhor.com/">لایک‌خور</a></span> ';
			if($options['show_author_url'] == 'true')
					$output .= '<span style="font-size:7pt;">توسط <a style="font-size:7pt;" href="http://www.moallemi.ir/">کاوشگر</a></span>';			
		} 
		else 
		{
			$output = '<li>چیزی برای نمایش وجود ندارد.</li>'."\n";
		}
		if($display) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	function likekhor_mini_show_stats($text, $display = true) 
	{
		$options = get_likekhor_options();
		$general = $options['likekhor_general'];
		
		$number_of_likes = number_format_i18n(intval($general['number_of_likes']));
		$number_of_subscribers = number_format_i18n(intval($general['number_of_subscribers']));

		$output = str_replace('[likes]', $number_of_likes, $text);
		$output = str_replace('[subscribers]', $number_of_subscribers, $output);
		
		if($display) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	
	function likekhor_get_most_viewed($limit = 10, $chars = 0, $display = true) 
	{
		$options = get_option('likekhor_options');
		$output = '';
		$most_liked = $options['likekhor_latest_posts'];
		
		$count = 0;
		if($most_liked != '') 
		{
			$output .= '<ul>';
			foreach ($most_liked as $post) 
			{
				if($count == $limit)
					break;
				if($chars > 0) 
					$post_title = likekhor_snippet_text($post['title'], $chars);
				$output .= "<li><a href='$post[link]'>".str_replace('%and%', '&', $post[title])."</a> - $post[likes] لایک</li>";
				$count++;
			}
			$output .= '</ul>';
			if($options['show_plugin_url'] == 'true' or $options['show_author_url'] == 'true')
				$output .= '<p style="border-top:1px solid #EEEEEE" />';
			if($options['show_plugin_url'] == 'true')
					$output .= '<span style="font-size:7pt;">قدرت گرفته از <a style="font-size:7pt;" href="http://likekhor.com/">لایک‌خور</a></span> ';
			if($options['show_author_url'] == 'true')
					$output .= '<span style="font-size:7pt;">توسط <a style="font-size:7pt;" href="http://www.moallemi.ir/">کاوشگر</a></span>';			
		} 
		else 
		{
			$output = '<li>چیزی برای نمایش وجود ندارد.</li>'."\n";
		}
		if($display) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	function likekhor_post_excerpt($post_excerpt, $post_content, $post_password, $chars = 200) 
	{
		if(!empty($post_password)) {
			if(!isset($_COOKIE['wp-postpass_'.COOKIEHASH]) || $_COOKIE['wp-postpass_'.COOKIEHASH] != $post_password) {
				return __('There is no excerpt because this is a protected post.', 'likekhor');
			}
		}
		if(empty($post_excerpt)) {
			return likekhor_snippet_text(strip_tags($post_content), $chars);
		} else {
			return $post_excerpt;
		}
	}
	
	function likekhor_snippet_text($text, $length = 0) 
	{
		if (defined('MB_OVERLOAD_STRING')) {
		  $text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
		 	if (mb_strlen($text) > $length) {
				return htmlentities(mb_substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
		 	} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
		 	}
		} else {
			$text = @html_entity_decode($text, ENT_QUOTES, get_option('blog_charset'));
		 	if (strlen($text) > $length) {
				return htmlentities(substr($text,0,$length), ENT_COMPAT, get_option('blog_charset')).'...';
		 	} else {
				return htmlentities($text, ENT_COMPAT, get_option('blog_charset'));
		 	}
		}
	}
					
?>
