# Elimina b�licas de la base de datos
# Dominio p�blico. 2008. vtamara@pasosdeJesus.org

if (test ! -f ./vardb.sh -o ! -f conf.php) then {
	echo "Ejecute desde directorio del sitio";
	exit 1;
} fi;

. ./vardb.sh

. ../../bin/elimcomun.sh

echo "Este script eliminar� clasificaci�n de b�licas de casos as� como casos que s�lo tengan clasificaci�n de b�licas (y las v�ctimas que pudieran tener erradamente  asociadas.  Confirma continuar (s para continuar)";
read sn
if (test "$sn" = "s") then {
	../../bin/psql.sh -c "delete from antecedente_combatiente;"
	../../bin/psql.sh -c "delete from p_responsable_agrede_combatiente;"
	../../bin/psql.sh -c "delete from combatiente;"
	../../bin/psql.sh -c "delete from combatiente;"
	../../bin/psql.sh -c "delete from categoria_p_responsable_caso where id_tipo_violencia='C';"
	../../bin/psql.sh -c "delete from categoria_comunidad where id_tipo_violencia='C';"
	../../bin/psql.sh -c "delete from categoria_caso where id_tipo_violencia='C';"

	../../bin/psql.sh -c "drop view nobelicas" > /dev/null 2> /dev/null
	../../bin/psql.sh -c "CREATE VIEW nobelicas AS (SELECT id_caso FROM acto UNION SELECT id_caso FROM actocolectivo UNION SELECT id_caso FROM categoria_p_responsable_caso) ORDER BY 1";
	eliminafuera nobelicas;
} fi;
