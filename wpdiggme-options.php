<div class="wrap" style="max-width:950px !important;">
	<h2>WP DiggMe</h2>
	
	<div id="poststuff" style="margin-top:10px;">
		<div id="mainblock" style="width:710px;">
			<div class="dbx-content">
				<form action="<?php echo $action_url; ?>" method="post">
					<input type="hidden" name="submitted" value="1"  />
					<?php wp_nonce_field('wpdiggme-nonce'); ?>
					<p>You can choose what pages you want to show in the archives. </p>
					<input type="checkbox" name="posts" <?php echo $posts; ?>  />
					<label for="posts">Show Posts</label><br  />
					<input type="checkbox" name="pages" <?php echo $pages; ?>  />
					<label for="pages">Show Pages</label><br />
					<p>Choose the button tipe</p>
					<select name="button_class">
						<option <?php if($button_class=='DiggWide') echo 'selected="selected"'; ?> >DiggWide</option>
						<option <?php if($button_class=='DiggMedium') echo 'selected="selected"'; ?> >DiggMedium</option>
						<option <?php if($button_class=='DiggCompact') echo 'selected="selected"'; ?> >DiggCompact</option>
					</select>
					<div class="submit">
						<input type="submit" name="Submit" value="update"  />
					</div>
				</form>
				<div id="plugin-developer" style="border:1px solid #CCCCCC; padding:10px; width:100%; max-width:950px;">
					<strong>WP DiggMe v.0.1</strong><br  />
					Developed by <a href="http://dimasaryo.com">Dimas Aryo</a><br  />
					email : javadise@gmail.com<br  />
					skype : mrdimasaryo<br  />
				</div>
			</div>
		</div>
	</div>
</div>