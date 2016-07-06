(function( $ ) {
	'use strict';

	$(document).on('ready', function() {

		$('.add_committee_member').on('change', function() {
			var committee_id = $(this).parents('.committee').find('[name="committee_id"]').val();
			var member_id = $(this).val();

			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'add_committee_member',
					committee_id: committee_id,
					member_id: member_id
				},
				success: function(data) {
					console.log(data);
					//window.location.reload();
				}
			});
		});

		$('.delete_committee_member').click(function() {
			var committee_id = $(this).parents('.committee').find('[name="committee_id"]').val();
			var member_id = $(this).next().val();

			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'delete_committee_member',
					committee_id: committee_id,
					member_id: member_id
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});

		$('.delete_member').click(function() {
			var member_id = $(this).next().val();

			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'delete_member',
					member_id: member_id
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});

		$('#new_member').click(function() {
			var member_name = $('#new_member_name').val();

			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'add_member',
					member_name: member_name
				},
				success: function(data) {
					window.location.reload();
				}
				//FIXME: add error message if failure
			});
		});

		$('#new_board').click(function() {
			var board_name = $('#new_board_name').val();
			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'add_board',
					board_name: board_name
				},
				success: function(data) {
					console.log(data);
				}
			});
		});
		$('.delete_board').click(function() {
			var board = $(this).next().val();
			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'delete_board',
					board: board
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});

		$('#new_committee').click(function() {
			var committee_name = $('#new_committee_name').val();

			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'add_committee',
					committee_name: committee_name
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});

		$('.committee_name').on('change', function() {
			var committee_id = $(this).parents('.committee').find('[name="committee_id"]').val();
			var committee_name = $(this).val();
			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'update_committee',
					committee_name: committee_name,
					committee_id: committee_id
				},
				success: function(data) {
				}
			});
		});

		$('.delete_committee').click(function() {
			var committee_id = $(this).parents('.committee').find('.committee_id').val();
			$.post( {
				url: boards_and_committees.ajaxurl,
				data: {
					action: 'delete_committee',
					committee_id: committee_id
				},
				success: function(data) {
					window.location.reload();
				}
			});
		});
	});
})( jQuery );
