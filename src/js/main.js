$(document).ready(function(){
    /* Actionsfor cookie consent buttons */
    const cookie_yes = document.getElementById('accept_cookies');
    const cookie_no = document.getElementById('reject_cookies');
    cookie_yes.addEventListener('click',function(){$("#cookie_banner").css("display","none");});
    cookie_no.addEventListener('click',function(){window.location = 'cookie_rejection.php';});
    /*-----------------------------------------------------------*/

    /* Displaying current date in the footer */
    const current_date = new Date();
    let current_year = current_date.getFullYear();
    let current_month = current_date.getMonth();
    const months = ["January", "Febuary", "March", "April", "May", "June", "July", "August","September","October","November","December"];
    document.querySelector('footer div').innerHTML = `${months[current_month]} ${current_year}`;
    /*-----------------------------------------------------------*/

    /* Animations played based on where user is currently looking at */
    $("#banner").waypoint(function(){
        $(".brief_header").css("animation-name","appering");
        $(".brief_header").css("animation-duration","1s");
        $(".brief_descriptions").css("animation-name","appering");
        $(".brief_descriptions").css("animation-duration","1s");
        $(".brief_introduction").css("animation-name","appering");
        $(".brief_introduction").css("animation-duration","1s");
        
        $('#banner').waypoint('destroy');
    });
    /*-----------------------------------------------------------*/
});

//Checking login form input fields for forbidden signs
function loginFormValidation()
{
    let username = document.getElementsByName('username')[0].value;
    let passwd = document.getElementsByName('password')[0].value;
    const fail = document.getElementById("failed_login");
    const not_allowed_signes = ['-',"'",'"',"<",">",'/','+','=','*','&','!','@','#','$','%','^','(',')','{','}','[',']'];
    if(username == "" || passwd == "")
    {
        fail.setAttribute("style","display: initial");
        return false;
    }
    else
    {
        for(let i = 0; i < not_allowed_signes.length; i++)
        {
        if(username.includes(not_allowed_signes[i]) || passwd.includes(not_allowed_signes[i]))
        {
            fail.setAttribute("style","display: initial");
            return false;
        }
        }
        return true;
    }
    
}
/*-----------------------------------------------------------*/

//Checking registration form input fields for forbidden signs
function registerFormValidation()
{
    let username = document.getElementsByName('username_r')[0].value;
    let passwd = document.getElementsByName('password_r')[0].value;
    let passwd_confirm = document.getElementById('paasword_r_conf').value;
    const fail = document.getElementById("failed_reg");
    const not_allowed_signes = ['-',"'",'"',"<",">",'/','+','=','*','&','!','@','#','$','%','^','(',')','{','}','[',']'];
    if(passwd != passwd_confirm || username == "" || passwd == "" || passwd_confirm == "" || passwd.length > 24)
    {
        fail.setAttribute("style","display: initial");
        return false;
    }else
    {
        for(let i = 0; i < not_allowed_signes.length; i++)
        {
            if(username.includes(not_allowed_signes[i]) || passwd.includes(not_allowed_signes[i]))
            {
                fail.setAttribute("style","display: initial");
                return false;
            }
        }
        return true;
    }
}
/*-----------------------------------------------------------*/
