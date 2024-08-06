 <script type="text/javascript">
     function loadDetails(category_id) {
         $.get('{{ url('admin/price-structure') }}/' + category_id, function(response) {
             $('#home-' + category_id).find('form .panal').html(response);
         });
     }

     $(function() {
         /* call default category */
        $('.nav-item button.active').trigger('click');
     })


     $('.form').submit(function(e) {
         e.preventDefault(0);

         var $this = $(this);
         e.preventDefault();

         $this.find("button").prop("disabled", true);
         $.ajax({
             method: "POST",
             url: $(this).prop("action"),
             data: new FormData(this),
             contentType: false,
             cache: false,
             processData: false,
             success: function(data) {

                 $this.find("button").prop("disabled", false);
                 toastr.success(data.message);
                 clearMessage();
             },
             error: function(error) {
                 console.log('============>', error.responseJSON);
                 let errors = error.responseJSON.errors;
                 $.each(errors, function(key, val) {
                     $('.error-' + key).html(val[0]);
                 });

                 clearMessage();
                 $this.find("button").prop("disabled", false);
             }
         });
     });


     function clearMessage() {
         setTimeout(() => {
             $('.errors, .process, .break-process').html('');
         }, 3000);
     }

     $(document).on('change', '.totalBreak', function(e) {
         $this = $(this);
         if (!confirm('Are you sure?.')) {
             $this.val(this.defaultValue);
             return;
         }

         let formData = new FormData();
         formData.append('_token', "{{ csrf_token() }}");
         formData.append('max_break', $this.val());

         let action = "{{ route('price-structure.store') }}?updateBreakNo=true";
         $.ajax({
             method: "POST",
             url: action,
             data: formData,
             contentType: false,
             cache: false,
             processData: false,
             success: function(data) {
                 toastr.success(data.message);
                 $('.nav-item button.active').trigger('click');
                 clearMessage();
             },
             error: function(error) {
                 console.log('============>', error.responseJSON);
                 let errors = error.responseJSON.errors;
                 $.each(errors, function(key, val) {
                     $('.error-' + key).html(val[0]);
                 });

                 clearMessage();
             }
         });
     });

    function isNumeric(that) {
        if (/[^\d.]/.test(that.value)) {
            that.value = that.value.replace(/[^\d.]/g, '');
        }
    }
 </script>
