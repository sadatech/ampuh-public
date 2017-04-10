var TableDatatablesEditable = function () {

    var handleTable = function () {

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            // console.log(aData);
            var jqTds = $('>td', nRow);

            var sts = '';
            var candidate = '';
            var desc = '';

            if (typeof aData.replacement.candidate !== 'undefined') {
                candidate = aData.replacement.candidate;
            }
            if (typeof aData.replacement.status !== 'undefined') {
                sts = aData.replacement.status;
            }
            if (typeof aData.replacement.description !== 'undefined') {
                desc = aData.replacement.description;
            }
            if( jqTds[11].innerHTML == 'Pending approval' ) {
                swal('Oppsss....', "BA currently waiting for approval can't be edited", 'error');
            }else{
                jqTds[10].innerHTML = '<input required type="date" class="form-control input-small" value="">';
                jqTds[11].innerHTML = '<select class="form-control input-small"><option value=""></option><option value="Lulus">Lulus</option><option value="Tidak Lulus">Tidak Lulus</option></select>';
                jqTds[12].innerHTML = '<input data-role="tagsinput" type="text" class="form-control input-small candidate bootstrap-tagsinput" value="' + candidate + '">';
                jqTds[13].innerHTML = '<input type="text" name="keterangan" class="form-control input-small" value="' + desc + '">';
                $('.candidate').tagsinput('refresh');
                jqTds[15].innerHTML = '<a class="btn blue edit" href="">Save</a>';
            }
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            var stsInterview = $('select', nRow);
            var jqTds = $('>td', nRow);
            console.log(jqInputs);
            let data = {
                'id': jqTds[0].innerHTML,
                'tanggal_interview': jqInputs[0].value,
                'status_interview': stsInterview[0].value,
                'nama_kandidat': jqInputs[2].value,
                'keterangan': jqInputs[3].value

            };
            if (data.nama_kandidat == '' && data.tanggal_interview != '') {
                swal("Candidate can't be null", "", 'error');
            }
            else {
                // console.log(data);
                $.post("/configuration/wip/update", data)
                    .done(function (result) {
                        if( result.progress == 'done' ) {
                            swal({
                                    title: "Are you want to input BA data ?",
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#DD6B55",
                                    confirmButtonText: "Yes",
                                    cancelButtonText: "No",
                                    closeOnConfirm: false
                                },
                                function(){
                                    window.location.replace("/master/ba/");
                                });
                        }
                    });


                jqTds[10].innerHTML = data.tanggal_interview;
                jqTds[11].innerHTML = data.status_interview;
                jqTds[12].innerHTML = data.nama_kandidat;
                jqTds[13].innerHTML = data.keterangan;
                jqTds[15].innerHTML = '<a href="javascript:;" class="btn green-meadow edit">Edit  </a>';
            }
        }

        var table = $('#wipTable');

        var oTable = table.dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '/configuration/wip/datatable',
                method: 'POST'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'store.store_name_1', name: 'store.store_name_1', class: 'namewrapper' },
                { data: 'store.city.region_id', name: 'store.city.region_id', class: 'namewrapper', "defaultContent": "" , orderable: false},
                { data: 'store.city.city_name', name: 'store.city.city_name', orderable: false, searchable: false, class: 'namewrapper'},
                { data: 'brand.name', name: 'brand.name', class: 'namewrapper', "defaultContent": "" },
                { data: 'store.account.name', name: 'store.account.name', class: 'namewrapper', "defaultContent": "" , orderable: false},
                { data: 'store.channel', name: 'store.channel', class: 'namewrapper', orderable: false },
                { data: 'status', name: 'status', class: 'namewrapper' },
                { data: 'filling_date', name: 'filling_date', class: 'namewrapper' },
                { data: 'effective_date', name: 'effective_date', class: 'namewrapper' },
                { data: 'replacement.interview_date', name: 'replacement.interview_date', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
                { data: 'replacement.status', name: 'replacement.status',orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
                { data: 'candidate', name: 'replacement.ba_replace.name', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
                { data: 'replacement.description', name: 'fullfield', orderable: false, searchable: false, class: 'namewrapper', "defaultContent": "" },
                { data: 'hc', name: 'hc', class: 'namewrapper', "defaultContent": "" , orderable: false, searchable: false},
                { data: 'edit', name: 'edit', searchable: false, sortable: false}
            ]

        });

        var nEditing = null;
        var nNew = false;
        table.on('click', '.edit', function (e) {
            e.preventDefault();
            nNew = false;

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];
            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                // restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == "Save") {
                /* Editing this row and want to save it */
                saveRow(oTable, nEditing);
                nEditing = null;
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }

        });
    };

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();

/*
 jQuery(document).ready(function() {
 TableDatatablesEditable.init();
 });*/

