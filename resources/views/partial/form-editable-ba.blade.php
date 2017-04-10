<script>
    var FormEditable = function () {

                $.mockjaxSettings.responseTime = 500;
            var edited = [];
            var agencies = [];
            var log = function (settings, response) {
                        edited.push(settings.data);
                        $('#console').val(settings.data.name + ': ' + settings.data.value + '\n' + $('#console').val());
                    }

                //fetch agency from database
                    $.getJSON("/master/agency?json", function (data) {
                            $.each(data, function (key, val) {
                                    agencies.push({value: val.id, text: val.name});
                                });
                        });

                var kelas = ['Silver', 'Gold', 'Platinum'];

                // sources buat class berdasarkan channel
                    var channel = $()

                var initEditables = function () {
                        //global settings
                            $.fn.editable.defaults.inputclass = 'form-control';
                        $.fn.editable.defaults.url = '/post';

                            //editables element samples
                                $('#nik, #no_hp, #no_ktp, #total_uniform, #name, #description, #extra_keterangan').editable({
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
                                ],
                                success: function (response, newValue) {
                                    if (newValue == 'bni' && $('#rekening').text().length != 10) {
                                            $('#msg').text("rekening number must 10 digit");
                                        } else if (newValue == 'mandiri' && $('#rekening').text().length != 13) {
                                            $('#msg').text("rekening number must 13 digit");
                                        } else {
                                            $('#msg').text("");
                                        }
                                    $('#msg').css("color", "#F44336");
                //                    userModel.set('username', newValue); //update backbone model
                                    }
                        });
                        $('#rekening').editable({
                                success: function (response, newValue) {
                                    if (newValue.length!=10 && $('#bank_name').text().trim() == "BNI") {
                                            $('#msg').text("rekening number must 10 digit");
                                        }else if(newValue.length!=13 && $('#bank_name').text().trim() =='MANDIRI'){
                                            $('#msg').text("rekening number must 13 digit");
                                        }else{
                                            $('#msg').text("");
                                        }
                                    $('#msg').css("color","#F44336");
                                }
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
                                    {value: 'SLTA', text: 'SLTA'},
                                    {value: 'DIPLOMA', text: 'DIPLOMA'},
                                    {value: 'S1', text: 'S1'}
                                ]
                        });

                            $('#agency_id').editable({
                                    inputclass: 'form-control',
                                pk: 1,
                                source: agencies

                        });
                        $('#class').editable({
                                inputclass: 'form-control',
                                pk: 1,
                                @if($channel == 'Dept Store' || $channel == 'Drug Store')
                                    source: [
                                    {value: 'Silver', text: 'Silver'},
                                    {value: 'Gold', text: 'Gold'},
                                    {value: 'Platinum', text: 'Platinum'}
                                ],
                                @else
                                    source: [
                                    {value: '1', text: '1'},
                                    {value: '2', text: '2'},
                                    {value: '3', text: '3'}
                                ],
                                @endif

                                });

                            $('#brand_id').editable({
                                    inputclass: 'form-control',
                                pk: 1,
                                source: [
                                    {value: '1', text: 'Cons'},
                                    {value: '2', text: 'Oap'},
                                    {value: '4', text: 'Gar'},
                                    {value: '5', text: 'Myb'},
                                    {value: '6', text: 'Mix'},
                                ]
                        });


                                $('#join_date, #birth_date, #join_date_mds').editable({
                                        format: 'yyyy-mm-dd',
                                viewformat: 'dd/mm/yyyy',
                                datepicker: {
                                    weekStart: 1
                                }
                        });

                        }

                var initAjaxMock = function () {
                        //ajax mocks

                                $.mockjax({
                                        url: '/post',
                                response: function (settings) {
                                    log(settings, this);
                                }
                        });
                    }

                return {
                    //main function to initiate the module
                    init: function () {

                            // init ajax simulation
                                initAjaxMock();

                            // init editable elements
                                initEditables();

                            // handle approve
                                $('#editBa').click(function () {
                    //                    console.log(data);
                                            swal({
                                                    title: 'Apa Anda Yakin untuk Input Data',
                                                type: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                confirmButtonText: 'Ya',
                                                cancelButtonText: 'Tidak'
                                        }).then(function () {
                                                const id = window.location.pathname.split('/');
                                                $.ajax({
                                                        url: "/master/ba/editData/" + id[4],
                                                        type: 'PUT',
                                                        dataType: "JSON",
                                                        data: edited,
                                                        success: function (data) {
                                                            console.log(data);

                                                            }
                                                });
                                                swal({
                                                        title: 'Data Ba telah Berhasil di Edit',
                                                        type: 'success',
                                                    }).then(function () {
                                                        window.location.href = '/master/ba';
                                                    });
                                            })

                                    });

                            // init editable toggler
                                $('#enable').click(function () {
                                        $('#user .editable').editable('toggleDisabled');
                                    });

                            // init
                                $('#inline').on('change', function (e) {
                                        if ($(this).is(':checked')) {
                                                window.location.href = 'form_editable.html?mode=inline';
                                            } else {
                                                window.location.href = 'form_editable.html';
                                            }
                                    });

                            // handle editable elements on hidden event fired
                                $('#user .editable').on('hidden', function (e, reason) {
                                        if (reason === 'save' || reason === 'nochange') {
                                                var $next = $(this).closest('tr').next().find('.editable');
                                                if ($('#autoopen').is(':checked')) {
                                                        setTimeout(function () {
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

        jQuery(document).ready(function () {
                FormEditable.init();
            });
</script>