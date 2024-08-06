<script>

    $(function(){
        
        $(document).on('change', '#country', function(e){
            $('#state').html('');
            $('#state').select2('destroy');
            _initState();
            
        });

        function _initState(){
            $('#state').select2({
                placeholder: '-Select-',
                dropdownParent: $('#common-modal .modal-content'),
                width: '100% !important',
                ajax: {
                    url: "{{ route('ajax.search.states') }}",
                    data: function(params) {
                        let country = $('#country').val();
                        var query = {
                            search: params.term,
                            country: country
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                },
            });
        }

        $('#country').select2({
            dropdownParent: $("#common-modal")
        });
        _initState();//init state
 
    })
</script>
<style>
    .select2-container {
        width: 100% !important;
    }
</style>