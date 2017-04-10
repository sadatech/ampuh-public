var FormRepeater = function () {

    return {
        //main function to initiate the module
        init: function () {
        	$('.mt-repeater').each(function(){
        		$(this).repeater({
                    initEmpty: true,
                    defaultValues: {
                        toko: 'ford'
                    },
        			show: function () {
                        $(this).slideDown();
                    },
		            hide: function (deleteElement) {
		                if(confirm('Are you sure you want to delete this element?')) {
		                    $(this).slideUp(deleteElement);
		                }
		            },
                    isFirstItemUndeletable: true,
		            ready: function (setIndexes) {
                        console.log('a');
		            }

        		});
        	});
        }

    };

}();

jQuery(document).ready(function() {
    FormRepeater.init();
});
