<script>
    $('.modal-footer').on('click', '.delete', function() {
        alert($('#deleted-id').val());
        $.ajax({
            type: 'POST',
            url: '{{ route($routeUrl) }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'id': $('#deleted-id').val(),
            },
            success: function(data) {
                if ((data.errors)){
                    setTimeout(function () {
                        toastr.error('Gagal menghapus, data sudah terpakai!!', 'Peringatan', {timeOut: 6000, positionClass: "toast-top-center"});
                    }, 500);
                }
                else{
                    window.location = '{{ route($redirectUrl) }}';
                }
            }
        });
    });
</script>