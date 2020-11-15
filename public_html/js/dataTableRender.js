export function renderTools (data, type, row, mode) {
    let active = `
    <button type="button" class="btn btn-default btn-circle" onclick="window.open('view.php?id=${data}')">
        <i class="fa fa-eye"></i>
    </button>
    <button type="button" class="btn btn-default btn-circle" onclick="window.location.href='project.php?id=${data}'">
        <i class="fa fa-pencil"></i>
    </button>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-circle dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-share-alt-square"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="#sendMailModal" data-toggle="modal" data-target="#sendMailModal" onclick="setMail('${data}','${row[1]}')"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Mail</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=xlsx"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=pdf""><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</a></li>
        </ul>
    </div>
    <button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProjectModal" onclick="setDelete('${data}','${row[1]}')">
        <i class="fa fa-times"></i>
    </button>
    <button type="button" class="btn btn-success btn-circle" data-toggle="modal" data-target="#finishProjectModal" onclick="setFinish('${data}','${row[1]}')">
        <i class="fa fa-check"></i>
    </button>
    `
    let archive = `
    <button type="button" class="btn btn-default btn-circle" onclick="window.open('view.php?id=${data}')">
        <i class="fa fa-eye"></i>
    </button>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-circle dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-share-alt-square"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="#sendMailModal" data-toggle="modal" data-target="#sendMailModal" onclick="setMail('${data}','${row[1]}')"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Mail</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=xlsx"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download Excel</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=pdf""><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</a></li>
        </ul>
    </div>
    <button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteProjectModal" onclick="setDelete('${data}','${row[1]}')">
        <i class="fa fa-times"></i>
    </button>`
    return (mode==1) ? active : archive
}

export function renderTitle (data, type, row, mode) {
    let active = '<a href="project.php?id=' + row[5] + '"><b>' + row[1] + '</b></a>'
    let archive = '<a href="view.php?id=' + row[5] + '"><b>' + row[1] + '</b></a>'
    return (mode==1) ? active : archive
}

export function renderEnquiry(data, type, row) {
    let cur = '<a href="view_enquiry.php?id=' + row[3] + '"><b>' + row[1] + '</b></a>'
    return cur
}

export function renderEnquiryTools (data, type, row) {
    let tools = `
        <button type="button" class="btn btn-default btn-circle" onclick="window.open('view_enquiry.php?id=${data}')">
            <i class="fa fa-eye"></i>
        </button>
        <button type="button" class="btn btn-default btn-circle" onclick="window.open('https://filmstunden.ch/api/v01/enquiries/${data}/ics')">
            <i class="fa fa-calendar"></i>
        </button>
        <button type="button" class="btn btn-danger btn-circle" data-toggle="modal" data-target="#deleteEnquiry" onclick="setDelete('${data}','${row[3]}')">
            <i class="fa fa-times"></i>
        </button>`
    return tools
}