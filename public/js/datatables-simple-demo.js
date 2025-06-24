window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        // Inicializar Simple-DataTables con opciones personalizadas
        new simpleDatatables.DataTable(datatablesSimple, {
            labels: {
                placeholder: "Buscar...", // Texto de marcador de posición de búsqueda
                perPage: "registros por página", // Etiqueta para el selector de entradas por página
                noRows: "No se encontraron entradas", // Mensaje cuando no hay filas
                info: "Mostrando {start} a {end} de {rows} registros", // Información de paginación
                search: "Buscar:", // Etiqueta para el campo de búsqueda
                paginate: {
                    first: "Primero", // Etiqueta para el botón "Primera página"
                    last: "Último", // Etiqueta para el botón "Última página"
                    next: "Siguiente", // Etiqueta para el botón "Siguiente página"
                    prev: "Anterior" // Etiqueta para el botón "Página anterior"
                }
            },
            // Puedes añadir otras opciones aquí si las necesitas, por ejemplo:
            // perPageSelect: [5, 10, 15, 20, 25, 50, ["All", -1]], // Opciones de entradas por página
            // sortable: true, // Habilitar/deshabilitar la ordenación por columna
            // searchable: true, // Habilitar/deshabilitar la búsqueda
            // fixedColumns: false, // Columnas fijas
            // fixedHeader: false, // Encabezado fijo
            // footer: false, // Deshabilitar pie de tabla
        });
    }
});
