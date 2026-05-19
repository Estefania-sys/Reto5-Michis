document.addEventListener("DOMContentLoaded", () => {
    // Obtenemos el idioma que está guardado, o usamos 'es' (ESPAÑOL) por defecto.
    const currentLang = localStorage.getItem("preferred-lang") || "es";
    // Aplicamos el idioma al cargar la página.
    setLanguage(currentLang);
    // 
    const langButtons = document.querySelectorAll(".btn-lang");
    langButtons.forEach(button =>{
        button.addEventListener("click", () => {
            const selectedLang = button.getAttribute("data-lang");
            // Guardamos la preferencia del idioma en el valor.
            localStorage.setItem("preferred-lang", selectedLang);
            // Recargado automático de la página para aplicar el idioma.
            window.location.reload();
        });
    });
});

// Función para mostrar u ocultar los textos según el idioma que seleccionemos.
function setLanguage(lang){
    // Cambiamos el atributo 'lang' del HTML.
    document.documentElement.lang = lang;
    // Buscamos todos los elementos que tengan la clase 'traductor'
    const elements = document.querySelectorAll(".traductor");
    elements.forEach(element=>{
        // Si el elemento coincide con el idioma que hemos seleccionado, lo mostramos
        if (element.getAttribute("lang") === lang){
            element.style.display = "block";
        } else {
            // Si el elemento no coincide, lo escondemos
            element.style.display = "none";
        }
    });
}
