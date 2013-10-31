<?php get_header(); ?>

		<div id="title" class="container_16">
			<h1 class="grid_16">
				Search Results	
			</h1>
		</div>

		<div id="content">
			<div class="container_16">
				<div class="grid_12">
					<div id="cse" style="padding-bottom: 25px; width: 100%;"><h3><img src="/wp-content/themes/cc5/images/loading.gif" alt="Loading" style="margin-bottom:-6px;" /> Loading search...</h3></div>
					<script src="http://www.google.com/jsapi" type="text/javascript"></script>
					<script type="text/javascript">
						google.load('search', '1', {language : 'en'});
						google.setOnLoadCallback(function() {
								var customSearchControl = new google.search.CustomSearchControl('010316592082702087679:vmxqegqb1uy');
								customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
								customSearchControl.draw('cse');
								customSearchControl.execute('<?php echo htmlspecialchars($_GET['s']); ?>');
						}, true);
					</script>
					<link rel="stylesheet" href="/wp-content/themes/cc5/google-cse.css" type="text/css" />
				</div>
			</div>
		</div>

	</div>
<?php get_footer(); ?>

