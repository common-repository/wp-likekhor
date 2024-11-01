<?php
	require_once '../../../wp-load.php';
	
	$options = get_likekhor_options();
	$graphs = $options['likekhor_graphs'];
	foreach($graphs as $item)
	{
		$date[] = date('m-d', $item['date']);
		$arrlikes[] = $item['likes'];
		$arrsubscribers[] = $item['subscribers'];
		$arrlike_to_post[] = $item['like_to_post'];
		$arrlikekhor[] = $item['likekhor'];
	}
	
	$date = @implode(',', $date);
	$likes = @implode(',', $arrlikes);
	$subscribers = @implode(',', $arrsubscribers);
	$like_to_post = @implode(',', $arrlike_to_post);
	$likekhor = @implode(',', $arrlikekhor);
	
	if($_GET['type'] == 'likes_chart') {
	?>
	&title=likes%2Fday,{font-size: 12px;}&
	&x_label_style=10,#000000,0,7&
	&x_axis_steps=4&
	&y_ticks=5,10,4&
	&filled_bar=50,#ffa133,#e57c00,,-1&
	&values=<?php echo $likes;?>&
	&x_labels=<?php echo $date;?>&
	&y_min=0&
	&y_max=<?php echo max($arrlikes)+400;?>&
	&bg_colour=#FFFFFF&
	&num_decimals=0&
	<?php 
	}
	if($_GET['type'] == 'subscribers_chart') {
	?>
	&title=subscribers,{font-size: 12px;}&
	&x_label_style=10,#000000,0,7&
	&x_axis_steps=4&
	&y_ticks=5,10,4&
	&area_hollow=2,3,25,#5177E8&
	&values=<?php echo $subscribers;?>&
	&x_labels=<?php echo $date;?>&
	&y_min=<?php echo min($arrsubscribers)-50;?>&
	&y_max=<?php echo max($arrsubscribers)+50;?>&
	&bg_colour=#FFFFFF&
	&num_decimals=0&
	<?php 
	}
	if($_GET['type'] == 'likes_to_post_chart') {
	?>
	&title=like%2Fpost,{font-size: 12px;}&
	&x_label_style=10,#000000,0,7&
	&x_axis_steps=4&
	&y_ticks=5,10,4&
	&area_hollow=2,3,25,#21b4a0&
	&values=<?php echo $like_to_post;?>&
	&x_labels=<?php echo $date;?>&
	&y_min=<?php echo min($arrlike_to_post)-1;?>&
	&y_max=<?php echo max($arrlike_to_post)+1;?>&
	&bg_colour=#FFFFFF&
	&num_decimals=0&
	<?php 
	}
	if($_GET['type'] == 'likekhor_chart') {
	?>
	&title=likekhor,{font-size: 12px;}&
	&x_label_style=10,#000000,0,7&
	&x_axis_steps=4&
	&y_ticks=5,10,4&
	&area_hollow=2,3,25,#f76371&
	&values=<?php echo $likekhor;?>&
	&x_labels=<?php echo $date;?>&
	&y_min=<?php echo min($arrlikekhor)-5;?>&
	&y_max=<?php echo max($arrlikekhor)+5;?>&
	&bg_colour=#FFFFFF&
	&num_decimals=0&
	<?php 
	}
?>