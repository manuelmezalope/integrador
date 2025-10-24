const codigo=document.getElementById('codigoProveedor').value;
const nombre=document.getElementById('nombreProveedor').value;
const telefono=document.getElementById('telefonoProveedor').value;
const direccion=document.getElementById('direccionProveedor').value;

const nombre_regex=/^[A-Z][a-zA-Z]*(?: [a-zA-Z]+)*$/;
const telefono_regex=/^9\d{8}$/;

function validarProveedor(){
    if(codigo.length!==5){
        document.getElementById('codigoProveedorP').innerHTML="codigo incorrecto";
    }

    if(!nombre.match(nombre_regex)){
        return;
    }

    if(!telefono.match(telefono_regex)){
        return;
    }
}