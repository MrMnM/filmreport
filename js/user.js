u = new User();
LoadUser();

$( "tr.name" )
.mouseenter(function() {
    $( this ).find('.name').html( '<input type="text" id="name" value="'+u.name+'">');
})
.mouseleave(function() {
    u.name=$('#name').val();
    $( this ).find('.name').html(u.name);
});
$( "tr.address" )
.mouseenter(function() {
    $( this ).find('.address').html( '<input type="text" id="address1" value="'+u.address1+'"><br><input type="text" id="address2" value="'+u.address2+'">');
})
.mouseleave(function() {
    u.address1=$('#address1').val();
    u.address2=$('#address2').val();
    $( this ).find('.address').html(u.address1+'<br>'+u.address2);
});
$( "tr.tel" )
.mouseenter(function() {
    $( this ).find('.tel').html( '<input type="text" id="tel" value="'+u.tel+'">');
})
.mouseleave(function() {
    u.tel = $('#tel').val();
    $( this ).find('.tel').html(u.tel);
});
/*
$( "tr.mail" )
.mouseenter(function() {
    $( this ).find('.mail').html( '<input type="mail" id="mail" value="'+u.mail+'">');
})
.mouseleave(function() {
    u.mail=$('#mail').val();
    $( this ).find('.mail').html(u.mail);
});
*/
$( "tr.ahv" )
.mouseenter(function() {
    $( this ).find('.ahv').html( '<input type="text" id="ahv" pattern="756\.\d{4}\.\d{4}\.\d{2}" value="'+u.ahv+'">');
})
.mouseleave(function() {
    u.ahv=$('#ahv').val();
    $( this ).find('.ahv').html(u.ahv);
});
$( "tr.dob" )
.mouseenter(function() {
    $( this ).find('.dob').html( '<input type="date" id="dob" value="'+u.dob+'">');
})
.mouseleave(function() {
    u.dob = $('#dob').val();
    $( this ).find('.adob').html(u.dob);
});
$( "tr.konto" )
.mouseenter(function() {
    $( this ).find('.konto').html( '<input type="text" id="konto" value="'+u.konto+'">');
})
.mouseleave(function() {
    u.konto = $('#konto').val();
    $( this ).find('.konto').html(u.konto);
});
$( "tr.bvg" )
.mouseenter(function() {
    $( this ).find('.bvg').html( '<input type="text" id="bvg" value="'+u.bvg+'">');
})
.mouseleave(function() {
    u.bvg=$('#bvg').val();
    $( this ).find('.bvg').html(u.bvg);
});

jQuery('#saveInfo').click(function(event){
    $.ajax({
        url: 'h_user.php',
        dataType: 'json',
        data : {'action':'update','us_id':us_id, 'name':u.name, 'tel':u.tel, 'address1':u.address1, 'address2':u.address2,'ahv':u.ahv,'dob':u.dob, 'konto':u.konto,'bvg':u.bvg,},
        type: 'POST',
        success: function(data){
            if (data.message=="SUCCESS") {
                alert('SUCCESS');
            }else{
                alert('ERROR');
            }
        }
    });
});

function LoadUser(){
    $.post( "h_user.php", { action: "get", us_id}).done(function( data ) {
    ret = jQuery.parseJSON(data);
    console.log(ret);
    u = ret;
    Redraw();
});
}

function Redraw(){
    $('td.tel').html(u.tel);
    $('td.name').html(u.name);
    $('td.mail').html(u.mail);
    $('td.ahv').html(u.ahv);
    $('td.dob').html(u.dob);
    $('td.konto').html(u.konto);
    $('td.address').html(u.address1+'<br>'+u.address2);
    $('td.name').html(u.name);
    $('td.bvg').html(u.bvg);
}

function User() {
    var obj = {};
    obj.action="update";
    obj.name=" ";
    obj.address1=" ";
    obj.address2=" ";
    obj.tel=" ";
    obj.mail=" ";
    obj.ahv=" ";
    obj.dob=" ";
    obj.konto=" ";
    obj.bvg=" ";
    return obj;
}
