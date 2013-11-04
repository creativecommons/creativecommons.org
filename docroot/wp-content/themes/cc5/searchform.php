<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div>
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="30" />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>
