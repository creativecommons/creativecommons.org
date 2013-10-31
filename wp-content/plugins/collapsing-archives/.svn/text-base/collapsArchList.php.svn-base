<?php
/*
Collapsing Archives version: 1.3.2

Copyright 2007-2010 Robert Felty

This file is part of Collapsing Archives

  Collapsing Archives is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  Collapsing Archives is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Collapsing Archives; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
?>
<?php
global $collapsArchItems;
$collapsArchItems = array();
function list_archives($options) {
  global $wpdb, $month, $collapsArchItems;
  extract($options);
  $post_attrs = "post_date != '0000-00-00 00:00:00' AND post_status = 'publish'";


  if ($expand==1) {
    $expandSym='+';
    $collapseSym='—';
  } elseif ($expand==2) {
    $expandSym='[+]';
    $collapseSym='[—]';
  } elseif ($expand==3) {
    $expandSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/expand.gif' alt='expand' />";
    $collapseSym="<img src='". get_settings('siteurl') .
         "/wp-content/plugins/collapsing-archives/" . 
         "img/collapse.gif' alt='collapse' />";
  } elseif ($expand==4) {
    $expandSym=htmlentities($customExpand);
    $collapseSym=htmlentities($customCollapse);
  } else {
    $expandSym='▶';
    $collapseSym='▼';
  }
  if ($expand==3) {
    $expandSymJS='expandImg';
    $collapseSymJS='collapseImg';
  } else {
    $expandSymJS=$expandSym;
    $collapseSymJS=$collapseSym;
  }
	$inExclusionsCat = array();
	if ( !empty($inExcludeCat) && !empty($inExcludeCats) ) {
		$exterms = preg_split('/[,]+/',$inExcludeCats);
    if ($inExcludeCat=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusionsCat))
					$inExclusionsCat = "'" . sanitize_title($exterm) . "'";
				else
					$inExclusionsCat .= ", '" . sanitize_title($exterm) . "' ";
			}
		}
	}
	if ( empty($inExclusionsCat) ) {
		$inExcludeCatQuery = "";
  } else {
    $inExcludeCatQuery ="AND $wpdb->terms.slug $in ($inExclusionsCat)";
  }
	$inExclusionsYear = array();
	if ( !empty($inExcludeYear) && !empty($inExcludeYears) ) {
		$exterms = preg_split('/[,]+/',$inExcludeYears);
    if ($inExcludeYear=='include') {
      $in='IN';
    } else {
      $in='NOT IN';
    }
		if ( count($exterms) ) {
			foreach ( $exterms as $exterm ) {
				if (empty($inExclusionsYear))
					$inExclusionsYear = "'" .$exterm . "'";
				else
					$inExclusionsYear .= ", '" . $exterm . "' ";
			}
		}
	}
	if ( empty($inExclusionsYear) ) {
		$inExcludeYearQuery = "";
  } else {
    $inExcludeYearQuery ="AND YEAR($wpdb->posts.post_date) $in ($inExclusionsYear)";
  }

  $isPage='';
  if (!$showPages) {
    $isPage="AND $wpdb->posts.post_type='post'";
  }
	if ($defaultExpand!='') {
		$autoExpand = preg_split('/,\s*/',$defaultExpand);
  } else {
	  $autoExpand = array();
  }
  if( !$showPages ) {
    $post_attrs .= " AND post_type = 'post'";
  }


  $postquery= "SELECT $wpdb->terms.slug, $wpdb->posts.ID,
    $wpdb->posts.post_name, $wpdb->posts.post_title, $wpdb->posts.post_author,
    $wpdb->posts.post_date, YEAR($wpdb->posts.post_date) AS 'year',
    MONTH($wpdb->posts.post_date) AS 'month' 
    FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID =
    $wpdb->term_relationships.object_id 
		LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id =
																			$wpdb->term_relationships.term_taxonomy_id
		LEFT JOIN $wpdb->terms ON $wpdb->terms.term_id = 
		                          $wpdb->term_taxonomy.term_id 
  WHERE $post_attrs  $inExcludeYearQuery $inExcludeCatQuery 
  GROUP BY $wpdb->posts.ID 
  ORDER BY $wpdb->posts.post_date $sort";

  $allPosts=$wpdb->get_results($postquery);

  if ($debug==1) {
    echo  "<pre style='display:none' >";
    printf ("MySQL server version: %s\n", mysql_get_server_info());
    echo  "\ncollapsArch options:\n";
    print_r($options);
    echo  "POST QUERY:\n $postquery\n";
    echo  "\nPOST QUERY RESULTS\n";
    print_r($allPosts);
    echo  "</pre>";
  }

  if( $allPosts ) {
    $currentYear = -1;
    $currentMonth = -1;
    $lastMonth=-1;
    $lastYear=-1;
    foreach ($allPosts as $post) {
      if ($post->year != $lastyear) {
        $lastYear=$post->year;
      }
      if ($post->month != $lastMonth) {
        $lastMonth=$post->month;
      }
      $yearCounts{"$lastYear"}++;
      $monthCounts{"$lastYear$lastMonth"}++;
    }
    $newYear = false;
    $newMonth = false;
    $closePreviousYear = false;
    $monthCount=0;
    $i=0;
    foreach( $allPosts as $archPost ) {
      $monthStyle = "style='display:none'";
      $postStyle = "style='display:none'";

      if ($currentYear != $archPost->year ) {
        $lastYear=$currentYear;
        $lastMonth=$currentMonth;
        $currentYear = $archPost->year;
        $theID = "collapsArch-$currentYear:$number";
        /* this should fix the "sparse year" problem
         * Thanks to Aishda
         */
        $currentMonth = 0;
        $newYear = true;
        if ($showYearCount) {
          $yearCount = ' <span class="yearCount">(' .
              $yearCounts{"$currentYear"} . ")</span>\n";
        }
        else {
          $yearCount = '';
        }
        $ding = $expandSym;
        $yearRel = 'expand';
        $monthRel = 'expand';
        $yearTitle= __('click to expand', 'collapsArch');
        $monthTitle= __('click to expand', 'collapsArch');
        /* rel = expand means that it will be hidden, and clicking on the
         * triangle will expand it. rel = collapse means that is will be shown, and
         * clicking on the triangle will collapse it 
         */
        if (( $expandCurrentYear
            && $archPost->year == date('Y')) || $_COOKIE[$theID]==1) {
          $ding = $collapseSym;
          $yearRel = 'collapse';
          $yearTitle= __('click to collapse', 'collapsArch');
          $monthStyle = '';
        }
        
        if($i>=1 && $allPosts[$i-1]->year != $archPost->year ) {
          if( $expandYears ) {
            if( $expandMonths ) {
              $archives .= "        </ul>\n      </li> <!-- close expanded month --> \n";
            } else {
              $archives .= "      </li> <!-- close month --> \n";
            }
            $archives .= "    </ul>\n  </li> <!-- end year -->\n";
          } else {
            if( $expandMonths ) {
              $archives .= "    </ul>\n  </li> <!-- end year -->\n";
            } else {
              $archives .= "  </li> <!-- end year -->\n";
            }
          }
        }
        $home = get_settings('home');
        if( $expandYears  || $expandMonths) {
          $archives .= "  <li class='collapsing archives'><span title='$yearTitle' " .
              "class='collapsing archives $yearRel' " .
              "onclick='expandCollapse(event" .
              ", \"$expandSymJS\", \"$collapseSymJS\", $animate," .
              "\"collapsing archives\"); return false' ><span class='sym'>$ding</span>";
        } else {
          $archives .= "  <li class='collapsing archives item'>\n";
        }
        if ($linkToArch) {
          $archives .=  "</span>";
          $archives .= "<a href='".get_year_link($archPost->year). "'>$currentYear $yearCount</a>\n";
        } else {
          $archives .= "<a href='".get_year_link($archPost->year). "'>$currentYear$yearCount</a>\n";
          $archives .= "</span>";
        }
        if( $expandYears || $expandMonths ) {
          $archives .= "    <ul $monthStyle id='$theID'>\n";
        }
        $newYear = false;
      }

      if ($currentMonth != $archPost->month) {
        $i++;
        //$lastMonth = $currentMonth;
        //$lastMonth= ($currentMonth==0) ? 1 : $currentMonth;
        if ($currentMonth==0) {
          $lastID = "collapsArch-$lastYear-$lastMonth:$number";
        } else {
          $lastID = "collapsArch-$currentYear-$currentMonth:$number";
        }
        $currentMonth = $archPost->month;
        $newMonth = true;
        if ($expandYears) 
          $theID = "collapsArch-$currentYear-$currentMonth:$number";
        if ($i>1) {
          //echo "$theID, $lastID, $currentMonth, $i<br />";
          $collapsArchItems[$lastID] = $monthText;
          //$archives.= $monthText;
          $monthText='';
        }
        if($newYear == false) { #close off last month
          $newYear=true; 
        } else {
          if ($expandYears) {
            if ($expandMonths) {
              $archives .= "        </ul>\n      </li> <!-- close expanded month --> \n";
            } else {
              $archives .= "      </li> <!-- close month --> \n";
            }
          }
        }

        if ($showMonthCount) {
          $monthCount = ' <span class="monthCount">(' .
              $monthCounts{"$currentYear$currentMonth"} . ")</span>\n";
        } else {
          $monthCount = '';
        }
        if( $expandYears ) {
          $text = sprintf('%s', $month[zeroise($currentMonth,2)]);

          $text = wptexturize($text);
          $title_text = wp_specialchars($text,1);

          if ($expandMonths ) {
            $link = 'javascript:;';
            $onclick = "onclick='expandCollapse(event" . 
                ", \"$expandSymJS\", \"$collapseSymJS\", $animate, " .
                "\"collapsing archives\"); return false'";
            $monthCollapse = 'collapsing archives';
            if(( $expandCurrentMonth && $currentYear == date('Y')
                && $currentMonth == date('n')) || $_COOKIE[$theID]==1 ) {
              $monthRel = 'collapse';
              $monthTitle= __('click to collapse', 'collapsArch');
              $postStyle = '';
              $ding = $collapseSym;
            } else {
              $monthRel = 'expand';
              $monthTitle= __('click to expand', 'collapsArch');
              $ding = $expandSym;
            }
            $the_span = "<span title='$monthTitle' " .
                "class='$monthCollapse $monthRel' $onclick>" ;
            $the_ding="<span class='sym'>$ding</span>";
            if ($linkToArch) {
              $the_link= "$the_span$the_ding</span>";
              $the_link .="<a href='".get_month_link($currentYear, $currentMonth).
                  "' title='$title_text'>";
              $the_link .="$text $monthCount</a>\n";
            } else {
              $the_link ="$the_span$the_ding<a href='".get_month_link($currentYear, $currentMonth).
                  "' >$text $monthCount</a>";
              $the_link.="</span>";
            }
          } else {
            $link = get_month_link( $currentYear, $currentMonth );
            $onclick = '';
            $monthRel = '';
            $monthTitle = '';
            $monthCollapse = 'collapsing archives';
            $the_link ="<a href='".get_month_link($currentYear, $currentMonth).
                "' title='$title_text'>";
            $the_link .="$text $monthCount</a>\n";
          }

          $archives .= "      <li class='collapsing archives'>".$the_link;

        }
        if ($expandYears && $expandMonths ) {
          $archives .= "        <ul $postStyle " . 
              "id='$theID'>\n";
          $text = '';
        }
      } else {
        if( $expandYears && $expandMonths ) {
          $text = '';
        }
      }
      $text = '';
      if( $showPostNumber ) {
        $text .= '#'.$archPost->ID;
      }

      if ($showPostTitle  && $expandMonths) {

        $title_text = htmlspecialchars(strip_tags(__($archPost->post_title)), ENT_QUOTES);
        if(strlen(trim($title_text))==0) {
          $title_text = $noTitle;
        }
        $tmp_text = '';
        if ($postTitleLength>0 && strlen($title_text)>$postTitleLength ) {
          $tmp_text = substr($title_text, 0, $postTitleLength );
          $tmp_text .= ' &hellip;';
        }

        $text .= ( $tmp_text == '' ? $title_text : $tmp_text );
        if ($showPostDate ) {
          $theDate = mysql2date($postDateFormat, $archPost->post_date );
          if ($postDateAppend=='after') { 
            $text .= ( $text == '' ? $theDate : " $theDate" );
          } else {
            $text = ( $text == '' ? $theDate : "$theDate " ) . $text;
          }
        }

        if ($showCommentCount ) {
          $commcount = ' ('.get_comments_number($archPost->ID).')';
        }

        $link = get_permalink($archPost);
        $monthText .= "          <li class='collapsing archives item'><a href='$link' " .
            "title='$title_text'>$text</a>$commcount</li>\n";
        if (($expandCurrentMonth  && $expandYears
                && $currentYear == date('Y')
                && $currentMonth == date('n')) || $_COOKIE[$theID]==1 ) {
          $archives .= $monthText;
          $monthText='';
        } elseif (($expandCurrentYear  && $expandMonths
                && $currentYear == date('Y')) || $_COOKIE[$theID]==1 ) {
          //$archives .= $monthText;
          //$monthText='';
        }
      }
    }
    if ($expandMonths) {
      $archives .= "        </ul>
      </li> <!-- close month -->\n";
      $archives .= "  </ul>";
      $collapsArchItems[$theID] = $monthText;
      $monthText='';
    }
    if (!$expandYears && $expandMonths) {
    }
  } 
  if ($expandYears && !$expandMonths) {
    $archives .= "  </li> <!-- close month --></ul><!-- close year -->\n";
    $collapsArchItems[$theID] = $theID;
  }
  if ($expandYears) {
    $archives .= "</li> <!-- end of collapsing archives -->";
  }
  return($archives);
}
?>
