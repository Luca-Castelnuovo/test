var working = false;
$('.login').on('submit', function (e) {
    e.preventDefault();
    if (working) return;
    working = true;
    var $this = $(this),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Authenticating');
    setTimeout(function () {
        $this.addClass('ok');
        $state.html('Welcome back!');
        setTimeout(function () {
            $state.html('Log in');
            $this.removeClass('ok loading');
            working = false;
        }, 4000);
    }, 1000);
});

//if submit button is clicked
$('#submit').click(function () {

    //Get the data from all the fields
    var username = $('input[name=username]');
    var password = $('input[name=password]');

    //Simple validation to make sure user entered something
    //If error found, add hightlight class to the text field
    if (username.val() == '') {
        username.addClass('hightlight');
        return false;
    } else username.removeClass('hightlight');

    if (password.val() == '') {
        password.addClass('hightlight');
        return false;
    } else password.removeClass('hightlight');

    //organize the data properly
    var data = 'username=' + username.val() + '&password=' + password.val();

    //disabled all the text fields
    $('.text').attr('disabled', 'true');

    e.preventDefault();
    if (working) return;
    working = true;
    var $this = $(this),
        $state = $this.find('button > .state');
    $this.addClass('loading');
    $state.html('Authenticating');
    setTimeout(function () {
        $this.addClass('ok');
        $state.html('Welcome back!');
        setTimeout(function () {
            $state.html('Log in');
            $this.removeClass('ok loading');
            working = false;
        }, 4000);
    }, 1000);

    //start the ajax
    $.ajax({
        //this is the php file that processes the data and send mail
        url: "auth.php",

        //GET method is used
        type: "GET",

        //pass the data
        data: data,

        //Do not cache the page
        cache: false,

        //success
        success: function (html) {
            //if process.php returned 1/true (send mail success)
            if (html == 1) {
                alert('succ');

                //if process.php returned 0/false (send mail failed)
            } else alert('errrot');
        }
    });

    //cancel the submit button default behaviours
    return false;
});
