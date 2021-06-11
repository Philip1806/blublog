@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"
        integrity="sha512-hCP3piYGSBPqnXypdKxKPSOzBHF75oU8wQ81a6OiGXHFMeKs9/8ChbgYl7pUvwImXJb03N4bs1o1DzmbokeeFw=="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(".select2-multi").select2();

    </script>
    <script>
        $(document).ready(function() {
            $('#editor').summernote({
                height: 400
            });
        });

    </script>
    <script>
        window.livewire.on('closeModal', () => {
            $('#staticBackdrop').modal('hide');
        })
        window.livewire.on('tagsUpdated', () => {
            $(".select2-multi").select2();
        })
        window.livewire.on('simTagClicked', id => {
            let oldValue = $('#multisel').val() || [];
            oldValue.push(id);

            $('#multisel').val(oldValue).trigger('change');
        })

    </script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true
            });
            $("#datepicker").datepicker("option", "dateFormat", "dd/mm/yy");
        });

    </script>
@endpush

@push('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
        integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
        integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endpush
