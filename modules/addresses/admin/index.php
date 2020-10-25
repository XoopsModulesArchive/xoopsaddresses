<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
require dirname(__DIR__, 3) . '/include/cp_header.php';
if (file_exists('../language/' . $xoopsConfig['language'] . '/main.php')) {
    include '../language/' . $xoopsConfig['language'] . '/main.php';
} else {
    include '../language/english/main.php';
}
require dirname(__DIR__) . '/include/functions.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$myts = MyTextSanitizer::getInstance();
$eh = new ErrorHandler();
$mytree = new XoopsTree($xoopsDB->prefix('addresses_cat'), 'cid', 'pid');

function addresses()
{
    global $xoopsDB, $xoopsModule;

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>';

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    // Temporarily 'homeless' links (to be revised in admin.php breakup)

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_broken') . '');

    [$totalbrokenlinks] = $xoopsDB->fetchRow($result);

    if ($totalbrokenlinks > 0) {
        $totalbrokenlinks = "<span style='color: #ff0000; font-weight: bold'>$totalbrokenlinks</span>";
    }

    $result2 = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_mod') . '');

    [$totalmodrequests] = $xoopsDB->fetchRow($result2);

    if ($totalmodrequests > 0) {
        $totalmodrequests = "<span style='color: #ff0000; font-weight: bold'>$totalmodrequests</span>";
    }

    $result3 = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_links') . ' where status=0');

    [$totalnewlinks] = $xoopsDB->fetchRow($result3);

    if ($totalnewlinks > 0) {
        $totalnewlinks = "<span style='color: #ff0000; font-weight: bold'>$totalnewlinks</span>";
    }

    echo " - <a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid') . "'>" . _MD_GENERALSET . '</a>';

    echo '<br><br>';

    echo ' - <a href=index.php?op=linksConfigMenu>' . _MD_ADDMODDELETE . '</a>';

    echo '<br><br>';

    echo ' - <a href=index.php?op=listNewLinks>' . _MD_LINKSWAITING . " ($totalnewlinks)</a>";

    echo '<br><br>';

    echo ' - <a href=index.php?op=listBrokenLinks>' . _MD_BROKENREPORTS . " ($totalbrokenlinks)</a>";

    echo '<br><br>';

    echo ' - <a href=index.php?op=listModReq>' . _MD_MODREQUESTS . " ($totalmodrequests)</a>";

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_links') . ' where status>0');

    [$numrows] = $xoopsDB->fetchRow($result);

    echo '<br><br><div>';

    printf(_MD_THEREARE, $numrows);

    echo '</div>';

    echo '</td></tr></table>';

    xoops_cp_footer();
}

function listNewLinks()
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;

    // List links waiting for validation

    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/modules/addresses/images/shots/');

    $result = $xoopsDB->query('select lid, cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl, submitter from ' . $xoopsDB->prefix('addresses_links') . ' where status=0 order by date DESC');

    $numrows = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>';

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    echo '<h4>' . _MD_LINKSWAITING . "&nbsp;($numrows)</h4><br>";

    if ($numrows > 0) {
        while (list($lid, $cid, $title, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl, $submitterid) = $xoopsDB->fetchRow($result)) {
            $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('addresses_text') . " where lid=$lid");

            [$description] = $xoopsDB->fetchRow($result2);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            $adress = htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5);

            $zip = htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5);

            $city = htmlspecialchars($city, ENT_QUOTES | ENT_HTML5);

            $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

            $phone = htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5);

            $mobile = htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5);

            $fax = htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5);

            $contemail = htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5);

            $opentime = htmlspecialchars($opentime, ENT_QUOTES | ENT_HTML5);

            $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

            //		$url = urldecode($url);

            //		$logourl = htmlspecialchars($logourl);

            //		$logourl = urldecode($logourl);

            $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

            $submitter = XoopsUser::getUnameFromId($submitterid);

            echo "<form action=\"index.php\" method=post>\n";

            echo '<table width="80%">';

            echo '<tr><td align="right" nowrap>' . _MD_SUBMITTER . "</td><td>\n";

            echo '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $submitterid . "\">$submitter</a>";

            echo "</td></tr>\n";

            echo '<tr><td align="right" nowrap>' . _MD_SITETITLE . '</td><td>';

            echo "<input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\" value=\"$title\">";

            echo '</td></tr>';

            // INGEVOEGD VANAF HIER //

            echo '<tr><td align="right" nowrap>' . _MD_CATEGORYC . '</td><td>';

            $mytree->makeMySelBox('title', 'title', $cid);

            echo "</td></tr>\n";

            echo '<tr><td align="right" nowrap>' . _MD_ADRESS . '</td><td>';

            echo "<input type=\"text\" name=\"adress\" size=\"50\" maxlength=\"100\" value=\"$adress\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_ZIP . '</td><td>';

            echo "<input type=\"text\" name=\"zip\" size=\"20\" maxlength=\"20\" value=\"$zip\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_CITY . '</td><td>';

            echo "<input type=\"text\" name=\"city\" size=\"50\" maxlength=\"100\" value=\"$city\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_COUNTRY . '</td><td>';

            echo "<input type=\"text\" name=\"country\" size=\"50\" maxlength=\"100\" value=\"$country\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_PHONE . '</td><td>';

            echo "<input type=\"text\" name=\"phone\" size=\"20\" maxlength=\"40\" value=\"$phone\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_MOBILE . '</td><td>';

            echo "<input type=\"text\" name=\"mobile\" size=\"20\" maxlength=\"40\" value=\"$mobile\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_FAX . '</td><td>';

            echo "<input type=\"text\" name=\"fax\" size=\"20\" maxlength=\"40\" value=\"$fax\">";

            echo '</td></tr>';

            echo '<tr><td align="right" nowrap>' . _MD_CONTEMAIL . '</td><td>';

            echo "<input type=\"text\" name=\"contemail\" size=\"50\" maxlength=\"100\" value=\"$contemail\">";

            // EIND INGEVOEGD FORM //

            echo '</td></tr><tr><td align="right" nowrap>' . _MD_SITEURL . '</td><td>';

            echo "<input type=\"text\" name=\"url\" size=\"50\" maxlength=\"250\" value=\"$url\">";

            echo "&nbsp;[&nbsp;<a href=\"$url\" target=\"_blank\">" . _MD_VISIT . '</a>&nbsp;]';

            echo '</td></tr>';

            //echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";

            //$mytree->makeMySelBox("title", "title", $cid);

            //echo "</td></tr>\n";

            //INGEVOEGD

            echo '<tr><td align="right" valign="top" nowrap>' . _MD_OPENED . "</td><td>\n";

            echo "<textarea name=opentime cols=\"50\" rows=\"5\">$opentime</textarea>\n";

            echo "</td></tr>\n";

            //EIND INGEVOEGD

            echo '<tr><td align="right" valign="top" nowrap>' . _MD_DESCRIPTIONC . "</td><td>\n";

            echo "<textarea name=description cols=\"60\" rows=\"7\">$description</textarea>\n";

            echo "</td></tr>\n";

            echo '<tr><td align="right" nowrap>' . _MD_SHOTIMAGE . "</td><td>\n";

            //echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\">\n";

            echo "<select size='1' name='logourl'>";

            echo "<option value=' '>------</option>";

            foreach ($linkimg_array as $image) {
                echo "<option value='" . $image . "'>" . $image . '</option>';
            }

            echo '</select>';

            echo '</td></tr><tr><td></td><td>';

            $shotdir = '<b>' . XOOPS_URL . '/modules/addresses/images/shots/</b>';

            printf(_MD_SHOTMUST, $shotdir);

            echo "</td></tr>\n";

            echo "</table>\n";

            echo '<br><input type="hidden" name="op" value="approve"></input>';

            echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\"></input>";

            echo '<input type="submit" value="' . _MD_APPROVE . "\"></form>\n";

            echo myTextForm("index.php?op=delNewLink&lid=$lid", _MD_DELETE); //ATTENTIE

            echo '<br><br>';
        }
    } else {
        echo '' . _MD_NOSUBMITTED . '';
    }

    echo '</td></tr></table>';

    xoops_cp_footer();
}

function linksConfigMenu()
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree;

    // Add a New Main Category

    xoops_cp_header();

    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/modules/addresses/images/shots/');

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>'; //ATTENTIE

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    echo "<form method=post action=index.php>\n";

    echo '<h4>' . _MD_ADDMAIN . '</h4><br>' . _MD_TITLEC . '<input type=text name=title size=30 maxlength=50><br>';

    echo '' . _MD_IMGURL . '<br><input type="text" name="imgurl" size="100" maxlength="150" value="http://"><br><br>';

    echo "<input type=hidden name=cid value=0>\n";

    echo '<input type=hidden name=op value=addCat>';

    echo '<input type=submit value=' . _MD_ADD . '><br></form>';

    echo '</td></tr></table>';

    echo '<br>';

    // Add a New Sub-Category

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_cat') . '');

    [$numrows] = $xoopsDB->fetchRow($result);

    if ($numrows > 0) {
        echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

        echo '<form method=post action=index.php>';

        echo '<h4>' . _MD_ADDSUB . '</h4><br>' . _MD_TITLEC . '<input type=text name=title size=30 maxlength=50>&nbsp;' . _MD_IN . '&nbsp;';

        $mytree->makeMySelBox('title', 'title');

        #		echo "<br>"._MD_IMGURL."<br><input type=\"text\" name=\"imgurl\" size=\"100\" maxlength=\"150\">\n";

        echo '<input type=hidden name=op value=addCat><br><br>';

        echo '<input type=submit value=' . _MD_ADD . '><br></form>';

        echo '</td></tr></table>';

        echo '<br>';

        // If there is a category, add a New Adress !!

        echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

        echo "<form method=post action=index.php>\n";

        echo '<h4>' . _MD_ADDNEWLINK . "</h4><br>\n";

        echo "<table width=\"80%\"><tr>\n";

        echo '<td align="right">' . _MD_SITETITLE . '</td><td>';

        echo '<input type=text name=title size=50 maxlength=100>';

        echo '</td></tr>';

        //INGEVOEGD FORM

        echo '<tr><td align="right" nowrap>' . _MD_CATEGORYC . '</td><td>';

        $mytree->makeMySelBox('title', 'title');

        echo "</td></tr>\n";

        echo '<tr><td align="right">' . _MD_ADRESS . '</td><td>';

        echo '<input type=text name=adress size=50 maxlength=100>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_ZIP . '</td><td>';

        echo '<input type=text name=zip size=20 maxlength=20>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_CITY . '</td><td>';

        echo '<input type=text name=city size=50 maxlength=100>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_COUNTRY . '</td><td>';

        echo '<input type=text name=country size=50 maxlength=100>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_PHONE . '</td><td>';

        echo '<input type=text name=phone size=20 maxlength=40>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_MOBILE . '</td><td>';

        echo '<input type=text name=mobile size=20 maxlength=40>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_FAX . '</td><td>';

        echo '<input type=text name=fax size=20 maxlength=40>';

        echo '</td></tr>';

        echo '<tr><td align="right">' . _MD_CONTEMAIL . '</td><td>';

        echo '<input type=text name=contemail size=50 maxlength=100>';

        echo '</td></tr>';

        //EIND INGEVOEGD FORM

        echo '<tr><td align="right" nowrap>' . _MD_SITEURL . '</td><td>';

        echo '<input type=text name=url size=50 maxlength=250 value="http://">';

        echo '</td></tr>';

        //echo "<tr><td align=\"right\" nowrap>"._MD_CATEGORYC."</td><td>";

        //$mytree->makeMySelBox("title", "title");

        // INGEVOEGD

        echo '<tr><td align="right" valign="top" nowrap>' . _MD_OPENED . "</td><td>\n";

        echo "<textarea name=opentime cols=60 rows=5></textarea>\n";

        echo "</td></tr>\n";

        // EIND INGEVOEGD

        echo '<tr><td align="right" valign="top" nowrap>' . _MD_DESCRIPTIONC . "</td><td>\n";

        xoopsCodeTarea('description', 60, 8);

        xoopsSmilies('description');

        //echo "<textarea name=description cols=60 rows=5></textarea>\n";

        echo "</td></tr>\n";

        echo '<tr><td align="right"nowrap>' . _MD_SHOTIMAGE . "</td><td>\n";

        //echo "<input type=\"text\" name=\"logourl\" size=\"50\" maxlength=\"60\">";

        echo "<select size='1' name='logourl'>";

        echo "<option value=' '>------</option>";

        foreach ($linkimg_array as $image) {
            echo "<option value='" . $image . "'>" . $image . '</option>';
        }

        echo '</select>';

        echo "</td></tr>\n";

        $shotdir = '<b>' . XOOPS_URL . '/modules/addresses/images/shots/</b>';

        echo '<tr><td></td><td>';

        printf(_MD_SHOTMUST, $shotdir);

        echo "</td></tr>\n";

        echo "</table>\n<br>";

        echo '<input type="hidden" name="op" value="addLink"></input>'; //ATTENTIE

        echo '<input type="submit" class="button" value="' . _MD_ADD . "\"></input>\n";

        echo '</form>';

        echo '</td></tr></table>';

        echo '<br>';

        // Modify Category

        echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

        echo '
    	</center><form method=post action=index.php>
    	<h4>' . _MD_MODCAT . '</h4><br>';

        echo _MD_CATEGORYC;

        $mytree->makeMySelBox('title', 'title');

        echo "<br><br>\n";

        echo "<input type=hidden name=op value=modCat>\n";

        echo '<input type=submit value=' . _MD_MODIFY . ">\n";

        echo '</form>';

        echo '</td></tr></table>';

        echo '<br>';
    }

    // Modify Adress

    $result2 = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_links') . '');

    [$numrows2] = $xoopsDB->fetchRow($result2);

    if ($numrows2 > 0) {
        echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

        echo "<form method=get action=\"index.php\">\n";

        echo '<h4>' . _MD_MODLINK . "</h4><br>\n"; //SHINE need to change later

        echo _MD_LINKID . "<input type=text name=lid size=12 maxlength=11>\n";

        echo "<input type=hidden name=fct value=addresses>\n"; //ATTENTIE
        echo "<input type=hidden name=op value=modLink><br><br>\n"; // SHINE need to change later
        echo '<input type=submit value=' . _MD_MODIFY . "></form>\n";

        echo '</td></tr></table>';
    }

    xoops_cp_footer();
}

//MODIFY LINK CORRESPONDENT WITH LINE 781
//Administrator modify works without problem !!
function modLink()
{
    global $xoopsDB, $_GET, $myts, $eh, $mytree, $xoopsConfig;

    $linkimg_array = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/modules/addresses/images/shots/');

    $lid = $_GET['lid'];

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>';

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    $result = $xoopsDB->query('select cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl from ' . $xoopsDB->prefix('addresses_links') . " where lid=$lid") or $eh::show('0013');

    echo '<h4>' . _MD_MODLINK . '</h4><br>';

    [$cid, $title, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $adress = htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5);

    $zip = htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5);

    $city = htmlspecialchars($city, ENT_QUOTES | ENT_HTML5);

    $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

    $phone = htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5);

    $mobile = htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5);

    $fax = htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5);

    $contemail = htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5);

    $opentime = htmlspecialchars($opentime, ENT_QUOTES | ENT_HTML5);

    $url = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5);

    //   	$url = urldecode($url);

    $logourl = htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5);

    //  	$logourl = urldecode($logourl);

    $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('addresses_text') . " where lid=$lid");

    [$description] = $xoopsDB->fetchRow($result2);

    $GLOBALS['description'] = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5);

    echo '<table>';

    echo '<form method=post action=index.php>';

    echo '<tr><td>' . _MD_LINKID . "</td><td><b>$lid</b></td></tr>";

    echo '<tr><td>' . _MD_SITETITLE . "</td><td><input type=text name=title value=\"$title\" size=50 maxlength=100></input></td></tr>\n";

    // INGEVOEGD FORM

    echo '<tr><td>' . _MD_CATEGORYC . '</td><td>';

    $mytree->makeMySelBox('title', 'title', $cid);

    echo "</td></tr>\n";

    echo '<tr><td>' . _MD_ADRESS . "</td><td><input type=text name=adress value=\"$adress\" size=50 maxlength=100></input></td></tr>\n";

    echo '<tr><td>' . _MD_ZIP . "</td><td><input type=text name=zip value=\"$zip\" size=20 maxlength=20></input></td></tr>\n";

    echo '<tr><td>' . _MD_CITY . "</td><td><input type=text name=city value=\"$city\" size=50 maxlength=100></input></td></tr>\n";

    echo '<tr><td>' . _MD_COUNTRY . "</td><td><input type=text name=country value=\"$country\" size=50 maxlength=100></input></td></tr>\n";

    echo '<tr><td>' . _MD_PHONE . "</td><td><input type=text name=phone value=\"$phone\" size=20 maxlength=40></input></td></tr>\n";

    echo '<tr><td>' . _MD_MOBILE . "</td><td><input type=text name=mobile value=\"$mobile\" size=20 maxlength=40></input></td></tr>\n";

    echo '<tr><td>' . _MD_FAX . "</td><td><input type=text name=fax value=\"$fax\" size=20 maxlength=40></input></td></tr>\n";

    echo '<tr><td>' . _MD_CONTEMAIL . "</td><td><input type=text name=contemail value=\"$contemail\" size=50 maxlength=100></input></td></tr>\n";

    // EIND FORM

    echo '<tr><td>' . _MD_SITEURL . "</td><td><input type=text name=url value=\"$url\" size=50 maxlength=250></input></td></tr>\n";

    // INGEVOEGD

    echo '<tr><td valign="top">' . _MD_OPENED . '</td><td>';

    echo "<textarea name=opentime cols=60 rows=5>$opentime</textarea>";

    echo '</td></tr>';

    //EIND INGEVOEGD

    echo '<tr><td valign="top">' . _MD_DESCRIPTIONC . '</td><td>';

    xoopsCodeTarea('description', 60, 8);

    xoopsSmilies('description');

    //echo "<textarea name=description cols=60 rows=5>$description</textarea>";

    echo '</td></tr>';

    //echo "<tr><td>"._MD_CATEGORYC."</td><td>";

    //$mytree->makeMySelBox("title", "title", $cid);

    //echo "</td></tr>\n";

    echo '<tr><td>' . _MD_SHOTIMAGE . '</td><td>';

    //echo "<input type=text name=logourl value=\"$logourl\" size=\"50\" maxlength=\"60\"></input>

    echo "<select size='1' name='logourl'>";

    echo "<option value=' '>------</option>";

    foreach ($linkimg_array as $image) {
        if ($image == $logourl) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = '';
        }

        echo "<option value='" . $image . "' $opt_selected>" . $image . '</option>';
    }

    echo '</select>';

    echo "</td></tr>\n";

    $shotdir = '<b>' . XOOPS_URL . '/modules/addresses/images/shots/</b>';

    echo '<tr><td></td><td>';

    printf(_MD_SHOTMUST, $shotdir);

    echo "</td></tr>\n";

    echo '</table>';

    echo "<br><br><input type=hidden name=lid value=$lid></input>\n";

    echo '<input type=hidden name=op value=modLinkS><input type=submit value=' . _MD_MODIFY . '>'; //SHINE need to change later

    // echo "&nbsp;<input type=button value="._MD_DELETE." onclick=\"javascript:location='index.php?op=delLink&lid=".$lid."'\">";

    //echo "&nbsp;<input type=button value="._MD_CANCEL." onclick=\"javascript:history.go(-1)\">";

    echo "</form>\n";

    echo "<table><tr><td>\n";

    echo myTextForm('index.php?op=delLink&lid=' . $lid, _MD_DELETE); //SHINE need to change later

    echo "</td><td>\n";

    echo myTextForm('index.php?op=linksConfigMenu', _MD_CANCEL); //SHINE need to change later

    echo "</td></tr></table>\n";

    echo '<hr>';

    $result5 = $xoopsDB->query('SELECT count(*) FROM ' . $xoopsDB->prefix('addresses_votedata') . " WHERE lid = $lid");

    [$totalvotes] = $xoopsDB->fetchRow($result5);

    echo "<table width=100%>\n";

    echo '<tr><td colspan=7><b>';

    printf(_MD_TOTALVOTES, $totalvotes);

    echo "</b><br><br></td></tr>\n";

    // Show Registered Users Votes

    $result5 = $xoopsDB->query('SELECT ratingid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('addresses_votedata') . " WHERE lid = $lid AND ratinguser >0 ORDER BY ratingtimestamp DESC");

    $votes = $xoopsDB->getRowsNum($result5);

    echo '<tr><td colspan=7><br><br><b>';

    printf(_MD_USERTOTALVOTES, $votes);

    echo "</b><br><br></td></tr>\n";

    echo '<tr><td><b>' . _MD_USER . '  </b></td><td><b>' . _MD_IP . '  </b></td><td><b>' . _MD_RATING . '  </b></td><td><b>' . _MD_USERAVG . '  </b></td><td><b>' . _MD_TOTALRATE . '  </b></td><td><b>' . _MD_DATE . '  </b></td><td align="center"><b>' . _MD_DELETE . "</b></td></tr>\n";

    if (0 == $votes) {
        echo '<tr><td align="center" colspan="7">' . _MD_NOREGVOTES . "<br></td></tr>\n";
    }

    $x = 0;

    $colorswitch = 'dddddd';

    while (list($ratingid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp) = $xoopsDB->fetchRow($result5)) {
        //	$ratingtimestamp = formatTimestamp($ratingtimestamp);

        //Individual user information

        $result2 = $xoopsDB->query('SELECT rating FROM ' . $xoopsDB->prefix('addresses_votedata') . " WHERE ratinguser = '$ratinguser'");

        $uservotes = $xoopsDB->getRowsNum($result2);

        $useravgrating = 0;

        while (list($rating2) = $xoopsDB->fetchRow($result2)) {
            $useravgrating += $rating2;
        }

        $useravgrating /= $uservotes;

        $useravgrating = number_format($useravgrating, 1);

        $ratingusername = XoopsUser::getUnameFromId($ratinguser);

        echo '<tr><td bgcolor="'
             . $colorswitch
             . '">'
             . $ratingusername
             . "</td><td bgcolor=\"$colorswitch\">"
             . $ratinghostname
             . "</td><td bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">"
             . $useravgrating
             . "</td><td bgcolor=\"$colorswitch\">"
             . $uservotes
             . "</td><td bgcolor=\"$colorswitch\">"
             . $ratingtimestamp
             . "</td><td bgcolor=\"$colorswitch\" align=\"center\"><b>"
             . myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid", 'X')
             . "</b></td></tr>\n";

        $x++;

        if ('dddddd' == $colorswitch) {
            $colorswitch = 'ffffff';
        } else {
            $colorswitch = 'dddddd';
        }
    }

    // Show Unregistered Users Votes

    $result5 = $xoopsDB->query('SELECT ratingid, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('addresses_votedata') . " WHERE lid = $lid AND ratinguser = 0 ORDER BY ratingtimestamp DESC");

    $votes = $xoopsDB->getRowsNum($result5);

    echo '<tr><td colspan=7><b><br><br>';

    printf(_MD_ANONTOTALVOTES, $votes);

    echo "</b><br><br></td></tr>\n";

    echo '<tr><td colspan=2><b>' . _MD_IP . '  </b></td><td colspan=3><b>' . _MD_RATING . '  </b></td><td><b>' . _MD_DATE . '  </b></b></td><td align="center"><b>' . _MD_DELETE . '</b></td><br></tr>';

    if (0 == $votes) {
        echo '<tr><td colspan="7" align="center">' . _MD_NOUNREGVOTES . '<br></td></tr>';
    }

    $x = 0;

    $colorswitch = 'dddddd';

    while (list($ratingid, $rating, $ratinghostname, $ratingtimestamp) = $xoopsDB->fetchRow($result5)) {
        $formatted_date = formatTimestamp($ratingtimestamp);

        echo "<td colspan=\"2\" bgcolor=\"$colorswitch\">$ratinghostname</td><td colspan=\"3\" bgcolor=\"$colorswitch\">$rating</td><td bgcolor=\"$colorswitch\">$formatted_date</td><td bgcolor=\"$colorswitch\" aling=\"center\"><b>"
             . myTextForm("index.php?op=delVote&lid=$lid&rid=$ratingid", 'X')
             . '</b></td></tr>';

        $x++;

        if ('dddddd' == $colorswitch) {
            $colorswitch = 'ffffff';
        } else {
            $colorswitch = 'dddddd';
        }
    }

    echo "<tr><td colspan=\"6\">&nbsp;<br></td></tr>\n";

    echo "</table>\n";

    echo '</td></tr></table>';

    xoops_cp_footer();
}

function delVote()
{
    global $xoopsDB, $_GET, $eh;

    $rid = $_GET['rid'];

    $lid = $_GET['lid'];

    $sql = sprintf('DELETE FROM %s WHERE ratingid = %u', $xoopsDB->prefix('addresses_votedata'), $rid);

    $xoopsDB->query($sql) or $eh::show('0013');

    updaterating($lid);

    redirect_header('index.php', 1, _MD_VOTEDELETED);

    exit();
}

function listBrokenLinks() //ATTENTIE
{
    global $xoopsDB, $eh;

    $result = $xoopsDB->query('select * from ' . $xoopsDB->prefix('addresses_broken') . ' group by lid order by reportid DESC');

    $totalbrokenlinks = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>'; //SHINE need to change later

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    echo '<h4>' . _MD_BROKENREPORTS . " ($totalbrokenlinks)</h4><br>"; //SHINE need to change later

    if (0 == $totalbrokenlinks) { //SHINE need to change later
        echo _MD_NOBROKEN;
    } else {
        echo '<center>
		' . _MD_IGNOREDESC . '<br>
		' . _MD_DELETEDESC . '</center><br><br><br>';

        $colorswitch = 'dddddd';

        echo '<table align="center" width="90%">';

        echo '
        <tr>
        <td><b>Link Name</b></td>
        <td><b>' . _MD_REPORTER . '</b></td>
        <td><b>' . _MD_LINKSUBMITTER . '</b></td>
        <td><b>' . _MD_IGNORE . '</b></td>
        <td><b>' . _EDIT . '</b></td>
        <td><b>' . _MD_DELETE . '</b></td>
        </tr>';

        while (list($reportid, $lid, $sender, $ip) = $xoopsDB->fetchRow($result)) {
            $result2 = $xoopsDB->query('select title, url, submitter from ' . $xoopsDB->prefix('addresses_links') . " where lid=$lid");

            if (0 != $sender) {
                $result3 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid=$sender");

                [$uname, $email] = $xoopsDB->fetchRow($result3);
            }

            [$title, $url, $ownerid] = $xoopsDB->fetchRow($result2);

            //			$url=urldecode($url);

            $result4 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$ownerid'");

            [$owner, $owneremail] = $xoopsDB->fetchRow($result4);

            echo "<tr><td bgcolor=$colorswitch><a href=$url target='_blank'>$title</a></td>";

            if ('' == $email) {
                echo '<td bgcolor="' . $colorswitch . '">' . $sender . ' (' . $ip . ')';
            } else {
                echo '<td bgcolor="' . $colorswitch . '"><a href="mailto:' . $email . '">' . $uname . '</a> (' . $ip . ')';
            }

            echo '</td>';

            if ('' == $owneremail) {
                echo '<td bgcolor="' . $colorswitch . '">' . $owner . '';
            } else {
                echo '<td bgcolor="' . $colorswitch . '"><a href="mailto:' . $owneremail . '">' . $owner . '</a>';
            }

            echo "</td><td bgcolor='$colorswitch' align='center'>\n";

            echo myTextForm("index.php?op=ignoreBrokenLinks&lid=$lid", 'X'); //SHINE need to change later

            echo "</td><td bgcolor='$colorswitch' align='center'>\n";

            echo myTextForm("index.php?op=modLink&lid=$lid", 'X');  //SHINE need to change later

            echo "</td><td align='center' bgcolor='$colorswitch'>\n";

            echo myTextForm("index.php?op=delBrokenLinks&lid=$lid", 'X'); //SHINE need to change later

            echo "</td></tr>\n";

            if ('#dddddd' == $colorswitch) {
                $colorswitch = '#ffffff';
            } else {
                $colorswitch = '#dddddd';
            }
        }

        echo '</table>';
    }

    echo '</td></tr></table>';

    xoops_cp_footer();
}

function delBrokenLinks() //SHINE need to change later
{
    global $xoopsDB, $_GET, $eh;

    $lid = $_GET['lid'];

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_broken'), $lid);

    $xoopsDB->query($sql) or $eh::show('0013');

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_links'), $lid);

    $xoopsDB->query($sql) or $eh::show('0013');

    redirect_header('index.php', 1, _MD_LINKDELETED);

    exit();
}

function ignoreBrokenLinks() //SHINE need to change later
{
    global $xoopsDB, $_GET, $eh;

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_broken'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    redirect_header('index.php', 1, _MD_BROKENDELETED);

    exit();
}

//Shine: MODIFICATION REQUESTS VIEW WITHIN ADMINAREA
//Shine: This is part where it goes wrong. Problems!!
function listModReq()
{
    global $xoopsDB, $xoopsConfig, $myts, $eh, $mytree, $xoopsModuleConfig;

    //Catz edit from here

    //Changed this to select all fields from the database, instead of listing them all as this creates an error for some reason. Not sure why but it does?

    $result = $xoopsDB->query('SELECT * from ' . $xoopsDB->prefix('addresses_mod') . ' order by requestid');

    $totalmodrequests = $xoopsDB->getRowsNum($result);

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>';

    echo '<h4>' . _MD_USERMODREQ . " ($totalmodrequests)</h4>";

    if ($totalmodrequests > 0) {
        $lookup_lid = [];

        while (list($requestid, $lid, $cid, $title, $url, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $logourl, $description, $submitterid) = $xoopsDB->fetchRow($result)) {
            $lookup_lid[$requestid] = $lid;

            $result2 = $xoopsDB->query('select cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl, submitter from ' . $xoopsDB->prefix('addresses_links') . " where lid=$lid");

            [$origcid, $origtitle, $origadress, $origzip, $origcity, $origcountry, $origphone, $origmobile, $origfax, $origcontemail, $origopentime, $origurl, $origlogourl, $ownerid] = $xoopsDB->fetchRow($result2);

            $result2 = $xoopsDB->query('select description from ' . $xoopsDB->prefix('addresses_text') . " where lid=$lid");

            [$origdescription] = $xoopsDB->fetchRow($result2);

            $result7 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$submitterid'");

            $result8 = $xoopsDB->query('select uname, email from ' . $xoopsDB->prefix('users') . " where uid='$ownerid'");

            $cidtitle = $mytree->getPathFromId($cid, 'title');

            $origcidtitle = $mytree->getPathFromId($origcid, 'title');

            [$submitter, $submitteremail] = $xoopsDB->fetchRow($result7);

            [$owner, $owneremail] = $xoopsDB->fetchRow($result8);

            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

            //ingevoegd Catz says ????? ;-)

            $adress = htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5);

            $zip = htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5);

            $city = htmlspecialchars($city, ENT_QUOTES | ENT_HTML5);

            $country = htmlspecialchars($country, ENT_QUOTES | ENT_HTML5);

            $phone = htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5);

            $mobile = htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5);

            $fax = htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5);

            $contemail = htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5);

            $opentime = $myts->displayTarea($opentime);

            // use original image file to prevent users from changing screen shots file

            $origlogourl = htmlspecialchars($origlogourl, ENT_QUOTES | ENT_HTML5);

            $logourl = $origlogourl;

            $description = $myts->displayTarea($description);

            $origdescription = $myts->displayTarea($origdescription);

            if ('' == $owner) {
                $owner = 'administration';
            }

            // VIEW ORIGINAL SEEMS OK

            echo "<table border=0 cellpadding=2 cellspacing=1 align=center width=100% class = 'outer'>
    	    <tr>
				<td class ='bg3' colspan = 2><b>" . _MD_ORIGINAL . "</b></td></tr>
    	    <tr>
				<td class ='head'>" . _MD_SITETITLE . "</td> <td class ='even'>$origtitle</td></tr>
       		<tr>
				<td class ='head'>" . _MD_ADRESS . ":</td> <td class ='even'>$origadress</td></tr>
			<tr>
				<td class ='head'>" . _MD_ZIP . ":</td> <td class ='even'>$origzip</td></tr>
			<tr>
				<td class ='head'>" . _MD_CITY . ":</td><td class ='even'>$origcity</td></tr>
			<tr>
				<td class ='head'>" . _MD_COUNTRY . ":</td><td class ='even'>$origcountry</td></tr>
			<tr>
				<td class ='head'>" . _MD_PHONE . ":</td><td class ='even'> $origphone</td></tr>
			<tr>
				<td class ='head'>" . _MD_MOBILE . ":</td><td class ='even'>$origmobile</td></tr>
			<tr>
				<td class ='head'>" . _MD_FAX . ":</td><td class ='even'> $origfax</td></tr>
			<tr>
				<td class ='head'>" . _MD_CONTEMAIL . ":</td><td class ='even'>$origcontemail</td></tr>
			<tr>
				<td class ='head' >" . _MD_SITEURL . "</td><td class ='even'>$origurl</td></tr>
			<tr>
				<td class ='head'>" . _MD_OPENED . ":</td><td class ='even'>$origopentime</td></tr>
	     	<tr>
				<td class ='head'>" . _MD_CATEGORYC . "</td><td class ='even'>$origcidtitle</td></tr>
	     	<tr>
				<td class ='head'>" . _MD_SHOTIMAGE . "</td><td class ='even'>";

            if ($xoopsModuleConfig['useshots'] && !empty($origlogourl)) {
                echo '<img src="' . XOOPS_URL . '/modules/addresses/images/shots/' . $origlogourl . '" width="' . $xoopsModuleConfig['shotwidth'] . '">';
            } else {
                echo '&nbsp;';
            }

            echo '</td></tr>';

            //VIEW PROPOSEL IS A BIG MESS

            echo "<tr><td class ='head'>" . _MD_DESCRIPTIONC . "</td><td class ='even'>$origdescription</td></tr>
			<tr>
				<td class = 'bg3' colspan = '2'><b>" . _MD_PROPOSED . "</b></td></tr>
    	    <tr>
				<td class ='head' >" . _MD_SITETITLE . " </td><td class ='even'>$title</td></tr>
            <tr>
				<td class ='head' >" . _MD_ADRESS . "</td><td class ='even'>$adress</td></tr>
			<tr>
				<td class ='head' >" . _MD_ZIP . "</td><td class ='even'>$zip</td></tr>
			<tr>
				<td class ='head' >" . _MD_CITY . "</td><td class ='even'>$city</td></tr>
			<tr>
				<td class ='head' >" . _MD_COUNTRY . "</td><td class ='even'>$country</td></tr>
			<tr>
				<td class ='head' >" . _MD_PHONE . "</td><td class ='even'>$phone</td></tr>
			<tr>
				<td class ='head' >" . _MD_MOBILE . "</td><td class ='even'>$mobile</td></tr>
			<tr>
				<td class ='head' >" . _MD_FAX . "</td><td class ='even'>$fax</td></tr>
			<tr>
				<td class ='head' >" . _MD_CONTEMAIL . "</td><td class ='even'>$contemail</td></tr>
			<tr>
				<td class ='head' >" . _MD_SITEURL . "</td><td class ='even'>$url</td></tr>
			<tr>
				<td class ='head' >" . _MD_OPENED . "</td><td class ='even'>$opentime</td></tr>
	     	<tr>
				<td class ='head'>" . _MD_CATEGORYC . "</td><td class ='even'>$cidtitle</td></tr>
	     	<tr>
				<td class ='head' >" . _MD_SHOTIMAGE . "</td><td class ='even'>";

            if (1 == $xoopsModuleConfig['useshots'] && !empty($logourl)) {
                echo '<img src="' . XOOPS_URL . '/modules/addresses/images/shots/' . $logourl . '" width="' . $xoopsModuleConfig['shotwidth'] . '" alt="/">';
            } else {
                echo '&nbsp;';
            }

            echo "</td></tr>
    	   	<tr>
			<td class ='head'>" . _MD_DESCRIPTIONC . "</td><td class ='even'>$description</td>
			</table>
		
	   		<table align=center width=100%>
    	  	<tr>";

            if ('' == $submitteremail) {
                echo '<td align=left><small>' . _MD_SUBMITTER . "$submitter</small></td>";
            } else {
                echo '<td align=left><small>' . _MD_SUBMITTER . '<a href=mailto:' . $submitteremail . '>' . $submitter . '</a></small></td>';
            }

            if ('' == $owneremail) {
                echo '<td align=center><small>' . _MD_OWNER . '' . $owner . '</small></td>';
            } else {
                echo '<td align=center><small>' . _MD_OWNER . '<a href=mailto:' . $owneremail . '>' . $owner . '</a></small></td>';
            }

            echo "<td align=right><small>\n";

            // Catz stops here. //

            echo "<table><tr><td>\n";

            echo myTextForm("index.php?op=changeModReq&requestid=$requestid", _MD_APPROVE);

            echo "</td><td>\n";

            echo myTextForm("index.php?op=modLink&lid=$lookup_lid[$requestid]", _EDIT);

            echo "</td><td>\n";

            echo myTextForm("index.php?op=ignoreModReq&requestid=$requestid", _MD_IGNORE);

            echo "</td></tr></table>\n";

            echo "</small></td></tr>\n";

            echo '</table>';
        }
    } else {
        echo _MD_NOMODREQ;
    }

    xoops_cp_footer();
}

function changeModReq()
{
    global $xoopsDB, $_GET, $eh, $myts;

    $requestid = $_GET['requestid'];

    $query = 'select lid, cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl, description from ' . $xoopsDB->prefix('addresses_mod') . ' where requestid=' . $requestid . '';

    $result = $xoopsDB->query($query);

    while (list($lid, $cid, $title, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl, $description) = $xoopsDB->fetchRow($result)) {
        if (get_magic_quotes_runtime()) {
            $title = stripslashes($title);

            $adress = stripslashes($adress);

            $zip = stripslashes($zip);

            $city = stripslashes($city);

            $country = stripslashes($country);

            $phone = stripslashes($phone);

            $mobile = stripslashes($mobile);

            $fax = stripslashes($fax);

            $contemail = stripslashes($contemail);

            $opentime = stripslashes($opentime);

            $url = stripslashes($url);

            $logourl = stripslashes($logourl);

            $description = stripslashes($description);
        }

        $title = addslashes($title);

        $adress = addslashes($adress);

        $zip = addslashes($zip);

        $city = addslashes($city);

        $country = addslashes($country);

        $phone = addslashes($phone);

        $mobile = addslashes($mobile);

        $fax = addslashes($fax);

        $contemail = addslashes($contemail);

        $opentime = addslashes($opentime);

        $url = addslashes($url);

        $logourl = addslashes($logourl);

        $description = addslashes($description);

        //NIET DUIDELIJK HOE ONDERSTAAND MOET !! ook regel 1026//

        //Wat moet je hier met die %u en %s toekenningen doen //

        //als je zelf extra velden hebt toegevoegd? Wat krijgt waar en wat? //

        //Heb er de extra velden bijgevoegd en ze allemaal maar %s toekenning gegeven //

        // I think the error msg comes through this //

        $sql = sprintf(
            "UPDATE %s SET cid = %u, title = '%s', adress = '%s', zip = '%s', city = '%s', country = '%s', phone = '%s', mobile = '%s', fax = '%s', contemail = '%s', opentime = '%s', url = '%s', logourl = '%s', status = 2, date = %u WHERE lid = %u",
            $xoopsDB->prefix('addresses_links'),
            $cid,
            $title,
            $adress,
            $zip,
            $city,
            $country,
            $phone,
            $mobile,
            $fax,
            $contemail,
            $opentime,
            $url,
            $logourl,
            time(),
            $lid
        );

        $xoopsDB->query($sql) or $eh::show('0013');

        $sql = sprintf("UPDATE %s SET description = '%s' WHERE lid = %u", $xoopsDB->prefix('addresses_text'), $description, $lid);

        $xoopsDB->query($sql) or $eh::show('0013');

        $sql = sprintf('DELETE FROM %s WHERE requestid = %u', $xoopsDB->prefix('addresses_mod'), $requestid);

        $xoopsDB->query($sql) or $eh::show('0013');
    }

    redirect_header('index.php', 1, _MD_DBUPDATED);

    exit();
}

function ignoreModReq()
{
    global $xoopsDB, $_GET, $eh;

    $sql = sprintf('DELETE FROM %s WHERE requestid = %u', $xoopsDB->prefix('addresses_mod'), $_GET['requestid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    redirect_header('index.php', 1, _MD_MODREQDELETED);

    exit();
}

//ADMIN MOD ADRESS
function modLinkS() //SHINE need to change later
{
    global $xoopsDB, $_POST, $myts, $eh;

    $cid = $_POST['cid'];

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //		$url = $myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']); //Shine eerst disabled !!

    $title = $myts->addSlashes($_POST['title']);

    $adress = $myts->addSlashes($_POST['adress']);

    $zip = $myts->addSlashes($_POST['zip']);

    $city = $myts->addSlashes($_POST['city']);

    $country = $myts->addSlashes($_POST['country']);

    $phone = $myts->addSlashes($_POST['phone']);

    $mobile = $myts->addSlashes($_POST['mobile']);

    $fax = $myts->addSlashes($_POST['fax']);

    $contemail = $myts->addSlashes($_POST['contemail']);

    $opentime = $myts->addSlashes($_POST['opentime']);

    $description = $myts->addSlashes($_POST['description']);

    $xoopsDB->query(
        'update '
        . $xoopsDB->prefix('addresses_links')
        . " set cid='$cid', title='$title',  adress='$adress', zip='$zip', city='$city', country='$country', phone='$phone', mobile='$mobile', fax='$fax', contemail='$contemail', opentime='$opentime', url='$url', logourl='$logourl', status=2, date="
        . time()
        . ' where lid='
        . $_POST['lid']
        . ''
    ) or $eh::show('0013');

    $xoopsDB->query('update ' . $xoopsDB->prefix('addresses_text') . " set description='$description' where lid=" . $_POST['lid'] . '') or $eh::show('0013');

    redirect_header('index.php', 1, _MD_DBUPDATED);

    exit();
}

function delLink() //SHINE need to change later
{
    global $xoopsDB, $_GET, $eh, $xoopsModule;

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_links'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_text'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_votedata'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    // delete comments

    xoops_comment_delete($xoopsModule->getVar('mid'), $_GET['lid']);

    // delete notifications

    xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $_GET['lid']);

    redirect_header('index.php', 1, _MD_LINKDELETED); //SHINE need to change later

    exit();
}

function modCat()
{
    global $xoopsDB, $_POST, $myts, $eh, $mytree;

    $cid = $_POST['cid'];

    xoops_cp_header();

    echo '<h4>' . _MD_WEBLINKSCONF . '</h4>'; //SHINE need to change later

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr class="odd"><td>';

    echo '<h4>' . _MD_MODCAT . '</h4><br>';

    $result = $xoopsDB->query('select pid, title, imgurl from ' . $xoopsDB->prefix('addresses_cat') . " where cid=$cid");

    [$pid, $title, $imgurl] = $xoopsDB->fetchRow($result);

    $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);

    $imgurl = htmlspecialchars($imgurl, ENT_QUOTES | ENT_HTML5);

    echo '<form action=index.php method=post>' . _MD_TITLEC . "<input type=text name=title value=\"$title\" size=51 maxlength=50><br><br>" . _MD_IMGURLMAIN . "<br><input type=text name=imgurl value=\"$imgurl\" size=100 maxlength=150><br><br>";

    echo _MD_PARENT . '&nbsp;';

    $mytree->makeMySelBox('title', 'title', $pid, 1, 'pid');

    //	<input type=hidden name=pid value=\"$pid\">

    echo '<br><input type="hidden" name="cid" value="' . $cid . '">
	<input type="hidden" name="op" value="modCatS"><br>
	<input type="submit" value="' . _MD_SAVE . '">
	<input type="button" value="' . _MD_DELETE . "\" onClick=\"location='index.php?pid=$pid&amp;cid=$cid&amp;op=delCat'\">";

    echo '&nbsp;<input type="button" value="' . _MD_CANCEL . "\" onclick=\"javascript:history.go(-1)\ /\">";

    echo '</form>';

    echo '</td></tr></table>';

    xoops_cp_footer();
}

function modCatS()
{
    global $xoopsDB, $_POST, $myts, $eh;

    $cid = $_POST['cid'];

    $pid = $_POST['pid'];

    $title = $myts->addSlashes($_POST['title']);

    if (empty($title)) {
        redirect_header('index.php', 2, _MD_ERRORTITLE);
    }

    if (($_POST['imgurl']) || ('' != $_POST['imgurl'])) {
        $imgurl = $myts->addSlashes($_POST['imgurl']);
    }

    $xoopsDB->query('update ' . $xoopsDB->prefix('addresses_cat') . " set pid=$pid, title='$title', imgurl='$imgurl' where cid=$cid") or $eh::show('0013');

    redirect_header('index.php', 1, _MD_DBUPDATED);
}

function delCat()
{
    global $xoopsDB, $_GET, $_POST, $eh, $mytree, $xoopsModule;

    $cid = isset($_POST['cid']) ? (int)$_POST['cid'] : (int)$_GET['cid'];

    $ok = isset($_POST['ok']) ? (int)$_POST['ok'] : 0;

    if (1 == $ok) {
        //get all subcategories under the specified category

        $arr = $mytree->getAllChildId($cid);

        $dcount = count($arr);

        for ($i = 0; $i < $dcount; $i++) {
            //get all links in each subcategory

            $result = $xoopsDB->query('select lid from ' . $xoopsDB->prefix('addresses_links') . ' where cid=' . $arr[$i] . '') or $eh::show('0013');

            //now for each link, delete the text data and vote ata associated with the link

            while (list($lid) = $xoopsDB->fetchRow($result)) {
                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_text'), $lid);

                $xoopsDB->query($sql) or $eh::show('0013');

                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_votedata'), $lid);

                $xoopsDB->query($sql) or $eh::show('0013');

                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_links'), $lid);

                $xoopsDB->query($sql) or $eh::show('0013');

                xoops_comment_delete($xoopsModule->getVar('mid'), $lid);

                xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
            }

            xoops_notification_deltebyitem($xoopsModule->getVar('mid'), 'category', $arr[$i]);

            //all links for each subcategory is deleted, now delete the subcategory data

            $sql = sprintf('DELETE FROM %s WHERE cid = %u', $xoopsDB->prefix('addresses_cat'), $arr[$i]);

            $xoopsDB->query($sql) or $eh::show('0013');
        }

        //all subcategory and associated data are deleted, now delete category data and its associated data

        $result = $xoopsDB->query('select lid from ' . $xoopsDB->prefix('addresses_links') . ' where cid=' . $cid . '') or $eh::show('0013');

        while (list($lid) = $xoopsDB->fetchRow($result)) {
            $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_links'), $lid);

            $xoopsDB->query($sql) or $eh::show('0013');

            $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_text'), $lid);

            $xoopsDB->query($sql) or $eh::show('0013');

            $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_votedata'), $lid);

            $xoopsDB->query($sql) or $eh::show('0013');

            // delete comments

            xoops_comment_delete($xoopsModule->getVar('mid'), $lid);

            // delete notifications

            xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $lid);
        }

        $sql = sprintf('DELETE FROM %s WHERE cid = %u', $xoopsDB->prefix('addresses_cat'), $cid);

        $xoopsDB->query($sql) or $eh::show('0013');

        xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'category', $cid);

        redirect_header('index.php', 1, _MD_CATDELETED);

        exit();
    }  

    xoops_cp_header();

    xoops_confirm(['op' => 'delCat', 'cid' => $cid, 'ok' => 1], 'index.php', _MD_WARNING);

    xoops_cp_footer();
}

function delNewLink() //SHINE need to change later
{
    global $xoopsDB, $_GET, $eh, $xoopsModule;

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_links'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('addresses_text'), $_GET['lid']);

    $xoopsDB->query($sql) or $eh::show('0013');

    // delete comments

    xoops_comment_delete($xoopsModule->getVar('mid'), $_GET['lid']);

    // delete notifications

    xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'link', $_GET['lid']);

    redirect_header('index.php', 1, _MD_LINKDELETED);
}

function addCat()
{
    global $xoopsDB, $_POST, $myts, $eh;

    $pid = $_POST['cid'];

    $title = $myts->addSlashes($_POST['title']);

    if (empty($title)) {
        redirect_header('index.php', 2, _MD_ERRORTITLE);

        exit();
    }

    if (($_POST['imgurl']) || ('' != $_POST['imgurl'])) {
        //		$imgurl = $myts->formatURL($_POST["imgurl"]);

        //		$imgurl = urlencode($imgurl);

        $imgurl = $myts->addSlashes($_POST['imgurl']);
    }

    $newid = $xoopsDB->genId($xoopsDB->prefix('addresses_cat') . '_cid_seq');

    $sql = sprintf("INSERT INTO %s (cid, pid, title, imgurl) VALUES (%u, %u, '%s', '%s')", $xoopsDB->prefix('addresses_cat'), $newid, $pid, $title, $imgurl);

    $xoopsDB->query($sql) or $eh::show('0013');

    if (0 == $newid) {
        $newid = $xoopsDB->getInsertId();
    }

    global $xoopsModule;

    $tags = [];

    $tags['CATEGORY_NAME'] = $title;

    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newid;

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);

    redirect_header('index.php', 1, _MD_NEWCATADDED);
}

function addLink() //SHINE need to change later
{
    global $xoopsConfig, $xoopsDB, $myts, $xoopsUser, $xoopsModule, $eh, $_POST;

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //	$url=$myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']);

    $title = $myts->addSlashes($_POST['title']);

    $adress = $myts->addSlashes($_POST['adress']);

    $zip = $myts->addSlashes($_POST['zip']);

    $city = $myts->addSlashes($_POST['city']);

    $country = $myts->addSlashes($_POST['country']);

    $phone = $myts->addSlashes($_POST['phone']);

    $mobile = $myts->addSlashes($_POST['mobile']);

    $fax = $myts->addSlashes($_POST['fax']);

    $contemail = $myts->addSlashes($_POST['contemail']);

    $opentime = $myts->addSlashes($_POST['opentime']);

    $description = $myts->addSlashes($_POST['description']);

    $submitter = $xoopsUser->uid();

    $result = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_links') . " where url='$url'");

    [$numrows] = $xoopsDB->fetchRow($result);

    $errormsg = '';

    $error = 0;

    if ($numrows > 0) {
        $errormsg .= "<h4 style='color: #ff0000'>";

        $errormsg .= _MD_ERROREXIST . '</h4>';

        $error = 1;
    }

    // Check if Title exist

    if ('' == $title) {
        $errormsg .= "<h4 style='color: #ff0000'>";

        $errormsg .= _MD_ERRORTITLE . '</h4>';

        $error = 1;
    }

    // Check if Description exist

    if ('' == $description) {
        $errormsg .= "<h4 style='color: #ff0000'>";

        $errormsg .= _MD_ERRORDESC . '</h4>';

        $error = 1;
    }

    if (1 == $error) {
        xoops_cp_header();

        echo $errormsg;

        xoops_cp_footer();

        exit();
    }

    if (!empty($_POST['cid'])) {
        $cid = $_POST['cid'];
    } else {
        $cid = 0;
    }

    // SHINE: What do new fields get: %s or %u ?

    // Same see also lines 760

    // Did put own fields and gave them %s which correspondent with earlier lines

    // I think the error msg comes through this

    $newid = $xoopsDB->genId($xoopsDB->prefix('addresses_links') . '_lid_seq');

    $sql = sprintf(
        "INSERT INTO %s (lid, cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl, submitter, status, date, hits, rating, votes, comments) VALUES (%u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u, %u, %u, %u, %u, %u, %u)",
        $xoopsDB->prefix('addresses_links'),
        $newid,
        $cid,
        $title,
        $adress,
        $zip,
        $city,
        $country,
        $phone,
        $mobile,
        $fax,
        $contemail,
        $opentime,
        $url,
        $logourl,
        $submitter,
        1,
        time(),
        0,
        0,
        0,
        0
    );

    $xoopsDB->query($sql) or $eh::show('0013');

    if (0 == $newid) {
        $newid = $xoopsDB->getInsertId();
    }

    $sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix('addresses_text'), $newid, $description);

    $xoopsDB->query($sql) or $eh::show('0013');

    $tags = [];

    //SHINE: If so,then what should be put in below?

    $tags['LINK_NAME'] = $title;

    $tags['LINK_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&amp;lid=' . $newid;

    $sql = 'SELECT title FROM ' . $xoopsDB->prefix('addresses_cat') . ' WHERE cid=' . $cid;

    $result = $xoopsDB->query($sql);

    $row = $xoopsDB->fetchArray($result);

    $tags['CATEGORY_NAME'] = $row['title'];

    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'new_link', $tags);

    $notificationHandler->triggerEvent('category', $cid, 'new_link', $tags);

    redirect_header('index.php?op=linksConfigMenu', 1, _MD_NEWLINKADDED);
}

function approve()
{
    global $xoopsConfig, $xoopsDB, $_POST, $myts, $eh;

    $lid = $_POST['lid'];

    $title = $_POST['title'];

    $cid = $_POST['cid'];

    if (empty($cid)) {
        $cid = 0;
    }

    $description = $_POST['description'];

    if (($_POST['url']) || ('' != $_POST['url'])) {
        //		$url=$myts->formatURL($_POST["url"]);

        //		$url = urlencode($url);

        $url = $myts->addSlashes($_POST['url']);
    }

    $logourl = $myts->addSlashes($_POST['logourl']);

    $title = $myts->addSlashes($title);

    $adress = $myts->addSlashes($_POST['adress']);

    $zip = $myts->addSlashes($_POST['zip']);

    $city = $myts->addSlashes($_POST['city']);

    $country = $myts->addSlashes($_POST['country']);

    $phone = $myts->addSlashes($_POST['phone']);

    $mobile = $myts->addSlashes($_POST['mobile']);

    $fax = $myts->addSlashes($_POST['fax']);

    $contemail = $myts->addSlashes($_POST['contemail']);

    $opentime = $myts->addSlashes($_POST['opentime']);

    $description = $myts->addSlashes($description);

    $query = 'update '
                   . $xoopsDB->prefix('addresses_links')
                   . " set cid='$cid', title='$title', adress='$adress', zip='$zip', city='$city', country='$country', phone='$phone', mobile='$mobile', fax='$fax', contemail='$contemail', opentime='$opentime', url='$url', logourl='$logourl', status=1, date="
                   . time()
                   . ' where lid='
                   . $lid
                   . '';

    $xoopsDB->query($query) or $eh::show('0013');

    $query = 'update ' . $xoopsDB->prefix('addresses_text') . " set description='$description' where lid=" . $lid . '';

    $xoopsDB->query($query) or $eh::show('0013');

    global $xoopsModule;

    $tags = [];

    //SHINE: If so,then what should be put in below?

    $tags['LINK_NAME'] = $title;

    $tags['LINK_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&amp;lid=' . $lid;

    $sql = 'SELECT title FROM ' . $xoopsDB->prefix('addresses_cat') . ' WHERE cid=' . $cid;

    $result = $xoopsDB->query($sql);

    $row = $xoopsDB->fetchArray($result);

    $tags['CATEGORY_NAME'] = $row['title'];

    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'new_link', $tags);

    $notificationHandler->triggerEvent('category', $cid, 'new_link', $tags);

    $notificationHandler->triggerEvent('link', $lid, 'approve', $tags);

    redirect_header('index.php', 1, _MD_NEWLINKADDED);
}

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';
switch ($op) {
    case 'delNewLink':
        delNewLink();
        break;
    case 'approve':
        approve();
        break;
    case 'addCat':
        addCat();
        break;
    case 'addLink':
        addLink();
        break;
    case 'listBrokenLinks':
        listBrokenLinks();
        break;
    case 'delBrokenLinks':
        delBrokenLinks();
        break;
    case 'ignoreBrokenLinks':
        ignoreBrokenLinks();
        break;
    case 'listModReq':
        listModReq();
        break;
    case 'changeModReq':
        changeModReq();
        break;
    case 'ignoreModReq':
        ignoreModReq();
        break;
    case 'delCat':
        delCat();
        break;
    case 'modCat':
        modCat();
        break;
    case 'modCatS':
        modCatS();
        break;
    case 'modLink':
        modLink();
        break;
    case 'modLinkS':
        modLinkS();
        break;
    case 'delLink':
        delLink();
        break;
    case 'delVote':
        delVote();
        break;
    case 'linksConfigMenu':
        linksConfigMenu();
        break;
    case 'listNewLinks':
        listNewLinks();
        break;
    case 'main':
    default:
        addresses();
        break;
}
