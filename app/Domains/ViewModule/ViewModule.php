<?php

namespace App\Domains\ViewModule;


class ViewModule
{
    public const LOCATIONS = [
        '6001001000' => '台北市',
        '6001002000' => '新北市',
        '6001008000' => '台中市',
        '6001016000' => '高雄市',
    ];

    public function getLocationSelectBox()
    {
        return view('AwsHack/Module/locations', [
            'locations' => self::LOCATIONS
        ]);
    }
}
