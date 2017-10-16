function newCreated(data) {
    if (data.message=="SUCCESS") {
        window.location.href = "./project.php?id="+data.project_id;
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

function setDelete(id,name){
    $('#toDelID').val(id);
    $('#toDelName').html(name);
    $('#delModalTitle').html("Projekt <strong>\""+name+"\"</strong> wirklich L&ouml;schen ?");
}
