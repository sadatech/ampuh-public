<div id="modal-detail">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">SDF NO : {{ $sdf->no_sdf }}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h4></h4>
                <table width="100%" class="table table-hover table-bordered">
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 16px;"> Detail Toko </td>
                    </tr>
                    <tr>
                        <td>Nama Toko </td>
                        <td>{{ $sdf->store->store_name_1 }}</td>
                    </tr>
                    <tr>
                        <td>Customer Id </td>
                        <td>{{ $sdf->store->customer_id }}</td>
                    </tr>
                    <tr>
                        <td>Channel </td>
                        <td>{{ $sdf->store->channel }}</td>
                    </tr>
                    <tr>
                        <td>Account </td>
                        <td>{{ $sdf->store->account->name }}</td>
                    </tr>
                    <tr>
                        <td>Kota </td>
                        <td>{{ $sdf->store->city->city_name }}</td>
                    </tr>
                </table>
                <table width="100%" class="table table-hover table-bordered">
                    <tr>
                        <td colspan="4" style="text-align: center; font-weight: bold; font-size: 16px;">WIP pada toko {{ $sdf->store->store_name_1 }} </td>
                    </tr>
                    <tr bgcolor="#f5f5f5">
                        <td width="1">No </td>
                        <td>Nama BA </td>
                        <td>Brand </td>
                        <td>First Day </td>
                    </tr>
                    @foreach($sdf->wip as $wip)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ (isset($wip->ba->name)) ? $wip->ba->name : 'vacant' }}</td>
                            <td>{{ $wip->brand->name}}</td>
                            <td>{{ ($wip->effective_date->format('d-M-Y'))}}</td>
{{--                            <td>{{ ($wip->fullfield == 'hold') ? date('d F Y') : 'fullfield '}}</td>--}}
                        </tr>

                    @endforeach
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn default" data-dismiss="modal" id="close">Close</button>
        </div>
    </div>

