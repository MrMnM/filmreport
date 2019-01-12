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
        <i class="fa fa-download"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=xlsx"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=pdf""><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a></li>
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
            <i class="fa fa-download"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=xlsx"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</a></li>
            <li><a href="https://filmstunden.ch/api/v01/view/download/${data}?format=pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a></li>
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
