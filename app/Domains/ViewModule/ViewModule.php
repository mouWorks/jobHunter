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

    public const APPEAR_PAGE_COUNT = 7;

    public function getLocationSelectBox(string $selectedValue = '')
    {
        $selectedText = self::LOCATIONS[$selectedValue] ?? 'ALL';

        return view('AwsHack/Module/locations', [
            'locations' => self::LOCATIONS,
            'text' => $selectedText,
            'value' => $selectedValue,
        ]);
    }

    public function pagination(int $totalPage, int $inPage, string $url, array $params = [])
    {
        unset($params['page']);

        $queryString = http_build_query($params);

        $showPageNumber = [1];

        $firstPage = 1;
        $lastPage = $totalPage;

        if ($totalPage <= self::APPEAR_PAGE_COUNT) {
            // 總頁數小於顯示頁數 1 2 3 4 5 6
            $startPage = 2;
            $endPage = $totalPage - 1;
        } else if ($inPage < self::APPEAR_PAGE_COUNT) {
            // 當前頁數小於最大顯示頁數 1 2 3 4 5 6 .. 50
            $startPage = 2;
            $endPage = self::APPEAR_PAGE_COUNT - 1;
        } else if ($totalPage - $inPage + 1 < self::APPEAR_PAGE_COUNT) {
            // 總頁數剪當前頁數小於顯示頁數 1 .. 8 9 10 11 12 13
            $startPage = $totalPage - self::APPEAR_PAGE_COUNT + 2;
            $endPage = $totalPage - 1;
        } else if ($totalPage - $inPage + 1 >= self::APPEAR_PAGE_COUNT) {
            // 總頁數剪當前頁數大於顯示頁數 1 .. 8 9 10 11 12 .. 50
            $startPage = (int)($inPage - floor(self::APPEAR_PAGE_COUNT/2) + 1);
            $endPage = $startPage + self::APPEAR_PAGE_COUNT - 3;
        }

        for ($page = $startPage; $page <= $endPage; $page++) {
            $showPageNumber[] = $page;
        }

        $showPageNumber[] = $lastPage;

        if ($firstPage === $inPage) {
            $previousPage = 1;
        } else {
            $previousPage = $inPage - 1;
        }

        if ($lastPage === $inPage) {
            $nextPage = $lastPage;
        } else {
            $nextPage = $inPage + 1;
        }

        return view('AwsHack/Module/pagination', [
            'show_pages' => $showPageNumber,
            'previous_page' => $previousPage,
            'next_page' => $nextPage,
            'url' => $url . $queryString,
        ]);
    }
}
