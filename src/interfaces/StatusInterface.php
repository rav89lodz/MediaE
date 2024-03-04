<?php

namespace MediaExpert\Backend\interfaces;

use MediaExpert\Backend\models\Status;

interface StatusInterface
{
    function create(Status $status);
    function edit(Status $status);
}