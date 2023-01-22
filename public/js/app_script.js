
                            /* HOMEPAGE */

// init Isotope
var $grid = $('.collection-list').isotope({
    // options
});
  
// filter items on button click
$('.filter-button-group').on( 'click', 'button', function() {
    var filterValue = $(this).attr('data-filter');
    resetFilterBtns();
    $(this).addClass('active-filter-btn');
    $grid.isotope({ filter: filterValue });
});
  
var filterBtns = $('.filter-button-group').find('button');
    function resetFilterBtns(){
        filterBtns.each(function(){
        $(this).removeClass('active-filter-btn');
    });
}



                            /* SHOW (Producto) */

//Carrito
let DB;

window.addEventListener('load', () =>{

    window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
    window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;

    document.querySelector('#botonAñadirCarrito').addEventListener('click', () =>{

        $.ajax({
            url:$("#path-to-controller").data("href"),
            type: "POST",
            dataType: "json",
            data: {
                "idProducto": "some_var_value"
            },
            async: true,

            /* error: function() {
                console.log("Error");
            }, */

            success: function (data)
            {                
                crear_carritoDB();
                setTimeout( () => {
                    aniadirProducto(JSON.parse(data));
                }, 3000);
     
            }
        });

    });

});

function crear_carritoDB() {
    // crear base de datos con la versión 1
    let carritoDB = window.indexedDB.open('carrito', 1);

    carritoDB.onerror = function() {
        console.log('Hubo un error');
    }
    
    carritoDB.onsuccess = function() {
        //Guardamos en DB el objeto resultante de crear nuestra base de datos exitosamente. Actualiza nuestra variable "DB" declarada anteriormente y nos sirve para luego abrir transacciones y añadir productos.
        DB = carritoDB.result;

    }

    carritoDB.onupgradeneeded = function(e) {
        let db = e.target.result;

        //Creamos una tabla en nuestra DB
        let tabla_ProductosCarrito = db.createObjectStore('carrito', { keyPath: 'idCarro',  autoIncrement: true } );

        //createindex --> Creamos las columnas de nuestra DB
        tabla_ProductosCarrito.createIndex('id', 'id', { unique: true } );
        tabla_ProductosCarrito.createIndex('nombre', 'nombre', { unique: false } );
        tabla_ProductosCarrito.createIndex('precio', 'precio', { unique: false } );
        tabla_ProductosCarrito.createIndex('descuento', 'descuento', { unique: false } );
        tabla_ProductosCarrito.createIndex('imagen', 'imagen', { unique: false } );
    }
}


function aniadirProducto(producto) {
    // Crear un nuevo registro
    let transaction = DB.transaction(['carrito'], 'readwrite');

    transaction.objectStore('carrito').add(producto);

    transaction.oncomplete = function(event) {
        /**** AQUÍ METER --> script para cambiar símbolito encima del carrito +1  ****/
        console.log('Transacción Completada');
    };
    
    transaction.onerror = function(event) {
        /****   AQUÍ METER --> Mostrar un alert o mensaje de que no se pudo añadir el producto  ****/
        console.log('Hubo un error en la transacción')
    };
}


function eliminarProducto(id_producto) {
    let transaction = DB.transaction(['carrito'], 'readwrite');

    let tabla = transaction.objectStore('carrito');

    tabla.openCursor().onsuccess = function(event) {
        let cursor = event.target.result;
        if(cursor) {
            if(cursor.value.id === id_producto) {
                let request = cursor.delete();
                request.onsuccess = function() {
                    /*METER CÓDIGO PARA BORRAR ELEMENTO HTML DEL PRODUCTO. Algo así:
                        const list = document.getElementById("myList");
                        const element= document.getElementById($id_producto);
                        list.removeChild(element);  
                    */
                };
            } 
            cursor.continue();        
        }else{
            console.log('Entries displayed.'); //MIRAR         
        }
      };
}

//Se ejecutará cuando ya se haya formalizado la compra y se borre todo el carrito.
function borrarDB(){
    const DBDeleteRequest = window.indexedDB.deleteDatabase('carrito');

    DBDeleteRequest.onerror = (event) => {
    console.error("Error deleting database.");
    };

    DBDeleteRequest.onsuccess = (event) => {
    console.log("Database deleted successfully");

    console.log(event.result); // should be undefined
    };
}