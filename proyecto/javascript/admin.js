// Definimos las variables locales 
var selector_tabla = document.querySelectorAll(".table");
var selector_borrar = document.querySelectorAll(".borrar");
var selector_ubi = document.querySelector("#barraubi a");
var tbody = selector_tabla[1].getElementsByTagName('tbody')[0];

// Sacamos la url de la pagina admin donde nos encontramos
var url = location.href;

// Comprobamos de que haya una tabla en el apartado de administrador para poder añadirle al crud un THEAD
if (selector_tabla.length != 0) {
    // Insertamos antes del TBODY
    selector_tabla[1].insertBefore(cabeceraTabla(), tbody);
} // End if de la comprobacion

// Asignamos al boton de borrar de cada tabla de crud el enlace para borrar
if (selector_borrar.length != 0) {
    for (let index = 0; index < selector_borrar.length; index++) {
        // Asignamos el evento onclick para poder borrar
        selector_borrar[index].addEventListener("click", function () {
            borrar(url.split("/")[3], selector_borrar[index]);
        });
    } // End del for
} // End if de la validacion

/**
 * Definimos la funcion borrar la cual se encarga de preguntarnos en un modal si queremos borrar o no el elemento del crud
 * @param {*} controlador ---> Le pasamos el controlador de donde venimos
 * @param {*} selector ---> Le pasamos el selector del boton de borrar para asigarle la url para borrar
 */
function borrar(controlador, selector) {
    // Preguntamos si queremos borrar el elemento o no
    if (confirm("¿Esta seguro de borrar el elemento?") == false) {
        selector.setAttribute("href", "/"+controlador);
        location = "/"+controlador;
    } // End if
} // End de la funcion de borrar

/**
 * Esta definiendo la funcion cabeceraTabla la cual se encarga de crear a través de 
 * nodo un thead e insertarlos en la tabla de los crud
 * @returns ---> THEAD con una frase de SISTEMA DE GESTION DE y un boton para AÑADIR
 */
function cabeceraTabla() {
    // Creamos los nodos correspondientes
    let thead = crearNodo("thead");
    let tr1 = crearNodo("tr");
    let th1 = crearNodo("th", "Sistema de Gestion de " + (selector_ubi.innerHTML));
    let th2 = crearNodo("th");
    let enlace = crearNodo("a", "Añadir " + selector_ubi.innerHTML);

    // Seleccionamos las columnas de la tablas en la que nos encontramos
    let columnas = document.querySelectorAll(".table tbody tr th");

    // Añadimos algunas caracteristicas al nodo TH para hacer un colSpan
    th1.colSpan = columnas.length - 1;

    // Añadir algunas caracteristicas al boton
    enlace.setAttribute("class", "btn btn-danger");
    enlace.style.backgroundColor = "#DC3545";
    enlace.setAttribute("href", "/" + url.split("/")[3].replace("#", '') + "/Crear" + selector_ubi.innerHTML);

    // Añadimos al segundo th el enlace
    th2.appendChild(enlace);

    // Añadimos a los th al tr
    tr1.appendChild(th1);
    tr1.appendChild(th2);

    // Añadimos el tr al thead
    thead.appendChild(tr1);

    // Devolvemos en el THEAD
    return thead;
} // End de la funcion cabeceraTabla

/**
 * Estamos definiendo la funcion Crear Nodo la cual se encarga de crear nodos a través del arbol DOM
 * @param {*} tipo ---> Elemento a crear
 * @param {*} texto ---> Texto que va contener ese elemento
 * @param {*} id ---> Podemos asignarle un id al elemento
 * @param {*} valor ---> Podemos asignarle un valor al elemento
 * @returns ---> Devolveremos el elemento creado
 */
function crearNodo(tipo, texto=null, id=null, valor=null) {
    // Definimos el tipo de elemento que vamos a crear 
    let type = document.createElement(tipo);

    // Comprobamos de que texto no sea nulo
    if (texto != null) {
        // Definimos el texto que va a contener
        let text = document.createTextNode(texto);

        // Le añadimos las caracteristicas
        type.appendChild(text);
    } // End de la validacion de texto

    // Comprobamos de que id no sea nulo
    if (id != null) {
        // Ahora definimos que el id que va a contener
        type.setAttribute("id", id);
    } // End de la validacion de id

    // Comprobamos de que valor no sea nulo
    if (valor != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("value", valor);
    } // End de la validacion de valor

    // Nos lo devuelve
    return type;
} // End de la función de crear un nodo