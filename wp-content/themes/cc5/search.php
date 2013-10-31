<?php get_header(); ?>

<div id="mainContent" class="box">
  <div id="contentPrimary">
        	<div class="block" id="title">
        <h2>
          Search Results
        </h2>
          </div>
          <div  class="content-box" id="page">
<?php /* Google Custom Search Engine */ ?>

<div id="cse" style="width: 100%;"><h3><img src="/wp-content/themes/cc5/images/loading.gif" alt="Loading" style="margin-bottom:-6px;" /> Loading search...</h3></div>
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


<?php /* End of Google CSE */ ?>

          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
