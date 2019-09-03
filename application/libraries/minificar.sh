#!/bin/bash
#PATH=assets/js/consulta

# ------------------------------------------------
# Minificacion de archivos JS
# ------------------------------------------------

#auth
minify -o assets/js/auth/auth.min.js assets/js/auth/auth.js

#consulta
minify -o assets/js/consulta/consulta.min.js assets/js/consulta/consulta.js
minify -o assets/js/consulta/masivo.min.js assets/js/consulta/masivo.js

#historico
minify -o assets/js/historico/historico.min.js assets/js/historico/historico.js

#monitoreo
minify -o assets/js/monitoreo/monitoreo.min.js assets/js/monitoreo/monitoreo.js

#notificacion
minify -o assets/js/notificacion/notificacion.min.js assets/js/notificacion/notificacion.js

#personalizar
minify -o assets/js/personalizar/personalizar.min.js assets/js/personalizar/personalizar.js

#paquetes
minify -o assets/js/paquetes/phpjs/php.min.js assets/js/paquetes/phpjs/php.js

#plantilla
minify -o assets/js/plantilla/plantilla.min.js assets/js/plantilla/plantilla.js

# ------------------------------------------------
# Minificacion de archivos CSS
# ------------------------------------------------

#auth
minify -o assets/css/auth/auth.min.css assets/css/auth/auth.css

#consulta
minify -o assets/css/consulta/consulta.min.css assets/css/consulta/consulta.css

#personalizar
minify -o assets/css/personalizar/personalizar.min.css assets/css/personalizar/personalizar.css

#plantilla
minify -o assets/css/plantilla/estilos.min.css assets/css/plantilla/estilos.css
minify -o assets/css/plantilla/menu.min.css assets/css/plantilla/menu.css
