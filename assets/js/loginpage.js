var email=document.getElementById('email');
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
var emailvalidate;

var eright = document.querySelector('.er');
var ewrong = document.querySelector('.ew');
var eerror1 = document.querySelector('.e-error');

email.addEventListener('keyup',function(){

if(email.value.match(mailformat))
{
   
    emailvalidate = true;
    eerror1.style.display = 'none';
    ewrong.style.visibility = 'hidden';
    eright.style.visibility = 'visible';
    eright.style.color = 'green';
}
else
{
    emailvalidate=false;
    ewrong.style.visibility = 'visible';
    eright.style.visibility = 'hidden';
    ewrong.style.color = 'red';
    eerror1.style.display = 'block';

}

})




var pwd=document.getElementById('pwd');
var pright = document.querySelector('.pr');
var pwrong = document.querySelector('.pw');
var perror = document.querySelector('.p-error');

pwd.addEventListener('keyup',function(){


if(pwd.value.length =='')
{
    pwrong.style.visibility = 'visible';
    pright.style.visibility = 'hidden';
    pwrong.style.color = 'red';
    perror.style.display = 'block';
 
}
else
{
    perror.style.display = 'none';
    pwrong.style.visibility = 'hidden';
    pright.style.visibility = 'visible';
    pright.style.color = 'green';

}

})

function validate(){

if(email.value=='')
{
Swal.fire
({
 icon: 'error',
 title: 'Email Validation',
 text: 'fill out the email!',
})
return false;
}
else if(emailvalidate==false)
{
Swal.fire
({
 icon: 'error',
 title: 'Email Validation',
 text: 'invalid email address!',
})
return false;

}
else if(pwd.value=='')
{
Swal.fire
({
 icon: 'error',
 title: 'Password Validation',
 text: 'fill out the Password',
})
return false;

}
}
