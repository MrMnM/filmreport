function newCreated(data) {
    if (data.message=="SUCCESS") {
      window.location.href = "./project.php?id="+data.project_id;
    }else{
    console.error(data.message);
    }
}

function companyCreated(data) {
    if (data.message=="SUCCESS") {
        // TODO Automatically select new one
        $.when($('#companylist').html('').load("./load_companies.php")).then(function() {
            $('#newCompany').modal('hide');
            $("#companylist").val(data.c_id);
        });
    }else{
    console.error(data.message);
    }
}

function projDeleted(data) {
    if (data.message=="SUCCESS:") {
        $('#deleteProjectModal').modal('hide');
        table.ajax.reload();
        console.log("refreshed");
    }else{
        console.error(data.message);
    }
}

function projFinished(data) {
    if (data.message=="SUCCESS:") {
        $('#finishProjectModal').modal('hide');
        table.ajax.reload();
    }else{
        console.error(data.message);
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
