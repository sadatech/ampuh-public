var FormValidationMd = function() {

    var handleValidation1 = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#frmTasks');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {
                payment: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                },
                'checkboxes1[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                },
                'checkboxes2[]': {
                    required: 'Please check some options',
                    minlength: jQuery.validator.format("At least {0} items must be selected"),
                }
            },
            rules: {
                store_no: {
                    minlength: 4,
                    required: true,
                    number: true
                },
                customer_id: {
                    required: true
                },
                store_name_1: {
                    required: true
                },
                store_name_2: {
                    required: true
                },
                channel: {
                    required: true
                },
                account: {
                    required: true
                },
                kota: {
                    required: true
                },
                region: {
                    required: true
                },
                supervisor: {
                    required: true
                },
                alokasi_ba_nyx: {
                    required: true
                },
                alokasi_ba_oap: {
                    required: true
                },
                alokasi_ba_myb: {
                    required: true
                },
                alokasi_ba_gar: {
                    required: true
                },
                alokasi_ba_cons: {
                    required: true
                }
                
                // payment: {
                //     required: true,
                //     minlength: 2,
                //     maxlength: 4
                // },
                // memo: {
                //     required: true,
                //     minlength: 10,
                //     maxlength: 40
                // },
                // 'checkboxes1[]': {
                //     required: true,
                //     minlength: 2,
                // },
                // 'checkboxes2[]': {
                //     required: true,
                //     minlength: 3,
                // },
                // radio1: {
                //     required: true
                // },
                // radio2: {
                //     required: true
                // }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
            },

            errorPlacement: function(error, element) {
                if (element.is(':checkbox')) {
                    error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                } else if (element.is(':radio')) {
                    error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function(form) {
                success1.show();
                error1.hide();
            }
        });
    }

    return {
        //main function to initiate the module
        init: function() {
            handleValidation1();
        }
    };
}();

jQuery(document).ready(function() {
    FormValidationMd.init();
});