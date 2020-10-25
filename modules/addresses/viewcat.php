<?php
// $Id: viewcat.php,v 1.12 2003/03/27 12:11:07 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
include 'header.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
$mytree = new XoopsTree($xoopsDB->prefix('addresses_cat'), 'cid', 'pid');

$cid = (int)$_GET['cid'];
$GLOBALS['xoopsOption']['template_main'] = 'addresses_viewcat.html';
require XOOPS_ROOT_PATH . '/header.php';

if (isset($_GET['show'])) {
    $show = (int)$_GET['show'];
} else {
    $show = $xoopsModuleConfig['perpage'];
}
$min = isset($_GET['min']) ? (int)$_GET['min'] : 0;
if (!isset($max)) {
    $max = $min + $show;
}
if (isset($_GET['orderby'])) {
    $orderby = convertorderbyin($_GET['orderby']);
} else {
    $orderby = 'title ASC';
}

$pathstring = "<a href='index.php'>" . _MD_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);
// get child category objects
$arr = [];
$arr = $mytree->getFirstChild($cid, 'title');
if (count($arr) > 0) {
    $scount = 1;

    foreach ($arr as $ele) {
        $sub_arr = [];

        $sub_arr = $mytree->getFirstChild($ele['cid'], 'title');

        $space = 0;

        $chcount = 0;

        $infercategories = '';

        foreach ($sub_arr as $sub_ele) {
            $chtitle = htmlspecialchars($sub_ele['title'], ENT_QUOTES | ENT_HTML5);

            if ($chcount > 5) {
                $infercategories .= '...';

                break;
            }

            if ($space > 0) {
                $infercategories .= ', ';
            }

            $infercategories .= '<a href="' . XOOPS_URL . '/modules/addresses/viewcat.php?cid=' . $sub_ele['cid'] . '">' . $chtitle . '</a>';

            $space++;

            $chcount++;
        }

        $xoopsTpl->append('subcategories', ['title' => htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5), 'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallinks' => getTotalItems($ele['cid'], 1), 'count' => $scount]);

        $scount++;
    }
}

if (1 == $xoopsModuleConfig['useshots']) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);

    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);

    $xoopsTpl->assign('show_screenshot', true);

    $xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
}

if (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $isadmin = true;
} else {
    $isadmin = false;
}
$fullcountresult = $xoopsDB->query('select count(*) from ' . $xoopsDB->prefix('addresses_links') . " where cid=$cid and status>0");
[$numrows] = $xoopsDB->fetchRow($fullcountresult);
$page_nav = '';
if ($numrows > 0) {
    $xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);

    $xoopsTpl->assign('lang_adress', _MD_ADRESS);

    $xoopsTpl->assign('lang_zip', _MD_ZIP);

    $xoopsTpl->assign('lang_city', _MD_CITY);

    $xoopsTpl->assign('lang_country', _MD_COUNTRY);

    $xoopsTpl->assign('lang_phone', _MD_PHONE);

    $xoopsTpl->assign('lang_fax', _MD_FAX);

    $xoopsTpl->assign('lang_mobile', _MD_MOBILE);

    $xoopsTpl->assign('lang_contemail', _MD_CONTEMAIL);

    $xoopsTpl->assign('lang_opened', _MD_OPENED);

    $xoopsTpl->assign('lang_viewmore', _MD_VIEWMORE);

    $xoopsTpl->assign('lang_lastupdate', _MD_LASTUPDATEC);

    $xoopsTpl->assign('lang_hits', _MD_HITSC);

    $xoopsTpl->assign('lang_rating', _MD_RATINGC);

    $xoopsTpl->assign('lang_ratethissite', _MD_RATETHISSITE);

    $xoopsTpl->assign('lang_reportbroken', _MD_REPORTBROKEN);

    $xoopsTpl->assign('lang_tellafriend', _MD_TELLAFRIEND);

    $xoopsTpl->assign('lang_modify', _MD_MODIFY);

    $xoopsTpl->assign('lang_category', _MD_CATEGORYC);

    $xoopsTpl->assign('lang_visit', _MD_VISIT);

    $xoopsTpl->assign('show_links', true);

    $xoopsTpl->assign('lang_comments', _COMMENTS);

    $sql = 'select l.lid, l.cid, l.title, l.adress, l.zip, l.city, l.country, l.phone, l.mobile, l.fax, l.contemail, l.opentime, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description from ' . $xoopsDB->prefix('addresses_links') . ' l, ' . $xoopsDB->prefix(
        'addresses_text'
    ) . " t where cid=$cid and l.lid=t.lid and status>0 order by $orderby";

    $result = $xoopsDB->query($sql, $show, $min);

    //if 2 or more items in result, show the sort menu

    if ($numrows > 1) {
        $xoopsTpl->assign('show_nav', true);

        $orderbyTrans = convertorderbytrans($orderby);

        $xoopsTpl->assign('lang_sortby', _MD_SORTBY);

        $xoopsTpl->assign('lang_title', _MD_TITLE);

        $xoopsTpl->assign('lang_date', _MD_DATE);

        $xoopsTpl->assign('lang_rating', _MD_RATING);

        $xoopsTpl->assign('lang_popularity', _MD_POPULARITY);

        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_CURSORTEDBY, convertorderbytrans($orderby)));
    }

    while (list($lid, $cid, $ltitle, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description) = $xoopsDB->fetchRow($result)) {
        if ($isadmin) {
            $adminlink = '<a href="' . XOOPS_URL . '/modules/addresses/admin/?op=modLink&amp;lid=' . $lid . '"><img src="' . XOOPS_URL . '/modules/addresses/images/editicon.gif" border="0" alt="' . _MD_EDITTHISLINK . '"></a>';
        } else {
            $adminlink = '';
        }

        if (1 == $votes) {
            $votestring = _MD_ONEVOTE;
        } else {
            $votestring = sprintf(_MD_NUMVOTES, $votes);
        }

        $path = $mytree->getPathFromId($cid, 'title');

        $path = mb_substr($path, 1);

        $path = str_replace('/', " <img src='" . XOOPS_URL . "/modules/addresses/images/arrow.gif' board='0' alt=''> ", $path);

        $new = newlinkgraphic($time, $status);

        $pop = popgraphic($hits);

        //JE moet hieronder nog aanpassen ivm tonen...

        $xoopsTpl->append(
            'links',
            [
                'id' => $lid,
                'cid' => $cid,
                'rating' => number_format($rating, 2),
                'title' => htmlspecialchars($ltitle, ENT_QUOTES | ENT_HTML5) . $new . $pop,
                'category' => $path,
                'logourl' => htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5),
                'adress' => htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5),
                'zip' => htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5),
                'city' => htmlspecialchars($city, ENT_QUOTES | ENT_HTML5),
                'country' => htmlspecialchars($country, ENT_QUOTES | ENT_HTML5),
                'phone' => htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5),
                'fax' => htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5),
                'mobile' => htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5),
                'contemail' => htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5),
                'opentime' => htmlspecialchars($opentime, ENT_QUOTES | ENT_HTML5),
                'updated' => formatTimestamp($time, 'm'),
                'description' => $myts->displayTarea($description, 0),
                'adminlink' => $adminlink,
                'hits' => $hits,
                'comments' => $comments,
                'votes' => $votestring,
                'mail_subject' => rawurlencode(sprintf(_MD_INTRESTLINK, $xoopsConfig['sitename'])),
                'mail_body' => rawurlencode(sprintf(_MD_INTLINKFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/addresses/singlelink.php?cid=' . $cid . '&amp;lid=' . $lid),
            ]
        );
    }

    $orderby = convertorderbyout($orderby);

    //Calculates how many pages exist.  Which page one should be on, etc...

    $linkpages = ceil($numrows / $show);

    //Page Numbering

    if (1 != $linkpages && 0 != $linkpages) {
        $cid = (int)$_GET['cid'];

        $prev = $min - $show;

        if ($prev >= 0) {
            $page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$prev&amp;orderby=$orderby&amp;show=$show'><b><u>&laquo;</u></b></a>&nbsp;";
        }

        $counter = 1;

        $currentpage = ($max / $show);

        while ($counter <= $linkpages) {
            $mintemp = ($show * $counter) - $show;

            if ($counter == $currentpage) {
                $page_nav .= "<b>($counter)</b>&nbsp;";
            } else {
                $page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$mintemp&amp;orderby=$orderby&amp;show=$show'>$counter</a>&nbsp;";
            }

            $counter++;
        }

        if ($numrows > $max) {
            $page_nav .= "<a href='viewcat.php?cid=$cid&amp;min=$max&amp;orderby=$orderby&amp;show=$show'>";

            $page_nav .= '<b><u>&raquo;</u></b></a>';
        }
    }
}
$xoopsTpl->assign('page_nav', $page_nav);
require XOOPS_ROOT_PATH . '/footer.php';
