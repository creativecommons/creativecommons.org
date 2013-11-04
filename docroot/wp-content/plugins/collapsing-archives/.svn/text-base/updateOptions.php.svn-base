<?php

  $title = strip_tags(stripslashes($new_instance['title']));
  $archSortOrder= 'DESC' ;
  if($new_instance['archSortOrder'] == 'ASC') {
    $archSortOrder= 'ASC' ;
  }
  if($new_instance['showPosts'] == 'yes') {
    $showPosts= true ;
  } else {
    $showPosts= false ;
  }
  if($new_instance['linkToArch'] == 'yes') {
    $linkToArch= true ;
  } else {
    $linkToArch= false;
  }
  if (isset($new_instance['showPostCount'])) {
    $showPostCount= true ;
  } else {
    $showPostCount= false ;
  }
  if (isset($new_instance['showArchives'])) {
    $showArchives= true ;
  } else {
    $showArchives= false ;
  }
  if (isset($new_instance['showYearCount'])) {
    $showYearCount= true ;
  } else {
    $showYearCount= false ;
  }
  if (isset($new_instance['expandCurrentYear'])) {
    $expandCurrentYear= true ;
  } else {
    $expandCurrentYear= false ;
  }
  $expand= $new_instance['expand'];
  $customExpand= $new_instance['customExpand'];
  $customCollapse= $new_instance['customCollapse'];
  $noTitle= $new_instance['noTitle'];

	$inExcludeYear= $new_instance['inExcludeYear'];
	$inExcludeCat= $new_instance['inExcludeCat'];

  if(isset($new_instance['expandYears'])) {
    $expandYears= true ;
  } else {
    $expandYears=false;
  }
  if (isset($new_instance['showMonthCount'])) {
    $showMonthCount= true ;
  } else {
    $showMonthCount=false;
  }
  if (isset($new_instance['expandMonths'])) {
    $expandMonths= true ;
  } else {
    $expandMonths=false;
  }
  if (isset($new_instance['showPostTitle'])) {
    $showPostTitle= true ;
  } else {
    $showPostTitle=false;
  }
  if( !isset($new_instance['animate'])) {
    $animate= 0 ;
  } else {
    $animate=1;
  }
  if (isset($new_instance['debug'])) {
    $debug= true ;
  } else {
    $debug= false;
  }
  if( isset($new_instance['showPostDate'])) {
    $showPostDate= true ;
  } else {
    $showPostDate=false;
  }
  $postDateFormat=addslashes($new_instance['postDateFormat']);
  $postDateAppend= 'after' ;
  if($new_instance['postDateAppend'] == 'before') {
    $postDateAppend= 'before' ;
  }
  if(isset($new_instance['expandCurrentMonth'])) {
    $expandCurrentMonth= true ;
  } else {
    $expandCurrentMonth= false ;
  }
  $inExcludeYears=addslashes($new_instance['inExcludeYears']);
  $postTitleLength=addslashes($new_instance['postTitleLength']);
  $inExcludeCats=addslashes($new_instance['inExcludeCats']);
  $defaultExpand=addslashes($new_instance['defaultExpand']);
  $instance = compact( 'title','showPostCount',
      'inExcludeCat', 'inExcludeCats', 'inExcludeYear', 'inExcludeYears',
      'archSortOrder', 'showPosts', 'showPages', 'linkToArch', 'debug',
      'showYearCount', 'expandCurrentYear','expandMonths', 'expandYears',
      'expandCurrentMonth','showMonthCount', 'showPostTitle', 'expand',
			'noTitle', 'customExpand', 'customCollapse', 'postDateAppend',
      'showPostDate', 'postDateFormat','animate','postTitleLength');
?>
