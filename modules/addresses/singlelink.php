<?php
// $Id: singlelink.php,v 1.10 2003/03/27 12:11:07 w4z004 Exp $
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
$lid = (int)$_GET['lid'];
$cid = (int)$_GET['cid'];
$GLOBALS['xoopsOption']['template_main'] = 'addresses_singlelink.html';
require XOOPS_ROOT_PATH . '/header.php';

$result = $xoopsDB->query(
    'select l.lid, l.cid, l.title, l.adress, l.zip, l.city, l.country, l.phone, l.mobile, l.fax, l.contemail, l.opentime, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description from ' . $xoopsDB->prefix('addresses_links') . ' l, ' . $xoopsDB->prefix(
        'addresses_text'
    ) . " t where l.lid=$lid and l.lid=t.lid and status>0"
);
[$lid, $cid, $ltitle, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description] = $xoopsDB->fetchRow($result);

$pathstring = "<a href='index.php'>" . _MD_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$xoopsTpl->assign('category_path', $pathstring);

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $adminlink = '<a href="' . XOOPS_URL . '/modules/addresses/admin/?op=modLink&amp;lid=' . $lid . '"><img src="' . XOOPS_URL . '/modules/addresses/images/editicon.gif" border="0" alt="' . _MD_EDITTHISLINK . '"></a>';
} else {
    $adminlink = '';
}
if (1 == $votes) {
    $votestring = _MD_ONEVOTE;
} else {
    $votestring = sprintf(_MD_NUMVOTES, $votes);
}

if (1 == $xoopsModuleConfig['useshots']) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);

    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);

    $xoopsTpl->assign('show_screenshot', true);

    $xoopsTpl->assign('lang_noscreenshot', _MD_NOSHOTS);
}
$path = $mytree->getPathFromId($cid, 'title');
$path = mb_substr($path, 1);
$path = str_replace('/', " <img src='" . XOOPS_URL . "/modules/addresses/images/arrow.gif' board='0' alt=''> ", $path);
$new = newlinkgraphic($time, $status);
$pop = popgraphic($hits);
//JE moet hieronder nog aanpassen ivm tonen...
$xoopsTpl->assign(
    'link',
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
        'url' => htmlspecialchars($url, ENT_QUOTES | ENT_HTML5),
        'opentime' => $myts->displayTarea($opentime),
        'updated' => formatTimestamp($time, 'm'),
        'description' => $myts->displayTarea($description, 0),
        'adminlink' => $adminlink,
        'hits' => $hits,
        'votes' => $votestring,
        'comments' => $comments,
        'mail_subject' => rawurlencode(sprintf(_MD_INTRESTLINK, $xoopsConfig['sitename'])),
        'mail_body' => rawurlencode(sprintf(_MD_INTLINKFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/addresses/singlelink.php?lid=' . $lid),
    ]
);
$xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
$xoopsTpl->assign('lang_adress', _MD_ADRESS);
$xoopsTpl->assign('lang_zip', _MD_ZIP);
$xoopsTpl->assign('lang_city', _MD_CITY);
$xoopsTpl->assign('lang_country', _MD_COUNTRY);
$xoopsTpl->assign('lang_phone', _MD_PHONE);
$xoopsTpl->assign('lang_fax', _MD_FAX);
$xoopsTpl->assign('lang_mobile', _MD_MOBILE);
$xoopsTpl->assign('lang_contemail', _MD_CONTEMAIL);
$xoopsTpl->assign('lang_website', _MD_WEBSITE);
$xoopsTpl->assign('lang_opened', _MD_OPENED);
//$xoopsTpl->assign('lang_previous', _MD_PREVIOUS); // disabled
$xoopsTpl->assign('lang_lastupdate', _MD_LASTUPDATEC);
$xoopsTpl->assign('lang_hits', _MD_HITSC);
$xoopsTpl->assign('lang_rating', _MD_RATINGC);
$xoopsTpl->assign('lang_ratethissite', _MD_RATETHISSITE);
$xoopsTpl->assign('lang_reportbroken', _MD_REPORTBROKEN);
$xoopsTpl->assign('lang_tellafriend', _MD_TELLAFRIEND);
$xoopsTpl->assign('lang_modify', _MD_MODIFY);
$xoopsTpl->assign('lang_print', _MD_PRINT);  //toegevoegd
$xoopsTpl->assign('lang_category', _MD_CATEGORYC);
$xoopsTpl->assign('lang_visit', _MD_VISIT);
$xoopsTpl->assign('lang_comments', _COMMENTS);
require XOOPS_ROOT_PATH . '/include/comment_view.php';
require XOOPS_ROOT_PATH . '/footer.php';
