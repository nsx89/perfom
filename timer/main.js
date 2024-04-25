const countDownClock = (date, format = 'seconds') => {

    const currnet_date = Date.now() / 1000;
    const date_end = new Date(date).getTime() / 1000;

    const number = Math.floor(date_end - currnet_date);

    const d = document;
    const daysElement = d.querySelector('.days');
    const hoursElement = d.querySelector('.hours');
    const minutesElement = d.querySelector('.minutes');
    const secondsElement = d.querySelector('.seconds');

    let countdown;
    convertFormat(format);


    function convertFormat(format) {
        switch (format) {
            case 'seconds':
                return timer(number);
            case 'minutes':
                return timer(number * 60);
            case 'hours':
                return timer(number * 60 * 60);
            case 'days':
                return timer(number * 60 * 60 * 24);}

    }

    function timer(seconds) {
        const now = Date.now();
        const then = now + seconds * 1000;

        countdown = setInterval(() => {
            const secondsLeft = Math.round((then - Date.now()) / 1000);

            if (secondsLeft <= 0) {
                clearInterval(countdown);
                return;
            };

            displayTimeLeft(secondsLeft);

        }, 1000);
    }

    function displayTimeLeft(seconds) {
        daysElement.textContent = Math.floor(seconds / 86400);
        hoursElement.textContent = Math.floor(seconds % 86400 / 3600);
        minutesElement.textContent = Math.floor(seconds % 86400 % 3600 / 60);
        secondsElement.textContent = seconds % 60 < 10 ? `0${seconds % 60}` : seconds % 60;
    }
};

//countDownClock('2024-04-28 15:30');
if ($('.countdown-container').length > 0) {
    countDownClock('2024-04-26 10:00');
}


/* --- Subs email --- */

$(document).ready(function(){
    $('body').on('click','.js-timer-btn', function(e) {
        var btn = $(this);
        var form = btn.closest('form');
        var input_email = $('input[name=email]', form);
        var email = input_email.val();   
        if (email == '') {
            Swal.fire({
                icon: "error",
                title: 'Введите Email',
                confirmButtonColor: "#ff4500",
            });
            return false;
        }
        input_email.val('');
        $.ajax({
            type: "POST",
            url: "/timer/ajax.php",
            data: {
                'method' : 'timer_subs',
                'email' : email
            },
            success: function(html){
                if (html != '') {
                    Swal.fire({
                        title: html,
                        confirmButtonColor: "#ff4500",
                    });
                }
            }
        });
    });  
});
 
/* --- // --- */


