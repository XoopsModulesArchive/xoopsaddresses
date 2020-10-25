<?php

require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$myts = MyTextSanitizer::getInstance();
$mytree = new XoopsTree($xoopsDB->prefix('addresses_cat'), 'cid', 'pid');

foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

foreach ($_GET as $k => $v) {
    ${$k} = $v;
}

if (empty($lid)) {
    redirect_header('index.php');
}

function PrintPage($lid)
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree, $xoopsModuleConfig, $xoopsModule;

    $result = $xoopsDB->query(
        'select l.lid, l.cid, l.title, l.adress, l.zip, l.city, l.country, l.phone, l.mobile, l.fax, l.contemail, l.url, l.opentime, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description from ' . $xoopsDB->prefix('addresses_links') . ' l, ' . $xoopsDB->prefix(
            'addresses_text'
        ) . " t where l.lid=$lid and l.lid=t.lid and status>0"
    );

    [$lid, $cid, $ltitle, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $url, $opentime, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description] = $xoopsDB->fetchRow($result);

    $ltitle = htmlspecialchars($ltitle, ENT_QUOTES | ENT_HTML5);

    $adress = htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5);

    $zip = htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5);

    $city = htmlspecialchars($city, ENT_QUOTES | ENT_HTML5);

    $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

    $phone = htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5);

    $mobile = htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5);

    $fax = htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5);

    $contemail = htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5);

    $opentime = $myts->displayTarea($opentime);

    $description = $myts->displayTarea($description);

    $datetime = formatTimestamp($time);

    echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>\n";

    echo "<html>\n<head>\n";

    echo '<title>' . $xoopsConfig['sitename'] . "</title>\n";

    echo "<meta http-equiv='Content-Type' content='text/html; charset=" . _CHARSET . "'>\n";

    echo "<meta name='AUTHOR' content='" . $xoopsConfig['sitename'] . "'>\n";

    echo "<meta name='COPYRIGHT' content='Copyright (c) 2001 by " . $xoopsConfig['sitename'] . "'>\n";

    echo "<meta name='DESCRIPTION' content='" . $xoopsConfig['slogan'] . "'>\n";

    echo "<meta name='GENERATOR' content='" . XOOPS_VERSION . "'>\n\n\n";

    echo "<body bgcolor='#ffffff' text='#000000' onload='window.print()'>
		<table border='0' width='650' cellpadding='0' cellspacing='1' bgcolor='#000000'>
			<tr><td>
          		<table border='0' width='650' cellpadding='20' cellspacing='1' bgcolor='#ffffff'>
					<tr><td align='center'>
          				<img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/logo.gif' border='0' alt=''>
							<br>
          						<h2>" . $ltitle . '</h2><hr>';

    echo "<table border=0 cellpadding=2 cellspacing=1 align=center width=100% class = 'outer'>
    	    <tr>
				<td class ='head' width = 30%><b>" . _MD_SITETITLE . "</b></td> <td class ='even' >$ltitle</td></tr>
       		<tr>
				<td class ='head'><b>" . _MD_ADRESS . ":</b></td> <td class ='even'>$adress</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_ZIP . ":</b></td> <td class ='even'>$zip</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_CITY . ":</b></td><td class ='even'>$city</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_COUNTRY . ":</b></td><td class ='even'>$country</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_PHONE . ":</b></td><td class ='even'> $phone</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_MOBILE . ":</b></td><td class ='even'>$mobile</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_FAX . ":</b></td><td class ='even'> $fax</td></tr>
			<tr>
				<td class ='head'><b>" . _MD_CONTEMAIL . ":</b></td><td class ='even'>$contemail</td></tr>
			<tr>
				<td valign='top' class ='head'><b>" . _MD_OPENED . ":</b></td><td class ='even'>$opentime</td></tr>
	     	<tr>
				<td class ='head'><b>" . _MD_DESCRIPTIONC . "</b></td><td class ='even'>$description</td>";

    echo '<tr><td colspan = 2><hr>';

    echo '<b>' . _MD_PUBLISHED . ':</b> ' . $datetime . '';

    echo '</td></tr>';

    echo "<tr><td colspan = 2 align = 'center'><hr>";

    printf(_MD_THISCOMESFROM, $xoopsConfig['sitename']);

    echo '<br><a href="' . XOOPS_URL . '/">' . XOOPS_URL . '</a><br><br>';

    echo '' . _MD_URLFORSTORY . '<br>';

    echo '<a href="' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/visit.php?cid=' . $cid . '&lid=' . $lid . '">' . XOOPS_URL . '/visit.php?cid=' . $cid . '&lid=' . $lid . '</a>';

    echo '</td></tr></table>';

    echo "</td></tr></table>\n";

    echo '</td></tr></table>';

    echo '</body></html>';
}

PrintPage($lid);
