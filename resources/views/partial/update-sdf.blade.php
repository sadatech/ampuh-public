<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">SDF Relations</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <h4></h4>

            <table width="100%" class="table table-hover table-bordered" id="sdfTableUpdate">
                <tr bgcolor="#f3f4f6">
                    <td width="1">No</td>
                    <td>No.SDF</td>
                    <td>Toko</td>
                    <td width="1">
                        <button class="btn green" data-toggle="modal" data-target="#tambahToko"><i class="fa fa-plus"></i></button>
                    </td>
                </tr>
                @foreach($sdf as $v)
                    <tr id="row-{{ $v->id }}">
                        <td class="no">{{ $loop->iteration }} </td>
                        <td>{{ $v->no_sdf }} </td>
                        <td>{{ $v->store->store_name_1 }} </td>
                        <td><button class="btn red" onclick="removeStore({{ $v->id }})"><i class="fa fa-remove"></i></button></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="tambahToko" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Tambah Toko</h4>
            </div>
            <div class="modal-body">
                <form action="#" class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group select2-bootstrap-prepend">
                                <span class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </span>
                                <select id="toko" class="form-control select2-allow-clear" style="width: 100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue" id="tambah">Tambah</button>
            </div>
        </div>
    </div>
</div>
<script>
    var token = '{{ $sdf->first()->token }}';
    function initSelect2() {
        $("#toko").select2({
            ajax: {
                url: "{{ route('availSDF') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        token: '{{ $sdf->first()->token }}'
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used

                    return {
                        results: $.map(data, function (obj) {
                            return {id: obj.id, text: obj.text}
                        })
                    }
                },
                cache: true
            },
        });
    }
    function removeStore(id) {
        var r = confirm("Hapus relasi SDF ?");
        if (r == true) {
            $.post( "{{ route('kurangTokoDiSDF') }}", { sdf: id })
                .done(function( data ) {
                    console.log( "Data Loaded: " + data );
                });
            $("#sdfTableUpdate #row-" + id).remove();
        }
    }
    $(document).ready(function() {
        initSelect2();
        $('#tambah').click(function() {
            $.post( "{{ route('tambahTokoDiSDF') }}", { token: token, sdf: $('#toko').val() })
                .done(function( data ) {
                    $('#tambahToko').modal('hide');
                    var last = parseInt($("#sdfTableUpdate tr:last td:first").text()) + 1;
                    $("#sdfTableUpdate tr:last").after("<tr><td>" + last + "</td><td>" + data.nosdf + "</td><td>" + data.store + "</td><td><button class='btn red' onclick='removeStore(" + data.id + ")'><i class='fa fa-remove'></i></button></td></tr>");
                });
        });


    });
</script>