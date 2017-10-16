function newCreated(data) {
    if (data.message=="SUCCESS") {
//        let urlData = "http://www.filmstunden.ch/shorten.php"
//        $.ajax({
//  url: urlData,
//              crossDomain: true,
//  data: { longurl : "http://www.xibrix.ch/filmreport/view.php?id="+data.project_id },
//  success: function() {
      window.location.href = "./project.php?id="+data.project_id;
//  },
//  dataType: 'json'
//});

    }else{
    alert(data.message);
    }
}

function companyCreated(data) {
    if (data.message=="SUCCESS") {
        //$('#newCompanyCreated').html('<div class="alert alert-success">Firma wurde erstellt... Bitte Warten.</div>');
        // TODO Automatically select new one
        $.when($('#companylist').html('').load("./load_companies.php")).then(function() {
            $('#newCompany').modal('hide');
            $("#companylist").val(data.c_id);
        });
    }else{
        alert(data.message);
    }
}

function projDeleted(data) {
    if (data.message=="SUCCESS") {
        $('#deleteProjectModal').modal('hide');
        table.ajax.reload();
    }else{
        alert(data.message);
    }
}

function projFinished(data) {
    if (data.message=="SUCCESS:") {
        $('#finishProjectModal').modal('hide');
        table.ajax.reload();
    }else{
        alert(data.message);
    }
}


function setDelete(id,name){
    $('#toDelID').val(id);
    $('#delModalTitle').html("<strong>\""+name+"\"</strong> wirklich L&ouml;schen ?");
}

function setFinish(id,name){
    $('#toFinID').val(id);
    $('#finModalTitle').html(''+name);
}
