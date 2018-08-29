<?php
	include("topics_fetch_from_db.php");
	$topics_arr = get_topics($conn, $name_seek, $page);
?>
	<h2>Haetut aiheet</h2>
<?php
	if ($topics_arr['count'] > 0) {
?>
		<div id="topics_listing_table" class="datatable">
			<div class="row heading-row">
				<div class="col-sm-10"><h5>Nimi</h5></div>
				<div class="col-sm-1-wrap">
					<div class="col-sm-1"></div>
					<div class="col-sm-1"></div>
				</div>
			</div>
<?php
			foreach($topics_arr['topics'] as $topic) {
?>				
				<div class="row datarow">
					<div class="col-sm-10">
						<?php echo $topic['name']; ?>
					</div>			
					<div class="col-sm-1-wrap">
						<div class="col-sm-1">
<?php
							$modify_topic_params = array($topic['topic_id']);
							$modify_js_call = java_script_call("modifyTopic", 
								$modify_topic_params);
?>	
							<button class="button" onclick="<?php echo $modify_js_call; ?>">Muokkaa</button>
						</div>						
						<div class="col-sm-1">
<?php 
							$remove_topic_params = array($topic['topic_id']);
							$remove_js_call = java_script_call("removeTopic", 
								$remove_topic_params);
?>			
							<button class="button" onclick="<?php echo $remove_js_call; ?>">Poista</button>
						</div>
					</div>
				</div>
<?php
			}
?>
		</div>
<?php
		echo generate_js_page_list("get_topics_page",
			array(), 
			$topics_arr['page_count'], $page, $page_page,
			"topic_pages", "",
			"curr_page", "other_page");
	} else {
?>
		<div id="no_topic_findings">
			<b>Haulla ei löytynyt yhtään aihetta</b>
		</div>
		<div id="topics_listing_table" class="datatable"></div>
<?php
	}
?>
<!-- These are because seek fields etc. can be changes after
     last query and other user and also to make js functions
	 work easier -->
<div id="page" style="display:none"><?php echo $page; ?></div>
<div id="page_page" style="display:none"><?php echo $page_page; ?></div>
<div id="last_query_name_seek" style="display:none"><?php echo $name_seek; ?></div>
