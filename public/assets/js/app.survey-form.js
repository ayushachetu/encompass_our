function openNav() {
	document.getElementById("sidenav").style.width = "250px";
}

function closeNav() {
	document.getElementById("sidenav").style.width = "0";
}

function handleFileSelect(evt) {
	var files = evt.target.files;
	document.getElementById('modal-images-preview').innerHTML = "";
	document.getElementById('images-preview').innerHTML = "";

	for (var i = 0, f; f = files[i]; i++) {
		if (!f.type.match('image.*')) {
			sweetAlert("Oops...", "Uploaded files must be of type image only!", "error");
			return false;
		}

		var reader = new FileReader();
		reader.onload = (function(theFile) {
			return function(e) {
				var span = document.createElement('span');
				span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '"/>'].join('') + '<p></p>';
				document.getElementById('images-preview').insertBefore(span, null);

				var div = document.createElement('div');
				div.innerHTML = '<div class="row">'+
													'<div class="col-sm-3">'+
														'<img src="'+e.target.result+'" title="'+escape(theFile.name)+'"/>'+
													'</div>'+
													'<div class="col-sm-9">'+
														'<textarea class="form-control img-desc" placeholder="Comments" data-image="'+escape(theFile.name)+'"></textarea>'+
													'</div>'+
												'</div>'+
												'<hr>';
				document.getElementById('modal-images-preview').insertBefore(div, null);
			};
		})(f);
		reader.readAsDataURL(f);
	}

	$('#images').modal('show');
	$('input[name="preview-btn"]').removeClass('hidden');
}

if (document.getElementById('files') != null && document.getElementById('files') != undefined) {
	document.getElementById('files').addEventListener('change', handleFileSelect, false);
}

$(document).ready(function () {
	$("#navHide").click(function(){
		$("#navigation-box").hide();
		$("#navShow").show();
	});

	$("#navShow").click(function(){
		$("#navigation-box").show();
		$("#navShow").hide();
		$("#navigation-box").addClass("slideOpen");
	});

	$('#sidenav-open-btn').click(function () {
		openNav();
	});

	$('#sidenav-close-btn').click(function () {
		closeNav();
	});

	$('input[type="radio"]').change(function () {
		if ($(this).is(':checked'))
			$(this).parents('tr').removeClass('bg-trans-red');
	});


	$('#images').on('keypress', 'textarea.img-desc', function (e) {
		if (e.which < 0x20) {
			return;
		} else if (this.value.length < 20) {
			$('#images-preview').find('img[title="'+$(this).attr('data-image')+'"]').siblings('p').text(this.value);
		}
	});

	$('.lang').change(function () {
		var question_data = question();

		if ($(this).val() == 'es') {
			$('.matrix-flow h3').text(question_data.es_matrix.label);
			$('.image-flow h3').text(question_data.es_image.label);
			$('.comment-flow h3').text(question_data.es_comment.label);
			$('.rating-flow h3').text('Niveles APPA (seleccione uno)');
			$('.option').each(function () {
				$(this).text(en_to_es_options($(this).text()));
			});
			$('.sidenav-link a').each(function () {
				$(this).text(en_to_es_ques($(this).text()));
			});
		} else {
			$('.matrix-flow h3').text(question_data.matrix.label);
			$('.image-flow h3').text(question_data.image.label);
			$('.comment-flow h3').text(question_data.comment.label);
			$('.rating-flow h3').text('APPA Levels (Select One)');
			$('.option').each(function () {
				$(this).text(es_to_en_options($(this).text()));
			});
			$('.sidenav-link a').each(function () {
				$(this).text(es_to_en_ques($(this).text()));
			});
		}
	});

	$('.cancel-image-upload').click(function () {
		document.getElementById('modal-images-preview').innerHTML = "";
		document.getElementById('images-preview').innerHTML = "";
		$('input[name="preview-btn"]').addClass('hidden');
		$('#images').modal('hide');
	});

	// $('#colors_sketch').sketch();

	$(".signature-flow a").eq(0).attr("style", "color:#000");

	$(".signature-flow a").click(function () {
		$(".signature-flow a").removeAttr("style");
		$(this).attr("style", "color:#000");
	});

	$('.img-upload-btn').click(function () {
		$(this).parent().find('input[type="file"]').click();
	});

	$('input[name="preview-btn"]').click(function () {
		$('#images').modal('show');
	});

	$('.question-navigation').click(function () {
		var fd = new FormData;
		fd.append('_token', $('input[name="_token"]').val());
		fd.append('new_ques', $(this).attr('data-index'));
		if (validateCurrent() !== false)
			fd.append('matrix', validateCurrent());
		else {
			$('html, body').animate({
				scrollTop: 0
			}, 500);
			return false;
		}
		var files = $('#files').prop('files');
		for (var i = 0; i < files.length; i++ )
			fd.append(files[i].name, files[i]);
		var img_desc = {};
		$('.img-desc').each(function () {
			img_desc[$(this).attr('data-image')] =  $(this).val();
		});
		fd.append('img_desc', JSON.stringify(img_desc));
		fd.append('comment', $('.comment-flow textarea').val());
		fd.append('rating', $('input[name="rating-level"]:checked').val());

		$.ajax({
			type: 'post',
			url: 'save-survey-question-data',
			data: fd,
			cache: false,
			dataType: 'text',
			contentType: false,
			processData: false,
			success: function (response) {
				response = JSON.parse(response);
				if (response.success == 1)
					window.location.reload();
				else if (response.signature == 1)
					show_signature();
			},
			error: function (response) {
				console.log(response);
			}
		});
	});

	$('.back').click(function () {
		window.location.reload();
	});

	$('.complete-survey').click(function () {
		var canvas = document.getElementById('colors_sketch');
		var data = {};
		data._token = $('input[name="_token"]').val();
		data.signature = canvas.toDataURL();
		$.ajax({
			type: 'post',
			url: 'save-signature',
			data: data,
			success: function (response) {
				if (response.success == 1)
					window.location = '/survey/completed';
			},
			error: function (response) {
				console.log(response);
			}
		})
	});
});

function validateCurrent() {
	var matrix_data = {};
	var proceed = true;
	$('.option').each(function () {
		var option = $(this).text();
		var radio_name = option.replace(/ /g, '-');
		var radio_score = $('input[name="'+radio_name+'"]:checked').val();

		if (radio_score !== undefined) {
			matrix_data[option] = radio_score;
			$(this).parents('tr').removeClass('bg-trans-red')
		} else {
			proceed = false;
			$(this).parents('tr').addClass('bg-trans-red');
		}
	});

	if (proceed !== false)
		return JSON.stringify(matrix_data);
	else
		return proceed;
}

function show_signature() {
	$('.matrix-flow').addClass('hidden');
	$('.image-flow').addClass('hidden');
	$('.comment-flow').addClass('hidden');
	$('.rating-flow').addClass('hidden');
	$('hr.flow-separator').addClass('hidden');
	$('.previous-ques').addClass('hidden');
	$('.next-ques').addClass('hidden');
	$('.relative-container').addClass('hidden');
	$('.lang').addClass('hidden');
	$('.back').removeClass('hidden');

	$('.signature-flow').removeClass('hidden');
	$('.complete-survey').parent().removeClass('hidden');
}