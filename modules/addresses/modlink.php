<?php
// $Id: modlink.php,v 1.7 2003/03/27 12:11:07 w4z004 Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <https://www.xoops.org>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
include 'header.php';
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
$mytree = new XoopsTree($xoopsDB->prefix('addresses_cat'), 'cid', 'pid');

if (!empty($_POST['submit'])) {
    $eh = new ErrorHandler(); //ErrorHandler object

    if (empty($xoopsUser)) {
        redirect_header(XOOPS_URL . '/user.php', 2, _MD_MUSTREGFIRST);

        exit();
    }  

    $user = $xoopsUser->getVar('uid');

    $lid = (int)$_POST['lid'];

    // Check if Title exist

    if ('' == $_POST['title']) {
        $eh::show('1001');
    }

    // Check if URL exist

    // if ($_POST["url"]=="") {

    // $eh->show("1016");

    // }

    // Check if Description exist

    if ('' == $_POST['description']) {
        $eh::show('1008');
    }

    $url = $myts->addSlashes($_POST['url']);

    $logourl = $myts->addSlashes($_POST['logourl']);

    $cid = (int)$_POST['cid'];

    $title = $myts->addSlashes($_POST['title']);

    $description = $myts->addSlashes($_POST['description']);

    $adress = $myts->addSlashes($_POST['adress']);

    $zip = $myts->addSlashes($_POST['zip']);

    $city = $myts->addSlashes($_POST['city']);

    $country = $myts->addSlashes($_POST['country']);

    $phone = $myts->addSlashes($_POST['phone']);

    $mobile = $myts->addSlashes($_POST['mobile']);

    $fax = $myts->addSlashes($_POST['fax']);

    $contemail = $myts->addSlashes($_POST['contemail']);

    $opentime = $myts->addSlashes($_POST['opentime']);

    $newid = $xoopsDB->genId($xoopsDB->prefix('addresses_mod') . '_requestid_seq');

    // % INVOEGEN ANDERS WERKT MODIFICATIE VERZOEK NIET

    $sql = sprintf(
        "INSERT INTO %s (requestid, lid, cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl, description, modifysubmitter) VALUES (%u, %u, %u, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %u)",
        $xoopsDB->prefix('addresses_mod'),
        $newid,
        $lid,
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
        $description,
        $user
    );

    $xoopsDB->query($sql) or $eh::show('0013');

    $tags = [];

    $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listModReq';

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'link_modify', $tags);

    redirect_header('index.php', 2, _MD_THANKSFORINFO);

    exit();
}  
    $lid = (int)$_GET['lid'];
    if (empty($xoopsUser)) {
        redirect_header(XOOPS_URL . '/user.php', 2, _MD_MUSTREGFIRST);

        exit();
    }
    $GLOBALS['xoopsOption']['template_main'] = 'addresses_modlink.html';
    require XOOPS_ROOT_PATH . '/header.php';
    $result = $xoopsDB->query('select cid, title, adress, zip, city, country, phone, mobile, fax, contemail, opentime, url, logourl from ' . $xoopsDB->prefix('addresses_links') . " where lid=$lid and status>0");
    $xoopsTpl->assign('lang_requestmod', _MD_REQUESTMOD);
    [$cid, $title, $adress, $zip, $city, $country, $phone, $mobile, $fax, $contemail, $opentime, $url, $logourl] = $xoopsDB->fetchRow($result);
    $result2 = $xoopsDB->query('SELECT description FROM ' . $xoopsDB->prefix('addresses_text') . " WHERE lid=$lid");
    [$description] = $xoopsDB->fetchRow($result2);

    $xoopsTpl->assign(
        'link',
        [
            'id' => $lid,
            'rating' => number_format($rating, 2),
            'title' => htmlspecialchars($title, ENT_QUOTES | ENT_HTML5),
            'adress' => htmlspecialchars($adress, ENT_QUOTES | ENT_HTML5),
            'zip' => htmlspecialchars($zip, ENT_QUOTES | ENT_HTML5),
            'city' => htmlspecialchars($city, ENT_QUOTES | ENT_HTML5),
            'country' => htmlspecialchars($country, ENT_QUOTES | ENT_HTML5),
            'phone' => htmlspecialchars($phone, ENT_QUOTES | ENT_HTML5),
            'fax' => htmlspecialchars($fax, ENT_QUOTES | ENT_HTML5),
            'mobile' => htmlspecialchars($mobile, ENT_QUOTES | ENT_HTML5),
            'contemail' => htmlspecialchars($contemail, ENT_QUOTES | ENT_HTML5),
            'opentime' => htmlspecialchars($opentime, ENT_QUOTES | ENT_HTML5),
            'url' => htmlspecialchars($url, ENT_QUOTES | ENT_HTML5),
            '$logourl' => htmlspecialchars($logourl, ENT_QUOTES | ENT_HTML5),
            'updated' => formatTimestamp($time, 'm'),
            'description' => htmlspecialchars($description, ENT_QUOTES | ENT_HTML5),
            'adminlink' => $adminlink,
            'hits' => $hits,
            'votes' => $votestring,
        ]
    );

    $xoopsTpl->assign('lang_modify', _MD_MODLINK2);
    $xoopsTpl->assign('lang_linkid', _MD_LINKID);
    $xoopsTpl->assign('lang_sitetitle', _MD_SITETITLE);
    $xoopsTpl->assign('lang_adress', _MD_ADRESS);
    $xoopsTpl->assign('lang_zip', _MD_ZIP);
    $xoopsTpl->assign('lang_city', _MD_CITY);
    $xoopsTpl->assign('lang_country', _MD_COUNTRY);
    $xoopsTpl->assign('lang_phone', _MD_PHONE);
    $xoopsTpl->assign('lang_fax', _MD_FAX);
    $xoopsTpl->assign('lang_mobile', _MD_MOBILE);
    $xoopsTpl->assign('lang_contemail', _MD_CONTEMAIL);
    $xoopsTpl->assign('lang_opened', _MD_OPENED);
    $xoopsTpl->assign('lang_siteurl', _MD_SITEURL);
    $xoopsTpl->assign('lang_category', _MD_CATEGORYC);
    ob_start();
    $mytree->makeMySelBox('title', 'title', $cid);
    $selbox = ob_get_contents();
    ob_end_clean();
    $xoopsTpl->assign('category_selbox', $selbox);
    $xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
    $xoopsTpl->assign('lang_sendrequest', _MD_SENDREQUEST);
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    require XOOPS_ROOT_PATH . '/footer.php';

