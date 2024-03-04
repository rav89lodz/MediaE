<?php

namespace MediaExpert\Backend\mappers;

use MediaExpert\Backend\Core\Validator;
use MediaExpert\Backend\models\Search;

class SearchMapper
{
    public function mapRequest()
    {
        $search = new Search;
        
        $column = isset($_GET["column"]) ? Validator::getValue($_GET["column"]) : null;
        if(in_array($column, $search->available_columns))
        {
            if($column == $search->available_columns[0])
            {
                $column = "st.name";
            }
            else
            {
                $column = "it." . $column;
            }
        }
        else
        {
            $column = null;
        }
        

        $result = Validator::string($column);
        if($result === false)
        {
            $column = null;
        }

        $data = isset($_GET["data"]) ? Validator::getValue($_GET["data"]) : null;
        $result = Validator::string($data);
        if($result === false)
        {
            $data = null;
        }

        $search->setColumn($column);
        $search->setData($data);

        return $search;
    }
}