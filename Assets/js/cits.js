$(document).ready(function(){   

// $("#login-btn").click(function(e){
//     if($("#login-form")[0].checkValidity()){
//         e.preventDefault();

//         $("#login-btn").val('Please wait.....');
//         $.ajax({
//             url:'core/Operations.php',
//             method:'post',
//             data: $("#login-form").serialize() + '&action=login',
//             success:function(response){
//                // console.log(response);
//                 $("login-btn").val('Signing in...');
//                 if (response === 'login'){
//                     window.location = 'index.php';
//                 } else {
//                     $("#loginAlert").html(response)
//                 }
//         }
//     });
//     }
// });


}); //end jQuery