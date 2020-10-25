<?php
// $Id: submit.php,v 1.12 2003/03/27 12:11:07 w4z004 Exp $
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
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

$eh = new ErrorHandler(); //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix('addresses_cat'), 'cid', 'pid');
//Catz edit..... Changed to !is_object rather than using !$xoopsUser
if (!is_object($xoopsUser) && !$xoopsModuleConfig['anonpost']) {
    redirect_header(XOOPS_URL . '/user.php', 2, _MD_MUSTREGFIRST);

    exit();
}
//End Catz edit

if (!empty($_POST['submit'])) {
    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

    // RMV - why store submitter on form??

    //if (!$_POST['submitter'] and $xoopsUser) {

    //   $submitter = $xoopsUser->uid();

    //}elseif(!$_POST['submitter'] and !$xoopsUser) {

    //	$submitter = 0;

    //}else{

    //	$submitter = intval($_POST['submitter']);

    //}

    // Check if Title exist

    if ('' == $_POST['title']) {
        $eh::show('1001');
    }

    // Check if URL exist for adresses

    // SHINE: BECAUSE URL ISN'T IMPORTANT WITHIN ADRESSES THIS FEATURE IS DISABLED

    //$url = $_POST["url"];

    //if ($url=="" || !isset($url)) {

    //   	$eh->show("1016");

    //}

    // Check if Description exist

    if ('' == $_POST['message']) {
        $eh::show('1008');
    }

    $notify = !empty($_POST['notify']) ? 1 : 0;

    if (!empty($_POST['cid'])) {
        $cid = (int)$_POST['cid'];
    } else {
        $cid = 0;
    }

    //Catz edit....Replaced URL back into form submit

    // Shine: url was already there just not within template submitform. disabled this

    //$url = urlencode($url);

    //$url = $myts->addSlashes($url);

    //stop

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

    $url = $myts->addSlashes($url);

    $description = $myts->addSlashes($_POST['message']);

    $date = time();

    $newid = $xoopsDB->genId($xoopsDB->prefix('addresses_links') . '_lid_seq');

    if (1 == $xoopsModuleConfig['autoapprove']) {
        // RMV-FIXME: shouldn't this be 'APPROVE' or something (also in mydl)?

        $status = $xoopsModuleConfig['autoapprove'];
    } else {
        $status = 0;
    }

    //SHINE: ENTERED %s same as within admin/index.php

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
        ' ',
        $submitter,
        $status,
        $date,
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

    // RMV-NEW

    // Notify of new address (anywhere) and new addres in category.

    $notificationHandler = xoops_getHandler('notification');

    $tags = [];

    $tags['LINK_NAME'] = $title;

    $tags['LINK_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlelink.php?cid=' . $cid . '&lid=' . $newid;

    $sql = 'SELECT title FROM ' . $xoopsDB->prefix('addresses_cat') . ' WHERE cid=' . $cid;

    $result = $xoopsDB->query($sql);

    $row = $xoopsDB->fetchArray($result);

    $tags['CATEGORY_NAME'] = $row['title'];

    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;

    if (1 == $xoopsModuleConfig['autoapprove']) {
        $notificationHandler->triggerEvent('global', 0, 'new_link', $tags);

        $notificationHandler->triggerEvent('category', $cid, 'new_link', $tags);

        redirect_header('index.php', 2, _MD_RECEIVED . '<br>' . _MD_ISAPPROVED . '');
    } else {
        $tags['WAITINGLINKS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewLinks';

        $notificationHandler->triggerEvent('global', 0, 'link_submit', $tags);

        $notificationHandler->triggerEvent('category', $cid, 'link_submit', $tags);

        if ($notify) {
            require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';

            $notificationHandler->subscribe('link', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
        }

        redirect_header('index.php', 2, _MD_RECEIVED);
    }

    exit();
}  
    $GLOBALS['xoopsOption']['template_main'] = 'addresses_submit.html';
    require XOOPS_ROOT_PATH . '/header.php';
    ob_start();
    xoopsCodeTarea('message', 37, 8);
    $xoopsTpl->assign('xoops_codes', ob_get_contents());
    ob_end_clean();
    ob_start();
    xoopsSmilies('message');
    $xoopsTpl->assign('xoops_smilies', ob_get_contents());
    ob_end_clean();
    $xoopsTpl->assign('notify_show', !empty($xoopsUser) && !$xoopsModuleConfig['autoapprove'] ? 1 : 0);
    $xoopsTpl->assign('lang_submitonce', _MD_SUBMITONCE);
    $xoopsTpl->assign('lang_submitlinkh', _MD_SUBMITLINKHEAD);
    $xoopsTpl->assign('lang_allpending', _MD_ALLPENDING);
    $xoopsTpl->assign('lang_dontabuse', _MD_DONTABUSE);
    $xoopsTpl->assign('lang_wetakeshot', _MD_TAKESHOT);
    $xoopsTpl->assign('lang_bannertise', _MD_BANNERTISE);
    $xoopsTpl->assign('lang_sitetitle', _MD_SITETITLE);
    $xoopsTpl->assign('lang_adress', _MD_ADRESS);
    $xoopsTpl->assign('lang_zip', _MD_ZIP);
    $xoopsTpl->assign('lang_city', _MD_CITY);
    $xoopsTpl->assign('lang_country', _MD_COUNTRY);
    $xoopsTpl->assign('lang_phone', _MD_PHONE);
    $xoopsTpl->assign('lang_mobile', _MD_MOBILE);
    $xoopsTpl->assign('lang_fax', _MD_FAX);
    $xoopsTpl->assign('lang_contemail', _MD_CONTEMAIL);
    $xoopsTpl->assign('lang_opened', _MD_OPENED);
    $xoopsTpl->assign('lang_siteurl', _MD_SITEURL);
    $xoopsTpl->assign('lang_category', _MD_CATEGORYC);
    $xoopsTpl->assign('lang_options', _MD_OPTIONS);
    $xoopsTpl->assign('lang_notify', _MD_NOTIFYAPPROVE);
    $xoopsTpl->assign('lang_description', _MD_DESCRIPTIONC);
    $xoopsTpl->assign('lang_submit', _SUBMIT);
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    ob_start();
    $mytree->makeMySelBox('title', 'title', 0, 1);
    $selbox = ob_get_contents();
    ob_end_clean();
    $xoopsTpl->assign('category_selbox', $selbox);
    require XOOPS_ROOT_PATH . '/footer.php';

