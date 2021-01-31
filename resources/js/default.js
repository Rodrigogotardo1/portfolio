function teste() {
    alert("Funcionou");
}

function oneError(id) {
    let value = this.valueId(id);

    let pathDOC = "";
    switch (extension(value)) {
        case 'doc':
            pathDOC = './resources/img/capa/doc.png';
            break;

        case 'docx':
            pathDOC = './resources/img/capa/docx.png';
            break;

        case 'pdf':
            pathDOC = './resources/img/capa/pdf.png';
            break;

        case 'odt':
            pathDOC = './resources/img/capa/odt.png';
            break;

    }


    document.getElementById(value).src = pathDOC;


    console.log(extension(value));
}

function extension(value) {
    let ext = value.split('.');
    return ext[ext.length - 1];
}

function deleteFile(id) {
    let file = document.getElementById(id).id;
    console.log(file);
    document.querySelector("[name='delete']").value = file;
}


function selectImage(id) {

    let name =  this.valueId(id);
    let dir = document.getElementById(id).src;
    let path = "";

    function constructPath(value, index, array) {
        if (index == 1) {
            path += "/";
        } else if (index > 1) {
            if (index < array.length - 1) {
                path += "/" + value;
            }
        } else {
            path += value;
        }

    }

    dir.split('/').forEach(constructPath);

    path = path + '/' + name;
    console.log(path);

    document.getElementById('viewImage').src = path;
    // document.getElementById('imgId').innerHTML = name;
    document.querySelector("[name='imagePath']").value = name;
}

function valueId(id) {
    return  document.getElementById(id).id;

}
function selectFile(id) {

    let name = document.getElementById(id).id;
    let dir = document.getElementById(id).src;
    let path = "";

    function constructPath(value, index, array) {
        if (index == 1) {
            path += "/";
        } else if (index > 1) {
            if (index < array.length - 1) {
                path += "/" + value;
            }
        } else {
            path += value;
        }

    }

    dir.split('/').forEach(constructPath);


    path = path + '/' + name;
    console.log();

    let pathDOC = "";
    switch (extension(name)) {
        case 'doc':
            pathDOC = './resources/img/capa/doc.png';
            break;

        case 'docx':
            pathDOC = './resources/img/capa/docx.png';
            break;

        case 'pdf':
            pathDOC = './resources/img/capa/pdf.png';
            break;

        case 'odt':
            pathDOC = './resources/img/capa/odt.png';
            break;

    }


    document.getElementById('viewFile').src = pathDOC;


    document.querySelector("[name='filePath']").value = name;
    // document.getElementById('fileId').innerHTML = name;
}

function oneModified(id) {
    let val = this.valueId(id);

    let pathDOC = "";
    switch (extension(val)) {
        case 'doc':
            pathDOC = './resources/img/capa/doc.png';
            break;

        case 'docx':
            pathDOC = './resources/img/capa/docx.png';
            break;

        case 'pdf':
            pathDOC = './resources/img/capa/pdf.png';
            break;

        case 'odt':
            pathDOC = './resources/img/capa/odt.png';
            break;

    }


    document.getElementById(value).src = pathDOC;


    console.log(extension(val));
}


function extension(file) {
    let ext = file.split('.');
    return ext[ext.length - 1];
}
