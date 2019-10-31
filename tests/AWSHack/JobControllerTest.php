<?php


use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Domains\Product\Services\BackupEventService;

class JobControllerTest extends TestCase
{
//    public function testJobController104JobSuccess()
//    {
//        // arrange
//        $this->withoutMiddleware();
//
//        // act
//        $response = $this->get(route('awshack.job.show', ['source' => '104']));
//
//        // assert
//        $response->assertResponseOk();
//    }

//    public function testJob104()
//    {
//        // arrange
//        /** @var Job104 $job */
//        $job = $this->app->make(Job104::class);
//
//        // act
//        $conditions = [
//            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
//            'area' => ['6001001000', '6001002000'],
//            'role' => [1, 4],
//            'exp'  => 7,
//            'kws'  => 'php python',
//            'kwop' => 3,
//        ];
//        $result = $job->get_jobs($conditions);
////        $result = $job->testAWS();
//
//        // assert
//        dd($result);
//    }

    public function testJobPtt()
    {
//        $descript = '</br>公司名稱，統編(中華民國以外註冊可免填):</br>財報狗資訊股份有限公司 53754983</br>公司地址(填寫詳細至號):</br>台北市大安區羅斯福路3段241號9樓之1，捷運台電大樓附近。</br>職缺:</br>資深前端工程師</br>職缺能力經歷要求:</br>必要能力</br>- 熟悉 Vue / React / Angular 至少其中一個 framework (我們目前使用 Vue)，並能獨立完成專案開發</br>- 熟悉 HTML5、CSS3、ES6</br>- 具備開發 unit test、integration test、和 end-to-end test 能力</br>- 熟悉 Git，理解 Git flow 或 Github flow</br>加分但非必要能力</br>- 有參與敏捷開發流程經驗</br>- 有參與 A/B test 產品優化經驗</br>- 具備 RWD 開發經驗</br>- 有開發 mobile APP 經驗，語言不限 (js、React Native、swift、Object C、java ...都可)</br>- 曾在任何一個領域達到頂尖的水準。（我們團隊有投資、魔術、電玩頂尖高手）</br>工作內容</br>1. 根據設計師制定的 UI / UI flow 規格，將高擬真 mockup / prototype，透過 html / css / js code 轉換為實際 web 產品外觀與互動介面</br>2. 與後端工程師共同制定 API 規格，並使用 API 取得數據，將數據正確的呈現於 web 介面上</br>3. 參與 A/B test 實驗，根據行銷和設計要求，開發實驗組與對照組不同 web 畫面</br>4. 優化程式碼執行效能，提升使用者互動體驗</br>5. 撰寫 unit / integration 測試，確保前端程式碼正確性</br>6. 實現與維護前端的持續整合與佈署 (CI/CD)</br>員工是否需自備工具? (是/否) :</br>否，公司全額補助設計軟/硬體設備</br>薪資(月薪):</br>7.2~10.7 萬，保障 14 個月(年薪 100-150 萬)</br>薪資(保證最低年薪，必填項目):</br>14 個月</br>年終獎金計算方式: </br>全薪計算</br>工時:</br>8小時</br>每日工作時間: 除週三早上需要到辦公室開會以外，其餘時間可以自行安排上班時間</br>每週工作時間: 8*5=40</br>加班費制度：</br>依台灣勞基法</br>工作環境與該職缺團隊介紹:</br>辦公室環境簡陋，不過書很多</br>其他</br>目前主要開發團隊主要共 6 人，4 位開發者、1 位行銷和 1 位設計。</br>以下是從團隊成員角度看到的優缺點</br>優點</br>1. data-driven 的決策方式，當有新的功能/想法時， 團隊會根據數據、A/B test、使用者訪談的結果來決定否採納， 而不是誰說了算。每個人都有高度機會對產品方向帶來影響力</br>2. 就事論事的團隊文化不是口號，是真的能做到有想法就直說完全不用擔心得罪人。</br>3. 尊重每個人對時程的評估，不會要求你在不合理的時程內交出成品。</br>4. 團隊成員皆為主動學習型的人，成員之間經常交流工作/非工作的知識。</br>5. 公司書很多，同事書也很多，適合喜歡看書的人</br>6. 喜歡站著工作的人可以買升降台/升降桌</br>7. 時間超級彈性，除了開會之外，沒有硬性上下班時間， 可以自由安排自己喜歡的時段工作。</br>8. 團隊投資能力不錯，對投資有興趣的話，這裡不會讓你失望</br>缺點</br>1. 由於快速迭代要求，程式碼品質較髒。不適合總是對程式碼有潔癖的人</br>2. 前端團隊規模小，開發、運營的自動化還有很大改善空間</br>3. 前端團隊規模小，目前沒有任何設計規範與文件</br>4. 團隊人數少，遇到問題不見得找的到對那方面熟悉的人可以討論。</br>5. 沒有嚴謹的 code review 流程（新人階段會有，但脫離新人階段後通常需要自己主動提出 code review 需求）。</br>6. 現行辦公室較為簡陋，短期內也不會投入太多資源在這一塊</br>7. 零食不是即時補充，不適合喜歡零食飲料吃到飽的人</br>工作福利:</br>- 工作用筆電、電腦、相關軟體全額補助</br>- 參加國內設計圈相關 conference 全額補助</br>- 彈性的休假</br>- 每月全額補助購買一本書</br>- 不定期員工聚餐</br>公司分紅與獎金:</br>按照獲利與表現分配，至少有兩個月</br>公司介紹:</br>財報狗（https://statementdog.com）為股市投資數據與分析平台，</br>目前會員數已超過 38 萬人，數據庫正逐漸從台灣延伸至國外。</br>我們的使命：協助投資人做出更好的投資決策，享受更好的生活。</br>如果你也認同我們的理念，那我們急需你這樣的夥伴加入，</br>協助我們開發出更棒的投資應用。</br>徵才聯絡方式:</br>1. 請連上此網頁 https://statementdog.breezy.hr/p/804f98c894ed-</br>2. 點擊網頁中「Apply To Position」</br>3. 根據「Apply To Position」網頁中的指示，填寫你的相關資訊與履歷後， 完成履歷提交上傳</br>4. 第一次線上面談：收到履歷後一週內，我們會主動通知是否與您約時間進行第一次面談</br>5. 第二次線上面談：第一次面談若通過，會在結束後三天內通知是否與您約第二次面談</br>6. 第三次現場面談：第二次面談若通過，會在兩週內與您約時間進行第三次現場面試</br>7. 第三次面試結束後一週內通知面試是否通過</br>履歷內容注意事項</br>- 附上您的個人履歷（或是任何你想要秀給我們瞧瞧的東西）， 同時分享你覺得很有挑戰性，普遍一般人不懂，但你有深刻專業認識的一件事情，讓我</br>們可以更認識你</br>- 我們尊重您的隱私，個人資訊僅需提供姓名、email、電話即可， 不需附照片或地址。若確定您的 email 不會漏收信， 在約面試前不提供電話也沒關係。</br>- 不需附長篇自傳，我們重視的是能展示你的專業能力、學習能力、 協作能力的資訊和作品</br>- 有中文履歷的話上傳中文履歷就好，我們沒有比較偏好英文履歷。</br>補充</br>我們認為徵才或求職是對等的關係</br>我們花很多心力選擇適合團隊的優秀人才加入，</br>求職者也是花很多時間研究選擇適合的公司，</br>因此我們儘可能提供完整的資訊給求職者參考，降低雙方誤解與浪費時間的可能性</br>想了解更多我們公司，歡迎到職缺網頁瀏覽完整資訊：</br>https://statementdog.breezy.hr/p/804f98c894ed-</br>也可以參考下面資源：</br>- 給新人的公司手冊：https://bit.ly/2Eh2pR2</br>- Our tech talk - Coscup 2018 — 火焰圖：分析效能瓶頸不可或缺的好幫手： - https://www.youtube.com/watch?v=eMb88USvAoQ</br> - Vim 讓你寫 Ruby 的速度飛起來： - https://www.slideshare.net/ChrisHoung/vim-ruby-87478331 - RubyConf Taiwan 2016 — 利用 Sidekiq Enterprise 打造高效率與高可靠度的爬蟲系統 - https://www.youtube.com/watch?v=iNyfyD_33vQ</br>- Our open source project - vim-material https://github.com/hzchirs/vim-material - tappay-ruby https://github.com/hzchirs/tappay-ruby - easy_ab https://github.com/icarus4/easy_ab</br>- Our blog：https://medium.com/statementdog-engineering (推薦觀看)</br>如果有其他疑問或想瞭解更多資訊，歡迎推文或寄站內信給我</br>--</br>';
//
//        if (empty($descript)) {
//            return[];
//        }
//        if (!preg_match('/薪資(.{120})/', $descript, $matches)) {
//            return[];
//        }
//
//        $descript = $matches[1];
//
//        $descript = str_replace(' ', '', $descript);
//        $descript = preg_replace('/\s(?=\s)/', '', $descript);
//        $descript = str_replace(',', '', $descript);
//        $descript = str_replace('K', '000', $descript);
//        $descript = str_replace('k', '000', $descript);
//        $descript = str_replace('$', '', $descript);
//        $descript = str_replace('up', '', $descript);
//        $descript = str_replace('UP', '', $descript);
//
//        $min_salary_pattern = '/\D*(\d*\.\d*|\d*).*<\/br>/';
//        $max_salary_pattern = '/\D*\d*[-|~](\d*\.\d*|\d*).*<\/br>/';
//
//        $min_match = [];
//        $max_match = [];
//
//        preg_match($min_salary_pattern, $descript, $min_match);
//        preg_match($max_salary_pattern, $descript, $max_match);
//
//        $min_salary = $min_match[1] ?? 0;
//        $max_salary = $max_match[1] ?? 0;
//
//        dd([$min_salary, $max_salary]);

        // arrange
        /** @var JobPtt $job */
        $job = $this->app->make(JobPtt::class);

        // act
        $result = $job->update_aws();
//        $result = $job->testAWS();

        // assert
        dd($result);
    }
}
