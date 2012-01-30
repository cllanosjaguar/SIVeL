#!/bin/sh
# Elimina un rango de casos.
# Forma de uso desde el directorio del sitio para eliminar todos los casos 
# con c�digos entre el 123 y el 897:
#	../../bin/elim-rango.sh 123 897
# Dominio p�blico. 2010. vtamara@pasosdeJesus.org

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

. ../../bin/elimcomun.sh

# Primer caso por eliminar
li=$1
# �ltimo caso por eliminar
ls=$2

if (test "$li" = "") then {
	echo "Falta primer caso por eliminar como primer par�metro";
	exit 1;
} fi;
if (test "$ls" = "") then {
	echo "Falta �ltimo caso por eliminar como segundo par�metro";
	exit 1;
} fi;
echo "Este script eliminar� casos entre $li y $ls.  Confirma continuar (s para continuar)";
read sn
if (test "$sn" = "s") then {
	../../bin/psql.sh -c "drop view vpreserva" > /dev/null 2> /dev/null
	../../bin/psql.sh -c "create view vpreserva as (select distinct id as id_caso from caso where id<'$li' or id>'$ls')";
	eliminafuera vpreserva;
	../../bin/psql.sh -c "drop view vpreserva" > /dev/null 2> /dev/null
} fi;
