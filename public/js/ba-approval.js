var FormEditable = function() {

    $.mockjaxSettings.responseTime = 500;
    var edited = [];
    var agencies = [];
    var log = function(settings, response) {
        edited.push(settings.data);
        $('#console').val(settings.data.name +': ' +settings.data.value +'\n'+ $('#console').val());
    }

    //fetch agency from database
    $.getJSON( "/master/agency?json", function( data ) {
        $.each( data, function( key, val ) {
            agencies.push({value: val.id, text: val.name});
        });
    });
    var initEditables = function() {


        //global settings
        $.fn.editable.defaults.inputclass = 'form-control';
        $.fn.editable.defaults.url = '/post';

        //editables element samples
        $('#nik, #no_hp, #no_ktp, #rekening, #total_uniform, #name, #description, #extra_keterangan').editable({
            pk: 1
        });

        $('#gender').editable({
            inputclass: 'form-control',
            source: [
                {value: 'female', text: 'Female'},
                {value: 'male', text: 'Male'}
            ]
        });
        $('#bank_name').editable({
            inputclass: 'form-control',
            source: [
                {value: 'mandiri', text: 'MANDIRI'},
                {value: 'bni', text: 'BNI'}
            ]
        });
        $('#uniform_size').editable({
            inputclass: 'form-control',
            name: 'uniform_size',
            source: [
                {value: 'S', text: 'S'},
                {value: 'M', text: 'M'},
                {value: 'L', text: 'L'},
                {value: 'XL', text: 'XL'},
                {value: 'XXL', text: 'XXL'},
                {value: 'XXXL', text: 'XXXL'}
            ]
        });
        $('#status').editable({
            inputclass: 'form-control',
            source: [
                {value: 'stay', text: 'Stay'},
                {value: 'mobile', text: 'Mobile'}
            ]
        });
        $('#edukasi').editable({
            inputclass: 'form-control',
            source: [
                {value: 'SD', text: 'SD'},
                {value: 'SLTP', text: 'SLTP'},
                {value: 'SLTA', text: 'SLTA'},
                {value: 'DIPLOMA', text: 'DIPLOMA'},
                {value: 'S1', text: 'S1'},
                {value: 'S2', text: 'S2'}
            ]
        });

        $('#agency_id').editable({
            inputclass: 'form-control',
            pk: 1,
            source: agencies

        });
        $('#join_date, #birth_date, #join_date_mds').editable({
            format: 'yyyy-mm-dd',
            viewformat: 'dd/mm/yyyy',
            datepicker: {
                weekStart: 1
            }
        });

    }

    var initAjaxMock = function() {
        //ajax mocks

        $.mockjax({
            url: '/post',
            response: function(settings) {
                log(settings, this);
            }
        });
    }

    return {
        //main function to initiate the module
        init: function() {

            // inii ajax simulation
            initAjaxMock();

            // init editable elements
            initEditables();

            // handle approve
            $('#approve').click(function() {

                swal({
                    title: 'Are you sure?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then(function() {
                    var id = window.location.pathname.split('/');
                    var isResign;
                    if (typeof id[5] !== 'undefined') {
                        isResign = id[5];
                    }
                    $.ajax({
                        url: "/master/ba/approved/" + id[4] + '?' + isResign,
                        type: 'POST',
                        dataType: "JSON",
                        data: edited,
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    swal({
                        title: 'BA Approved!',
                        type: 'success',
                    }).then(function() {
                        window.location.href = '/master/ba/approval';
                    });
                })

            });

            // handle approve
            $('#reject').click(function() {

                swal({
                    title: 'Are you sure?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, reject it!'
                }).then(function() {
                    var id = window.location.pathname.split('/');
                    $.ajax({
                        url: "/master/ba/reject/" + id[4],
                        type: 'POST',
                        dataType: "JSON",
                        data: id[4],
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    swal({
                        title: 'BA Rejected!',
                        type: 'success',
                    }).then(function() {
                        window.location.href = '/master/ba/approval';
                    });
                })

            });

            // init editable toggler
            $('#enable').click(function() {
                $('#user .editable').editable('toggleDisabled');
            });

            // init
            $('#inline').on('change', function(e) {
                if ($(this).is(':checked')) {
                    window.location.href = 'form_editable.html?mode=inline';
                } else {
                    window.location.href = 'form_editable.html';
                }
            });

            // handle editable elements on hidden event fired
            $('#user .editable').on('hidden', function(e, reason) {
                if (reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable');
                    if ($('#autoopen').is(':checked')) {
                        setTimeout(function() {
                            $next.editable('show');
                        }, 300);
                    } else {
                        $next.focus();
                    }
                }
            });


        }

    };

}();

jQuery(document).ready(function() {
    FormEditable.init();
});