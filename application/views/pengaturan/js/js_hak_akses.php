<script>
	$(document).ready(function() {
		$('#filter_modul_id').select2({
			theme: 'bootstrap4',
			placeholder: '-- FILTER MODUL --',
			allowClear: true
		});

		$.ajax({
			type: "GET",
			url: "<?= base_url('sistem/modul/options/') ?>",
			beforeSend: function(xhr) {
				$("#overlay").fadeIn(300);
			},
			success: function(data) {
				$("#overlay").fadeOut(300);
				var opts = $.parseJSON(data);
				$.each(opts, function(i, d) {
					$("#filter_modul_id").append('<option value="' + d.id + '">' + d.text + '</option>');
				});
			},
			error: function(xhr, status, error) {
				swal(error, "Terjadi kegagalan saat memuat data. Sepertinya internetmu kurang stabil. Silahkan coba kembali saat internetmu stabil.", "error").then((value) => {
					$("#dtTable").DataTable().ajax.reload(function() {
						$("#overlay").fadeOut(300)
					}, false);
				})
			}
		});

		$("#filter_modul_id").change(function() {
			$("#dtTable").DataTable().ajax.reload(function() {
				$("#overlay").fadeOut(300)
			}, true);
		});

		$.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
			return {
				"iStart": oSettings._iDisplayStart,
				"iEnd": oSettings.fnDisplayEnd(),
				"iLength": oSettings._iDisplayLength,
				"iTotal": oSettings.fnRecordsTotal(),
				"iFilteredTotal": oSettings.fnRecordsDisplay(),
				"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
				"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
			};
		};

		var table = $("#dtTable").dataTable({
			initComplete: function() {
				$("#overlay").fadeOut(300);
				var api = this.api();
				$('#dtTable_filter input').off('.DT').on('input.DT', function() {
					api.search(this.value).draw();
					$("#overlay").fadeOut(300);
				});
			},
			processing: true,
			serverSide: true,
			ajax: {
				"url": "<?= base_url('pengaturan/hak_akses/list_data/') ?>",
				"type": "POST",
				"data": function(d) {
					return $.extend({}, d, {
						'modul_id': $('#filter_modul_id').val(),
					});
				},
				"error": function(xhr, status, error) {
					swal(error, "Terjadi kegagalan saat memuat data. Sepertinya internetmu kurang stabil. Silahkan coba kembali saat internetmu stabil.", "error").then((value) => {
						$("#dtTable").DataTable().ajax.reload(function() {
							$("#overlay").fadeOut(300)
						}, false);
					})
				}
			},
			columns: [{
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1 + ".";
					}
				},
				{
					"data": "1"
				},
				{
					"data": "2"
				},
				{
					"data": "3"
				},
				{
					"data": "4"
				},
				{
					"data": "5",
					"searchable": false
				}
			],
			rowCallback: function(row, data, iDisplayIndex) {
				$("#overlay").fadeOut(300);
				var info = this.fnPagingInfo();
				var page = info.iPage;
				var length = info.iLength;
				$('td:eq(0)', row).html();
			}
		});

		$('#Frm').submit(function(e) {
			e.preventDefault();
			swal({
				title: "Anda Yakin Ingin Menyimpan Data?",
				text: "Klik CANCEL jika ingin membatalkan!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((Oke) => {
				if (Oke) {
					$.ajax({
						type: "POST",
						url: "<?= base_url('pengaturan/hak_akses/simpan/') ?>",
						data: $("#Frm").serialize(),
						timeout: 5000,
						beforeSend: function(xhr) {
							$("#overlay").fadeIn(300);
						},
						success: function(response) {
							$("#overlay").fadeOut(300);
							var data = JSON.parse(response);
							swal(data.warning, data.pesan, data.kode).then((value) => {
								if (data.kode == "success") {
									$("#dtTable").DataTable().ajax.reload(function() {
										$("#overlay").fadeOut(300)
									}, false);
									$("#frmData").modal('hide');
								}
							})
						},
						error: function(xhr, status, error) {
							swal(error, "Please Ask Support or Refresh the Page!", "error").then((value) => {
								$("#dtTable").DataTable().ajax.reload(function() {
									$("#overlay").fadeOut(300)
								}, false);
							})
						}
					})
				} else {
					swal("Poof!", "Penyimpanan Data Dibatalkan", "error").then((value) => {
						location.reload();
					})
				}
			});
		});

		$('input[type=checkbox]').click(function() {
			if ($(this).is(':checked')) {
				$("#submodul_roles").val($("#submodul_roles").val() + "," + $(this).val());
			} else {
				$("#submodul_roles").val($("#submodul_roles").val().replace("," + $(this).val(), ""));
			}
		});

		$('#FrmSetup').submit(function(e) {
			e.preventDefault();
			swal({
				title: "Anda Yakin Ingin Menyimpan Data?",
				text: "Klik CANCEL jika ingin membatalkan!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((Oke) => {
				if (Oke) {
					$.ajax({
						type: "POST",
						url: "<?= base_url('pengaturan/hak_akses/simpan_modul/') ?>",
						data: $("#FrmSetup").serialize(),
						timeout: 5000,
						beforeSend: function(xhr) {
							$("#overlay").fadeIn(300);
						},
						success: function(response) {
							$("#overlay").fadeOut(300);
							var data = JSON.parse(response);
							swal(data.warning, data.pesan, data.kode).then((value) => {
								if (data.kode == "success") {
									$("#dtTable").DataTable().ajax.reload(function() {
										$("#overlay").fadeOut(300)
									}, false);
									$("#frmSetup").modal('hide');
								}
							})
						},
						error: function(xhr, status, error) {
							swal(error, "Please Ask Support or Refresh the Page!", "error").then((value) => {
								$("#dtTable").DataTable().ajax.reload(function() {
									$("#overlay").fadeOut(300)
								}, false);
							})
						}
					})
				} else {
					swal("Poof!", "Penyimpanan Data Dibatalkan", "error").then((value) => {
						location.reload();
					})
				}
			});
		});

		$('input[type=checkbox]').click(function() {
			if ($(this).is(':checked')) {
				$("#modul_roles").val($("#modul_roles").val() + "," + $(this).val());
			} else {
				$("#modul_roles").val($("#modul_roles").val().replace("," + $(this).val(), ""));
			}
		});

		$(document).on('click', '#edit', function() {
			$("#frmData").modal('show');
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pengaturan/hak_akses/get_data/') ?>",
				dataType: 'json',
				data: {
					submodul_id: $(this).attr("data")
				},
				beforeSend: function(xhr) {
					$("#overlay").fadeIn(300);
				},
				success: function(data) {
					$("#overlay").fadeOut(300);
					split = data.submodul_roles.split(",");
					$.each(split, function(key, value) {
						ident = $("#submodul_roles_" + value);
						ident.prop('checked', true);
					})
					var n = $('input[type=checkbox]').length + 1;
					for (i = 1; i <= n; i++) {
						$("#submodul_roles_" + i).val(i);
					}
					$.each(data, function(key, value) {
						if (key == "submodul_nama") {
							$("#submodul_nama").text(">" + value + "<");
						}
						var ctrl = $('[name=' + key + ']', $('#Frm'));
						switch (ctrl.prop("type")) {
							case "select-one":
								ctrl.val(value).change();
								break;
							default:
								ctrl.val(value);
						}
					});
				},
				error: function(xhr, status, error) {
					swal(error, "Terjadi kegagalan saat memuat data. Sepertinya internetmu kurang stabil. Silahkan coba kembali saat internetmu stabil.", "error").then((value) => {
						$("#dtTable").DataTable().ajax.reload(function() {
							$("#overlay").fadeOut(300)
						}, false);
					})
				}
			});
		});

		$(document).on('click', '#setup', function() {
			$("#frmSetup").modal('show');
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pengaturan/hak_akses/get_data_modul/') ?>",
				dataType: 'json',
				data: {
					modul_id: $(this).attr("data")
				},
				beforeSend: function(xhr) {
					$("#overlay").fadeIn(300);
				},
				success: function(data) {
					$("#overlay").fadeOut(300);
					split = data.modul_roles.split(",");
					$.each(split, function(key, value) {
						ident = $("#modul_roles_" + value);
						ident.prop('checked', true);
					})
					var n = $('input[type=checkbox]').length + 1;
					for (i = 1; i <= n; i++) {
						$("#modul_roles_" + i).val(i);
					}
					$.each(data, function(key, value) {
						if (key == "modul_nama") {
							$("#modul_nama").text(">" + value + "<");
						}
						var ctrl = $('[name=' + key + ']', $('#FrmSetup'));
						switch (ctrl.prop("type")) {
							case "select-one":
								ctrl.val(value).change();
								break;
							default:
								ctrl.val(value);
						}
					});
				},
				error: function(xhr, status, error) {
					swal(error, "Terjadi kegagalan saat memuat data. Sepertinya internetmu kurang stabil. Silahkan coba kembali saat internetmu stabil.", "error").then((value) => {
						$("#dtTable").DataTable().ajax.reload(function() {
							$("#overlay").fadeOut(300)
						}, false);
					})
				}
			});
		});
	});
</script>