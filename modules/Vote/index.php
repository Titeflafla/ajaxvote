<?php
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
defined('INDEX_CHECK') or die ('<div style="text-align: center;">You cannot open this page directly</div>');

translate('modules/Vote/lang/' . $language . '.lang.php');

$visiteur = ($user) ? $user[1] : 0;

function vote_index($module, $vid) {

    	global $user, $nuked, $visiteur, $user_ip;

    	$level_access = nivo_mod('Vote');

        $theme_stars = '1';
        $nb_star     = '10';

    	echo '<script type="text/javascript" src="modules/Admin/scripts/jquery-1.6.1.min.js"></script>'
    	. '<script type="text/javascript" language="javascript" src="modules/Vote/vote.js"></script>';

    	$sql = mysql_query("SELECT vote FROM " . VOTE_TABLE . " WHERE vid = '" . $vid . "' AND module = '" . mysql_real_escape_string(stripslashes($module)) . "'");
        $count = mysql_num_rows($sql);
        if ($count > 0) {
                $total = '0';
                while(list($vote) = mysql_fetch_array($sql)) {
                        $total = $total + $vote / $count;
                }

                $note = ceil($total);
        } else $note = "0";

      	?>
        <script type="text/javascript">
        //<![CDATA[
        function submitRating_<?php echo $vid; ?>(evt) {
	        //alert(l'ajax en jquery mais putain ca sux quoi !!!!);
	        var xhr = getXhr();
     		xhr.onreadystatechange = function(){
          		if(xhr.readyState == 4) {
		        	if(xhr.status == 200) {
		            		leselect = xhr.responseText;
			      		document.getElementById('resultat_vote_<?php echo $vid; ?>').innerHTML = leselect;
			      		init_rating(<?php echo $vid; ?>,<?php echo $theme_stars; ?>,<?php echo $nb_star; ?>);
		        	} else {
			      		document.getElementById('resultat_vote_<?php echo $vid; ?>').innerHTML = "Erreur !";
		        	}
	   		} else {
	       			document.getElementById('resultat_vote_<?php echo $vid; ?>').innerHTML = "Loading ...";
	   		}
     		}
     		document.getElementById('vote_<?php echo $vid; ?>').style.display = 'none';
     		var tmp = evt.target.getAttribute('id').substr(5);
	        var widgetId = <?php echo $vid; ?>;
	        var starNbr = tmp.substr(tmp.indexOf('_')+1);
	        var module = '<?php echo addslashes($module); ?>';
     		xhr.open("POST","index.php?file=Vote&op=a_voter&nuked_nude=index",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
     		xhr.send("ratingID="+widgetId+"&value="+starNbr+"&moduleId="+module+"");
        }
        function prototypeInit_<?php echo $vid; ?>() {
        	init_rating(<?php echo $vid; ?>,<?php echo $theme_stars; ?>,<?php echo $nb_star; ?>);
        	$('.rating_<?php echo $vid; ?>').bind('click', submitRating_<?php echo $vid; ?>);
        }
        $(document).ready( prototypeInit_<?php echo $vid; ?> );
        //]]>
        </script>
        <?php
        echo '<table style="width:450px;" id="vote_'. $vid .'" cellpadding="0" cellspacing="0"><tr>'
        . '<td><div class="rating_'. $vid .'" id="rating_'. $vid .'">'. $note .'</div></td>'
        . '<td style="width:10%;"><div style="border:1px solid #548732;color:#004000;text-align:center;padding:2px;background:#CEE7BE;">'. $note .'/'. $nb_star .'</div></td>'
        . '<td style="padding-left:10px;">Nb de vote : '. $count .'</td>'
        . '</tr></table>'
        . '<div id="resultat_vote_'. $vid .'"></div>';
}

function a_voter() {

	global $user, $user_ip, $visiteur;

	$valeur_v = $_POST['value']+1;
	$vid      = $_POST['ratingID'];
	$module   = $_POST['moduleId'];

        $level_access = nivo_mod("Vote");

        $sql = mysql_query("SELECT ip FROM ". VOTE_TABLE ." WHERE vid = '". $vid ."' AND module = '". mysql_real_escape_string(stripslashes($module)) ."'  AND ip = '" . $user_ip . "'");
	list($user_ip_bdd) = mysql_fetch_array($sql);

	if($valeur_v != "" && $vid != "" && $module != "" && is_numeric($valeur_v)) {
	        if ($visiteur >= $level_access && $level_access > -1) {
	                if($user_ip != $user_ip_bdd)  {
	                        $sql = mysql_query("INSERT INTO " . VOTE_TABLE . " ( `id` , `module` , `vid` , `ip` , `vote` ) VALUES ( '' , '" . $module . "' , '" . $vid . "' , '" . $user_ip . "' , '" . $valeur_v . "' )");

	                        $theme_stars = '1';
        			$nb_star     = '10';
	                        $sql = mysql_query("SELECT vote FROM " . VOTE_TABLE . " WHERE vid = '" . $vid . "' AND module = '" . mysql_real_escape_string(stripslashes($module)) . "'");
			        $count = mysql_num_rows($sql);
			        if ($count > 0) {
			                $total = '0';
			                while(list($vote) = mysql_fetch_array($sql)) {
			                        $total = $total + $vote / $count;
			                }

			                $note = ceil($total);
			        } else $note = "0";
	                        echo '<table style="width:450px;" cellpadding="0" cellspacing="0"><tr>'
        			. '<td><div class="rating_'. $vid .'" id="rating_'. $vid .'">'. $note .'</div></td>'
        			. '<td style="width:10%;"><div style="border:1px solid #548732;color:#004000;text-align:center;padding:2px;background:#CEE7BE;">'. $note .'/'. $nb_star .'</div></td>'
        			. '<td style="padding-left:10px;">Nb de vote : '. $count .'</td>'
        			. '</tr></table><br />'
        			. '<img style="vertical-align:middle;" src="modules/Vote/images/rating_ok.png" alt="" />&nbsp;<span style="color:#034003;">'. _VOTEADD .'</span>';
	                } else echo '<img style="vertical-align:middle;" src="modules/Vote/images/rating_error.png" alt="" />&nbsp;<span style="color:#BD1212;">'. _ALREADYVOTE .'</span>';
	        } else if ($level_access == -1) echo '<span style="color:#BD1212;"><img style="vertical-align:middle;" src="modules/Vote/images/rating_error.png" alt="" />&nbsp;'. _MODULEOFF_VOTE .'</span>';
	        else if ($level_access == 1 && $visiteur == 0) echo '<span style="color:#BD1212;"><img style="vertical-align:middle;" src="modules/Vote/images/rating_error.png" alt="" />&nbsp;'. _USERENTRANCE_VOTE .'</span>';
	        else echo '<span style="color:#BD1212;"><img style="vertical-align:middle;" src="modules/Vote/images/rating_error.png" alt="" />&nbsp;'. _NOENTRANCE_VOTE .'</span>';
	} else echo 'Erreur !';
}

switch ($_REQUEST['op']) {
    	case 'vote_index':
        vote_index($_REQUEST['module'], $_REQUEST['vid']);
        break;

    	case 'a_voter':
        a_voter();
        break;

    	default:
        break;
}

?>
