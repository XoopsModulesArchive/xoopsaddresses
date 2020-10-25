<?php
// $Id: modinfo.php,v 1.12 2003/04/11 15:20:05 okazu Exp $
// Module Info

// The name of this module
define('_MI_MYADRESSES_NAME', 'Adressenboek');

// A brief description of this module
define('_MI_MYADRESSES_DESC', 'Creeer een adresboek/Gouden Gids sectie waar gebruikers adressen kunnen opzoeken, bekijken/beoordelen en inzenden.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_MYADRESSES_BNAME1', 'Recente Adressen');
define('_MI_MYADRESSES_BNAME2', 'Top Adressen');

// Sub menu titles
define('_MI_MYADRESSES_SMNAME1', 'Inzenden');
define('_MI_MYADRESSES_SMNAME2', 'Populair');
define('_MI_MYADRESSES_SMNAME3', 'Top Beoordelingen');

// Names of admin menu items
define('_MI_MYADRESSES_ADMENU2', 'Categorien/Adressen: Toevoegen/Editeren');
define('_MI_MYADRESSES_ADMENU3', 'Ingezonden Adressen');
define('_MI_MYADRESSES_ADMENU4', 'Gebroken Adreswebsites'); //niet actief in adresboek
define('_MI_MYADRESSES_ADMENU5', 'Adres Modificatie verzoeken');
define('_MI_MYADRESSES_ADMENU6', 'Adreslink Checker'); //niet actief

// Title of config items
define('_MI_MYADRESSES_POPULAR', 'Aantal views om populair te zijn');
define('_MI_MYADRESSES_NEWLINKS', 'Aantal adressen als nieuw op main pagina');
define('_MI_MYADRESSES_PERPAGE', 'Aantal adressen per Pagina');
define('_MI_MYADRESSES_USESHOTS', 'Wil je gebruik maken van tonen banner/logo mogelijkheid afbeelding bij een adres?');
//define('_MI_MYADRESSES_USEFRAMES', 'Adres tonen in een frame?'); niet mogelijk
define('_MI_MYADRESSES_SHOTWIDTH', 'Maximale breedte banner/logo afbeelding.<br>NB: Deze instelling zal niet van toepassing zijn in uitgebreide informatie adres!');
define('_MI_MYADRESSES_ANONPOST', 'Mogen anonieme gebruikers een adres inzenden?');
define('_MI_MYADRESSES_AUTOAPPROVE', 'Automatisch ingezonden adres goedkeuren zonder admin tussenkomst?');

// Description of each config items
define('_MI_MYADRESSES_POPULARDSC', '');
define('_MI_MYADRESSES_NEWLINKSDSC', '');
define('_MI_MYADRESSES_PERPAGEDSC', '');
define('_MI_MYADRESSES_USEFRAMEDSC', '');
define('_MI_MYADRESSES_USESHOTSDSC', '');
define('_MI_MYADRESSES_SHOTWIDTHDSC', '');
define('_MI_MYADRESSES_AUTOAPPROVEDSC', '');

// Text for notifications

define('_MI_MYADRESSES_GLOBAL_NOTIFY', 'Globaal');
define('_MI_MYADRESSES_GLOBAL_NOTIFYDSC', 'Globale adressen notificatie opties.');

define('_MI_MYADRESSES_CATEGORY_NOTIFY', 'Categorie');
define('_MI_MYADRESSES_CATEGORY_NOTIFYDSC', 'Notificatie opties die worden toegepast op de huidige categorie.');

define('_MI_MYADRESSES_LINK_NOTIFY', 'Adressen');
define('_MI_MYADRESSES_LINK_NOTIFYDSC', 'Notificatie opties die worden toegepast op het huidige adres.');

define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFY', 'Nieuwe Categorie aangemaakt');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notificeer me over alle nieuwe aangemaakte categorieen.');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Ontvang een notificatie van iedere nieuw aangemaakte categorie.');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Nieuwe categorie in adressen');

define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFY', 'Modificatie verzoek van een adres');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notificeer me over alle ingezonden adres modificatieverzoek.');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Ontvang een notificatie van alle ingezonden adres modificatieverzoek.');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Adres Modificatieverzoek');

define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFY', 'Gebroken Adres-weblink Ingezonden');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notificeer me over alle ingezonden gebroken Adres-weblink rapportage.');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Ontvang een notificatie van ieder ingezonden Gebroken adres-weblink rapportage.');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Gebroken Adres-weblink Gerapporteerd');

define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFY', 'Nieuw Adres ingezonden');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notificeer me over alle nieuw ingezonden adressen (nog wachtende op goedkeuring).');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Ontvang een notificatie van ieder nieuw ingezonden adres (nog wachtende op goedkeuring).');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Nieuw adres ingezonden');

define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFY', 'Nieuw adres geplaatst');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYCAP', 'Notificeer me over ieder nieuw geplaatst adres.');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYDSC', 'Ontvang een notificatie van ieder nieuw geplaatst adres.');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Nieuw adres geplaatst');

define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFY', 'Nieuw adres voor categorie ingezonden');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notificeer me over alle nieuw ingezonden adressen (nog wachtende op goedkeuring) in de huidige categorie.');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Ontvang een notificatie van ieder nieuw ingezonden adres (nog wachtende op goedkeuring) in de huidige categorie.');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Nieuw adres geplaats in de huidige categorie');

define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFY', 'Nieuw Adres in categorie geplaatst');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYCAP', 'Notificeer me over alle nieuw geplaatste adressen in de huidige categorie.');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYDSC', 'Ontvang een notificatie van ieder nieuw geplaatst adres in de huidige categorie.');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Nieuw adres geplaatst in de categorie');

define('_MI_MYADRESSES_LINK_APPROVE_NOTIFY', 'Adres Goedgekeurd');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYCAP', 'Notificeer me over ieder goedgekeurd adres.');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYDSC', 'Ontvang een notificatie wanneer dit adres is goedgekeurd.');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische-notificatie : Adres is goedgekeurd');
