<script>
    $(document).on('click', '.link-modal', function(e){
        e.preventDefault(0);
        
        let url=$(this).attr('link-url');
        let title=$(this).attr('link-title');
        let isFooter=$(this).attr('link-isFooter') ? 1 : 0;
        
        $('#common-modal').modal('show');
        $('#common-modal .modal-panel').html('<p>Loading.....</p>');
        $('#common-modal .modal-title').html(title ? title : '');
        $.get(url, function(response) {
            $('#common-modal .modal-panel').html(response);

            if(isFooter) {
                $('#common-modal .footer-content').html('');
            }
            
        });
    });
</script>