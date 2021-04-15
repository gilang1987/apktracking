$(function() {
    $("#main_form").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                $(document).find('small.text-danger').text('');
                $(document).find('input').removeClass('is-invalid');
            },
            success: function(data) {
                if (data.status == false) {
                    if (data.type == 'validation') {
                        $.each(data.message, function(prefix, val) {
                            $("input[name="+prefix+"]").addClass('is-invalid');
                            $('small.'+prefix+'_error').text(val[0]);
                        });
                    }
                    if (data.type == 'alert') {
                        swal.fire("Gagal!", data.message, "error");
                    }
                } else {
                    swal.fire("Berhasil!", data.message, "success").then(function () {
                        $("#modal").modal('hide');
                        window.LaravelDataTables["data-table"].draw();
                    });
                }
            },
            error:function() {
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    });
});