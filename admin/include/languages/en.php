<?php
    function lang( $phrase ){
        static $lang=array(
            "HOME_ADMIN" => "HOME",
            "Categories" => "Categories",
            "Items" => "Items",
            "Members" => "Members",
            "Comments"=>"Comments",
            "Statistics" => "Statistics",
            "Logs" => "Logs"

        );
        return $lang[$phrase];
    }