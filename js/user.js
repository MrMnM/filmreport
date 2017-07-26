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
$( "tr.phone" )
.mouseenter(function() {
    $( this ).find('.phone').html( '<input type="text" id="phone" value="'+u.tel+'">');
})
.mouseleave(function() {
    u.phone = $('#phone').val();
    $( this ).find('.phone').html(u.tel);
});
$( "tr.mail" )
.mouseenter(function() {
    $( this ).find('.mail').html( '<input type="mail" id="mail" value="'+u.mail+'">');
})
.mouseleave(function() {
    u.mail=$('#mail').val();
    $( this ).find('.mail').html(u.mail);
});
$( "tr.ahv" )
.mouseenter(function() {
    $( this ).find('.ahv').html( '<input type="text" id="ahv" value="'+u.ahv+'">');
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

function LoadUser(){
    $.post( "h_user.php", { action: "get", us_id}).done(function( data ) {
    ret = jQuery.parseJSON(data);
    u = ret;
    Redraw();
});
}

function Redraw(){
    $('td.phone').html(u.tel);
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
    obj.name=null;
    obj.address1=null;
    obj.address2=null;
    obj.tel=null;
    obj.mail=null;
    obj.ahv=null;
    obj.dob=null;
    obj.konto=null;
    obj.bvg=null;
    return obj;
}
