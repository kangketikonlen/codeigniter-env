<script>
	jQuery.ajax({
		type: "GET",
		url: "<?= base_url('sistem/info/get_data/') ?>",
		dataType: 'json',
		beforeSend: function(xhr) {
			$("#overlay").fadeIn(300);
		},
		success: function(data) {
			$("#overlay").fadeOut(300);
			$.each(data, function(key, value) {
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
					url: "<?= base_url('sistem/info/simpan/') ?>",
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
								location.reload();
							}
						})
					},
					error: function(xhr, status, error) {
						swal(error, "Please Ask Support or Refresh the Page!", "error").then((value) => {
							location.reload();
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
</script>