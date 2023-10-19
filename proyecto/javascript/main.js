// Estamos realizando que cuando gane el focus el buscadpr
document.getElementById("buscador").addEventListener("focus", function () {

    // Sacamos la cadena del buscador
    let nombre_buscar = document.getElementById("buscador").value;

    // Nos aparecera los conciertos que coincidan con la cadena a partir de tercer caracter
    if (nombre_buscar.length >= 3) {
        document.getElementById("listados").style.display = "inline-block"; // Mostraremos la caja
    } // End if
    else{
        document.getElementById("listados").style.display = "none";
    } // End else

    // Estamos realziando el autocompletado en el buscador para que nos salgan todos los espectaculos disponibles
    document.getElementById("buscador").oninput = function () {

        // Sacamos la cadena del buscador
        let nombre_buscar = document.getElementById("buscador").value;

        // Nos aparecera los conciertos que coincidan con la cadena a partir de tercer caracter
        if (nombre_buscar.length >= 3) {
            document.getElementById("listados").style.display = "inline-block"; // Mostraremos la caja

            // Realizamos la conexión ajax
            $.ajax({
                type: "POST", // Indicaremos el tipo de metodo
                url: "/espectaculos/AjaxEspectaculos", // Hacemos la conexion a la accion de Productos
                data: { nombre: nombre_buscar }, // Enviamos datos
                dataType: "json", // Como enviamos los datos

                // En caso de que se hecho la conexion correcta mostraremos los espectaculos en un parrafo
                success: function (response) {

                    // Obtenemos el parrafo donde insertaremos los datos
                    var selector_p = document.querySelectorAll("#listados p");

                    // Comprobamos de que correcto sea verdadero en caso contrario no mostraremos nada
                    if (response.correcto === true) {

                        // Comprobamos de que si el div listados tiene hijos
                        if (document.getElementById("listados").children.length > 0) {

                            // Recorremos los selectores p
                            for (let key of selector_p) {
                                document.getElementById("listados").removeChild(key);
                            } // End for

                        } // End if

                        // Recorremos los datos que recibimos del ajax
                        for (const key in response.datos) {
                            // Creamos el nodo
                            let parrafo = crearNodo("p", response.datos[key].titulo, null, null, null);

                            // Añadimos el evento hoover para añadir al input text
                            parrafo.onmouseover = function () {
                                document.getElementById("buscador").value = response.datos[key].titulo;
                            } // End del evento onmouseover

                            // Lo añadimos los parrafos de los titulos 
                            document.getElementById("listados").appendChild(parrafo);

                        } // End for
                    } // End if
                    else {
                        // Comprobamos 
                        if (document.getElementById("listados").children.length > 0) {

                            // Recorremos los selectores
                            for (let key of selector_p) {
                                document.getElementById("listados").removeChild(key);
                            } // End for

                        } // End if

                        // Creamos los nodos parrafos
                        let parrafo = crearNodo("p", response.datos, null, null, null);

                        // Añadimos el parrafo al div
                        document.getElementById("listados").appendChild(parrafo);
                    } // End else
                },
                // En caso de que no se haya hecho bien la conexion AJAX mostraremos los errores por consola
                error: function (error) {
                    console.error("Se ha producido un error");
                    console.error(error);
                } // End del error
            });
        } // End if
    } // End if del autocompletar
})

// En caso de que perdimos el foco deshabilitamos el nodo
document.getElementById("buscador").addEventListener("blur", function () {
    document.getElementById("listados").style.display = "none";
});

// Comprobamos de que exista el ID mostrar_productos
if (document.getElementById("mostrar_productos")){

    // Definimos nuestras variables locales
    var sumatorio = 0;

    // Realizamos la conexion AJAX y hacemos por el metodo GET
    $.ajax({
        type: "GET", // Indicaremos el tipo de metodo
        url: "/cataproducto/ObtenerProductos", // Hacemos la conexion a la accion de Productos
        data: "", // Enviamos datos
        dataType: "json", // Como enviamos los datos

        // En caso de que se hecho la conexion correcta mostraremos los productos en un card
        success: function (response) {
            // Comprobamos de que correcto sea verdadero en caso contrario no mostraremos nada
            if (response.correcto === true){
                // Recorremos los datos de productos a traves de un for in
                for (const key in response.datos) {
                    // Creamos dos nodos de tipo DIV y img. A la imagen le añadidos el src y un alt
                    let div1 = crearNodo("div", null, null, "card", null);
                    let img = crearNodo("img", null, null, "card-img-top", null);
                    img.setAttribute("src", response.datos[key].imagen);
                    img.setAttribute("alt", response.datos[key].nombre_producto);
    
                    // Creamos varios nodos donde mostraremos el nombre, precio y unidades del Producto en un card-body
                    let div2 = crearNodo("div", null, null, "card-body", null);
                    let h5 = crearNodo("h5", response.datos[key].nombre_producto, null, "card-title", null);
                    let precio = crearNodo("h5", "Precio: "+response.datos[key].precio+"€", null, "card-title", null);
                    let unidad = crearNodo("h5", "Unidades: "+response.datos[key].unidades, null, "card-title", null);
    
                    // Añadimos los tres apartados al div del card-body 
                    div2.appendChild(h5);
                    div2.appendChild(precio);
                    div2.appendChild(unidad);

                    // Aqui lo que haremos seria añadir un formulario oculto con los datos del producto para enviarlo al servidor
                    div2.appendChild(formulario(response.datos[key].cod_producto, response.datos[key].unidades));
    
                    // Añadimos la imagen del producto y el div con sus datos mostrados
                    div1.appendChild(img);
                    div1.appendChild(div2);
    
                    // Añadimos los productos a div padre a traves del ID mostrar_productos
                    document.getElementById("mostrar_productos").appendChild(div1);
                } // End del for in
            } // End del for in
        }, // End del success

        // En caso de que no se haya hecho bien la conexion AJAX mostraremos los errores por consola
        error: function (error) {
            console.error("Se ha producido un error");
            console.error(error);
        } // End del error
    }); // End de la conexion AJAX
} // End de la existencia del ID mostrar_productos

// Comprobamos de que exista la barra de ubicacion
if (document.getElementById("barraubi")){
    // Si esta vacio la ocultamos
    if (document.getElementById("barraubi").childNodes.length == 1){
        document.getElementById("barraubi").style.display = "none";
    } // End if 2
} // End if 1

/**
 * Estamos definiendo la funcion Crear Nodo la cual se encarga de crear nodos a través del arbol DOM
 * @param {*} tipo ---> Elemento a crear
 * @param {*} texto ---> Texto que va contener ese elemento
 * @param {*} id ---> Podemos asignarle un id al elemento
 * @param {*} clase ---> Podemos asignarle una clase al elemento
 * @param {*} valor ---> Podemos asignarle un valor al elemento
 * @returns ---> Devolveremos el elemento creado
 */
function crearNodo(tipo, texto=null, id=null, clase=null, valor=null) {
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

    // Comprobamos de que clase no sea nulo
    if (clase != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("class", clase);
    } // End de la validacion de clase

    // Comprobamos de que valor no sea nulo
    if (valor != null) {
        // Ahora definimos el value que va a contener
        type.setAttribute("value", valor);
    } // End de la validacion de valor

    // Nos lo devuelve
    return type;
} // End de la función de crear un nodo