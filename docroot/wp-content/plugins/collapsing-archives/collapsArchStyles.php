<?php
$style="#sidebar span.collapsing.archives {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
} 

#sidebar span.monthCount, span.yearCount {text-decoration:none; color:#333}
#sidebar li.collapsing.archives a.self {font-weight:bold}
#sidebar ul.collapsing.archives.list ul.collapsing.archives.list:before {content:'';} 
#sidebar ul.collapsing.archives.list li.collapsing.archives:before {content:'';} 
#sidebar ul.collapsing.archives.list li.collapsing.archives {list-style-type:none}
#sidebar ul.collapsing.archives.list li {
       margin:0 0 0 .8em;
       text-indent:-1em}
#sidebar ul.collapsing.archives.list li.collapsing.archives.item:before {content: '\\\\00BB \\\\00A0' !important;} 
#sidebar ul.collapsing.archives.list li.collapsing.archives .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
   cursor:pointer;
    padding-right:5px;}";

$default=$style;

$block="#sidebar li.collapsing.archives a {
            display:block;
            text-decoration:none;
            margin:0;
            padding:0;
            }
#sidebar li.collapsing.archives a:hover {
            background:#CCC;
            text-decoration:none;
          }
#sidebar span.collapsing.archives {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}

#sidebar li.collapsing.archives a.self {background:#CCC;
                       font-weight:bold}
#sidebar ul.collapsing.archives.list ul.collapsing.archives.list:before, 
  #sidebar ul.collapsing.archives.list li.collapsing.archives:before,
  #sidebar ul.collapsing.archives.list li.collapsing.archives.item:before {
        content:'';
  } 
#sidebar ul.collapsing.archives.list li.collapsing.archives {list-style-type:none}
#sidebar ul.collapsing.archives.list li.collapsItem {
      }
#sidebar ul.collapsing.archives.list li.collapsing.archives .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
    float:left;
    padding-right:5px;
    cursor:pointer;
}
";

$noArrows="#sidebar span.collapsing.archives {
        border:0;
        padding:0; 
        margin:0; 
        cursor:pointer;
}
#sidebar span.monthCount, span.yearCount {text-decoration:none; color:#333}
#sidebar li.collapsing.archives a.self {font-weight:bold}
#sidebar ul.collapsing.archives.list li {
       margin:0 0 0 .8em;
       text-indent:-1em}

#sidebar ul.collapsing.archives.list ul.collapsing.archives.list:before, 
  #sidebar ul.collapsing.archives.list li.collapsing.archives:before, 
  #sidebar ul.collapsing.archives.list li.collapsing.archives.item:before {
       content:'';
  } 
#sidebar ul.collapsing.archives.list li.collapsing.archives {list-style-type:none}
#sidebar ul.collapsing.archives.list li.collapsing.archives .sym {
   font-size:1.2em;
   font-family:Monaco, 'Andale Mono', 'FreeMono', 'Courier new', 'Courier', monospace;
   cursor:pointer;
    padding-right:5px;}";
$selected='default';
$custom=get_option('collapsing.archivesStyle');
?>
