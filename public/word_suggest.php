<html>
    <head>
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <script>
            (function($){
                $(document).ready(function(){
                    $('#text').on('keyup', function(event){

                        if(event.which !== 32) {
                            return;
                        }

                        var $elt = $(this);

                        $.ajax({
                            url: 'word_suggest_ajax.php',
                            method: 'POST',
                            data: {
                                search: $elt.val()
                            },
                            success: function(data){
                                $('#suggestions').empty();
                                for (var suggestion in data) {
                                    $('#suggestions').append($('<li>').text(suggestion + ' (score='+ data[suggestion] + ')'));
                                }
                            }
                        });
                    });
                });
            })(jQuery);
        </script>
    </head>
    <body>
        <form method="post">
            <textarea name="text" id="text" rows="10" cols="200"></textarea>
            <ul id="suggestions"></ul>
        </form>
    </body>
</html>
