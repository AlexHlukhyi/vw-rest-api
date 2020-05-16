function getData() {
	$.get("/api/?action=getComplectations", (data) => {
		updateHead(data);
		updateTable(data);
	});
}

function updateHead({quantity}) {
	$('#compl-count').html(quantity);
}

function updateTable({complectations}) {
	$('table tbody tr').not('[hidden]').remove();
	var $i = 1;
	complectations.forEach(complectation => {
		var complRow = $('table tbody tr#table-row-prototype').clone()
			.removeAttr('id').removeAttr('hidden').attr('complId', complectation.id);
		complRow.find('[scope=row]').html($i++);
		complRow.find('#compl-0-model')
			.attr('id', `compl-${complectation.id}-mode`)
			.html(complectation.model.name);
		complRow.find('#compl-0-name')
			.attr('id', `compl-${complectation.id}-name`)
			.html(complectation.name);
		complRow.find('#compl-0-engine')
			.attr('id', `compl-${complectation.id}-engine`)
			.html(complectation.engine.name);
		complRow.find('#compl-0-gearbox')
			.attr('id', `compl-${complectation.id}-gearbox`)
			.html(complectation.gearbox.name);
		$('table tbody').append(complRow);
	});
	$('.delete-compl').click(
		(event) => {
			var complId = $(event.target).closest('[complId]').attr('complId');
			deleteCompl(complId);
		}
	);
	$('.edit-compl').click(
		(event) => {
			var complId = $(event.target).closest('[complId]').attr('complId');
			editCompl(complId);
		}
	);
}

function deleteCompl(complId) {
	$.ajax({
		type: 'POST',
		url: '/api/?action=deleteComplectation',
		data: {
			'complectation-id': complId
		},
		success: (result) => {
			if (result.deleted) {
				$(`tr[complId=${complId}]`).remove();
				$(`tr[complId]`).each(function (i, elem) {
					$(elem).find('[scope=row]').html(i);
				});
				getData();
			}
		}
	});
}

function editCompl(complId) {
	$.get('/api/?action=getComplectation&complectation-id=' + complId, ({complectation}) => {
		$('#editModalLabel').html(complectation.name);
		$('#complId').val(complectation.id);
		$('#complName').val(complectation.name);
		$('#editModal').modal('show');
	});
}

$().ready(() => {
	getData();
	$('.add-compl').click(() => {
		$.get('/api/?action=getModels', ({models}) => {
			$('#addComplModelSelect option[value]').remove();
			if (models) {
				models.forEach((model) => {
					var option = new Option(model.name, model.id);
					$('#addComplModelSelect').append($(option));
				});
			}
		});
		$.get('/api/?action=getEngines', ({engines}) => {
			$('#addComplEngineSelect option[value]').remove();
			if (engines) {
				engines.forEach((engine) => {
					var option = new Option(engine.name, engine.id);
					$('#addComplEngineSelect').append($(option));
				});
			}
		});
		$.get('/api/?action=getGearboxes', ({gearboxes}) => {
			$('#addComplGearboxSelect option[value]').remove();
			if (gearboxes) {
				gearboxes.forEach((gearbox) => {
					var option = new Option(gearbox.name, gearbox.id);
					$('#addComplGearboxSelect').append($(option));
				});
			}
		});
		$('#addModal').modal('show');
	});
	$('#addModalSave').click(() => {
		$.ajax({
			type: 'POST',
			url: '/api/?action=insertComplectation',
			data: {
				'complectation-name': $('#addComplName').val(),
				'model-id': $('#addComplModelSelect').val(),
				'engine-id': $('#addComplEngineSelect').val(),
				'gearbox-id': $('#addComplGearboxSelect').val()
			},
			success: (result) => {
				if (result.inserted) {
					getData();
				}
				$('#addModal').modal('hide');
			}
		});
	});
	$('#editModalSave').click(() => {
		$.ajax({
			type: 'POST',
			url: '/api/?action=updateComplectation',
			data: {
				'complectation-id': $('#complId').val(),
				'complectation-name': $('#complName').val()
			},
			success: (result) => {
				if (result.updated) {
					$(`#compl-${$('#complId').val()}-name`).html($('#complName').val());
				}
				$('#editModal').modal('hide');
			}
		});
	});
});