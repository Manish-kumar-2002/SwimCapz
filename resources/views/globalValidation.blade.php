 <script>
     //Only numeric allow
     $(document).on('keydown', 'minimum-order, .only-numeric', function(event) {
         let _text = $(this).val();
         let decimalCount = _text.split('.').length - 1;
         if (!(
                 (event.keyCode >= 48 && event.keyCode <= 57) ||
                 event.keyCode == 38 || event.keyCode == 40 ||
                 event.keyCode == 46 || event.keyCode == 8 ||
                 event.keyCode == 190 && decimalCount == 0 && _text.indexOf('.') == -1) ||
             event.key == "@" || event.key == "#" ||
             event.key == "!" || event.key == "$" ||
             event.key == "%" || event.key == "&" ||
             event.key == "*" || event.key == "(" ||
             event.key == ")" || event.key == "_" ||
             event.key == "-" || event.key == "+" ||
             event.key == "~" || event.key == "?" ||
             event.key == "!" || event.key == "/" ||
             event.key == "{" || event.key == "}" ||
             event.key == "\\"
         ) {
             event.preventDefault();
         } else {
             let xyz = checkNumber(_text);
             if (event.keyCode != 8 && xyz) {
                 event.preventDefault();
             }
         }

         if (parseInt(_text) <= 1) {
             $(this).val(1);
         }

         let maxChar = $(this).attr('max');
         if (maxChar && event.keyCode != 8) {
             if (_text.length >= maxChar) {
                 event.preventDefault();
             }
         }
     });

     function checkNumber(_text) {
         isAllow = false;
         let _array = _text.split('.');
         if (_array.length == 2 && _array[1].length >= 2) {
             isAllow = true;
         }
         //console.log("=============",_array[1].length);
         return isAllow;
     }

     $(document).on('keyup', '.email-validate', function() {
         let email = $(this).val();
         if (email.length < 5) {
             return;
         }

         let errorPanel = $(this).attr('error-class');
         if (isValidEmail(email)) {
             $('.' + errorPanel).html('');
         } else {
             $('.' + errorPanel).html('invalide email');
         }
     });

     function isValidEmail(email) {
         // Regular expression for basic email validation
         var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return emailPattern.test(email);
     }
 </script>
