<?php
//error_reporting(7);
class PageNav {
        var $limit;
        var $execute, $query;
        var $total_result = 0;
        var $offset = "offset";
		var $objClass = "link_box";
		var $return = "";
		var $show_pages_number ;
		var $number_type ;
		var $_get_vars ;
		var $DB;

        function execute($query) {
				if(isset($_GET[$this->offset]))$GLOBALS[$this->offset] = $_GET[$this->offset] ;
                $GLOBALS[$this->offset] = (!isset($GLOBALS[$this->offset]) OR $GLOBALS[$this->offset] < 0) ? 0 : $GLOBALS[$this->offset]; 
                // $this->sql_result = $DB->query($query);
                $GLOBALS[$this->offset] = ($GLOBALS[$this->offset] > $this->total_result) ? $this->total_result-10 : $GLOBALS[$this->offset];

                if (empty($this->limit)) {
                        $this->limit = 20;
                } 

                if (isset($this->limit)) {
                        $query .= " LIMIT " . $GLOBALS[$this->offset] . ", $this->limit";
                        $this->sql_result = $this->DB->query($query);
                        $this->num_pages = ceil($this->total_result / $this->limit);
                } 
                if ($GLOBALS[$this->offset] + 1 > $this->total_result) {
                        $GLOBALS[$this->offset] = $this->total_result-1;
                } 
        } 

        function show_num_pages($frew = "&laquo;", $rew = '<-', $ffwd = '&raquo;', $fwd = '->', $separator = '') {
                $current_pg = $GLOBALS[$this->offset] / $this->limit + 1;
                if ($current_pg > '5') {
                        $fgp = ($current_pg-5 > 0) ? $current_pg-5 : 1;
                        $egp = $current_pg + 4;
                        if ($egp > $this->num_pages) {
                                $egp = $this->num_pages;
                                $fgp = ($this->num_pages-9 > 0) ? $this->num_pages-9 : 1;
                        } 
                } else {
                        $fgp = 1;
                        $egp = ($this->num_pages >= 10) ? 10 : $this->num_pages;
                } 
                if ($this->num_pages > 1) {
                        // searching for http_get_vars
                        foreach ($GLOBALS["HTTP_GET_VARS"] as $_get_name => $_get_value) {
                                if ($_get_name != $this->offset) {
                                        $this->_get_vars .= "&$_get_name=$_get_value";
                                } 
                        } 
                        $this->listNext = $GLOBALS[$this->offset] + $this->limit;
                        $this->listPrev = $GLOBALS[$this->offset] - $this->limit;
                        $this->theClass = $this->objClass;
                        if (!empty($rew)) { // $separator [$frew] $rew
                                $this->return .= ($GLOBALS[$this->offset] > 0) ? "
				
				
				<a href=\"?$this->offset=$this->listPrev$this->_get_vars\"  class =\"$this->theClass\">上一页</a> $separator
				" : "";
                        } 
                        // showing pages
                        if ($this->show_pages_number || !isset($this->show_pages_number)) {
                                for($this->a = $fgp; $this->a <= $egp; $this->a++) {
                                        $this->theNext = ($this->a-1) * $this->limit;
                                        if ($this->theNext != $GLOBALS[$this->offset]) {
                                                $this->return .= " <a href=\"?$this->offset=$this->theNext$this->_get_vars\" class =\"$this->theClass\"> ";
                                                if ($this->number_type == 'alpha') {
                                                        $this->return .= chr(64 + ($this->a));
                                                } else {
                                                        $this->return .= $this->a;
                                                } 
                                                $this->return .= "</a> ";
                                        } else {
                                                if ($this->number_type == 'alpha') {
                                                        $this->return .= chr(64 + ($this->a));
                                                } else {
                                                        $this->return .= "<b>$this->a</b>";
                                                } 
                                                $this->return .= ($this->a < $this->num_pages) ? " $separator " : "";
                                        } 
                                } 
                                $this->theNext = $GLOBALS[$this->offset] + $this->limit;
                                if (!empty($fwd)) {
                                        $offset_end = ($this->num_pages-1) * $this->limit; //$separator $fwd [$ffwd]
                                        $this->return .= ($GLOBALS[$this->offset] + $this->limit < $this->total_result) ? "
		
		$separator <a href=\"?$this->offset=$this->listNext$this->_get_vars\"  class =\"$this->theClass\">下一页</a>
		
		" : "";
                                } 
                        } 
                } 
                return $this->return;
        } 
        // [Function : Showing the Information for the Offset]
        function show_info() {
                $this->return .= "共: " . $this->total_result . " , ";
                $list_from = ($GLOBALS[$this->offset] + 1 > $this->total_result) ? $this->total_result : $GLOBALS[$this->offset] + 1;
                $list_to = ($GLOBALS[$this->offset] + $this->limit >= $this->total_result) ? $this->total_result : $GLOBALS[$this->offset] + $this->limit; 
                // $this->return .= 'Showing Results from ' . $list_from . ' - ' . $list_to . '<br>';
                $this->return .= "显示: " . $list_from . " - " . $list_to;
                return $this->return;
        } 

        function pagenav() {
                $this->return = "
                           <table width=\"100%\" border=\"0\" cellspacing=\"0\">
                             <tr>
                               <td style=\"padding:5px;\">".$this->show_info()."</td>
                               <td align=\"right\">".$this->show_num_pages()."</td>
                             </tr>
                           </table>";

                return $this->return;
        } 
} 

?>