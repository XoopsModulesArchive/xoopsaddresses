<?php
// comment callback functions

function addresses_com_update($link_id, $total_num)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $sql = 'UPDATE ' . $db->prefix('addresses_links') . ' SET comments = ' . $total_num . ' WHERE lid = ' . $link_id;

    $db->query($sql);
}

function addresses_com_approve(&$comment)
{
    // notification mail here
}
