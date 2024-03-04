<?php

namespace MediaExpert\Backend\interfaces;

use MediaExpert\Backend\models\Item;
use MediaExpert\Backend\models\Search;

interface ItemInterface
{
    function create(Item $item);
    function edit(Item $item);
    function searchBy(Search $search);
    function getItemHisory($id);
}