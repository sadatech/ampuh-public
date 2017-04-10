<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <title>L'oreal Apps</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Ba Exit Form"
          name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=all" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,300" rel="stylesheet">
    <style>
        .one{
            padding: 0px 100px 0px 0px;

        }

        .two{
            border: 1px solid black;
            width: 100px;
            text-align: left;
        }

        tr {
            border-spacing: 0px;
        }

        .three{
            border: 1px solid black;
            width: 100px;
            text-align: center;
            height: 100px;
        }
        table, th, td{
            width: 100%;
            border: 1px solid black;
        }

        td {
            padding: 0 40px;
            text-align: left;
        }

        tr {
            border-spacing: 15px;
        }

        .one{
            border: 0px;
        }

        .two{
            background-color: #cccccc;
        }
    </style>
</head>
<body>

<div class="page-container">
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <form>
            <table class="">
                <th colspan="2">
                    CONSUMER PRODUCT DIVISION
                </th>
                <tr>
                    <td class="one">Nama BA</td>
                    <td><input type="text" name="namaBa" size="100" value="{{ $resign->ba->name }}"> </td>
                </tr>
                <tr>
                    <td class="one">Nama Store</td>
                    <td><input type="text" name="NamaStore" size="100" value="{{ $resign->stores }}"> </td>
                </tr>
                <tr>
                    <td class="one">Brand</td>
                    <td><input type="text" name="Brand" size="100" value="{{ $resign->ba->brand->name }}"> </td>
                </tr>
                <tr>
                    <td class="one">Join Date</td>
                    <td><input type="date" name="JoinDate" value="{{ $resign->ba->join_date->format('d-M-y') }}"></td>
                </tr>
                <tr>
                    <td class="one">Tanggal Mengajukan Resign</td>
                    <td><input type="date" name="resign" value="{{ $resign->filling_date->format('d-M-y') }}"> </td>
                </tr>
                <tr>
                    <td class="one">Tanggal Efektif Resign</td>
                    <td><input type="date" name="JoinDate" value="{{ $resign->effective_date->format('d-M-y') }}"></td>
                </tr>
                <tr>
                    <td class="one">Alasan Resign</td>
                    <td><input type="text" name="alasan" size="100" value="{{ $resign->alasan }}"> </td>
                </tr>
                <tr>
                    <td class="one">Penjelasan</td>
                    <td><textarea rows="4" cols="101" name="penjelasan" >{{ $resign->resign_info or ' Tidak menambahkan keterangan ' }}</textarea></td>
                </tr>
                <tr>
                    <td class="one">Perlengkapan</td>
                </tr>
                <tr>
                    <td class="one">
                        <ol>
                            <li>Seragam + PIN</li>
                        </ol>
                    </td>
                    <td class="two"></td>
                    <td class="two"></td>
                    <td class="two"></td>
                    <td class="two"></td>
                </tr>
                <tr>
                    <td class="one">
                        <ol start="2">
                            <li>Handover dengan BA baru</li>
                        </ol>
                    </td>
                    <td><input type="text" name="handover" size="100"></td>
                </tr>
                <tr>
                    <td class="one">
                        <ol start="3">
                            <li>Administrasi Absen + CMB</li>
                        </ol>
                    </td>
                    <td><input type="text" name="adminis" size="100"></td>
                </tr>

                <tr>
                    <td class="one">Acknowledge by,</td>
                </tr>

                <tr>
                    <td class="two">REO</td>
                    <td class="two"></td>
                    <td class="two">Head Account Arina</td>
                </tr>
                <tr>
                    <td class="three" colspan="2"></td>
                    <td class="three" colspan=""></td>
                </tr>

                <tr>
                    <td class="one">Approved by,</td>
                </tr>
                <tr>
                    <td class="two" colspan="3">REM</td>
                </tr>
                <tr>
                    <td class="three" colspan="3"></td>
                </tr>

            </table>
        </form>
    </div>
    <!-- END CONTENT -->
</div>

</body>

</html>