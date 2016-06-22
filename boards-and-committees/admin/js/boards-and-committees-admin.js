(function( $ ) {
	'use strict';

    $(document).on('ready', function() {

		$('#new_committee').click(function() {
			var committee_name;
			var minutes;
			var agenda_date;

			committee_name = $('#new_committee_name').val();

			$.post( {
				url: agendas.ajaxurl,
				data: {
					action: 'add_committee',
					committee_name: committee_name
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});
    });

})( jQuery );
