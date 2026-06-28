// Menu hamburguesa (movil)
$(".myicono").click(function(){
	$("#navbar").toggleClass("menu-abierto");
});

// Acordeon de submenus en movil: tocar un apartado con submenu lo despliega (no navega).
$("#navbar > ul > li > a").on("click", function(e){
	var $li = $(this).parent();
	if ($li.children("ul").length && window.matchMedia("(max-width: 820px)").matches){
		e.preventDefault();
		$li.toggleClass("submenu-abierto");
	}
});

// Carrusel con scroll-snap: las flechas desplazan la pista una tarjeta.
(function(){
	var pista = document.getElementById("carruselPista");
	if (!pista) return;
	function paso(){
		var item = pista.querySelector(".carrusel-item");
		return item ? item.getBoundingClientRect().width + 16 : 240;
	}
	var izq = document.querySelector(".carrusel-flecha.izq");
	var dcha = document.querySelector(".carrusel-flecha.dcha");
	if (izq) izq.addEventListener("click", function(){ pista.scrollBy({ left: -paso(), behavior: "smooth" }); });
	if (dcha) dcha.addEventListener("click", function(){ pista.scrollBy({ left: paso(), behavior: "smooth" }); });
})();

// Anadir al carrito por AJAX + notificacion (toast), sin recargar la pagina.
function mostrarToastCarrito(nombre){
	var t = document.createElement("div");
	t.className = "toast-carrito";
	var icono = document.createElement("i");
	icono.className = "fa fa-check-circle";
	var texto = document.createElement("span");
	texto.textContent = '"' + nombre + '" añadido al carrito';
	var enlace = document.createElement("a");
	enlace.href = "cesta.php";
	enlace.textContent = "Ver cesta";
	t.appendChild(icono); t.appendChild(texto); t.appendChild(enlace);
	document.body.appendChild(t);
	requestAnimationFrame(function(){ t.classList.add("visible"); });
	setTimeout(function(){
		t.classList.remove("visible");
		setTimeout(function(){ t.remove(); }, 400);
	}, 3500);
}
document.addEventListener("submit", function(e){
	var form = e.target;
	if (!form.classList || !form.classList.contains("form-carrito")) return;
	e.preventDefault();
	fetch("agregarCarrito.php", { method: "POST", body: new FormData(form) })
		.then(function(r){ return r.json(); })
		.then(function(d){
			if (d.ok){ mostrarToastCarrito(d.name); }
			else if (d.error === "sin-sesion"){ window.location.href = "Validacion.php"; }
			else { mostrarToastCarrito("No se pudo añadir"); }
		})
		.catch(function(){ form.submit(); });   // si el AJAX falla, envio normal
});

// Validar que las dos contrasenas coinciden en el registro.
function comprobar(){
	if (document.getElementById("password").value == document.getElementById("password2").value){
		return true;
	} else {
		document.getElementById("password").style.backgroundColor = "#ff0000";
		document.getElementById("password2").style.backgroundColor = "#ff0000";
		return false;
	}
}
