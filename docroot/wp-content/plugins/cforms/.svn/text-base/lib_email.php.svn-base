<?php
class cf_mail {
	public $eolH;
	public $eol;
	public $html_show;
	public $html_show_ac;

	public $f_txt;
	public $f_html;
	public $frommail;

	public $priority = 3;
	public $char_set = 'utf-8';
	public $content_type = 'text/plain';
	public $enc = '8bit';
	public $err  = '';
	public $ver  = '';

	public $from = '';
	public $fname = 'cforms';
	public $split_to  = false;
    public $confirm_to = '';

	public $sender = '';
	public $subj  = '';

	public $body = '';
	public $body_alt  = '';

	public $host = '';
	public $msg_id = '';

	private $to = array();
	private $cc = array();
	private $bcc = array();
	private $replyto = array();

	private $up = array();
	private $msg_type = '';
	private $boundary = array();

	private $err_count = 0;

	###
	### setup
	###
    public function __construct($no, $from, $to, $replyto='',$adminEmail=false){
		$cformsSettings = get_option('cforms_settings');

        $this->ver = $cformsSettings['global']['v'];

	    $this->eolH = ($cformsSettings['global']['cforms_crlf'][h]!=1)?"\r\n":"\n";
	    $this->eol  = ($cformsSettings['global']['cforms_crlf'][b]!=1)?"\r\n":"\n";

        if( (int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority'] > 0 )
	        $this->priority = (int)$cformsSettings['form'.$no]['cforms'.$no.'_emailpriority'];

		$this->html_show    = ( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],2,1)=='1' )?true:false;
		$this->html_show_ac = ( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],3,1)=='1' )?true:false;

	    $this->f_txt  = ( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],0,1)=='1' )?true:false;
	    $this->f_html = ( substr($cformsSettings['form'.$no]['cforms'.$no.'_formdata'],1,1)=='1' )?true:false;

	    $this->to = array();
	    $this->cc = array();
	    $this->bcc = array();
	    $this->replyto = array();
	    $this->up = array();

	    ### from
	    if ( $from=='' )
	        $from = '"'.get_option('blogname').'" <wordpress@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])) . '>';
		$this->frommail = $from;

	    $fe=array();
	    $f=array();
	    if( preg_match('/([\w-\+\.]+@([\w-]+\.)+[\w-]{2,4})/',$from,$fe) )
	        $this->from = $fe[0];

	    if( preg_match('/(.*)\s+(([\w-\.]+@|<)).*/',$from,$f) )
	        $this->fname = str_replace('"','',$f[1]);
	    else
	        $this->fname = $fe[0];

	    ### reply-to
	    $te=array();
	    $t=array();
	    if( preg_match('/([\w-\+\.]+@([\w-]+\.)+[\w-]{2,4})/',$replyto,$te) ) {
	        if ( preg_match('/(.*)\s+(([\w-\+\.]+@|<)).*/',$replyto,$t) )
	            $this->add_reply($te[0] ,str_replace('"','',$t[1]) );
	        else
	            $this->add_reply($te[0]);
	    }

	    ### TAF
	    if ( substr($cformsSettings['form'.$no]['cforms'.$no.'_tellafriend'],0,1) == 1 && $cformsSettings['form'.$no]['cforms'.$no.'_tafCC']=='1' && !$adminEmail )
	        $this->add_cc($te[0],str_replace('"','',$t[1]));

	    ### bcc
	    $te=array();
	    $t=array();

	    $addresses = explode(',',stripslashes($cformsSettings['form'.$no]['cforms'.$no.'_bcc']) );
	    foreach( $addresses as $a ){
	        if( preg_match('/([\w-+\.]+@([\w-]+\.)+[\w-]{2,4})/',$a,$te) && $adminEmail) {
	            if ( preg_match('/(.*)\s+(([\w-+\.]+@|<)).*/',$a,$t) )
	                $this->add_bcc($te[0] ,str_replace('"','',$t[1]) );
	            else
		            $this->add_bcc($te[0]);
	        }
		}


	    ### to
	    $te=array();
	    $t=array();
	    $addresses = explode(',',$to);

	    foreach( $addresses as $a ){
	        if( preg_match('/([\w-+\.]+@([\w-]+\.)+[\w-]{2,4})/',$a,$te) ) {
	            if ( preg_match('/(.*)\s+(([\w-+\.]+@|<)).*/',$a,$t) )
	                $this->add_addr($te[0] ,str_replace('"','',$t[1]) );
	            else
	                $this->add_addr($te[0]);
	        }
	    }

    }

	###
	### General Functions
	###
	public function is_html($bool) {
		$this->content_type = $bool?'text/html':'text/plain';
	}
	public function is_err() {
		return ($this->err_count > 0);
	}
	public function has_inline_img() {
	    $r = false;
	    for($i = 0; $i < count($this->up); $i++) {
	      if($this->up[$i][6] == 'inline') {
	        $r = true;
	        break;
	      }
	    }
	    return $r;
	}

	###
	### Header Functions
	###
	public function add_addr($address, $name = '') {
	    $t = count($this->to);
	    $this->to[$t][0] = trim($address);
	    $this->to[$t][1] = $name;
	}
	public function add_cc($address, $name = '') {
	    $t = count($this->cc);
	    $this->cc[$t][0] = trim($address);
	    $this->cc[$t][1] = $name;
	}
	public function add_bcc($address, $name = '') {
	    $t = count($this->bcc);
	    $this->bcc[$t][0] = trim($address);
	    $this->bcc[$t][1] = $name;
	}
	public function add_reply($address, $name = '') {
	    $t = count($this->replyto);
	    $this->replyto[$t][0] = trim($address);
	    $this->replyto[$t][1] = $name;
	}
	private function addr_add($type, $addr) {
		$addr_str = $type . ': ';
	    $addr_str .= $this->addr_fmt($addr[0]);
	    if(count($addr)>1) {
	      for($i = 1; $i < count($addr); $i++)
	        $addr_str .= ', ' . $this->addr_fmt($addr[$i]);
	    }
		return $addr_str . $this->eolH;
	}
	private function addr_fmt($addr) {
		return empty($addr[1]) ? $this->fix_header($addr[0]) : $this->enc_h($this->fix_header($addr[1]), 'phrase') . " <" . $this->fix_header($addr[0]) . ">";
	}
	private function fix_header($t) {
	    $t = trim($t);
	    $t = str_replace("\r", "", $t);
	    return str_replace("\n", "", $t);
	}
	private function mail_header() {
	    $r = $this->h_line('Date', $this->get_date());
	    $r .= ($this->sender == '')?$this->h_line('Return-Path', trim($this->from)):$this->h_line('Return-Path', trim($this->sender));

	    $u_id = md5(uniqid(time()));
	    $this->boundary[1] = 'b1_' . $u_id;
	    $this->boundary[2] = 'b2_' . $u_id;

	    $from = array();
	    $from[0][0] = trim($this->from);
	    $from[0][1] = $this->fname;
	    $r .= $this->addr_add('From', $from);

		$r .= (count($this->cc) > 0) ? $this->addr_add('Cc', $this->cc):'';
        $r .= (count($this->bcc) > 0) ? $this->addr_add('Bcc', $this->bcc):'';
	    $r .= (count($this->replyto) > 0) ? $this->addr_add('Reply-to', $this->replyto):'';
	    $r .= ($this->msg_id != '') ? $this->h_line('Message-ID',$this->msg_id):sprintf("Message-ID: <%s@%s>%s", $u_id, $this->server_name(), $this->eolH);

	    $r .= $this->h_line('X-Priority', $this->priority);
	    $r .= $this->h_line('X-Mailer', 'cformsII (deliciousdays.com) [version '. $this->ver . ']');
	    $r .= ($this->confirm_to != '') ? $this->h_line('Disposition-Notification-To', '<' . trim($this->confirm_to) . '>'):'';
	    $r .= $this->h_line('MIME-Version', '1.0');

        ### get mime
	    switch($this->msg_type) {
	      case 'plain':
	        $r .= $this->h_line('Content-Transfer-Encoding', $this->enc) . sprintf("Content-Type: %s; charset=\"%s\"", $this->content_type, $this->char_set);
	        break;
	      case 'attachments':
	      case 'alt_attachments':
	        if( $this->has_inline_img() )
	          $r .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s", 'multipart/related', $this->eolH, $this->eolH, $this->boundary[1], $this->eolH);
	        else
	          $r .= $this->h_line('Content-Type', 'multipart/mixed;') . $this->t_line("\tboundary=\"" . $this->boundary[1] . '"');
	        break;
	      case 'alt':
	        $r .= $this->h_line('Content-Type', 'multipart/alternative;') . $this->t_line("\tboundary=\"" . $this->boundary[1] . '"');
	        break;
	    }
	    return $r;
	}
	private function h_line($n, $v) {
		return $n . ': ' . $v . $this->eolH;
	}

	###
	### Body Functions
	###
	private function mail_body() {
	    switch($this->msg_type) {
	      case 'plain':
	        $r = $this->enc_str($this->body, $this->enc);
	        break;
	      case 'alt':
	        $r  = $this->begin_b($this->boundary[1], '', 'text/plain', '');
	        $r .= $this->enc_str($this->body_alt, $this->enc) . $this->eol.$this->eol;
	        $r .= $this->begin_b($this->boundary[1], '', 'text/html', '');
	        $r .= $this->enc_str($this->body, $this->enc) . $this->eol.$this->eol;
	        $r .= $this->end_b($this->boundary[1]);
	        break;
	      case 'attachments':
	        $r  = $this->begin_b($this->boundary[1], '', '', '');
	        $r .= $this->enc_str($this->body, $this->enc) . $this->eol;
	        $r .= $this->attach_files();
	        break;
	      case 'alt_attachments':
	        $r  = sprintf("--%s%s", $this->boundary[1], $this->eol);
	        $r .= sprintf("Content-Type: %s;%s" . "\tboundary=\"%s\"%s", 'multipart/alternative', $this->eol, $this->boundary[2], $this->eol.$this->eol);
	        $r .= $this->begin_b($this->boundary[2], '', 'text/plain', '') . $this->eol;
	        $r .= $this->enc_str($this->body_alt, $this->enc) . $this->eol.$this->eol;
	        $r .= $this->begin_b($this->boundary[2], '', 'text/html', '') . $this->eol;
	        $r .= $this->enc_str($this->body, $this->enc) . $this->eol.$this->eol;
	        $r .= $this->end_b($this->boundary[2]);
	        $r .= $this->attach_files();
	        break;
	    }
	    return $this->is_err() ? '' : $r;
	}
	private function begin_b($boundary, $char_set, $content_type, $encoding) {
	    if($char_set == '')
	      $char_set = $this->char_set;
	    if($content_type == '')
	      $content_type = $this->content_type;
	    if($encoding == '')
	      $encoding = $this->enc;

	    $r  = $this->t_line('--' . $boundary);
	    $r .= sprintf("Content-Type: %s; charset = \"%s\"", $content_type, $char_set) . $this->eol;
	    return $r . $this->h_line('Content-Transfer-Encoding', $encoding) . $this->eol;
	}
	private function end_b($b) {
		return $this->eol . '--' . $b . '--' . $this->eol;
	}
	private function t_line($v) {
		return $v . $this->eol;
	}
	private function wrap_t($message, $length, $qp_mode = false) {

	    $message = $this->fix_eol($message);
	    if (substr($message, -1) == $this->eol)
	      $message = substr($message, 0, -1);

    	$is_qp_crlf = ($qp_mode) ? sprintf(" =%s", $this->eol) : $this->eol;
	    $is_utf8 = (strtolower($this->char_set) == "utf-8");

	    $line = explode($this->eol, $message);
	    $message = '';
	    for ($i=0 ;$i < count($line); $i++) {
	      $tmp_line = explode(' ', $line[$i]);
	      $buf = '';
	      for ($e = 0; $e<count($tmp_line); $e++) {
	        $word = $tmp_line[$e];
	        if ($qp_mode and (strlen($word) > $length)) {
	          $spc_n = $length - strlen($buf) - 1;
	          if ($e != 0) {

	            if ($spc_n > 20) {
	              $len = $spc_n;
	              if ($is_utf8) 	$len = $this->utf8_char_b($word, $len);
	              elseif (substr($word, $len - 1, 1) == "=")	$len--;
	              elseif (substr($word, $len - 2, 1) == "=")	$len -= 2;

	              $part = substr($word, 0, $len);
	              $word = substr($word, $len);
	              $buf .= ' ' . $part;
	              $message .= $buf . sprintf("=%s", $this->eol);

	            } else
	              $message .= $buf . $is_qp_crlf;

	            $buf = '';
	          }
	          while (strlen($word) > 0) {
	            $len = $length;
	            if ($is_utf8)
	              $len = $this->utf8_char_b($word, $len);
	            elseif (substr($word, $len - 1, 1) == "=")
	              $len--;
	            elseif (substr($word, $len - 2, 1) == "=")
	              $len -= 2;

	            $part = substr($word, 0, $len);
	            $word = substr($word, $len);

	            if (strlen($word) > 0)
	              $message .= $part . sprintf("=%s", $this->eol);
	            else
	              $buf = $part;
	          }
	        } else {
	          $buf_o = $buf;
	          $buf .= ($e == 0) ? $word : (' ' . $word);

	          if (strlen($buf) > $length and $buf_o != '') {
	            $message .= $buf_o . $is_qp_crlf;
	            $buf = $word;
	          }
	        }
	      }
	      $message .= $buf . $this->eol;
	    }
	    return $message;
	}
	private function utf8_char_b($t, $max_len) {
	    $foundSplitPos = false;
	    $chk_back = 3;

	    while (!$foundSplitPos) {
	      $lastChunk = substr($t, $max_len - $chk_back, $chk_back);
	      $enc_tCharPos = strpos($lastChunk, "=");
	      if ($enc_tCharPos !== false) {
	        $hex = substr($t, $max_len - $chk_back + $enc_tCharPos + 1, 2);
	        $dec = hexdec($hex);
	        if ($dec < 128) {
	          $max_len = ($enc_tCharPos == 0) ? $max_len :
	          $max_len - ($chk_back - $enc_tCharPos);
	          $foundSplitPos = true;
	        } elseif ($dec >= 192) {
	          $max_len = $max_len - ($chk_back - $enc_tCharPos);
	          $foundSplitPos = true;
	        } elseif ($dec < 192)
	          $chk_back += 3;
	      } else
	        $foundSplitPos = true;

	    }
	    return $max_len;
	}

	###
	### Send Functions
	###
	public function send() {
	    $this->err_count = 0;

	    if((count($this->to) + count($this->cc) + count($this->bcc)) < 1) {
	      $this->set_err( __('You must provide at least one recipient email address.','cforms') );
	      return false;
	    }

	    if(!empty($this->body_alt))
	      $this->content_type = 'multipart/alternative';

	    if(count($this->up) < 1 && strlen($this->body_alt) < 1)
	      $this->msg_type = 'plain';
	    else {
	      if(count($this->up) > 0)
	        $this->msg_type = 'attachments';
	      if(strlen($this->body_alt) > 0 && count($this->up) < 1)
	        $this->msg_type = 'alt';
	      if(strlen($this->body_alt) > 0 && count($this->up) > 0)
	        $this->msg_type = 'alt_attachments';
	    }

	    $header  = $this->mail_header();
	    $body    = $this->mail_body();

        ### bail out
	    if ($body == '') return false;

	    $to = '';
	    for($i = 0; $i < count($this->to); $i++)
          $to .= (($i != 0) ? ', ':'' ) . $this->addr_fmt($this->to[$i]);

	    $to_all = split(',', $to);
	    $params = sprintf("-oi -f %s", $this->sender);

	    if ($this->sender != '' && strlen(ini_get('safe_mode'))< 1) {
	      $old_from = ini_get('sendmail_from');
	      ini_set('sendmail_from', $this->sender);
	      if ($this->split_to === true && count($to_all) > 1) {
	        foreach ($to_all as $key => $val)
	          $rt = @mail($val, $this->enc_h($this->fix_header($this->subj)), $body, $header, $params);
	      } else
	        $rt = @mail($to, $this->enc_h($this->fix_header($this->subj)), $body, $header, $params);
	    } else {
	      if ($this->split_to === true && count($to_all) > 1) {
	        foreach ($to_all as $key => $val)
	          $rt = @mail($val, $this->enc_h($this->fix_header($this->subj)), $body, $header, $params);
	      } else
	        $rt = @mail($to, $this->enc_h($this->fix_header($this->subj)), $body, $header);
	    }

	    if (isset($old_from))
	      ini_set('sendmail_from', $old_from);

	    if(!$rt) {
	      $this->set_err(__('Could not instantiate mail function.','cforms'));
	      return false;
	    }

	    return true;
	}

	public function add_file($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream') {
	    if(!@is_file($path)) {
	      $this->set_err(__('Could not access file: ','cforms'));
	      return false;
	    }

	    $filename = basename($path);
		$name = ($name == '') ? $filename : $name;

	    $t = count($this->up);
	    $this->up[$t][0] = $path;
	    $this->up[$t][1] = $filename;
	    $this->up[$t][2] = $name;
	    $this->up[$t][3] = $encoding;
	    $this->up[$t][4] = $type;
	    $this->up[$t][6] = 'attachment';
	    $this->up[$t][7] = 0;
	    return true;
	}

	private function attach_files() {
	    $mime_arr = array();
	    for($i = 0; $i < count($this->up); $i++) {

			$path		 = $this->up[$i][0];
	        $filename    = $this->up[$i][1];
	        $name        = $this->up[$i][2];
	        $encoding    = $this->up[$i][3];
	        $type        = $this->up[$i][4];
	        $dispo 		 = $this->up[$i][6];
	        $cid         = $this->up[$i][7];

	        $mime_arr[] = sprintf("--%s%s", $this->boundary[1], $this->eol);
	        $mime_arr[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $this->enc_h($this->fix_header($name)), $this->eol);
	        $mime_arr[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->eol);

	        if($dispo == 'inline')
		        $mime_arr[] = sprintf("Content-ID: <%s>%s", $cid, $this->eol);

	        $mime_arr[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", $dispo, $this->enc_h($this->fix_header($name)), $this->eol.$this->eol);

	        if($bString) {

		        $mime_arr[] = $this->enc_str($string, $encoding);
		    	if($this->is_err()) return '';
		        $mime_arr[] = $this->eol.$this->eol;

	        } else {

	        	$mime_arr[] = $this->enc_file($path, $encoding);
	        	if($this->is_err()) return '';
		        $mime_arr[] = $this->eol.$this->eol;

	        }
        }
	    $mime_arr[] = sprintf("--%s--%s", $this->boundary[1], $this->eol);
	    return join('', $mime_arr);
	}

	private function enc_file ($path, $encoding = 'base64') {
	    if(!@$fd = fopen($path, 'rb')) {
	      $this->set_err(__('File Error: Could not open file: ','cforms'));
	      return '';
	    }
	    if (function_exists('get_magic_quotes')) {
	        function get_magic_quotes() {
	            return false;
	        }
	    }
	    if (PHP_VERSION < 6) {
	      $magic_q = get_magic_quotes_runtime();
	      set_magic_quotes_runtime(0);
	    }
	    $buffer  = file_get_contents($path);
	    $buffer  = $this->enc_str($buffer, $encoding);
	    fclose($fd);

	    if (PHP_VERSION < 6) { set_magic_quotes_runtime($magic_q); }
	    return $buffer;
	}

	private function enc_str ($str, $e = 'base64') {
	    $enc_t = '';
	    switch(strtolower($e)) {
	      case 'base64':			$enc_t = chunk_split(base64_encode($str), 76, $this->eol); break;
	      case '7bit':
	      case '8bit':				$enc_t = $this->fix_eol($str) . ((substr($enc_t, -(strlen($this->eol))) != $this->eol) ? $this->eol : ''); break;
	      case 'binary':			$enc_t = $str; break;
	      case 'quoted-printable':	$enc_t = $this->enc_qp($str); break;
	      default:					$this->set_err(__('Unknown encoding: ','cforms')); break;
	    }
	    return $enc_t;
	}

	private function enc_h ($str, $position = 'text') {
	    $x = 0;

	    switch (strtolower($position)) {
	      case 'phrase':
	        if (!preg_match('/[\200-\377]/', $str)) {

	          $enc_t = addcslashes($str, "\0..\37\177\\\"");
              return (($str == $enc_t) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str)) ? $enc_t :"\"$enc_t\"";

	        }
	        $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
	        break;
	      case 'comment':
	        $x = preg_match_all('/[()"]/', $str, $matches);

	      case 'text':
	      default:
	        $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
	        break;
	    }

	    if ($x == 0)
	      return ($str);

	    $maxlen = 75 - 7 - strlen($this->char_set);

	    if (strlen($str)/3 < $x) {
	      $encoding = 'B';
	      if (function_exists('mb_strlen') && (strlen($str) > mb_strlen($str, $this->char_set)) )
	        $enc_t = $this->enc_special($str);
	      else {
	        $enc_t = base64_encode($str);
	        $maxlen -= $maxlen % 4;
	        $enc_t = trim(chunk_split($enc_t, $maxlen, "\n"));
	      }
	    } else {
	      $encoding = 'Q';
	      $enc_t = $this->enc_q($str, $position);
	      $enc_t = $this->wrap_t($enc_t, $maxlen, true);
	      $enc_t = str_replace('='.$this->eol, "\n", trim($enc_t));
	    }

	    $enc_t = preg_replace('/^(.*)$/m', " =?".$this->char_set."?$encoding?\\1?=", $enc_t);
	    return trim(str_replace("\n", $this->eol, $enc_t));
	}

	private function enc_special($str) {
	    $start = "=?".$this->char_set."?B?";
	    $end = "?=";
	    $enc_t = "";

	    $mb_length = mb_strlen($str, $this->char_set);
	    $length = 75 - strlen($start) - strlen($end);
	    $ratio = $mb_length / strlen($str);
	    $offset = $avgLength = floor($length * $ratio * .75);

	    for ($i = 0; $i < $mb_length; $i += $offset) {
	      $chk_back = 0;

	      do {
	        $offset = $avgLength - $chk_back;
	        $rest = mb_substr($str, $i, $offset, $this->char_set);
	        $rest = base64_encode($rest);
	        $chk_back++;
	      }
	      while (strlen($rest) > $length);
	      $enc_t .= $rest . $this->eol;
	    }

	    return substr($enc_t, 0, -strlen($this->eol));
	}

	private function enc_qp( $input = '', $line_max = 76, $space_conv = false ) {
	    $hex = array('0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F');
	    $lines = preg_split('/(?:\r\n|\r|\n)/', $input);
	    $escape = '=';
	    $output = '';
	    while( list(, $line) = each($lines) ) {
	      $l_len = strlen($line);
	      $newline = '';
	      for($i = 0; $i < $l_len; $i++) {
	        $c = substr( $line, $i, 1 );
	        $dec = ord( $c );
	        if ( ( $i == 0 ) && ( $dec == 46 ) ) $c = '=2E';
	        if ( $dec == 32 ) {

              if ( $i == ( $l_len - 1 ) ) $c = '=20';
              else if ( $space_conv ) $c = '=20';

	        } elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
	          $h2 = floor($dec/16);
	          $h1 = floor($dec%16);
	          $c = $escape.$hex[$h2].$hex[$h1];
	        }
	        if ( (strlen($newline) + strlen($c)) >= $line_max ) {
	          $output .= $newline.$escape.$this->eol;
	          $newline = '';
	          if ( $dec == 46 ) $c = '=2E';
	        }
	        $newline .= $c;
	      }
	      $output .= $newline.$this->eol;
	    }
	    return trim($output);
	}
	private function enc_q ($str, $position = 'text') {
		$enc_t = preg_replace("[\r\n]", '', $str);
	    switch (strtolower($position)) {
	      case 'phrase':	$enc_t = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $enc_t); break;
	      case 'comment':	$enc_t = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $enc_t);
	      case 'text':
	      default:			$enc_t = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e', "'='.sprintf('%02X', ord('\\1'))", $enc_t); break;
	    }
	    return str_replace(' ', '_', $enc_t);
	}
	private function set_err($m) {
	    $this->err = $m;
	    $this->err_count++;
	}
	private static function get_date() {
	    $d = date('Z');
	    $ds = ($d < 0) ? '-' : '+';
	    $d = abs($d);
	    $d = (int)($d/3600)*100 + ($d%3600)/60;
	    $r = sprintf("%s %s%04d", date('D, j M Y H:i:s'), $ds, $d);
	    return $r;
	}
	private function server_name() {
        if (!empty($this->host))				return $this->host;
		elseif (isset($_SERVER['SERVER_NAME']))	return $_SERVER['SERVER_NAME'];
		else									return "localhost.localdomain";
    }
    private function fix_eol($s) {
	    $s = str_replace("\r\n", "\n", $s);
	    $s = str_replace("\r", "\n", $s);
	    $s = str_replace("\n", $this->eol, $s);
	    return $s;
	}
}
?>