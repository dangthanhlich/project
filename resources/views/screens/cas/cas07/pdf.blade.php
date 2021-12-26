<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
        * {
            font-family: simsum !important;
        }

        body {
            max-width: 800px;
            margin: 0px auto;
        }
        .container-pdf {
            width: 100%;
            margin: 0 auto;
            padding: 0 1rem;
            margin-bottom: 80px;
        }
        .h5-title {
            font-weight: bold;
            text-align: center;
            margin-top: 0px;
        }
        .table-are {
            margin-top: 0px;
        }
    
        .text-main {
            border: solid 2px black;
            border-radius: 24px;
            padding-left: 15px;
        }
        .text-main p, .table-bottom p{
            font-size: 10px;
        }
        .text-main p:nth-child(1) {
            font-size: 14px;
        }
        .desc {
            padding-left: 15px;
            margin-top: 0px;
        }


        .table-content {
            width: 100%;
        }

        .left-content, .right-content {
            width: 50%;
        }

        .tableMain {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .tableRow {
            display: table-row;
        }

        .tableCell,
        .tableHead {
            border-top: 2px solid black;
            border-left: 2px solid black;
            display: table-cell;
        }

        .tableCell-left {
            width: 60%;
            padding-left: 20px;
            max-width: 250px;
        }

        .tableCell-right {
            width: 40%;
            text-align: center;
            max-width: 490px;
            word-wrap: break-word;
        }

        .tableHeading {
            display: table-header-group;
            font-weight: bold;
        }

        .tableBody {
            display: table-row-group;
        }

        .table-cell-last {
            border-right: 2px solid black;
        }

        .border-right {
            border-right: 2px solid black;
        }

        .border-bottom {
            border-bottom: 2px solid black;
        }
        .border-top-none {
            border-top: none;
        }

        .border-bottom-none {
            border-bottom: none !important;
        }
        .height-60 {
            height: 60px;
        }
        .height-30 {
            height: 30px;
            line-height: 30px;
        }

        .vertical-align-top {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container-pdf">
        <h3 class="h5-title">
            <u>エアバッグ類(産業廃棄物)収集・運搬委託契約書</u>
        </h3>
    

        <div class="tableMain">
            <div class="tableBody">
                <div class="tableRow">
                    <div class="tableCell tableCell-left">解体業者（受託者)</div>
                    <div class="tableCell tableCell-right table-cell-last">引渡日</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom-none"></div>
                    <div class="tableCell tableCell-right table-cell-last">{{ $contract->contract_date_format }}</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom-none vertical-align-top">事業所名：{{ $contract->contract_office_name_1 ?? null }}</div>
                    <div class="tableCell table-cell-last tableCell-right border-bottom-none">サイン</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom"><br/>住所：{{ $contract->contract_office_address_1 ?? null }}</div>
                    <div class="tableCell table-cell-last tableCell-right border-bottom height-60">{{ $contract->sign_scrapper }}</div>
                </div>
            </div>
        </div>

        <div class="tableMain">
            <div class="tableBody">
                <div class="tableRow">
                    <div class="tableCell tableCell-left tableCell-left">運搬ネットワーク業者（受託者）</div>
                    <div class="tableCell tableCell-right table-cell-last">受取日</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom-none"></div>
                    <div class="tableCell tableCell-right table-cell-last">事業所名：{{ $contract->contract_office_name_2 ?? null }}</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom-none vertical-align-top">事業所名：{{ $contract->contract_office_name_2 ?? null }}</div>
                    <div class="tableCell table-cell-last tableCell-right border-bottom-none">サイン</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom"><br/>住所：{{ $contract->contract_office_address_2 ?? null }}</div>
                    <div class="tableCell table-cell-last tableCell-right border-bottom height-60">{{ $contract->sign_tr_1 }}</div>
                </div>
            </div>
        </div>

        <div class="tableMain">
            <div class="tableBody">
                <div class="tableRow">
                    <div class="tableCell tableCell-left tableCell-left">指定引取場所（搬入先</div>
                    <div class="tableCell tableCell-right table-cell-last"></div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom-none">事業所名：{{ $contract->contract_office_name_3 }}</div>
                    <div class="tableCell tableCell-right table-cell-last border-top-none"></div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-top-none border-bottom vertical-align-top">住所：{{ $contract->contract_office_address_3 }}</div>
                    <div class="tableCell table-cell-last tableCell-right border-bottom border-top-none"></div>
                </div>
            </div>
        </div>

        <div class="tableMain" style="margin-bottom: 0px;">
            <div class="tableBody">
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-right">ケース番号：{{ $contract->contract_case_no }}</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-right border-top-none border-bottom-none">荷姿ID：{{ $contract->case_id }}</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-right border-top-none border-bottom-none vertical-align-top">車台数：{{ $contract->contract_qty }}</div>
                </div>
                <div class="tableRow">
                    <div class="tableCell tableCell-left border-right border-top-none border-bottom vertical-align-top">運搬料金（税抜）：\{{ $contract->contract_price }}</div>
                </div>
            </div>
        </div>

        <div class="table-are">
            <div class="table desc">
                <p>産業廃棄物の種類：{{ $contract->contract_type }}（金属くず・廃プラスチック類）</p>
                <p>許可の範囲：{{ $contract->contract_scope }}</p>
                <p>契約の有効期間：{{ $contract->contract_period }}</p>
            </div>

            <div class="table" style="margin-top: 0px;">
               <div class="text-main" style="margin-top: -10px;">
                   <p>ご注意</p>
                   <p>運搬ネットワーク業者にて行う輸送は、自動車製造車等が定める引取基準に合致したエアバッグ類に限ります。</p>
                   <p>エアバッグ類以外の品物や引取基準に合致しないエアバッグ類のお取り扱いはできません。</p>
                   <p>また、輸送後の指定引取場所にてお預かりした品物がエアバッグ類以外の品物や引取基準に合致しないエアバッグ類で</p>
                   <p>あることが確認され、引取を拒否された場合が、この契約は成立しなかったこととします。</p>
                   <p>尚、その他は『エアバッグ類運搬ネットワーク利用規約』によります。</p>
               </div>
            </div>

            <div class="table table-bottom">
               <p>※『札樽自動車運輸・カネシマメタル・岡山県貨物運送』の運搬NW業者をご利用の方は、下記URLから積替保管場所等の
                詳細をご確認ください</p>
                <p>URL：○○○○</p>
             </div>
        </div>
    </div>
</body>
</html>