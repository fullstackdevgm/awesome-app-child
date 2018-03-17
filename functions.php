<?php
function top_section() {
		global $enable_hover_state;
		$navItemsArr = ot_get_option('nav_items');
		$top_section_arr = array();
		if($navItemsArr && !(is_string($navItemsArr))) {
			foreach ($navItemsArr as $val) {
				if($val['brick_type'] ==='slogan' && $val['hide_nav_item'] === 'no') {
					$top_section_arr[] = $val;
				}
			}
		}
		?>
		<!-- START TOP MOST SECTION -->
		<?php if($top_section_arr && !(is_string($top_section_arr))) { ?>
			<?php foreach ($top_section_arr as $navItem) { // getting the top section
				$navItemObj = json_decode(json_encode(unserialize(serialize($navItem))));
				if($navItemObj->brick_type === 'slogan' && $navItemObj->hide_nav_item === 'no') :
					$bg_img = $navItemObj->top_section_banner;
					$mobileDetectObj = new Mobile_Detect;
					$fixed_style ='';
					if(!$mobileDetectObj->isiOS()) {
						$fixed_style = "background-size: cover; ";
						$logo_margin="margin-left:10%";
						$header_cnt='margin-top:10%;margin-left:10%;';
					}else{
						$logo_margin="";
						
					}
					
					?>
					<header id="home" class="headerbg_1">
							<div class="row height64">
								  <div class="span12" >
									<div class="container">
										<div class="row-fluid">
											<div class="scala-logo" style="width:165px;">
												<img src="<?php echo get_stylesheet_directory_uri().'/images/logo_grid2.jpg';?>" alt="Logo">
													 <!-- <div class="spiral-logo"><img src="/resources/img/scala-spiral-white.png"/></div> -->
													<!-- <div class="scala-text">Scala</div> -->
											</div>
											<div style="width:75%;float:right;">
												<table class="nav topnavmnu" style="width:70%"><tr>
												<?php 
												$class_arr=array('redtop','orangetop','yellowtop','greentop','graytop','browntop','bluetop');	
												$b=0;
												foreach($navItemsArr as $ech_navelm):?>
												<?php if($ech_navelm["hide_nav_item"]=='no' && $ech_navelm["brick_type"]=="nav_item" && strpos($ech_navelm['title'],'#')===false):?>
												<?php $pageObj = get_page( $ech_navelm['page_select'] ); $page_url = site_url('/#').$pageObj->post_name;?>
												<td class="<?php echo $class_arr[$b]?>" nowrap><a data-toggle="collapse" data-target=".nav-collapse" href="javascript:scrollToElement('<?php echo '#'.$pageObj->post_name;?>',2200,'')"><?php echo $ech_navelm['title'];$b++;?></a></td>
											<?php endif;endforeach;?>
											</tr></table>
											</div>
										</div>
									</div>
								 </div>
							</div>
						<div class="theme_col" style="cursor:auto !important;background-image: url('<?php echo $bg_img; ?>'); <?php echo $fixed_style; ?>">
							
							
								<div class="row">
								  <div class="span12" style="<?php echo $header_cnt;?>">
									<div class="span8 bghighlight">
										<?php echo do_shortcode($navItemObj->top_section_banner_content); ?>
									</div>
								  </div>
								</div>
								 <div class="row" style="position:relative;position:relative;width: 50%;margin-left: 12%;">
									 <div style="width:30%;float:left"><a  data-toggle="collapse" data-target=".nav-collapse" class="central-btn download windows manage-bigdata" id="main-download-button" href="javascript:scrollToElement('#manage-big-data',2200,'')"><!--<p class="for-platform">for Mac OS</p>--></a></div>
									  <div style="width:30%;float:right;"><a  data-toggle="collapse" data-target=".nav-collapse" class="page_ central-btn api sensing-activity" href="javascript:scrollToElement('#sensing-activity',2200,'')"></a></div>
									</div>
								</div>
								
								
						</div>
					</header>
					<?php break; ?>
				<?php endif; ?>
			<?php } ?>
		<?php } ?>
		<!-- END TOP MOST SECTION -->
		<?php

	}

if(plugin_is_active('wp-to-twitter')===true){
	global $instance_mod;
	$instance_mod = array( 
			'twitter_id' => false,
			'twitter_num' => 10,
			'twitter_duration' => 3600,
			'twitter_hide_replice' => 0,
			'twitter_include_rts' => 1,
			'link_links' => 1,
			'link_mentions' => 1,
			'link_hashtags' => 0,
			'intents' => 1,
			'source' => 0 );
wp_twitter_modified();
}
add_shortcode('wp-to-twitter-mod','wp_twitter_modified');

function wpt_twitter_feed_mod( $instance ) {
	$return = '<div class="wpt-header">';
		$user = wpt_get_user( $instance['twitter_id'] );
		$avatar = $user->profile_image_url_https;
		$name = $user->name;
		$verified = $user->verified;
		$img_alignment = ( is_rtl() )?'wpt-right':'wpt-left';
		$follow_alignment = ( is_rtl() )?'wpt-left':'wpt-right';
		$follow_url = esc_url( 'https://twitter.com/'.$instance['twitter_id'] );
		$follow_button = apply_filters ( 'wpt_follow_button', "<a href='$follow_url' class='twitter-follow-button $follow_alignment' data-width='30px' data-show-screen-name='false' data-size='large' data-show-count='false' data-lang='en'>Follow @$instance[twitter_id]</a>" );
		$return .= "<p>
			$follow_button
			<img src='$avatar' alt='' class='wpt-twitter-avatar $img_alignment' />
			<span class='wpt-twitter-name'>$name</span><br />
			<span class='wpt-twitter-id'><a href='$follow_url'>@$instance[twitter_id]</a></span>
			</p>";
	$return .= '</div>';
	$return .= '<ul>' . "\n";

	$options['exclude_replies'] = $instance['twitter_hide_replies'];
	$options['include_rts'] = $instance['twitter_include_rts'];
	$opts['links'] = $instance['link_links'];
	$opts['mentions'] = $instance['link_mentions'];
	$opts['hashtags'] = $instance['link_hashtags'];
	$rawtweets = WPT_getTweets($instance['twitter_num'], $instance['twitter_id'], $options);
	return $rawtweets;
}



function wp_twitter_modified(){
 global $instance_mod;
$tweets_arr=wpt_twitter_feed_mod($instance_mod);
$twt_user=get_option('wtt_twitter_username');
$i=0;
$begin='<div class="tweet_slider_pr"><ul class="slides">';
foreach($tweets_arr as $single_twt){
	if(is_array($single_twt)):
		$twt_dt=strtotime($single_twt['created_at']);
		$txt=$single_twt['text'];
		$return.='<li><div class="entry-meta">Posted Date: '.date("M d Y",$twt_dt).'</div><p style="margin:auto;width:250px;text-align:left;">'.$txt.'</p></li>';
	endif;
}
$end='</ul></div>
<div class="see-more-tweets">See more tweets, or</div>
<a id="twitter-btn" href="https://twitter.com/'.$twt_user.'">Follow on Twitter</a>
<br>
<a style="border-bottom: 0px;" href="https://twitter.com/'.$twt_user.'"><img style="padding-top: 8px;" alt="white Twitter logo" src="http://shenll.net/Peters/wp-content/uploads/2013/11/twitter-logo-white-lg.png" /></a>';

return $begin.$return.$end;
}


function plugin_is_active($plugin_var) 
{
	return in_array( $plugin_var. '/' .$plugin_var. '.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ); 

}
?>